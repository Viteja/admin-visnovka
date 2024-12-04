<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'ini.php'; // Předpokládám, že tento soubor obsahuje připojení k databázi pomocí PDO
session_start();

// Nastavení časového limitu pro session (3600 sekund = 1 hodina)
$session_lifetime = 3600; 

// Kontrola, jestli session ještě není stará (více než 1 hodina)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_lifetime)) {
    // Pokud uplynulo více než 1 hodina, zničíme session
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

// Příklad: Token uložený v databázi (používáme PDO)
try {
    // SQL dotaz pro získání posledního tokenu z databáze
    $sql = "SELECT token FROM tokens ORDER BY id DESC LIMIT 1";
    $stmt = $conn->query($sql); // Spustíme SQL dotaz
    $row = $stmt->fetch(PDO::FETCH_ASSOC); // Načteme poslední záznam

    // Pokud byl token nalezen, uložíme ho, jinak nastavíme výchozí hodnotu
    $tokenFromDatabase = $row ? $row['token'] : "ses pomalej";
} catch (PDOException $e) {
    // Pokud dojde k chybě při vykonávání dotazu
    $tokenFromDatabase = "Chyba při zpracování požadavku: " . $e->getMessage();
}

// Vrácení dat pro JavaScript
echo json_encode([
    'sessionToken' => $tokenFromSession,
    'databaseToken' => $tokenFromDatabase
]);

?>
