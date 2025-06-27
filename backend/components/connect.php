<?php

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "skiddies_db";

// Connessione al database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Controllo della connessione
if (!$conn) {
    die('Errore di connessione: ' . mysqli_connect_error());
}

// Funzione per generare un ID unico
function unique_id() {
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $rand = array();
    $length = strlen($str) - 1;
    for ($i = 0; $i < 20; $i++) {
        $n = mt_rand(0, $length);
        $rand[] = $str[$n]; 
    }
    return implode($rand);  // Ritorna l'id come stringa
}
?>
