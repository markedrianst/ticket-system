<?php
include "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$ticket_id = $input['ticket_id'] ?? null;
$isActive = 0;

$sql = "UPDATE tickets SET isActive = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param("ii", $isActive, $ticket_id);

$response = [];
if ($stmt->execute()) {
    $response = ["success" => true];
} else {
    $response = ["success" => false, "message" => $stmt->error];
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
