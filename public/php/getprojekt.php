<?php
require_once 'ini.php'; // Předpokládám, že to obsahuje správné připojení k databázi
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ověření, zda je požadavek typu POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Provádění dotazu na získání všech projektů
    $sql = "SELECT * FROM project";
    $run = mysqli_query($conn, $sql);

    // Pokud se dotaz nezdaří, vrátí se chybová odpověď
    if (!$run) {
        http_response_code(404);  // Chyba při dotazu
        die(mysqli_error($conn));  // Zobrazení chybového hlášení
    }

    // Načítání všech řádků do pole
    $projects = [];
    while ($row = mysqli_fetch_assoc($run)) {
        $projects[] = $row;  // Přidání každého projektu do pole
    }

    // Odeslání dat jako JSON
    echo json_encode($projects);  // Vytvoření JSON pole

    // Zavření připojení k databázi
    $conn->close();
}
?>
