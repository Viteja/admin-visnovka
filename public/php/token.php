<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'ini.php';


session_start();

// Generování tokenu
$token = bin2hex(random_bytes(16));

var_dump($token);

// Uložení tokenu do databáze (příklad s PDO)
try {
    $stmt = $conn->prepare("INSERT INTO tokens (token) VALUES (?)");
    $stmt->bind_param("s", $token);
    $stmt->execute();
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Uložení tokenu do session
$_SESSION['token'] = $token;
?>