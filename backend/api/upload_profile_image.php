<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) && !isset($_SESSION['tutor_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autenticato']);
    exit;
}

$userId = $_SESSION['user_id'] ?? $_SESSION['tutor_id'];
$table = isset($_SESSION['tutor_id']) ? 'tutors' : 'users';

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Errore file immagine']);
    exit;
}

$image = $_FILES['image'];
$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '.' . $ext;

$uploadDir = __DIR__ . '/../uploads/profile_images/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$destination = $uploadDir . $filename;
if (!move_uploaded_file($image['tmp_name'], $destination)) {
    echo json_encode(['success' => false, 'error' => 'Upload fallito']);
    exit;
}

// Salva nel database
$stmt = $conn->prepare("UPDATE $table SET image = ? WHERE id = ?");
$stmt->bind_param("si", $filename, $userId);
$stmt->execute();
$stmt->close();

echo json_encode([
    'success' => true,
    'imageUrl' => 'uploads/profile_images/' . $filename
]);
