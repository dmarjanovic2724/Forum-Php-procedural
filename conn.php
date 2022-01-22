<?php

$serverName= "localhost";
$userName="root";
$db="forum";
$password="";


$conn= new mysqli($serverName,$userName, $password,$db);

if($conn->connect_error)
{
    die("Connection failed" . $conn->connect_error);
}