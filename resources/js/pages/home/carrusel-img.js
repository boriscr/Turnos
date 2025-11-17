/*JavaScript del carrusel*/
if (window.location.pathname === '/') {
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.carousel-slide');
        let current = 0;

        const nextSlide = () => {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        };

        setInterval(nextSlide, 6500);

        // Precargar imágenes
        slides.forEach(slide => {
            const img = new Image();
            img.src = slide.style.backgroundImage.replace('url("', '').replace('")', '');
        });
    });
}