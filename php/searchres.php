<?php
require_once 'db.php'; 

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modified query to include pharmacy details
$stmt = $conn->prepare("
    SELECT 
        m.id,
        m.medicine_name,
        m.description,
        m.price,
        m.quantity,
        m.form,
        m.expire_date,
        p.name as pharmacy_name,
        p.location,
        p.phone
    FROM medicine m
    JOIN pharmacy p ON m.pharmacy_id = p.id
    WHERE LOWER(m.medicine_name) LIKE ?
    ORDER BY m.medicine_name ASC
");

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

$stmt->close();
$conn->close();
