<?php
include("../../connect.php");

$sp = 0;
$lp = 1;

$sql = "";
$name;

$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestPayload = file_get_contents("php://input");
$object = json_decode($requestPayload, true);

if ($requestMethod == "POST") {
    $username = $object["username"];
    $email = $object["email"];
    $sql = "SELECT * FROM user WHERE username='$username' AND email='$email'";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($result);
    $_id = $data["_id"];
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        $reset_password = RandomString();
        $dumb = $reset_password;
        $reset_password = md5($dumb);
        $sql = "UPDATE user SET password='$reset_password' WHERE _id=$_id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $response = ((object) ['message' => 'แก้ไขข้อมูลรหัสผ่านสำเร็จ', 'sql' => $sql, 'new_password' => $dumb]);

            $strTo = $email;
            $strSubject = "Your Account information username and password.";
            $strHeader = "Content-type: text/html; charset=windows-874\n"; // or UTF-8 //
            $strHeader .= "From: Snowmilk\nReply-To: " . $data["firstname"] . " " . $data["lastname"];
            $strMessage = "";
            $strMessage .= "Welcome : " . $data["firstname"] . " " . $data["lastname"] . "<br>";
            $strMessage .= "Username : " . $data["username"] . "<br>";
            $strMessage .= "Password : " . $dumb . "<br>";
            $strMessage .= "=================================<br>";
            $strMessage .= "Snowmilk.com<br>";
            $flgSend = mail($strTo, $strSubject, $strMessage, $strHeader);
        } else {
            $response = ((object) ['message' => 'SQL เกิดข้อผิดพลาด', 'sql' => $sql]);
        }
    } else {
        $response = ((object) ['message' => 'SQL เกิดข้อผิดพลาด', 'sql' => $sql]);
    }
} else {
    $response = ((object) ['message' => 'Methos is not POST']);
}
mysqli_close($conn);
echo (json_encode($response));


function RandomString()
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 10; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}
