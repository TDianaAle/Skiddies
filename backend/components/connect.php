<?php
//generato dal servizio di hosting
$servername = "fdb1034.awardspace.net";
$username   = "4704212_skiddies";
$password   = "Diana.1994!!!";
$dbname     = "4704212_skiddies";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die('Errore di connessione: ' . mysqli_connect_error());
}

// Funzione per generare ID unici
function unique_id() {
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $rand = array();
    $length = strlen($str) - 1;
    for ($i = 0; $i < 20; $i++) {
        $n = mt_rand(0, $length);
        $rand[] = $str[$n]; 
    }
    return implode($rand);
}
