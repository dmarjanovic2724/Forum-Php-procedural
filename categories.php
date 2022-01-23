<?php

require_once "conn.php";
session_start();
if (!isset($_SESSION['userId'])) {
    $id = $_GET['id'];
    $_SESSION['userId'] = $id;
}
$id = $_SESSION['userId'];
//query
$query = "SELECT username FROM users
    WHERE id = $id";
$result = $conn->query($query);
$user = $result->fetch_assoc();
$user = $user['username'];
$_SESSION['userName'] = $user;
require_once "commponents/head.php";

$query = "SELECT * FROM categories";
$result = $conn->query($query);

if ($result->num_rows != 0) {
    echo "<table class='table'>
            <tr><th>categories:</th></tr>";
    foreach ($result as $cat) {
        $cat_id = $cat['id'];
        echo "<tr>";
        echo "<td><a href='topics.php?cat=$cat_id'>" . $cat['cat_name'] . "</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}
include_once "commponents/footer.php";
?>



