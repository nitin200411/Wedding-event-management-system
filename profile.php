<?php 
session_start();
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "wonder wedding"; // Changed to underscore for consistency (assuming your DB name has underscore)

// Create database connection
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$con) {
    die("<script>alert('Database connection failed')</script>");
}

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$em = $_SESSION['email'];
$sql = "SELECT * FROM user_info WHERE Email='$em' LIMIT 1";
$res = mysqli_query($con, $sql);

// Initialize variables to avoid undefined variable warnings
$name = $phone = $email = $address = '';

if($res && mysqli_num_rows($res) > 0) {
    $user = mysqli_fetch_assoc($res);
    $name = $user['Name'];
    $phone = $user['Phone'];
    $email = $user['Email'];
    $address = $user['Address'];
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $n = mysqli_real_escape_string($con, $_POST['name']);
    $p = mysqli_real_escape_string($con, $_POST['Phone']); // Changed to match input name
    $e = mysqli_real_escape_string($con, $_POST['Email']); // Changed to match input name
    $a = mysqli_real_escape_string($con, $_POST['add']);
    $EM = $_SESSION['email'];
    
    $sql1 = "UPDATE user_info SET Name='$n', Phone='$p', Email='$e', Address='$a' WHERE Email='$EM'";
    $res1 = mysqli_query($con, $sql1);
    
    if($res1) {
        // Update session email if email was changed
        if($e != $EM) {
            $_SESSION['email'] = $e;
        }
        
        // Refresh user data after update
        $sql = "SELECT * FROM user_info WHERE Email='$e' LIMIT 1";
        $res = mysqli_query($con, $sql);
        if($res && mysqli_num_rows($res) > 0) {
            $user = mysqli_fetch_assoc($res);
            $name = $user['Name'];
            $phone = $user['Phone'];
            $email = $user['Email'];
            $address = $user['Address'];
        }
        
        echo "<script>alert('Profile updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating profile: ".mysqli_error($con)."');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Profile Page</title>
        <style>
            #nav-bar {
                display: flex;
                background-color: hotpink;
                padding: 10px;
                margin: -10px;
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
            #tt {
                font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
                padding-left: 50px;
                padding-top: 10px;
                font-size: 22px;
                margin-left: 430px;
            }
            a {
                color: black;
                text-decoration: none;
            }
            #log {
                margin-left: 400px;
            }
            .typewriter {
                font-family: 'Times New Roman', Times, serif;
                overflow: hidden;
                white-space: nowrap;
                margin: 0 auto;
                margin-top: 20px;
                letter-spacing: .10em;
                animation: typing 6.5s steps(50, end);
            }
            @keyframes typing {
                from { width: 0 }
                to { width: 100% }
            }
            #pro {
                margin-top: 30px;
                margin-left: 400px;
                width: 400px;
                height: auto;
                border: solid hotpink 5px;
                padding: 20px;
            }
            .inp {
                margin: 20px;
                margin-left: 30px;
                padding: 6px;
                width: 300px;
            }
            label {
                margin: 20px;
                margin-left: 30px;
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
        <h1 align="center" class="typewriter">Hii <?php echo htmlspecialchars($name); ?></h1>
        <!-- profile box -->
        <div id="pro">
            <form method="POST">
                <label for="name">Name</label><br>
                <input type="text" name="name" class="inp" value="<?php echo htmlspecialchars($name); ?>"><br>
                <label for="Phone">Phone Number</label><br>
                <input type="text" name="Phone" class="inp" value="<?php echo htmlspecialchars($phone); ?>"><br>
                <label for="Email">Email Address</label><br>
                <input type="email" name="Email" class="inp" value="<?php echo htmlspecialchars($email); ?>"><br>
                <label for="add">Address</label><br>
                <input type="text" name="add" class="inp" value="<?php echo htmlspecialchars($address); ?>"><br>
                <input type="submit" class="inp" value="Update Profile" style="background-color:#00FF00;">
            </form>
        </div>
    </body>
</html>