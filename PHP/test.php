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
?>
