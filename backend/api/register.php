<?php
// backend/api/register.php

include __DIR__ . '/../components/connect.php';
session_start();

// Imposta intestazioni per CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

// Gestione preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0);
}

// Accetta solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Metodo non consentito']);
    exit;
}

// Leggi i dati JSON dalla richiesta
$name = trim($_POST['name'] ?? '');
$role = trim($_POST['profession'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass = $_POST['pass'] ?? '';
$cpass = $_POST['cpass'] ?? '';
$image = $_FILES['image'] ?? null;


// Validazioni base
if ($pass !== $cpass) {
    echo json_encode(['error' => 'Le password non corrispondono.']);
    exit;
}

// Verifica email esistente
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['error' => 'Email già registrata.']);
    exit;
}
$stmt->close();

// Hash password
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

// Gestione immagine se base64
$filename = null;
if ($image && $image['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_', true) . '.' . $ext;
    $destination = __DIR__ . '/../uploaded_files/' . $filename;

    if (!move_uploaded_file($image['tmp_name'], $destination)) {
        echo json_encode(['error' => 'Errore nel caricamento immagine.']);
        exit;
    }
} else {
    echo json_encode(['error' => 'Nessuna immagine inviata.']);
    exit;
}
// Inserisce nel db
$insert = $conn->prepare("INSERT INTO users (name, email, password, image, role) VALUES (?, ?, ?, ?, ?)");
$insert->bind_param("sssss", $name, $email, $hashed_pass, $filename, $role);

if ($insert->execute()) {
    echo json_encode(['success' => 'Registrazione completata!']);
} else {
    echo json_encode(['error' => 'Errore durante la registrazione.']);
}
$insert->close();
