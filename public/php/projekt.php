<?php
require_once 'ini.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($POST['type'])) {
        echo json_encode(["status" => "error", "message" => "Chybí typ operace"]);
        exit;
    }

    $type = $POST['type'];

    // ====== LOGIKA PRO ODSTRANĚNÍ ======
    if ($type === "remove") {
        if (isset($POST['id'])) {
            $id = $POST['id']; // PDO automaticky ošetřuje vstupy, není potřeba mysqli_real_escape_string
            
            // Kontrola počtu záznamů v tabulce
            try {
                $countSql = "SELECT COUNT(*) as count FROM project";
                $stmt = $conn->query($countSql);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row['count'] <= 1) {
                    echo json_encode(["status" => "cannot-delete-last"]);
                    exit;
                }
            } catch (PDOException $e) {
                echo json_encode(["status" => "db-error", "message" => $e->getMessage()]);
                exit;
            }

            // Odstranit záznam
            try {
                $sql = "DELETE FROM project WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                echo json_encode(["status" => "success"]);
            } catch (PDOException $e) {
                echo json_encode(["status" => "db-error", "message" => $e->getMessage()]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Chybí ID pro odstranění"]);
        }
    }

    // ====== LOGIKA PRO AKTUALIZACI ======
    if ($type === "update") {
        if (isset($POST['id'], $POST['name'], $POST['desc'], $POST['text'])) {
            $id = $POST['id'];
            $name = $POST['name'];
            $desc = $POST['desc'];
            $text = $POST['text'];

            try {
                // Provedení SQL dotazu pro aktualizaci
                $sql = "UPDATE project SET name = :name, `desc` = :desc, text = :text WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':desc', $desc, PDO::PARAM_STR);
                $stmt->bindParam(':text', $text, PDO::PARAM_STR);
                $stmt->execute();

                echo json_encode(["status" => "success"]);
            } catch (PDOException $e) {
                echo json_encode(["status" => "db-error", "message" => $e->getMessage()]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Chybí některé údaje pro aktualizaci"]);
        }
    }
}
?>
