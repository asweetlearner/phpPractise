<?php
// 检测是否接收到要删除的id参数
if(empty($_GET['id'])) {
    exit('没有接收到需要删除项的id');
}
$id = $_GET['id'];

// 1.建立连接
$conn = mysqli_connect('127.0.0.1','root','mysql','demo');
// ========================================================================================
// 检测连接是否成功
if(!$conn) {
    exit("<h1>数据库连接失败</h1>");
}

// 2.开始查询
// 单个元素删除
$del = mysqli_query($conn,'delete from users where id='. $id .';');

// 批量删除
//$del = mysqli_query($conn,'delete from users where id in ('. $id .');');

if(!$del) {
    exit("<h1>查询失败</h1>");}

// 查询受影响的行数
$affected_rows = mysqli_affected_rows($conn);
if($affected_rows <= 0) {
   exit('<h1>删除失败</h1>');
}
header('Location: index.php');