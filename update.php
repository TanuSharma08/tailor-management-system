<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "tailor_db");
if ($conn->connect_error) {
    echo json_encode(['success'=>false,'error'=>"Connection failed"]);
    exit;
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success'=>false,'error'=>"Invalid ID"]);
    exit;
}

$id = intval($_POST['id']);
unset($_POST['id']); // don’t update primary key

if (empty($_POST)) {
    echo json_encode(['success'=>false,'error'=>"No data provided"]);
    exit;
}

// Remove empty or null fields to avoid prepare error
$updates = []; $values = [];
foreach ($_POST as $col => $val) {
    if($val !== null && $col !== "") {
        $updates[] = "`$col` = ?";
        $values[] = $val;
    }
}

if(empty($updates)){
    echo json_encode(['success'=>false,'error'=>"No valid data to update"]);
    exit;
}

$sql = "UPDATE measurements SET ".implode(", ", $updates)." WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success'=>false,'error'=>"Prepare failed: ".$conn->error]);
    exit;
}

$values[] = $id;
$types = str_repeat("s", count($values)-1)."i"; 
$stmt->bind_param($types, ...$values);

if ($stmt->execute()) {
    $stmt2 = $conn->prepare("SELECT * FROM measurements WHERE id=?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $updated = $stmt2->get_result()->fetch_assoc();
    echo json_encode(['success'=>true,'updatedRecord'=>$updated]);
} else {
    echo json_encode(['success'=>false,'error'=>"Update failed: ".$stmt->error]);
}

$stmt->close();
$conn->close();
?>