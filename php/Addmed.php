<?php
session_start();
include "db.php";

// Check if the pharmacy is logged in
if (!isset($_SESSION['pharmacy_id'])) {
    header("Location: signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pharmacy_id = $_SESSION['pharmacy_id'];
 // ID of the currently logged-in pharmacy

    // Collect and sanitize inputs
    $name = trim($_POST['name']);
    $brand = trim($_POST['brand']);
    $description = trim($_POST['description']);
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $category = trim($_POST['category']);
    $dosage = trim($_POST['dosage']);
    $edate = $_POST['edate']; // Make sure it's a valid date format

    // Basic validation (you can expand this)
    if (empty($name) || empty($brand) || empty($description) || empty($quantity) || empty($price) || empty($edate)) {
        echo "All required fields must be filled.";
        exit();
    }

    // Insert into the medicines table
    $stmt = $conn->prepare("INSERT INTO medicine (pharmacy_id, medicine_name, brand_name, description, quantity, price, category, form, expire_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssddsss", $pharmacy_id, $name, $brand, $description, $quantity, $price, $category, $dosage, $edate);

    if ($stmt->execute()) {
        header("Location: Dashboard.php?status=added");
        exit();
    } else {
        echo "Failed to add medicine. Please try again.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
