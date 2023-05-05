<?php
    $servername = "localhost";
    $username = "root";
    $password = "123456";
    $db = "mall";
    // 创建连接
    $conn = new mysqli($servername, $username, $password,$db);
     
    // 检测连接
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }else{
        // echo "连接成功";
    } 
    // echo phpinfo()
    $tb_goods = "goods";
    $tb_detail = "detail";
    $tb_receive = "receive";
    $tb_store = "store";
    $tb_box = "box";
    $tb_record = "record";
    $tb_return = "returns";
    $tb_detail2 = "detail2";
?>