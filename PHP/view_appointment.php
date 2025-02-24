<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: Sign_In.html');
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'project');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Get user email from session
$user_email = $_SESSION['email'];

// Fetch user appointments
$stmt = $conn->prepare('
    SELECT b.id, d.name AS doctor_name, h.name AS hospital_name, b.appointment_date, 
           b.appointment_start_time, b.appointment_end_time, b.patient_name, b.symptoms
    FROM booking b
    JOIN doctors d ON b.doctor_id = d.doctor_id
    JOIN hospitals h ON b.hospital_id = h.hospital_id
    WHERE b.useremail = ?
    ORDER BY b.appointment_date DESC;
');
$stmt->bind_param('s', $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }

    .container {
        max-width: 900px;
    }

    .appointment-card {
        border-radius: 10px;
        transition: 0.3s;
        background: white;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
    }

    .appointment-card:hover {
        transform: scale(1.02);
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
    }

    .status-badge {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 20px;
    }

    .cancel-btn {
        background-color: #dc3545;
        border: none;
        color: white;
        padding: 6px 12px;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .cancel-btn:hover {
        background-color: #c82333;
    }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">📅 My Appointments</h2>

        <?php if ($result->num_rows > 0): ?>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-6 mb-4">
                <div class="card appointment-card p-3">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title">👨‍⚕️ <?php echo htmlspecialchars($row['doctor_name']); ?></h5>
                        <span class="badge bg-primary status-badge">Upcoming</span>
                    </div>
                    <p class="mb-1"><strong>🏥 Hospital:</strong> <?php echo htmlspecialchars($row['hospital_name']); ?>
                    </p>
                    <p class="mb-1"><strong>📅 Date:</strong> <?php echo htmlspecialchars($row['appointment_date']); ?>
                    </p>
                    <p class="mb-1"><strong>🕒 Time:</strong>
                        <?php echo htmlspecialchars($row['appointment_start_time']) . ' - ' . htmlspecialchars($row['appointment_end_time']); ?>
                    </p>
                    <p class="mb-1"><strong>🤒 Symptoms:</strong> <?php echo htmlspecialchars($row['symptoms']); ?></p>
                    <!-- <p class="mb-1"><strong>🔑 Token No:</strong> <span
                            class="badge bg-success"><?php echo htmlspecialchars($row['token_no']); ?></span></p> -->

                    <div class="text-end">
                        <button class="cancel-btn" data-bs-toggle="modal" data-bs-target="#cancelModal"
                            data-id="<?php echo $row['id']; ?>">Cancel</button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <p class="text-center text-muted">No appointments found.</p>
        <?php endif; ?>
    </div>

    <!-- Cancel Confirmation Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel this appointment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmCancel">Cancel Appointment</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let bookingIdToCancel = null;

    $(document).ready(function() {
        $(".cancel-btn").click(function() {
            bookingIdToCancel = $(this).data("id");
        });

        $("#confirmCancel").click(function() {
            if (bookingIdToCancel) {
                $.ajax({
                    url: "cancel_booking.php",
                    type: "POST",
                    data: {
                        booking_id: bookingIdToCancel
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function() {
                        alert("An error occurred while canceling the appointment.");
                    }
                });
            }
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>