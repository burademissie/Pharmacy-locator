<?php
session_start();
require_once 'db.php';

// Check if pharmacy is logged in
if (!isset($_SESSION['pharmacy_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$pharmacy_id = $_SESSION['pharmacy_id'];

// Get all medicines for this pharmacy
$sql = "SELECT id, medicine_name, description, price, quantity, form, expire_date, brand_name 
        FROM medicine 
        WHERE pharmacy_id = ? 
        ORDER BY medicine_name ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pharmacy_id);
$stmt->execute();
$result = $stmt->get_result();

$medicines = [];
while ($row = $result->fetch_assoc()) {
    $medicines[] = $row;
}

header('Content-Type: application/json');
echo json_encode($medicines);

$stmt->close();
$conn->close();
?> 