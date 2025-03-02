<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <script>
        let fruits = ["2025-02-13", "2025-02-17", "2025-02-13"];

        console.log(fruits.includes("2025-02-13")); // true
        console.log(fruits.includes("grape")); // false
        console.log(fruits.includes("apple", 1));
    </script>
</body>

</html> -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="admin">
        <label for="">Email: </label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="">Password: </label><br>
        <input type="password" id="password" name="password" required><br><br>
        <label for="">Hospital ID:</label><br>
        <input type="text" name="hospitalID" id="hospitalID"><br><br>
        <label for="">Role:</label><br>
        <input type="text" name="role" id="role"><br><br>
        <button id="submit">OKay</button>
    </form>

    <script>
        document.getElementById('submit').addEventListener('click', function(e) {
            e.preventDefault();
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;
            let hospitalID = document.getElementById('hospitalID').value;
            let role = document.getElementById('role').value;

            console.log(email);
            console.log(password);
            console.log(hospitalID);
            console.log(role);

            let formData = new FormData();
            formData.append('email', email);
            formData.append('password', password);
            formData.append('hospitalID', hospitalID);
            formData.append('role', role);

            fetch('/DAS/PHP/test1.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
</body>

</html>
<?php

// $filename = "Report.txt";

// if(!file_exists($filename)){
//     $file = fopen($filename, "w");
// } else {
//     $file = fopen($filename, "w");
// }

// if($file){
//     for($i = 'A'; $i <= 'Z'; $i++){
//         fwrite($file, ($i));
//         if($i === 'Z'){
//             break;
//         }
//     }
//     fclose($file);
//     echo "Report.txt has been created (or cleared) and written with letters A-Z.</br>";
// } else {
//     echo "Unable to open the files";
// }

// $fr = fopen("Report.txt", "r");
// if($fr){
//     $content = fread($fr, filesize("Report.txt"));
//     echo $content;
// }

// $date = "2025-02-15";
// $day = date('d M Y', strtotime($date));
// echo $day;

// Creating an object
// require_once 'patient.php';
// $patient = new Patient("John Doe","tmoe8123@gmail.com", "Dr. Smith", "City Hospital", "123 Main St", "Fever, Cough", "20th Feb 2025");

// // Passing the object to function
// sendAppointmentEmail($patient);

$passwords = [
    "DrJohn123",
    "DrSarah123",
    "DrEmily123",
    "DrMichael123",
    "DrRachel123"
];

foreach ($passwords as $password) {
    echo "Plain: $password<br>";
    echo "Hashed: " . password_hash($password, PASSWORD_DEFAULT) . "<br><br>";
}

// $dsn = "mysql:host=localhost;dbname=project";
// $username = "root";
// $password = "";

// try {
//     $conn = new PDO($dsn, $username, $password);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     echo "Connected successfully";

//     $sql = "SELECT * FROM doctors";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     if($stmt->rowCount()){
//         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         echo "<pre>";
//         print_r($result);
//         echo "</pre>";
//     } else {
//         echo "No records found";
//     }
// } catch (PDOException $e) {
//     echo "Connection failed: " . $e->getMessage();
// }

// Database connection parameters
// $host = 'localhost';        // Replace with your host
// $db = 'project';            // Your database name
// $user = 'root';     // Replace with your database user
// $pass = ''; // Replace with your database password

// try {
//     // Set up the PDO connection
//     $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//     // Fetch all doctors from the doctors table
//     $stmt = $pdo->query("SELECT doctor_id FROM doctors");
//     $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     // Fetch all hospital ids between 1 and 30
//     $hospitalIds = range(1, 30);

//     // Start a transaction
//     $pdo->beginTransaction();

//     // Loop over each doctor
//     foreach ($doctors as $doctor) {
//         // Generate a random number of hospitals (between 1 and 5)
//         $numHospitals = rand(1, 5);

//         // Randomly pick hospitals for this doctor
//         $assignedHospitals = array_rand($hospitalIds, $numHospitals);

//         // If only one hospital is assigned, $assignedHospitals will be a single number, so wrap it in an array.
//         if (!is_array($assignedHospitals)) {
//             $assignedHospitals = [$assignedHospitals];
//         }

//         // Loop through each assigned hospital for the current doctor
//         foreach ($assignedHospitals as $hospitalIndex) {
//             $hospitalId = $hospitalIds[$hospitalIndex];

//             // Generate a random available day (1 to 7, representing Monday to Sunday)
//             $days = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];
//             $availableDay = $days[rand(0, 6)];

//             // Generate a random start time between 8 AM (8) and 5 PM (17)
//             // Generate a random start time between 8 AM (8) and 5 PM (17)
//             $startTime = rand(8, 16);

//             // The duration will always be 2 hours
//             $duration = 2;

//             // Calculate the available time range
//             $endTime = $startTime + $duration;

//             // Format the time in '1PM-3PM' format
//             $startFormatted = date("gA", strtotime("$startTime:00"));
//             $endFormatted = date("gA", strtotime("$endTime:00"));

//             // Store the formatted available time
//             $availableTime = "$startFormatted-$endFormatted";

//             // Prepare the insert query for doctor_hospital table
//             $insertQuery = "INSERT INTO doctor_hospital (doctor_id, hospital_id, available_day, available_time)
//                 VALUES (:doctor_id, :hospital_id, :available_day, :available_time)";
//             $insertStmt = $pdo->prepare($insertQuery);
//             $insertStmt->execute([
//                 ':doctor_id' => $doctor['doctor_id'],
//                 ':hospital_id' => $hospitalId,
//                 ':available_day' => $availableDay,
//                 ':available_time' => $availableTime
//             ]);
//         }
//     }
    
//     // Commit the transaction
//     $pdo->commit();
//     echo "Data inserted successfully.";
// } catch (PDOException $e) {
//     // Rollback the transaction if an error occurs
//     $pdo->rollBack();
//     echo "Error: " . $e->getMessage();
// }

// $password = "Doctor@123";
// $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
// echo $hashedPassword;

?>