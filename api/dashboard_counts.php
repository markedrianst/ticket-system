<?php
include "../config/db.php";

$total = "SELECT COUNT(*) AS count FROM tickets";
$new = "SELECT COUNT(*) AS count FROM tickets WHERE status='new'";
$resolved = "SELECT COUNT(*) AS count FROM tickets WHERE status='resolved'";

$stmt = $conn->prepare($total);
$stmt->execute();
$totalResult = $stmt->get_result()->fetch_assoc()['count'];

$stmt = $conn->prepare($new);
$stmt->execute();
$newResult = $stmt->get_result()->fetch_assoc()['count'];


$stmt = $conn->prepare($resolved);
$stmt->execute();
$resolvedResult = $stmt->get_result()->fetch_assoc()['count'];

echo json_encode([
    "success" => true,
    "total_tickets" => $totalResult,
    "new_tickets" => $newResult,
    "resolved_tickets" => $resolvedResult
]);
?>
