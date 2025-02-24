<?php
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
    $name = trim($_POST['name']);
    $job_type = trim($_POST['job_type']);
    $available_day = trim($_POST['available_day']);
    $available_time = trim($_POST['available_time']);

    if (!empty($name) && !empty($job_type) && !empty($available_day) && !empty($available_time)) {
        // Insert into the doctors table
        $insertDoctorQuery = 'INSERT INTO doctors (name, job_type) VALUES (?, ?)';
        $stmt = $conn->prepare($insertDoctorQuery);
        $stmt->bind_param('ss', $name, $job_type);
        $stmt->execute();
        $doctor_id = $stmt->insert_id;  // Get the newly inserted doctor ID

        // Insert into the doctor_hospital table
        $insertDoctorHospitalQuery = 'INSERT INTO doctor_hospital (doctor_id, hospital_id, available_day, available_time) VALUES (?, ?, ?, ?)';
        $stmt = $conn->prepare($insertDoctorHospitalQuery);
        $stmt->bind_param('iiss', $doctor_id, $hospital_id, $available_day, $available_time);
        $stmt->execute();

        echo "<script>alert('Doctor added successfully!'); window.location.href='hospital_dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('All fields are required!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Add New Doctor</h2>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Doctor Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Specialty</label>
                <input type="text" name="job_type" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Available Days</label>
                <input type="text" name="available_day" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Available Time</label>
                <input type="time" name="available_time" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Add Doctor</button>
            <a href="hospital_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>

<?php $conn->close(); ?>