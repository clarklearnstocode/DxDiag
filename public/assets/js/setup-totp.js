(() => {
    function copyKey(btn) {
        const text = document.getElementById('secretKey').textContent.trim();
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                btn.textContent = 'Copied!';
                setTimeout(() => { btn.textContent = 'Copy'; }, 2000);
            });
        } else {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            btn.textContent = 'Copied!';
            setTimeout(() => { btn.textContent = 'Copy'; }, 2000);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const wrap = document.getElementById('setupTotpData');
        const otpauthUri = wrap ? wrap.dataset.otpauthUri : '';
        if (window.QRCode && otpauthUri) {
            new QRCode(document.getElementById('qrcode'), {
                text: otpauthUri,
                width: 180,
                height: 180,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M,
            });
        }

        const input = document.getElementById('totpInput');
        const btn = document.getElementById('confirmBtn');
        input.addEventListener('input', function onInput() {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
            btn.disabled = this.value.length < 6;
            if (this.value.length === 6) {
                setTimeout(() => { document.getElementById('setupForm').submit(); }, 300);
            }
        });

        window.copyKey = copyKey;
    });
})();
