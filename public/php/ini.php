<?php

require_once 'cors.php';  // Předpokládám, že tento soubor se stará o CORS hlavičky
// Parametry připojení k databázi

$db_host = "md417.wedos.net";

$db_user = "w365713_acv";

$db_password = "Montaze2014*";

$db_name = "d365713_acv";

try {
    // Vytvoření připojení k databázi pomocí PDO
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    // Nastavení režimu chyb (použití výjimek)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Volitelné: Zajištění, že připojení používá správnou sadu znaků
    $conn->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
  // Pokud připojení selže, vyvoláme chybu a ukončíme skript
    die("Připojení se nezdařilo: " . $e->getMessage());
}
?>