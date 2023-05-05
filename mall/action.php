<?php

session_start();
require_once("conf/conn.php");
require_once("conf/conf.php");
$a = isset($_GET["a"])?$_GET["a"]:"";
$limit = isset($_GET["limit"])?$_GET["limit"]:30;
$page = isset($_GET["page"])?$_GET["page"]:1;


$code = 0;
$msg = "";
$count = 0;
$datas = array();

if($a == "username"){
    $user = $_SESSION[$this_username];
    // $datas[] = gettype($logs_users);

    if(in_array(strtolower($user),$logs_users)){
        $row['whether'] = '1';
        $datas[] = $row;
        
    }else{
        $row['whether'] = '0';
        $datas[] = $row;
    }
}


if($msg == ""){
    $data = array(
        "code"=>$code,
        "msg" =>$msg,
        "count"=>$count,
        "data" =>$datas
    );
    echo json_encode($data);
}else{
    echo $msg;
}
mysql_close($conn);
?>