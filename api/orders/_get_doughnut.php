<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$name;

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "GET") {
    $sql = "SELECT SUM(CASE WHEN c.name='Gelato' THEN od.qty END) AS 'gelato',SUM(CASE WHEN c.name='Overdose' THEN od.qty END) AS 'overdose'FROM orders as o, orders_detail as od, product as p, category as c WHERE o._id = od.orders AND od.product = p._id AND c._id = p.category AND YEAR(o.dor)=2021";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $arr = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $arr[] = $row;
    }
    $data = (object) ['total_items' => $count, 'items' => $arr];
    mysqli_close($conn);
    echo json_encode(($data));
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
