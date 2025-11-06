<?php
session_start();

// Database connection and processing logic at the top
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "wonder wedding";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get event details from POST data
    $event_name = $_POST['event_name'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $user_email = $_SESSION['email'] ?? ''; // Make sure this matches your session variable

    // Validate required fields
    if (empty($event_name) || empty($event_date) || empty($event_time) || empty($user_email)) {
        die("Required fields are missing. Please go back and ensure all event details are filled.");
    }

    // Vendor types to check for
    $vendor_types = ['photography', 'wedding', 'catering', 'entertainment', 'makeup'];
    $bookings_inserted = false;

    foreach ($vendor_types as $type) {
        if (isset($_POST["{$type}_name"])) {
            $vendor_name = $_POST["{$type}_name"];
            $vendor_cost = $_POST["{$type}_cost"];
            
            // Insert into database
            $sql = "INSERT INTO vendor_details (event_name, event_date, event_time, vendor_type, vendor_name, vendor_cost, user_email)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssds", 
                $event_name, 
                $event_date, 
                $event_time, 
                $type, 
                $vendor_name, 
                $vendor_cost, 
                $user_email
            );
            
            if ($stmt->execute()) {
                $bookings_inserted = true;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            $stmt->close();
        }
    }

    $conn->close();

    if ($bookings_inserted) {
        // Redirect back to the original form with success message
        header("Location: custom_wed_updated.php?success=vendors_booked");
        exit();
    }
}

// Get parameters from URL - with proper validation
$event = isset($_GET['event']) ? htmlspecialchars($_GET['event']) : '';
$event_date = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';
$event_time = isset($_GET['time']) ? htmlspecialchars($_GET['time']) : '';

