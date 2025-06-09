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
    
    if (!isset($data['medicine_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing medicine ID']);
        exit();
    }

    $medicine_id = $data['medicine_id'];
    $pharmacy_id = $_SESSION['pharmacy_id'];
    $add_quantity = isset($data['add_quantity']) ? (int)$data['add_quantity'] : 0;
    $remove_quantity = isset($data['remove_quantity']) ? (int)$data['remove_quantity'] : 0;
    $new_price = isset($data['new_price']) ? (float)$data['new_price'] : null;

    // Verify the medicine belongs to this pharmacy
    $check_stmt = $conn->prepare("SELECT quantity FROM medicine WHERE id = ? AND pharmacy_id = ?");
    $check_stmt->bind_param("ii", $medicine_id, $pharmacy_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(403);
        echo json_encode(['error' => 'Medicine not found or unauthorized']);
        exit();
    }

    $current_quantity = $result->fetch_assoc()['quantity'];
    $new_quantity = $current_quantity + $add_quantity - $remove_quantity;

    if ($new_quantity < 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Cannot remove more items than available']);
        exit();
    }

    // Build update query based on provided fields
    $updates = ["quantity = ?"];
    $types = "i";
    $params = [$new_quantity];

    if ($new_price !== null) {
        $updates[] = "price = ?";
        $types .= "d";
        $params[] = $new_price;
    }

    $update_query = "UPDATE medicine SET " . implode(", ", $updates) . " WHERE id = ? AND pharmacy_id = ?";
    $types .= "ii";
    $params[] = $medicine_id;
    $params[] = $pharmacy_id;

    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param($types, ...$params);

    if ($update_stmt->execute()) {
        echo json_encode(['success' => true, 'new_quantity' => $new_quantity]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update medicine']);
    }

    $update_stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

$conn->close();
?> 