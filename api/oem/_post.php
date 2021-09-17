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
$firstname = $object['firstname'];
$lastname = $object["lastname"];
$phone = $object["phone"];
$text = $object["text"];
$address = $object["address"];

if ($requestMethod == "POST") {
    $sql = "INSERT INTO oem (firstname,lastname,phone,line,address) VALUES ('$firstname','$lastname','$phone','$text','$address')";
    $result = mysqli_query($conn,$sql);
    if($result){
        $response = ((object) ['message' => 'เพิ่มข้อมูลสำเร็จ']);
    }else{
        $response = ((object) ['message'=>'SQL เกิดข้อผิดพลาด','sql'=>$sql]);
    }
}else{
    $response = ((object) ['message'=>'Methos is not POST']);
}
mysqli_close($conn);
echo (json_encode($response));
