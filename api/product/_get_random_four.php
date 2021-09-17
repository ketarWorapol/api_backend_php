<?php
include("../../connect.php");

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "GET") {
    $_id=$_GET["_id"];
    $sql = "SELECT p._id,p.name,p.taste,p.image FROM product as p WHERE view=1 AND p._id !=$_id ORDER BY RAND() LIMIT 4";
    echo execute_data($sql);
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
    $data = (object) ['total_items' => $count, 'items' => $arr,'sql'=>$sql];
    if ($show_total == 'false' && $count > 0) {
        return json_encode($arr[0]);
    }
    mysqli_close($conn);
    return json_encode($data);
}
