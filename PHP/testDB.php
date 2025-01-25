<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="testDB.php" method="post">
        <label for="">Input</label><br>
        <input type="text" name="name" id="input"><br>
        <input type="submit" name="login" value="login">
    </form>
</body>
</html>
<?php

include 'connection.php';

if(isset($_POST["login"])){
    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);

    echo $name;
}

?>