<?php
require_once 'db.php'; 

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prepared statement to search
$stmt = $conn->prepare("SELECT medicine_name, description, price, quantity FROM medicine WHERE LOWER(medicine_name) LIKE ?");
$searchTerm = "%$search%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$medicines = [];

while ($row = $result->fetch_assoc()) {
    $medicines[] = $row;
}

header('Content-Type: application/json');
echo json_encode($medicines);
