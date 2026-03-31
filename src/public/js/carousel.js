// Carousel JS (inline)
(function () {
    const slidesEl = document.getElementById('slides');
    const slides = document.querySelectorAll('.carousel-slide');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const indicators = document.querySelectorAll('.indicator');
    let index = 0;
    const total = slides.length;
    let autoplayInterval = 1000;
    let timer = null;
    let startX = 0;

    function goTo(i) {
        index = (i + total) % total;
        slidesEl.style.transform = `translateX(-${index * 100}%)`;
        updateIndicators();
    }

    function next() { goTo(index + 1); }
    function prev() { goTo(index - 1); }

    function updateIndicators() {
        indicators.forEach((btn) => {
            btn.classList.remove('bg-opacity-100', 'scale-110');
            btn.classList.add('bg-opacity-40');
        });
        const active = indicators[index];
        if (active) {
            active.classList.remove('bg-opacity-40');
            active.classList.add('bg-opacity-100', 'scale-110');
        }
    }

    function startAutoplay() {
        stopAutoplay();
        timer = setInterval(next, autoplayInterval);
    }
    function stopAutoplay() {
        if (timer) { clearInterval(timer); timer = null; }
    }

    // Events
    nextBtn.addEventListener('click', () => { next(); startAutoplay(); });
    prevBtn.addEventListener('click', () => { prev(); startAutoplay(); });
    indicators.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const idx = parseInt(e.currentTarget.dataset.index, 10);
            goTo(idx);
            startAutoplay();
        });
    });

    // Pause on hover
    const carouselRoot = document.getElementById('carousel');
    carouselRoot.addEventListener('mouseenter', stopAutoplay);
    carouselRoot.addEventListener('mouseleave', startAutoplay);

    // Touch support
    slidesEl.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
    }, { passive: true });

    slidesEl.addEventListener('touchend', (e) => {
        const endX = (e.changedTouches && e.changedTouches[0]) ? e.changedTouches[0].clientX : startX;
        const diff = endX - startX;
        if (Math.abs(diff) > 40) {
            if (diff > 0) prev(); else next();
        }
        startAutoplay();
    });

    // Initialize
    goTo(0);
    startAutoplay();
    // Make sure slides take same width as container on resize
    window.addEventListener('resize', () => { slidesEl.style.transition = 'none'; goTo(index); setTimeout(() => slidesEl.style.transition = 'transform 0.5s ease-in-out', 20); });
})();