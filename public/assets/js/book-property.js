(() => {
    function parseUTC(dateStr) {
        const p = dateStr.split('-');
        return new Date(Date.UTC(+p[0], +p[1] - 1, +p[2]));
    }

    function formatDateTime(dateStr, timeStr) {
        if (!dateStr) return '—';
        const d = new Date(`${dateStr}T00:00:00`);
        const datePart = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        if (!timeStr) return datePart;
        const parts = timeStr.split(':');
        let h = parseInt(parts[0], 10);
        const m = parts[1];
        const ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;
        return `${datePart} · ${h}:${m} ${ampm}`;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const dataNode = document.getElementById('bookPropertyData');
        const bookedRanges = dataNode ? JSON.parse(dataNode.dataset.bookedRanges || '[]') : [];

        const rate = parseFloat(document.getElementById('propertyRate')?.value || '0');
        const checkInEl = document.getElementById('checkIn');
        const checkOutEl = document.getElementById('checkOut');
        const inTimeEl = document.getElementById('checkInTime');
        const outTimeEl = document.getElementById('checkOutTime');
        const warning = document.getElementById('dateWarning');
        const summary = document.getElementById('summaryBox');
        const confirmBtn = document.getElementById('confirmBtn');

        function datesOverlap(ni, no) {
            for (let i = 0; i < bookedRanges.length; i += 1) {
                const r = bookedRanges[i];
                if (!r.in || !r.out) continue;
                if (ni < parseUTC(r.out) && no > parseUTC(r.in)) return true;
            }
            return false;
        }

        function validate() {
            if (!checkInEl.value || !checkOutEl.value) {
                summary.style.display = 'none';
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Select Dates to Continue';
                return;
            }
            const inDate = parseUTC(checkInEl.value);
            const outDate = parseUTC(checkOutEl.value);

            if (outDate <= inDate) {
                warning.style.display = 'none';
                summary.style.display = 'none';
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Check-out must be after Check-in';
                const minOut = new Date(inDate);
                minOut.setUTCDate(minOut.getUTCDate() + 1);
                checkOutEl.min = minOut.toISOString().split('T')[0];
                return;
            }

            const minOut = new Date(inDate);
            minOut.setUTCDate(minOut.getUTCDate() + 1);
            checkOutEl.min = minOut.toISOString().split('T')[0];

            const conflict = datesOverlap(inDate, outDate);
            if (conflict) {
                warning.style.display = 'block';
                summary.style.display = 'none';
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Dates Unavailable';
            } else {
                warning.style.display = 'none';
                const nights = Math.round((outDate - inDate) / 86400000);
                const total = nights * rate;
                document.getElementById('numNights').textContent = `${nights} night${nights !== 1 ? 's' : ''}`;
                document.getElementById('totalPrice').textContent = `₱${total.toLocaleString()}`;
                document.getElementById('summaryIn').textContent = formatDateTime(checkInEl.value, inTimeEl.value);
                document.getElementById('summaryOut').textContent = formatDateTime(checkOutEl.value, outTimeEl.value);
                summary.style.display = 'block';
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Confirm Reservation';
            }
        }

        checkInEl.addEventListener('change', validate);
        checkOutEl.addEventListener('change', validate);
        inTimeEl.addEventListener('change', validate);
        outTimeEl.addEventListener('change', validate);

        window.toggleDropdown = function toggleDropdown() {
            document.getElementById('profileDropdown').classList.toggle('show');
        };
        window.addEventListener('click', (e) => {
            if (!e.target.closest('.user-profile-container')) {
                const d = document.getElementById('profileDropdown');
                if (d && d.classList.contains('show')) d.classList.remove('show');
            }
        });
    });
})();
