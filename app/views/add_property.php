<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | Add New Property</title>
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

        .main-admin { margin-left: 280px; width: 100%; padding: 50px; display: flex; justify-content: center; }
        .form-container { width: 100%; max-width: 800px; }
        
        .form-card { background: var(--card); padding: 40px; border-radius: 20px; border: 1px solid #222; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px; }
        
        .input-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
        .full-width { grid-column: span 2; }
        
        label { font-size: 0.85rem; color: #666; font-weight: 600; text-transform: uppercase; }
        input, select, textarea { 
            background: #1a1a1a; border: 1px solid #333; padding: 15px; border-radius: 10px; color: white; outline: none; transition: 0.3s; 
        }
        input:focus { border-color: var(--primary); }
        
        .image-upload-box {
            border: 2px dashed #333; padding: 40px; border-radius: 15px; text-align: center; cursor: pointer; transition: 0.3s;
        }
        .image-upload-box:hover { border-color: var(--primary); background: rgba(201, 160, 122, 0.05); }

        .btn-save { 
            background: var(--primary); color: black; padding: 18px; border: none; border-radius: 12px; 
            font-weight: 800; width: 100%; cursor: pointer; margin-top: 20px; transition: 0.3s;
        }
        .btn-save:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(201, 160, 122, 0.2); }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <a href="#" class="admin-logo">Admin<span>Portal</span></a>
        
        <div class="nav-group">
            <span class="nav-label">Main Menu</span>
            <a href="index.php?action=admin_dashboard" class="nav-link">Dashboard</a>
            <a href="index.php?action=add_property" class="nav-link active">Add Property</a>           
            <a href="index.php?action=reservations" class="nav-link">Reservations</a>
            <a href="index.php?action=user_management" class="nav-link">User Management</a>
        </div>

        <div class="nav-group">
            <span class="nav-label">Settings</span>
            <a href="index.php?action=home" class="nav-link" style="color: #ff4444;">Exit Admin</a>
        </div>
    </aside>

    <main class="main-admin">
        <div class="form-container">
            <header style="margin-bottom: 30px;">
                <h1 style="font-size: 2rem;">List a New Property</h1>
                <p style="color: #666;">Fill in the details to add a new estate to your curated collection.</p>
            </header>

            <form class="form-card" action="index.php?action=admin_dashboard" method="POST">
                <div class="input-group full-width">
                    <label>Property Name</label>
                    <input type="text" placeholder="e.g. Modern Glass Villa" required>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>Location (Bacolod & Nearby)</label>
                        <select required>
                            <option value="Bacolod City">Bacolod City</option>
                            <option value="Silay City">Silay City</option>
                            <option value="Talisay City">Talisay City</option>
                            <option value="Don Salvador Benedicto">Don Salvador Benedicto</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Price (PHP)</label>
                        <input type="number" placeholder="₱ 0.00" required>
                    </div>

                    <div class="input-group full-width" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                        <div class="input-subgroup">
                            <label>Bedrooms</label>
                            <input type="number" min="0" placeholder="0" required>
                        </div>
                        <div class="input-subgroup">
                            <label>Bathrooms</label>
                            <input type="number" min="0" placeholder="0" required>
                        </div>
                        <div class="input-subgroup">
                            <label>Square Meters (sqm)</label>
                            <input type="number" min="0" placeholder="0 m²" required>
                        </div>
                    </div>
                </div>

                <div class="input-group full-width">
                    <label>Description</label>
                    <textarea rows="4" placeholder="Describe the features, amenities, and unique selling points..."></textarea>
                </div>

                <div class="input-group full-width">
                    <label>Featured Image</label>
                    <div class="image-upload-box" onclick="document.getElementById('file-input').click()">
                        <p style="color: #888;">Click to upload or drag and drop</p>
                        <span style="font-size: 0.75rem; color: #444;">PNG, JPG up to 10MB</span>
                        <input type="file" id="file-input" style="display: none;">
                    </div>
                </div>

                <button type="submit" class="btn-save">Publish Property</button>
            </form>
        </div>
    </main>

    
</body>
</html>

</body>
</html>