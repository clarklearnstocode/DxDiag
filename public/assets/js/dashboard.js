(() => {
    function toggleDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown) dropdown.classList.toggle('show');
    }

    function clearSearch(searchBar, clearBtn, applyFilters) {
        searchBar.value = '';
        clearBtn.style.display = 'none';
        applyFilters();
    }

    function initDashboardFilters() {
        const searchBar = document.getElementById('searchBar');
        const clearBtn = document.getElementById('clearSearch');
        const cards = document.querySelectorAll('.property-card');

        if (!searchBar || !clearBtn || cards.length === 0) return;

        function applyFilters() {
            const availabilityNode = document.querySelector('input[name="availability"]:checked');
            const avail = availabilityNode ? availabilityNode.value : 'all';
            const query = searchBar.value.trim().toLowerCase();
            const needPool = document.getElementById('filter-pool')?.checked || false;
            const minCap = parseInt(document.getElementById('filterCapacity')?.value || '0', 10) || 0;
            const minBath = parseInt(document.getElementById('filterBathrooms')?.value || '0', 10) || 0;
            const minSize = parseInt(document.getElementById('filterSize')?.value || '0', 10) || 0;
            const minPrice = parseFloat(document.getElementById('priceMin')?.value || '0') || 0;
            const maxPrice = parseFloat(document.getElementById('priceMax')?.value || 'Infinity') || Infinity;

            let visible = 0;
            cards.forEach((card) => {
                const name = card.dataset.name || '';
                const loc = card.dataset.location || '';
                const status = card.dataset.status || '';
                const pool = card.dataset.pool === '1';
                const bath = parseInt(card.dataset.bathrooms || '0', 10);
                const cap = parseInt(card.dataset.capacity || '0', 10);
                const size = parseInt(card.dataset.size || '0', 10);
                const rate = parseFloat(card.dataset.rate || '0');

                const matchSearch = !query || name.includes(query) || loc.includes(query);
                const matchAvail = avail === 'all' || status === avail;
                const matchPool = !needPool || pool;
                const matchCap = cap >= minCap;
                const matchBath = bath >= minBath;
                const matchSize = size >= minSize;
                const matchPrice = rate >= minPrice && rate <= maxPrice;

                const show = matchSearch && matchAvail && matchPool && matchCap && matchBath && matchSize && matchPrice;
                card.style.display = show ? '' : 'none';
                if (show) visible += 1;
            });

            const resultCount = document.getElementById('resultCount');
            const noResults = document.getElementById('noResults');
            if (resultCount) resultCount.textContent = String(visible);
            if (noResults) noResults.style.display = visible === 0 ? 'inline' : 'none';
        }

        function resetFilters() {
            const allOption = document.querySelector('input[name="availability"][value="all"]');
            if (allOption) allOption.checked = true;
            const ids = ['filter-pool', 'filterCapacity', 'filterBathrooms', 'filterSize', 'priceMin', 'priceMax'];
            ids.forEach((id) => {
                const node = document.getElementById(id);
                if (!node) return;
                if (node instanceof HTMLInputElement && node.type === 'checkbox') node.checked = false;
                else node.value = '';
            });
            clearSearch(searchBar, clearBtn, applyFilters);
        }

        // Expose handlers used by inline onclick until markup cleanup is complete.
        window.applyFilters = applyFilters;
        window.resetFilters = resetFilters;
        window.clearSearch = () => clearSearch(searchBar, clearBtn, applyFilters);
        window.toggleDropdown = toggleDropdown;

        searchBar.addEventListener('input', function onInput() {
            clearBtn.style.display = this.value ? 'flex' : 'none';
            applyFilters();
        });

        document.querySelectorAll('input[name="availability"]').forEach((node) => node.addEventListener('change', applyFilters));
        ['filter-pool', 'filterCapacity', 'filterBathrooms', 'filterSize'].forEach((id) => {
            const node = document.getElementById(id);
            if (!node) return;
            node.addEventListener(node instanceof HTMLInputElement && node.type === 'checkbox' ? 'change' : 'input', applyFilters);
        });

        window.addEventListener('click', (e) => {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown && dropdown.classList.contains('show') && !e.target.closest('.user-profile-container')) {
                dropdown.classList.remove('show');
            }
        });

        applyFilters();
    }

    document.addEventListener('DOMContentLoaded', initDashboardFilters);
})();
