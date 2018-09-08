<?php
if (!isset($_COOKIE['account'])) exit('身份验证过期！请先登录!');

$link = mysqli_connect('localhost', 'lyf', 'root', 'schooldata');
mysqli_query($link, 'set names utf8');
if(!$link)
{
	echo "<script>alert('服务器连接失败！');</script>";
	echo "<script>window.location.href='login.php';</script> ";
}
if(isset($_POST['courseopenclose']))
{
	$yesornot=$_POST['courseopenclose'];
	$sql="update judge set yesornot={$yesornot} where name='choose'";
	$result=mysqli_query($link,$sql);
}
if(isset($_POST['loadopenclose']))
{
	$yesornot=$_POST['loadopenclose'];
	$sql="update judge set yesornot={$yesornot} where name='load'";
	$result=mysqli_query($link,$sql);
}
?>
<html>
<head>
<title>权限设置</title>
<link type="text/css" rel="styleSheet"  href="./css/manager.css" />
</head>

<body>
<div id="header">
	<h1>权限设置</h1>
</div>

<div id="section3">
    <form method="POST">
        <选课开关>
		<input type="radio" name="courseopenclose" value="1" checked>开启
		<input type="radio" name="courseopenclose" value="0" checked>关闭
		&nbsp;	&nbsp;	&nbsp;
		<input id='submit' type='submit' value='开启/关闭' />
		   （当前状态：
		    <?php
				$sql="select yesornot from judge where name='choose'";
				$result=mysqli_query($link,$sql);
				$row=mysqli_fetch_array($result);
				$name=$row[0];
				if($name==1)
				{
					echo "开启";
				}else{
					  echo "关闭";
				}
		    ?>
		    ）
    </form>
</div>

<div id="section3">
        <form method="POST">
		    <登分开关>
			 <input type="radio" name="loadopenclose" value="1" checked>开启
			 <input type="radio" name="loadopenclose" value="0" checked>关闭
			 &nbsp;	&nbsp;	&nbsp;
			 <input id='submit' type='submit' value='开启/关闭' />
		   （当前状态：
		        <?php
					$sql="select yesornot from judge where name='load'";
					$result=mysqli_query($link,$sql);
					$row=mysqli_fetch_array($result);
					$name=$row[0];
					if($name==1)
					{
						echo "开启";
					}else{
						  echo "关闭";
					}
			    ?>
		    ）
		</form>
</div>

<div id="section3" style="text_align:center">
<button type="button" onclick="window.location='manager.php';">返回</button>
</div>

</div>