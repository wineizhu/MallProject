<?php
require_once ("../conf/conn.php");
$a = isset($_GET["a"])?$_GET["a"]:"";
$limit = isset($_GET["limit"])?$_GET["limit"]:10;
$page = isset($_GET["page"])?$_GET["page"]:1;

$code = 0;
$msg = "";
$count = 0;
$datas = array();

$start_num = ($page-1)*$limit;

if($a == "view"){
    $str = "select * from $tb_goods limit $start_num,$limit";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $datas[] = $row;
    }
    $strcount = "select count(*) from $tb_goods";
    $count = $conn->query($strcount)->fetch_array()[0];
}else if($a == "search"){
    $brand = isset($_GET["brand"])?$_GET["brand"]:"";
    $name = isset($_GET["name"])?$_GET["name"]:"";
    if($brand && $name){
        $str = "select * from $tb_goods where brand='$brand' and name like '%$name%'";
        $strcount = "select count(*) from $tb_goods where brand='$brand' and name like '%$name%'";
    }else if($brand){
        $str = "select * from $tb_goods where brand='$brand'";
        $strcount = "select count(*) from $tb_goods where brand='$brand'";
    }else if($name){
        $str = "select * from $tb_goods where name like '%$name%'";
        $strcount = "select count(*) from $tb_goods where name like '%$name%'";
    }else{
        $str = "select * from $tb_goods";
        $strcount = "select count(*) from $tb_goods";
    }
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $datas[] = $row;
    }
    $count = $conn->query($strcount)->fetch_array()[0];
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
mysqli_close($conn);

?>
