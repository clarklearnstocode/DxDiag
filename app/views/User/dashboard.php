<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook | Available Estates</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css?v=2.0">
</head>
<body>

    <aside class="sidebar">
        <a href="index.php?action=home" class="logo">Estate<span>Book</span></a>

        <!-- Availability Filter -->
        <div class="filter-section">
            <span class="filter-label">Availability</span>
            <label class="radio-group">
                <input type="radio" name="availability" value="all" checked> All Properties
            </label>
            <label class="radio-group">
                <input type="radio" name="availability" value="available"> Available Only
            </label>
            <label class="radio-group">
                <input type="radio" name="availability" value="occupied"> Currently Occupied
            </label>
        </div>

        <!-- Amenities Filter -->
        <div class="filter-section">
            <span class="filter-label">Amenities</span>
            <label class="checkbox-group">
                <input type="checkbox" id="filter-pool"> <span>🏊 Has Swimming Pool</span>
            </label>
        </div>

        <!-- Guest Capacity -->
        <div class="filter-section">
            <span class="filter-label">Min. Guest Capacity</span>
            <input type="number" id="filterCapacity" class="sidebar-input" placeholder="e.g. 6" min="1">
        </div>

        <!-- Bathrooms -->
        <div class="filter-section">
            <span class="filter-label">Min. Bathrooms</span>
            <input type="number" id="filterBathrooms" class="sidebar-input" placeholder="e.g. 2" min="0">
        </div>

        <!-- Villa Size -->
        <div class="filter-section">
            <span class="filter-label">Min. Size (m²)</span>
            <input type="number" id="filterSize" class="sidebar-input" placeholder="e.g. 150" min="0">
        </div>

        <!-- Price Range Filter -->
        <div class="filter-section">
            <span class="filter-label">Price Range / Night</span>
            <div class="price-inputs">
                <input type="number" id="priceMin" placeholder="Min ₱" min="0">
                <span class="price-sep">—</span>
                <input type="number" id="priceMax" placeholder="Max ₱" min="0">
            </div>
            <button class="btn-apply" onclick="applyFilters()">Apply Filters</button>
            <button class="btn-reset" onclick="resetFilters()">Reset</button>
        </div>

        <!-- Results Count -->
        <div class="filter-section" style="margin-top: auto; padding-top: 20px; border-top: 1px solid #1a1a1a;">
            <span class="filter-label">Showing</span>
            <span id="resultCount" style="color: var(--primary); font-weight: 700; font-size: 1.1rem;">—</span>
            <span style="color: #444; font-size: 0.8rem;"> properties</span>
        </div>
    </aside>

    <main class="main-content">

        <!-- Top Navbar -->
        <div class="header-top">
            <div class="search-wrap">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="searchBar" class="search-bar" placeholder="Search by name or location...">
                <button class="search-clear" id="clearSearch" onclick="clearSearch()" style="display:none;">✕</button>
            </div>

            <nav class="top-nav">
                <a href="index.php?action=dashboard" class="nav-item active">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Browse
                </a>
                <a href="index.php?action=my_bookings" class="nav-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    My Bookings
                </a>

                <div class="user-profile-container">
                    <button class="profile-btn" onclick="toggleDropdown(event)">
                        <img src="<?php echo !empty($_SESSION['user_image']) ? 'assets/img/uploads/' . htmlspecialchars($_SESSION['user_image']) : 'assets/img/user.png'; ?>" alt="Profile" class="profile-icon">
                        <span class="profile-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div id="profileDropdown" class="dropdown-menu">
                        <div class="dropdown-header">
                            <img src="<?php echo !empty($_SESSION['user_image']) ? 'assets/img/uploads/' . htmlspecialchars($_SESSION['user_image']) : 'assets/img/user.png'; ?>" alt="Profile" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);">
                            <div>
                                <div style="font-weight:700;font-size:0.85rem;"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></div>
                                <div style="font-size:0.72rem;color:#555;"><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></div>
                            </div>
                        </div>
                        <a href="index.php?action=profile">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            My Profile
                        </a>
                        <a href="index.php?action=my_bookings">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            My Bookings
                        </a>
                        <hr style="border:0; border-top:1px solid #1e1e1e; margin:6px 0;">
                        <a href="index.php?action=logout" style="color:#ff4c4c;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ff4c4c" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <div class="page-heading">
            <h2>Available Estates <span id="noResults" style="display:none; font-size:1rem; color:#555; font-weight:400;">— No properties match your filters</span></h2>
            <p style="color:#555; font-size:0.875rem; margin-top:4px;">Luxury villas across Bacolod & Negros Occidental</p>
        </div>

        <div class="property-grid" id="propertyGrid">
            <?php if (!empty($properties)): ?>
                <?php foreach ($properties as $property): ?>
                    <?php
                        $isOccupied = strtolower($property['Status'] ?? '') === 'occupied';
                        $hasPool    = !empty($property['Has_pool']) && $property['Has_pool'] != '0';
                    ?>
                    <div class="property-card <?php echo $isOccupied ? 'card-occupied' : ''; ?>"
                         data-name="<?php echo strtolower(htmlspecialchars($property['Property_Name'])); ?>"
                         data-location="<?php echo strtolower(htmlspecialchars($property['Property_location'])); ?>"
                         data-status="<?php echo strtolower($property['Status'] ?? 'available'); ?>"
                         data-pool="<?php echo $hasPool ? '1' : '0'; ?>"
                         data-bathrooms="<?php echo intval($property['Property_bathrooms'] ?? 0); ?>"
                         data-capacity="<?php echo intval($property['Property_capacity'] ?? 0); ?>"
                         data-size="<?php echo intval($property['Property_size'] ?? 0); ?>"
                         data-rate="<?php echo floatval($property['Property_rate'] ?? 0); ?>">

                        <div class="card-image-wrap">
                            <img src="assets/img/<?php echo htmlspecialchars($property['image_path']); ?>"
                                 alt="<?php echo htmlspecialchars($property['Property_Name']); ?>"
                                 class="property-image"
                                 onerror="this.onerror=null;this.src='assets/img/villa1.png'">
                            <div class="card-status-tag <?php echo $isOccupied ? 'tag-occupied' : 'tag-available'; ?>">
                                <?php echo $isOccupied ? '🔴 Occupied' : '🟢 Available'; ?>
                            </div>
                            <?php if ($hasPool): ?>
                                <div class="card-pool-tag">🏊 Pool</div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">
                            <div class="card-top-row">
                                <h3 class="card-title"><?php echo htmlspecialchars($property['Property_Name']); ?></h3>
                                <span class="card-rate">₱<?php echo number_format($property['Property_rate']); ?><small>/night</small></span>
                            </div>
                            <span class="card-loc">📍 <?php echo htmlspecialchars($property['Property_location']); ?></span>
                            <div class="property-specs">
                                <span title="Size">📐 <?php echo $property['Property_size'] ?? '—'; ?>m²</span>
                                <span title="Capacity">👥 <?php echo $property['Property_capacity'] ?? '—'; ?> guests</span>
                                <span title="Bathrooms">🚿 <?php echo $property['Property_bathrooms'] ?? '—'; ?> bath</span>
                            </div>
                            <?php if ($isOccupied): ?>
                                <div class="btn-occupied">Currently Unavailable</div>
                            <?php else: ?>
                                <a href="index.php?action=view_property&id=<?php echo $property['Property_Id']; ?>" class="btn-view">
                                    View & Reserve →
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color:#555; grid-column:1/-1; padding:40px 0;">No properties found in the database.</p>
            <?php endif; ?>
        </div>

    </main>

