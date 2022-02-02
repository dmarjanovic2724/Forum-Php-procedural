<?php

require_once "conn.php";
session_start();

if(empty($_SESSION['userId'])){
    header("Location: login.php");
}
$userId=$_SESSION['userId'];

require_once "commponents/head.php";
$query = "SELECT * FROM categories";
$result = $conn->query($query);

if ($result->num_rows != 0) {
    echo "<div class='container'>
    <div class='item categories'>    
    <ul class='list'>
    <h3>Choose categories:</h3><hr>";
    foreach ($result as $cat) {
        $cat_id = $cat['id'];            
        echo "<li><a href='topics.php?cat=$cat_id'>" . $cat['cat_name'] . "</a></li>";       
    }  
    echo "</ul></div>";
}

//change the owner to someone else

 if ($_SERVER["REQUEST_METHOD"] == "POST"  && isset($_POST['topic'])){
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


 $query="SELECT id, topic_name FROM topics WHERE user_id = $userId";
 $resultTopics=$conn->query($query);
 if($resultTopics->num_rows !=0){
 
     echo "<div class='item changeOwner'>
     <form  action='#' method='POST'>
     <fieldset class='catfield'>
     <legend>change topic to someone else</legend>
         <label>Choose topic:</label>
             <select name='topic'>
             <option disabled>list of my topics</option>";
     foreach ($resultTopics as $topic) {
             
         echo"<option value=".$topic['id'].">". $topic['topic_name'] ."</option>";
           
     }  
         echo "</select>";       
 }

//query users list

$query = "SELECT * FROM users";
$result = $conn->query($query);
if ($result->num_rows != 0 && $resultTopics->num_rows !=0) {
    echo "       
         <label>Choose user:</label>
            <select name='user'>
            <option disabled>list of users</option>";
    foreach ($result as $user) {
        
           if($user['id'] !=$id)
           {
            echo"<option  value=".$user['id'].">".$user['username'] ."</option>";  
           } 
              
    }  
        echo "</select>            
            <input type='submit' name='send' value='Send'>
            <p class='errors'>".$message."</p>
            </fieldset> 
        </form>
        </div>";
}else{
   echo"<h3>You dont have any topic yet...</h3>";
}

// search

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {




    $search=$conn->real_escape_string($_GET['search']);
    $filter=$_GET['filter'];
    $queryPosts="SELECT posts.id, posts.topic_id as 'topic_id', posts.post_text, posts.post_date as 'date', users.username as 'owner' FROM users INNER JOIN posts ON posts.user_id = users.id WHERE post_text LIKE '%$search%'";
    $resultPosts=$conn->query($queryPosts);

    $queryTopics="SELECT topics.id as 'id', topics.topic_name as 'name', topics.topic_date as 'date', users.username as 'owner' FROM users INNER JOIN topics ON topics.user_id = users.id WHERE topic_name LIKE '%$search%'";
    $resultTopics=$conn->query($queryTopics);

   


        if(isset($_GET['search']) && ($resultPosts->num_rows !=0 || $resultTopics->num_rows !=0)){
            echo "
                     <table class='table searchTable item'>
                     <tr><th> name:</th><th>owner</th><th>date:</th></tr>";
        if($filter =='topics'){
              foreach($resultTopics as $topic){
                  $idTopic=$topic['id'];
                  echo "<tr><td><a href='posts.php?id=$idTopic'>
                        {$topic['name']}
                        </a></td>
                        <td>{$topic['owner']}</td>
                        <td>{$topic['date']}</td></tr>";                 
              }
        }elseif($filter =='posts'){
            foreach($resultPosts as $post){
                $idPost=$post['topic_id'];
                echo "<tr><td><a href='posts.php?id=$idPost'>
                {$post['post_text']}
                     </a></td>
                <td>{$post['owner']}</td>
                <td>{$post['date']}</td></tr>";
        }
        echo "</table>";
                
            }
        }
        if(isset($_GET['search']) && ($resultPosts->num_rows ==0 || $resultTopics->num_rows ==0)){
            echo "<p class='results'>
              no found results
                </p>";
        }
  
}

?>

<div class="item search">
    <form action="#" method="GET">
        <fieldset class="catfield">
            <legend>Search topics or posts area</legend>
    <input type="text" name="search" placeholder="search" required>
    <select name="filter" required>
        <option >Search fields</option>
        <option value="topics">Topics</option>
        <option value="posts">Posts</option>
        <input type="submit" value="Find">
    </select>
         </fieldset>
    </form>
</div>
</div>

<?php
include_once "commponents/footer.php";
?>




