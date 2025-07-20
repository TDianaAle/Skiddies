<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['name']) || trim($data['name']) === '') {
    echo json_encode(['success' => false, 'error' => 'Nome non valido']);
    exit;
}

if (!isset($_SESSION['user_id']) && !isset($_SESSION['tutor_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autenticato']);
    exit;
}

$id = $_SESSION['user_id'] ?? $_SESSION['tutor_id'];
$table = isset($_SESSION['tutor_id']) ? 'tutors' : 'users';

$name = trim($data['name']);
$stmt = $conn->prepare("UPDATE $table SET name = ? WHERE id = ?");
$stmt->bind_param("si", $name, $id);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => true]);
