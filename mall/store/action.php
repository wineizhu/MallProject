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
    if(empty($str)){return "B00000001";}
    $nums = array('1','2','3','4','5','6','7','8','9','0');
    $res = '';
    for($i=0;$i<strlen($str);$i++){
        if(in_array($str[$i],$nums)){
            $res.=$str[$i];
        }
    }
    $res = $res+1;
    if($res<10){
        $ress = "B0000000$res";
    }else if($res<100){
        $ress = "B000000$res";
    }else if($res<1000){
        $ress = "B00000$res";
    }else if($res<10000){
        $ress = "B0000$res";
    }else if($res<100000){
        $ress = "B000$res";
    }
    return $ress;
}



if($a == "add"){
    $x = $_GET['x'];
    $detail = strtoupper($_POST["detail"]);
    $note = $_POST["note"];
    $str = "select id,box_num from $tb_store order by id desc limit 1";
    $res = $conn->query($str);
    $xx = 0;
    $yy = 0;
    if($row = $res->fetch_array()){
        if($row["box_num"]){
            $box_num = cksn($row["box_num"]);
        }else{
            $box_num = "B00000001";
        }
    }else{
        $box_num = "B00000001";
    }
    
    $part = explode('-',$detail)[0];
    $area = strlen(explode('-',$detail)[1]) == 2?explode('-',$detail)[1]:'0'.explode('-',$detail)[1];
    $level = strlen(explode('-',$detail)[2]) == 2?explode('-',$detail)[2]:'0'.explode('-',$detail)[2];
    $detail = $part.'-'.$area.'-'.$level;
    $str1 = "insert into $tb_store (detail,part,area,level,box_num,note) values('$detail','$part','$area','$level','$box_num','$note')";
    $res1 = $conn->query($str1);

    for($i=0;$i<$x;$i++){
        $brand = isset($_POST["brand$i"])?$_POST["brand$i"]:"";
        $name = isset($_POST["name$i"])?$_POST["name$i"]:"";
        $num = isset($_POST["num$i"])?$_POST["num$i"]:"";
        $str3 = "select id from $tb_goods where brand='$brand' and name='$name'";
        $good_id = $conn->query($str3)->fetch_array()[0];
        if($brand){
            $xx++;
            $str2 = "insert into $tb_box (box_num,good_id,num) values('$box_num',$good_id,$num)";
            $res2 = $conn->query($str2);
            if($res2){
                $yy++;
            }
        }else{
            
        }
    }
    if($xx == $yy){
        $msg = "success";
    }else{
    }

}else if($a =="view"){
    $start_num = ($page-1)*$limit;
    $box_num = isset($_GET["box_num"])?$_GET["box_num"]:"";
    if($box_num){
        $str = "select * from $tb_box where box_num='$box_num' limit $start_num,$limit";
        $strcount = "select count(*) from $tb_box where box_num='$box_num'";
    }else{
        $str = "select * from $tb_box limit $start_num,$limit";
        $strcount = "select count(*) from $tb_box";
    }
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $good_id = $row["good_id"];
        $box_num = $row["box_num"];
        $str2 = "select detail from $tb_store where box_num='$box_num'";
        $str1 = "select * from $tb_goods where id=$good_id";
        $row1 = $conn->query($str1)->fetch_array();
        $row["brand"] = $row1["brand"];
        $row["name"] = $row1["name"];
        $row["detail"] = $conn->query($str2)->fetch_array()[0];
        $datas[] = $row;
    }
    $count = $conn->query($strcount)->fetch_array()[0];

}else if($a == "edit"){
    $action = "edit";
    $box_num = $_POST['box_num'];
    $id = $_POST["id"];
    $detail = $_POST["detail"];
    $brand = $_POST["brand"];
    $name = $_POST["name"];
    $num = $_POST["num"];
    $str2 = "select num from $tb_box where id=$id";
    $old_num = $conn->query($str2)->fetch_array()[0];
    $str = "update $tb_box set num=$num where id=$id";
    $res = $conn->query($str);
    $str1 = "select id from $tb_goods where brand='$brand' and name='$name'";
    $good_id = $conn->query($str1)->fetch_array()[0];
    $dates = date("Y-m-d");
    $detail = $detail."位置上得".$brand.'-'.$name.'数量由'.$old_num.'改为'.$num;
    $str3 = "insert into $tb_record (good_id,action,detail,dates) values($good_id,'$action','$detail','$dates')";
    $res3 = $conn->query($str3);
    if($res){
        $msg = "success";
    }else{
        $msg = "fail";
    }

}else if($a == "search"){
    $brand = isset($_GET["brand"])?$_GET["brand"]:"";
    $name = isset($_GET["name"])?$_GET["name"]:"";
    if($brand !="" and $name !=""){
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
    while ($row = $res->fetch_array()) {
        $good_id = $row["id"];
        // $datas[] = $good_id;
        $str1 = "select * from $tb_box where good_id=$good_id";
        $res1 = $conn->query($str1);
        while ($row1 = $res1->fetch_array()) {
            $box_num = $row1["box_num"];
            $need_id = $row1["good_id"];
            $str3 = "select brand,name from $tb_goods where id=$need_id";
            $row1["brand"] = $conn->query($str3)->fetch_array()[0];
            $row1["name"] = $conn->query($str3)->fetch_array()[1];
            // $datas[] = $box_num;
            $str2 = "select * from $tb_store where box_num='$box_num'";
            $row1["detail"] = $conn->query($str2)->fetch_array()["detail"];
            $datas[] = $row1;
        }
    }
    $count = $conn->query($strcount)->fetch_array()[0];
    // $count = 1;

}else if($a == "search_hwname"){
    $list = array();
    $hw_name = $_POST["hw_name"];
    if($hw_name == ''){
        $str6 = "select * from $tb_store";
        $res6 = mysql_query($str6);
        while($row6 = mysql_fetch_array($res6)){
            $datas[] = $row6;
        }
    }else{
        $search_name = "%".$hw_name."%";
        $str = "select * from $tb_store where hw_name like '$search_name'";
        $res = mysql_query($str);
        // $datas[] = $str;
        while($row = mysql_fetch_array($res)){
            // $id = $row[0]+0;
            $pid = $row["pid"]+0;
            Array_push($list,$pid);
            $str2 = "select * from $tb_store where id=$pid";
            $res2 = mysql_query($str2);
            $row2 = mysql_fetch_array($res2);
            $datas[] = $row2;
            $datas[] = $row;
            
        }
    }
    

}else if($a == "tree"){
    $str = "select part from $tb_store group by part";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $part = $row["part"];
        if($part){
            $str1 = "select area from $tb_store where part='$part' group by area";
            $res1 = $conn->query($str1);
            while ($row1=$res1->fetch_array()) {
                $area = $row1["area"];
                if($area){
                    $str3 = "select level from $tb_store where part='$part' and area='$area' group by level";
                    $res3 = $conn->query($str3);
                    while ($row3 = $res3->fetch_array()) {
                        $level = $row3["level"];
                        if($level){
                            $str4 = "select id,box_num from $tb_store where part='$part' and area='$area' and level='$level'";
                            $res4 = $conn->query($str4);
                            while ($row4 = $res4->fetch_array()) {
                                $children3[] = array(
                                    "title"=>$row4["box_num"],
                                    "id"=>$row4["id"]
                                );
                            }
                            $children2[] = array(
                                "title"=>$level,
                                "children"=>$children3
                            );
                            $children3 = array();
                        }
                    }
                    $children1[] = array(
                        "title"=>$area,
                        "children"=>$children2
                    );
                    $children2 = array();
                }
            }
            $rows[] = array(
                "title"=>$part,
                "children"=>$children1
            );
            $children1 = array();

            $datas = $rows;
        }
    }
    
}else if($a == "get_brand"){
    $str = "select brand from $tb_goods group by brand";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $datas[] = $row;
    }
}else if($a == "get_name"){
    $brand = $_GET["brand"];
    $str = "select name from $tb_goods where brand='$brand'";
    $res = $conn->query($str);
    while($row = $res->fetch_array()){
        $datas[] = $row;
    }
}else if($a == "sell"){
    $action = "sell";
    $id = $_POST["id"];
    $sell_num = $_POST["num"];
    $date = date('Y-m-d');
    $str = "select good_id,num from $tb_box where id=$id";
    $row = $conn->query($str)->fetch_array();
    $box_new_num = $row[1] - $sell_num;
    if($row[1] == $sell_num){
        $str5 = "delete from $tb_box where id=$id";
        $res5 = $conn->query($str5);
    }
    $good_id = $conn->query($str)->fetch_array()[0];
    $str1 = "select supplier,brand,name from $tb_goods where id=$good_id";
    $row1 = $conn->query($str1)->fetch_array();

    $detail = "出售了".$sell_num."个".$row1[0].'-'.$row1[1].'-'.$row1[2];

    $str2 = "insert into $tb_record (good_id,num,action,detail,dates) values($good_id,$sell_num,'$action','$detail','$date')";
    $res2 = $conn->query($str2);

    $str3 = "select num from $tb_goods where id=$good_id";
    $new_num = $conn->query($str3)->fetch_array()[0] - $sell_num;
    
    $str4 = "update $tb_goods set num=$new_num where id=$good_id";
    $res4 = $conn->query($str4);

    $str5 = "update $tb_box set num=$box_new_num where id=$id";
    $res5 = $conn->query($str5);
    
    
    if($res2){
        $msg = "success";
    }else{
        $msg = "fail";
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