<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #0a0a0a; --card: #161616; }
        body { background: var(--dark); color: white; font-family: 'Inter', sans-serif; padding: 50px; }
        .container { max-width: 800px; margin: 0 auto; }
        .back-btn { color: #888; text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 30px; }
        .back-btn:hover { color: var(--primary); }
        h1 { font-size: 2.5rem; margin-bottom: 40px; font-weight: 800; }
        .booking-item { 
            background: var(--card); 
            padding: 25px; 
            border-radius: 20px; 
            border: 1px solid #222; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 20px;
            transition: 0.3s;
        }
        .booking-item:hover { border-color: var(--primary); transform: translateX(10px); }
        .status-badge { 
            padding: 6px 14px; 
            border-radius: 50px; 
            font-size: 0.75rem; 
            font-weight: 700; 
            text-transform: uppercase; 
            background: rgba(201, 160, 122, 0.1); 
            color: var(--primary); 
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php?action=dashboard" class="back-btn">← Back to Dashboard</a>
        <h1>My Bookings</h1>

        <?php if (!empty($bookings)): ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-item">
                    <div>
                        <h3 style="margin: 0 0 8px 0;"><?php echo htmlspecialchars($booking['Property_Name']); ?></h3>
                        
                        <p style="color: #888; font-size: 0.9rem; margin: 0;">
                            <?php echo htmlspecialchars($booking['Property_location']); ?> • 
                            <?php echo date('M d, Y', strtotime($booking['Booking_Date'])); ?>
                        </p>
                    </div>
                    <div style="text-align: right;">
                        <span class="status-badge"><?php echo htmlspecialchars($booking['Reservation_Status']); ?></span>
                        
                        <p style="margin: 12px 0 0 0; font-weight: 800; font-size: 1.1rem;">
                            ₱<?php echo number_format($booking['Amount']); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 50px; background: var(--card); border-radius: 20px; border: 1px dashed #333;">
                <p style="color: #666;">No reservations found. Start exploring Bacolod estates!</p>
                <a href="index.php?action=dashboard" style="color: var(--primary); text-decoration: none; display: inline-block; margin-top: 15px; font-weight: 700;">Explore Now →</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>