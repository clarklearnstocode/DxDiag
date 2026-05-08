(() => {
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', function onClick(e) {
                const targetUrl = this.getAttribute('href');
                if (!targetUrl || targetUrl.startsWith('#')) return;
                e.preventDefault();
                document.body.classList.add('page-exit');
                setTimeout(() => {
                    window.location.href = targetUrl;
                }, 200);
            });
        });
    });
})();
