<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Wedding Wonders</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background-color: #fff5f8;
            color: #333;
            background-image: url("Images/homeback.jpg");
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
        /* Hero Section */
        .hero {
            text-align: center;
            padding: 100px 20px;
            background: url('Images/wedding.jpg') no-repeat center center/cover;
            color: white;
        }
        .hero h1 {
            font-size: 50px;
            font-weight: bold;
        }
        .hero p {
            font-size: 20px;
            margin-top: 10px;
        }
        .cta {
            background-color:hotpink;
            color:white;
            padding: 15px 30px;
            margin-top: 20px;
            font-size: 18px;
            border: none;
            cursor: pointer;
        }
        /* How It Works */
        .how-it-works {
            text-align: center;
            padding: 50px;
        }
        .steps {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }
        .step {
            width: 30%;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        /* About Us */
        .about {
            text-align: center;
            padding: 50px;
            background-color: white;
        }
        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            background-color: hotpink;
            color: white;
        }
        a{
            color: black;
            text-decoration: none;
        }
        #log
        {
            margin-left: 400px;
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

    <!-- Hero Section -->
    <div class="hero">
        <h1 style="color:black;">Your Dream Wedding, Made Easy</h1>
        <p style="color:aqua;">Plan your wedding effortlessly with the best vendors, places, and packages.</p>
        <button class="cta" onclick="window.location.href='package.php'">Start Planning</button>
    </div>

    <!-- How It Works -->
    <div class="how-it-works">
        <h2>How It Works</h2>
        <div class="steps">
            <div class="step">
                <h3>1. Choose a Package</h3>
                <p>Select from a variety of wedding plans that suit your needs.</p>
            </div>
            <div class="step">
                <h3>2. Book Vendors</h3>
                <p>Find and book the best vendors for your big day.</p>
            </div>
            <div class="step">
                <h3>3. Enjoy Your Wedding</h3>
                <p>Relax and enjoy your special day without any stress!</p>
            </div>
        </div>
    </div>

    <!-- About Us -->
    <div class="about">
        <h2>About Wedding Wonders</h2>
        <p>We help couples plan their perfect wedding by providing seamless vendor management, budgeting tools, and beautiful venues.</p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Contact Us: support@weddingwonders.com | Follow us on Social Media</p>
    </div>
</body>
</html>