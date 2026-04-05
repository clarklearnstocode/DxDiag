<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstateBook | Premium Real Estate</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <!-- Link to CSS -->
    <link rel="stylesheet" href="/EstateBook(0)/public/assets/css/landing.css">
    
    <style>
        /* Smooth Scrolling */
        html { scroll-behavior: smooth; }

        /* Featured Section Styling */
        .explore-section { padding: 100px 10%; background: #0a0a0a; color: white; }
        .section-header { margin-bottom: 50px; text-align: center; }
        .section-header h2 { font-size: 2.5rem; font-weight: 800; margin-bottom: 10px; }
        .section-header p { color: #666; font-size: 1.1rem; }

        .property-grid-lite { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 30px; 
            margin-bottom: 50px;
        }

        /* Footer Styling */
        .main-footer { background: #070707; padding: 80px 10% 30px; border-top: 1px solid #1a1a1a; margin-top: 50px; }
        .footer-content { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 50px; margin-bottom: 50px; }
        .footer-brand p { color: #666; margin-top: 15px; font-size: 0.9rem; line-height: 1.6; }
        .footer-links h4, .footer-social h4 { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: #c9a07a; margin-bottom: 25px; }
        .footer-links a { display: block; color: #888; text-decoration: none; margin-bottom: 12px; font-size: 0.9rem; transition: 0.3s; }
        .footer-links a:hover { color: #c9a07a; }
        
        .social-icons { display: flex; gap: 15px; }
        .social-icons a { 
            width: 40px; height: 40px; background: #111; border: 1px solid #222; 
            display: flex; align-items: center; justify-content: center; border-radius: 50%; 
            transition: 0.3s; overflow: hidden;
        }
        
        /* Icon Image Styling */
        .social-icons a img {
            width: 20px; /* Adjust size as needed */
            height: 20px;
            object-fit: contain;
            filter: brightness(0) invert(1); /* Makes black icons white to match theme */
            transition: 0.3s;
        }

        .social-icons a:hover { background: #c9a07a; border-color: #c9a07a; transform: translateY(-3px); }
        .social-icons a:hover img { filter: brightness(0); /* Makes icon black when background turns gold */ }

        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid #111; color: #333; font-size: 0.8rem; }
        
    </style>
</head>
<body id="home">

    <nav class="glass-nav">
        <div class="logo" style="cursor:pointer;" onclick="window.location.href='#home'">Estate<span>Book</span></div>
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
                <div style="width:1px; height:30px; background:#eee;"></div>
                <div class="search-group">
                    <label>Property Type</label>
                    <input type="text" value="Modern Villa" readonly>
                </div>
                <button class="btn-search" onclick="window.location.href='#explore'">Explore</button>
            </div>
        </div>

        <div class="floating-card">
            <div class="slideshow-container">
                <img id="main-slide-img" src="assets/img/villa1.png" alt="Villa" style="transition: opacity 0.6s ease;">
            </div>
            <div class="card-info">
                <h3 id="slide-title">Modern Zen Villa</h3>
                <p id="slide-loc">Lacson St, Bacolod City</p>
            </div>
        </div>
    </section>

    <section id="explore" class="explore-section">
        <div class="section-header">
            <h2>Our Featured Estates</h2>
            <p>Hand-picked premium properties in Bacolod Metro Area</p>
        </div>

        <div class="property-grid-lite">
            <div style="background:#111; border-radius:20px; padding:20px; border:1px solid #222;">
                <img src="assets/img/villa1.png" style="width:100%; border-radius:15px; margin-bottom:15px;">
                <h4 style="color:#c9a07a;">Luxury Villa with Pool</h4>
                <p style="color:#888; font-size:0.9rem;">Alijis, Bacolod City</p>
            </div>
            <div style="background:#111; border-radius:20px; padding:20px; border:1px solid #222;">
                <img src="assets/img/villa2.png" style="width:100%; border-radius:15px; margin-bottom:15px;">
                <h4 style="color:#c9a07a;">Modern Condo</h4>
                <p style="color:#888; font-size:0.9rem;">Lacson St, Bacolod City</p>
            </div>
            <div style="background:#111; border-radius:20px; padding:20px; border:1px solid #222;">
                <img src="assets/img/villa3.png" style="width:100%; border-radius:15px; margin-bottom:15px;">
                <h4 style="color:#c9a07a;">Heritage Mansion</h4>
                <p style="color:#888; font-size:0.9rem;">Silay City</p>
            </div>
        </div>
        
        <div style="text-align:center;">
            <a href="index.php?action=login" style="color:white; text-decoration:none; border:1px solid #c9a07a; padding:12px 30px; border-radius:30px;">View Full Dashboard →</a>
        </div>
    </section>

    <footer id="contact" class="main-footer">
        <div class="footer-content">
            <div class="footer-brand">
                <div class="logo" style="margin-bottom:20px;">Estate<span>Book</span></div>
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
            &copy; 2026 EstateBook Real Estate. All rights reserved.
        </div>
    </footer>

    <script>
        // Smooth scroll for anchors
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Slideshow Logic
        const slidesData = [
            { img: 'assets/img/villa1.png', title: 'Modern Zen Villa', loc: 'Lacson St, Bacolod City' },
            { img: 'assets/img/villa2.png', title: 'Tropical Loft', loc: 'Alangilan, Bacolod City' },
            { img: 'assets/img/villa3.png', title: 'Oceanview Mansion', loc: 'Silay City, Metro Bacolod' }
        ];

        const slideImg = document.getElementById('main-slide-img');
        const slideTitle = document.getElementById('slide-title');
        const slideLoc = document.getElementById('slide-loc');
        let currentSlide = 0;

        function showNextSlide() {
            if (!slideImg) return; 
            slideImg.style.opacity = '0';
            setTimeout(() => {
                currentSlide = (currentSlide + 1) % slidesData.length;
                slideImg.src = slidesData[currentSlide].img;
                slideTitle.textContent = slidesData[currentSlide].title;
                slideLoc.textContent = slidesData[currentSlide].loc;
                slideImg.style.opacity = '1';
            }, 600); 
        }

        setInterval(showNextSlide, 4000);
    </script>
</body>
</html>