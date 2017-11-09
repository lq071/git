<?php


function p($msg=''){
    echo $msg,"<br>";
}

/*
    针对数组格式输出
*/
function dump($data){
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

/*
    针对数组格式输出
*/
function print_array($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/*
    将字符串$str通过$delimiter进行分割,转换为数组
    @param $str   要分割的字符串
    @param string $delimiter   分隔符
    @return array   分割的结果
*/
function str2arr($str,$delimiter=','){
    return explode($delimiter,$str);
}

/*
    通过$join连接符号将$arr中的元素连接起来
    @param $arr   要连接的数组的值
    @param string $join  连接符号
    @return string  连接后的结果
*/
function arr2str($arr,$join=","){
    return implode($join,$arr);
}
/**
 * 连接数据库
 */
function mylink($db = 'itsource'){
    $link = @mysqli_connect('127.0.0.1','root','root',$db) or die('连接失败');
    return $link;
}
/*
 *执行SQl语句
 */
function myquery($link,$sql){
    $result=mysqli_query($link,$sql);
    if ($result===false){
        echo $sql,"语句出错,出错原因:",mysqli_error($link);
        die;
    }
    return $result;
}
