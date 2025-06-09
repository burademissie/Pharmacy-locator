<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

include "db.php";

try {
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
    
    // Debug information
    error_log("Search term: " . $searchTerm);
    
    // If search term is empty, return all medicines
    if (empty($searchTerm)) {
        $query = "SELECT m.*, p.name as pharmacy_name, p.location, p.phone 
                 FROM medicine m 
                 JOIN pharmacy p ON m.pharmacy_id = p.id 
                 ORDER BY m.medicine_name ASC";
        $stmt = $conn->prepare($query);
        error_log("Query (empty search): " . $query);
    } else {
        // Search in medicine name, brand name, and description
        $query = "SELECT m.*, p.name as pharmacy_name, p.location, p.phone 
                 FROM medicine m 
                 JOIN pharmacy p ON m.pharmacy_id = p.id 
                 WHERE m.medicine_name LIKE ? 
                 OR m.brand_name LIKE ? 
                 OR m.description LIKE ?
                 ORDER BY m.medicine_name ASC";
        $searchPattern = "%" . $searchTerm . "%";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
        error_log("Query (with search): " . $query);
        error_log("Search pattern: " . $searchPattern);
    }

    if (!$stmt) {
        throw new Exception("Query preparation failed: " . $conn->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Query execution failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $medicines = [];

    while ($row = $result->fetch_assoc()) {
        $medicines[] = [
            'id' => $row['id'],
            'medicine_name' => $row['medicine_name'],
            'brand_name' => $row['brand_name'],
            'description' => $row['description'],
            'price' => number_format($row['price'], 2),
            'quantity' => $row['quantity'],
            'form' => $row['form'],
            'expire_date' => $row['expire_date'],
            'pharmacy_name' => $row['pharmacy_name'],
            'location' => $row['location'],
            'phone' => $row['phone']
        ];
    }

    error_log("Number of medicines found: " . count($medicines));

    echo json_encode([
        'status' => 'success',
        'data' => $medicines,
        'debug' => [
            'search_term' => $searchTerm,
            'query' => $query,
            'count' => count($medicines)
        ]
    ]);

} catch (Exception $e) {
    error_log("Error in searchres.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error searching medicines: ' . $e->getMessage(),
        'debug' => [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]
    ]);
}

$conn->close();
?>