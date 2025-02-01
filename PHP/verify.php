<?php

header('Content-Type: application/json');
session_start();

$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);

if($data['source'] === 'verify') {
    $email = $data['email'];
    $otp = $data['otp'];
    echo "Data received from verify: Email = $email";
    echo "Data received from verify: Otp = $otp";
    
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "project";
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if ($conn->connect_error) {
        echo "Connection failed!";
        exit;
    }

    $stmt = $conn->prepare("SELECT otp FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($otp_db);
    $stmt->fetch();

    if($otp == $otp_db) {
        echo "OTP verified!";
        $stmt = $conn->prepare("UPDATE users SET otp = 0, status = 'verified' WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $_SESSION['loggedin'] = true;
        echo "Email verified!";
    } else {
        echo "Invalid OTP!";
    }

} else {
    echo "Invalid source!";
}

?>