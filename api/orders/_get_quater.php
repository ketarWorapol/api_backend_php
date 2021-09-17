<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$name;

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "GET") {
    $sql="SELECT ";
    $sql.="SUM(CASE WHEN MONTH(dor)=1 THEN orders.total END) as 'Jan',";
    $sql.="SUM(CASE WHEN MONTH(dor)=2 THEN orders.total END) as 'Feb',";
    $sql.="SUM(CASE WHEN MONTH(dor)=3 THEN orders.total END) as 'Mar',";
    $sql.="SUM(CASE WHEN MONTH(dor)=4 THEN orders.total END) as 'Apr',";
    $sql.="SUM(CASE WHEN MONTH(dor)=5 THEN orders.total END) as 'May',";
    $sql.="SUM(CASE WHEN MONTH(dor)=6 THEN orders.total END) as 'Jun',";
    $sql.="SUM(CASE WHEN MONTH(dor)=7 THEN orders.total END) as 'Jul',";
    $sql.="SUM(CASE WHEN MONTH(dor)=8 THEN orders.total END) as 'Aug',";
    $sql.="SUM(CASE WHEN MONTH(dor)=9 THEN orders.total END) as 'Sep',";
    $sql.="SUM(CASE WHEN MONTH(dor)=10 THEN orders.total END) as 'Oct',";
    $sql.="SUM(CASE WHEN MONTH(dor)=11 THEN orders.total END) as 'Nov',";
    $sql.="SUM(CASE WHEN MONTH(dor)=12 THEN orders.total END) as 'Dec'";
    $sql.=" FROM orders,user";
    $sql.=" WHERE orders.user = user._id AND orders.status>=2 AND YEAR(dor)=2021 ORDER BY MONTH(dor) LIMIT 12";
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
