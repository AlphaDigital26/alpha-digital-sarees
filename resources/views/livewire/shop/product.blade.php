<main class="product-main">
    <div class="product-container">
        <!-- Gallery Section -->
        <div class="product-gallery">
            <div class="thumbnails">
                <img src="https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80" class="thumb active" onclick="switchImage(this)">
                <img src="https://images.unsplash.com/photo-1610030469915-055106670868?auto=format&fit=crop&q=80" class="thumb" onclick="switchImage(this)">
                <img src="https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&q=80" class="thumb" onclick="switchImage(this)">
            </div>
            <div class="main-display">
                <!-- ID matches the JS function below -->
                <img src="https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80" id="expandedImg">
            </div>
        </div>

        <!-- Product Info Section -->
        <div class="product-info">
            <span class="stock-status">(Out of stock)</span>
            <h1>Ivory pure cotton linen Saree with Pastel Floral Design</h1>
            <p class="price">Rs. 1,599</p>
            <p class="tax-tag">Inclusive of all taxes.</p>

            <div class="similar-products">
                <h4>Similar Products</h4>
                <div class="similar-grid">
                    <div class="sim-item"><img src="https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&w=100"></div>
                    <div class="sim-item"><img src="https://images.unsplash.com/photo-1595967783875-c371f35d8049?auto=format&fit=crop&w=100"></div>
                    <div class="sim-item"><img src="https://images.unsplash.com/photo-1610030469668-93510ec67d9e?auto=format&fit=crop&w=100"></div>
                </div>
            </div>

            <div class="purchase-controls">
                <div class="quantity-box">
                    <label style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Quantity</label>
                    <div class="qty-selector">
                        <button onclick="changeQty(-1)">-</button>
                        <span id="quantity">1</span>
                        <button onclick="changeQty(1)">+</button>
                    </div>
                </div>

<<<<<<< Updated upstream
                <button class="btn-cart">Add to cart</button>
                <button class="btn-buy">Buy Now</button>
                <button class="btn-whatsapp">
                    <i data-lucide="message-circle"></i> Order on WhatsApp
                </button>
=======
                <div class="flex flex-col gap-3 mb-10 mt-6">
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button class="flex-1 bg-white border-2 border-[#800020] text-[#800020] font-bold py-3.5 px-6 rounded-xl hover:bg-[#800020] hover:text-white transition-colors shadow-sm disabled:opacity-50" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            ADD TO CART
                        </button>
                        
                        <button class="flex-1 bg-[#800020] text-white font-bold py-3.5 px-6 rounded-xl hover:bg-[#5D4037] transition-colors shadow-md disabled:opacity-50" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            BUY IT NOW
                        </button>
                    </div>
                    
                    <a href="https://wa.me{{ $settings->whatsapp_number ?? '919876543210' }}?text=Hello!%20I%20am%20interested%20in%20buying%20{{ $quantity }}x%20{{ urlencode($product->name) }}." 
                    target="_blank" 
                    class="mt-3 flex w-full items-center justify-center gap-2 rounded-sm bg-[#25D366] px-6 py-4 text-[13px] font-bold uppercase tracking-widest text-white transition hover:bg-[#20ba5a] shadow-sm">
                        
                        <!-- WhatsApp Icon -->
                        <svg xmlns="http://w3.org" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
                        </svg>
                        
                        Inquire on WhatsApp
                    </a>

                </div>

                <div class="space-y-3">
                    <details class="group bg-white border border-gray-200 rounded-xl" open>
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 text-gray-900 select-none">
                            <span>Product Description</span>
                            <span class="transition group-open:rotate-180">
                                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                            </span>
                        </summary>
                        <div class="text-gray-600 p-4 border-t prose prose-sm max-w-none">
                            {!! $product->description ?? 'No description available.' !!}
                        </div>
                    </details>

                    <details class="group bg-white border border-gray-200 rounded-xl">
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 text-gray-900 select-none">
                            <span>Specification & Dimension</span>
                            <span class="transition group-open:rotate-180">
                                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                            </span>
                        </summary>
                        <div class="text-gray-600 p-4 border-t prose prose-sm max-w-none">
                            {!! $product->specifications ?? 'No specifications available.' !!}
                        </div>
                    </details>

                    <details class="group bg-white border border-gray-200 rounded-xl">
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 text-gray-900 select-none">
                            <span>Care & Maintenance</span>
                            <span class="transition group-open:rotate-180">
                                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                            </span>
                        </summary>
                        <div class="text-gray-600 p-4 border-t prose prose-sm max-w-none">
                            {!! $product->care_instructions ?? 'Dry clean recommended.' !!}
                        </div>
                    </details>
                </div>
>>>>>>> Stashed changes
            </div>
        </div>
    </div>

    <!-- Details Section -->
    <div class="product-tabs">
        <div class="tab-item">
            <h3>Product Descriptions</h3>
            <p>An exquisite ivory saree handwoven from premium pure cotton linen, featuring delicate pastel floral motifs perfect for summer occasions.</p>
        </div>
        <div class="tab-item">
            <h3>Specification & Dimension</h3>
            <p>Length: 6.5 meters including blouse | Fabric: Cotton Linen | Origin: Handcrafted in India.</p>
        </div>
        <div class="tab-item">
            <h3>Care & Maintenance</h3>
            <p>Dry clean only. Store in a cool, dry place wrapped in soft muslin cloth to preserve the zari work.</p>
        </div>
    </div>

    <!-- UI Logic Script -->
    <script>
        // Image Switcher for thumbnails
        function switchImage(img) {
            const main = document.getElementById("expandedImg");
            
            // Remove active class from all thumbnails
            document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
            // Add active class to clicked thumbnail
            img.classList.add('active');

            // Swap image with fade effect
            main.style.opacity = 0.5;
            setTimeout(() => {
                main.src = img.src;
                main.style.opacity = 1;
            }, 150);
        }

        // Quantity Counter
        function changeQty(amount) {
            const qtyText = document.getElementById("quantity");
            let current = parseInt(qtyText.innerText);
            if (current + amount >= 1) {
                qtyText.innerText = current + amount;
            }
        }
    </script>
</main>