<?php
session_start();
require_once __DIR__ . '/../components/connect.php';

// Headers CORS
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, DELETE, OPTIONS');
header('Content-Type: application/json');

// Gestione preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Consenti solo POST e DELETE
if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'DELETE'])) {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
    exit;
}

// Verifica sessione e ruolo
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'tutor') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Non autorizzato, accesso riservato ai tutor']);
    exit;
}

$tutorId = $_SESSION['user']['id'];

// Ottieni l'ID del video
$data = json_decode(file_get_contents("php://input"), true);
$videoId = $data['video_id'] ?? null;

if (!$videoId || !is_numeric($videoId)) {
    echo json_encode(['success' => false, 'error' => 'ID video non valido']);
    exit;
}

$videoId = intval($videoId);

// Verifica che il video appartenga al tutor corrente
$stmt = $conn->prepare("SELECT id, file_path FROM videos WHERE id = ? AND tutor_id = ?");
$stmt->bind_param("ii", $videoId, $tutorId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Video non trovato o non autorizzato ad eliminarlo']);
    exit;
}

$video = $result->fetch_assoc();
$filePath = $video['file_path'];

// Inizia una transazione per garantire la consistenza dei dati
$conn->begin_transaction();

try {
    // 1. Elimina i commenti associati al video
    $stmt = $conn->prepare("DELETE FROM comments WHERE content_id = ?");
    $stmt->bind_param("i", $videoId);
    $stmt->execute();
    
    // 2. Elimina i like associati al video
    $stmt = $conn->prepare("DELETE FROM likes WHERE content_id = ?");
    $stmt->bind_param("i", $videoId);
    $stmt->execute();
    
    // 3. Rimuovi il video dalle playlist degli utenti
    $stmt = $conn->prepare("DELETE FROM playlist WHERE video_id = ?");
    $stmt->bind_param("i", $videoId);
    $stmt->execute();
    
    // 4. Elimina il record del video dal database
    $stmt = $conn->prepare("DELETE FROM videos WHERE id = ? AND tutor_id = ?");
    $stmt->bind_param("ii", $videoId, $tutorId);
    $stmt->execute();
    
    // 5. Elimina il file fisico dal server
    $fullFilePath = __DIR__ . '/../' . $filePath;
    if (file_exists($fullFilePath)) {
        if (!unlink($fullFilePath)) {
            // Se non riusciamo a eliminare il file, facciamo rollback
            throw new Exception('Impossibile eliminare il file video dal server');
        }
    }
    
    // Conferma tutte le operazioni
    $conn->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Video eliminato con successo dal database e dal server'
    ]);
    
} catch (Exception $e) {
    // In caso di errore, annulla tutte le operazioni
    $conn->rollback();
    echo json_encode([
        'success' => false, 
        'error' => 'Errore durante l\'eliminazione del video: ' . $e->getMessage()
    ]);
}

$stmt->close();
$conn->close();
?>
