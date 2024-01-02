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
    case 'POST':
        getFilter($conn);
        break;
    default:
        die(json_encode(['error' => 'Invalid request method.']));
}


function getFilter($conn) {

    $data = json_decode(file_get_contents('php://input'), true);


    // $result = $conn->query('SELECT * FROM order_tb');
    // $data = [];

    // while ($row = $result->fetch_assoc()) {
    //     $data[] = $row;
    // }
    // $incialData = new DateTime($data->startDat);
    // $finalData = new DateTime($data->endDate);

    // $startDate = date('$data->startDate');
    // $endDate = date('$data->endDate');
    // create a date object for start and end dates
    foreach($data as $dateString){
        $dates[] = DateTime::createFromFormat('Y-m-d', $dateString);
    }


$startDate = $dates[0];
$endDate = $dates[1];

$formattedStartDate = $startDate->format('Y-m-d');
$formattedEndDate = $endDate->format('Y-m-d');


    
    $result = $conn->query("SELECT * FROM order_tb WHERE order_date between $formattedStartDate and $formattedEndDate ");
    
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // echo json_encode($data);

    echo json_encode($data);
}
// function getFilter($conn) {

//     // $sql = 'INSERT INTO menu_tb (prodName, quantity, price, prodDescr) VALUES (?, ?, ?, ?)';
//     // $stmt = $conn->prepare($sql);
//     // $stmt->bind_param('sids', $data['prodName'], $data['quantity'], $data['price'], $data['prodDescr']);

//     // if ($stmt->execute()) {
//     //     echo json_encode(['message' => 'Menu added successfully.']);
//     // } else {
//     //     echo json_encode(['error' => 'Menu addition failed.']);
//     // }
//     $startDate = date('2023-12-01');
//     $endDate = date('2023-12-30');
//     $result = $conn->query("SELECT * FROM order_tb WHERE order_date between '$startDate' and '$endDate'");
//     $data = [];

//     while ($row = $result->fetch_assoc()) {
//         $data[] = $row;
//     }

//     echo json_encode($data);
// }

$conn->close();
?>