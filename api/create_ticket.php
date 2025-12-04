<?php
include "../config/db.php";
$input = json_decode(file_get_contents("php://input"), true);

$title = $input['title'] ?? '';
$description = $input['description'] ?? '';
$status = 'New';
$priority = $input['tickprio'] ?? '';
$reporter_id = 1; 
$created_at = date('Y-m-d H:i:s');

$sql = "INSERT INTO tickets (title, description, status, priority, reporter_id, created_at)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $title, $description, $status, $priority, $reporter_id, $created_at);

if ($stmt->execute()) {
   echo json_encode([
        "success" => true,
        "data" => [
            "title" => $title,
            "description" => $description,
            "status" => $status,
            "priority" => $priority,
            "reporter_id" => $reporter_id,
            "created_at" => $created_at
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
