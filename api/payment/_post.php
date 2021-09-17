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
$bank = $object['bank'];
$dor = $object["dor"];
$amount = $object["amount"];
$verify = $object["verify"];
$orders = $object["orders"];

if ($requestMethod == "POST") {
    $sql = "INSERT INTO payment (bank,dor,amount,verify,orders) VALUES ('$bank','$dor','$amount','$verify','$orders')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $sql = "UPDATE orders SET status=1 WHERE _id=$orders";
        $result = mysqli_query($conn, $sql);
        if ($result)
            $response = ((object) ['message' => 'เพิ่มข้อมูลสำเร็จ']);
        else
            $response = ((object) ['message' => 'SQL เกิดข้อผิดพลาด1','sql'=>$sql]);
    } else {
        $response = ((object) ['message' => 'SQL เกิดข้อผิดพลาด2','sql'=>$sql]);
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
