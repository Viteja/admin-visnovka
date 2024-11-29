<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'ini.php';
session_start();

// Nastavení časového limitu pro session (10 sekund = 10 sekund)
$session_lifetime = 10; 

// Kontrola, jestli session ještě není stará 10 sekund
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_lifetime)) {
    // Pokud uplynulo více než 10 sekund, zničíme session
    session_unset(); // Vymazání všech proměnných v session
    session_destroy(); // Zničení session
    setcookie(session_name(), '', time() - 3600, '/'); // Odstranění session cookie
    header("Location: /admin");
    exit();
}

// Aktualizace poslední aktivity na aktuální čas
$_SESSION['last_activity'] = time();

header('Content-Type: application/json');

// Příklad: Token uložený v session
$tokenFromSession = $_SESSION['token'] ?? null;

// Příklad: Token uložený v databázi
$sql = "SELECT token FROM tokens ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $tokenFromDatabase = $row['token'];
} else {
    $tokenFromDatabase = null;
}

// Vrácení dat pro JavaScript
echo json_encode([
    'sessionToken' => $tokenFromSession,
    'databaseToken' => $tokenFromDatabase
]);
?>
