<?php
if ($_FILES['file']) {
    $file = $_FILES['file'];
    $uploadDir = 'uploads/';
    $filename = basename($file['name']);
    $targetPath = $uploadDir . uniqid() . '_' . $filename;

    // Allow only PDF, Word, and Excel files
    $allowed = [
        'application/pdf', // .pdf
        'application/msword', // .doc
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
        'application/vnd.ms-excel', // .xls
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // .xlsx
    ];

    if (!in_array($file['type'], $allowed)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type. Only PDF, Word, and Excel files are allowed.']);
        exit;
    }

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode(['location' => '/' . $targetPath]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to upload.']);
    }
}
