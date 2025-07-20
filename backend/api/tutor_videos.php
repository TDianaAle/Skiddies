<?php
session_start();

require_once __DIR__ . '/../components/connect.php';

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tutor') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Non autorizzato']);
    exit;
}

$tutorId = $_SESSION['user_id'];

// Qui la query deve recuperare titolo, categoria e file_path
$stmt = $conn->prepare("SELECT id, title, category, file_path FROM videos WHERE tutor_id = ?");
$stmt->bind_param("i", $tutorId);
$stmt->execute();
$res = $stmt->get_result();

$videos = [];
while ($row = $res->fetch_assoc()) {
    $videos[] = $row;
}

echo json_encode(['success' => true, 'videos' => $videos]);
