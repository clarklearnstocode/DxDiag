<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstateBook | Welcome Back</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #0a0a0a; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        
        body { background: var(--dark); color: white; display: flex; height: 100vh; overflow: hidden; }

        /* Left Side: Image (To match Registration) */
        .image-side {
            flex: 1.5;
            background: linear-gradient(to right, transparent, var(--dark)), 
                        url('assets/img/landinging.png') center/cover;
            display: none;
        }

        /* Right Side: Form */
        .form-side { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            padding: 60px; 
            background: var(--dark);
        }

        @media (min-width: 768px) { .image-side { display: block; } }

        .form-container { max-width: 400px; width: 100%; margin: 0 auto; }
        
        .logo { font-weight: 800; font-size: 1.5rem; margin-bottom: 40px; cursor: pointer; }
        .logo span { color: var(--primary); }
        h2 { font-size: 2.2rem; margin-bottom: 10px; letter-spacing: -1px; }
        p.subtitle { color: #666; margin-bottom: 30px; }

        .input-group { margin-bottom: 20px; }
        label { display: block; font-size: 0.7rem; font-weight: 800; color: var(--primary); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; }
        
        input { 
            width: 100%; 
            background: #1a1a1a; 
            border: 1px solid #333; 
            padding: 15px; 
            border-radius: 12px; 
            color: white; 
            outline: none; 
            transition: 0.3s;
        }
        input:focus { border-color: var(--primary); background: #222; }

        .btn-login { 
            width: 100%; 
            background: var(--primary); 
            color: black; 
            border: none; 
            padding: 18px; 
            border-radius: 12px; 
            font-weight: 800; 
            cursor: pointer; 
            transition: 0.3s; 
        }
        .btn-login:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(201, 160, 122, 0.2); }

        .footer-link { text-align: center; margin-top: 30px; font-size: 0.85rem; color: #888; }
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
            transition: all 0.3s ease;
            z-index: 100;
        }
        .back-home:hover { color: var(--primary); }

        /* Animations */
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        body { animation: slideInRight 0.5s ease-out; }
        .page-exit { animation: slideOutLeft 0.4s ease-in forwards !important; }
        
        @keyframes slideOutLeft {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(-40px); }
        }
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
            
            <?php if(isset($_GET['success'])): ?>
                <p style="color: #4CAF50; background: rgba(76, 175, 80, 0.1); padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; border: 1px solid #4CAF50;">
                    Account created! Please sign in.
                </p>
            <?php endif; ?>

            <h2>Welcome Back</h2>
            <p class="subtitle">Enter your details to access your dashboard.</p>

            <form action="index.php?action=authenticate" method="POST">
                <div class="input-group">
                    <label>Username or Email</label>
                    <input type="text" name="login_identity" placeholder="clark_dev" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <div class="footer-link">
                New to EstateBook? <a href="index.php?action=register">Create Account</a>
            </div>

            <div style="margin-top: 25px; border-top: 1px solid #222; padding-top: 20px;">
                <p style="color: #555; font-size: 0.85rem; text-align: center;">
                    Are you a staff member? 
                    <a href="index.php?action=admin_login" style="color: #c9a07a; text-decoration: none; font-weight: bold;">
                        Admin Portal →
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const targetUrl = this.getAttribute('href');
                if (!targetUrl || targetUrl.startsWith('#')) return;
                e.preventDefault();
                document.body.classList.add('page-exit');
                setTimeout(() => { window.location.href = targetUrl; }, 100); 
            });
        });
    </script>
</body>
</html>