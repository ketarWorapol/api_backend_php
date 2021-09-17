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
$orders = $object["orders"];
$addr = $object["addr"];
$subdis = $object["subdis"];
$dis = $object["dis"];
$prov = $object["prov"];
$post = $object["post"];

if ($requestMethod == "POST") {
    $sql = "INSERT INTO address (orders, addr, subdis, dis, prov, post) VALUES ('$orders','$addr','$subdis','$dis','$prov','$post')";
    $result = mysqli_query($conn, $sql);
    if($result){
        $response = ((object) ['message'=> 'เพิ่มข้อมูลสำเร็จ','sql'=>$sql]);
    }else{
        $response =((object) ['message'=>'SQL เกิดข้อผิดพลาด']);
    }
} else {
    $response = ((object) ['message' => 'Methos is not POST']);
}
mysqli_close($conn);
echo (json_encode($response));

function execute_data($sql, $show_total = 'true')
{
    global $conn;
    $result = mysqli_query($conn, $sql);
    $arr = array();
    $count = mysqli_num_rows($result);
    while ($row = mysqli_fetch_assoc($result)) {
        $arr[] = $row;
    }
    $data = (object) ['total_items' => $count, 'items' => $arr];
    if ($show_total == 'false' && $count > 0) {
        return json_encode($arr[0]);
    }
    mysqli_close($conn);
    return json_encode($data);
}
