<x-app-layout title="Checkout" bodyclass="checkout-page">
    <div class="container">
        @if (!Auth::check())
            <div class="alert alert-error">
                Please <a href="{{ route('login') }}" class="action-link">log in</a> to complete your purchase.
                <button class="close">×</button>
            </div>
        @else
            <h1 class="font-playfair text-4xl font-bold mb-8 text-accent">Checkout</h1>
            <div class="checkout-grid">
                <!-- Order Summary -->
                <div class="activity-card">
                    <h2 class="card-title">Order Summary</h2>
                    @forelse ($cartItems as $item)
                        <div class="summary-row">
                            <span>{{ $item->product->name }} (SKU: {{ $item->product->sku ?? 'N/A' }}) (x{{ $item->quantity }})</span>
                            <span>${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-gray-600 dark:text-gray-400 text-center py-12">No items in your cart.</p>
                    @endforelse
                    <hr class="divider">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span class="subtotal">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if ($subtotal < 50)
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>$5.00</span>
                        </div>
                    @endif
                    <div class="summary-row total">
                        <span>Total</span>
                        <span class="total">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
                
                <!-- Payment Form -->
                <div class="admin-form">
                    <h2 class="card-title">Pay with Cryptocurrency</h2>
                    <p class="mb-4">Select your preferred cryptocurrency and send payment to the address below:</p>
                    
                    <!-- Crypto Selection Tabs -->
                    <div class="crypto-tabs">
                        <button class="crypto-tab active" onclick="showCrypto('bitcoin', this)">
                            <i class="fab fa-bitcoin"></i> Bitcoin
                        </button>
                        <button class="crypto-tab" onclick="showCrypto('ethereum', this)">
                            <i class="fab fa-ethereum"></i> Ethereum
                        </button>
                        <button class="crypto-tab" onclick="showCrypto('usdt', this)">
                            <i class="fas fa-dollar-sign"></i> USDT
                        </button>
                        <button class="crypto-tab" onclick="showCrypto('usdc', this)">
                            <i class="fas fa-coins"></i> USDC
                        </button>
                        <button class="crypto-tab" onclick="showCrypto('pyusd', this)">
                            <i class="fab fa-paypal"></i> PYUSD
                        </button>
                    </div>
                    
                    <!-- Crypto Address Display -->
                    <div class="crypto-display">
                        <div class="crypto-content active" id="bitcoin-content">
                            <h4 class="crypto-title">Bitcoin (BTC)</h4>
                            <div class="crypto-address-container">
                                <div class="crypto-address" id="btc-address">bc1qln6l4s3nq4jsuncd7a0cq2xsn9q3z56eyxevhd</div>
                                <button type="button" class="copy-btn" onclick="copyAddress('btc-address', this)" title="Copy address">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="crypto-content" id="ethereum-content">
                            <h4 class="crypto-title">Ethereum (ETH)</h4>
                            <div class="crypto-address-container">
                                <div class="crypto-address" id="eth-address">0x91aB926d588374203a069FFf27a9a5f264568d54</div>
                                <button type="button" class="copy-btn" onclick="copyAddress('eth-address', this)" title="Copy address">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="crypto-content" id="usdt-content">
                            <h4 class="crypto-title">USDT (Ethereum Network)</h4>
                            <div class="crypto-address-container">
                                <div class="crypto-address" id="usdt-address">0x91aB926d588374203a069FFf27a9a5f264568d54</div>
                                <button type="button" class="copy-btn" onclick="copyAddress('usdt-address', this)" title="Copy address">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="crypto-content" id="usdc-content">
                            <h4 class="crypto-title">USDC (Ethereum Network)</h4>
                            <div class="crypto-address-container">
                                <div class="crypto-address" id="usdc-address">0x91aB926d588374203a069FFf27a9a5f264568d54</div>
                                <button type="button" class="copy-btn" onclick="copyAddress('usdc-address', this)" title="Copy address">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="crypto-content" id="pyusd-content">
                            <h4 class="crypto-title">PYUSD (Ethereum Network)</h4>
                            <div class="crypto-address-container">
                                <div class="crypto-address" id="pyusd-address">0x91aB926d588374203a069FFf27a9a5f264568d54</div>
                                <button type="button" class="copy-btn" onclick="copyAddress('pyusd-address', this)" title="Copy address">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        <strong>Important:</strong> Your order will remain pending until our team verifies the payment receipt. This may take up to 24 hours. Please ensure you send the exact amount in USD equivalent.
                    </p>
                    
                    <form action="{{ route('cart.storeOrder') }}" method="POST" enctype="multipart/form-data" id="checkout-form" class="admin-form">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" required class="form-control">
                            @error('name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" required class="form-control">
                            @error('email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone (Optional)</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" class="form-control">
                            @error('phone')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address', Auth::user()->address ?? '') }}" required class="form-control">
                            @error('address')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="crypto_type" class="form-label">Cryptocurrency Used for Payment</label>
                            <select name="crypto_type" id="crypto_type" required class="form-control">
                                <option value="">Select cryptocurrency used</option>
                                <option value="bitcoin">Bitcoin (BTC)</option>
                                <option value="ethereum">Ethereum (ETH)</option>
                                <option value="usdt">USDT (Ethereum Network)</option>
                                <option value="usdc">USDC (Ethereum Network)</option>
                                <option value="pyusd">PYUSD (Ethereum Network)</option>
                            </select>
                            @error('crypto_type')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="transaction_hash" class="form-label">Transaction Hash (Optional)</label>
                            <input type="text" name="transaction_hash" id="transaction_hash" value="{{ old('transaction_hash') }}" class="form-control" placeholder="Enter transaction hash for faster verification">
                            @error('transaction_hash')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="receipt" class="form-label">Payment Receipt/Screenshot</label>
                            <input type="file" name="receipt" id="receipt" accept="image/*" required class="form-control">
                            <small class="text-gray-600 dark:text-gray-400">Upload a screenshot of your payment confirmation</small>
                            @error('receipt')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-glow">Submit Payment Receipt</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    

    <script>
        function showCrypto(cryptoType, tabElement) {
            // Hide all crypto contents
            const contents = document.querySelectorAll('.crypto-content');
            contents.forEach(content => content.classList.remove('active'));
            
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.crypto-tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected crypto content
            document.getElementById(cryptoType + '-content').classList.add('active');
            
            // Add active class to clicked tab
            tabElement.classList.add('active');
            
            // Update the form dropdown to match selection
            const cryptoSelect = document.getElementById('crypto_type');
            if (cryptoSelect) {
                cryptoSelect.value = cryptoType;
            }
        }

        function copyAddress(addressId, button) {
            const addressElement = document.getElementById(addressId);
            const address = addressElement.textContent;
            
            // Copy to clipboard
            navigator.clipboard.writeText(address).then(function() {
                // Change button appearance to show success
                const icon = button.querySelector('i');
                const originalClass = icon.className;
                
                button.classList.add('copied');
                icon.className = 'fas fa-check';
                
                // Reset after 2 seconds
                setTimeout(function() {
                    button.classList.remove('copied');
                    icon.className = originalClass;
                }, 2000);
                
            }).catch(function(err) {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = address;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                // Show success feedback
                const icon = button.querySelector('i');
                const originalClass = icon.className;
                
                button.classList.add('copied');
                icon.className = 'fas fa-check';
                
                setTimeout(function() {
                    button.classList.remove('copied');
                    icon.className = originalClass;
                }, 2000);
            });
        }
    </script>
</x-app-layout>