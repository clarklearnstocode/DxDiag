<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstateBook | Premium Real Estate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,800;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/landing.css?v=3.1">
</head>
<body id="home">

    <!-- Property data for JS slideshow -->
    <div id="landing-data"
         data-slides='<?php echo htmlspecialchars(json_encode(array_values($properties)), ENT_QUOTES, "UTF-8"); ?>'></div>

    <!-- ══════════════════════════════════════════
         NAV
    ══════════════════════════════════════════ -->
    <nav class="glass-nav">
        <div class="logo logo-clickable" onclick="window.location.href='#home'">Estate<span>Book</span></div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#explore">Estates</a></li>
            <li><a href="#mission">About</a></li>
            <li><a href="index.php?action=login">Log In</a></li>
            <li><a href="#contact" class="btn-contact">Contact Us</a></li>
        </ul>
    </nav>

    <!-- ══════════════════════════════════════════
         HERO — split: text left + floating card right
    ══════════════════════════════════════════ -->
    <section class="hero">
        <!-- background handled by CSS -->

        <div class="hero-inner">

            <!-- LEFT: Editorial copy -->
            <div class="hero-content">
                <span class="badge">Premium Properties</span>
                <h1>Find Nearby<br><em>Luxurious</em><br>Estates</h1>
                <p>Enhance the experience of finding and booking luxury villas with EstateBook's curated collection of exclusive Bacolod estates.</p>

                <div class="hero-ctas">
                    <a href="#explore" class="btn-primary-hero">Explore Estates</a>
                    <a href="index.php?action=login" class="btn-ghost-hero">Sign In &rarr;</a>
                </div>

                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-num" id="statPropCount"><?php echo count($properties); ?>+</span>
                        <span class="stat-label">Luxury Estates</span>
                    </div>
                    <div class="stat-sep"></div>
                    <div class="stat-item">
                        <span class="stat-num">100%</span>
                        <span class="stat-label">Verified Listings</span>
                    </div>
                    <div class="stat-sep"></div>
                    <div class="stat-item">
                        <span class="stat-num">24/7</span>
                        <span class="stat-label">Support</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Floating property card -->
            <div class="hero-card-col">
                <div class="hero-card" id="heroCard">

                    <div class="hero-card-img-wrap">
                        <img id="main-slide-img"
                             src="assets/img/<?php echo htmlspecialchars(!empty($properties[0]['image_path']) ? $properties[0]['image_path'] : 'villa1.png'); ?>"
                             alt="Featured Property"
                             onerror="this.src='assets/img/villa1.png'">
                        <div class="card-live-tag">
                            <span class="live-dot"></span> Live Listings
                        </div>
                        <div class="card-img-overlay"></div>
                    </div>

                    <div class="hero-card-body">
                        <div class="card-prop-row">
                            <div class="card-prop-text">
                                <div class="card-prop-name" id="slide-title">
                                    <?php echo htmlspecialchars($properties[0]['Property_Name'] ?? 'Luxury Estate'); ?>
                                </div>
                                <div class="card-prop-loc" id="slide-loc">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <?php echo htmlspecialchars($properties[0]['Property_location'] ?? 'Bacolod City'); ?>
                                </div>
                            </div>
                            <div class="card-rate-box">
                                <div class="card-rate" id="slide-rate">
                                    ₱<?php echo !empty($properties[0]['Property_rate']) ? number_format($properties[0]['Property_rate']) : '0'; ?>
                                </div>
                                <div class="card-rate-unit">/night</div>
                            </div>
                        </div>

                        <div class="card-amenities" id="slide-amenities">
                            <span><?php echo !empty($properties[0]['Property_capacity']) ? '👥 '.$properties[0]['Property_capacity'].' guests' : '👥 —'; ?></span>
                            <span><?php echo !empty($properties[0]['Property_bathrooms']) ? '🚿 '.$properties[0]['Property_bathrooms'].' baths' : ''; ?></span>
                            <span><?php echo !empty($properties[0]['Has_pool']) && $properties[0]['Has_pool'] != '0' ? '🏊 Pool' : ''; ?></span>
                        </div>

                        <a href="index.php?action=login" class="card-cta-btn">Reserve This Estate →</a>
                    </div>

                    <!-- Slide dots -->
                    <div class="slide-dots" id="slideDots">
                        <?php foreach ($properties as $i => $p): ?>
                            <button class="slide-dot <?php echo $i === 0 ? 'active' : ''; ?>"
                                    data-idx="<?php echo $i; ?>"
                                    onclick="goToSlide(<?php echo $i; ?>)"
                                    aria-label="Slide <?php echo $i+1; ?>"></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Floating accent badge -->
                <div class="hero-badge-float">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#cdaa56" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span>Exclusively Curated</span>
                </div>
            </div>

        </div><!-- /hero-inner -->

        <!-- Scroll indicator -->
        <div class="scroll-cue" onclick="document.getElementById('explore').scrollIntoView({behavior:'smooth'})">
            <span>Scroll</span>
            <div class="scroll-cue-line"></div>
        </div>
    </section>

    <!-- ══════════════════════════════════════════
         FEATURED ESTATES
    ══════════════════════════════════════════ -->
    <section id="explore" class="explore-section fade-section">
        <div class="section-header">
            <h2>Our Featured Estates</h2>
            <p>Hand-picked premium properties in Bacolod Metro Area</p>
        </div>

        <div class="property-grid-lite">
            <?php if (!empty($properties)): ?>
                <?php foreach ($properties as $property): ?>
                    <div class="featured-card fade-card">
                        <div class="card-image-wrap">
                            <img src="assets/img/<?php echo htmlspecialchars($property['image_path']); ?>"
                                 onerror="this.onerror=null;this.src='assets/img/villa1.png';"
                                 class="featured-card-image">
                            <?php if (!empty($property['Has_pool']) && $property['Has_pool'] != '0'): ?>
                                <div class="card-pool-ribbon">🏊 Pool</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-details-box">
                            <h4 class="featured-card-title"><?php echo htmlspecialchars($property['Property_Name']); ?></h4>
                            <p class="featured-card-location">📍 <?php echo htmlspecialchars($property['Property_location']); ?></p>
                            <div class="card-specs-row">
                                <?php if (!empty($property['Property_capacity'])): ?>
                                    <span>👥 <?php echo $property['Property_capacity']; ?></span>
                                <?php endif; ?>
                                <?php if (!empty($property['Property_bathrooms'])): ?>
                                    <span>🚿 <?php echo $property['Property_bathrooms']; ?></span>
                                <?php endif; ?>
                                <?php if (!empty($property['Property_size'])): ?>
                                    <span>📐 <?php echo $property['Property_size']; ?>m²</span>
                                <?php endif; ?>
                            </div>
                            <p class="featured-card-rate">₱<?php echo number_format($property['Property_rate']); ?><small>/night</small></p>
                            <a href="index.php?action=login" class="btn-book-lite">Book Now</a>
                        </div>
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

    <!-- ══════════════════════════════════════════
         MISSION & VISION
    ══════════════════════════════════════════ -->
    <section id="mission" class="mission-section fade-section">

        <div class="mission-inner">

            <div class="mission-header fade-up">
                <span class="mission-eyebrow">Who We Are</span>
                <h2>Redefining Luxury<br>Real Estate in the Philippines</h2>
            </div>

            <div class="mission-cards">

                <div class="mission-card fade-up" style="--delay: 0.1s">
                    <div class="mission-card-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div class="mission-card-label">Our Mission</div>
                    <h3>To Connect People with Extraordinary Homes</h3>
                    <p>We democratize access to luxury living by connecting discerning clients with the Philippines' most exclusive properties — through transparent, technology-forward real estate solutions that put the client first at every step.</p>
                    <div class="mission-card-line"></div>
                </div>

                <div class="mission-card mission-card-gold fade-up" style="--delay: 0.22s">
                    <div class="mission-card-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="2" y1="12" x2="22" y2="12"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <div class="mission-card-label">Our Vision</div>
                    <h3>The Philippines' Most Trusted Luxury Platform</h3>
                    <p>To become the country's premier luxury real estate platform — redefining how premium properties are discovered, reserved, and experienced, while building lasting trust with every booking and every guest.</p>
                    <div class="mission-card-line"></div>
                </div>

            </div>

            <!-- Values strip -->
            <div class="values-strip fade-up" style="--delay: 0.35s">
                <div class="value-item">
                    <div class="value-icon">✦</div>
                    <div class="value-label">Transparency</div>
                </div>
                <div class="value-sep"></div>
                <div class="value-item">
                    <div class="value-icon">✦</div>
                    <div class="value-label">Excellence</div>
                </div>
                <div class="value-sep"></div>
                <div class="value-item">
                    <div class="value-icon">✦</div>
                    <div class="value-label">Innovation</div>
                </div>
                <div class="value-sep"></div>
                <div class="value-item">
                    <div class="value-icon">✦</div>
                    <div class="value-label">Trust</div>
                </div>
                <div class="value-sep"></div>
                <div class="value-item">
                    <div class="value-icon">✦</div>
                    <div class="value-label">Luxury</div>
                </div>
            </div>

        </div>
    </section>

    <!-- ══════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════ -->
    <footer id="contact" class="main-footer fade-section">
        <div class="footer-content">
            <div class="footer-brand">
                <div class="logo footer-logo">Estate<span>Book</span></div>
                <p>Providing the most exclusive property deals in Bacolod. Find your dream home with our expert team.</p>
            </div>

            <div class="footer-links">
                <h4>Navigate</h4>
                <a href="#home">Home</a>
                <a href="#explore">Estates</a>
                <a href="#mission">About</a>
                <a href="index.php?action=login">Sign In</a>
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
            &copy; <?php echo date("Y"); ?> EstateBook Real Estate &mdash; All rights reserved.
        </div>
    </footer>

    <script src="assets/js/landing.js"></script>
</body>
</html>
