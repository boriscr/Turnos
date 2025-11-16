document.addEventListener('DOMContentLoaded', function () {
    const track = document.getElementById('specialtiesTrack');
    const cards = track.querySelectorAll('.specialty-card');
    if (cards.length === 0) return;

    // Duplicar una sola vez → bucle perfecto
    track.innerHTML += track.innerHTML;

    // Solo en móvil: arrastre suave e infinito
    if (window.innerWidth <= 768) {
        let isDragging = false;
        let startX = 0;
        let scrollLeft = 0;

        const start = (e) => {
            isDragging = true;
            track.classList.add('dragging');
            startX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            scrollLeft = track.parentElement.scrollLeft || 0;
        };

        const move = (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            const walk = (x - startX) * 2.5;
            track.parentElement.scrollLeft = scrollLeft - walk;
        };

        const end = () => {
            if (isDragging) {
                isDragging = false;
                track.classList.remove('dragging');

                // Reinicio invisible si llegó al final
                const maxScroll = track.parentElement.scrollWidth / 2;
                if (track.parentElement.scrollLeft >= maxScroll - 100) {
                    track.parentElement.scrollLeft = 0;
                }
            }
        };

        track.parentElement.addEventListener('touchstart', start, { passive: true });
        track.parentElement.addEventListener('touchmove', move, { passive: false });
        track.parentElement.addEventListener('touchend', end);

        track.parentElement.addEventListener('mousedown', start);
        document.addEventListener('mousemove', move);
        document.addEventListener('mouseup', end);
    }
});