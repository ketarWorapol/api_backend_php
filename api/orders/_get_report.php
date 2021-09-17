<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$name;

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "GET") {
    $sql="SELECT YEAR(dor) as 'year',SUM(orders.total) as amount FROM orders,user WHERE orders.user = user._id AND orders.status>=2 AND YEAR(dor)=2021 GROUP BY YEAR(dor) ORDER BY MONTH(dor) LIMIT 12";
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
