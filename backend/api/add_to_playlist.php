<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

// CORS
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Type: application/json');

// Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$userId = $_SESSION['user_id'] ?? null;
$playlistId = $data['playlist_id'] ?? null;
$videoId = $data['video_id'] ?? null;

if (!$userId || !$playlistId || !$videoId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Dati mancanti o non autenticato']);
    exit;
}

// Verifica che la playlist appartenga all'utente
$stmt = $conn->prepare("SELECT id FROM playlists WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $playlistId, $userId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Playlist non trovata o non autorizzato']);
    exit;
}

// Verifica che il video esista
$stmt = $conn->prepare("SELECT id FROM videos WHERE id = ?");
$stmt->bind_param("i", $videoId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Video non trovato']);
    exit;
}

// Assegna il video alla playlist
$stmt = $conn->prepare("UPDATE videos SET playlist_id = ? WHERE id = ?");
$stmt->bind_param("ii", $playlistId, $videoId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Video aggiunto alla playlist']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Errore durante l\'aggiornamento']);
}
