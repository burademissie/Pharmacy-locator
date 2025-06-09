<?php
session_start();
require_once 'db.php';

// Check if pharmacy is logged in
if (!isset($_SESSION['pharmacy_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['medicine_id']) || !isset($data['quantity'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit();
    }

    $medicine_id = $data['medicine_id'];
    $pharmacy_id = $_SESSION['pharmacy_id'];
    $quantity = (int)$data['quantity'];
    $price = isset($data['price']) ? (float)$data['price'] : null;
    $expire_date = isset($data['expire_date']) ? $data['expire_date'] : null;

    // Verify the medicine belongs to this pharmacy
    $check_stmt = $conn->prepare("SELECT id FROM medicine WHERE id = ? AND pharmacy_id = ?");
    $check_stmt->bind_param("ii", $medicine_id, $pharmacy_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(403);
        echo json_encode(['error' => 'Medicine not found or unauthorized']);
        exit();
    }

    // Build update query based on provided fields
    $updates = ["quantity = ?"];
    $types = "i";
    $params = [$quantity];

    if ($price !== null) {
        $updates[] = "price = ?";
        $types .= "d";
        $params[] = $price;
    }

    if ($expire_date !== null) {
        $updates[] = "expire_date = ?";
        $types .= "s";
        $params[] = $expire_date;
    }

    $update_query = "UPDATE medicine SET " . implode(", ", $updates) . " WHERE id = ? AND pharmacy_id = ?";
    $types .= "ii";
    $params[] = $medicine_id;
    $params[] = $pharmacy_id;

    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param($types, ...$params);

    if ($update_stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Stock updated successfully',
            'updated' => [
                'quantity' => $quantity,
                'price' => $price,
                'expire_date' => $expire_date
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update stock']);
    }

    $update_stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

$conn->close();
?> 