<?php
include "db.php";
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare("SELECT id, pharmacy_name, pass FROM pharmacy WHERE email = 'kal@gmail.'");
    // $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows == 1) {
        
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['pharmacy_id'] = 3;
            $_SESSION['pharmacy_name'] = "boss";
            header("Location: Addmed.php"); // Redirect after login
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        // var_dump($stmt);
        $error = "No account found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Med Finder - Sign In</title>
  <link rel="stylesheet" href="../css/signin.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@200..900&display=swap" rel="stylesheet"/>
</head>
<body>
  <div class="container">
    <div class="form-container">
      <h1>Med Finder</h1>
      <h2>Welcome Back!</h2>

      <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
      <?php endif; ?>

      <form id="signin-form" action="" method="POST" onsubmit="return validateForm();">
        <div class="input-group">
          <input type="email" id="email" name="email" placeholder="Email" required />
        </div>
        <div class="input-group">
          <input type="password" id="password" name="password" placeholder="Password" required />
        </div>
        <button type="submit" class="signin-btn">Sign In</button>
        <p class="signup-link">Don't have an account? <a href="signup.php">Sign Up</a></p>
      </form>
    </div>
    <div class="image-container">
      <!-- styled in CSS -->
    </div>
  </div>

  <script>
    function validateForm() {
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();

      if (!email || !password) {
        alert("Please fill out all fields.");
        return false;
      }

      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
      }

      if (password.length < 2) {
        alert("Password must be at least 2 characters long.");
        return false;
      }

      return true;
    }
  </script>
</body>
</html>
