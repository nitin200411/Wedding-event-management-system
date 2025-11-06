<?php
// Start session at the VERY TOP (no output before this)
session_start();

// Database configuration - use underscore instead of space
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

// Process package selection if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['email'])) {
        header('Location: login.php');
        exit;
    }

    $package_type = mysqli_real_escape_string($con, $_POST['package_type']);
    $user_email = $_SESSION['email'];
    
    
    // Insert into database
    $sql = "INSERT INTO package_details
            (Email, package_type,created_at) 
            VALUES ('$user_email', '$package_type',NOW())";
    
    if (mysqli_query($con, $sql)) {
        $_SESSION['package_type'] = $package_type;
        if ($package_type === 'full') {
            header('Location: full_wedding_event_updated.php');
            exit;
        } else {
            header('Location: custom_wed_updated.php');
            exit;
        }
    } else {
        $error = "Error saving package selection: " . mysqli_error($con);
    }
}

// Check login status for page view
$is_logged_in = isset($_SESSION['email']);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Packages - Wonder Wedding</title>
        <style>
            body {
                background-color:blanchedalmond;
                color: black;
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            }
            .logo {
                width: 18px;
                height: 15px;
            }
            .logotxt {
                font-size: 18px;
                font-weight: 500;
                padding-left: 30px;
                padding-top: 10px;
            }
            img {
                width: 300px;
                height: 300px;
                border-radius: 15px;
                object-fit: cover;
            }
            #tt {
                font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
                padding-left: 50px;
                padding-top: 10px;
                font-size: 22px;
            }
            #f1 {
                display: flex;
            }
            .mcont {
                font-size: large;
                line-height: 1.6;
            }
            .whatinc {
                margin-left: 60px;
                padding-top: 10px;
                line-height: 1.6;
            }
            #t1, #t2 {
                display: flex;
                background-color: hotpink;
                color: white;
                border-radius: 40px;
                margin: 40px;
                padding: 30px;
                border-color: deeppink;
                border-width: 4px;
                border-style: solid;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                cursor: pointer;
            }
            #t1:hover, #t2:hover {
                transform: scale(1.02);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                border-color: lime;
            }
            h1, h2, h3 {
                text-align: center;
            }
            h3 {
                color: brown;
                font-size: 22px;
            }
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
            .logotxt{
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
           a{
            color: black;
            text-decoration: none;
            }
            #log
            {
            margin-left: 400px;
            }
            .error-message {
                color: red;
                text-align: center;
                padding: 15px;
                background-color: rgba(255, 255, 255, 0.8);
                margin: 20px auto;
                width: 80%;
                border-radius: 10px;
                font-weight: bold;
            }
            form {
                display: none; /* Hidden form for package selection */
            }
        </style>
    </head>
    <body>
        <?php if (!$is_logged_in): ?>
            <script>
                // JavaScript redirect to avoid header already sent issues
                window.location.href = "login.php?redirect=package.php";
            </script>
        <?php else: ?>
            <div id="nav-bar">
            <ul>
                <li class="logotxt"><a href="home.php"><img src="Images/home.png" class="logo"> Home</a></li>
                <li id="tt">Wedding Wonders</li>
                <li class="logotxt" id="log"><a href="profile.php"><img src="Images/user.png" class="logo"> Profile</a></li>
            </ul>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <h1>Packages</h1>
            <h2>Choose The Plan That Suits You The Best</h2>
            
            <!-- Hidden form for package selection -->
            <form method="POST" action="">
                <input type="hidden" name="package_type" id="package_type" value="">
                <input type="submit" id="package_submit">
            </form>

            <!-- Full Wedding Plan -->
            <div id="t1" onclick="selectPackage('full')">
                <div id="R1c1">
                    <h2>Full Wedding Plan</h2>
                    <h3>Leave It All To Us</h3>
                    <p class="mcont">From vendors to venues, we take care of everything to make your dream wedding come true. Our full wedding plan ensures a seamless and stress-free experience, so you can focus on celebrating your special day.</p>
                    <h3>What's Included:</h3>
                    <ul>
                        <li class="whatinc"><b>Venue Selection:</b> Handpicked venues to match your vision.</li>
                        <li class="whatinc"><b>Wedding Decor:</b> Stunning decorations tailored to your theme.</li>
                        <li class="whatinc"><b>Photoshoots:</b> Professional photography and videography.</li>
                        <li class="whatinc"><b>Entertainment:</b> DJ, dance teams, and live performances.</li>
                        <li class="whatinc"><b>Catering:</b> Exquisite menus curated by top chefs.</li>
                        <li class="whatinc"><b>Coordination:</b> Dedicated wedding planner.</li>
                    </ul>
                </div>
                <div>
                    <img src="Images/fullWed.jpg" id="R1c2" alt="Full Wedding Package">
                </div>
            </div>

            <!-- Customized Plan -->
            <div id="t2" onclick="selectPackage('custom')">
                <div>
                    <img src="Images/custwed.jpg" id="R2c1" alt="Custom Wedding Package">
                </div>
                <div id="R2c2">
                    <h2>Customized Plan</h2>
                    <h3>Your Vision, Your Way</h3>
                    <p class="mcont">Want to personalize every detail of your wedding? Our customized wedding plan lets you handpick the services you need, ensuring your big day reflects your unique style and preferences.</p>
                    <h3>What's Included:</h3>
                    <ul>
                        <li class="whatinc"><b>Flexible Options:</b> Choose only what you need.</li>
                        <li class="whatinc"><b>Vendor Selection:</b> Handpick preferred vendors.</li>
                        <li class="whatinc"><b>Custom Decorations:</b> Tailored to your theme.</li>
                        <li class="whatinc"><b>Entertainment:</b> Build your own package.</li>
                        <li class="whatinc"><b>Photography:</b> Select your preferred style.</li>
                        <li class="whatinc"><b>Partial Planning:</b> Help with specific aspects.</li>
                    </ul>
                </div>
            </div>

            <script>
                function selectPackage(packageType) {
                    document.getElementById('package_type').value = packageType;
                    document.getElementById('package_submit').click();
                }
            </script>
        <?php endif; ?>
    </body>
</html>