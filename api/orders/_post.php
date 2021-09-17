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
$total = $object["total"];
$discount = $object["discount"];

if ($requestMethod == "POST") {
    $sql = "INSERT INTO orders ( user, total,discount) VALUES ('$user','$total','$discount')";
    $dumb=$sql;
    $result = mysqli_query($conn, $sql);
    $sql="SELECT MAX(_id) as orders FROM orders WHERE user='$user'";
    echo execute_data($sql,'false');
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
