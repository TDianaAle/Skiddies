<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

error_log("PHPSESSID: " . session_id());
error_log("SESSION DATA: " . print_r($_SESSION, true));


// Header CORS completi e permissivi per localhost dev
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Type: application/json');

// Gestione della richiesta preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
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

// Ottieni il tutor_id del video
$stmt = $conn->prepare("SELECT tutor_id FROM videos WHERE id = ?");
$stmt->bind_param("i", $videoId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Video non trovato']);
    exit;
}

$video = $result->fetch_assoc();
$tutorId = $video['tutor_id'];

// Controlla se like già esiste
$stmt = $conn->prepare("SELECT user_id FROM likes WHERE user_id=? AND tutor_id=? AND content_id=?");
$stmt->bind_param("iii", $userId, $tutorId, $videoId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Like già presente']);
    exit;
}

// Inserisci like
$stmt = $conn->prepare("INSERT INTO likes (user_id, tutor_id, content_id) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $userId, $tutorId, $videoId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Like aggiunto con successo']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Errore nel salvare il like']);
}
?>