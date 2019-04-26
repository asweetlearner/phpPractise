<?php
/**
 * Created by IntelliJ IDEA.
 * User: 15863
 * Date: 2018/12/24
 * Time: 15:39
 */
function add_user() {
    // 验证非空（包括文本域、文件域、性别选择）
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
    //验证头像文件
    if(empty($_FILES['avatar'])){
        $GLOBALS['error_message'] = '请上传头像';
        return ;
    }
    $ext = pathinfo($_FILES['avatar']['name'],PATHINFO_EXTENSION);
    // 图片另存文件路径要设置正确，确保路径存在
    $target = 'assets/img/'.uniqid().'.'.$ext;
    if(!move_uploaded_file($_FILES['avatar']['tmp_name'],$target)){
        $GLOBALS['error_message'] = '上传头像失败';
        return ;
    }
    // 取值
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $avatar = $target;

    // 保存
    // 1.建立连接
    $conn = mysqli_connect('localhost','root','mysql','demo');
    if(!$conn) {
        $GLOBALS['error_message'] = '连接数据库失败';
        return ;
    }
    // 2.开始查询
    $query = mysqli_query($conn,"insert into users values (null,'{$name}',$gender,'{$birthday}','{$avatar}');");
    if(!$query){
        $GLOBALS['error_message'] = '查询过程失败';
        return ;
    }
    $affected_rows = mysqli_affected_rows($conn);
    if($affected_rows!==1) {
        $GLOBALS['error_message'] = '插入数据失败';
        return ;
    }
    // 响应:使用header函数设置Location，跳转到主页面
    header('Location:index.php');
}

if($_SERVER['REQUEST_METHOD']==='POST') {
    // ADD
    add_user();
}
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
    <h1 class="heading">添加用户</h1>
      <?php if(isset($error_message)):?>
          <div class="alert alert-warning">
              <?php echo $error_message; ?>
          </div>
      <?php endif ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"
          enctype="multipart/form-data" autocomplete="off">
      <div class="form-group">
        <label for="avatar">头像</label>
        <input type="file" class="form-control" name="avatar">
      </div>
      <div class="form-group">
        <label for="name">姓名</label>
        <input type="text" class="form-control" name="name">
      </div>
      <div class="form-group">
        <label for="gender">性别</label>
        <select class="form-control" name="gender">
          <option value="-1">请选择性别</option>
          <option value="1">男</option>
          <option value="0">女</option>
        </select>
      </div>
      <div class="form-group">
        <label for="birthday">生日</label>
        <!--date是html5出现的-->
        <input type="date" class="form-control" name="birthday">
      </div>
      <button class="btn btn-primary">保存</button>
    </form>
  </main>
</body>
</html>
