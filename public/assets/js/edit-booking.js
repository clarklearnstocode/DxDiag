(() => {
    function parseUTC(s) { const p = s.split('-'); return new Date(Date.UTC(+p[0], +p[1] - 1, +p[2])); }
    function hasOverlap(ranges, ni, no) { return ranges.some((r) => r.in && r.out && ni < parseUTC(r.out) && no > parseUTC(r.in)); }
    function fmtTime(t) { if (!t) return ''; const p = t.split(':'); let h = parseInt(p[0], 10); const m = p[1]; const ampm = h >= 12 ? 'PM' : 'AM'; h = h % 12 || 12; return `${h}:${m} ${ampm}`; }
    function fmtDate(d) { if (!d) return '—'; return new Date(`${d}T00:00:00`).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }); }

    document.addEventListener('DOMContentLoaded', () => {
        const dataNode = document.getElementById('editBookingData');
        const ranges = dataNode ? JSON.parse(dataNode.dataset.bookedRanges || '[]') : [];
        const rate = parseFloat(document.getElementById('rateField').value) || 0;
        const checkInEl = document.getElementById('checkIn');
        const checkOutEl = document.getElementById('checkOut');
        const inTimeEl = document.getElementById('checkInTime');
        const outTimeEl = document.getElementById('checkOutTime');
        const warning = document.getElementById('dateWarning');
        const summary = document.getElementById('summaryBox');
        const saveBtn = document.getElementById('saveBtn');

        function recalc() {
            if (!checkInEl.value || !checkOutEl.value) return;
            const ni = parseUTC(checkInEl.value);
            const no = parseUTC(checkOutEl.value);
            if (no <= ni) {
                summary.style.display = 'none';
                const minOut = new Date(ni); minOut.setUTCDate(minOut.getUTCDate() + 1);
                checkOutEl.min = minOut.toISOString().split('T')[0];
                return;
            }
            const minOut = new Date(ni); minOut.setUTCDate(minOut.getUTCDate() + 1);
            checkOutEl.min = minOut.toISOString().split('T')[0];
            const conflict = hasOverlap(ranges, ni, no);
            warning.style.display = conflict ? 'block' : 'none';
            saveBtn.disabled = conflict;
            if (!conflict) {
                const nights = Math.round((no - ni) / 86400000);
                document.getElementById('numNights').textContent = `${nights} night${nights !== 1 ? 's' : ''}`;
                document.getElementById('totalPrice').textContent = `₱${(nights * rate).toLocaleString()}`;
                document.getElementById('summaryIn').textContent = fmtDate(checkInEl.value) + (inTimeEl.value ? ` · ${fmtTime(inTimeEl.value)}` : '');
                document.getElementById('summaryOut').textContent = fmtDate(checkOutEl.value) + (outTimeEl.value ? ` · ${fmtTime(outTimeEl.value)}` : '');
                summary.style.display = 'block';
            } else {
                summary.style.display = 'none';
            }
        }

        checkInEl.addEventListener('change', recalc);
        checkOutEl.addEventListener('change', recalc);
        inTimeEl.addEventListener('change', recalc);
        outTimeEl.addEventListener('change', recalc);
        recalc();
    });
})();
