<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | Reservations</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #070707; --card: #111; --success: #2ecc71; --pending: #f1c40f; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--dark); color: white; font-family: 'Inter', sans-serif; display: flex; }
        
        /* Sidebar Consistency */
        .admin-sidebar { width: 280px; height: 100vh; background: #0f0f0f; border-right: 1px solid #222; padding: 40px 20px; position: fixed; }
        .admin-logo { font-size: 1.5rem; font-weight: 800; margin-bottom: 50px; color: white; text-decoration: none; display: block; }
        .admin-logo span { color: var(--primary); }
        .nav-link { display: flex; align-items: center; gap: 12px; color: #888; text-decoration: none; padding: 12px; border-radius: 8px; transition: 0.3s; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: #1a1a1a; color: var(--primary); }

        .main-admin { margin-left: 280px; width: 100%; padding: 50px; }
        
        /* Table Styles */
        .reservation-table { width: 100%; border-collapse: collapse; margin-top: 30px; background: var(--card); border-radius: 15px; overflow: hidden; border: 1px solid #222; }
        .reservation-table th { text-align: left; padding: 20px; background: #1a1a1a; color: #666; font-size: 0.75rem; text-transform: uppercase; }
        .reservation-table td { padding: 20px; border-bottom: 1px solid #222; font-size: 0.9rem; vertical-align: middle; }
        
        .client-info { display: flex; flex-direction: column; }
        .client-name { font-weight: 600; color: white; }
        .client-email { font-size: 0.8rem; color: #555; }

        .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; }
        .status-pending { background: rgba(241, 196, 15, 0.1); color: var(--pending); }
        .status-confirmed { background: rgba(46, 204, 113, 0.1); color: var(--success); }

        .action-btn { background: transparent; border: 1px solid #333; color: white; padding: 8px 12px; border-radius: 6px; cursor: pointer; transition: 0.3s; font-size: 0.8rem; }
        .action-btn:hover { border-color: var(--primary); color: var(--primary); }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <a href="index.php?action=admin_dashboard" class="admin-logo">Admin<span>Portal</span></a>
        <a href="index.php?action=admin_dashboard" class="nav-link">Dashboard</a>
        <a href="index.php?action=add_property" class="nav-link">Add Property</a>
        <a href="index.php?action=reservations" class="nav-link active">Reservations</a>
        <a href="index.php?action=user_management" class="nav-link">User Management</a>
        
    </aside>

    <main class="main-admin">
        <header>
            <h1 style="font-size: 2rem;">Booking Requests</h1>
            <p style="color: #666;">Manage tour schedules and property reservations from potential clients.</p>
        </header>

        <table class="reservation-table">
            <thead>
                <tr>
                    <th>Client Details</th>
                    <th>Property Interested</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="client-info">
                            <span class="client-name">Juan Dela Cruz</span>
                            <span class="client-email">juan.dc@email.com</span>
                        </div>
                    </td>
                    <td>Modern Zen Villa</td>
                    <td>April 12, 2026 | 10:00 AM</td>
                    <td><span class="status-badge status-pending">Pending Review</span></td>
                    <td><button class="action-btn">Approve</button></td>
                </tr>
                <tr>
                    <td>
                        <div class="client-info">
                            <span class="client-name">Maria Clara</span>
                            <span class="client-email">mclara@email.com</span>
                        </div>
                    </td>
                    <td>Oceanview Mansion</td>
                    <td>April 15, 2026 | 2:30 PM</td>
                    <td><span class="status-badge status-confirmed">Confirmed</span></td>
                    <td><button class="action-btn">Reschedule</button></td>
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