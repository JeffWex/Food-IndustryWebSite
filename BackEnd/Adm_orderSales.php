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
        getOrder($conn);
        break;
    case 'POST':
        getFilter($conn);
        break;
    default:
        die(json_encode(['error' => 'Invalid request method.']));
}

function getFilter($conn) {
    $startDate = date('2023-12-01');
    $endDate = date('2023-12-30');
    $result = $conn->query("SELECT order_date FROM order_tb WHERE order_date between '$startDate' and '$endDate'");
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

// // Select using RIGHT join to find values from Orders table, users tables and MENU tables
// SELECT orders.id, prod.id, prod.prodName, orders.quantity, orders.price, users.id, users.fname, users.lname,
// orders.total_values, orders.order_date, orders.rating 
// FROM food_db.menu_tb as prod
// RIGHT JOIN food_db.order_tb as orders
// ON prod.id = orders.id
// RIGHT JOIN food_db.user_tb as users
// ON users.id = orders.id;

function getOrder($conn) {
    $result = $conn->query('SELECT * FROM order_tb');
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}
$conn->close();
?>