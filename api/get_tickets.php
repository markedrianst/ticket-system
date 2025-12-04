<?php
include "../config/db.php";
$sql = "SELECT * FROM tickets ORDER BY id ASC";
$stmt = $conn->prepare($sql);
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