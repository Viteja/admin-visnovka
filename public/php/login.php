<?php
require_once 'ini.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM login";
    $run = mysqli_query($conn, $sql);
    if (!$run) {
        http_response_code(404);
        die(mysqli_error($conn));
    }
    if (!$id) echo '[';
    for ($i = 0; $i < mysqli_num_rows($run); $i++) {
        echo ($i > 0 ? ',' : '') . json_encode(mysqli_fetch_object($run));
    }
    if (!$id) echo ']';
}
$conn->close();
?>