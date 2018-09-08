<?php
if (!isset($_COOKIE['account'])) exit('身份验证过期！请先登录!');
$link = mysqli_connect('localhost', 'lyf', 'root', 'schooldata');
mysqli_query($link, 'set names utf8');
if(!$link)
{
	echo "<script>alert('服务器连接失败！');</script>";
	echo "<script>window.location.href='login.php';</script> ";
}
$account = $_COOKIE['account'];
$sql = "SELECT * FROM teacher WHERE tno='{$account}'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$usedpassword=$row['tpassword'];
if(isset($_POST['password'])||isset($_POST['surepassword'])||isset($_POST['pastpassword']))
{
	$password=$_POST['password'];
	$surepassword=$_POST['surepassword'];
	$pastpassword=$_POST['pastpassword'];
	$i=0;
	if(strlen($pastpassword)!=0)
	{
		if(strlen($password)==0)
		{
			echo "<script>alert('请输入新密码！')</script>";
		}else if(strlen($password)!=0)
				{
					if(strlen($surepassword)==0)
					{
						echo "<script>alert('请确认新密码！')</script>";
					}else{
							if($password==$surepassword)
							{
								$i=1;
							}else{
								   echo "<script>alert('两次输入的密码不一致！')</script>";
								 }
						 }
			   }
	}
	if($i)
	{
		if($pastpassword==$usedpassword)
		{
			$sql="update teacher set tpassword='{$password}' where tno='{$account}'";
			$result = mysqli_query($link, $sql);
			if($result )
			{
				echo "<script>alert('修改成功！')</script>";
			}else{
					echo "<script>alert('修改失败！！')</script>";
			     }
		}else{
				echo "<script>alert('旧密码错误！')</script>";
		     }
	}
}
mysqli_close($link);
?>
<html>

<head>
<title>教师登录密码修改</title>
<link type="text/css" rel="styleSheet"  href="./css/teacher.css" />
</head>
<body>

<div id="header" style="background-color:#C0C0C0">
	<h1>教师登录密码修改</h1>
</div
<div id="section2" style="background-color:	#FAEBD7">
<button type="button" style="text_align:center" onclick="window.location='teacher.php';">返回</button>
<form method="post" style="text_align:center" >
旧密码：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" name="pastpassword"/><br/>
新密码：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" name="password"/><br/>
确认新密码：<input type="password" name="surepassword"/><br/>
<input type="submit" value="确认修改"/>
</form>
</div>
</html>