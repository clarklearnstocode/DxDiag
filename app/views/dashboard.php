<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook | Available Estates</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

    <aside class="sidebar">
        <a href="index.php?action=dashboard" class="logo">Estate<span>Book</span></a>
        
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
            
            <!-- Profile Dropdown -->
            <div class="user-profile-container">
                <button class="profile-btn" onclick="toggleDropdown(event)">
                    <img src="assets/img/user.png" alt="Profile" class="profile-icon">
                </button>
                <div id="profileDropdown" class="dropdown-menu">
                    <!-- Links Updated Here -->
                    <a href="index.php?action=profile">My Profile</a>
                    <a href="index.php?action=my_bookings">My Bookings</a>
                    <hr style="border:0; border-top: 1px solid #222; margin: 5px 0;">
                    <a href="index.php?action=home" style="color: #ff4444;">Logout</a>
                </div>
            </div>
        </div>

        <h2 style="margin-bottom: 30px;">Available Estates</h2>

        <div class="property-grid">
            <!-- Villa 1 -->
            <div class="card" onclick="window.location.href='index.php?action=book_property&id=1'">
                <div class="card-img" style="background-image: url('assets/img/villa1.png');">
                    <span class="badge-avail">Available</span>
                    <span class="price-chip">₱8,500</span>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Luxury Villa with Pool</h3>
                    <p class="card-loc">Alijis, Bacolod City</p>
                    <div class="card-specs">
                        <span>🛏️ 5 Beds</span> <span>🚿 3 Baths</span> <span>📐 300m²</span>
                    </div>
                </div>
            </div>

            <!-- Villa 2 -->
            <div class="card" onclick="window.location.href='index.php?action=book_property&id=2'">
                <div class="card-img" style="background-image: url('assets/img/villa2.png');">
                    <span class="badge-avail">Available</span>
                    <span class="price-chip">₱2,500</span>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Modern Condo in Bacolod</h3>
                    <p class="card-loc">Lacson St, Bacolod City</p>
                    <div class="card-specs">
                        <span>🛏️ 2 Beds</span> <span>🚿 1 Baths</span> <span>📐 120m²</span>
                    </div>
                </div>
            </div>

            <!-- Villa 3 -->
            <div class="card" onclick="window.location.href='index.php?action=book_property&id=3'">
                <div class="card-img" style="background-image: url('assets/img/villa3.png');">
                    <span class="badge-avail">Available</span>
                    <span class="price-chip">₱12,000</span>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Heritage Mansion</h3>
                    <p class="card-loc">Silay City</p>
                    <div class="card-specs">
                        <span>🛏️ 6 Beds</span> <span>🚿 4 Baths</span> <span>📐 500m²</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleDropdown(event) {
            // Prevent event from bubbling up to window.onclick
            event.stopPropagation();
            document.getElementById("profileDropdown").classList.toggle("show");
        }

        // Close dropdown if user clicks outside
        window.onclick = function(event) {
            // Check if the click was inside the dropdown menu
            var dropdown = document.getElementById("profileDropdown");
            if (dropdown.classList.contains('show')) {
                // If the user clicks anywhere that is NOT the button or the icon
                if (!event.target.matches('.profile-btn') && !event.target.matches('.profile-icon')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>
</body>
</html>