<?php
require_once __DIR__ . '/../components/connect.php';

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Content-Type: application/json');

$videoId = $_GET['video_id'] ?? null;

if (!$videoId) {
    echo json_encode(['success' => false, 'error' => 'video_id mancante']);
    exit;
}

$stmt = $conn->prepare("
    SELECT c.id, c.comment, c.date, u.name AS student_name
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.content_id = ?
    ORDER BY c.date DESC
");
$stmt->bind_param("i", $videoId);
$stmt->execute();

$res = $stmt->get_result();
$comments = [];

while ($row = $res->fetch_assoc()) {
    $comments[] = $row;
}

echo json_encode([
    'success' => true,
    'comments' => $comments
]);
