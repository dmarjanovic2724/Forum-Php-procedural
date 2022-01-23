<?php
require_once "conn.php";
require_once "commponents/head.php";
session_start();
if (empty($_SESSION['userId'])) {
    header("Location: index.php");
}

$userId = $_SESSION['userId'];
$topic_id = $_GET['id'];
$cat=$_SESSION['cat'];
var_dump($cat);
$date = date('Y-m-d H:i:s');
$message="";

//query

$query="SELECT topic_name from topics WHERE id = $topic_id";
$result=$conn->query($query);
$topic=$result->fetch_assoc();
$topicName=$topic['topic_name'];
?>
<h1><?php echo $topicName ?></h1>
<?php
//form 1
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST['post'])) {

   
        $validate = false;
        $postName = $_POST['postName'];
    
        if ($validate == false) {
            $query = "INSERT INTO posts (post_text, post_date, topic_id, user_id) VALUES
            ('$postName', '$date', $topic_id, '$userId')";
            $result = $conn->query($query);
            if ($result) {           
                $message = "new post has been created";
                header("refresh:2;url=posts.php?id=$topic_id");
            } else {
                $message = "something went wrong, the post isnt created";
                header("refresh:2;url=posts.php?id=$topic_id");
            }
        }   
    
}
//form2
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset( $_POST['close'])) {

    
    $query="UPDATE topics SET topic_done='1' WHERE id = $topic_id";
    $result=$conn->query($query);
    if($result){
        $message = "The topic is closed";
    }else{
        $message ="erorr";
    }
}

?>

<h3><?php echo $message ?></h3>


<div>
    <div>
        <form  action="" method="POST">
            <fieldset>
                <legend>Create new post</legend>

                <input type="text" name="postName" value="" placeholder="new post...">
                <input type="submit" name="post" value="Create"><span class="errors">
                    <?php echo $message ?></span>

            </fieldset>

        </form>
    </div>
   
</div>

<?php
$query="SELECT posts.id, posts.post_text , posts.post_date, topics.topic_name, users.username FROM topics INNER JOIN posts ON posts.topic_id = topics.id INNER JOIN users ON posts.user_id = users.id WHERE topics.id = $topic_id";
$result=$conn->query($query);

if($result->num_rows !=0)
{
    echo "<table class='table'>
    <th>topic name:</th>
    <th>topic date:</th>
    <th>Created by:</th>
    <th>cat name</th>";
foreach ($result as $post) {

echo "<tr>";
echo "<td>".$post['post_text']."</td>";
echo "<td>".$post['post_date']."</td>";
echo "<td>".$post['username']."</td>";
echo "</tr>";
}
echo "</table>";
}

?>

<div>
    <form action="#"  method="POST">
        <input type="submit" name="close" value="close topic">
    </form>
</div>

<a href="topics.php?cat=<?php echo $cat ?>">Topics</a>