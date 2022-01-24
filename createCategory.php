<?php
require_once "conn.php";
require_once "commponents/head.php";


if($_SERVER["REQUEST_METHOD"]=="POST")
{
        $validate=false;
        $catName=$conn->real_escape_string($_POST['catName']);
        $description=$conn->real_escape_string($_POST['description']);

        if($validate == false)
    {
        $query="INSERT INTO categories(cat_name, cat_description) VALUES ('$catName','$description')";
        $result=$conn->query($query);        
        if($result){
            echo "New category has been created";
        }else{
            echo "an error has occurred";
        }
    }    
}

?>
    <div class="form-post">    
        <form action="" method="POST">
                <fieldset>
                <legend>Create a new category</legend>
                
                <input type="text" name="catName"  placeholder="Category name">
                <input type="text" name="description"  placeholder="Category description">
                <input type="submit" value="Create">
            </fieldset>
        </form>             
    </div>
    <p>
        <a href="index.php">&lAarr; Go back</a>
    </p>  
