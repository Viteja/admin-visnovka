<?php
require_once '../ini.php';

$POST = json_decode(file_get_contents('php://input'), true);

$type = mysqli_real_escape_string($conn, $POST['type']);
$name = mysqli_real_escape_string($conn, $POST['name']);
$surname = mysqli_real_escape_string($conn, $POST['surname']);
$job = mysqli_real_escape_string($conn, $POST['job']);
$email = mysqli_real_escape_string($conn, $POST['email']);
$adress = mysqli_real_escape_string($conn, $POST['adress']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type ==="create"){
        $sql = "SELECT * FROM employee WHERE name = '$name'";
        $run = mysqli_query($conn, $sql);
        if (mysqli_num_rows($run) === 0)
        {
            $query = "INSERT INTO employee (name, surname, job, email, adress) VALUES ('$name', '$surname', '$job', '$email', '$adress')";
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
        $sql = "DELETE FROM employee WHERE id = '$name'";
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