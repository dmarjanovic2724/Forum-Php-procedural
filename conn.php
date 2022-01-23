<?php

$serverName= "localhost";
$userName="root";
$db="forum";
$password="";
$port="3306";


$conn= new mysqli($serverName,$userName, $password,$db, $port);

if($conn->connect_error)
{
    die("Connection failed" . $conn->connect_error);
}