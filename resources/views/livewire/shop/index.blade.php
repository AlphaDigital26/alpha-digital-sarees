<main class="shop-container">
    <aside class="sidebar">
        <h2 class="filter-title">FILTERS</h2>
        
        <div class="filter-group">
            <h3>FABRIC</h3>
            <label><input type="checkbox"> Banarasi Silk</label>
            <label><input type="checkbox"> Kanchipuram</label>
            <label><input type="checkbox"> Pure Linen</label>
            <label><input type="checkbox"> Organza</label>
        </div>

        <div class="filter-group">
            <h3>COLOR</h3>
            <div class="color-options">
                <span class="color-circle" style="background: #800020;" title="Maroon"></span>
                <span class="color-circle" style="background: #004225;" title="Emerald"></span>
                <span class="color-circle" style="background: #FFD700;" title="Gold"></span>
                <span class="color-circle" style="background: #1A1A1A;" title="Black"></span>
            </div>
        </div>

        <div class="filter-group">
            <h3>PRICE</h3>
            <label><input type="radio" name="price"> Under 5k</label>
            <label><input type="radio" name="price"> 5k - 10k</label>
            <label><input type="radio" name="price"> 10k - 20k</label>
            <label><input type="radio" name="price"> Above 20k</label>
            
            <div class="price-range">
                <p>Range &rarr; ₹</p>
                <input type="number" placeholder="Min">
                <span>-</span>
                <input type="number" placeholder="Max">
            </div>
        </div>

        <div class="filter-group">
            <h3>PATTERN (Optional)</h3>
            <label><input type="checkbox"> Floral</label>
            <label><input type="checkbox"> Zari Work</label>
            <label><input type="checkbox"> Solid</label>
        </div>
    </aside>

    <section class="listing-area">
        <div class="listing-header">
            <p class="item-count">Showing 24 items</p>
            <div class="sort-dropdown">
                <span>Sort by:</span>
                <select>
                    <option>Latest</option>
                    <option>Price: High to Low</option>
                    <option>Price: Low to High</option>
                </select>
            </div>
        </div>

        <div class="product-grid">
            <div class="product-card">
                <div class="img-wrapper">
                    <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80" alt="Saree">
                    <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                </div>
                <h3>Midnight Banarasi</h3>
                <p>₹45,500</p>
            </div>
            <div class="product-card">
                <div class="img-wrapper">
                    <img src="https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&q=80" alt="Saree">
                    <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                </div>
                <h3>Emerald Temple Kanchipuram</h3>
                <p>₹58,000</p>
            </div>
            <div class="product-card">
                <div class="img-wrapper">
                    <img src="https://images.unsplash.com/photo-1595967783875-c371f35d8049?auto=format&fit=crop&q=80" alt="Saree">
                    <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                </div>
                <h3>Blossom Organza</h3>
                <p>₹18,200</p>
            </div>
        </div>

        <div class="load-more">
            <button class="btn-discover">Discover More</button>
        </div>
    </section>
</main>