<?php

require_once "conn.php";
require_once "commponents/head.php";
session_start();
if(!isset($_SESSION['userId'])){
    $id = $_GET['id'];
    $_SESSION['userId'] = $id;
}
$id=$_SESSION['userId'];

$query = "SELECT username FROM users
    WHERE id = $id";
$result = $conn->query($query);
$user = $result->fetch_assoc();
$user = $user['username'];
$_SESSION['userName'] = $user;
?>
<h4>Welcome <?php echo $user; ?></h4>
<?php

$query = "SELECT * FROM categories";
$result = $conn->query($query);

if ($result->num_rows != 0) {
    echo "<table>
            <th>categories:</th>";
    foreach ($result as $cat) {
        $cat_id = $cat['id'];
        echo "<tr>";
        echo "<td><a href='topics.php?cat=$cat_id'>" . $cat['cat_name'] . "</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}
//logout test
if (isset($_GET['logOff'])) {
    if (isset($_SESSION['userId'])) {

        session_destroy();
        header('Location:index.php');
    }
}

?>

<div>
    <p>
        <a href="?logOff">LogOFF</a>
    </p>
</div>