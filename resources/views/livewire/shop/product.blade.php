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

                <button class="btn-cart">Add to cart</button>
                <button class="btn-buy">Buy Now</button>
                <button class="btn-whatsapp">
                    <i data-lucide="message-circle"></i> Order on WhatsApp
                </button>
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