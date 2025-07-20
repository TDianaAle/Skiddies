<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Gestione preflight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) && !isset($_SESSION['tutor_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autenticato']);
    exit;
}

$id = $_SESSION['user_id'] ?? $_SESSION['tutor_id'];
$table = isset($_SESSION['tutor_id']) ? 'tutors' : 'users';
$userType = isset($_SESSION['tutor_id']) ? 'tutor' : 'student';

// Modificato per includere anche l'email e il ruolo
$stmt = $conn->prepare("SELECT name, image, email, role FROM $table WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user) {
    echo json_encode([
        'success' => true,
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'] ?? ($userType === 'tutor' ? 'tutor' : 'studente'),
        'image' => $user['image'] ?? 'default.png',
        'userType' => $userType // Aggiungiamo il tipo di utente nella risposta
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Utente non trovato']);
}
