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
$name = $object['name'];
$color = $object['color'];

if ($requestMethod == "POST") {
    $sql="SELECT * FROM category WHERE name='$name'";
    $result=mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    if($count>0){
        $response=((object) ['code'=>'500','message'=>'มีประเภทดังกล่าวในระบบแล้ว!']);
        mysqli_close($conn);
        echo (json_encode($response));
        return;
    }

    $sql = "INSERT INTO category (name, color) VALUES ('$name','$color')";
    $result = mysqli_query($conn,$sql);
    if($result){
        $response = ((object) ['message' => 'เพิ่มข้อมูลสำเร็จ']);
    }else{
        $response = ((object) ['message'=>'SQL เกิดข้อผิดพลาด']);
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
