<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
// backend/api/register.php

require_once __DIR__ . '/../components/connect.php';

// Headers CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');

// Gestione preflight (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0);
}

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Metodo non consentito']);
    exit;
}

// Dati in JSON
$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$confirmPassword = $data['confirmPassword'] ?? '';
$role = $data['role'] ?? '';
$image = $data['image'] ?? null;

// Validazioni base
if (empty($name) || empty($email) || empty($password) || empty($confirmPassword) || empty($role)) {
    echo json_encode(['error' => 'Tutti i campi sono obbligatori']);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode(['error' => 'Le password non coincidono']);
    exit;
}

// Verifica se l'email esiste già
$table = $role === 'tutor' ? 'tutors' : 'users';
$check = $conn->prepare("SELECT id FROM $table WHERE email = ?");
$check->bind_param('s', $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(['error' => 'Email già registrata']);
    exit;
}
$check->close();

// Crittografia password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Inserimento nel database
$stmt = $conn->prepare("INSERT INTO $table (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $name, $email, $hashedPassword, $role);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Errore nella registrazione']);
}
$stmt->close();
?>
