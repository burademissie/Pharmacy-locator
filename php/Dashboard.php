<?php
session_start();
include "db.php";

// Check if pharmacy is logged in
if (!isset($_SESSION['pharmacy_id'])) {
    header("Location: login.php");
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to count medicines for a pharmacy
function countMedicines($conn, $pharmacyId) {
    $sql = "SELECT COUNT(*) as total FROM medicine WHERE pharmacy_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pharmacyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Get pharmacy ID from session
$pharmacyId = $_SESSION['pharmacy_id'];

// Get medicine count
$medicineCount = countMedicines($conn, $pharmacyId);

// Get all medicines for this pharmacy
$sql = "SELECT id, medicine_name, description, price, quantity, form, expire_date, brand_name FROM medicine WHERE pharmacy_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pharmacyId);
$stmt->execute();
$result = $stmt->get_result();
$medicines = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/Dasbord.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="../html/landing.html" class="a"><div class="sidebar-header">
                <h2>MED FINDER</h2>
            </div></a>
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href=""><i class="fas fa-pills"></i>Dashboard</a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- Main Content -->
        <div class="main-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div>
                    <h1 class="h1-dash">Pharmacy Medicines</h1>
                    <p style="color: #666; margin-top: 0.5rem;">Total Medicines: <?php echo $medicineCount; ?></p>
                </div>
                <a href="../html/Addmed.html">
                    <button id="addMedicineBtn" class="Addmed-btn">+ Add Medicine</button>
                </a>
            </div>
            <!-- Medicines List Section -->
            <div class="results-section" id="medicinesList">
                <?php if (count($medicines) > 0): ?>
                    <?php foreach ($medicines as $med): ?>
                        <div class="pharmacy-card">
                            <div style="flex:1">
                                <div class="pharmacy-name">
                                    <?php echo htmlspecialchars($med['medicine_name']); ?>
                                    <span style="font-size:0.7rem; color:rgba(255,255,255,0.6)">
                                        (<?php echo htmlspecialchars($med['brand_name']); ?>)
                                    </span>
                                </div>
                                <div class="pharmacy-description"><?php echo htmlspecialchars($med['description']); ?></div>
                                <div class="pharmacy-details">
                                    <span>Price: ₦<?php echo htmlspecialchars($med['price']); ?></span>
                                    <span>Qty: <?php echo htmlspecialchars($med['quantity']); ?></span>
                                    <span>Form: <?php echo htmlspecialchars($med['form']); ?></span>
                                </div>
                            </div>
                            <button class="update-btn" data-medicine-id="<?php echo $med['id']; ?>">
                                <svg class="svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path d="M12 19V5M5 12l7-7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Update
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <!-- Not Found Message -->
            <div id="notFoundMessage" class="not-found" style="<?php echo count($medicines) > 0 ? 'display:none' : 'display:block'; ?>">
                <i class="fas fa-prescription-bottle-alt"></i>
                <h2>No Medicines Found</h2>
                <p>This pharmacy does not have any medicines yet.</p>
                <p>Click 'Add Medicine' to add your first medicine.</p>
            </div>
        </div>
    </div>
    <script src="../js/dashboard.js"></script>
</body>
</html>