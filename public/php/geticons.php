<?php

 

$allowedOrigins = ['https://www.acvisnovka.cz'];

 

if (in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {

    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);

}

 

// Povolit specifikované hlavičky (pokud je to potřeba)

header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

 

// Povolit specifikované metody

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

 

// Povolit odesílání cookies a autentizačních informací

header("Access-Control-Allow-Credentials: true");

 

// Povolit pre-flight cache na 1 den

header("Access-Control-Max-Age: 86400");

 

// Pokud je požadavek typu OPTIONS (pre-flight), ukončit script, protože server již odpověděl

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    exit;

}

 

require_once './ini.php'; // Předpokládám, že to obsahuje správné připojení k databázi

error_reporting(E_ALL);

ini_set('display_errors', 1);

 

// Ověření, zda je požadavek typu POST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        // Použijeme již existující připojení (PDO), které je v $conn

        // Provádění dotazu na získání všech projektů

        $sql = "SELECT * FROM icons";

        $stmt = $conn->query($sql); // Spustí SQL dotaz

       

        // Načítání všech řádků do pole

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC); // Načteme všechny výsledky jako asociativní pole

 

        // Odeslání dat jako JSON

        echo json_encode($projects);  // Vytvoření JSON pole

 

    } catch (PDOException $e) {

        // Pokud dojde k chybě, vypíše ji

        http_response_code(404); // Chyba při dotazu

        die("Chyba při připojení k databázi: " . $e->getMessage()); // Zobrazení chybového hlášení

    }

}

?>