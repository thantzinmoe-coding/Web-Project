<?php

$db_server = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "project";
$conn = "";

try{
    $conn = mysqli_connect(
        $db_server, 
        $db_user, 
        $db_password, 
        $db_name);
    if($conn){
        echo "connected succeccfully!";
    }
} catch(mysqli_sql_exception){
    echo "connection failed!";
}

?>