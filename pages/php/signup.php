<?php
session_start();
include "db.php"; // Make sure db.php only connects to DB (no redirection)

$page_title = "Sign Up";
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $owner = trim($_POST['owner']);
    $email = trim($_POST['email']);
    $phonenum = trim($_POST['phonenum']);
    $location = trim($_POST['location']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($name) || empty($owner) || empty($email) || empty($phonenum) || empty($location) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 2) {
        $error = "Password must be at least 8 characters long";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM pharmacy WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into database
            $insert = $conn->prepare("INSERT INTO pharmacy (Pharmacy_name, owner_name, email, phone_number, location, pass) VALUES (?, ?, ?, ?, ?, ?)");
            $insert->bind_param("ssssss", $name, $owner, $email, $phonenum, $location, $hashed_password);

            if ($insert->execute()) {
                $_SESSION['pharmacy_id'] = $stmt->insert_id;
                $_SESSION['pharmacy_name'] = $name;
                header("Location: ../html/Addmed.html");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>

<head>
    <link rel="stylesheet" href="../css/signin.css">
</head>
<div class="image-container2"></div>
<div class="form-container">
    <h1>Med Finder</h1>
    <p class="intro-text">Register your pharmacy to help people find the medicines they need</p>
    <h2>Create Account</h2>
    
    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['registered'])): ?>
        <div class="alert success">Registration successful! Please sign in.</div>
    <?php endif; ?>
    
    <form id="signup-form" method="POST">
        <div class="input-group">
            <input type="text" id="name" name="name" placeholder="Pharmacy Name" required>
        </div>

         <div class="input-group">
            <input type="text" id="owner" name="owner" placeholder="Owner Full Name" required>
        </div>

        <div class="input-group">
            <input type="email" id="email" name="email" placeholder="Email" required>
        </div>

         <div class="input-group">
            <input type="tel" id="phonenum" name="phonenum" placeholder="Phone number" required>
        </div>

        <div class="input-group">
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <div class="input-group">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
        </div>

        <div class="input-group">
            <input type="text" id="location" name="location" placeholder="Enter your city or address" required>
        </div>
        
        <button type="submit" class="signin-btn">Sign Up</button>
        <p class="signup-link">Already have an account? <a href="login.php">Sign In</a></p>
    </form>
</div>

<!-- <?php require_once '../../includes/footer.php'; ?> -->