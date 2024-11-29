<?php
require_once '../ini.php';

$POST = json_decode(file_get_contents('php://input'), true);

$name = mysqli_real_escape_string($conn, $POST['name']);
$type = mysqli_real_escape_string($conn, $POST['type']);
$name = mysqli_real_escape_string($conn, $POST['name']);
$date = mysqli_real_escape_string($conn, $POST['date']);
$status = mysqli_real_escape_string($conn, $POST['status']);
$description = mysqli_real_escape_string($conn, $POST['description']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type ==="create"){
        $sql = "SELECT * FROM task WHERE name = '$name'";
        $run = mysqli_query($conn, $sql);
        if (mysqli_num_rows($run) === 0)
        {
            $query = "INSERT INTO task (name, date, status, description) VALUES ('$name', '$date', '$status', '$description')";
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
        $sql = "DELETE FROM task WHERE id = '$name'";
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
        $taskId = mysqli_real_escape_string($conn, $POST['taskId']);
        $employeeId = mysqli_real_escape_string($conn, $POST['employeeId']);
        
        $sql = "UPDATE task SET employeeId = '$employeeId' WHERE id = '$taskId'";
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