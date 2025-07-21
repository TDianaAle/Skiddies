<?php


// non serve usare unique_id quando gli id nel db sono AUTOINCREMENT e lavoriamo con PHPSESSID

session_start();
require_once __DIR__ . '/../components/connect.php';

// CORS
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
    exit;
}

// Verifica autenticazione
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user']['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Non autenticato']);
    exit;
}

$userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'];

// Prendi dati POST JSON
$data = json_decode(file_get_contents('php://input'), true);
$videoId = isset($data['video_id']) ? intval($data['video_id']) : null;

if (!$videoId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID video mancante o non valido']);
    exit;
}

// Verifica che il video esista
$checkVideo = $conn->prepare("SELECT id FROM videos WHERE id = ?");
$checkVideo->bind_param("i", $videoId);
$checkVideo->execute();
$result = $checkVideo->get_result();
if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Video non trovato']);
    exit;
}
$checkVideo->close();

// Controlla se il video è già nella playlist
$checkPlaylist = $conn->prepare("SELECT id FROM playlist WHERE user_id = ? AND video_id = ?");
$checkPlaylist->bind_param("ii", $userId, $videoId);
$checkPlaylist->execute();
$checkPlaylist->store_result();

if ($checkPlaylist->num_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Il video è già nella tua playlist']);
    exit;
}
$checkPlaylist->close();

// Inserisci nella playlist
$insert = $conn->prepare("INSERT INTO playlist (user_id, video_id) VALUES (?, ?)");
$insert->bind_param("ii", $userId, $videoId);

if ($insert->execute()) {
    echo json_encode(['success' => true, 'message' => 'Video aggiunto alla playlist']);
} else {
    echo json_encode(['success' => false, 'error' => 'Errore nell\'aggiunta del video: ' . $insert->error]);
}

$insert->close();
$conn->close();
