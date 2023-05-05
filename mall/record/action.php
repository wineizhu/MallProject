<?php
    require_once ("../conf/conn.php");
    $a =$_GET["a"];
    $limit = isset($_GET["limit"])?$_GET["limit"]:1000;
    $page = isset($_GET["page"])?$_GET["page"]:1;

    $code = 0;
    $datas = array();
    $msg = "";
    $count = 0;

    $start_num = ($page-1)*$limit;
    if($a == "view"){
        $str = "select * from $tb_record order by id desc limit $start_num,$limit";
        $strcount = "select count(*) from $tb_record";
        $res = $conn->query($str);
        while($row = $res->fetch_array()){
            $good_id = $row["good_id"];
            $str1 = "select brand,name from $tb_goods where id=$good_id";
            $row["brand"] = $conn->query($str1)->fetch_array()[0];
            $row["name"] = $conn->query($str1)->fetch_array()[1];
            $datas[] = $row;
        }
        $count = $conn->query($strcount)->fetch_array()[0];
    }else if($a == "search"){
        $date = isset($_GET["date"])?$_GET["date"]:"";
        $action = isset($_GET["action"])?$_GET["action"]:"";
        if($date != "" and $action != ""){
            $str = "select * from $tb_record where dates='$date' and action='$action' order by id desc limit $start_num,$limit";
            $strcount = "select count(*) from $tb_record where dates='$date' and action='$action'";
        }else if($date){
            $str = "select * from $tb_record where dates='$date' order by id desc limit $start_num,$limit";
            $strcount = "select count(*) from $tb_record where dates='$date'";
        }else if($action){
            $str = "select * from $tb_record where action='$action' order by id desc limit $start_num,$limit";
            $strcount = "select count(*) from $tb_record where action='$action'";
        }else{
            $limit = isset($_GET["limit"])?$_GET["limit"]:1000;
            $page = isset($_GET["page"])?$_GET["page"]:1;
            $start_num = ($page-1)*$limit;
            $str = "select * from $tb_record order by id desc limit $start_num,$limit";
            $strcount = "select count(*) from $tb_record";
        }

        $res = $conn->query($str);
        while($row = $res->fetch_array()){
            $good_id = $row["good_id"];
            $str1 = "select brand,name from $tb_goods where id=$good_id";
            $row["brand"] = $conn->query($str1)->fetch_array()[0];
            $row["name"] = $conn->query($str1)->fetch_array()[1];
            $datas[] = $row;
        }
        $count = $conn->query($strcount)->fetch_array()[0];
    }

    if($msg){
        echo $msg;
    }else{
        $data = array(
            "code"=>$code,
            "msg"=>$msg,
            "data"=>$datas,
            "count"=>$count
        );
        echo json_encode($data);
    }
?>