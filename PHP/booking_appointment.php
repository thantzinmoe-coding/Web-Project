<?php

header('Content-Type: application/json');
ob_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    ob_end_clean();
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = isset($_POST['doctor_id']) ? intval($_POST['doctor_id']) : 0;
    $useremail = isset($_POST['useremail']) ? htmlspecialchars($_POST['useremail']) : '';
    $hospital_id = isset($_POST['hospital_id']) ? intval($_POST['hospital_id']) : 0;
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $time_range = isset($_POST['time']) ? trim($_POST['time']) : '';
    $patient_name = isset($_POST['patient_name']) ? trim($_POST['patient_name']) : '';
    $symptoms = isset($_POST['symptoms']) ? trim($_POST['symptoms']) : '';

    if ($doctor_id <= 0 || $hospital_id <= 0 || empty($date) || empty($time_range)) {
        die(json_encode(["error" => "Missing required fields"]));
    }

    $sql = "SELECT name from doctors WHERE doctor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($doctor_name);
    $stmt->fetch();
    ob_end_clean();

    if (!$stmt->num_rows) {
        die(json_encode(["error" => "Doctor not found"]));
    }
    $stmt->close();

    $sql = "SELECT name, location from hospitals WHERE hospital_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hospital_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hospital_name, $hospital_location);
    $stmt->fetch();

    ob_end_clean();

    if (!$stmt->num_rows) {
        die(json_encode(["error" => "Hospital not found"]));
    }
    $stmt->close();

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        die(json_encode(["error" => "Invalid date format"]));
    }

    // Extract start and end time from "11AM-1PM"
    if (!preg_match('/^(\d{1,2}(?::\d{2})?\s?(AM|PM))-(\d{1,2}(?::\d{2})?\s?(AM|PM))$/i', $time_range, $matches)) {
        die(json_encode(["error" => "Invalid time range format. Expected format: '11AM-1PM' or '11:30 AM-1:00 PM'"]));
    }

    $start_time = $matches[1];
    $end_time = $matches[3];

    $query = "INSERT INTO booking (doctor_id, hospital_id, useremail, appointment_date, appointment_start_time, appointment_end_time, patient_name, symptoms) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die(json_encode(["error" => "SQL prepare error: " . $conn->error]));
    }

    $appointment_date = date('d M Y', strtotime($date));
    require_once 'patient.php';
    $patient = new Patient($patient_name, $useremail, $doctor_name, $hospital_name, $hospital_location, $symptoms, $appointment_date);
    sendAppointmentEmail($patient);

    $stmt->bind_param("iissssss", $doctor_id, $hospital_id, $useremail, $date, $start_time, $end_time, $patient_name, $symptoms);

    if (!$stmt->execute()) {
        ob_end_clean();
        echo json_encode(["error" => "Database error: " . $stmt->error]);
        exit;
    }
    echo json_encode(["success" => true, "message" => "Appointment booked successfully"]);

    $stmt->close();
    $conn->close();

} else {
    ob_end_clean();
    echo json_encode(["error" => "Invalid request method. Only POST is allowed."]);
}
