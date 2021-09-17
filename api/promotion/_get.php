<?php
include("../../connect.php");

$sql = "";
$name;

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "GET") {
    if (isset($_GET["sp"])) {
        $sp = $_GET["sp"];
        $lp = $_GET["lp"];
    }
    $sql="SELECT * FROM promotion";
    $result = mysqli_query($conn,$sql);
    $count = mysqli_num_rows($result);

    $skip = $sp*$lp;

    if($result){
        $sql="SELECT _id, title, content, image, type, discount, condit, max_discount, dor, code, status FROM promotion ORDER BY _id DESC LIMIT $skip,$lp";
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
