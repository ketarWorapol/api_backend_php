<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$type;

$requestMethod = $_SERVER["REQUEST_METHOD"];


// ถ้าค้นหาแบบมี limit
if ($requestMethod == "GET") {
    if (isset($_GET["type"])) {
        $type = $_GET["type"];
    }

    // ค้นหาสินค้าขายดี
    $sql = "SELECT p._id,p.content, p.name,p.image, SUM(od.qty) as popular FROM product as p, orders_detail as od, orders as o WHERE p._id = od.product AND o._id = od.orders AND o.status>=2 GROUP BY p._id ORDER BY SUM(od.qty) DESC LIMIT 3";
    
    // ถ้า type = visit ค้นหาสินค้าที่คนชมเยอะ
    if($type=="visit"){
        $sql="SELECT * FROM product as p WHERE p.view=1 ORDER BY p.visit DESC LIMIT 3";
    }
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
    $data = (object) ['total_items' => $count, 'items' => $arr];
    if ($show_total == 'false' && $count > 0) {
        return json_encode($arr[0]);
    }
    mysqli_close($conn);
    return json_encode($data);
}
