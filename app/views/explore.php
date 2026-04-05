<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstateBook | Explore Our Vision</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/landing.css"> 
    <style>
        :root { --primary: #c9a07a; --dark: #0a0a0a; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        
        body { 
            background: var(--dark); 
            color: white; s
            min-height: 100vh;
            overflow-x: hidden;
            /* Entrance Animation */
            animation: slideInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        /* --- ANIMATIONS --- */

        /* Entrance: Slide up from below */
        @keyframes slideInUp {
            from { 
                opacity: 0; 
                transform: translateY(40px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        /* Exit: Slide out to the left (Matches your Login/Register logic) */
        @keyframes slideOutLeft {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(-40px); }
        }

        .page-exit {
            animation: slideOutLeft 0.5s ease-in forwards !important;
        }

        /* --- UI STYLING --- */

        .glass-nav {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 1200px;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            z-index: 1000;
        }

        .explore-hero {
            height: 60vh;
            background: linear-gradient(to bottom, rgba(10,10,10,0.4), var(--dark)), 
                        url('../public/assets/img/explore.jpg') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 10px;
        }

        .about-section {
            max-width: 800px;
            margin: -50px auto 100px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            line-height: 1.8;
        }

        .about-section h2 { color: var(--primary); margin-bottom: 20px; }
        .about-section p { color: #ccc; margin-bottom: 20px; }
    </style>
</head>
<body>

    <nav class="glass-nav">
        <div class="logo" style="cursor:pointer;">Estate<span>Book</span></div>
        <ul class="nav-links">
            <li><a href="index.php?action=landing">Home</a></li>
            <li><a href="index.php?action=explore">Explore</a></li>
            <li><a href="index.php?action=login" class="btn-outline">Sign In</a></li>
            <li><a href="index.php?action=register" class="btn-filled">Get Started</a></li>
        </ul>
    </nav>

    <section class="explore-hero">
        <div class="hero-content">
            <p style="color: var(--primary); font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">Discover the Standard</p>
            <h1>About EstateBook</h1>
        </div>
    </section>

    <main class="container">
        <div class="about-section">
            <h2>Redefining Modern Living</h2>
            <p>
                Welcome to <strong>EstateBook</strong>, the premier platform for luxury real estate in Metro Bacolod. 
                We bridge the gap between exclusive property owners and elite clients looking for their next 
                sophisticated sanctuary.
            </p>
            <p>
                Whether you're looking for a Modern Zen Villa in Lacson St or a Tropical Loft in Alangilan, 
                we provide the tools to explore, decide, and secure your stay.
            </p>
        </div>
    </main>

    <script>
        // Smooth Exit Logic
        document.querySelectorAll('a, .logo').forEach(link => {
            link.addEventListener('click', function(e) {
                // Determine target URL
                let targetUrl = "";
                if (this.tagName === 'A') {
                    targetUrl = this.href;
                } else if (this.classList.contains('logo')) {
                    targetUrl = "index.php?action=landing";
                }

                // Only animate if it's an internal link and not a hash
                if (targetUrl && !targetUrl.includes('#') && targetUrl.includes('index.php')) {
                    e.preventDefault();
                    
                    // Add exit class
                    document.body.classList.add('page-exit');

                    // Match the 500ms duration of the CSS animation
                    setTimeout(() => {
                        window.location.href = targetUrl;
                    }, 450);
                }
            });
        });
    </script>
</body>
</html>