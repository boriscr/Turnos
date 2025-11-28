if (window.location.pathname === '/') {

    document.addEventListener('DOMContentLoaded', () => {
        const carousel = document.getElementById('carousel');
        const track = document.getElementById('track');
        const cards = track.querySelectorAll('.specialty-card');

        if (cards.length === 0) return;

        // Duplicar para bucle infinito (solo una vez)
        track.innerHTML += track.innerHTML;

        // =============== ESCRITORIO: ACELERAR EN BORDES ===============
        if (window.innerWidth > 768) {
            carousel.addEventListener('mousemove', e => {
                const rect = carousel.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const width = rect.width;

                if (x < width * 0.15) {
                    carousel.dataset.side = 'left';
                } else if (x > width * 0.85) {
                    carousel.dataset.side = 'right';
                } else {
                    carousel.dataset.side = '';
                }

                // Cambiar velocidad dinámicamente
                const speed = carousel.dataset.side === 'left' ? -3 : carousel.dataset.side === 'right' ? 3 : 1;
                track.style.animationDuration = `${50 / speed}s`;
            });

            carousel.addEventListener('mouseleave', () => {
                delete carousel.dataset.side;
                track.style.animationDuration = '50s';
            });
        }

        // =============== MÓVIL: EFECTO TINDER PERFECTO ===============
        if (window.innerWidth <= 768) {
            let isDragging = false;
            let startX = 0;
            let scrollLeft = 0;

            const cardWidth = cards[0].offsetWidth + 20; // ancho real + gap aproximado
            const maxScroll = track.scrollWidth / 2;

            const start = e => {
                isDragging = true;
                const touch = e.touches ? e.touches[0] : e;
                startX = touch.clientX;
                scrollLeft = carousel.scrollLeft;
            };

            const move = e => {
                if (!isDragging) return;
                e.preventDefault();
                const touch = e.touches ? e.touches[0] : e;
                const walk = (touch.clientX - startX) * 2.5;
                carousel.scrollLeft = scrollLeft - walk;
            };

            const end = () => {
                if (!isDragging) return;
                isDragging = false;

                // Snap al centro más cercano
                const scrolled = carousel.scrollLeft;
                const index = Math.round(scrolled / cardWidth);
                const target = index * cardWidth;

                carousel.style.scrollBehavior = 'smooth';
                carousel.scrollLeft = target;

                // Bucle infinito invisible
                setTimeout(() => {
                    carousel.style.scrollBehavior = 'auto';
                    if (carousel.scrollLeft >= maxScroll - cardWidth) {
                        carousel.scrollLeft -= maxScroll;
                    } else if (carousel.scrollLeft < cardWidth) {
                        carousel.scrollLeft += maxScroll;
                    }
                }, 500);
            };

            carousel.addEventListener('touchstart', start, { passive: true });
            carousel.addEventListener('touchmove', move, { passive: false });
            carousel.addEventListener('touchend', end);
            carousel.addEventListener('mousedown', start);
            document.addEventListener('mousemove', move);
            document.addEventListener('mouseup', end);
        }
    });
}