<?php

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
    case '':
        require __DIR__ . '/index.php';
        break;
    case '/DAS/Home':
    case '/DAS/home':
        require __DIR__ . '/PHP/Home_page.php';
        break;
    case '/DAS/test':
        require __DIR__ . '/PHP/test.php';
        break;
    case '/DAS/doctor':
        require __DIR__ . '/PHP/Find_Doctor.php';
        break;
    case '/DAS/hospital':
        require __DIR__ . '/PHP/Find_Hospital.php';
        break;
    case '/DAS/login':
        require __DIR__ . '/Html/Sign_In.html';
        break;
    case '/DAS/verify':
        require __DIR__ . '/Html/verify.html';
        break;
    case '/DAS/forget-password':
        require __DIR__ . '/Html/forget-password.html';
        break;
    case '/DAS/reset-password':
        require __DIR__ . '/Html/reset-password.html';
        break;
    case '/DAS/dashboard':
        require __DIR__ . '/Html/dashboard.html';
        break;
    case '/DAS/doctor-profile':
        require __DIR__ . '/PHP/doctor.php';
        break;
    case '/DAS/booking-doctor':
        require __DIR__ . '/PHP/booking.php';
        break;
    case '/DAS/booking-hospital':
        require __DIR__ . '/PHP/booking_hospitals.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/404.php';
        break;
}
