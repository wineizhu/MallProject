<?php
$dir        = "../pics/";

//初始化返回数组
$datestr = date("YmdHis");

$file_info  = $_FILES['file'];
$file_error = $file_info['error'];
$randstr = getRandStr(5);
$file = $dir . $datestr . "_" . $randstr . "_" . $_FILES["file"]["name"];
$arr = array('code' => 0,
         'msg'  => '',
         'data' => array('src' => $file));
if (!file_exists($file)) {
         if ($file_error == 0) {
                   if (move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
                            $arr['msg'] = "上传成功";
                   } else {
                            $arr['msg'] = "上传失败";
                   }
         } else {
                   switch ($file_error) {
                            case 1:
                                     $arr['msg'] = '上传文件超过了PHP配置文件中upload_max_filesize选项的值';
                                     break;
                            case 2:
                                     $arr['msg'] = '超过了表单max_file_size限制的大小';
                                     break;
                            case 3:
                                     $arr['msg'] = '文件部分被上传';
                                     break;
                            case 4:
                                     $arr['msg'] = '没有选择上传文件';
                                     break;
                            case 6:
                                     $arr['msg'] = '没有找到临时文件';
                                     break;
                            case 7:
                            case 8:
                                     $arr['msg'] = '系统错误';
                                     break;
                   }
         }
} else {
         $arr['code'] = "1";
         $arr['msg']  = "当前目录中，文件" . $file . "已存在";
}

$res = json_encode($arr);

die($res);



function GetRandStr($length){
    $str='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $len=strlen($str)-1;
    $randstr='';
    for($i=0;$i<$length;$i++){
    $num=mt_rand(0,$len);
    $randstr .= $str[$num];
    }
    return $randstr;
}

?>
