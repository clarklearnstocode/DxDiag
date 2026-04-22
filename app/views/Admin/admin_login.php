<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstateBook | Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #0a0a0a; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            background: var(--dark); 
            color: white; 
            font-family: 'Inter', sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh;
        }
        .login-card {
            background: #111;
            padding: 40px;
            border-radius: 20px;
            border: 1px solid #222;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .logo { font-weight: 800; font-size: 1.5rem; margin-bottom: 10px; }
        .logo span { color: var(--primary); }
        .badge { 
            background: rgba(201, 160, 122, 0.1); 
            color: var(--primary); 
            padding: 5px 12px; 
            border-radius: 50px; 
            font-size: 0.7rem; 
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 25px;
            display: inline-block;
        }
        input {
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 10px;
            color: white;
            outline: none;
        }
        .btn-admin-login {
            width: 100%;
            padding: 15px;
            background: var(--primary);
            border: none;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-admin-login:hover { transform: translateY(-2px); filter: brightness(1.1); }
        .back-link { margin-top: 20px; display: block; color: #555; text-decoration: none; font-size: 0.85rem; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo">Estate<span>Book</span></div>
        <span class="badge">Administrative Access</span>
        
        <form action="index.php?action=do_admin_login" method="POST">
            <input type="text" name="username" placeholder="Admin Username" required>
            <input type="password" name="passcode" placeholder="Passcode" required>
            <button type="submit" class="btn-admin-login">Access Dashboard</button>
        </form>

        <a href="index.php?action=login" class="back-link">← Back to User Login</a>
    </div>

</body>
</html>