<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook | Available Estates</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css?v=1.1">
</head>
<body>

    <aside class="sidebar">
        <a href="index.php?action=home" class="logo">Estate<span>Book</span></a>
        
        <div class="filter-section">
            <span class="filter-label">Property Type</span>
            <div class="checkbox-group"><input type="checkbox"> Villas</div>
            <div class="checkbox-group"><input type="checkbox"> Mansions</div>
            <div class="checkbox-group"><input type="checkbox"> Penthouses</div>
        </div>

        <div class="filter-section">
            <span class="filter-label">Price Range</span>
            <div class="price-inputs">
                <input type="text" placeholder="Min">
                <span style="color:#333">-</span>
                <input type="text" placeholder="Max">
            </div>
            <button class="btn-apply">Apply</button>
        </div>
    </aside>

    <main class="main-content">
        <div class="header-top">
            <input type="text" class="search-bar" placeholder="Search locations in Bacolod...">
            
            <div class="user-profile-container">
                <span style="margin-right: 15px; color: #888;">Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                <button class="profile-btn" onclick="toggleDropdown(event)">
                    <img src="assets/img/user.png" alt="Profile" class="profile-icon">
                </button>
                <div id="profileDropdown" class="dropdown-menu">
                    <a href="index.php?action=profile">My Profile</a>
                    <a href="index.php?action=my_bookings">My Bookings</a>
                    <hr style="border:0; border-top: 1px solid #222; margin: 5px 0;">
                    <a href="index.php?action=logout" style="color: #ff4444;">Logout</a>
                </div>
            </div>
        </div>

        <h2 style="margin-bottom: 30px;">Available Estates</h2>

<div class="property-grid">
    <?php if (!empty($properties)): ?>
        <?php foreach ($properties as $property): ?>
            <div class="property-card">
                <img src="assets/img/<?php echo htmlspecialchars($property['image_path']); ?>" 
                     alt="Property Image" 
                     class="property-image">
                
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="card-title"><?php echo htmlspecialchars($property['Property_Name']); ?></h3>
                        <span style="color: var(--primary); font-weight: 800;">
                            ₱<?php echo number_format($property['Property_rate']); ?>
                        </span>
                    </div>
                    
                    <span class="card-loc" style="display: block; margin-top: 5px;">
                        📍 <?php echo htmlspecialchars($property['Property_location']); ?>
                    </span>

                    <div class="property-specs">
                        <span>📐 <?php echo $property['Property_size'] ?? '0'; ?>m²</span>
                        <span>🛏️ <?php echo $property['Property_capacity'] ?? '0'; ?>BR</span>
                        <span>🚿 <?php echo $property['Property_bathrooms'] ?? '0'; ?></span>
                    </div>

                    <a href="index.php?action=view_property&id=<?php echo $property['Property_Id']; ?>" class="btn-view">
                        View Details
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color: #666;">No properties available at the moment.</p>
    <?php endif; ?>
</div>
    </main>

<script>
        function toggleDropdown(event) {
            event.stopPropagation();
            document.getElementById("profileDropdown").classList.toggle("show");
        }

        window.onclick = function(event) {
            var dropdown = document.getElementById("profileDropdown");
            if (dropdown && dropdown.classList.contains('show')) {
                if (!event.target.matches('.profile-btn') && !event.target.matches('.profile-icon')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>
</body>
</html>