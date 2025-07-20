<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

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
    echo json_encode(['error' => 'Metodo non consentito']);
    exit;
}

// ✅ Check sessione
if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] !== 'student') {
    echo json_encode([
        'success' => false,
        'error' => 'Non autorizzato',
        'debug' => $_SESSION
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$videoId = $data['video_id'] ?? null;
$comment = trim($data['comment'] ?? '');
$userId = $_SESSION['user']['id'];

// 🔍 Debug controllo
if (!$videoId || !$comment || !$userId) {
    echo json_encode([
        'success' => false,
        'error' => 'Dati mancanti',
        'debug' => compact('videoId', 'comment', 'userId')
    ]);
    exit;
}

// Recupera tutor
$stmt = $conn->prepare("SELECT tutor_id FROM videos WHERE id = ?");
$stmt->bind_param("i", $videoId);
$stmt->execute();
$res = $stmt->get_result();
$tutor = $res->fetch_assoc()['tutor_id'] ?? null;

if (!$tutor) {
    echo json_encode(['success' => false, 'error' => 'Tutor non trovato']);
    exit;
}

// Inserisci commento
$stmt = $conn->prepare("INSERT INTO comments (content_id, user_id, tutor_id, comment, date) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("iiis", $videoId, $userId, $tutor, $comment);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
