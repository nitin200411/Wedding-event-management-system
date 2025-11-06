<?php
session_start();

// Get parameters from URL
$event_type = isset($_GET['event_type']) ? $_GET['event_type'] : '';
$selected_date = isset($_GET['date']) ? $_GET['date'] : '';
$selected_time = isset($_GET['time']) ? $_GET['time'] : '';

// Database connection (replace with your credentials)
$db = new mysqli('localhost', 'root', '', 'wonder wedding');

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Query to get available venues (not booked on the selected date)
$query = "SELECT * FROM places 
          WHERE id NOT IN (
              SELECT DISTINCT place_id FROM bookings 
              WHERE date = ?
          )";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $selected_date);
$stmt->execute();
$result = $stmt->get_result();
$available_venues = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$db->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Wedding Venues - <?php echo ucfirst($event_type); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff0f5;
            margin: 0;
            padding: 0;
        }
        /* Navigation Bar */
        #nav-bar {
            display: flex;
            background-color: hotpink;
            padding: 10px;
        }
        #nav-bar ul {
            list-style: none;
            display: flex;
            padding: 0;
            margin: 0;
            width: 100%;
        }
        #nav-bar li {
            padding: 10px 15px;
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
        .logo {
            height: 20px;
            vertical-align: middle;
            margin-right: 5px;
        }
        .date-input {
            text-align: center;
            margin: 30px 0;
        }
        .venue-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .venue-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: hotpink;
        }
        .venue-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 0 20px;
        }
        .venue-card {
            width: 280px;
            background-color: white;
            border: 2px solid #ffc0cb;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 192, 203, 0.5);
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .venue-card:hover {
            transform: scale(1.05);
        }
        .venue-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .venue-info {
            padding: 10px;
        }
        .venue-type {
            background-color: #ffb6c1;
            color: white;
            text-align: center;
            padding: 5px;
            font-weight: bold;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .selected-details {
            background-color: #ffe6ee;
            padding: 15px;
            margin: 20px auto;
            border-radius: 8px;
            max-width: 600px;
            text-align: center;
            border: 1px solid #ff69b4;
        }
        .no-venues {
            text-align: center;
            padding: 40px;
            color: #ff69b4;
            font-size: 18px;
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

    <div class="selected-details">
        <h2 style="color: hotpink; margin-top: 0;">Available Venues for <?php echo ucfirst($event_type); ?></h2>
        <p><strong>Selected Date:</strong> <?php echo date('F j, Y', strtotime($selected_date)); ?></p>
        <p><strong>Selected Time:</strong> <?php echo date('g:i A', strtotime($selected_time)); ?></p>
    </div>

    <div id="venues-container">
        <div class="venue-section">
            <?php if (!empty($available_venues)): ?>
                <div class="venue-title">Available Venues (<?php echo count($available_venues); ?>)</div>
                <div class="venue-grid">
                    <?php foreach ($available_venues as $venue): ?>
                        <div class="venue-card" onclick="selectVenue('<?php echo addslashes($venue['name']); ?>', <?php echo $venue['cost']; ?>)">
                            <div class="venue-type"><?php echo htmlspecialchars($venue['type']); ?></div>
                            <img src="<?php echo htmlspecialchars($venue['image_path']); ?>" alt="<?php echo htmlspecialchars($venue['name']); ?>">
                            <div class="venue-info">
                                <h3><?php echo htmlspecialchars($venue['name']); ?></h3>
                                <p>Capacity: <?php echo htmlspecialchars($venue['capacity']); ?> People</p>
                                <p>Address: <?php echo htmlspecialchars($venue['address']); ?></p>
                                <p>Cost: ₹<?php echo number_format($venue['cost']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-venues">
                    <h3>No venues available for the selected date and time.</h3>
                    <p>Please try a different date or time.</p>
                    <button onclick="window.history.back();" style="
                        background-color: #ff69b4;
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 16px;
                        margin-top: 15px;
                    ">Go Back</button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function selectVenue(venueName, venueCost) {
            const eventType = "<?php echo $event_type; ?>";
            const selectedDate = "<?php echo $selected_date; ?>";
            const selectedTime = "<?php echo $selected_time; ?>";
            
            window.location.href = `full_wedding_event_updated.php?venue=${encodeURIComponent(venueName)}&cost=${venueCost}&event_type=${eventType}&date=${selectedDate}&time=${selectedTime}`;
        }
    </script>

</body>
</html>