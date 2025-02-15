<?php

class Patient {
    public $name;
    public $email;
    public $doctor;
    public $hospital;
    public $location;
    public $symptoms;
    public $appointmentDate;

    public function __construct($name, $email, $doctor, $hospital, $location, $symptoms, $appointmentDate) {
        $this->name = $name;
        $this->email = $email;
        $this->doctor = $doctor;
        $this->hospital = $hospital;
        $this->location = $location;
        $this->symptoms = $symptoms;
        $this->appointmentDate = $appointmentDate;
    }
}

function sendAppointmentEmail(Patient $patient) {
    // Access object properties
    require_once 'appointment_mail.php';
    sendMail($patient);
}

// // Creating an object
// $patient = new Patient("John Doe","tmoe8123@gmail.com", "Dr. Smith", "City Hospital", "123 Main St", "Fever, Cough", "20th Feb 2025");

// // Passing the object to function
// sendAppointmentEmail($patient);
