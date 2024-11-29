<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'ini.php';
session_start();

// Funkce pro odhlášení uživatele
function logout() {
    // Zrušit konkrétní token ze session
    unset($_SESSION['token']);

    // Zrušit všechny session proměnné (volitelné)
    session_unset();

    // Zničit celou session
    session_destroy();

    // Přesměrovat na přihlašovací stránku (nebo jinam)
    header("Location: /admin"); // Nahraď URL cílové stránky
    exit;
}

// Volání funkce logout
logout();
?>
