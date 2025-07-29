<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <!-- Main Footer Grid -->
            <div class="footer-grid">
                <div class="footer-column">
                    <div class="footer-logo">
                        <h3>Paws, Petals & Fungi</h3>
                        <p class="footer-tagline">Premium quality products for your wellness journey</p>
                    </div>
                    <div class="footer-contact">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>support@pawspetals.com</span>
                        </div>
                        {{-- <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+1 (555) 123-4567</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Portland, Oregon, USA</span>
                        </div> --}}
                    </div>
                </div>

                <div class="footer-column">
                    <h4>Shop Categories</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('cap') }}"><i class="fas fa-seedling"></i> Premium Caps</a></li>
                        <li><a href="{{ route('shroom') }}"><i class="fas fa-leaf"></i> Magic Shrooms</a></li>
                        <li><a href="{{ route('sheet') }}"><i class="fas fa-candy-cane"></i> Chocolate Bars</a></li>
                        {{-- <li><a href="#"><i class="fas fa-gift"></i> Gift Sets</a></li> --}}
                    </ul>
                </div>

                <div class="footer-column">
                    <h4>Customer Care</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('aboutus') }}"><i class="fas fa-info-circle"></i> About Us</a></li>
                        <li><a href="{{ route('contactus') }}"><i class="fas fa-headset"></i> Contact Support</a></li>
                        {{-- <li><a href="#"><i class="fas fa-shipping-fast"></i> Shipping Info</a></li>
                        <li><a href="#"><i class="fas fa-undo"></i> Returns & Refunds</a></li>
                        <li><a href="#"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
                        <li><a href="#"><i class="fas fa-file-contract"></i> Terms of Service</a></li> --}}
                    </ul>
                </div>

                <div class="footer-column newsletter-column">
                    <h4>Stay Connected</h4>
                    <p class="newsletter-description">Join our community for exclusive updates, special offers, and wellness insights delivered to your inbox.</p>
                    
                    <form class="newsletter-form" method="POST" action="{{ route('newsletter.subscribe') }}">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="email" placeholder="Enter your email address" required>
                            <button type="submit" class="newsletter-btn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>

                    {{-- <div class="social-links">
                        <a href="#" aria-label="Instagram" class="social-link instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" aria-label="Facebook" class="social-link facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" aria-label="Twitter" class="social-link twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" aria-label="Telegram" class="social-link telegram">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                    </div> --}}
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="payment-methods">
                <h5>We Accept</h5>
                <div class="payment-icons">
                    <div class="payment-icon">
                        <i class="fab fa-bitcoin"></i>
                        <span>Bitcoin</span>
                    </div>
                    <div class="payment-icon">
                        <i class="fab fa-ethereum"></i>
                        <span>Ethereum</span>
                    </div>
                    <div class="payment-icon">
                        <i class="fas fa-dollar-sign"></i>
                        <span>USDT</span>
                    </div>
                    <div class="payment-icon">
                        <i class="fas fa-coins"></i>
                        <span>USDC</span>
                    </div>
                    <div class="payment-icon">
                        <i class="fab fa-paypal"></i>
                        <span>PYUSD</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <div class="copyright">
                    <p>© {{ date('Y') }} Paws, Petals & Fungi. All rights reserved.</p>
                    <p class="disclaimer">Please consume responsibly. Products not evaluated by FDA.</p>
                </div>
                <div class="footer-badges">
                    <div class="badge">
                        <i class="fas fa-lock"></i>
                        <span>Secure Checkout</span>
                    </div>
                    <div class="badge">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Fast Shipping</span>
                    </div>
                    <div class="badge">
                        <i class="fas fa-certificate"></i>
                        <span>Premium Quality</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

