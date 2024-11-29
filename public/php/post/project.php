<?php
require_once '../ini.php';

$POST = json_decode(file_get_contents('php://input'), true);

$type = mysqli_real_escape_string($conn, $POST['type']);
$name = mysqli_real_escape_string($conn, $POST['name']);
$datestart = mysqli_real_escape_string($conn, $POST['datestart']);
$dateend = mysqli_real_escape_string($conn, $POST['dateend']);
$description = mysqli_real_escape_string($conn, $POST['description']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type ==="create"){
        $sql = "SELECT * FROM project WHERE name = '$name'";
        $run = mysqli_query($conn, $sql);
        if (mysqli_num_rows($run) === 0)
        {
            $query = "INSERT INTO project (name, datestart, dateend, description) VALUES ('$name', '$datestart', '$dateend', '$description')";
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

    if ($type ==="remove")
    {
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

    if ($type ==="update")
    {
        $projectId = mysqli_real_escape_string($conn, $POST['projectId']);
        $taskId = mysqli_real_escape_string($conn, $POST['taskId']);
        
        $sql = "UPDATE project SET taskId = '$taskId' WHERE id = '$projectId'";
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