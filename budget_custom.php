<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wedding Budget Details</title>
  <style>
    body {
      background-color: #ffe6f0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 20px;
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
      max-width: 900px;
      margin: auto;
      background: #fff0f5;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(255, 105, 180, 0.3);
      text-align: center;
    }

    h2 {
      color: #d63384;
      border-bottom: 2px solid #ffc0cb;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .event-section {
      margin-bottom: 30px;
      background-color: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 5px 15px rgba(255, 105, 180, 0.2);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 10px;
      border-bottom: 1px solid #f4a4c4;
    }

    th {
      background-color: #ffd6e7;
    }

    .total {
      font-weight: bold;
      color: #c2185b;
      margin-top: 15px;
    }

    .grand-total {
      font-size: 1.4em;
      margin-top: 30px;
      color: #e91e63;
    }
    
    .success-message {
      color: #4CAF50;
      margin: 10px 0;
      padding: 10px;
      background-color: #e8f5e9;
      border-radius: 5px;
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
    <h2>Wedding Budget Details</h2>
    <?php
        session_start();

        if (!isset($_SESSION['email'])) {
          echo "<p>Please log in to view budget details.</p>";
          exit;
        }
        
        $user_email = $_SESSION['email'];
        
        $conn = new mysqli("localhost", "root", "", "wonder wedding");
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM custom_packages WHERE user_email='$user_email' ORDER BY created_at DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $data = $result->fetch_assoc();
          $grand_total = 0;
          $sections = ['engagement', 'reception', 'marriage'];

          foreach ($sections as $section) {
            $date = $data[$section . '_date'];
            if ($date) {
              $time = $data[$section . '_time'];
              $place = $data[$section . '_place'];
              $place_amt = $data[$section . '_place_amount'];
              $guests = $data[$section . '_guests'];

              echo "<div class='event-section'>";
              echo "<h3>" . ucfirst($section) . " Details</h3>";
              echo "<p><strong>Date:</strong> $date</p>";
              echo "<p><strong>Time:</strong> $time</p>";
              echo "<p><strong>Place:</strong> $place</p>";
              echo "<p><strong>Guests:</strong> $guests</p>";
              echo "<p><strong>Venue Cost:</strong> ₹$place_amt</p>";

              // Get place_id from places table
              $place_query = "SELECT id FROM places WHERE name = ?";
              $place_stmt = $conn->prepare($place_query);
              $place_stmt->bind_param("s", $place);
              $place_stmt->execute();
              $place_result = $place_stmt->get_result();
              
              if ($place_result->num_rows > 0) {
                $place_data = $place_result->fetch_assoc();
                $place_id = $place_data['id'];
                
                // Check if booking already exists
                $booking_check = "SELECT id FROM bookings WHERE place_id = ? AND date = ? AND time = ?";
                $check_stmt = $conn->prepare($booking_check);
                $check_stmt->bind_param("iss", $place_id, $date, $time);
                $check_stmt->execute();
                $booking_exists = $check_stmt->get_result()->num_rows > 0;
                
                if (!$booking_exists) {
                  // Insert into bookings table
                  $insert_booking = "INSERT INTO bookings (place_id, date, time) VALUES (?, ?, ?)";
                  $insert_stmt = $conn->prepare($insert_booking);
                  $insert_stmt->bind_param("iss", $place_id, $date, $time);
                  
                  if ($insert_stmt->execute()) {
                    echo "<div class='success-message'>Venue booking confirmed for " . ucfirst($section) . "!</div>";
                  } else {
                    echo "<div style='color:red'>Error booking venue: " . $conn->error . "</div>";
                  }
                } else {
                  echo "<div class='success-message'>Venue already booked for " . ucfirst($section) . "</div>";
                }
              } else {
                echo "<div style='color:red'>Venue details not found in database</div>";
              }

              // Get vendor details
              $vendor_query = "SELECT * FROM Vendor_details WHERE user_email='$user_email' AND event_name='$section'";
              $vendor_result = $conn->query($vendor_query);

              $section_total = $place_amt;

              if ($vendor_result->num_rows > 0) {
                echo "<table>
                        <tr>
                          <th>Vendor Type</th>
                          <th>Vendor Name</th>
                          <th>Vendor Cost</th>
                        </tr>";
                while ($vendor = $vendor_result->fetch_assoc()) {
                  $vendor_cost = $vendor['vendor_cost'];
                  if (strtolower($vendor['vendor_type']) === "catering") {
                    $vendor_cost *= $guests;
                  }
                  $section_total += $vendor_cost;
                  echo "<tr>
                          <td>{$vendor['vendor_type']}</td>
                          <td>{$vendor['vendor_name']}</td>
                          <td>₹$vendor_cost</td>
                        </tr>";
                }
                echo "</table>";
              }
              $grand_total += $section_total;
              echo "<div class='total'>Total " . ucfirst($section) . " Cost: ₹$section_total</div>";
              echo "</div>";
            }
          }

          echo "<div class='grand-total'>Grand Total: ₹$grand_total</div>";
        } else {
          echo "<p>No budget details found.</p>";
        }

        $conn->close();
    ?>
    <a href="#" class="print-btn" onclick="window.print()">Print Budget</a>
  </div>
</body>
</html>