<?php

session_start();
require_once __DIR__ . '/../components/connect.php';

// Headers CORS
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Type: application/json');

// Gestione preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Consenti solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
    exit;
}

// Verifica sessione e ruolo
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'tutor') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Non autorizzato, accesso riservato ai tutor']);
    exit;
}

$tutorId = $_SESSION['user']['id'];
$title = $_POST['title'] ?? '';
$category = $_POST['category'] ?? '';
$videoFile = $_FILES['video'] ?? null;

// Validazioni di base
if (empty($title) || empty($category) || !$videoFile) {
    echo json_encode(['success' => false, 'error' => 'Tutti i campi sono obbligatori.']);
    exit;
}

$allowedCategories = ['informatica', 'matematica', 'economia'];
if (!in_array($category, $allowedCategories)) {
    echo json_encode(['success' => false, 'error' => 'Categoria non valida.']);
    exit;
}

if ($videoFile['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Errore nel file upload: ' . $videoFile['error']]);
    exit;
}

// Cartella di destinazione
$uploadDir = __DIR__ . '/../uploads/videos/';
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo json_encode(['success' => false, 'error' => 'Impossibile creare la cartella di destinazione']);
        exit;
    }
}

// Sanitize nome file
$safeName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", basename($videoFile['name']));
$filename = uniqid('vid_', true) . '_' . $safeName;
$targetPath = $uploadDir . $filename;
$relativePath = 'uploads/videos/' . $filename;

// Sposta il file e salva nel DB
if (move_uploaded_file($videoFile['tmp_name'], $targetPath)) {
    $stmt = $conn->prepare("INSERT INTO videos (tutor_id, title, category, file_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $tutorId, $title, $category, $relativePath);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Errore nel salvataggio nel database']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Errore nel caricamento del file']);
}
