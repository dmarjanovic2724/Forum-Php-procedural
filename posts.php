<?php
require_once "conn.php";
session_start();

if(empty($_SESSION['userId'])){
    header("Location: index.php");
}
if(empty($_GET['id'])){
    header("Location: index.php");
}
$userId = $_SESSION['userId'];
$topic_id = $_GET['id'];
$query = "SELECT topic_name, topic_done, cat_id from topics WHERE id = $topic_id";
$result = $conn->query($query);
$topic = $result->fetch_assoc();
$cat=$topic['cat_id'];
$topicName = $topic['topic_name'];
$topicStatus = $topic['topic_done']; //topic status 0=active ||  1=close
require "commponents/head.php";

$date = date('Y-m-d H:i:s');
$message = "";

if ($topicStatus == 0) {
    //form 1
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post'])) {




        //validate
        if (empty($_POST['postName'])) {
            $validated = true;
            $message = "write a post";
        } else {
            $validated = false;
            $postName = $conn->real_escape_string($_POST['postName']);
            // $postName=str_ireplace('  ',' ',$postName); cut whitespaces??
        }


        if ($validated == false) {
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

    //form 2

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['close'])) {


        $query = "UPDATE topics SET topic_done='1' WHERE id = $topic_id";
        $result = $conn->query($query);
        if ($result) {
            header('Location: posts.php?id=' . $topic_id);
        } else {
            $message = "erorr";
        }
    }
?>


    <div class="form-post">
        <form action="#" method="POST">
            <input type="text" name="postName" value="" placeholder="Create new post...">
            <input type="submit" name="post" value="Create">
            <p class="errors"><?php echo $message ?></p>
        </form>
    </div>
    <div id="close-topic">
        <form action="#" method="POST">
            <input type="submit" name="close" value="close topic">
        </form>
    </div>



<?php
}

if ($topicStatus == 1) {
    echo "<div id='post-msg'><h1>Topic <span class='errors'>" . $topicName . "</span> is closed</h1></div>";
}

$query = "SELECT posts.id, posts.post_text , posts.post_date, topics.topic_name, users.username FROM topics INNER JOIN posts ON posts.topic_id = topics.id INNER JOIN users ON posts.user_id = users.id WHERE topics.id = $topic_id";
$result = $conn->query($query);

if ($result->num_rows != 0) {
    foreach ($result as $post) {

        echo "<div class='post'><div class='post-top'>
            <p class='post-userName'><i class='fas fa-user-tie'></i>" . $post['username'] . "</p><p class='post-date'><i class='fas fa-clock'></i> &nbsp;" . $post['post_date'] . "</p>
            </div>";
        echo "<p class='post-text'><i class='fas fa-comments'></i> &nbsp;" . $post['post_text'] . "</p></div><hr>";
    }
}



include_once "commponents/footer.php";
?>