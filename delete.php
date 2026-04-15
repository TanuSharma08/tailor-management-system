<?php
$conn = new mysqli("localhost", "root", "", "tailor_db");
header('Content-Type: application/json');

if ($conn->connect_error) {
    echo json_encode(['success'=>false, 'error'=>'DB connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
    $ids = array_map('intval', explode(",", $_POST['delete_ids']));
    $idList = implode(",", $ids);

    if (!empty($idList)) {
        $sql = "DELETE FROM measurements WHERE id IN ($idList)";
        if ($conn->query($sql) === TRUE) {
            echo json_encode([
                'success'=>true,
                'deletedIds'=>$ids
            ]);
        } else {
            echo json_encode(['success'=>false, 'error'=>'Error deleting records']);
        }
    } else {
        echo json_encode(['success'=>false, 'error'=>'Invalid request']);
    }
} else {
    echo json_encode(['success'=>false, 'error'=>'Invalid request']);
}
$conn->close();
?>