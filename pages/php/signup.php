<?php

// include "db.php";

session_start();



$page_title = "Sign Up";
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Email already registered";
        } else {
            // if (registerUser($name, $email, $password)) {
            //     header("Location: signin.php?registered=1");
            //     exit();
            // } else {
                $error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }


// require_once 'includes/header.php';
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
        <p class="signup-link">Already have an account? <a href="../html/signin.html">Sign In</a></p>
    </form>
</div>

<!-- <?php require_once '../../includes/footer.php'; ?> -->