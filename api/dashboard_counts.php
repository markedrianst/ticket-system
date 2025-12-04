<?php
include "../config/db.php";
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}
$total = "SELECT COUNT(*) AS count FROM tickets WHERE reporter_id = ? AND isActive = 1";
$new = "SELECT COUNT(*) AS count FROM tickets WHERE reporter_id = ? AND status = 'new' AND isActive = 1";
$resolved = "SELECT COUNT(*) AS count FROM tickets WHERE reporter_id = ? AND status = 'resolved' AND isActive = 1";

$stmt = $conn->prepare($total);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$totalResult = $stmt->get_result()->fetch_assoc()['count'];

$stmt = $conn->prepare($new);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$newResult = $stmt->get_result()->fetch_assoc()['count'];

$stmt = $conn->prepare($resolved);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resolvedResult = $stmt->get_result()->fetch_assoc()['count'];

echo json_encode([
    "success" => true,
    "total_tickets" => $totalResult,
    "new_tickets" => $newResult,
    "resolved_tickets" => $resolvedResult
]);

$conn->close();
?>
