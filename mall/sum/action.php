<?php
require_once("../conf/conn.php");
$a = isset($_GET["a"])?$_GET["a"]:"";
$limit = isset($_GET["limit"])?$_GET["limit"]:10;
$page = isset($_GET["page"])?$_GET["page"]:1;

$code = 0;
$msg = "";
$count = 0;
$datas = array();


if($a == "get_info"){
    $dates = date("Y-m-d");
    $str = "select count(*) from $tb_record where dates='$dates' and action='receive'";
    $str2 = "select count(*) from $tb_record where dates='$dates' and action='return'";
    $str3 = "select count(*) from $tb_record where dates='$dates' and action='sell'";
    $str1 = "select count(*) from $tb_goods";
    $row["nums"] = $conn->query($str1)->fetch_array()[0];
    $row["receive"] = $conn->query($str)->fetch_array()[0];
    $row["sell"] = $conn->query($str3)->fetch_array()[0];
    $row["back"] = $conn->query($str2)->fetch_array()[0];;
    $datas[] = $row;
}else if($a == "chart"){
    $type = isset($_GET["type"])?$_GET["type"]:"";
    if($type == "all"){
        $str = "select brand,name,num from $tb_goods";
    }else if($type == ""){
        $str = "select brand,name,num from $tb_goods where num<safety";
    }
    $labels = array();
    $nums = array();
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $label = $row[0].'-'.$row[1];
        $num = $row[2];
        $labels[] = $label;
        $nums[] = $num;
    }
    $datas['labels'] = $labels;
    $datas['nums'] = $nums;
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

?>
