<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | User Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #070707; --card: #111; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--dark); color: white; font-family: 'Inter', sans-serif; display: flex; }
        
        /* Sidebar Styles */
        .admin-sidebar { width: 280px; height: 100vh; background: #0f0f0f; border-right: 1px solid #222; padding: 40px 20px; position: fixed; }
        .admin-logo { font-size: 1.5rem; font-weight: 800; margin-bottom: 50px; color: white; text-decoration: none; display: block; }
        .admin-logo span { color: var(--primary); }
        
        .nav-group { margin-bottom: 30px; }
        .nav-label { font-size: 0.7rem; color: #444; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; display: block; }
        .nav-link { display: flex; align-items: center; gap: 12px; color: #888; text-decoration: none; padding: 12px; border-radius: 8px; transition: 0.3s; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: #1a1a1a; color: var(--primary); }

        .main-admin { margin-left: 280px; width: 100%; padding: 50px; }
        
        /* User Table Styles */
        .user-table { width: 100%; border-collapse: collapse; margin-top: 30px; background: var(--card); border-radius: 15px; overflow: hidden; border: 1px solid #222; }
        .user-table th { text-align: left; padding: 20px; background: #1a1a1a; color: #666; font-size: 0.75rem; text-transform: uppercase; }
        .user-table td { padding: 20px; border-bottom: 1px solid #222; font-size: 0.9rem; }
        
        .user-profile { display: flex; align-items: center; gap: 12px; }
        .avatar { width: 35px; height: 35px; background: #333; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; color: var(--primary); }

        .role-badge { padding: 4px 10px; border-radius: 4px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .role-admin { background: rgba(201, 160, 122, 0.1); color: var(--primary); }
        .role-client { background: rgba(255, 255, 255, 0.05); color: #888; }

        .btn-action { background: none; border: none; color: #444; cursor: pointer; font-size: 1.2rem; transition: 0.3s; }
        .btn-action:hover { color: #ff4444; }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <a href="#" class="admin-logo">Admin<span>Portal</span></a>
        
        <div class="nav-group">
            <span class="nav-label">Main Menu</span>
            <a href="index.php?action=admin_dashboard" class="nav-link">Dashboard</a>
            <a href="index.php?action=add_property" class="nav-link">Add Property</a>           
            <a href="index.php?action=reservations" class="nav-link">Reservations</a>
            <a href="index.php?action=user_management" class="nav-link active">User Management</a>
        </div>

        <div class="nav-group">
            <span class="nav-label">Settings</span>
            <a href="index.php?action=home" class="nav-link" style="color: #ff4444;">Exit Admin</a>
        </div>
    </aside>

    <main class="main-admin">
        <header>
            <h1 style="font-size: 2rem;">User Management</h1>
            <p style="color: #666;">View and manage registered accounts and staff permissions.</p>
        </header>

        <table class="user-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email Address</th>
                    <th>Role</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="user-profile">
                            <div class="avatar">CS</div>
                            <span style="font-weight: 600;">Clark Sabordo</span>
                        </div>
                    </td>
                    <td>ksabordo22@gmail.com</td>
                    <td><span class="role-badge role-admin">Administrator</span></td>
                    <td>Jan 15, 2026</td>
                    <td><button class="btn-action">×</button></td>
                </tr>
                <tr>
                    <td>
                        <div class="user-profile">
                            <div class="avatar">JD</div>
                            <span style="font-weight: 600;">Juan Dela Cruz</span>
                        </div>
                    </td>
                    <td>juan.dc@example.ph</td>
                    <td><span class="role-badge role-client">Client</span></td>
                    <td>Feb 02, 2026</td>
                    <td><button class="btn-action">×</button></td>
                </tr>
            </tbody>
        </table>
    </main>

    
</body>
</html>

</body>
</html>