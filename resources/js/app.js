import '../css/app.css'


// Hover Image Lazy Loading
document.addEventListener('livewire:navigated', initHoverImages);
document.addEventListener('DOMContentLoaded', initHoverImages);

function initHoverImages() {
    const productCards = document.querySelectorAll('.product-card, .editorial-card, .img-wrapper');
    
    productCards.forEach(card => {
        const hoverImg = card.querySelector('.lazy-hover');
        if (!hoverImg) return;
        
        const loadImg = () => {
            if (hoverImg.dataset.src && !hoverImg.src.includes(hoverImg.dataset.src)) {
                if (hoverImg.dataset.srcset) {
                    hoverImg.srcset = hoverImg.dataset.srcset;
                }
                hoverImg.src = hoverImg.dataset.src;
            }
        };

        card.addEventListener('mouseenter', loadImg, { once: true });
        card.addEventListener('touchstart', loadImg, { once: true, passive: true });
    });
}
