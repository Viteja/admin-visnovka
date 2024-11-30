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
            $id = mysqli_real_escape_string($conn, $POST['id']);
            
            // Kontrola počtu záznamů v tabulce
            $countSql = "SELECT COUNT(*) as count FROM icons";
            $countResult = mysqli_query($conn, $countSql);
            
            if ($countResult) {
                $row = mysqli_fetch_assoc($countResult);
                if ($row['count'] <= 1) {
                    echo json_encode(["status" => "cannot-delete-last"]);
                    exit;
                }
            }

            // Odstranit záznam
            $sql = "DELETE FROM icons WHERE id = '$id'";
            $run = mysqli_query($conn, $sql);

            if ($run) {
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "db-error", "message" => mysqli_error($conn)]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Chybí ID pro odstranění"]);
        }
    }

    // ====== LOGIKA PRO AKTUALIZACI ======
    if ($type === "update") {
            
            $id = mysqli_real_escape_string($conn, $POST['id']);
     
            $text = mysqli_real_escape_string($conn, $POST['text']);

            // Provedení SQL dotazu pro aktualizaci
            $sql = "UPDATE icons SET text = '$text' WHERE id = $id";
            $run = mysqli_query($conn, $sql);

            if ($run) {
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "db-error", "message" => mysqli_error($conn)]);
            }
        }
    }
?>
