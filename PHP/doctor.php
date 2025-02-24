<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f4f6f9;
    }

    .container {
        max-width: 1000px;
        position: relative;
        min-height: 100vh;
        padding-bottom: 60px;
    }

    .card {
        border-radius: 15px;
        background: white;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        height: 100%;
    }

    .btn {
        border-radius: 8px;
    }

    h2 {
        font-weight: bold;
        color: #343a40;
    }

    .full-height {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .logout-btn {
        bottom: 20px;
        right: 20px;
        padding: 10px 20px;
    }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Doctor Dashboard</h2>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card p-4 full-height">
                    <h5 class="mb-3">Appointments</h5>
                    <ul class="list-group">
                        <li class="list-group-item">🩺 John Doe - 10:00 AM</li>
                        <li class="list-group-item">🩺 Jane Smith - 1:00 PM</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4 full-height">
                    <h5 class="mb-3">Update Availability</h5>
                    <form>
                        <div class="mb-3">
                            <label for="date" class="form-label">Select Hospital</label>
                            <select name="hospital" id="hospital" class="btn bg-success ms-5 text-center text-white">
                                <option value="hospital1" class="ms-3">Heaven sky hospital</option>
                                <option value="hospital2" class="ms-3">Thamine General Hospital</option>
                            </select>
                        </div>
                        <div class="mb-3">

                        </div>
                        <button class="btn btn-primary w-100">Update</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Patient Records Section -->
        <div class="row g-4 mt-2">
            <div class="col-md-12">
                <div class="card p-4 text-center">
                    <h5 class="mb-3">Patient Records</h5>
                    <button class="btn btn-info w-100" data-bs-toggle="collapse" data-bs-target="#patientRecords">View
                        Patient Records</button>

                    <!-- Collapsible Patient Records -->
                    <div id="patientRecords" class="collapse mt-3">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Age</th>
                                    <th>Condition</th>
                                    <th>Last Visit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require 'connection.php';
                                $conn = connect();

                                $sql = $conn->prepare('select * from patients');
                                $sql->execute();
                                $result = $sql->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($result as $row) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['age']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['Con_dition']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['last_visit']) . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-danger logout-btn m-3">Logout</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>