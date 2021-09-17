<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$name;

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "GET") {
    $before = $_GET["before"];
    $after = $_GET["after"];
    $sql = "SELECT od._id, od.orders as receipt, o.dor,";
    $sql.=" SUM(CASE WHEN p.category=1 THEN od.price*od.qty END) as overdose,";
    $sql.=" SUM(CASE WHEN p.category=2 THEN od.price*od.qty END) as gelato,";
    $sql.=" SUM(CASE WHEN p.category=1 THEN od.price*od.qty END) + SUM(CASE WHEN p.category=2 THEN od.price*od.qty END) as total";
    $sql.=" FROM orders as o, orders_detail as od, product as p, category as c";
    $sql.=" WHERE p._id = od.product";
    $sql.=" AND o._id = od.orders";
    $sql.=" AND p.category = c._id";
    $sql.=" AND o.status>=2";
    $sql .=" AND o.dor>='$before' AND o.dor<='$after'";
    $sql.=" GROUP BY YEAR(o.dor), MONTH(o.dor), DAY(o.dor)";
    $sql .= " ORDER BY dor";
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
