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

if (!isset($_GET['doctor_id']) || empty($_GET['doctor_id'])) {
    die('Invalid doctor ID.');
}

$doctor_id = intval($_GET['doctor_id']);

// Fetch existing doctor details
$query = 'SELECT d.doctor_id, d.name, d.job_type, dh.available_day, dh.available_time
          FROM doctors d
          JOIN doctor_hospital dh ON d.doctor_id = dh.doctor_id
          WHERE d.doctor_id = ?';
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die('Doctor not found.');
}

$doctor = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $job_type = trim($_POST['job_type']);
    $available_day = trim($_POST['available_day']);
    $available_time = trim($_POST['available_time']);

    if (!empty($name) && !empty($job_type) && !empty($available_day) && !empty($available_time)) {
        $updateQuery = 'UPDATE doctors SET name = ?, job_type = ? WHERE doctor_id = ?';
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('ssi', $name, $job_type, $doctor_id);
        $stmt->execute();

        $updateScheduleQuery = 'UPDATE doctor_hospital SET available_day = ?, available_time = ? WHERE doctor_id = ?';
        $stmt = $conn->prepare($updateScheduleQuery);
        $stmt->bind_param('ssi', $available_day, $available_time, $doctor_id);
        $stmt->execute();

        echo "<script>alert('Doctor updated successfully!'); window.location.href='hospital_dashboard.php';</script>";
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
    <title>Edit Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Doctor</h2>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Doctor Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($doctor['name']) ?>"
                    required>
            </div>
            <div class="mb-3">
                <label class="form-label">Specialty</label>
                <input type="text" name="job_type" class="form-control"
                    value="<?= htmlspecialchars($doctor['job_type']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Available Days</label>
                <input type="text" name="available_day" class="form-control"
                    value="<?= htmlspecialchars($doctor['available_day']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Available Time</label>
                <input type="time" name="available_time" class="form-control"
                    value="<?= htmlspecialchars($doctor['available_time']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Doctor</button>
            <a href="hospital_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>

<?php $conn->close(); ?>