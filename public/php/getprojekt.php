<?php
$allowedOrigins = ['https://www.acvisnovka.cz'];
if (in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {

    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);

}

header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}
require_once './ini.php'; // Předpokládám, že to obsahuje správné připojení k databázi

error_reporting(E_ALL);

ini_set('display_errors', 1);
// Ověření, zda je požadavek typu POST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $sql = "SELECT * FROM project";
        $stmt = $conn->query($sql); // Spustí SQL dotaz        

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC); // Načteme všechny výsledky jako asociativní pole
        echo json_encode($projects);  // Vytvoření JSON pole
    } catch (PDOException $e) {
        // Pokud dojde k chybě, vypíše ji
        http_response_code(404); // Chyba při dotazu
        die("Chyba při připojení k databázi: " . $e->getMessage()); // Zobrazení chybového hlášení
    }
}

?>