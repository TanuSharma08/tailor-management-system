<?php
$cn = mysqli_connect("localhost", "root", "", "tailor_db");
header('Content-Type: application/json');

if (!$cn) {
    echo json_encode(['suggestions' => []]);
    exit;
}

if (isset($_GET['name'])) {
    $name = trim($_GET['name']);
    if ($name === '') {
        echo json_encode(['suggestions' => []]);
        exit;
    }

    $name_safe = mysqli_real_escape_string($cn, $name);

    // Use LOWER + LIKE and trim spaces
    $q = mysqli_query($cn, "SELECT DISTINCT customer_name, phone 
                            FROM measurements 
                            WHERE LOWER(TRIM(customer_name)) LIKE LOWER('%$name_safe%') 
                            ORDER BY id DESC 
                            LIMIT 10");

    $suggestions = [];
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $suggestions[] = $row;
        }
    }
    
    echo json_encode(['suggestions' => $suggestions]);
}
?>