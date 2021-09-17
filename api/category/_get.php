<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$name;

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "GET") {
    if (isset($_GET["sp"])) {
        $sp = $_GET["sp"];
        $lp = $_GET["lp"];
    }
    $sql="SELECT * FROM category WHERE view=1";
    $result = mysqli_query($conn,$sql);
    $count = mysqli_num_rows($result);
    $skip = $sp*$lp;

    if($result){
        $sql="SELECT c._id,c.color, c.name,COUNT(CASE WHEN product.category=c._id THEN product.category END) as 'num' FROM category as c, product WHERE product.view=1 AND c.view=1 GROUP BY c.name ORDER BY c._id LIMIT  $skip,$lp";
        $result = mysqli_query($conn, $sql);
        $arr=array();
        while($row=mysqli_fetch_assoc($result)){
            $arr[] = $row;
        }
        $data = (object) ['total_items'=>$count, 'items'=>$arr];
    }
    mysqli_close($conn);
    echo json_encode(($data));
    return ;
}

function execute_data($sql, $show_total='true'){
    global $conn;
    $result = mysqli_query($conn, $sql);
    $arr = array();
    $count = mysqli_num_rows($result);
    while($row=mysqli_fetch_assoc($result)){
        $arr[] = $row;
    }
    $data = (object) ['total_items'=>$count, 'items'=>$arr];
    if($show_total=='false' && $count>0){
        return json_encode($arr[0]);
    }
    mysqli_close($conn);
    return json_encode($data);
}
