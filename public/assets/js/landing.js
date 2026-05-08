(() => {
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
            anchor.addEventListener('click', (e) => {
                const targetSelector = anchor.getAttribute('href');
                const target = targetSelector ? document.querySelector(targetSelector) : null;
                if (!target) return;
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            });
        });
    }

    function initSlideshow() {
        const dataNode = document.getElementById('landing-data');
        if (!dataNode) return;

        let slidesData = [];
        try {
            slidesData = JSON.parse(dataNode.dataset.slides || '[]');
        } catch (_err) {
            slidesData = [];
        }

        const slideImg = document.getElementById('main-slide-img');
        const slideTitle = document.getElementById('slide-title');
        const slideLoc = document.getElementById('slide-loc');
        if (!slideImg || !slideTitle || !slideLoc || slidesData.length <= 1) return;

        let currentSlide = 0;

        function showNextSlide() {
            slideImg.style.opacity = '0';
            setTimeout(() => {
                currentSlide = (currentSlide + 1) % slidesData.length;
                slideImg.src = `assets/img/${slidesData[currentSlide].image_path}`;
                slideTitle.textContent = slidesData[currentSlide].Property_Name;
                slideLoc.textContent = slidesData[currentSlide].Property_location;
                slideImg.style.opacity = '1';
            }, 600);
        }

        setInterval(showNextSlide, 4000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        initSmoothScroll();
        initSlideshow();
    });
})();
