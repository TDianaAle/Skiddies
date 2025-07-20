<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

// ✅ Autenticazione tutor
if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] !== 'tutor') {
    echo json_encode(['success' => false, 'error' => 'Non autorizzato']);
    exit;
}

$tutorId = $_SESSION['user']['id'];

// ✅ Recupera i video + conteggio like
$stmt = $conn->prepare("
    SELECT v.*, 
        (SELECT COUNT(*) FROM likes l WHERE l.content_id = v.id) AS likes
    FROM videos v
    WHERE v.tutor_id = ?
    ORDER BY v.uploaded_at DESC
");
$stmt->bind_param("i", $tutorId);
$stmt->execute();

$res = $stmt->get_result();
$videos = [];

while ($row = $res->fetch_assoc()) {
    $videos[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'category' => $row['category'],
        'file_path' => $row['file_path'],
        'likes' => (int)$row['likes']
    ];
}

echo json_encode([
    'success' => true,
    'videos' => $videos
]);
