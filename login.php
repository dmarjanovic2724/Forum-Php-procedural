<?php
session_start();
require_once "conn.php";
require_once "commponents/head.php";

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    //validate
    if (empty($_POST['userName'])) {
        $validated = true;
        $message = "enter a username";
    } elseif (strpos($_POST["userName"], ' ') !== false) {
        $validated = true;
        $message = "username can't contain whitespace";
    } elseif (strlen($_POST['userName']) < 5 || strlen($_POST['userName']) > 30) {
        $validated = true;
        $message = "username must be between 5 and 30 characters";
    } else {
        $userName=$conn->real_escape_string($_POST['userName']);       
        $password=$conn->real_escape_string($_POST['password']);
        $validated = false;
    }

    if($validated==false){
        $query="SELECT * FROM users WHERE username = '$userName'";
        $result=$conn->query($query);
        if($result->num_rows !=0){
            $row=$result->fetch_assoc();
            $dbPass=$row['password'];
            if($dbPass == $password){
                $_SESSION['userId'] = $row['id'];
                $_SESSION['userName']= $row['username'];

                header('Location: categories.php');
                
            }else{
                $message="wrong password";
            }
        }else{
            $message= "username dont exists";
        }
    }
}
?>
<div class="position-center">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <fieldset class="catfield">
            <legend class="text-center"><h1>Log in</h1></legend>
            <label >User Name</label>
            <input type="text" name="userName">
            <label>Password</label>
            <input type="password" name="password">
            <input type="submit" value="Log in"></input>
            <p class="errors"><?php echo $message ?></p>
        </fieldset>
    </form>
</div>


<?php
include_once "commponents/footer.php";
?>