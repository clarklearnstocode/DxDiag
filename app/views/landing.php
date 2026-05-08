<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstateBook | Premium Real Estate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/landing.css">
    <link rel="stylesheet" href="assets/css/landing-page.css">
</head>
<body id="home">
    <div id="landing-data" data-slides='<?php echo htmlspecialchars(json_encode($properties), ENT_QUOTES, "UTF-8"); ?>'></div>

    <nav class="glass-nav">
        <div class="logo logo-clickable" onclick="window.location.href='#home'">Estate<span>Book</span></div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#explore">Estates</a></li>
            <li><a href="index.php?action=login">Log In</a></li>
            <li><a href="#contact" class="btn-filled btn-contact">Contact Us</a></li>
        </ul>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <span class="badge">Premium Properties</span>
            <h1>Find Nearby <br> Luxurious Estates</h1>
            <p>Enhance the experience of finding and dealing luxury houses with EstateBook's curated listings.</p>
            
            <div class="search-box-sleek">
                <div class="search-group">
                    <label>Location</label>
                    <input type="text" value="Bacolod City, PH" readonly>
                </div>
                <div class="search-divider"></div>
                <div class="search-group">
                    <label>Property Type</label>
                    <input type="text" value="Modern Villa" readonly>
                </div>
                <button class="btn-search" onclick="window.location.href='#explore'">Explore</button>
            </div>
        </div>

        <div class="floating-card">
            <div class="slideshow-container">
                <?php if (!empty($properties)): ?>
                    <img id="main-slide-img" src="assets/img/<?php echo $properties[0]['image_path']; ?>" alt="Villa" class="slide-image">
                <?php endif; ?>
            </div>
            <div class="card-info">
                <h3 id="slide-title"><?php echo $properties[0]['Property_Name'] ?? 'No Estates Available'; ?></h3>
                <p id="slide-loc"><?php echo $properties[0]['Property_location'] ?? 'Check back later'; ?></p>
            </div>
        </div>
    </section>

    <section id="explore" class="explore-section">
        <div class="section-header">
            <h2>Our Featured Estates</h2>
            <p>Hand-picked premium properties in Bacolod Metro Area</p>
        </div>

        <div class="property-grid-lite">
            <?php if (!empty($properties)): ?>
                <?php foreach ($properties as $property): ?>
                    <div class="featured-card">
                        <img src="assets/img/<?php echo htmlspecialchars($property['image_path']); ?>"
                             onerror="this.onerror=null;this.src='assets/img/villa1.png';"
                             class="featured-card-image">
                        
                        <h4 class="featured-card-title"><?php echo htmlspecialchars($property['Property_Name']); ?></h4>
                        <p class="featured-card-location">📍 <?php echo htmlspecialchars($property['Property_location']); ?></p>
                        <p class="featured-card-rate">₱<?php echo number_format($property['Property_rate'], 2); ?></p>
                        
                        <a href="index.php?action=login" class="btn-book-lite">Book Now</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="featured-empty">No properties available at the moment.</p>
            <?php endif; ?>
        </div>
        
        <div class="featured-cta-wrap">
            <a href="index.php?action=login" class="featured-cta-link">View Full Dashboard →</a>
        </div>
    </section>

    <footer id="contact" class="main-footer">
        <div class="footer-content">
            <div class="footer-brand">
                <div class="logo footer-logo">Estate<span>Book</span></div>
                <p>Providing the most exclusive property deals in Bacolod. Find your dream home with our expert agents.</p>
            </div>
            
            <div class="footer-links">
                <h4>Explore</h4>
                <a href="#home">Home</a>
                <a href="#explore">Estates</a>
                <a href="index.php?action=login">Agent Login</a>
            </div>

            <div class="footer-social">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#" title="Facebook"><img src="assets/img/fb.png" alt="FB"></a>
                    <a href="#" title="Instagram"><img src="assets/img/ig.png" alt="IG"></a>
                    <a href="#" title="Twitter"><img src="assets/img/tw.png" alt="TW"></a>
                    <a href="#" title="Telegram"><img src="assets/img/tg.png" alt="TG"></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?php echo date("Y"); ?> EstateBook Real Estate. All rights reserved.
        </div>
    </footer>

    <script src="assets/js/landing.js"></script>
</body>
</html>