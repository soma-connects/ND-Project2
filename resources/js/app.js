document.addEventListener('DOMContentLoaded', () => {
    console.log('app.js loaded successfully');

    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) console.error('CSRF token not found.');

    // Helper: Show notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const mobileNav = document.querySelector('.mobile-nav');

    if (menuToggle && mobileNav) {
        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent click from triggering outside handler
            mobileNav.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            if (mobileNav.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
                console.log('Mobile nav opened');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
                console.log('Mobile nav closed');
            }
        });

        // Close mobile nav when clicking outside
        document.addEventListener('click', (e) => {
            if (mobileNav.classList.contains('active') &&
                !menuToggle.contains(e.target) &&
                !mobileNav.contains(e.target)) {
                mobileNav.classList.remove('active');
                const icon = menuToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
                console.log('Mobile nav closed (outside click)');
            }
        });

        // Handle window resize to reset menu state
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                mobileNav.classList.remove('active');
                const icon = menuToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
                console.log('Mobile nav reset on resize to desktop');
            }
        });
    } else {
        console.error('Menu toggle or mobile nav not found:', { menuToggle, mobileNav });
    }

    // Close Alerts
    document.querySelectorAll('.close').forEach(button => {
        button.addEventListener('click', function () {
            const alert = this.parentElement;
            alert.classList.add('fade-out');
            setTimeout(() => alert.remove(), 500);
        });
    });

    // Search Bar Functionality
    const searchForms = document.querySelectorAll('.search-form');
    searchForms.forEach(form => {
        const input = form.querySelector('input[name="q"]');
        const resultsContainer = document.createElement('div');
        resultsContainer.className = 'search-results';
        form.appendChild(resultsContainer);

        input.addEventListener('input', async () => {
            const query = input.value.trim();
            if (query.length < 2) {
                resultsContainer.classList.remove('active');
                return;
            }

            try {
                const response = await fetch(`/search?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                resultsContainer.innerHTML = '';
                if (data.products && data.products.data.length) {
                    data.products.data.forEach(product => {
                        const item = document.createElement('a');
                        item.className = 'result-item';
                        item.href = `/products/${product.id}`;
                        item.innerHTML = `
                            <img src="${product.image || '/storage/images/placeholder.jpg'}" alt="${product.name}">
                            <span>${product.name} - $${product.price.toFixed(2)}</span>
                        `;
                        resultsContainer.appendChild(item);
                    });
                    resultsContainer.classList.add('active');
                } else {
                    resultsContainer.innerHTML = '<div class="result-item">No results found.</div>';
                    resultsContainer.classList.add('active');
                }
            } catch (error) {
                console.error('Search error:', error);
                resultsContainer.innerHTML = '<div class="result-item">Error performing search.</div>';
                resultsContainer.classList.add('active');
            }
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const query = input.value.trim();
            if (!query) {
                showNotification('Please enter a search term.', 'error');
                return;
            }

            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                window.location.href = `/search?q=${encodeURIComponent(query)}`;
            } catch (error) {
                showNotification('Error performing search.', 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-search"></i>';
            }
        });

        document.addEventListener('click', e => {
            if (!form.contains(e.target)) {
                resultsContainer.classList.remove('active');
            }
        });
    });

    // Add to Cart
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                const response = await fetch(form.action, {
                    method: form.querySelector('input[name="_method"]')?.value || 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    document.querySelectorAll('.cart-count').forEach(el => el.textContent = data.cartCount);
                    showNotification(data.message || 'Product added to cart.');
                    if (form.closest('.quick-view-modal')) {
                        form.closest('.quick-view-modal').classList.remove('active');
                    }
                } else {
                    showNotification(data.message || 'Failed to add to cart.', 'error');
                }
            } catch (error) {
                showNotification(`Error: ${error.message}`, 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    });

    // Update Cart
    document.querySelectorAll('.quantity-selector input').forEach(input => {
        input.addEventListener('change', async () => {
            const form = input.closest('form');
            if (!form) return;

            const formData = new FormData(form);
            const productId = formData.get('product_id');
            const quantity = parseInt(formData.get('quantity'));
            if (isNaN(quantity) || quantity < 1) {
                showNotification('Quantity must be at least 1.', 'error');
                return;
            }

            try {
                const response = await fetch(form.action, {
                    method: form.querySelector('input[name="_method"]')?.value || 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    document.querySelectorAll('.cart-count').forEach(el => el.textContent = data.cartCount);
                    document.querySelector(`.cart-item[data-product-id="${productId}"] .cart-item-total`)?.textContent = `$${data.item_total.toFixed(2)}`;
                    document.querySelector('.summary-row .subtotal')?.textContent = `$${data.subtotal.toFixed(2)}`;
                    document.querySelector('.total-row .total')?.textContent = `$${data.total.toFixed(2)}`;
                    document.querySelector('.summary-row .shipping')?.textContent = data.subtotal >= 50 ? 'Free' : '$5.00';
                    showNotification(data.message || 'Cart updated.');
                } else {
                    showNotification(data.message || 'Failed to update cart.', 'error');
                }
            } catch (error) {
                showNotification(`Error: ${error.message}`, 'error');
            }
        });
    });

    // Remove from Cart
    document.querySelectorAll('.remove-item-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const productId = formData.get('product_id');

            try {
                const response = await fetch(form.action, {
                    method: form.querySelector('input[name="_method"]')?.value || 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    document.querySelector(`.cart-item[data-product-id="${productId}"]`)?.remove();
                    document.querySelectorAll('.cart-count').forEach(el => el.textContent = data.cartCount);
                    document.querySelector('.summary-row .subtotal')?.textContent = `$${data.subtotal.toFixed(2)}`;
                    document.querySelector('.total-row .total')?.textContent = `$${data.total.toFixed(2)}`;
                    document.querySelector('.summary-row .shipping')?.textContent = data.subtotal >= 50 ? 'Free' : '$5.00';
                    showNotification(data.message || 'Item removed from cart.');
                    if (!document.querySelector('.cart-item')) {
                        document.querySelector('.cart-items')?.innerHTML = '<p class="text-gray-600 text-center py-12">Your cart is empty.</p>';
                    }
                } else {
                    showNotification(data.message || 'Failed to remove item.', 'error');
                }
            } catch (error) {
                showNotification(`Error: ${error.message}`, 'error');
            }
        });
    });

    // Quick View
    const quickViewButtons = document.querySelectorAll('.quick-view-btn');
    const quickViewModal = document.querySelector('.quick-view-modal');
    const quickViewContent = document.querySelector('.quick-view-content');
    const closeModal = document.querySelector('.close-modal');

    if (quickViewButtons.length && quickViewModal && quickViewContent) {
        quickViewButtons.forEach(button => {
            button.addEventListener('click', async () => {
                const productId = button.dataset.productId;
                try {
                    const response = await fetch(`/api/products/${productId}`, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        quickViewContent.querySelector('.quick-view-image').innerHTML = `<img src="${data.image || '/storage/images/placeholder.jpg'}" alt="${data.name}" class="product-image" loading="lazy">`;
                        quickViewContent.querySelector('.quick-view-details').innerHTML = `
                            <h3>${data.name}</h3>
                            <p class="price">$${data.price.toFixed(2)}</p>
                            <div class="product-rating">
                                <span class="stars">★ ${data.average_rating || '0.0'}</span>
                                <span class="review-count">(${data.review_count || 0} reviews)</span>
                            </div>
                            <p class="sku">SKU: ${data.sku || 'N/A'}</p>
                            <p>${data.description || 'No description available.'}</p>
                            <form class="add-to-cart-form" action="/cart/add" method="POST">
                                <input type="hidden" name="product_id" value="${data.id}">
                                <input type="number" name="quantity" value="1" min="1" max="${data.stock || 10}" class="w-16 p-1 border rounded">
                                <button type="submit" class="btn btn-primary add-to-cart" ${data.stock === 0 ? 'disabled' : ''}>Add to Cart</button>
                            </form>
                        `;
                        quickViewModal.classList.add('active');
                    } else {
                        showNotification(data.message || 'Failed to load product.', 'error');
                    }
                } catch (error) {
                    showNotification(`Error loading product: ${error.message}`, 'error');
                }
            });
        });

        if (closeModal) {
            closeModal.addEventListener('click', () => quickViewModal.classList.remove('active'));
            quickViewModal.addEventListener('click', e => {
                if (e.target === quickViewModal) quickViewModal.classList.remove('active');
            });
        }
    }

    // Checkout Form
    document.querySelectorAll('#checkout-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });
                const data = await response.json();
                if (data.success) {
                    showNotification(data.message || 'Order placed successfully. Awaiting payment verification.', 'success');
                    window.location.href = data.redirect || `/order/confirmation/${data.order_id}`;
                } else {
                    showNotification(data.message || 'Failed to place order.', 'error');
                }
            } catch (error) {
                showNotification('Failed to submit order: ' + error.message, 'error');
                console.error('Checkout error:', error);
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = 'Submit Payment Receipt';
            }
        });
    });

    // Password Visibility Toggle
    document.querySelectorAll('.form-group input[type="password"]').forEach(input => {
        const toggle = document.createElement('span');
        toggle.className = 'password-toggle';
        toggle.innerHTML = '<i class="fas fa-eye"></i>';
        input.parentNode.style.position = 'relative';
        input.parentNode.appendChild(toggle);

        toggle.addEventListener('click', () => {
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
            toggle.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    });

    // Sorting
    const sortBy = document.getElementById('sort-by');
    if (sortBy) {
        sortBy.addEventListener('change', () => {
            const url = new URL(window.location);
            url.searchParams.set('sort', sortBy.value);
            window.location = url.toString();
        });
    }
});