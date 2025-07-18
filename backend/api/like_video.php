<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

// Header CORS completi e permissivi per localhost dev
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Type: application/json');

// Gestione della richiesta preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Rispondi 200 OK con header CORS e senza body
    http_response_code(200);
    exit;
}

// Se non è POST, blocca
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$videoId = $input['video_id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

if (!$userId || !$videoId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Dati mancanti o non autenticato']);
    exit;
}

// Controlla se like già esiste
$stmt = $conn->prepare("SELECT id FROM likes WHERE user_id=? AND video_id=?");
$stmt->bind_param("ii", $userId, $videoId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(['success' => true]);
    exit;
}

// Inserisci like
$stmt = $conn->prepare("INSERT INTO likes (user_id, video_id) VALUES (?, ?)");
$stmt->bind_param("ii", $userId, $videoId);
$stmt->execute();

echo json_encode(['success' => true]);