<script>
    // --- Dropdown ---
    function toggleDropdown(event) {
        event.stopPropagation();
        document.getElementById("profileDropdown").classList.toggle("show");
    }
    window.onclick = function(e) {
        var d = document.getElementById("profileDropdown");
        if (d && d.classList.contains('show') && !e.target.closest('.user-profile-container')) {
            d.classList.remove('show');
        }
    }

    // --- Search ---
    var searchBar = document.getElementById('searchBar');
    var clearBtn  = document.getElementById('clearSearch');

    searchBar.addEventListener('input', function() {
        clearBtn.style.display = this.value ? 'flex' : 'none';
        applyFilters();
    });

    function clearSearch() {
        searchBar.value = '';
        clearBtn.style.display = 'none';
        applyFilters();
    }

    // --- Filters ---
    document.querySelectorAll('input[name="availability"]').forEach(function(r) {
        r.addEventListener('change', applyFilters);
    });
    document.getElementById('filter-pool').addEventListener('change', applyFilters);
    document.getElementById('filterCapacity').addEventListener('input', applyFilters);
    document.getElementById('filterBathrooms').addEventListener('input', applyFilters);
    document.getElementById('filterSize').addEventListener('input', applyFilters);

    function applyFilters() {
        var query    = searchBar.value.trim().toLowerCase();
        var avail    = document.querySelector('input[name="availability"]:checked').value;
        var needPool = document.getElementById('filter-pool').checked;
        var minCap   = parseInt(document.getElementById('filterCapacity').value)  || 0;
        var minBath  = parseInt(document.getElementById('filterBathrooms').value) || 0;
        var minSize  = parseInt(document.getElementById('filterSize').value)      || 0;
        var minPrice = parseFloat(document.getElementById('priceMin').value) || 0;
        var maxPrice = parseFloat(document.getElementById('priceMax').value) || Infinity;

        var cards   = document.querySelectorAll('.property-card');
        var visible = 0;

        cards.forEach(function(card) {
            var name   = card.dataset.name;
            var loc    = card.dataset.location;
            var status = card.dataset.status;
            var pool   = card.dataset.pool === '1';
            var bath   = parseInt(card.dataset.bathrooms);
            var cap    = parseInt(card.dataset.capacity);
            var size   = parseInt(card.dataset.size   || 0);
            var rate   = parseFloat(card.dataset.rate);

            var matchSearch = !query || name.includes(query) || loc.includes(query);
            var matchAvail  = avail === 'all' || status === avail;
            var matchPool   = !needPool || pool;
            var matchCap    = cap  >= minCap;
            var matchBath   = bath >= minBath;
            var matchSize   = size >= minSize;
            var matchPrice  = rate >= minPrice && rate <= maxPrice;

            var show = matchSearch && matchAvail && matchPool && matchCap && matchBath && matchSize && matchPrice;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('resultCount').textContent = visible;
        document.getElementById('noResults').style.display = visible === 0 ? 'inline' : 'none';
    }

    function resetFilters() {
        document.querySelector('input[name="availability"][value="all"]').checked = true;
        document.getElementById('filter-pool').checked = false;
        document.getElementById('filterCapacity').value  = '';
        document.getElementById('filterBathrooms').value = '';
        document.getElementById('filterSize').value      = '';
        document.getElementById('priceMin').value = '';
        document.getElementById('priceMax').value = '';
        searchBar.value = '';
        clearBtn.style.display = 'none';
        applyFilters();
    }

    // Init count on load
    window.addEventListener('DOMContentLoaded', applyFilters);
</script>
</body>
</html>
