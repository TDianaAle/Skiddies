<?php
require_once __DIR__ . '/../components/connect.php';
session_start();

// CORS Headers
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Type: application/json');

// Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Metodo non consentito']);
    exit;
}

// Ricezione dati JSON
$data = json_decode(file_get_contents("php://input"), true);
$email = strtolower(trim($data['email'] ?? ''));
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['error' => 'Email e password obbligatorie']);
    exit;
}

// Funzione per login da una tabella
function checkUser($conn, $table, $email, $password) {
    $stmt = $conn->prepare("SELECT id, email, password, role FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Debug temporaneo per vedere i dati recuperati dal DB
        error_log("USER FETCHED FROM DB ($table): " . print_r($user, true));

        if (password_verify($password, $user['password'])) {
            return [
                'id' => (int)$user['id'],  // cast esplicito a intero per sicurezza
                'email' => $user['email'],
                'role' => $user['role']
            ];
        }
    }
    return null;
}

$user = checkUser($conn, 'users', $email, $password);
$fromTable = 'users';

if (!$user) {
    $user = checkUser($conn, 'tutors', $email, $password);
    $fromTable = 'tutors';
}

if ($user && !empty($user['id'])) {
    $role = ($fromTable === 'tutors') ? 'tutor' : 'student';

    $_SESSION['user'] = [
        'id' => $user['id'],
        'role' => $role,
        'email' => $user['email'],
        'table' => $fromTable
    ];
    
    // Imposta la variabile di sessione corretta in base al tipo di utente
    if ($fromTable === 'tutors') {
        $_SESSION['tutor_id'] = $user['id'];
        unset($_SESSION['user_id']); // Rimuovi user_id se è un tutor
    } else {
        $_SESSION['user_id'] = $user['id'];
        unset($_SESSION['tutor_id']); // Rimuovi tutor_id se è uno studente
    }
    
    $_SESSION['role'] = $role;

    // Debug finale: dati salvati in sessione
    error_log("USER FINAL SESSION DATA: " . print_r($_SESSION, true));

    session_write_close(); // Assicurarsi che la sessione venga salvata prima della risposta

    echo json_encode([
        'success' => true,
        'redirect' => $role === 'tutor' ? 'tutor' : 'student',
        'role' => $role
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Credenziali non valide o id mancante']);
}
