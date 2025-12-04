<?php
include "../config/db.php";
session_start();

$user_id = $_SESSION['user_id'] ?? 0; 
if (!$user_id) {
    echo json_encode([
        "success" => false,
        "error" => "Unauthorized"
    ]);
    exit;
}

$sql = "SELECT t.*, u.name AS reporter_name, u.email AS reporter_email
        FROM tickets t
        LEFT JOIN users u ON t.reporter_id = u.id
        WHERE t.reporter_id = ? AND t.isActive = 1
        ORDER BY t.id ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$tickets = [];
while ($row = $result->fetch_assoc()) {
    $tickets[] = $row;
}

echo json_encode([
    "success" => true,
    "data" => $tickets
]);

$stmt->close();
$conn->close();
?>
