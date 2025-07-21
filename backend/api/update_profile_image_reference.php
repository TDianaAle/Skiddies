<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Includi la connessione al database
require_once '../components/connect.php';

// Verifica che l'utente sia loggato
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Utente non autenticato']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    if (isset($_POST['reset_image'])) {
        // Reset dell'immagine profilo (rimuovi il riferimento dal database)
        $stmt = $conn->prepare("UPDATE users SET image = NULL WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Riferimento immagine rimosso']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Errore nell\'aggiornamento del database']);
        }
    } else if (isset($_POST['image_name'])) {
        // Aggiorna il riferimento all'immagine nel database
        $image_name = $_POST['image_name'];
        
        $stmt = $conn->prepare("UPDATE users SET image = ? WHERE id = ?");
        $stmt->bind_param("si", $image_name, $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Riferimento immagine aggiornato']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Errore nell\'aggiornamento del database']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Parametri mancanti']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Errore del server: ' . $e->getMessage()]);
}

$conn->close();
?>
