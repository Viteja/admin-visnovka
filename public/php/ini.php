<?php
require_once 'cors.php';

//db connect
$db_host = "md417.wedos.net";
$db_user = "a365713_acv";
$db_password = "KTp3Sk6q";
$db_name = "d365713_acv";
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (!$conn) {
    die("Připojení se nezdařilo. " . mysqli_connect_error());
}