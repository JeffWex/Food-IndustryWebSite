<?php
header("Access-Control-Allow-Origin: *");
include("./connect.php");

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection error.']));
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    switch ($_POST['req']) {
        case 'login':
            $logCmd = "SELECT * FROM user_tb WHERE email='" . $_POST["email"] . "'";
            $result = $conn->query($logCmd);
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if ($_POST["password"] == $user["password"]) {
                    session_start();
                    $_SESSION["user"] = $user;
                    $_SESSION["fname"] = $user["fname"];
                    $_SESSION["lname"] = $user["lname"];
                    $_SESSION["user_type"] = $user["user_type"];
                    $response = [
                        "id" => $user["id"],
                        "fname" => $user["fname"],
                        "lname" => $user["lname"],
                        "user_type" => $user["user_type"]
                    ];
                    echo json_encode($response);
                } else {
                    http_response_code(400);
                    echo ("Invalid Email/Password");
                }
            } else {
                http_response_code(400);
                echo ("Invalid Email/Password");
            }
            break;
        case 'signup':
            $insertCmd = $conn->prepare("INSERT INTO user_tb (fname,lname,mobile,email,password,user_type) VALUES (?,?,?,?,?,?)");
            $insertCmd->bind_param("ssisss", $_POST["fname"], $_POST["lname"], $_POST["mobile"], $_POST["email"], $_POST["password"], $_POST["user_type"]);
            $insertCmd->execute();
            echo "Registration success";
            $insertCmd->close();
            $conn->close();
            break;
        default:
            die(json_encode(['error' => 'Invalid request method.']));
    }
}
?>
