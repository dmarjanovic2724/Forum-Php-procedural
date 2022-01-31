<?php

require_once "conn.php";
require_once "commponents/head.php";
$url=$_SERVER['REQUEST_URI'];
$check=strpos($url,'posts.php?id');
$checkIndexPage=strpos($url,'index.php');
$checkCreateCategoryPage=strpos($url,'createCategory.php');
$sesionActive=isset($_SESSION['userId']);



if(!$checkIndexPage && !$checkCreateCategoryPage)
{
    $userName=$_SESSION['userName'];
    //logOUt
if (isset($_GET['logOff'])) {
    if ($sesionActive) {
        session_destroy();
        header('Location:index.php');
    }
}   
    echo  "
   
    <nav class='row'>            
            <ul>
            <div class='header-left'>                    
                <li><a href='categories.php'>Categories</a></li>";
                if($check)
                {
                    echo "<li><a href='topics.php?cat=$cat'>Topics</a></li>"; 
                }
                echo "</div><h1 class='title'>FORUM</h1>";
                               
                    if($sesionActive){
                        echo "<div class='header-right'><p id='username'><i class='fas fa-user-tie'></i> ".$userName."</p><li><a href='?logOff'><i class='fas fa-sign-out-alt'></i> LogOut</a></li></div>";
                    }
            echo "</ul>    
                   
        </nav>";

}