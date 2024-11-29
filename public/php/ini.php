<?php
require_once 'cors.php';

//db connect
$db_host = "db.dw214.webglobe.com";
$db_user = "designjj_test_eu";
$db_password = "Radegast12*";
$db_name = "designjj_test_eu";
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (!$conn) {
    die("Připojení se nezdařilo. " . mysqli_connect_error());
}