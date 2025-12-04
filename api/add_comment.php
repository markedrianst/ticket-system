<?php
include "../config/db.php";
session_start();

$user_id = $_SESSION['user_id'] ?? 0; 

$input = json_decode(file_get_contents("php://input"), true);

$ticket_id = $input['ticket_id'] ?? 0;
$comment = trim($input['comment'] ?? '');

if(!$ticket_id || !is_numeric($ticket_id)) {
    echo json_encode(["success" => false, "error" => "Invalid ticket_id"]);
    exit;
}

if(empty($comment)) {
    echo json_encode(["success" => false, "error" => "Comment cannot be empty"]);
    exit;
}

$created_at = date('Y-m-d H:i:s');

$sql = "INSERT INTO ticket_comments (ticket_id, user_id, comment, created_at) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $ticket_id, $user_id, $comment, $created_at);

$response = [];
if ($stmt->execute()) {
    $response = [
        "success" => true,
        "data" => [
            "ticket_id" => $ticket_id,
            "user_id" => $user_id,
            "comment" => $comment,
            "created_at" => $created_at
        ]
    ];
} else {
    $response = ["success" => false, "error" => $stmt->error];
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
