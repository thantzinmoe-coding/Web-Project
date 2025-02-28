<?php
require 'connection.php';
$conn = connect();

$data = json_decode(file_get_contents('php://input'), true);
$hospital_id = $data['hospital_id'];

$sql = $conn->prepare("SELECT * FROM booking WHERE hospital_id = :hospital_id");
$sql->bindParam(':hospital_id', $hospital_id, PDO::PARAM_INT);
$sql->execute();
$appointments = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($appointments as $appointment) {
    echo "<tr>";
    echo "<td class='text-start'>" . htmlspecialchars($appointment['patient_name']) . "</td>";
    echo "<td class='text-start'>" . htmlspecialchars($appointment['appointment_start_time']) . " - " . htmlspecialchars($appointment['appointment_end_time']) . "</td>";
    echo "<td class='text-start'>
            <div class='appointment-actions'>
                <button class='btn btn-success btn-sm done-btn'>Done</button>
            </div>
          </td>";
    echo "</tr>";
}
?>