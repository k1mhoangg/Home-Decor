const counters = document.querySelectorAll('.counter');

const options = {
    root: null,
    threshold: 0.5 // Khi 50% phần tử hiện trên viewport
};

const callback = (entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const counter = entry.target;
            const target = +counter.getAttribute('data-target');
            const duration = 2000; // tổng thời gian 2s
            const startTime = performance.now();

            const updateCount = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1); // tỉ lệ từ 0 -> 1
                counter.textContent = Math.floor(progress * target);
                if (progress < 1) {
                    requestAnimationFrame(updateCount);
                } else {
                    counter.textContent = target; // đảm bảo hiển thị đúng target
                }
            };

            requestAnimationFrame(updateCount);
            observer.unobserve(counter);
        }
    });
};

const observer = new IntersectionObserver(callback, options);
counters.forEach(counter => observer.observe(counter));
