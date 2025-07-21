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

// Gestisci reset dell'immagine
if (isset($_POST['reset_image'])) {
    $stmt = $conn->prepare("UPDATE $table SET image = NULL WHERE id = ?");
    $stmt->bind_param("i", $userId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Immagine profilo rimossa']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Errore nell\'aggiornamento del database']);
    }
    $stmt->close();
    exit;
}

if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Nessun file caricato o errore nell\'upload']);
    exit;
}

$image = $_FILES['profile_image'];
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
$maxSize = 5 * 1024 * 1024; // 5MB

if (!in_array($image['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'error' => 'Formato file non supportato']);
    exit;
}

if ($image['size'] > $maxSize) {
    echo json_encode(['success' => false, 'error' => 'File troppo grande (max 5MB)']);
    exit;
}

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

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'filename' => $filename,
        'imageUrl' => 'uploads/profile_images/' . $filename
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Errore nel salvataggio del database']);
}

$stmt->close();
?>
