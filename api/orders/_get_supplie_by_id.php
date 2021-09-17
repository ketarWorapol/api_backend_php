<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$name;

$requestMethod = $_SERVER["REQUEST_METHOD"];

// ถ้าค้นหาคนเดียว
if(isset($_GET["orders"])){
    $orders = $_GET["orders"];
    $sql="SELECT u.name, a.addr, a.subdis, a.dis, a.prov, a.post FROM user as u, orders as o, address as a WHERE u._id = o.user AND o._id = a.orders AND o._id = $orders";
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
