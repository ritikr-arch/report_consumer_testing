<?php
if (!empty($_FILES['file']['name'])) {
    $file = $_FILES['file'];

    $targetDir = 'uploads/';
    $filename = basename($file['name']);
    $targetFile = $targetDir . time() . '-' . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        echo json_encode(['location' => '/' . $targetFile]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'File move failed']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
}
