<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');

if (!isset($_SESSION['user_id']) && !isset($_SESSION['tutor_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autenticato']);
    exit;
}

$id = $_SESSION['user_id'] ?? $_SESSION['tutor_id'];
$table = isset($_SESSION['tutor_id']) ? 'tutors' : 'users';

$stmt = $conn->prepare("SELECT name, image FROM $table WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user) {
    echo json_encode([
        'success' => true,
        'name' => $user['name'],
        'image' => $user['image'] ?? 'default.png'
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Utente non trovato']);
}
