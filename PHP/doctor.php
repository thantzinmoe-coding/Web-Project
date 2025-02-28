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

        .scroll-box {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .appointment-actions {
            display: flex;
            gap: 10px;
        }

        .full-height {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .sticky-header th {
            position: sticky;
            top: 0;
            background: white;
            z-index: 1;
        }

        .center-header th {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Doctor Dashboard</h2>

        <div class="row g-4">
            <div class="col-md-6 d-flex flex-column">
                <div class="card p-4 full-height">
                    <h5 class="mb-3">Hospitals you work at</h5>
                    <div class="scroll-box">
                        <table class="table table-striped">
                            <thead class="sticky-header">
                                <tr>
                                    <th>Hospital Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require 'connection.php';
                                $conn = connect();

                                $sql = $conn->prepare("SELECT hospital_id, name FROM hospitals ORDER BY name ASC");
                                $sql->execute();
                                $hospitals = $sql->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($hospitals as $hospital) {
                                    echo "<tr data-hospital_id='" . htmlspecialchars($hospital['hospital_id']) . "'>";
                                    echo "<td class='text-start'>" . htmlspecialchars($hospital['name']) . "</td>";
                                    echo "<td class='text-start'>
                                            <div class='appointment-actions'>
                                                <button class='btn btn-primary btn-sm view-btn'>View</button>
                                            </div>
                                          </td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex flex-column">
                <div class="card p-4 full-height">
                    <h5 class="mb-3">Appointments</h5>
                    <div class="scroll-box">
                        <table class="table table-striped">
                            <thead class="sticky-header">
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Appointment Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="appointments-tbody">
                                <?php
                                $sql = $conn->prepare("SELECT * FROM booking");
                                $sql->execute();
                                $result = $sql->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($result as $row) {
                                    echo "<tr data-id='" . htmlspecialchars($row['id']) . "'>";
                                    echo "<td class='text-start'>" . htmlspecialchars($row['patient_name']) . "</td>";
                                    echo "<td class='text-start'>" . htmlspecialchars($row['appointment_start_time']) . " - " . htmlspecialchars($row['appointment_end_time']) . "</td>";
                                    echo "<td class='text-start'>
                                            <div class='appointment-actions'>
                                                <button class='btn btn-success btn-sm done-btn'>Done</button>
                                            </div>
                                          </td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mt-4">
            <div class="col-md-12">
                <div class="card p-4 text-center">
                    <h5 class="mb-3">Patient Records</h5>
                    <button class="btn btn-info w-100" data-bs-toggle="collapse" data-bs-target="#patientRecords">View Patient Records</button>
                    <div id="patientRecords" class="collapse mt-3">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark center-header">
                                    <tr>
                                        <th>Patient Name</th>
                                        <th>Appointment Date</th>
                                        <th>Appointment Start Time</th>
                                        <th>Appointment End Time</th>
                                        <th>Hospital Name</th>
                                        <th>Symptoms</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to fetch patient history
            function fetchHistory() {
                fetch('/DAS/PHP/fetch_history.php')
                    .then(response => response.text())
                    .then(data => {
                        document.querySelector('#patientRecords tbody').innerHTML = data;
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Initial fetch when the page loads
            fetchHistory();

            // Attach event listener to all "done" buttons
            document.querySelectorAll('.done-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var row = this.closest('tr');
                    var id = row.getAttribute('data-id');
                    console.log(id);

                    fetch('/DAS/PHP/make_history.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: id
                            })
                        })
                        .then(response => response.text())
                        .then(data => {
                            console.log(data);
                            const jsonData = JSON.parse(data);
                            if (jsonData.status === 'success') {
                                row.remove();
                                fetchHistory(); // Refresh history after success
                            } else {
                                console.error('Error:', jsonData);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });

            // Attach event listener to all "view" buttons
            document.querySelectorAll('.view-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var row = this.closest('tr');
                    var hospitalId = row.getAttribute('data-hospital_id');
                    console.log(hospitalId);

                    fetch('/DAS/PHP/fetch_appointments.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                hospital_id: hospitalId
                            })
                        })
                        .then(response => response.text())
                        .then(data => {
                            document.querySelector('#appointments-tbody').innerHTML = data;
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>

</body>

</html>