// Verify we have all required parameters
if (empty($event) || empty($event_date) || empty($event_time)) {
    die("Event details are missing. Please go back and select an event first.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vendors</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
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
        img { width: 300px; height: 300px; }
        td { padding-left: 40px; padding-right: 40px; }
        #f1 { display: flex; }
        #d1 { height: 50px; width: 101%; background-color: hotpink; margin-top: -10px; margin-left: -9px; }
        .vendor-section { display: flex; color: white; border-radius: 30px; padding: 10px; margin-bottom: 30px; }
        .vendor-list { width: 500px; padding-left: 40px; }
        .vendor-photo { padding-right: 140px; }
        .vendorpic { width: 220px; height: 220px; }
        .checked { color: gold; }
        .bookven { width: 100px; margin-top: 10px; border-radius: 20px; background-color: deeppink; color: white; }
        .listword { font-size: 20px; }
    </style>
    <script>
        const vendors = {
           photography: {
                    1: { name: "ABC Studio", img: "Images/photoimg.jpg", desc: "Capturing your special day perfectly.", rating: 3, cost: 20000 },
                    2: { name: "Zion Photography", img: "Images/photo2.jpg", desc: "Modern cinematic memories.", rating: 4, cost: 25000 },
                    3: { name: "Professional Makers", img: "Images/photo3.jpg", desc: "Elegant photography style.", rating: 5, cost: 30000 },
                    4: { name: "Gravity Workers", img: "Images/photo4.jpg", desc: "Creative and artistic photos.", rating: 4, cost: 22000 }
                },
                wedding: {
                    1: { name: "ABC Decorators", img: "Images/wed_dec.jpeg", desc: "Elegant decorations tailored for you.", rating: 4, cost: 50000 },
                    2: { name: "Zion Decor", img: "Images/dec2.jpg", desc: "Floral themes and vibrant lighting.", rating: 5, cost: 55000 },
                    3: { name: "Pro Event Setup", img: "Images/dec3.jpg", desc: "Luxurious wedding setups.", rating: 5, cost: 60000 },
                    4: { name: "Gravity Events", img: "Images/dec4.jpg", desc: "Traditional with a modern touch.", rating: 4, cost: 48000 }
                },
                catering: {
                    1: { name: "ABC Caterers", img: "Images/wedding-catering-services.jpeg", desc: "Delicious multi-cuisine dishes.", rating: 5, cost: 200 },
                    2: { name: "Zion Caterers", img: "Images/cat2.jpg", desc: "Spicy and authentic flavors.", rating: 4, cost: 300 },
                    3: { name: "Pro Cuisine", img: "Images/cat3.jpg", desc: "Chef-special custom menus.", rating: 5, cost: 450 },
                    4: { name: "Gravity Kitchen", img: "Images/cat4.jpg", desc: "Fusion and modern platters.", rating: 4, cost: 390 }
                },
                entertainment: {
                    1: { name: "ABC Entertainment", img: "Images/enterdj.jpeg", desc: "Groovy DJ and performances.", rating: 4, cost: 30000 },
                    2: { name: "Zion DJs", img: "Images/ent2.jpg", desc: "Live music and lights.", rating: 5, cost: 35000 },
                    3: { name: "Pro Beats", img: "Images/ent3.jpg", desc: "Rocking dance floors.", rating: 4, cost: 33000 },
                    4: { name: "Gravity Vibes", img: "Images/ent4.jpg", desc: "Fusion music experience.", rating: 4, cost: 31000 }
                },
                makeup: {
                    1: { name: "ABC Makeup", img: "Images/makeup.jpg", desc: "Bridal makeup perfection.", rating: 5, cost: 15000 },
                    2: { name: "Zion Looks", img: "Images/mak2.jpg", desc: "Flawless and natural style.", rating: 5, cost: 18000 },
                    3: { name: "Pro Stylists", img: "Images/mak3.jpg", desc: "Bold and elegant.", rating: 4, cost: 17000 },
                    4: { name: "Gravity Glam", img: "Images/mak4.jpg", desc: "Red carpet-ready looks.", rating: 4, cost: 16000 }
                }
        };

        const bookings = {};
        
        // Get URL parameters
        const eventName = "<?php echo $event; ?>";
        const eventDate = "<?php echo $event_date; ?>";
        const eventTime = "<?php echo $event_time; ?>";

        function updateSection(type, id) {
            const vendor = vendors[type][id];
            document.getElementById(`${type}_img`).src = vendor.img;
            document.getElementById(`${type}_desc`).innerText = vendor.desc;
            document.getElementById(`${type}_rating`).innerHTML = "★".repeat(vendor.rating) + "☆".repeat(5 - vendor.rating);
            document.getElementById(`${type}_cost`).innerText = "Cost: ₹" + vendor.cost;
            document.getElementById(`${type}_bookbtn`).dataset.vendor = JSON.stringify(vendor);
        }

        function bookVendor(type) {
            const data = JSON.parse(document.getElementById(`${type}_bookbtn`).dataset.vendor);
            bookings[type] = data;
            alert(`Booked ${data.name} for ${type}`);
        }

        function submitAll() 
        {
            // Create form with all booking data
            const form = document.createElement("form");
            form.method = "POST";
            form.action = ""; // Submit to same page
            
            // Add event details - these must match the PHP expected values
            addHiddenField(form, "event_name", "<?php echo $event; ?>");
            addHiddenField(form, "event_date", "<?php echo $event_date; ?>");
            addHiddenField(form, "event_time", "<?php echo $event_time; ?>");
            
            // Helper function to add hidden fields
            function addHiddenField(form, name, value) {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = name;
                input.value = value;
                form.appendChild(input);
            }

            // Add vendor bookings
            Object.keys(bookings).forEach((key) => {
                const vendor = bookings[key];
                addHiddenField(form, `${key}_name`, vendor.name);
                addHiddenField(form, `${key}_cost`, vendor.cost);
            });

            document.body.appendChild(form);
            form.submit();
        }
    </script>
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

    <h2 style="color:gold;">Vendors for <?php echo htmlspecialchars($event); ?> on <?php echo htmlspecialchars($event_date); ?> at <?php echo htmlspecialchars($event_time); ?></h2>

    <!-- Photography Section -->
    <div class="vendor-section" style="background-color:rgb(255, 214, 255);" id="photography">
        <div class="vendor-list">
            <h2 style="color:black;">Photography</h2>
            <ul type="none">
                <li class="listword"><a href="#" onclick="updateSection('photography',1)">ABC Studio</a></li>
                <li class="listword"><a href="#" onclick="updateSection('photography',2)">Zion Photography</a></li>
                <li class="listword"><a href="#" onclick="updateSection('photography',3)">Professional Makers</a></li>
                <li class="listword"><a href="#" onclick="updateSection('photography',4)">Gravity Workers</a></li>
            </ul>
        </div>
        <div class="vendor-photo"><img id="photography_img" src="Images/photoimg.jpg" class="vendorpic"></div>
        <div>
            <h4 id="photography_desc" style="color:black;">Every detail of this wedding decoration is a reflection of love, joy, and togetherness</h4>
            <h3 id="photography_rating" style="color:rgb(250, 209, 1)">★★★☆☆</h3>
            <p id="photography_cost" style="color:deeppink;">Cost: ₹20000</p>
            <button class="bookven" onclick="bookVendor('photography')" id="photography_bookbtn">Book Vendor</button>
        </div>
    </div>

    <!-- Wedding Decoration Section -->
    <div class="vendor-section" style="background-color:rgb(231, 198, 255);" id="wedding">
        <div class="vendor-list">
            <h2>Wedding Decoration</h2>
            <ul type="none">
                <li class="listword"><a href="#" onclick="updateSection('wedding',1)">ABC Studio</a></li>
                <li class="listword"><a href="#" onclick="updateSection('wedding',2)">Zion Photography</a></li>
                <li class="listword"><a href="#" onclick="updateSection('wedding',3)">Professional Makers</a></li>
                <li class="listword"><a href="#" onclick="updateSection('wedding',4)">Gravity Workers</a></li>
            </ul>
        </div>
        <div class="vendor-photo"><img id="wedding_img" src="Images/wed_dec.jpeg" class="vendorpic"></div>
        <div>
            <h4 id="wedding_desc" style="color:black;">Every detail of this wedding decoration is a reflection of love, joy, and togetherness</h4>
            <h3 id="wedding_rating" style="color:rgb(250, 209, 1);">★★★☆☆</h3>
            <p id="wedding_cost" style="color:deeppink;">Cost: ₹50000</p>
            <button class="bookven" onclick="bookVendor('wedding')" id="wedding_bookbtn">Book Vendor</button>
        </div>
    </div>
    
    <!-- Catering Section -->
    <div class="vendor-section" style="background-color:rgb(184, 192, 255);" id="catering">
        <div class="vendor-list">
            <h2>Catering</h2>
            <ul type="none">
                <li class="listword"><a href="#" onclick="updateSection('catering',1)">ABC Caterers</a></li>
                <li class="listword"><a href="#" onclick="updateSection('catering',2)">Zion Caterers</a></li>
                <li class="listword"><a href="#" onclick="updateSection('catering',3)">Pro Cuisine</a></li>
                <li class="listword"><a href="#" onclick="updateSection('catering',4)">Gravity Kitchen</a></li>
            </ul>
        </div>
        <div class="vendor-photo"><img id="catering_img" src="Images/wedding-catering-services.jpeg" class="vendorpic"></div>
        <div>
            <h4 id="catering_desc" style="color:black;">Delicious multi-cuisine dishes.</h4>
            <h3 id="catering_rating" style="color:rgb(250, 209, 1);">★★★★★</h3>
            <p id="catering_cost" style="color:deeppink;">Cost: ₹40000</p>
            <button class="bookven" onclick="bookVendor('catering')" id="catering_bookbtn">Book Vendor</button>
        </div>
    </div>
    
    <!-- Entertainment Section -->
    <div class="vendor-section" style="background-color:rgb(200, 182, 255);" id="entertainment">
        <div class="vendor-list">
            <h2>Entertainment</h2>
            <ul type="none">
                <li class="listword"><a href="#" onclick="updateSection('entertainment',1)">ABC Entertainment</a></li>
                <li class="listword"><a href="#" onclick="updateSection('entertainment',2)">Zion DJs</a></li>
                <li class="listword"><a href="#" onclick="updateSection('entertainment',3)">Pro Beats</a></li>
                <li class="listword"><a href="#" onclick="updateSection('entertainment',4)">Gravity Vibes</a></li>
            </ul>
        </div>
        <div class="vendor-photo"><img id="entertainment_img" src="Images/enterdj.jpeg" class="vendorpic"></div>
        <div>
            <h4 id="entertainment_desc" style="color:black;">Groovy DJ and performances.</h4>
            <h3 id="entertainment_rating" style="color:rgb(250, 209, 1);">★★★★☆</h3>
            <p id="entertainment_cost" style="color:deeppink;">Cost: ₹30000</p>
            <button class="bookven" onclick="bookVendor('entertainment')" id="entertainment_bookbtn">Book Vendor</button>
        </div>
    </div>
    
    <!-- Makeup Section -->
    <div class="vendor-section" style="background-color:rgb(153, 50, 204);" id="makeup">
        <div class="vendor-list">
            <h2>Makeup</h2>
            <ul type="none">
                <li class="listword"><a href="#" onclick="updateSection('makeup',1)">ABC Makeup</a></li>
                <li class="listword"><a href="#" onclick="updateSection('makeup',2)">Zion Looks</a></li>
                <li class="listword"><a href="#" onclick="updateSection('makeup',3)">Pro Stylists</a></li>
                <li class="listword"><a href="#" onclick="updateSection('makeup',4)">Gravity Glam</a></li>
            </ul>
        </div>
        <div class="vendor-photo"><img id="makeup_img" src="Images/makeup.jpg" class="vendorpic"></div>
        <div>
            <h4 id="makeup_desc" style="color:black;">Bridal makeup perfection.</h4>
            <h3 id="makeup_rating" style="color:rgb(250, 209, 1);">★★★★★</h3>
            <p id="makeup_cost" style="color:deeppink;">Cost: ₹15000</p>
            <button class="bookven" onclick="bookVendor('makeup')" id="makeup_bookbtn">Book Vendor</button>
        </div>
    </div>

    <!-- Submit Button -->
    <div style="text-align: center; margin-top: 40px;">
        <button onclick="submitAll()" style="padding: 10px 20px; font-size: 18px; background-color:green; color:white; border:none; border-radius:10px;">Submit All Booked Vendors</button>
    </div>
</body>
</html>