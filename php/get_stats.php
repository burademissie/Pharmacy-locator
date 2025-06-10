 <?php
// Include the database connection
require_once 'db.php';

// Check if the search parameter is set
if (!isset($_GET['search']) || empty(trim($_GET['search']))) {
    echo json_encode([]);
    exit;
}

$search = trim($_GET['search']);

// Prepare and execute the SQL query
$sql = "SELECT m.medicine_name, m.brand_name, m.description, m.price, m.quantity, m.form, 
               p.pharmacy_name, p.location, p.phone
        FROM medicine m
        JOIN pharmacy
        p ON m.pharmacy_id = p.id
        WHERE m.medicine_name LIKE ? OR m.brand_name LIKE ? OR m.description LIKE ?";

$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();

$result = $stmt->get_result();

$medicines = [];

while ($row = $result->fetch_assoc()) {
    $medicines[] = $row;
}

echo json_encode($medicines);

$stmt->close();
$conn->close();
