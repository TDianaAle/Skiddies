<?php
// Abilita la visualizzazione degli errori per il debug
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

    // Ottieni l'ID dell'utente
    $userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'];
    // Converti in stringa per compatibilità con la tabella
    $userIdStr = (string)$userId;

    // Ottieni l'ID del video dal corpo della richiesta
    $data = json_decode(file_get_contents('php://input'), true);
    $videoId = $data['video_id'] ?? null;
    // Converti in stringa per compatibilità con la tabella
    $videoIdStr = (string)$videoId;

    if (!$videoId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'ID video mancante']);
        exit;
    }

    // Verifica che il video esista
    $checkVideo = $conn->prepare("SELECT id, title, file_path FROM videos WHERE id = ?");
    $checkVideo->bind_param("i", $videoId);
    $checkVideo->execute();
    $result = $checkVideo->get_result();
    $video = $result->fetch_assoc();

    if (!$video) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Video non trovato']);
        exit;
    }
    $checkVideo->close();

    // Genera un ID univoco per il record
    $recordId = unique_id();
    $playlistId = unique_id();
    $videoTitle = $video['title'];
    $videoPath = $video['file_path'];
    $currentDate = date('Y-m-d');

    // Verifica se il video è già nella playlist
    $checkPlaylist = $conn->prepare("SELECT id FROM playlist WHERE user_id = ? AND video_id = ?");
    $checkPlaylist->bind_param("ss", $userIdStr, $videoIdStr);
    $checkPlaylist->execute();
    $checkPlaylist->store_result();
    $alreadyInPlaylist = $checkPlaylist->num_rows > 0;
    $checkPlaylist->close();

    if ($alreadyInPlaylist) {
        echo json_encode(['success' => true, 'message' => 'Il video è già nella tua playlist']);
        exit;
    }

    // Aggiungi il video alla playlist
    $stmt = $conn->prepare("
        INSERT INTO playlist 
        (id, user_id, video_id, tutor_id, title, description, date, status, thumb, video, playlist_id) 
        VALUES 
        (?, ?, ?, '0', ?, 'Video aggiunto alla playlist', ?, 'active', '', ?, ?)
    ");

    $stmt->bind_param("sssssss", $recordId, $userIdStr, $videoIdStr, $videoTitle, $currentDate, $videoPath, $playlistId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Video aggiunto alla playlist']);
    } else {
        logError("Errore SQL: " . $stmt->error);
        echo json_encode(['success' => false, 'error' => 'Errore nell\'aggiunta del video alla playlist: ' . $stmt->error]);
    }
    $stmt->close();
} catch (Exception $e) {
    logError("Eccezione: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Errore del server: ' . $e->getMessage()]);
}
