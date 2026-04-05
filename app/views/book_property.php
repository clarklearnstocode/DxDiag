<?php
// Get the villa ID from the URL (default to 1)
$villaId = isset($_GET['id']) ? $_GET['id'] : 1;

// Mock data matching your img folder
$villaData = [
    1 => [
        'name' => 'Luxury Villa with Pool',
        'loc' => 'Alijis, Bacolod City',
        'price' => '8,500',
        'img' => 'assets/img/villa1.png',
        'desc' => 'Experience the pinnacle of luxury in this spacious villa located in the heart of Alijis. Perfect for family getaways.'
    ],
    2 => [
        'name' => 'Modern Condo in Bacolod',
        'loc' => 'Lacson St, Bacolod City',
        'price' => '2,500',
        'img' => 'assets/img/villa2.png',
        'desc' => 'A sleek, modern condominium perfect for urban professionals. Located right on the bustling Lacson Street.'
    ],
    3 => [
        'name' => 'Heritage Mansion',
        'loc' => 'Silay City',
        'price' => '12,000',
        'img' => 'assets/img/villa3.png',
        'desc' => 'Live like royalty in this preserved heritage mansion. Featuring high ceilings and classic Silaynon architecture.'
    ]
];

// Fallback if ID doesn't exist
$currentVilla = isset($villaData[$villaId]) ? $villaData[$villaId] : $villaData[1];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook | Reserve Your Villa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #0a0a0a; --card: #161616; }
        body { background: var(--dark); color: white; font-family: 'Inter', sans-serif; margin: 0; padding: 50px; }
        
        /* Header & Profile Styles */
        .header-nav { display: flex; justify-content: space-between; align-items: center; max-width: 1000px; margin: 0 auto 30px auto; }
        
        .user-profile-container { position: relative; }
        .profile-btn { background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; }
        .profile-icon { width: 40px; height: 40px; border-radius: 50%; border: 2px solid transparent; transition: 0.3s; object-fit: cover; }
        .profile-btn:hover .profile-icon { border-color: var(--primary); }
        
        .dropdown-menu { display: none; position: absolute; right: 0; top: 50px; background: #161616; border: 1px solid #222; border-radius: 12px; min-width: 160px; z-index: 1000; box-shadow: 0 10px 30px rgba(0,0,0,0.5); overflow: hidden; }
        .dropdown-menu a { color: #ccc; padding: 12px 20px; text-decoration: none; display: block; font-size: 0.85rem; transition: 0.2s; text-align: left; }
        .dropdown-menu a:hover { background: #1f1f1f; color: var(--primary); }
        .show { display: block; }

        /* Booking Layout */
        .booking-container { max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: 1.5fr 1fr; gap: 40px; }
        
        .property-preview img { width: 100%; border-radius: 20px; border: 1px solid #333; }
        .property-info h1 { font-size: 2.5rem; margin: 20px 0 10px 0; }
        .property-info p { color: #888; line-height: 1.6; }
        
        .booking-card { background: var(--card); padding: 30px; border-radius: 20px; border: 1px solid #222; height: fit-content; position: sticky; top: 50px; }
        .price-tag { font-size: 1.5rem; font-weight: 800; color: var(--primary); margin-bottom: 20px; display: block; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 0.8rem; color: #666; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; }
        input { width: 100%; padding: 15px; background: #222; border: 1px solid #333; border-radius: 10px; color: white; outline: none; }
        
        .btn-confirm { width: 100%; padding: 18px; background: var(--primary); border: none; border-radius: 12px; font-weight: 800; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-confirm:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(201, 160, 122, 0.2); }
        
        .back-link { color: #555; text-decoration: none; font-size: 0.9rem; transition: 0.3s; }
        .back-link:hover { color: var(--primary); }
    </style>
</head>
<body>

    <!-- Updated Top Navigation -->
    <header class="header-nav">
        <a href="index.php?action=dashboard" class="back-link">&larr; Back to Explore</a>
        
        <div class="user-profile-container">
            <button class="profile-btn" onclick="toggleDropdown()">
                <img src="assets/img/user.png" alt="Profile" class="profile-icon">
            </button>
            <div id="profileDropdown" class="dropdown-menu">
                <a href="index.php?action=profile">My Profile</a>
                <a href="index.php?action=my_bookings">My Bookings</a>
                <hr style="border:0; border-top: 1px solid #222; margin: 5px 0;">
                <a href="index.php?action=home" style="color: #ff4444;">Logout</a>
            </div>
        </div>
    </header>

    <div class="booking-container">
        <div class="property-preview">
            <!-- Dynamic Image based on the ID -->
            <img src="<?php echo $currentVilla['img']; ?>" alt="Villa Image">
            
            <div class="property-info">
                <h1><?php echo $currentVilla['name']; ?></h1>
                <p><?php echo $currentVilla['loc']; ?></p>
                
                <div style="display:flex; gap: 20px; margin-top: 15px; color: #666; font-size: 0.9rem;">
                    <span>🛏️ 5 Beds</span> <span>🚿 3 Baths</span> <span>📐 300m²</span>
                </div>
                <hr style="border: 0; border-top: 1px solid #222; margin: 30px 0;">
                <h3>Description</h3>
                <p><?php echo $currentVilla['desc']; ?></p>
            </div>
        </div>

        <div class="booking-card">
            <span class="price-tag">₱<?php echo $currentVilla['price']; ?> <small style="font-size: 0.8rem; color: #555;">/ Night</small></span>
            
            <form action="index.php?action=dashboard" method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" value="Lance Eduard Libuna" readonly>
                </div>
                <div class="form-group">
                    <label>Check-in Date</label>
                    <input type="date" required>
                </div>
                <button type="submit" class="btn-confirm" onclick="alert('Reservation submitted for <?php echo $currentVilla['name']; ?>!')">Confirm Reservation</button>
            </form>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("show");
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.profile-btn') && !event.target.matches('.profile-icon')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>

</body>
</html>