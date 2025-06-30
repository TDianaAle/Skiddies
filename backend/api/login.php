<?php
// backend/api/login.php

require_once __DIR__ . '/../components/connect.php';
session_start();

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
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

// Validazioni base
if (empty($email) || empty($password)) {
    echo json_encode(['error' => 'Email e password obbligatorie']);
    exit;
}

// Controlla se la connessione al database è attiva
if (!$conn) {
    echo json_encode(['error' => 'Connessione al database fallita']);
    exit;
}

// Cerca utente
$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Controlla se l'utente esiste
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verifica la password
    if (password_verify($password, $user['password'])) {
        // Imposta la sessione dell'utente
        $_SESSION['user_id'] = $user['id'];

        // Successo
        echo json_encode(['success' => true]);
    } else {
        // Password errata
        echo json_encode(['error' => 'Password errata']);
    }
} else {
    // Utente non trovato
    echo json_encode(['error' => 'Utente non trovato']);
}

// Chiudi la dichiarazione
$stmt->close();
?>
