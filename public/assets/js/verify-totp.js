(() => {
    document.addEventListener('DOMContentLoaded', () => {
        const boxes = document.querySelectorAll('.otp-box');
        const hidden = document.getElementById('totp_code_hidden');
        const btn = document.getElementById('verifyBtn');

        function update() {
            const val = Array.from(boxes).map((b) => b.value).join('');
            hidden.value = val;
            btn.disabled = val.length < 6;
            boxes.forEach((b) => b.classList.toggle('filled', b.value !== ''));
            if (val.length === 6) {
                setTimeout(() => { document.getElementById('totpForm').submit(); }, 200);
            }
        }

        boxes.forEach((box, idx) => {
            box.addEventListener('input', (e) => {
                const val = e.target.value.replace(/\D/g, '');
                if (val.length > 1) {
                    val.split('').forEach((ch, i) => { if (boxes[idx + i]) boxes[idx + i].value = ch; });
                    const next = Math.min(idx + val.length, 5);
                    boxes[next].focus();
                } else {
                    box.value = val;
                    if (val && boxes[idx + 1]) boxes[idx + 1].focus();
                }
                update();
            });
            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !box.value && boxes[idx - 1]) boxes[idx - 1].focus();
            });
            box.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
                pasted.split('').forEach((ch, i) => { if (boxes[i]) boxes[i].value = ch; });
                boxes[Math.min(pasted.length, 5)].focus();
                update();
            });
        });

        if (boxes[0]) boxes[0].focus();
    });
})();
