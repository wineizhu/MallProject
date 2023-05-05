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

if($a == "add"){
    $x = $_GET["xx"];
    $xx = 0;
    $yy = 0;
    $reason = $_POST["reason"];
    $act_dates = $_POST["dates"];
    $returner = $_POST["returner"];
    $reason = $_POST["reason"];
    $pics = $_POST["pics"];
    $str = "insert into $tb_return (dates,returner,reason,pics) values('$act_dates','$returner','$reason','$pics')";
    $res = $conn->query($str);
    $return_id = $conn->insert_id;
    // $msg = $return_id;
    $action = "return";
    $dates = date('Y-m-d');
    for($i=0;$i<$x;$i++){
        $brand = isset($_POST["brand$i"])?$_POST["brand$i"]:"";
        $supplier = isset($_POST["supplier$i"])?$_POST["supplier$i"]:"";
        $name = isset($_POST["name$i"])?$_POST["name$i"]:"";
        $num = isset($_POST["num$i"])?$_POST["num$i"]:"";
        $detail = "退了".$num.'个'.$supplier.'-'.$brand.'-'.$name;
        
        if($name){
            $xx++;
            $str1 = "select id,num from $tb_goods where brand='$brand' and name='$name' and supplier='$supplier'";
            $row1 = $conn->query($str1)->fetch_array();
            $good_id = $row1[0];
            $str2 = "insert into $tb_detail2 (return_id,good_id,num) values($return_id,$good_id,$num)";
            $res2 = $conn->query($str2);
            $new_num = $row1[1] - $num;
            $str3 = "update $tb_goods set num=$new_num where id=$good_id";
            $res3 = $conn->query($str3);
            $str4 = "insert into $tb_record (good_id,num,action,detail,dates) values($good_id,$num,'$action','$detail','$dates')";
            $res4 = $conn->query($str4);
            if($res2){
                $yy++;
            }
            
        }
    }
    if($xx==$yy){
        $msg = "success";
    }else{
        $msg = "fail";
    }
}else if($a == "search"){
    $date = $_GET["date"];
    if($date){
        $str = "select * from $tb_return where dates='$date'";
        $strcount = "select count(*) from $tb_return where dates='$date'";
        $res = $conn->query($str);
        while($row = $res->fetch_array()){
            $datas[] = $row;
        }
        $count = $conn->query($strcount)->fetch_array()[0];
    }else{
        $str = "select * from $tb_return";
        $strcount = "select count(*) from $tb_return";
        $res = $conn->query($str);
        while($row = $res->fetch_array()){
            $datas[] = $row;
        }
        $count = $conn->query($strcount)->fetch_array()[0];
    }

}else if($a == "get_supplier"){
    $str = "select supplier from $tb_goods group by supplier";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $datas[] = $row;
    }
}else if($a == "get_brand"){
    $supplier = $_GET["supplier"];
    $str = "select brand from $tb_goods where supplier='$supplier' group by brand";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $datas[] = $row;
    }
}else if($a == "get_name"){
    $supplier = $_GET["supplier"];
    $brand = $_GET["brand"];
    $str = "select name from $tb_goods where supplier='$supplier' and brand='$brand' group by name";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $datas[] = $row;
    }
}else if($a == "view"){
    $str = "select * from $tb_return limit $start_num,$limit";
    $strcount = "select count(*) from $tb_return";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $datas[] = $row;
    }
    $count = $conn->query($strcount)->fetch_array()[0];
}else if($a == "search"){
    $date = isset($_GET["date"])?$_GET["date"]:"";
    if($date){
        $str = "select * from $tb_return where dates='$date' limit $start_num,$limit";
        $strcount = "select count(*) from $tb_return where dates='$date'";
        $res = $conn->query($str);
        while($row = $res->fetch_array()){
            $datas[] = $row;
        }
        $count = $conn->query($strcount)->fetch_array()[0];
    }else{

    }
    
}else if($a == "show"){
    $id = isset($_GET["id"])?$_GET["id"]:"";
    if($id){
        $str = "select * from $tb_detail2 where return_id=$id";
        $res = $conn->query($str);
        while($row = $res->fetch_array()){
            $good_id = $row["good_id"];
            $str2 = "select supplier,brand,name from $tb_goods where id=$good_id";
            $row2 = $conn->query($str2)->fetch_array();
            $row["supplier"] = $row2["supplier"];
            $row["brand"] = $row2[1];
            $row["name"] = $row2[2];
            $datas[] = $row;
        }
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

?>
