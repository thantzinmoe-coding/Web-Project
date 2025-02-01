<?php

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        session_start();
        session_destroy();

        $response = array(
            'status' => 'success',
            'message' => 'You have been logged out successfully.'
        );

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
}

?>