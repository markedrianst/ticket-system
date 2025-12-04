<?php
include "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$ticket_id = $input['ticket_id'];

$sql = "UPDATE tickets 
        SET title = ?, description = ?, priority = ?, status = ?
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $title, $description, $priority, $status, $ticket_id);

$response = [];
if ($stmt->execute()) {
    $response = ["success" => true,
    "data" => [
            "Id" => $ticket_id,
            "title" => $title,
            "description" => $description,
            "priority" => $priority,
            "status" => $status
        ] ];
} else {
    $response = ["success" => false];
}

echo json_encode($response);
$stmt->close();
$conn->close();
?>
