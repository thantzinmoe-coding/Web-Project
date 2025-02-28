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

$hospital_id = 0;

// Unset hospital_id on page reload
if (isset($_SESSION['hospital_id'])) {
    $hospital_id = $_SESSION['hospital_id'];
}


// Fetch total doctors count
$totalDoctorsQuery = 'SELECT COUNT(DISTINCT doctor_id) AS total_doctors FROM doctor_hospital WHERE hospital_id = ?';
$stmt = $conn->prepare($totalDoctorsQuery);
$stmt->bind_param('i', $hospital_id);
$stmt->execute();
$totalDoctorsResult = $stmt->get_result()->fetch_assoc();
$totalDoctors = $totalDoctorsResult['total_doctors'];

// Fetch doctors for this hospital
$doctorQuery = '
   SELECT d.doctor_id, d.name, d.job_type, dh.available_day, dh.available_time,
       (SELECT COUNT(*) FROM booking WHERE booking.doctor_id = d.doctor_id) AS total_appointments
FROM doctor_hospital dh
JOIN doctors d ON dh.doctor_id = d.doctor_id
WHERE dh.hospital_id = ?';
$stmt = $conn->prepare($doctorQuery);
$stmt->bind_param('i', $hospital_id);
$stmt->execute();
$doctorResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .dashboard-card {
            border-radius: 10px;
            background: white;
            padding: 20px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .dashboard-card:hover {
            transform: scale(1.03);
        }

        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h2 class="text-center mb-4">🏥 Hospital Admin Dashboard</h2>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="dashboard-card text-center">
                    <h4>Total Doctors</h4>
                    <h2 class="text-primary"><?= $totalDoctors ?></h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card text-center">
                    <h4>Total Appointments</h4>
                    <h2 class="text-success">
                        <?php
                        $appointmentCountQuery = 'SELECT COUNT(*) AS total_appointments FROM booking b JOIN doctor_hospital dh ON b.doctor_id = dh.doctor_id WHERE dh.hospital_id = ?';
                        $stmt = $conn->prepare($appointmentCountQuery);
                        $stmt->bind_param('i', $hospital_id);
                        $stmt->execute();
                        $appointmentCountResult = $stmt->get_result()->fetch_assoc();
                        echo $appointmentCountResult['total_appointments'];
                        ?>
                    </h2>
                </div>
            </div>
        </div>

        <!-- Doctor List -->
        <h2 class="text-center mb-4">🩺 Doctor List</h2>
        <div class="table-container">
            <table class="table table-bordered table-hover">
                <thead class="table-primary small">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Specialty</th>
                        <th>Available Days</th>
                        <th>Available Time</th>
                        <th>Total Appointments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php while ($doctor = $doctorResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= $doctor['doctor_id'] ?></td>
                            <td><?= htmlspecialchars($doctor['name']) ?></td>
                            <td><?= htmlspecialchars($doctor['job_type']) ?></td>
                            <td><?= htmlspecialchars($doctor['available_day']) ?></td>
                            <td><?= htmlspecialchars($doctor['available_time']) ?>
                            </td>
                            <td><?= $doctor['total_appointments'] ?></td>
                            <td>
                                <a href="<?php echo '/DAS/PHP/hos_admin_view_appointment.php?doctor_id=' . htmlspecialchars($doctor['doctor_id']); ?>"
                                    class="btn btn-info btn-sm">View Appointments</a>

                                <a href="<?php echo '/DAS/PHP/edit_doctor.php?doctor_id=' . htmlspecialchars($doctor['doctor_id']); ?>"
                                    class="btn btn-warning btn-sm">Edit</a>

                                <a href="<?php echo '/DAS/PHP/delete_doctor.php?doctor_id=' . htmlspecialchars($doctor['doctor_id']); ?>"
                                    class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <form action="/DAS/PHP/logout.php">
                <a href="/DAS/add-doctor" class="btn btn-success mb-3">➕ Add New Doctor</a>
                <button class="btn btn-danger mb-3 float-end">Log Out</button>
            </form>
        </div>
    </div>
    </div>
</body>

</html>

<?php $conn->close(); ?>