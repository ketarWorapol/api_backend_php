<?php
include("../../connect.php");

$sp = 0;
$lp = 0;

$sql = "";

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "GET") {
    $user=$_GET["user"];
    $sql = "SELECT COUNT(CASE WHEN user._id=cart.user THEN cart._id END) as cart FROM user,cart WHERE user._id='$user'";
    echo execute_data($sql,"false");
    return;
}

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