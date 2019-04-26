<?php
include 'calculateAge.php';
// 1.建立连接
$connection = mysqli_connect('127.0.0.1','root','mysql','demo');
// ========================================================================================
// 检测连接是否成功
if(!$connection) {
    exit("<h1>数据库连接失败</h1>");
}
// 2.开始查询
$query = mysqli_query($connection,'select * from users;');
if(!$query) {
    exit("<h1>查询失败</h1>");
}

// 4.关闭连接
// mysqli_close($connection);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>XXX管理系统</title>
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">XXX管理系统</a>
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">用户管理</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">商品管理</a>
      </li>
    </ul>
  </nav>
  <main class="container">
    <h1 class="heading">用户管理 <a class="btn btn-link btn-sm" href="add.php">添加</a></h1>
    <table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>头像</th>
          <th>姓名</th>
          <th>性别</th>
          <th>年龄</th>
          <th class="text-center" width="140">操作</th>
        </tr>
      </thead>
      <tbody>
        <!--3.遍历结果集-->
        <?php while ($item = mysqli_fetch_assoc($query)): ?>
        <tr>
          <th scope="row"><?php echo $item['id'] ?></th>
          <td><img src="<?php echo $item['avatar']; ?>" class="rounded" alt="<?php echo $item['name']; ?>"></td>
          <td><?php echo $item['name']; ?></td>
          <!--取出来的每一项结果是字符串，所以不能用全等，而是用相等-->
          <td><?php echo $item['gender'] == 0 ? '♀' : '♂'; ?></td>
          <td><?php echo age($item['birthday']); ?></td>
          <td class="text-center">
            <a class="btn btn-info btn-sm" href="edit.php?id=<?php echo $item['id'] ?>">编辑</a>
            <a class="btn btn-danger btn-sm" href="delete.php?id=<?php echo $item['id'] ?>">删除</a>
          </td>
        </tr>
        <?php endwhile ?>
      </tbody>
    </table>
    <ul class="pagination justify-content-center">
      <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
    </ul>
  </main>
</body>
</html>
