if (window.location.pathname === '/') {
    document.addEventListener('DOMContentLoaded', function () {
        const track = document.getElementById('specialtiesTrack');
        const container = track.parentElement;
        const cards = track.querySelectorAll('.specialty-card');
        if (cards.length === 0) return;

        // Duplicar para bucle infinito perfecto
        track.innerHTML += track.innerHTML;

        // Solo en móvil
        if (window.innerWidth <= 768) {
            let isDragging = false;
            let startX = 0;
            let startY = 0;
            let scrollLeft = 0;
            const cardWidth = cards[0].offsetWidth + 28; // ancho card + gap

            const start = (e) => {
                isDragging = true;
                container.classList.add('dragging');
                const touch = e.type.includes('touch') ? e.touches[0] : e;
                startX = touch.clientX;
                startY = touch.clientY;
                scrollLeft = container.scrollLeft;
            };

            const move = (e) => {
                if (!isDragging) return;

                const touch = e.type.includes('touch') ? e.touches[0] : e;
                const deltaX = Math.abs(touch.clientX - startX);
                const deltaY = Math.abs(touch.clientY - startY);

                // Si es más vertical → permitir scroll de página
                if (deltaY > deltaX && deltaY > 10) {
                    end();
                    return;
                }

                e.preventDefault();
                const walk = (touch.clientX - startX) * 2.8;
                container.scrollLeft = scrollLeft - walk;
            };

            const end = () => {
                if (!isDragging) return;
                isDragging = false;
                container.classList.remove('dragging');

                // === SNAP AUTOMÁTICO ===
                const currentScroll = container.scrollLeft;
                const cardIndex = Math.round(currentScroll / cardWidth);
                const targetScroll = cardIndex * cardWidth;

                // Animación suave al soltar
                container.style.scrollBehavior = 'smooth';
                container.scrollLeft = targetScroll;

                // Reinicio invisible para bucle infinito
                setTimeout(() => {
                    container.style.scrollBehavior = 'auto';
                    const maxScroll = container.scrollWidth / 2;

                    if (container.scrollLeft >= maxScroll - cardWidth) {
                        container.scrollLeft -= maxScroll;
                    } else if (container.scrollLeft <= cardWidth) {
                        container.scrollLeft += maxScroll;
                    }
                }, 400); // después de la animación smooth
            };

            // Eventos
            container.addEventListener('touchstart', start, { passive: true });
            container.addEventListener('touchmove', move, { passive: false });
            container.addEventListener('touchend', end);
            container.addEventListener('touchcancel', end);

            container.addEventListener('mousedown', start);
            document.addEventListener('mousemove', move);
            document.addEventListener('mouseup', end);
        }
    });
}