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
$orders=$_GET["_id"];

if ($requestMethod == "POST") {
    $round = count($object);
    for($i=0;$i<$round;$i++){
        $product=$object[$i]['product'];
        $price=$object[$i]['price'];
        $qty=$object[$i]['qty'];

        $sql="INSERT INTO orders_detail (orders, product, price, qty) VALUES ($orders,$product,$price,$qty)";
        $result = mysqli_query($conn,$sql);
        if($result && $i==$round-1){
            $response = ((object) ['message'=> 'เพิ่มข้อมูลสำเร็จ','sql'=>$sql]);
        }else{
            $response =((object) ['message'=>'SQL เกิดข้อผิดพลาด','sql'=>$sql]);
        }
    }
} else {
    $response = ((object) ['message' => 'Methos is not POST']);
}
mysqli_close($conn);
echo (json_encode($response));