<?php
require_once "conn.php";
session_start();
$userId = $_SESSION['userId'];
$topic_id = $_GET['id'];
$cat = $_SESSION['cat'];
require_once "commponents/head.php";
if (empty($_SESSION['userId'])) {
    header("Location: index.php");
}

$date = date('Y-m-d H:i:s');
$message = "";

//query

$query = "SELECT topic_name, topic_done from topics WHERE id = $topic_id";
$result = $conn->query($query);
$topic = $result->fetch_assoc();
$topicName = $topic['topic_name'];
$topicStatus = $topic['topic_done']; //topic status 0=active ||  1=close

if ($topicStatus == 0) {
    //form 1
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post'])) {


        $validate = false;
        $postName = $conn->real_escape_string($_POST['postName']);

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
            <p class='post-userName'>" . $post['username'] . "</p><p class='post-date'>" . $post['post_date'] . "</p>
            </div>";
        echo "<p class='post-text'>" . $post['post_text'] . "</p></div><hr>";
    }
}



include_once "commponents/footer.php";
?>