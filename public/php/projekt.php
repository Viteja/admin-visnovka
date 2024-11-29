<?php
require_once 'ini.php';

$POST = json_decode(file_get_contents('php://input'), true);

$type = mysqli_real_escape_string($conn, $POST['type']);
$name = mysqli_real_escape_string($conn, $POST['name']);
$datestart = mysqli_real_escape_string($conn, $POST['desc']);
$dateend = mysqli_real_escape_string($conn, $POST['text']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type ==="create"){
        $sql = "SELECT * FROM project WHERE name = '$name'";
        $run = mysqli_query($conn, $sql);
        if (mysqli_num_rows($run) === 0)
        {
            $query = "INSERT INTO project (name, datestart, dateend, description) VALUES ('$name', '$desc', '$text')";
            $run = mysqli_query($conn, $query);
            if ($run) {
                $data = array("status" => "success");
                echo json_encode($data);
            } else {
                $data = array("status" => "db-error");
                echo json_encode($data);
                die(mysqli_error($conn));
            }
        }
        else{
            $data = array("status" => "name-exist");
            echo json_encode($data);
        }
    }

    if ($type === "remove") {
        // Zjistit počet záznamů v tabulce
        $countSql = "SELECT COUNT(*) as count FROM project";
        $countResult = mysqli_query($conn, $countSql);
        $row = mysqli_fetch_assoc($countResult);
    
        if ($row['count'] <= 1) {
            // Pokud je v tabulce jen jeden záznam, nepovolit odstranění
            $data = array("status" => "cannot-delete-last");
            echo json_encode($data);
        } else {
            // Odstranit záznam, pokud je více než jeden
            $sql = "DELETE FROM project WHERE id = '$name'";
            $run = mysqli_query($conn, $sql);
            if ($run) {
                $data = array("status" => "success");
                echo json_encode($data);
            } else {
                $data = array("status" => "db-error");
                echo json_encode($data);
                die(mysqli_error($conn));
            }
        }
    }
    

    if ($type === "update") {
        // Získání dat z POST
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $desc = mysqli_real_escape_string($conn, $_POST['desc']);
        $text = mysqli_real_escape_string($conn, $_POST['text']);
        
        // Aktualizace záznamu v databázi
        $sql = "UPDATE project SET name = '$name', description = '$desc', text = '$text' WHERE id = '$id'";
        $run = mysqli_query($conn, $sql);
        
        if ($run) {
            $data = array("status" => "success");
            echo json_encode($data);
        } else {
            $data = array("status" => "db-error");
            echo json_encode($data);
            die(mysqli_error($conn));
        }
    }
    

}