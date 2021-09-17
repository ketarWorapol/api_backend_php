<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$name;

$requestMethod = $_SERVER["REQUEST_METHOD"];

// ถ้าค้นหาคนเดียว
if(isset($_GET["_id"])){
    $_id = $_GET["_id"];
    $sql="SELECT o._id, o.dor,o.supplies,o.total, p.name,u.name as user, od.price, od.qty, od.price * od.qty as amount FROM orders as o, orders_detail as od, user as u , product as p WHERE o._id = od.orders AND o.user = u._id AND od.product = p._id AND o._id = $_id";
    echo execute_data($sql);
    return;
}else{
    // ถ้าค้นหาแบบมี limit
    if ($requestMethod == "GET") {
        if (isset($_GET["sp"])) {
            $sp = $_GET["sp"];
            $lp = $_GET["lp"];
        }
        $sql = "SELECT * FROM orders";
        $result = mysqli_query($conn, $sql);
        $skip = $sp * $lp;
    
        if(isset($_GET["search"])){
            $before = $_GET["before"];
            $after = $_GET["after"];
        }
    
    
        if ($result) {
            $sql = "SELECT orders._id, dor, status, name as user,supplies, total FROM orders,user";
            $sql .= " WHERE orders.user = user._id";
            if (isset($_GET["search"]))
                $sql .= " AND dor>='$before' AND dor<='$after'";
    
            $result = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($result);
            $sql .= " ORDER BY dor DESC LIMIT $skip,$lp";
            $result = mysqli_query($conn, $sql);
            $arr = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $arr[] = $row;
            }
            $data = (object) ['total_items' => $count, 'items' => $arr,'sql'=>$sql];
        }
        mysqli_close($conn);
        echo json_encode(($data));
        return;
    }
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
