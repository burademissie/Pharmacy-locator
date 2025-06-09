<?php
require_once 'db.php';

// Get total medicines count
$medicineQuery = "SELECT COUNT(*) as total_medicines FROM medicine";
$medicineResult = $conn->query($medicineQuery);
$medicineCount = $medicineResult->fetch_assoc()['total_medicines'];

// Get total pharmacies count
$pharmacyQuery = "SELECT COUNT(*) as total_pharmacies FROM pharmacy";
$pharmacyResult = $conn->query($pharmacyQuery);
$pharmacyCount = $pharmacyResult->fetch_assoc()['total_pharmacies'];

// Prepare response
$stats = [
    'total_medicines' => $medicineCount,
    'total_pharmacies' => $pharmacyCount
];

// Send JSON response
header('Content-Type: application/json');
echo json_encode($stats);

$conn->close();
?> 