<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './ini.php'; // Předpokládám, že tento soubor obsahuje připojení k databázi pomocí PDO
session_start();

// Funkce pro odhlášení uživatele
function logout() {
    global $conn;

    // Pokud chcete odstranit token z databáze, použijeme PDO
    if (isset($_SESSION['token'])) {
        try {
            // Předpokládáme, že máte tabulku 'tokens' a chcete odstranit token uživatele
            $sql = "DELETE FROM tokens WHERE token = :token";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':token', $_SESSION['token'], PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            // Pokud dojde k chybě při dotazu
            echo "Chyba při odstraňování tokenu: " . $e->getMessage();
        }
    }

    // Zrušit konkrétní token ze session
    unset($_SESSION['token']);

    // Zrušit všechny session proměnné (volitelné)
    session_unset();

    // Zničit celou session
    session_destroy();

    // Přesměrovat na přihlašovací stránku (nebo jinam)
    header("Location: /admin"); // Nahraďte URL podle potřeby
    exit;
}

// Volání funkce logout
logout();
?>
