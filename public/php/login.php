<?php
require_once 'ini.php'; // Předpokládám, že to obsahuje připojení k databázi pomocí PDO

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // SQL dotaz pro získání všech záznamů z tabulky 'login'
        $sql = "SELECT * FROM login";
        $stmt = $conn->query($sql); // Spustí SQL dotaz

        $logins = $stmt->fetchAll(PDO::FETCH_ASSOC); // Načteme všechny výsledky jako asociativní pole

        // Pokud není žádný výsledek, vrátíme prázdné pole
        if (empty($logins)) {
            echo '[]';
        } else {
            // Vrátíme odpověď jako JSON
            echo json_encode($logins, JSON_PRETTY_PRINT);
        }
    } catch (PDOException $e) {
        // Chyba při dotazu na databázi
        http_response_code(404);
        echo json_encode(["error" => "Chyba při zpracování požadavku: " . $e->getMessage()]);
    }
}

$conn = null; // Zavření připojení k databázi
?>
