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
$_id = $object["_id"];

if ($requestMethod == "PUT") {
    $sql = "UPDATE product SET visit=visit+1 WHERE _id='$_id'";
    $result = mysqli_query($conn,$sql);
    if($result){
        $response = ((object) ['message' => 'แก้ไขข้อมูลสำเร็จ']);
    }else{
        $response = ((object) ['message'=>'SQL เกิดข้อผิดพลาด','sql'=>$sql]);
    }
}else{
    $response = ((object) ['message'=>'Methos is not POST']);
}
mysqli_close($conn);
echo (json_encode($response));

function execute_data($sql, $show_total='true'){
    global $conn;
    $result = mysqli_query($conn, $sql);
    $arr = array();
    $count = mysqli_num_rows($result);
    while($row=mysqli_fetch_assoc($result)){
        $arr[] = $row;
    }
    $data = (object) ['total_items'=>$count, 'items'=>$arr];
    if($show_total=='false' && $count>0){
        return json_encode($arr[0]);
    }
    mysqli_close($conn);
    return json_encode($data);
}
