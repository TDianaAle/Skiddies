<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

// Controllo sessione e ruolo
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'tutor') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Non autorizzato']);
    exit;
}

$tutorId = $_SESSION['user']['id'];

// Recupera i video del tutor
$stmt = $conn->prepare("SELECT id, title, category, file_path FROM videos WHERE tutor_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $tutorId);
$stmt->execute();
$result = $stmt->get_result();

$videos = [];
while ($row = $result->fetch_assoc()) {
    $videos[] = $row;
}

echo json_encode(['success' => true, 'videos' => $videos]);
