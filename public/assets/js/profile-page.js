(() => {
    function toggleDropdown() {
        document.getElementById('profileDropdown').classList.toggle('show');
    }

    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => { document.getElementById('avatarPreview').src = e.target.result; };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePw(id, btn) {
        const el = document.getElementById(id);
        if (el.type === 'password') { el.type = 'text'; btn.textContent = 'Hide'; }
        else { el.type = 'password'; btn.textContent = 'Show'; }
    }

    document.addEventListener('DOMContentLoaded', () => {
        window.toggleDropdown = toggleDropdown;
        window.previewPhoto = previewPhoto;
        window.togglePw = togglePw;

        window.addEventListener('click', (e) => {
            if (!e.target.closest('.user-profile-container')) {
                const d = document.getElementById('profileDropdown');
                if (d && d.classList.contains('show')) d.classList.remove('show');
            }
        });

        document.getElementById('profileForm').addEventListener('submit', (e) => {
            const np = document.getElementById('newPw').value;
            const cp = document.getElementById('conPw').value;
            if (np && np !== cp) {
                e.preventDefault();
                alert('New passwords do not match. Please re-enter.');
            }
        });
    });
})();
