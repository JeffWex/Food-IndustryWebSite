<?php
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
    include("./connect.php");

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die(json_encode(['error' => 'Database connection error.']));
    }

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            getMenu($conn);
            break;
        default:
            die(json_encode(['error' => 'Invalid request method.']));
    }

    function getMenu($conn) {
        $result = $conn->query('SELECT id, fname, lname, mobile, email, user_type FROM user_tb');
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
    }


    $conn->close();
?>