<?php
/**
// * Created by IntelliJ IDEA.
// * User: 15863
// * Date: 2019/1/15
// * Time: 10:55
// */
    // 1.查询有没有接收到要编辑的id
    if (empty($_GET['id'])) {
        exit('没有接收到要编辑项的id');
    }
    $id = $_GET['id'];

// 2.连接数据库
    $connection = mysqli_connect('localhost', 'root', 'mysql', 'demo');
    if (!$connection) {
        exit('<h1>连接数据库失败</h1>');
    }

// 3.查询数据库
    // 因为 ID 是唯一的 那么找到第一个满足条件的就不用再继续了 limit 1
    $query = mysqli_query($connection, "select * from users where id={$id} limit 1;");
    if (!$query) {
        $GLOBALS['error_message'] = '查询数据失败';
        return;
    }
    // 已经查询到的已有数据
    $user = mysqli_fetch_assoc($query);
    if (!$user) {
        exit("<h1>找不到要查询的数据</h1>");
    }
    function update() {
        // 声明user为全局变量
        global $user;
        if(empty($_POST['name'])) {
            $GLOBALS['error_message'] = '请输入姓名';
            return ;
        }
        if(!(isset($_POST['gender']) && $_POST['gender'] !== '-1')) {
            $GLOBALS['error_message'] = '请选择性别';
            return ;
        }
        if(empty($_POST['birthday'])) {
            $GLOBALS['error_message'] = '请选择出生日期';
            return ;
        }
        //如果有上传的图片并且上传成功
        if(isset($_FILES['avatar']) && $_FILES['avatar']['error']===UPLOAD_ERR_OK){
            // 若用户上传了新头像——>用户希望修改头像
            $ext = pathinfo($_FILES['avatar']['name'],PATHINFO_EXTENSION);
            // iconv()函数第一个参数是原编码格式，第二个参数是需要的编码格式，第三个参数是要修改编码的内容
            $target = 'assets/img/'.uniqid().'.'.iconv('UTF-8','GBK',$ext);
            // move_uploaded_file在Windows中文系统上要求传入的参数如果有中文，必须是GBK编码
            // 切记在接收文件时注意文件名中文的问题，通过iconv函数转换中文编码为GBK编码
            if(!move_uploaded_file($_FILES['avatar']['tmp_name'],$target)) {
                $GLOBALS['error_message'] = '上传图片失败';
                return ;
            }
            $user['avatar'] = $target;
        }
        // 取值
        $user['name'] = $_POST['name'];
        $user['gender'] = $_POST['gender'];
        $user['birthday'] = $_POST['birthday'];
    }
    if($_SERVER['REQUEST_METHOD']==='POST') {
        // 执行函数
        update();
        $edit = mysqli_query($connection,
            "update users set `name`='{$user['name']}',gender='{$user['gender']}',birthday='{$user['birthday']}' where id = 11 limit 1;");
        if(!$edit)
            $GLOBALS['error_message'] = '更新数据失败';
        header('Location:index.php');
    }
// 响应
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
    <h1 class="heading">编辑<?php echo $user['name'] ?></h1>
      <?php if(isset($error_message)):?>
          <div class="alert alert-warning">
              <?php echo $error_message; ?>
          </div>
      <?php endif ?>
    <form action="<?php echo $_SERVER['PHP_SELF'].'?'.'id='.$id; ?>" method="post"
          enctype="multipart/form-data" autocomplete="off">
      <div class="form-group">
        <label for="avatar">头像</label>
<!--文件域不能写默认值，因为取的是本地文件-->
        <input type="file" class="form-control" name="avatar">
      </div>
      <div class="form-group">
        <label for="name">姓名</label>
        <input type="text" class="form-control" name="name" value="<?php echo $user['name']?>">
      </div>
      <div class="form-group">
        <label for="gender">性别</label>
        <select class="form-control" name="gender">
          <option value="-1">请选择性别</option>
            <!--在不严谨时，null与0相等,所以应该使用完全相等-->
          <option value="1" <?php echo $user['gender']==='1' ?' selected':'' ?>>男</option>
          <option value="0" <?php echo $user['gender']==='0' ?' selected':'' ?>>女</option>
        </select>
      </div>
      <div class="form-group">
        <label for="birthday">生日</label>
        <!--date是html5出现的-->
        <input type="date" class="form-control" name="birthday" value="<?php echo $user['birthday']?>">
      </div>
      <button class="btn btn-primary">保存</button>
    </form>
  </main>
</body>
</html>
