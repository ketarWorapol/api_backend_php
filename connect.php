<?php
    $servername = "localhost";
    // $username = "root";
    // $password = "";
    // $database = "snowmilk";
    $username = "root";
    $password = "";
    $database = "sql_injection";

    $conn = new mysqli($servername, $username, $password, $database);
    mysqli_set_charset($conn, 'utf8');

    if($conn->connect_error){
        die ("connection failed : " . $conn->connect_error);
    }else{
        // echo "ASF";
    }

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Origin, Accept, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
?>