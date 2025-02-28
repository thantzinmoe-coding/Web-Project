<?php

require 'connection.php';
$conn = connect();

$sql = $conn->prepare("SELECT p.*, h.name AS hospital_name FROM patients p LEFT JOIN hospitals h ON p.hospital_id = h.hospital_id;");
$sql->execute();
$result = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['appointment_date']) . "</td>";
    echo "<td>" . htmlspecialchars($row['appointment_start_time']) . "</td>";
    echo "<td>" . htmlspecialchars($row['appointment_end_time']) . "</td>";
    echo "<td>" . htmlspecialchars($row['hospital_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['symptoms']) . "</td>";
    echo "</tr>";
}
