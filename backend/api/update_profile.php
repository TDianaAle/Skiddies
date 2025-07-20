<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

// CORS Headers
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Gestione preflight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Imposta il Content-Type solo per le risposte non-OPTIONS
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

// Verifica che i campi obbligatori siano presenti
if (!isset($data['name']) || trim($data['name']) === '') {
    echo json_encode(['success' => false, 'error' => 'Nome non valido']);
    exit;
}

if (!isset($data['email']) || trim($data['email']) === '') {
    echo json_encode(['success' => false, 'error' => 'Email non valida']);
    exit;
}

if (!isset($_SESSION['user_id']) && !isset($_SESSION['tutor_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non autenticato']);
    exit;
}

$id = $_SESSION['user_id'] ?? $_SESSION['tutor_id'];
$table = isset($_SESSION['tutor_id']) ? 'tutors' : 'users';

$name = trim($data['name']);
$email = trim($data['email']);
$password = isset($data['password']) && !empty($data['password']) ? trim($data['password']) : null;

// Verifica se l'email è già in uso da un altro utente
$stmt = $conn->prepare("SELECT id FROM $table WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'Email già in uso da un altro utente']);
    exit;
}
$stmt->close();

// Aggiorna i dati dell'utente
if ($password) {
    // Se è stata fornita una nuova password, aggiorna anche quella
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE $table SET name = ?, email = ?, password = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $hashedPassword, $id);
} else {
    // Altrimenti aggiorna solo nome ed email
    $stmt = $conn->prepare("UPDATE $table SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $id);
}

if ($stmt->execute()) {
    // Aggiorna anche i dati di sessione
    if (isset($_SESSION['user'])) {
        $_SESSION['user']['email'] = $email;
    }
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Errore nell\'aggiornamento del profilo']);
}
$stmt->close();
