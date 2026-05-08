(() => {
    function toggleDropdown() {
        document.getElementById('profileDropdown').classList.toggle('show');
    }

    document.addEventListener('DOMContentLoaded', () => {
        window.toggleDropdown = toggleDropdown;
        window.addEventListener('click', (e) => {
            if (!e.target.closest('.user-profile-container')) {
                const d = document.getElementById('profileDropdown');
                if (d && d.classList.contains('show')) d.classList.remove('show');
            }
        });
    });
})();
