<?php

require_once "conn.php";
session_start();
if (!isset($_SESSION['userId'])) {
    $id = $_GET['id'];
    $_SESSION['userId'] = $id;
}
$id = $_SESSION['userId'];
if(isset($_SESSION['cat'])){
    unset($_SESSION['cat']);
}
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
    echo "<ul class='list'>";
    foreach ($result as $cat) {
        $cat_id = $cat['id'];      
        echo "<li><a href='topics.php?cat=$cat_id'>" . $cat['cat_name'] . "</a></li>";       
    }  
    echo "</ul>";
}

//topic should be able to change the owner to someone else

 if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $topicId=$_POST['topic'];
    $user=$_POST['user'];

    $query="UPDATE topics SET user_id='$user' WHERE id = '$topicId'";
    $result=$conn->query($query);
    if($result)
    {
                $message = "the topic ... has change the owner to ...";
                header("refresh:2;url=categories.php");
    }
 }


$query="SELECT id, topic_name FROM topics WHERE user_id = $id";
$resultTopics=$conn->query($query);
if($resultTopics->num_rows !=0){

    echo "<form action='#' method='POST'>
        <div>
            <select name='topic'>
            <option>list of my topics</option>";
    foreach ($resultTopics as $topic) {
            
        echo"<option value=".$topic['id'].">". $topic['topic_name'] ."</option>";
          
    }  
        echo "</select>
            </div>";       
}

//query users list

$query = "SELECT * FROM users";
$result = $conn->query($query);
if ($result->num_rows != 0 && $resultTopics->num_rows !=0) {
    echo "
        <div>
            <select name='user'>
            <option>list of users</option>";
    foreach ($result as $user) {
        
           if($user['id'] !=$id)
           {
            echo"<option value=".$user['id'].">".$user['username'] ."</option>";  
           } 
              
    }  
        echo "</select>
            </div>
            <input type='submit' name='send' value='send'>
            <p class='errors'>".$message."</p> 
        </form>";
}else{
   echo"<h3>You dont have any topic yet...</h3>";
}

include_once "commponents/footer.php";
?>




