<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook | Reserve <?php echo htmlspecialchars($property['Property_Name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        /* Global Reset & Fix */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --primary: #c9a07a; --dark: #0a0a0a; --card: #161616; }

        body { 
            background: var(--dark); 
            color: white; 
            font-family: 'Inter', sans-serif; 
            padding: 50px; 
            line-height: 1.6;
        }

        .header-nav { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            max-width: 1100px; 
            margin: 0 auto 30px auto; 
        }
        
        .user-profile-container { position: relative; }
        .profile-btn { background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; }
        .profile-icon { width: 40px; height: 40px; border-radius: 50%; border: 2px solid transparent; transition: 0.3s; object-fit: cover; }
        .profile-btn:hover .profile-icon { border-color: var(--primary); }
        
        .dropdown-menu { 
            display: none; 
            position: absolute; 
            right: 0; 
            top: 50px; 
            background: #161616; 
            border: 1px solid #222; 
            border-radius: 12px; 
            min-width: 160px; 
            z-index: 1000; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
            overflow: hidden; 
        }
        
        .dropdown-menu a { color: #ccc; padding: 12px 20px; text-decoration: none; display: block; font-size: 0.85rem; transition: 0.2s; }
        .dropdown-menu a:hover { background: #1f1f1f; color: var(--primary); }
        .show { display: block; }

        .booking-container { 
            max-width: 1100px; 
            margin: 0 auto; 
            display: grid; 
            grid-template-columns: 1.6fr 1fr; 
            gap: 50px; 
        }
        
        .property-preview img { width: 100%; border-radius: 24px; border: 1px solid #333; object-fit: cover; height: 450px; }
        .property-info h1 { font-size: 2.8rem; margin: 25px 0 10px 0; letter-spacing: -1px; }
        .property-info p { color: #888; margin-bottom: 20px; }
        
        .booking-card { 
            background: var(--card); 
            padding: 35px; 
            border-radius: 24px; 
            border: 1px solid #222; 
            height: fit-content; 
            position: static; 
            top: 40px; 
        }

        .price-tag { font-size: 1.6rem; font-weight: 800; color: var(--primary); margin-bottom: 5px; display: block; }
        .total-box { background: #000; padding: 15px; border-radius: 12px; margin: 20px 0; border: 1px dashed #333; }
        .total-row { display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 5px; color: #888; }
        .total-row.grand-total { color: var(--primary); font-weight: 800; font-size: 1.1rem; margin-top: 10px; border-top: 1px solid #222; pt-10; }
        
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 0.7rem; color: var(--primary); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; }
        
        input, select { 
            width: 100%; 
            padding: 15px; 
            background: #111; 
            border: 1px solid #333; 
            border-radius: 12px; 
            color: white; 
            outline: none; 
            transition: 0.3s;
            font-size: 0.95rem;
        }
        input:focus { border-color: var(--primary); background: #1a1a1a; }
        input[readonly] { color: #666; cursor: not-allowed; border-color: #222; }

        .btn-confirm { 
            width: 100%; 
            padding: 20px; 
            background: var(--primary); 
            border: none; 
            border-radius: 14px; 
            font-weight: 800; 
            cursor: pointer; 
            transition: 0.3s; 
            margin-top: 10px; 
            color: #000;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-confirm:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(201, 160, 122, 0.3); }
        
        .back-link { color: #888; text-decoration: none; font-size: 0.9rem; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; }
        .back-link:hover { color: white; }
    </style>
</head>
<body>

    <header class="header-nav">
        <a href="index.php?action=dashboard" class="back-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Explore
        </a>
        
        <div class="user-profile-container">
            <button class="profile-btn" onclick="toggleDropdown()">
                <img src="assets/img/user.png" alt="Profile" class="profile-icon">
            </button>
            <div id="profileDropdown" class="dropdown-menu">
                <a href="index.php?action=profile">My Profile</a>
                <a href="index.php?action=my_bookings">My Bookings</a>
                <hr style="border:0; border-top: 1px solid #222; margin: 5px 0;">
                <a href="index.php?action=logout" style="color: #ff4444;">Logout</a>
            </div>
        </div>
    </header>

    <div class="booking-container">
        <div class="property-preview">
            <img src="assets/img/<?php echo htmlspecialchars($property['image_path']); ?>" alt="Villa Image">
            
            <div class="property-info">
                <h1><?php echo htmlspecialchars($property['Property_Name']); ?></h1>
                <p>📍 <?php echo htmlspecialchars($property['Property_location']); ?></p>
                
                <div style="display:flex; gap: 30px; margin-top: 15px; color: #888; font-size: 0.95rem;">
                    <span><strong style="color:white;">Capacity:</strong> <?php echo $property['Property_capacity']; ?> Guests</span> 
                    <span><strong style="color:white;">Pool:</strong> <?php echo $property['Has_pool']; ?></span> 
                    <span><strong style="color:white;">Size:</strong> <?php echo $property['Property_size'] ?? '150'; ?> m²</span>
                    <span><strong style="color:white;">Type:</strong> Luxury Villa</span>
                </div>
                <hr style="border: 0; border-top: 1px solid #222; margin: 30px 0;">
                <h3 style="margin-bottom:10px;">The Space</h3>
                <p style="color: #888; font-size: 1rem; line-height: 1.8;">
                    <?php echo htmlspecialchars($property['Property_Description'] ?? 'This elite property offers a seamless blend of modern architecture and tropical comfort. Located in the heart of Bacolod, it provides the ultimate sanctuary for those seeking privacy and prestige.'); ?>
                </p>
            </div>
        </div>

        <div class="booking-card">
            <span class="price-tag">₱<?php echo number_format($property['Property_rate']); ?> <small style="font-size: 0.8rem; color: #555; font-weight: 400;">per night</small></span>
            
            <form action="index.php?action=confirm_booking" method="POST" id="bookingForm">
                <input type="hidden" name="property_id" value="<?php echo $property['Property_Id']; ?>">
                <input type="hidden" name="rate" id="propertyRate" value="<?php echo $property['Property_rate']; ?>">
                
                <div class="form-group">
                    <label>Guest Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" readonly>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Check-in</label>
                        <input type="date" name="check_in" id="checkIn" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Check-out</label>
                        <input type="date" name="check_out" id="checkOut" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" required>
                        <option value="GCash">GCash</option>
                        <option value="Maya">Maya</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>

                <div class="total-box" id="summaryBox" style="display:none;">
                    <div class="total-row">
                        <span>Nights</span>
                        <span id="numNights">0</span>
                    </div>
                    <div class="total-row.grand-total">
                        <span>Total Price</span>
                        <span id="totalPrice">₱0</span>
                    </div>
                </div>

                <button type="submit" class="btn-confirm">Confirm Reservation</button>
            </form>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("show");
        }

        // Logic to calculate total price dynamically
        const checkIn = document.getElementById('checkIn');
        const checkOut = document.getElementById('checkOut');
        const rate = parseFloat(document.getElementById('propertyRate').value);
        const summaryBox = document.getElementById('summaryBox');
        const numNightsText = document.getElementById('numNights');
        const totalPriceText = document.getElementById('totalPrice');

        function calculateTotal() {
            if (checkIn.value && checkOut.value) {
                const start = new Date(checkIn.value);
                const end = new Date(checkOut.value);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays > 0) {
                    summaryBox.style.display = 'block';
                    numNightsText.innerText = diffDays;
                    totalPriceText.innerText = '₱' + (diffDays * rate).toLocaleString();
                } else {
                    summaryBox.style.display = 'none';
                }
            }
        }

        checkIn.addEventListener('change', calculateTotal);
        checkOut.addEventListener('change', calculateTotal);

        window.onclick = function(event) {
            if (!event.target.matches('.profile-btn') && !event.target.matches('.profile-icon')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>