<?php
include "../config/db.php";
$ticket_id = $_GET['ticket_id'] ?? 0;
$sql_comments = "SELECT tc.comment, tc.created_at, u.name AS author
                 FROM tickets t
                 LEFT JOIN ticket_comments tc ON t.id = tc.ticket_id
                 LEFT JOIN users u ON u.id = tc.user_id
                 WHERE t.id = ?";

$stmt_comments = $conn->prepare($sql_comments);
$stmt_comments->bind_param("i", $ticket_id);
$stmt_comments->execute();
$result_comments = $stmt_comments->get_result();

$comments = [];
while ($row = $result_comments->fetch_assoc()) {
    $comments[] = $row;
}
$stmt_comments->close();
$sql_logs = "SELECT action, created_at FROM ticket_logs WHERE ticket_id = ? ORDER BY created_at DESC";
$stmt_logs = $conn->prepare($sql_logs);
$stmt_logs->bind_param("i", $ticket_id);
$stmt_logs->execute();
$result_logs = $stmt_logs->get_result();

$logs = [];
while ($row = $result_logs->fetch_assoc()) {
    $logs[] = $row;
}
$stmt_logs->close();

echo json_encode([
    "success" => true,
    "comments" => $comments,
    "Actions" => $logs
]);

$conn->close();
?>
