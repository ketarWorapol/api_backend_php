<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$name;

$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestPayload = file_get_contents("php://input");
$object = json_decode($requestPayload, true);

if ($requestMethod == "POST") {
    $username=$object["username"];
    $password=md5($object["password"]);
    $new_password=md5($object["new_password"]);


    $sql="SELECT * FROM user WHERE username='$username' AND password='$password'";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_num_rows($result);
    if($row==0){
        $response = ((object) ['message' => 'ไม่พบข้อมูล','sql'=>$sql]);
    }else{
        $sql = "UPDATE user SET username='$username', password='$new_password' WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn,$sql);
        if ($result) {
            $response = ((object) ['message' => 'แก้ไขข้อมูลรหัสผ่านสำเร็จ','sql'=>$sql]);
        } else {
            $response = ((object) ['message' => 'SQL เกิดข้อผิดพลาด','sql'=>$sql]);
        }
    }
} else {
    $response = ((object) ['message' => 'Methos is not POST']);
}
mysqli_close($conn);
echo (json_encode($response));