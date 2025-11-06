<?php
session_start();

// Database configuration - use underscore instead of space for database name
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "wonder wedding";

// Create database connection
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$con) {
    die("<script>alert('Database connection failed')</script>");
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Sign'])) {
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $password = $_POST['password'];

    // Query database for user
    $sql = "SELECT * FROM user_info WHERE Email = '$email' LIMIT 1";
    $result = mysqli_query($con, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password - REMOVE THIS IN PRODUCTION (use password_verify with hashed passwords)
        if ($user['Password'] == $password) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['Email'];
            $_SESSION['name'] = $user['Name'];
            $_SESSION['logged_in'] = true;
            
            // Redirect to requested page or home
            if (isset($_GET['redirect'])) {
                header('Location: ' . urldecode($_GET['redirect']));
            } else {
                header('Location: home.php');
            }
            exit;
        } else {
            $login_error = "Invalid email or password"; // Generic message for security
        }
    } else {
        $login_error = "Invalid email or password"; // Generic message for security
    }
}


?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login Page - Wonder Wedding</title>
        <style>
            #ah {
                text-align: center;
                color: black;
                margin-top: 10px;
                margin-left: 50px;
                font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
                font-weight: 400;
                font-size: 70px;
                text-shadow: 3px;
            }
            #login_form {
                width: 400px;
                height: 210px;
                margin: 0 auto;
                margin-top: 30px;
                
                border-radius: 15px;
               
            }
            #email, #pass {
                width: 100%;
                margin-bottom: 10px;
                height: 40px;
                border-radius: 7px;
                padding-left: 14px;
                border: 4px solid blanchedalmond;
                font-size: 16px;
            }
            body {
                margin: 0;
                padding: 0;
                background: url("Images/holding.avif") no-repeat center center fixed;
                background-size: cover;
                font-family: Arial, sans-serif;
            }
            #h33 {
                color: maroon;
                text-align: center;
                font-size: 35px;
                font-weight: 800;
                margin-bottom: 20px;
            }
            #Sign {
                width: 100%;
                margin-top: 15px;
                height: 45px;
                font-size: 19px;
                border-radius: 5px;
                border: 4px solid greenyellow;
                background-color: hotpink;
                color: white;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            #Sign:hover {
                background-color: deeppink;
                transform: scale(1.02);
            }
            #bh {
                text-align: center;
                margin-top: 20px;
                font-size: 18px;
                color: black;
            }
            .error-message {
                color: red;
                text-align: center;
                margin-top: 10px;
                font-weight: bold;
                font-size: 16px;
                padding: 10px;
                background-color: rgba(255, 255, 255, 0.7);
                border-radius: 5px;
            }
            #al {
                color: deeppink;
                text-decoration: none;
                font-weight: bold;
            }
            #al:hover {
                color: fuchsia;
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <h1 id="ah">Wonder Wedding</h1>
        <div id="login_form">
            <h3 id="h33">Login</h3>
            <form method="POST">
                <input type="email" placeholder="Email Address" id="email" name="email" required>
                <input type="password" placeholder="Password" id="pass" name="password" required>
                <input type="submit" value="Sign in" id="Sign" name="Sign">
                
                <?php if (isset($login_error)): ?>
                    <div class="error-message"><?php echo $login_error; ?></div>
                <?php endif; ?>
                
                <div id="bh">New user? <a href="signup.php" id="al">Sign up</a></div>
            </form>
        </div>
        
        <script>
            // Simple client-side validation
            document.querySelector('form').addEventListener('submit', function(e) {
                const email = document.getElementById('email').value;
                const password = document.getElementById('pass').value;
                
                if (!email || !password) {
                    e.preventDefault();
                    alert('Please fill in all fields');
                }
            });
        </script>
    </body>
</html>