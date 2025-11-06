<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

// Database configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "wonder wedding";

// Create connection
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the latest package for the user
$user_email = $_SESSION['email'];
$sql = "SELECT * FROM user_packages WHERE user_email = '$user_email' ORDER BY created_at DESC LIMIT 1";
$result = mysqli_query($con, $sql);
$package = mysqli_fetch_assoc($result);

if (!$package) {
    header('Location: Full_Wedding_EventForm.php');
    exit;
}

// Insert venue bookings into the bookings table
if ($package) {
    // Check if bookings already exist for this package
    $check_query = "SELECT id FROM bookings WHERE date = ? AND time = ? AND place_id = 
                   (SELECT id FROM places WHERE name = ? LIMIT 1)";
    $check_stmt = mysqli_prepare($con, $check_query);
    
    // Insert engagement booking
    if (!empty($package['engagement_place'])) {
        $engagement_venue = mysqli_real_escape_string($con, $package['engagement_place']);
        $engagement_date = mysqli_real_escape_string($con, $package['engagement_date']);
        $engagement_time = mysqli_real_escape_string($con, $package['engagement_time']);
        
        // Check if booking already exists
        mysqli_stmt_bind_param($check_stmt, "sss", $engagement_date, $engagement_time, $engagement_venue);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) == 0) {
            // Get venue ID
            $venue_query = "SELECT id FROM places WHERE name = '$engagement_venue' LIMIT 1";
            $venue_result = mysqli_query($con, $venue_query);
            if ($venue_result && mysqli_num_rows($venue_result) > 0) {
                $venue = mysqli_fetch_assoc($venue_result);
                $venue_id = $venue['id'];
                
                // Insert booking
                $insert_query = "INSERT INTO bookings (place_id, date, time) 
                                VALUES ($venue_id, '$engagement_date', '$engagement_time')";
                mysqli_query($con, $insert_query);
            }
        }
    }

    // Insert marriage booking
    if (!empty($package['marriage_place'])) {
        $marriage_venue = mysqli_real_escape_string($con, $package['marriage_place']);
        $marriage_date = mysqli_real_escape_string($con, $package['marriage_date']);
        $marriage_time = mysqli_real_escape_string($con, $package['marriage_time']);
        
        // Check if booking already exists
        mysqli_stmt_bind_param($check_stmt, "sss", $marriage_date, $marriage_time, $marriage_venue);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) == 0) {
            // Get venue ID
            $venue_query = "SELECT id FROM places WHERE name = '$marriage_venue' LIMIT 1";
            $venue_result = mysqli_query($con, $venue_query);
            if ($venue_result && mysqli_num_rows($venue_result) > 0) {
                $venue = mysqli_fetch_assoc($venue_result);
                $venue_id = $venue['id'];
                
                // Insert booking
                $insert_query = "INSERT INTO bookings (place_id, date, time) 
                                VALUES ($venue_id, '$marriage_date', '$marriage_time')";
                mysqli_query($con, $insert_query);
            }
        }
    }

    // Insert reception booking
    if (!empty($package['reception_place'])) {
        $reception_venue = mysqli_real_escape_string($con, $package['reception_place']);
        $reception_date = mysqli_real_escape_string($con, $package['reception_date']);
        $reception_time = mysqli_real_escape_string($con, $package['reception_time']);
        
        // Check if booking already exists
        mysqli_stmt_bind_param($check_stmt, "sss", $reception_date, $reception_time, $reception_venue);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) == 0) {
            // Get venue ID
            $venue_query = "SELECT id FROM places WHERE name = '$reception_venue' LIMIT 1";
            $venue_result = mysqli_query($con, $venue_query);
            if ($venue_result && mysqli_num_rows($venue_result) > 0) {
                $venue = mysqli_fetch_assoc($venue_result);
                $venue_id = $venue['id'];
                
                // Insert booking
                $insert_query = "INSERT INTO bookings (place_id, date, time) 
                                VALUES ($venue_id, '$reception_date', '$reception_time')";
                mysqli_query($con, $insert_query);
            }
        }
    }
    
    mysqli_stmt_close($check_stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Budget Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff0f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        /* Navigation Bar */
        #nav-bar {
            display: flex;
            background-color: hotpink;
            padding: 10px;
            margin-left: -25px;
            margin-top: -20px;
            margin-right: -20px;
        }
        #nav-bar ul {
            list-style: none;
            display: flex;
            padding: 0;
            margin: 0;
            width: 100%;
        }
        #nav-bar li {
            padding: 5px 15px;
            cursor: pointer;
        }
        .logo{
            width: 18px;
            height: 15px;
        }
        .logotxt
        {
            font-size: 18px;
            font-weight: 500;
            padding-left: 30px;
            padding-top: 10px;
        }
        #tt {
            font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
            padding-left: 50px;
            padding-top: 10px;
            font-size: 22px;
            margin-left: 430px;
        }
        a{
            color: black;
            text-decoration: none;
        }
        #log
        {
            margin-left: 400px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #ff69b4;
            text-align: center;
            margin-bottom: 30px;
        }
        .event-section {
            margin-bottom: 30px;
            border-bottom: 1px dashed #ffb6c1;
            padding-bottom: 20px;
        }
        h2 {
            color: #ff69b4;
            margin-bottom: 15px;
        }
        .budget-details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .budget-item {
            width: 48%;
            margin-bottom: 10px;
        }
        .total-section {
            background-color: #ffebf1;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .grand-total {
            font-size: 24px;
            font-weight: bold;
            color: #ff1493;
            text-align: center;
            margin-top: 20px;
        }
        .print-btn {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            padding: 12px;
            background-color: #ff69b4;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        .print-btn:hover {
            background-color: #ff1493;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div id="nav-bar">
        <ul>
            <li class="logotxt"><a href="home.php"><img src="Images/home.png" class="logo"> Home</a></li>
            <li id="tt">Wedding Wonders</li>
            <li class="logotxt" id="log"><a href="profile.php"><img src="Images/user.png" class="logo"> Profile</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Your Wedding Budget Summary</h1>
        
        <!-- Engagement Section -->
        <div class="event-section">
            <h2>Engagement Details</h2>
            <div class="budget-details">
                <div class="budget-item">
                    <strong>Date:</strong> <?php echo htmlspecialchars($package['engagement_date']); ?>
                </div>
                <div class="budget-item">
                    <strong>Time:</strong> <?php echo htmlspecialchars($package['engagement_time']); ?>
                </div>
                <div class="budget-item">
                    <strong>Venue:</strong> <?php echo htmlspecialchars($package['engagement_place']); ?>
                </div>
                <div class="budget-item">
                    <strong>Venue Cost:</strong> ₹<?php echo number_format($package['engagement_place_amount'], 2); ?>
                </div>
                <div class="budget-item">
                    <strong>Number of Guests:</strong> <?php echo number_format($package['engagement_guests']); ?>
                </div>
                <div class="budget-item">
                    <strong>Food Cost:</strong> ₹<?php echo number_format($package['engagement_food_cost'], 2); ?>
                </div>
            </div>
            <div style="text-align: right; margin-top: 10px;">
                <strong>Engagement Total:</strong> ₹<?php echo number_format($package['engagement_total'], 2); ?>
            </div>
        </div>
        
        <!-- Marriage Section -->
        <div class="event-section">
            <h2>Marriage Details</h2>
            <div class="budget-details">
                <div class="budget-item">
                    <strong>Date:</strong> <?php echo htmlspecialchars($package['marriage_date']); ?>
                </div>
                <div class="budget-item">
                    <strong>Time:</strong> <?php echo htmlspecialchars($package['marriage_time']); ?>
                </div>
                <div class="budget-item">
                    <strong>Venue:</strong> <?php echo htmlspecialchars($package['marriage_place']); ?>
                </div>
                <div class="budget-item">
                    <strong>Venue Cost:</strong> ₹<?php echo number_format($package['marriage_place_amount'], 2); ?>
                </div>
                <div class="budget-item">
                    <strong>Number of Guests:</strong> <?php echo number_format($package['marriage_guests']); ?>
                </div>
                <div class="budget-item">
                    <strong>Food Cost:</strong> ₹<?php echo number_format($package['marriage_food_cost'], 2); ?>
                </div>
            </div>
            <div style="text-align: right; margin-top: 10px;">
                <strong>Marriage Total:</strong> ₹<?php echo number_format($package['marriage_total'], 2); ?>
            </div>
        </div>
        
        <!-- Reception Section -->
        <div class="event-section">
            <h2>Reception Details</h2>
            <div class="budget-details">
                <div class="budget-item">
                    <strong>Date:</strong> <?php echo htmlspecialchars($package['reception_date']); ?>
                </div>
                <div class="budget-item">
                    <strong>Time:</strong> <?php echo htmlspecialchars($package['reception_time']); ?>
                </div>
                <div class="budget-item">
                    <strong>Venue:</strong> <?php echo htmlspecialchars($package['reception_place']); ?>
                </div>
                <div class="budget-item">
                    <strong>Venue Cost:</strong> ₹<?php echo number_format($package['reception_place_amount'], 2); ?>
                </div>
                <div class="budget-item">
                    <strong>Number of Guests:</strong> <?php echo number_format($package['reception_guests']); ?>
                </div>
                <div class="budget-item">
                    <strong>Food Cost:</strong> ₹<?php echo number_format($package['reception_food_cost'], 2); ?>
                </div>
            </div>
            <div style="text-align: right; margin-top: 10px;">
                <strong>Reception Total:</strong> ₹<?php echo number_format($package['reception_total'], 2); ?>
            </div>
        </div>
        <div style="margin-top: 20px;">
            <h4 style="color: #d63384;">Vendor Details</h4>
            <div class="budget-details">
                <div class="budget-item"><strong>Photographer:</strong> ₹<?php echo number_format($package['photographer'], 2); ?></div>
                <div class="budget-item"><strong>Decoration:</strong> ₹<?php echo number_format($package['decoration'], 2); ?></div>
                <div class="budget-item"><strong>Entertainment:</strong> ₹<?php echo number_format($package['entertainment'], 2); ?></div>
                <div class="budget-item"><strong>Makeup:</strong> ₹<?php echo number_format($package['makeup'], 2); ?></div>
            </div>
            <div style="text-align: right; margin-top: 10px;">
                <strong>Vendor Total:</strong> ₹<?php echo number_format($package['reception_total'], 2); ?>
            </div>
        </div>

        <!-- Grand Total Section -->
        <div class="total-section">
            <div class="grand-total">
                Grand Total: ₹<?php echo number_format($package['grand_total'], 2); ?>
            </div>
        </div>
        
        <a href="#" class="print-btn" onclick="window.print()">Print Budget</a>
    </div>
</body>
</html>