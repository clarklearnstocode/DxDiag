<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | Management</title>
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

        /* Main Content */
        .main-admin { margin-left: 280px; width: 100%; padding: 50px; }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .btn-add { background: var(--primary); color: black; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 800; font-size: 0.9rem; }

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: var(--card); padding: 25px; border-radius: 15px; border: 1px solid #222; }
        .stat-card h2 { font-size: 1.8rem; margin-bottom: 5px; }
        .stat-card p { color: #666; font-size: 0.85rem; }

        /* Management Table */
        .data-table { width: 100%; border-collapse: collapse; background: var(--card); border-radius: 15px; overflow: hidden; border: 1px solid #222; }
        .data-table th { text-align: left; padding: 20px; background: #1a1a1a; font-size: 0.8rem; color: #666; text-transform: uppercase; }
        .data-table td { padding: 20px; border-bottom: 1px solid #222; font-size: 0.9rem; }
        .status-pill { padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .available { background: rgba(0, 255, 0, 0.1); color: #00ff00; }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <a href="#" class="admin-logo">Admin<span>Portal</span></a>
        
        <div class="nav-group">
            <span class="nav-label">Main Menu</span>
            <a href="index.php?action=admin_dashboard" class="nav-link active">Dashboard</a>
            <a href="index.php?action=add_property" class="nav-link">Add Property</a>           
            <a href="index.php?action=reservations" class="nav-link">Reservations</a>
            <a href="index.php?action=user_management" class="nav-link">User Management</a>
        </div>

        <div class="nav-group">
            <span class="nav-label">Settings</span>
            <a href="index.php?action=home" class="nav-link" style="color: #ff4444;">Exit Admin</a>
        </div>
    </aside>

    <main class="main-admin">
        <div class="header-flex">
            <div>
                <h1>Management Overview</h1>
                <p style="color: #666;">Welcome back, Administrator.</p>
            </div>
            <a href="index.php?action=add_property" class="btn-add">+ New Property</a>
        </div>

        <div class="stats-grid">
            <div class="stat-card"><h2>24</h2><p>Total Properties</p></div>
            <div class="stat-card"><h2>156</h2><p>Total Bookings</p></div>
            <div class="stat-card"><h2>$2.4M</h2><p>Revenue (Mock)</p></div>
            <div class="stat-card"><h2>12</h2><p>Pending Reviews</p></div>
        </div>

        <h2 style="margin-bottom: 20px;">Recent Listings</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Property Name</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Modern Zen Villa</td>
                    <td>Lacson St, Bacolod</td>
                    <td>₱12,500,000</td>
                    <td><span class="status-pill available">Available</span></td>
                    <td><a href="#" style="color: var(--primary); text-decoration: none;">Edit</a></td>
                </tr>
                <tr>
                    <td>Oceanview Mansion</td>
                    <td>Silay City</td>
                    <td>₱45,000,000</td>
                    <td><span class="status-pill available">Available</span></td>
                    <td><a href="#" style="color: var(--primary); text-decoration: none;">Edit</a></td>
                </tr>
            </tbody>
        </table>
    </main>

    <script>
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const targetUrl = this.getAttribute('href');

                // Only apply transition if there is a valid link
                if (targetUrl && targetUrl !== '#' && !targetUrl.startsWith('#')) {
                    e.preventDefault(); 
                    
                    // Add a fade-out effect to the body
                    document.body.style.transition = "opacity 0.4s ease";
                    document.body.style.opacity = "0";
                    
                    setTimeout(() => {
                        window.location.href = targetUrl;
                    }, 400); 
                }
            });
        });
    </script>
</body>
</html>

</body>
</html>