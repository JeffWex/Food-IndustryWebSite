<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
?>
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
            $userData = json_decode(file_get_contents('php://input'), true);
            // if ($userData['user'] === 'A') {
            if ($userData['user']->userType === 'A') {
                addMenu($conn);
            } else{
                saveMenu($conn);
            }
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

    function tmpFunc($conn){
        $data = json_decode(file_get_contents('php://input'), true);
        print_r($data);
    }

    function getMenu($conn) {
        # show the menu from menu_tb
        $result = $conn->query('SELECT * FROM menu_tb');
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
    }


    
    $statusMsg = ''; 

    
    function updateQuantity ($conn, $selected_amount, $prod_id){

        $updateQuery = $conn->prepare("UPDATE menu_tb SET quantity = quantity - ? WHERE id = ?");
        $updateQuery->bind_param("ii", $selected_amount, $prod_id);
        $updateQuery->execute();
        $updateQuery->close();

    }

    function saveMenu($conn){
        $data = json_decode(file_get_contents('php://input'), true);
        $selectedItems = json_decode($data['prod'],true);
        // $user_id = json_decode($data['user']->user_id, true);

        $prod_id = ""; $prodName = ""; $quantity = 0; $user_id = 0; $price = 0; $total_values = 0;$user_fname = ""; $user_lname = "";
        foreach($selectedItems as $item) {
            $prod_id = $item['id']; 
            $quantity = $item['selctAmount']; 
            
            $updateQuery = $conn->prepare("UPDATE menu_tb SET quantity = quantity - ? WHERE id = ?");
            $updateQuery->bind_param("ii", $quantity, $prod_id);
            $updateQuery->execute();
            
            // SELECT query  -- quantity : total
            $selectQuery = $conn->prepare("SELECT prodName, price FROM menu_tb WHERE id = ?");
            $selectQuery->bind_param("i", $prod_id);
            $selectQuery->execute();
            $selectQuery->bind_result($prodName, $price);
            $selectQuery->fetch();
            $selectQuery->close();
        
            $user_id = 1001; // temporary user id
            $total_values = $quantity * $price;

            // // INSERT query
            $insertQuery = $conn->prepare("INSERT INTO order_tb (prod_id, prodName, quantity, price, user_id, user_fname, user_lname, total_values, order_date) SELECT ?, ?, ?, ?, ?, user_tb.fname, user_tb.lname, ?, NOW() FROM user_tb WHERE user_tb.id = ?");
            $insertQuery->bind_param("isididi", $prod_id, $prodName, $quantity, $price, $user_id, $total_values, $user_id);
            $insertQuery->execute();
        }
        
        // $insertQuery->close();
        $conn->close();

    }


    function addMenu($conn) {

        # read the inventory JSON file
        $file = fopen("../data/inventory.json", "r") or die("Unable to open the ");
        $data = fread($file, filesize("../data/inventory.json"));
        fclose($file);
        $data = json_decode($data); 

        # Declare variables 
        $id = null;
        $prodName = null;
        $quantity = null;
        $price = null;
        $food_type = null;
        $prodDescr = null;

        # insert the value to DB
        $insertQuery = $conn->prepare("INSERT INTO menu_tb VALUES(?,?,?,?,?,?)");
        $insertQuery->bind_param("isidss", $id, $prodName, $quantity, $price, $food_type, $prodDescr); //isidss

        foreach($data as $e){
            # set parameters and execute
            $id = $e->id;
            $prodName = $e->product;
            $quantity = $e->amount;
            $price = $e->cost;
            $food_type = null;
            $prodDescr = null;
            
            $insertQuery->execute();
        }
        $insertQuery->close();

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

    function addMenuForC($conn) {
        print_r($_POST["prod"]);
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

    // $conn->close();
?>