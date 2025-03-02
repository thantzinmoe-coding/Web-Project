<?php

header('Content-Type: application/json');

session_start();
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'project';
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Ensure hospital_id is set
if (!isset($_SESSION['hospital_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Hospital ID is not set.']));
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

    // Handling Available Days and Times (received as CSV)
    $available_days = isset($_POST['available_day']) ? explode(',', $_POST['available_day']) : [];
    $available_times = isset($_POST['available_time']) ? explode(',', $_POST['available_time']) : [];

    // Ensure matching count of days and times
    if (count($available_days) !== count($available_times)) {
        echo json_encode(['status' => 'error', 'message' => 'Mismatch in available days and times.']);
        exit();
    }

    // Validate available time format (Server-side validation)
    $time_pattern = "/^([1-9]|1[0-2])(am|pm)-([1-9]|1[0-2])(am|pm)$/";

    foreach ($available_times as $time) {
        if (!preg_match($time_pattern, trim($time))) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid time format detected. Use format like "12pm-2pm".']);
            exit();
        }
    }

    // Set the upload directory path
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Handle Profile Image Upload
    if (!empty($_FILES['profile_image']['name'])) {
        $filename = time() . '_' . basename($_FILES['profile_image']['name']);
        $targetFile = $uploadDir . $filename;

        if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
            echo json_encode(['status' => 'error', 'message' => 'Profile image upload failed.']);
            exit();
        }
    } else {
        $filename = ''; // No image uploaded
    }

    // Hash the password before storing
    $hash_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into `doctors` table
    $stmt = $conn->prepare("INSERT INTO doctors (name, job_type, email, password, credential, gender, consultation_fee, profile, experience, profile_image) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssssdsss', $name, $job_type, $email, $hash_password, $credential, $gender, $consultation_fee, $profile, $experience, $filename);

    if ($stmt->execute()) {
        $doctor_id = $stmt->insert_id;
        $stmt->close();

        // Insert into `doctor_hospital` table (Each day will be inserted as a separate row)
        $stmt = $conn->prepare("INSERT INTO doctor_hospital (doctor_id, hospital_id, available_day, available_time) VALUES (?, ?, ?, ?)");

        for ($i = 0; $i < count($available_days); $i++) {
            $day = trim($available_days[$i]); // Get the current day
            $time = trim($available_times[$i]); // Get the corresponding time

            if (!empty($day) && !empty($time)) { // Ensure both are set
                $stmt->bind_param("iiss", $doctor_id, $hospital_id, $day, $time);
                $stmt->execute();
            }
        }
        $stmt->close();

        echo json_encode(['status' => 'success', 'message' => 'Doctor added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding doctor: ' . $stmt->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>