<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$name;
$dumb;

$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestPayload = file_get_contents("php://input");
$object = json_decode($requestPayload, true);

// Set Body
$user = $object["user"];
$product = $object["product"];
$comment = $object["comment"];

if ($requestMethod == "POST") {
    $sql = "INSERT INTO comment ( user,product,comment) VALUES ('$user','$product','$comment')";
    $result = mysqli_query($conn,$sql);
    if($result){
        $response = ((object) ['message' => 'สำเร็จ']);
    }else{
        $response = ((object) ['message' => 'ไม่สำเร็จ']);
    }
    return;
} else {
    $response = ((object) ['message' => 'Methos is not POST']);
}
mysqli_close($conn);
echo (json_encode($response));

function execute_data($sql, $show_total = 'true')
{
    global $conn;
    global $dumb;
    $result = mysqli_query($conn, $sql);
    $arr = array();
    $count = mysqli_num_rows($result);
    while ($row = mysqli_fetch_assoc($result)) {
        $arr[] = $row;
    }
    $data = (object) ['total_items' => $count, 'items' => $arr,'sql'=>$dumb];
    if ($show_total == 'false' && $count > 0) {
        return json_encode($arr[0]);
    }
    mysqli_close($conn);
    return json_encode($data);
}
