<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$link = mysqli_connect('localhost', 'lyf', 'root', 'schooldata');
	mysqli_query($link, 'set names utf8');
	if(!$link)
	{
		echo "<script>alert('服务器连接失败！');</script>";
		echo "<script>window.location.href='login.php';</script> ";
	}
	
	$account = $_POST['account'];
	$password = $_POST['password'];
	$loginway = $_POST['loginway'];
	
	if( $loginway == '学生')
	{
		$sql = "SELECT * FROM student WHERE sno='{$account}' and spassword='{$password}'";
		$result = mysqli_query($link, $sql);
		
		if ($result->num_rows === 0) {
			$message = '账号或密码错误！';
		} else {
			setcookie('account', $account, time() + 3600);
			header('Location: student.php');
		}
	}
	
	
	if( $loginway == '教师')
	{
		$sql = "SELECT * FROM teacher WHERE tno='{$account}' and tpassword='{$password}'";
		$result = mysqli_query($link, $sql);
		if ($result->num_rows === 0) {
			$message = '账号或密码错误！';
		} else {
			setcookie('account', $account, time() + 3600);
			header('Location: teacher.php');
		}
	}
	
	
	if( $loginway == '教务员')
	{
		$sql = "SELECT * FROM manager WHERE num='{$account}' and mpassword='{$password}'";
		$result = mysqli_query($link, $sql);
		
		if ($result->num_rows === 0) {
			$message = '账号或密码错误！';
		} else {
			setcookie('account', $account, time() + 3600);
			header('Location: manager.php');
		}
	}
	mysqli_close($link);
}
?>
<html>
<head>
  <meta charset="UTF-8">
  <title>登录</title>
  <link rel='stylesheet' href='./css/earn2.css' />
</head>
<body id='page-login'>
	<header style="text-align:center; font-size: 50px; color: khaki;">学生选课管理系统</header>
    <div id='login-form-wrapper'>
    
    <form id='login-form' method='POST'>
	<br/>
	  <div style="text-align:center; color: red;"><?php if (isset($message)) echo $message;?> </div>
      <input id='username' name='account' value='' />
      <input id='password' name='password' type='password' />
      <input id='submit' type='submit' value='' />
	  <div id="select">
		  <input type="radio" name="loginway" value="教务员" checked>教务员
		  <input type="radio" name="loginway" value="学生" checked>学生
		  <input type="radio" name="loginway" value="教师" checked>教师
	  </div>
	 
    </form>
  </div>
  <script src="./js/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      var window_height = $('html').height();
      if (window.innerHeight) {
        window_height = window.innerHeight;
      }

      var offset = window_height - $('body').height();
      if (offset > 0) {
        $('body').css('marginTop', offset / 2);
      }
    });
  </script>
</body>
</html>
