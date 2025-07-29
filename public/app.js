document.addEventListener('DOMContentLoaded', () => {
    console.log('app.js loaded successfully');

    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) console.error('CSRF token not found.');

    // Helper: Show notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type}`;
        notification.innerHTML = `${message} <button class="close">×</button>`;
        const container = document.querySelector('.container') || document.body;
        container.prepend(notification);
        const closeButton = notification.querySelector('.close');
        if (closeButton) {
            closeButton.addEventListener('click', () => {
                notification.classList.add('fade-out');
                setTimeout(() => notification.remove(), 500);
            });
        }
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 500);
        }, 3000);
    }

    // Helper: Update Cart Count
    function updateCartCount() {
        fetch('/cart/count', {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            document.querySelectorAll('.cart-count').forEach(el => {
                el.textContent = data.count || 0;
            });
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
            showNotification('Failed to update cart count.', 'error');
        });
    }

    // Helper: Update Cart Summary
    function updateCartSummary() {
        fetch('/cart/summary', {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            const subtotal = document.querySelector('.summary-row .subtotal');
            const total = document.querySelector('.summary-row.total span');
            if (subtotal) subtotal.textContent = `$${parseFloat(data.subtotal).toFixed(2)}`;
            if (total) total.textContent = `$${parseFloat(data.total).toFixed(2)}`;
            const shipping = document.querySelector('.summary-row .shipping');
            if (shipping) shipping.textContent = data.subtotal >= 50 ? 'Free' : '$5.00';
        })
        .catch(error => {
            console.error('Error updating cart summary:', error);
            showNotification('Failed to update cart summary.', 'error');
        });
    }

    // --- Sidebar Toggle for Admin ---
    const sidebarToggle = document.querySelector('.menu-toggle.toggle-edit');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    if (sidebarToggle && sidebar && mainContent) {
        console.log('Admin sidebar toggle elements found');

        // Initialize sidebar state based on screen size
        function initializeSidebar() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('collapsed');
                sidebar.classList.add('active');
                mainContent.classList.add('sidebar-active');
                sidebarToggle.setAttribute('aria-expanded', 'true');
                const icon = sidebarToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                }
            } else {
                sidebar.classList.remove('active');
                sidebar.classList.add('collapsed');
                mainContent.classList.remove('sidebar-active');
                sidebarToggle.setAttribute('aria-expanded', 'false');
                const icon = sidebarToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        }

        // Run on page load
        initializeSidebar();

        // Update on window resize
        window.addEventListener('resize', initializeSidebar);

        // Toggle sidebar on button click
        sidebarToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            const isActive = sidebar.classList.toggle('active');
            sidebar.classList.toggle('collapsed', !isActive);
            if (window.innerWidth >= 1024) {
                mainContent.classList.toggle('sidebar-active', isActive);
            }
            sidebarToggle.setAttribute('aria-expanded', isActive);
            const icon = sidebarToggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars', !isActive);
                icon.classList.toggle('fa-times', isActive);
            }
        });

        // Close sidebar when clicking outside (mobile only)
        document.addEventListener('click', (event) => {
            if (window.innerWidth < 1024 && sidebar.classList.contains('active') && !sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('active');
                sidebar.classList.add('collapsed');
                mainContent.classList.remove('sidebar-active');
                sidebarToggle.setAttribute('aria-expanded', 'false');
                const icon = sidebarToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    } else {
        console.warn('Admin sidebar toggle elements not found');
    }

    // --- Mobile Menu Toggle for Non-Admin Pages ---
    const mobileMenuToggle = document.querySelector('.menu-toggle:not(.toggle-edit)');
    const mobileNav = document.querySelector('.mobile-nav');
    const mobileNavBackdrop = document.querySelector('.mobile-nav-backdrop');
    const mobileNavClose = document.querySelector('.mobile-nav-close');

    if (mobileMenuToggle && mobileNav && mobileNavBackdrop) {
        console.log('Mobile menu toggle elements found');

        mobileMenuToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            const isActive = mobileNav.classList.toggle('active');
            mobileNavBackdrop.classList.toggle('active', isActive);
            mobileMenuToggle.setAttribute('aria-expanded', isActive);
            const icon = mobileMenuToggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars', !isActive);
                icon.classList.toggle('fa-times', isActive);
            }
        });

        if (mobileNavClose) {
            mobileNavClose.addEventListener('click', (event) => {
                event.stopPropagation();
                mobileNav.classList.remove('active');
                mobileNavBackdrop.classList.remove('active');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                const icon = mobileMenuToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
        }

        document.addEventListener('click', (event) => {
            if (mobileNav.classList.contains('active') && !mobileNav.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                mobileNav.classList.remove('active');
                mobileNavBackdrop.classList.remove('active');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                const icon = mobileMenuToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    } else {
        console.warn('Mobile menu toggle elements not found');
    }

    // Close Alerts
    document.querySelectorAll('.close').forEach(button => {
        button.addEventListener('click', function () {
            this.parentElement.classList.add('fade-out');
            setTimeout(() => this.parentElement.remove(), 500);
        });
    });

    // Search Bar Functionality
    document.querySelectorAll('.search-form').forEach(form => {
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
                        item.innerHTML = `<img src="${product.image || '/storage/images/placeholder.jpg'}" alt="${product.name}"> <span>${product.name} - $${product.price.toFixed(2)}</span>`;
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

            const isUpdate = form.action.includes('/cart/update');
            const cartItemId = isUpdate ? form.closest('.cart-item').getAttribute('data-product-id') : formData.get('product_id');
            const action = isUpdate ? `/cart/update/${cartItemId}` : '/cart/add';

            console.log('Cart action:', { action, cartItemId });

            if (!cartItemId && isUpdate) {
                showNotification('Error: Invalid product ID. Please try again.', 'error');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
                return;
            }

            try {
                const response = await fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();
                if (data.success) {
                    if (isUpdate) {
                        const cartItem = form.closest('.cart-item');
                        const totalElement = cartItem.querySelector('.cart-item-total');
                        if (totalElement) {
                            totalElement.textContent = `Subtotal: $${parseFloat(data.itemTotal).toFixed(2)}`;
                        }
                        updateCartCount();
                        updateCartSummary();
                        showNotification(data.message || 'Cart updated successfully.', 'success');
                    } else {
                        document.querySelectorAll('.cart-count').forEach(el => el.textContent = data.cartCount);
                        showNotification(data.message || 'Product added to cart.', 'success');
                        if (form.closest('.quick-view-modal')) {
                            form.closest('.quick-view-modal').classList.remove('active');
                        }
                    }
                } else {
                    showNotification(data.message || (isUpdate ? 'Failed to update cart.' : 'Failed to add to cart.'), 'error');
                }
            } catch (error) {
                console.error('Cart error:', error);
                showNotification(`Error: ${error.message}. Please try again or contact support.`, 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });

        // Quantity Increment/Decrement
        form.querySelectorAll('.quantity-increment, .quantity-decrement').forEach(button => {
            button.addEventListener('click', () => {
                const input = form.querySelector('.quantity-input');
                let value = parseInt(input.value) || 1;
                if (button.classList.contains('quantity-increment')) {
                    input.value = value + 1;
                } else if (button.classList.contains('quantity-decrement') && value > 1) {
                    input.value = value - 1;
                }
            });
        });
    });

    // Remove from Cart
    document.querySelectorAll('.remove-item-btn').forEach(button => {
        button.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            const cartItemId = button.getAttribute('data-product-id');
            const cartItem = button.closest('.cart-item');

            try {
                const response = await fetch(`/cart/remove/${cartItemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();
                if (data.success) {
                    cartItem.classList.add('fade-out');
                    setTimeout(() => {
                        cartItem.remove();
                        if (!document.querySelector('.cart-item')) {
                            document.querySelector('.cart-items').innerHTML = '<div class="empty-cart"><h2>Your cart is empty</h2><p>Add some products to get started!</p><a href="/shop" class="btn btn-primary">Shop Now</a></div>';
                        }
                    }, 500);
                    updateCartCount();
                    updateCartSummary();
                    showNotification(data.message || 'Item removed from cart.', 'success');
                } else {
                    showNotification(data.message || 'Failed to remove item.', 'error');
                }
            } catch (error) {
                console.error('Remove error:', error);
                showNotification(`Error: ${error.message}. Please try again or contact support.`, 'error');
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
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    const data = await response.json();
                    if (data.success) {
                        quickViewContent.querySelector('.quick-view-image').innerHTML = `<img src="${data.image || '/storage/images/placeholder.jpg'}" alt="${data.name}" loading="lazy">`;
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
                const response = await fetch('/cart/store-order', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();
                if (data.success) {
                    showNotification(data.message || 'Order placed successfully. Awaiting payment verification.', 'success');
                    window.location.href = `/order/confirmation/${data.order_id}`;
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