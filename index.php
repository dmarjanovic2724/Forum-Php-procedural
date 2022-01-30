<?php
require_once "conn.php";
require_once "commponents/head.php";

$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //validate
    if (empty($_POST['userName'])) {
        $validated = true;
        $message = "enter a userName";
    } elseif (strpos($_POST["userName"], ' ') !== false) {
        $validated = true;
        $message = "username can't contain whitespace";
    } elseif (strlen($_POST['userName']) < 5 || strlen($_POST['userName']) > 30) {
        $validated = true;
        $message = "username must be between 5 and 30 characters";
    } else {
        $userName = $conn->real_escape_string($_POST['userName']);
        $validated = false;
    }
    //check validate

    if ($validated == false) {

        $query = "SELECT * FROM users WHERE username = '$userName'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $message = "Username already existed";
        } else {
            $query = "INSERT INTO users(username)
                VALUES ('$userName')";
            $result = $conn->query($query);

            if ($result) {
                $message = "New user is created successfully";
                header("refresh:3;url=index.php");
            } else {
                $message = "an error has occurred";
                header("refresh:3;url=index.php");
            }
        }
    }
}

?>

<header>
    <h1>Welcome to forum</h1>
</header>

<!-- users list -->
<section>
    <div>
        <?php
        $query = "SELECT * FROM users";
        $result = $conn->query($query);
        if ($result->num_rows != 0) {
            echo
            "<div class='center'>
            <table cellspacing='0' class='tableUser' >
                    <tr><th>Chose a user name:</th></tr>";
            foreach ($result as $user) {
                $id = $user['id'];

                echo "<tr>";
                echo "<td><a href='categories.php?id=$id'>" . $user['username'] . "</a></td>";
                echo "</tr>";
            }
            echo
            "</table>
            </div>";
        }
        

        ?>
    </div>
</section>

<div class="form-post">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <fieldset>
            <legend>Create a new user</legend>
            <input type="text" name="userName" placeholder="Create username">
            <input type="submit" value="Create">
            <p class="errors"><?php echo $message ?></p>
        </fieldset>
    </form>
</div>
<div id="createCat">
    <a href="createCategory.php"><button type="button"> create new category</button></a>
</div>