<?php
include "db.php";
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get total medicines
$sql_medicines = "SELECT COUNT(*) as total_medicines FROM medicine";
$result_medicines = $conn->query($sql_medicines);
$total_medicines = $result_medicines->fetch_assoc()['total_medicines'];

// Get total pharmacies
$sql_pharmacies = "SELECT COUNT(*) as total_pharmacies FROM pharmacy";
$result_pharmacies = $conn->query($sql_pharmacies);
$total_pharmacies = $result_pharmacies->fetch_assoc()['total_pharmacies'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Med Finder - Find Medicines Near You</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Unbounded:wght@200..900&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/landing.css" />
  </head>
  <body>
    <nav class="navbar">
      <div class="logo">Med<span>Finder</span></div>
      <div class="nav-links">
        <a onclick="About()"  href="#features">About</a>
        <a onclick="Contact()"  href="#contact">Contact</a>
      </div>
    </nav>

    <section class="hero">
      <div class="hero-content">
        <div class="hero-text">
          <h1 class="hero-title">
            Find Medicines Faster in<br />
            <span class="gradient-text">Your Neighborhood</span>
          </h1>
          <p class="hero-description">
            MedFinder connects you with nearby pharmacies that have your
            required medicines in stock. Save time and get what you need with
            real-time availability tracking across your city.
          </p>

          <div class="features">
            <div class="feature-tag"><i class="fas fa-bolt"></i> Fast</div>
            <div class="feature-tag">
              <i class="fas fa-map-marker-alt"></i> Accurate
            </div>
            <div class="feature-tag"><i class="fas fa-heart"></i> Reliable</div>
          </div>

          <div class="cta-buttons">
            <a href="../php/medicines.php" class="btn btn-primary"
              >Search Medicines</a
            >
            <a href="../php/signup.php" class="btn btn-secondary"
              >Register Pharmacy</a
            >
          </div>
        </div>
      </div>
    </section>

    <section class="reasons-section">
      <h2 class="section-title">Why Choose MedFinder</h2>
      <div class="reasons-grid">
        <div class="reason-card">
          <div class="reason-icon">
            <i class="fas fa-mobile-alt"></i>
          </div>
          <h3>User-Friendly Interface</h3>
          <p>
            MedFinder is designed for everyone. Our intuitive interface ensures
            you can find medicines quickly and easily.
          </p>
        </div>
        <div class="reason-card">
          <div class="reason-icon">
            <i class="fas fa-chart-line"></i>
          </div>
          <h3>Real-Time Availability</h3>
          <p>
            We provide real-time stock information so you never waste time
            visiting pharmacies that don't have what you need.
          </p>
        </div>
        <div class="reason-card">
          <div class="reason-icon">
            <i class="fas fa-map-marked-alt"></i>
          </div>
          <h3>Location-Based Results</h3>
          <p>
            Find medicines closest to you with our smart location-based search
            that prioritizes nearby pharmacies.
          </p>
        </div>
        <div class="reason-card">
          <div class="reason-icon">
            <i class="fas fa-shield-alt"></i>
          </div>
          <h3>Verified Pharmacies</h3>
          <p>
            All our partner pharmacies are verified and licensed, ensuring you
            get genuine medicines every time.
          </p>
        </div>
      </div>
    </section>

    <section class="steps-section">
      <h2 class="section-title">Find Your Medicine in 3 Simple Steps</h2>
      <div class="steps-container">
        <div class="step">
          <div class="step-number">1</div>
          <div class="step-icon"><i class="fas fa-search"></i></div>
          <h3>Search Your Medicine</h3>
          <p>Enter the name of your prescribed medication in our search bar</p>
        </div>
        <div class="step">
          <div class="step-number">2</div>
          <div class="step-icon"><i class="fas fa-map-marker-alt"></i></div>
          <h3>See Nearby Options</h3>
          <p>We'll show you pharmacies near you that have it in stock</p>
        </div>
        <div class="step">
          <div class="step-number">3</div>
          <div class="step-icon"><i class="fas fa-check-circle"></i></div>
          <h3>Reserve & Collect</h3>
          <p>Reserve your medication and pick it up at your convenience</p>
        </div>
      </div>
    </section>

    <!-- <script src="../js/landing.js"></script> -->

    <footer class="footer">
      <div class="footer-content">
        <div class="footer-section">
          <h3 class="footer-logo">Med<span>Finder</span></h3>
          <p>Connecting you with the medicines you need, when you need them.</p>
          <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
        <div class="footer-section"></div>
        <div class="footer-section">
          <h3>Contact</h3>
          <ul>
            <li>
              <i class="fas fa-map-marker-alt"></i> 4 kilo university
            </li>
            <li><i class="fas fa-phone"></i> +251 9060212134</li>
            <li><i class="fas fa-envelope"></i> info@medfinder.com</li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2023 MedFinder. All rights reserved.</p>
      </div>
    </footer>
  </body>
</html> 