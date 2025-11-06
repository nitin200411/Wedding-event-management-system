<?php

// Database configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "wonder wedding";

// Initialize variables
$errors = [];
$name = $phone = $email = $address = '';

// Create database connection
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = mysqli_real_escape_string($con, trim($_POST['name']));
    $phone = mysqli_real_escape_string($con, trim($_POST['phone']));
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $address = mysqli_real_escape_string($con, trim($_POST['address']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation (same as before)
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }

    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required';
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors['phone'] = 'Invalid phone number format';
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = $password;
        
        // Prepare SQL statement
        $sql = "INSERT INTO  user_info
                (Name, Phone, Email, Address, Password) 
                VALUES ('$name', '$phone', '$email', '$address', '$hashed_password')";
        
        // Execute query
        if (mysqli_query($con, $sql)) {
            $_SESSION['success'] = 'Registration successful!';
            header('Location: login.php');  // Changed to redirect to home.php
            exit;
        } else {
            echo "Registration failed";  // Simple error message display
            // You can also use: die("Registration failed");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Wonder Weddings</title>
    <style>
        /* Your existing CSS remains exactly the same */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-image: url("Images/invi5.jpg");
            background-size: cover;
            background-position: center;
        }
        .container {
            max-width: 600px;
            margin: auto;
            margin-top: 30px;
            border: 4px solid #000000;
            padding: 20px;
            padding-right: 50px;
            color: white;
            background-color: rgba(0,0,0,0.7);
        }
        .header {
            font-size: 20px;
            font-weight: bold;
            color: aqua;
            margin-bottom: 20px;
        }
        .form-group {
            margin: 15px 0;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: rgba(255,255,255,0.9);
        }
        .error {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: 5px;
        }
        .buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .button {
            background-color: #008000;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        .button:hover {
            background-color: #006600;
        }
        .cancel {
            color: #ff6b6b;
            cursor: pointer;
            font-weight: bold;
        }
        .cancel:hover {
            text-decoration: underline;
        }
        h1 {
            color: white;
            text-shadow: 2px 2px 4px #000000;
        }
    </style>
</head>
<body>
    <h1 align="center">Wonder Wedding</h1>
    <div class="container">
        <div class="header">Create Your Account</div>
        
        <?php if (!empty($errors)): ?>
            <div style="color: red; margin-bottom: 15px;">
                Please fix the following errors:
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="name">Full Name*</label>
                <input type="text" placeholder="Name" name="name" id="name" 
                       value="<?php echo htmlspecialchars($name); ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <span class="error"><?php echo $errors['name']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number*</label>
                <input type="tel" placeholder="Phone Number" name="phone" id="phone" 
                       value="<?php echo htmlspecialchars($phone); ?>" required>
                <?php if (isset($errors['phone'])): ?>
                    <span class="error"><?php echo $errors['phone']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" placeholder="Email" name="email" id="email" 
                       value="<?php echo htmlspecialchars($email); ?>">
                <?php if (isset($errors['email'])): ?>
                    <span class="error"><?php echo $errors['email']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" placeholder="Address" name="address" id="address" 
                       value="<?php echo htmlspecialchars($address); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password*</label>
                <input type="password" placeholder="Password (min 8 characters)" name="password" id="password" required>
                <?php if (isset($errors['password'])): ?>
                    <span class="error"><?php echo $errors['password']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password*</label>
                <input type="password" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required>
                <?php if (isset($errors['confirm_password'])): ?>
                    <span class="error"><?php echo $errors['confirm_password']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="buttons">
                <button type="submit" class="button">Register</button>
                <a href="login.php" class="cancel">Already have an account? Login</a>
            </div>
        </form>
    </div>
</body>
</html>
