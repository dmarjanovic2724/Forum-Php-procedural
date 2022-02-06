<?php
require_once "conn.php";
require_once "commponents/head.php";

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

   

    //userName 
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
      
    }

    //password
    if(empty($_POST['password'])){
        $validated=true;
        $message="enter a password";
    }elseif(strpos($_POST["password"], ' ') !== false){
        $validated=true;
        $message = "password can't contain whitespace";
    }elseif(strlen($_POST['password']) < 5 || strlen($_POST['password']) > 25){
        $validated=true;
        $message = "password must be between 5 and 25 characters";
    }else {
        $password=$conn->real_escape_string($_POST['password']);     
      
    }

    //re-password
    if(empty($_POST['rePassword'])){
        $validated=true;
        $message="enter a retype password";
    }else{
        $rePassword=$_POST['rePassword'];
    }
    //match passwords
    if($password==$rePassword){
        $validated=false;
    }else{        
        $message="The password don't match";       
    }

    if($validated==false){
        $password=md5($password);
        $queryCheck="SELECT * FROM users where username = '$userName'";
        $result=$conn->query($queryCheck);
        if($result->num_rows != 0){
            $message="The username is allready taken";
        }else{
            $query="INSERT into users ( username, password) VALUE ( '$userName', '$password')";
            if($conn->query($query)){
                $message="You have successfully registered, Please log in!";                
            }else{
                $message="an error has occurred, " . $conn->error . "</p>";
            }

        }


        

       
      
    }
}
?>

<div class="position-center">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <fieldset class="catfield">
            <legend class="text-center"><h1 >Registration</h1></legend>
            <label >User Name</label>
            <input type="text" name="userName">
            <label>Password</label>
            <input type="password" name="password">
            <label>Retype password</label>
            <input type="password" name="rePassword">
            <input type="submit" value="Sing up"></input>
            <p class="errors"><?php echo $message ?></p>
        </fieldset>
    </form>
</div>

<?php
include_once "commponents/footer.php";
?>