<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$name;


$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestPayload = file_get_contents("php://input");
$object = json_decode($requestPayload, true);

// Set Body
$product = $object["product"];
$user = $object["user"];

if ($requestMethod == "POST") {
    $sql = "INSERT INTO cart (product, user) VALUES ('$product','$user')";
    $result = mysqli_query($conn, $sql);
    if($result){
        $response = ((object) ['message' => 'เพิ่มข้อมูลสำเร็จ']);
    }else{
        $response = ((object) ['message' => 'SQL เกิดข้อผิดพลาด']);
    }
} else {
    $response = ((object) ['message' => 'Methos is not POST']);
}
mysqli_close($conn);
echo (json_encode($response));