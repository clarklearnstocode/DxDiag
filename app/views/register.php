<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstateBook | Join the Elite</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #0a0a0a; --bg: #121212; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        
        body { background: var(--dark); color: white; display: flex; height: 100vh; overflow: hidden; }

        /* Left Side: The Image */
        .image-side {
            flex: 1.5;
            background: linear-gradient(to right, transparent, var(--dark)), 
                        url('assets/img/landinging.png') center/cover;
            display: none;
        }

        /* Right Side: The Form */
        .form-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 60px;
            background: var(--dark);
            overflow-y: auto;
        }

        /* Scrollbar Styling */
        .form-side::-webkit-scrollbar { width: 5px; }
        .form-side::-webkit-scrollbar-track { background: var(--dark); }
        .form-side::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }

        @media (min-width: 768px) { .image-side { display: block; } }

        .form-container { max-width: 400px; width: 100%; margin: 0 auto; }
        
        .logo { font-weight: 800; font-size: 1.5rem; margin-bottom: 25px; cursor: pointer; }
        .logo span { color: var(--primary); }

        h2 { font-size: 2rem; margin-bottom: 8px; letter-spacing: -1px; }
        p.subtitle { color: #666; margin-bottom: 25px; font-size: 0.9rem; }

        .input-group { margin-bottom: 15px; }
        label { display: block; font-size: 0.7rem; font-weight: 800; color: var(--primary); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 1px; }
        
        input {
            width: 100%;
            background: #1a1a1a;
            border: 1px solid #333;
            padding: 14px;
            border-radius: 12px;
            color: white;
            outline: none;
            transition: 0.3s;
        }

        input:focus { border-color: var(--primary); background: #222; }

        .btn-reg {
            width: 100%;
            background: var(--primary);
            color: black;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-reg:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(201, 160, 122, 0.2); }

        .footer-link { text-align: center; margin-top: 20px; font-size: 0.85rem; color: #888; }
        .footer-link a { color: var(--primary); text-decoration: none; font-weight: 600; }

        .back-home {
            position: absolute;
            top: 30px;
            right: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #888;
            font-size: 0.9rem;
            font-weight: 600;
            z-index: 100;
        }

        .back-home:hover { color: var(--primary); }

        /* Animations */
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        body { animation: slideInRight 0.5s ease-out; }
    </style>
</head>
<body>

    <a href="index.php?action=landing" class="back-home">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Back to Home</span>
    </a>

    <div class="image-side"></div>
    <div class="form-side">
        <div class="form-container">
            <div class="logo" onclick="window.location.href='index.php?action=landing'">Estate<span>Book</span></div>
            <h2>Create Account</h2>
            <p class="subtitle">Join the elite community of Bacolod homeowners.</p>

            <form action="index.php?action=handleSignup" method="POST">
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="Clark Sabordo" required>
                </div>

                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="clark_dev" required>
                </div>

                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="clark@example.com" required>
                </div>

                <div class="input-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="0912 345 6789" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-reg">Get Started</button>
            </form>

            <div class="footer-link">
                Already part of the elite? <a href="index.php?action=login">Sign In</a>
            </div>
        </div>
    </div>

    <script>
        // Smooth Page Transition
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const targetUrl = this.getAttribute('href');
                if (!targetUrl || targetUrl.startsWith('#')) return;

                e.preventDefault();
                document.body.classList.add('page-exit');
                
                setTimeout(() => {
                    window.location.href = targetUrl;
                }, 400); 
            });
        });
    </script>
</body>
</html>