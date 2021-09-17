<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$name;

$requestMethod = $_SERVER["REQUEST_METHOD"];

// ถ้าค้นหาคนเดียว
if (isset($_GET["_id"])) {
    $_id = $_GET["_id"];
    $sql = "SELECT p.image,p.name,od.price,od.qty,c.name as category FROM product as p, orders_detail as od,category as c WHERE od.product = p._id AND p.category = c._id AND od.orders=$_id";
    echo execute_data($sql);
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
    $data = (object) ['total_items' => $count, 'items' => $arr, 'sql' => $sql];
    if ($show_total == 'false' && $count > 0) {
        return json_encode($arr[0]);
    }
    mysqli_close($conn);
    return json_encode($data);
}
