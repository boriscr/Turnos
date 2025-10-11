if (window.location.pathname.includes('/profile')) {
    document.getElementById('profile-photo').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const img = document.querySelector('.profile-img');
            img.src = URL.createObjectURL(file);
        }
    });
}