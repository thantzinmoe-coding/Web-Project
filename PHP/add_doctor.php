<?php

header('Content-Type: application/json');

session_start();
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'project';
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Ensure hospital_id is set
if (!isset($_SESSION['hospital_id'])) {
    die('Hospital ID is not set.');
}

$hospital_id = $_SESSION['hospital_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture text inputs from POST
    $name = trim($_POST['name']);
    $job_type = trim($_POST['job_type']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $credential = trim($_POST['credential']);
    $gender = trim($_POST['gender']);
    $consultation_fee = trim($_POST['consultation_fee']);
    $profile = trim($_POST['profile']);
    $experience = trim($_POST['experience']);
    $available_day = trim($_POST['available_day']);
    $available_time = trim($_POST['available_time']);

    // Set the upload directory path relative to this file
    $uploadDir = 'uploads/';

    // Check if the upload directory exists, if not create it
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = time() . '_' . basename($_FILES['profile_image']['name']);
    $targetFile = $uploadDir . $filename;

    // Move the uploaded file
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
        // Save just the filename in the database
        if (
            !empty($name) && !empty($job_type) && !empty($email) && !empty($password)
            && !empty($credential) && !empty($gender) && !empty($consultation_fee)
            && !empty($profile) && !empty($experience) && !empty($available_day)
            && !empty($available_time)
        ) {

            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            // Create the INSERT query for doctors table containing all fields
            $query = "INSERT INTO doctors 
                (name, job_type, email, password, credential, gender, consultation_fee, profile, experience, profile_image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param(
                'ssssssdsss',
                $name,
                $job_type,
                $email,
                $hash_password,
                $credential,
                $gender,
                $consultation_fee,
                $profile,
                $experience,
                $filename
            );
            if ($stmt->execute()) {
                $insert_doctor_id = $conn->insert_id;
                $stmt->close();
                $sql = "INSERT INTO doctor_hospital (doctor_id, hospital_id, available_day, available_time) VALUES ('$insert_doctor_id', '$hospital_id', '$available_day', '$available_time')";
                $stmt = $conn->prepare($sql);
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Doctor added successfully.']);
                    exit();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error adding doctor to hospital: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error adding doctor: ' . $stmt->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'File upload failed.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>