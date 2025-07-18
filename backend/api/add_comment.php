<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
    exit;
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'studente') {
    echo json_encode(['success' => false, 'error' => 'Non autorizzato']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$videoId = $data['video_id'] ?? null;
$comment = trim($data['comment'] ?? '');
$userId = $_SESSION['user']['id'] ?? null;

if (!$videoId || !$comment || !$userId) {
    echo json_encode([
        'success' => false,
        'error' => 'Dati mancanti',
        'debug' => compact('videoId', 'comment', 'userId')
    ]);
    exit;
}

// Prendo tutor_id dal video
$stmt = $conn->prepare("SELECT tutor_id FROM videos WHERE id = ?");
$stmt->bind_param("i", $videoId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$tutorId = $row['tutor_id'] ?? null;

if (!$tutorId) {
    echo json_encode([
        'success' => false,
        'error' => 'Tutor non trovato per questo video',
    ]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO comments (content_id, user_id, tutor_id, comment, date) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("iiis", $videoId, $userId, $tutorId, $comment);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
