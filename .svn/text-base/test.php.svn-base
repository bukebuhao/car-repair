<?php
header("Content-type: text/html; charset=UTF-8");

$enCodeFile = "newfile.php";
$lines = file($enCodeFile);
 
//第一次base64解密
$str="";
if(preg_match("/O0O0000O0\('.*'\)/",$lines[1],$y)){
    $str = str_replace("O0O0000O0('","",$y[0]);
    $str = str_replace("')","",$str);
    $str = base64_decode($str);
}
 
//第一次base64解密后的内容中查找密钥
$key="";
if(preg_match("/\),'.*',/",$str,$k)){
    $key = str_replace("),'","",$k[0]);
    $key = str_replace("',","",$key);
}
 
//查找要截取字符串长度
$len = "";
if(preg_match("/,\d*\),/",$str,$k)){
    $len = str_replace("),","",$k[0]);
    $len = str_replace(",","",$len);
}
 
//截取文件加密后的密文
$source = substr($lines[2],$len);
 
//直接还原密文输出
file_put_contents('./tmpglobal.php', "<?php\n".base64_decode(strtr($source,$key,'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/')));

 