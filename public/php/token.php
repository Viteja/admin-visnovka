<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'ini.php'; // Předpokládám, že tento soubor obsahuje připojení k databázi pomocí PDO

session_start();

// Generování tokenu
$token = bin2hex(random_bytes(16));

var_dump($token);

// Uložení tokenu do databáze (používáme PDO)
try {
    // Příprava SQL dotazu pro vložení tokenu
    $sql = "INSERT INTO tokens (token) VALUES (:token)";
    $stmt = $conn->prepare($sql);
    
    // Navázání hodnoty tokenu
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);

    // Spuštění dotazu
    $stmt->execute();
} catch (PDOException $e) {
    // Pokud dojde k chybě při vykonávání dotazu
    echo 'Chyba při ukládání tokenu: ' . $e->getMessage();
}

// Uložení tokenu do session
$_SESSION['token'] = $token;
?>
