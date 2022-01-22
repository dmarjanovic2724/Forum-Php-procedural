<?php

require_once "conn.php";


//User Table
$table="CREATE TABLE IF NOT EXISTS users(
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL)  
    ENGINE = InnoDB;";


//Categories Table

$table .="CREATE TABLE IF NOT EXISTS categories(
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    cat_name VARCHAR(50) UNIQUE NOT NULL,
    cat_description VARCHAR(255) NOT NULL)     
    ENGINE = InnoDB;";


//Topics Table

$table .="CREATE TABLE IF NOT EXISTS topics(
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    topic_name VARCHAR(255) NOT NULL,
    topic_date DATETIME NOT NULL,   
    topic_done BOOLEAN NOT NULL DEFAULT 0,
    cat_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    FOREIGN KEY(cat_id) REFERENCES categories(id),
    FOREIGN KEY(user_id) REFERENCES users(id))
    ENGINE = InnoDB;";


//Posts Table

$table .="CREATE TABLE IF NOT EXISTS posts(
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,   
    post_text TEXT NOT NULL,
    post_date DATETIME NOT NULL,    
    topic_id INT UNSIGNED NOT NULL,  
    user_id INT UNSIGNED NOT NULL,
    FOREIGN KEY(topic_id) REFERENCES topics(id),   
    FOREIGN KEY(user_id) REFERENCES users(id))
    ENGINE = InnoDB;";


$result=$conn->multi_query($table);
if($result){
    echo "Tables created successfull";
}else{
    echo "Failed, something went wrong...". $conn->error;
}
?>