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
    case 'POST':
        addMenu($conn);
        break;
    case 'PUT':
        updateMenu($conn);
        break;
    case 'DELETE':
        deleteMenu($conn);
        break;
    default:
        die(json_encode(['error' => 'Invalid request method.']));
}

function getMenu($conn) {
    $result = $conn->query('SELECT * FROM menu_tb');
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}
function addMenu($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    $sql = 'INSERT INTO menu_tb (prodName, quantity, price, prodDescr) VALUES (?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sids', $data['prodName'], $data['quantity'], $data['price'], $data['prodDescr']);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Menu added successfully.']);
    } else {
        echo json_encode(['error' => 'Menu addition failed.']);
    }

    $stmt->close();
}

function updateMenu($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $sql = 'UPDATE menu_tb SET prodName = ?, quantity = ?, price = ?, prodDescr = ? WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sidsi', $data['prodName'], $data['quantity'], $data['price'], $data['prodDescr'], $data['id']);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Menu deleted successfully.']);
    } else {
        echo json_encode(['error' => 'Menu deletion failed.']);
    }
 
    $stmt->close();
}

function deleteMenu($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    $sql = 'DELETE FROM menu_tb WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $data['id']);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Menu deleted successfully.']);
    } else {
        echo json_encode(['error' => 'Menu deletion failed.']);
    }

    $stmt->close();
}

$conn->close();
?>