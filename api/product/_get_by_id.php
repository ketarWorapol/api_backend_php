<?php
include("../../connect.php");

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "GET") {
    $_id=$_GET["_id"];
    $sql = "SELECT p._id,p.name,c.name as category,p.taste,p.image,p.price, p.content FROM product as p,category as c WHERE p.category = c._id AND p._id='$_id'";
    echo execute_data($sql,'false');
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
