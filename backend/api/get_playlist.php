<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../components/connect.php';

// CORS
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
    exit;
}

if (!isset($_SESSION['user_id']) && !isset($_SESSION['user']['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Non autenticato']);
    exit;
}

$userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'];

// Prepara la query per prendere i video in playlist con info dettagliate
$sql = "
    SELECT 
        p.id as playlist_id, 
        v.id as video_id,
        v.title,
        v.file_path,
        p.added_at
    FROM playlist p
    JOIN videos v ON p.video_id = v.id
    WHERE p.user_id = ?
    ORDER BY p.added_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$playlist = [];
while ($row = $result->fetch_assoc()) {
    $playlist[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'playlist' => $playlist
]);
