<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

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
    echo json_encode(['error' => 'Metodo non consentito']);
    exit;
}

if (!isset($_SESSION['user']['id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorizzato']);
    exit;
}

if ($_SESSION['user']['role'] !== 'studente') {
    http_response_code(403);
    echo json_encode(['error' => 'Accesso negato']);
    exit;
}

$stmt = $conn->prepare("
    SELECT v.id, v.title, v.category, v.file_path, t.name AS tutor_name, v.tutor_id
    FROM videos v
    JOIN tutors t ON v.tutor_id = t.id
");
$stmt->execute();
$result = $stmt->get_result();

$videos = [];
while ($row = $result->fetch_assoc()) {
    $videos[] = $row;
}

echo json_encode(['videos' => $videos]);
