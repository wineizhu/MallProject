<?php
require_once("../conf/conn.php");
$a = isset($_GET["a"])?$_GET["a"]:"";
$limit = isset($_GET["limit"])?$_GET["limit"]:10;
$page = isset($_GET["page"])?$_GET["page"]:1;

$code = 0;
$msg = "";
$count = 0;
$datas = array();



function cksn($str){
    $str = trim($str);
    if(empty($str)){return "R00000001";}
    $nums = array('1','2','3','4','5','6','7','8','9','0');
    $res = '';
    for($i=0;$i<strlen($str);$i++){
        if(in_array($str[$i],$nums)){
            $res.=$str[$i];
        }
    }
    $res = $res+1;
    if($res<10){
        $ress = "R0000000$res";
    }else if($res<100){
        $ress = "R000000$res";
    }else if($res<1000){
        $ress = "R00000$res";
    }else if($res<10000){
        $ress = "R0000$res";
    }else if($res<100000){
        $ress = "R000$res";
    }
    return $ress;
}



if($a == "add"){
    $supplier = $_POST["supplier"];
    $date = $_POST["date"];
    $receiver = $_POST["receiver"];
    $list_num = $_POST["list_num"];
    $action = "receive";
    $xx = 0;
    $yy = 0;

    $str1 = "select id,sn from $tb_receive where not isnull(sn) order by id desc limit 1";
    if($res1 = $conn->query($str1)){
        if($row1 = $res1->fetch_array()){
            if(isset($row1['sn'])){
                $sn = cksn($row1['sn']);
            }else{
                $sn = "R00000001";
            }
        }else{
            $sn = "R00000001";
        }
    }
    

    $x = isset($_GET["x"])?$_GET["x"]:50;


    $str = "insert into $tb_receive (sn,supplier,dates,receiver,list_num) values('$sn','$supplier','$date','$receiver','$list_num')";
    $res= $conn->query($str);
    for($i=0;$i<$x;$i++){
        if(isset($_POST["name$i"])){
            $xx++;
            $brand = $_POST["brand$i"];
            $name = $_POST["name$i"];
            $num = $_POST["num$i"];

            $str5 = "select count(*) from $tb_goods where name='$name' and brand='$brand' and supplier='$supplier'";
            $act = $conn->query($str5)->fetch_array()[0];
            if($act == 0){
                $str6 = "insert into $tb_goods (name,brand,supplier,num) values('$name','$brand','$supplier',$num)";
                $res6 = $conn->query($str6);
            }else{
                $str6 = "select num from $tb_goods where brand='$brand' and name='$name' and supplier='$supplier'";
                $newnum = $conn->query($str6)->fetch_array()[0] + $num;
                $str7 = "update $tb_goods set num=$newnum where brand='$brand' and name='$name' and supplier='$supplier'";
                $res7 = $conn->query($str7);
            }
            $str2 = "insert into $tb_detail(sn,brand,name,num) values('$sn','$brand','$name',$num)";
            $res2 = $conn->query($str2);
            $str3 = "select id from $tb_goods where brand='$brand' and name='$name'";
            $good_id = $conn->query($str3)->fetch_array()[0];
            $detail = "收取".$num.'个'.$brand.'-'.$name;
            $dates = date("Y-m-d");
            $str4 = "insert into $tb_record (good_id,num,action,detail,dates) values($good_id,$num,'$action','$detail','$dates')";
            $res4 = $conn->query($str4);
            if($res2){
                $yy++;
            }
        }
    }
    if($xx == $yy){
        $msg = "success";
    }else{
        $msg = "fail";
    }
    
    

}else if($a == "get_supplier"){
    $str = "select supplier from $tb_goods group by supplier";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $datas[] = $row;
    }
}else if($a =="view"){
    $sn = isset($_GET["sn"])?$_GET["sn"]:"";
    $start_num = ($page-1)*$limit;
    if($sn){
        $str = "select * from $tb_receive where sn='$sn'";
        $row = $conn->query($str)->fetch_array();
        $datas[] = $row;
    }else{
        $str = "select * from $tb_receive order by id limit $start_num,$limit";
        $strcount = "select count(*) from $tb_receive";
        $res = $conn->query($str);
        while($row = $res->fetch_array()){
            $datas[] = $row;
        }
        $count = $conn->query($strcount)->fetch_array()[0];
    } 
}else if($a == "search"){
    $date = $_GET["date"];
    $str = "select * from $tb_receive where dates='$date'";
    $strcount = "select count(*) from $tb_receive where dates='$date'";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $datas[] = $row;
    }
    $count = $conn->query($strcount)->fetch_array()[0];



}else if($a == "tree"){
    $str = "select dates from $tb_receive group by dates";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
 
        $date = $row["dates"];
        if($date){
            $str2 = "select id,sn from $tb_receive where dates='$date' order by id desc";
            $res2 = $conn->query($str2);
            while($row2 = $res2->fetch_array()){
                $children[] = array(
                    "title"=>$row2["sn"],
                    "id"=>$row2["id"]
                );
            }
            $rows[] = array(
                "title"=>$row["dates"],
                "children" => $children
            );
            $children=array();
            $datas = $rows;
          
        }
       
    }
}else if($a == 'get_name'){
    $brand = $_GET["brand"];
    $str = "select name from $tb_goods where brand='$brand'";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $datas[] = $row;
    }
}else if($a == 'get_brand'){
    $str = "select brand from $tb_goods group by brand";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $datas[] = $row;
    }
}else if($a == "get_info"){
    // $msg = "success";
    $sn = $_GET["sn"];
    $str = "select * from $tb_receive where sn='$sn'";
    $row = $conn->query($str)->fetch_array();
    $datas[] = $row;
    
}else if($a == "get_detail"){
    $sn = $_GET["sn"];
    $str = "select * from $tb_detail where sn='$sn'";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
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
// mysql_close($conn);

?>