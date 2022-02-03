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
    <div class="position-center">  
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <fieldset class="catfield">
                <legend class="text-center"><h1>Create new category</h1></legend> 
                <label>Category name</label>               
                <input type="text" name="catName">
                <label>Category description</label>
                <input type="text" name="description">
                <input type="submit" value="Create">
            </fieldset>
        </form>             
    </div>
   
