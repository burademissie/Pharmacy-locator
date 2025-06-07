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
                $_SESSION['pharmacy_id'] = $insert->insert_id;
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Courgette&family=Roboto:ital,wght@0,100..900;1,100..900&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
</head>
<div class="image-container2"></div>
<div class="form-container">
    <h1 class="Finder" >Med Finder</h1>
    <p class="intro-text">Register your pharmacy to help people find the medicines they need</p>
    <h2>Create Account</h2>
    
    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['registered'])): ?>
        <div class="alert success">Registration successful! Please sign in.</div>
    <?php endif; ?>
    
    <form id="signup-form" method="POST">
        <div class="input-group  ">
            <input class="form" type="text" id="name" name="name"  placeholder="  Pharmacy Name" required>
              <div class="input-icon">
                        <svg class="icon icon-small" viewBox="0 0 24 24">
                            <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
                            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                            <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/>
                            <path d="M2 7h20"/>
                            <path d="M22 7v3a2 2 0 0 1-2 2v0a2.18 2.18 0 0 1-2-2.18 2.18 2.18 0 0 0-2-2.18 2.18 2.18 0 0 0-2 2.18 2.18 2.18 0 0 1-2 2.18 2.18 2.18 0 0 1-2-2.18 2.18 2.18 0 0 0-2-2.18 2.18 2.18 0 0 0-2 2.18 2.18 2.18 0 0 1-2 2.18 2.18 2.18 0 0 1-2-2.18V7"/>
                        </svg>
                    </div>
            
        </div>

         <div class="input-group">
            <input class="" type="text" id="owner" name="owner" placeholder="Owner Full Name" required>
             <div class="input-icon">
                        <!-- User SVG -->
                        <svg class="icon icon-small" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
        </div>

        <div class="input-group">
            <input type="email" id="email" name="email" placeholder="Email" required>
             <div class="input-icon">
                        <!-- Mail SVG -->
                        <svg class="icon icon-small" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
        </div>

         <div class="input-group">
            <input type="tel" id="phonenum" name="phonenum" placeholder="Phone number" required>
             <div class="input-icon">
                        <!-- Phone SVG -->
                        <svg class="icon icon-small" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
        </div>

        <div class="input-group">
            <input type="password" id="password" name="password" placeholder="Password" required>
             <div class="input-icon">
                        <!-- Lock SVG -->
                        <svg class="icon icon-small" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <circle cx="12" cy="16" r="1"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
        </div>
        <div class="input-group">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
             <div class="input-icon">
                        <!-- Lock SVG -->
                        <svg class="icon icon-small" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <circle cx="12" cy="16" r="1"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
        </div>

        <div class="input-group">
            <input type="text" id="location" name="location" placeholder="Enter your city or address" required>
              <div class="input-icon">
                        <!-- Map Pin SVG -->
                        <svg class="icon icon-small" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
        </div>
        
        <button type="submit" class="signin-btn">Sign Up</button>
        <p class="signup-link">Already have an account? <a href="login.php">Sign In</a></p>
    </form>
</div>

<!-- <?php require_once '../../includes/footer.php'; ?> -->