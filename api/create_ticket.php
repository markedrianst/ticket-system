<?php
include "../config/db.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

// Get input data
$input = json_decode(file_get_contents("php://input"), true);

$title = $input['title'] ?? '';
$description = $input['description'] ?? '';
$status = 'New';
$priority = $input['tickprio'] ?? '';
$reporter_id = $_SESSION['user_id']; // get from session
$created_at = date('Y-m-d H:i:s');

// Validate input
if (empty($title) || empty($description)) {
    echo json_encode(["success" => false, "message" => "Title and description are required."]);
    exit;
}

// Prepare SQL
$sql = "INSERT INTO tickets (title, description, status, priority, reporter_id, created_at)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => $conn->error]);
    exit;
}

$stmt->bind_param("ssssis", $title, $description, $status, $priority, $reporter_id, $created_at);
if ($stmt->execute()) {
    $ticket_id = $stmt->insert_id;
    echo json_encode([
        "success" => true,
        "data" => [
            "id" => $ticket_id,
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
