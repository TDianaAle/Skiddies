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
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Content-Type: application/json');

    // Preflight
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
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

    // Query per ottenere i video nella playlist dell'utente
    $stmt = $conn->prepare("
        SELECT 
            p.id as playlist_item_id,
            v.id as video_id,
            v.title,
            v.category,
            v.file_path,
            v.uploaded_at,
            t.name as tutor_name,
            (SELECT COUNT(*) FROM likes l WHERE l.content_id = v.id) AS likes
        FROM playlist p
        JOIN videos v ON p.video_id = v.id
        JOIN tutors t ON v.tutor_id = t.id
        WHERE p.user_id = ?
        ORDER BY p.date DESC
    ");
    $stmt->bind_param("s", $userIdStr);
    $stmt->execute();
    $result = $stmt->get_result();

    $videos = [];
    while ($row = $result->fetch_assoc()) {
        $videos[] = [
            'id' => $row['video_id'],
            'playlist_item_id' => $row['playlist_item_id'],
            'title' => $row['title'],
            'category' => $row['category'],
            'file_path' => $row['file_path'],
            'uploaded_at' => $row['uploaded_at'],
            'tutor_name' => $row['tutor_name'],
            'likes' => (int)$row['likes']
        ];
    }
    $stmt->close();

    echo json_encode([
        'success' => true,
        'videos' => $videos
    ]);
} catch (Exception $e) {
    logError("Eccezione: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Errore del server: ' . $e->getMessage()]);
}
