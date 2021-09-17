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
$username = $object["username"];
$password = md5($object["password"]);
$new_password = md5($object["new_password"]);

if ($requestMethod == "PUT") {
    $sql = "UPDATE user SET username='$username', password='$new_password' WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn,$sql);
    if ($result) {
        $response = ((object) ['message' => 'แก้ไขข้อมูลรหัสผ่านสำเร็จ','sql'=>$sql]);
    } else {
        $response = ((object) ['message' => 'SQL เกิดข้อผิดพลาด']);
    }
} else {
    $response = ((object) ['message' => 'Methos is not POST']);
}
mysqli_close($conn);
echo (json_encode($response));