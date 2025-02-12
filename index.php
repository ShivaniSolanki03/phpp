<?php
error_reporting(E_ALL); // Show errors for debugging (optional)
ini_set('display_errors', 1);

$server = "localhost"; 
$username = "root";
$password = "";
$database = "trip"; 

// Create connection
$con = new mysqli($server, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$insert = false; // Initialize insert status

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['name']) && !empty($_POST['gender']) && !empty($_POST['age']) && !empty($_POST['email']) && !empty($_POST['phone'])) {
        
        // Use prepared statement for security
        $stmt = $con->prepare("INSERT INTO trip (Name, age, gender, email, phone, other, dt) 
                                VALUES (?, ?, ?, ?, ?, ?, current_timestamp())");
        $stmt->bind_param("sissss", $name, $age, $gender, $email, $phone, $other);

        // Sanitize inputs
        $name = htmlspecialchars($_POST['name']);
        $age = (int) $_POST['age'];
        $gender = htmlspecialchars($_POST['gender']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars($_POST['phone']);
        $other = htmlspecialchars($_POST['other']);

        // Execute query
        if ($stmt->execute()) {
            $insert = true;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "<p class='error'>All fields are required!</p>";
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Form</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function validateForm() {
            let name = document.getElementById("name").value.trim();
            let age = document.getElementById("age").value.trim();
            let gender = document.getElementById("gender").value.trim();
            let email = document.getElementById("email").value.trim();
            let phone = document.getElementById("phone").value.trim();

            if (name === "" || age === "" || gender === "" || email === "" || phone === "") {
                alert("All fields are required!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <img class="bg" src="bg.jpg" alt="Background">
    <div class="container">
        <h3>Welcome To Central University Of Rajasthan US Trip Form</h3>
        <p>Enter your details to confirm participation in the US trip</p>

        <!-- Show message only after successful submission -->
        <?php if ($insert): ?>
            <p class="message">Thanks for submitting your form! We are happy to see you joining the US trip.</p>
        <?php endif; ?>

        <form action="index.php" method="post" onsubmit="return validateForm()">
            <input type="text" name="name" id="name" placeholder="Enter your name" required>
            <input type="number" name="age" id="age" placeholder="Enter your age" required>
            <input type="text" name="gender" id="gender" placeholder="Enter your gender" required>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>
            <input type="text" name="phone" id="phone" placeholder="Enter your phone" required>
            <textarea name="other" id="other" cols="30" rows="10" placeholder="Enter any other info"></textarea>
            <button type="submit" class="btn">Submit</button>
        </form>
    </div>
</body>
</html>
