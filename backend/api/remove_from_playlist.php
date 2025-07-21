<?php
// Abilita la visualizzazione degli errori per il debug (disabilita in produzione)
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Funzione per registrare gli errori in un file di log
function logError($message) {
    file_put_contents(__DIR__ . '/../logs/playlist_errors.log', date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

try {
    session_start();
    require_once __DIR__ . '/../components/connect.php';

    // CORS
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
        echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
        exit;
    }

    // Verifica autenticazione
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['user']['id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Non autenticato']);
        exit;
    }

    // Ottieni l'ID dell'utente come intero
    $userId = intval($_SESSION['user_id'] ?? $_SESSION['user']['id']);

    // Ottieni l'ID del video dal corpo della richiesta e converti in intero
    $data = json_decode(file_get_contents('php://input'), true);
    $videoId = isset($data['video_id']) ? intval($data['video_id']) : 0;

    if ($videoId === 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'ID video mancante']);
        exit;
    }

    // Rimuovi il video dalla playlist
    $stmt = $conn->prepare("DELETE FROM playlist WHERE user_id = ? AND video_id = ?");
    $stmt->bind_param("ii", $userId, $videoId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Video rimosso dalla playlist']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Il video non è nella tua playlist']);
        }
    } else {
        logError("Errore SQL: " . $stmt->error);
        echo json_encode(['success' => false, 'error' => 'Errore nella rimozione del video dalla playlist']);
    }

    $stmt->close();
} catch (Exception $e) {
    logError("Eccezione: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Errore del server: ' . $e->getMessage()]);
}
