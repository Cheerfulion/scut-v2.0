<?php
if (!isset($_COOKIE['account'])) exit('身份验证过期！请先登录!');
$link = mysqli_connect('localhost', 'lyf', 'root', 'schooldata');
mysqli_query($link, 'set names utf8');
if(!$link)
{
	echo "<script>alert('服务器连接失败！');</script>";
	echo "<script>window.location.href='login.php';</script> ";
}
if(isset($_POST['cno'])&&isset($_POST['cname'])&&isset($_POST['cpno'])&&isset($_POST['ccredit'])&&isset($_POST['num'])&&isset($_POST['tno'])&&isset($_POST['dno'])){
	$i1=0;
	$i2=1;
	$i3=1;
	$cno=$_POST['cno'];
	$cname=$_POST['cname'];
	$cpno=$_POST['cpno'];
	$ccredit=$_POST['ccredit'];
	$num=$_POST['num'];
	$tno=$_POST['tno'];
	$dno=$_POST['dno'];
	if(strlen($cno)!=0&&strlen($cname)!=0&&strlen($ccredit)!=0&&strlen($num)!=0&&strlen($dno)!=0)
	{
		if(strlen($cno)===4)
		{
			$sql="select dno from teacher where tno='{$tno}'";
			$result = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($result);
			$tdno=$row['dno'];
			if($dno===$tdno)
			{
				if($num>=15 and $num<=200){
					if($ccredit<=10 and $ccredit >=1){
						$sql="insert into course(cno,cname,ccredit,num,studentnum) values('{$cno}','{$cname}',{$ccredit},{$num},0)";
						$result = mysqli_query($link, $sql);
						if($result)
						{
								$i1=1;
						}else{
								echo "<script>alert('课程号已存在！')</script>";
							}
					}else{
							echo "<script>alert('学分不合法！')</script>";
						}
				}else{
					echo "<script>alert('学生数不合法！')</script>";
				}
			}else{
					echo "<script>alert('开课学院与教师所属学院不符！')</script>";
				}
		}else{
			   echo "<script>alert('课程号不合法！')</script>";
		}
	}else{
			echo "<script>alert('请将课程信息填写完整！')</script>";
	    }
	if($i1&&strlen($_POST['cpno'])!=0)
	{
		$cpno=$_POST['cpno'];
		$sql="update course set cpno='{$cpno}' where cno='{$cno}'";	
		$result = mysqli_query($link, $sql);
	    if(!$result){
		    $i2=0;
			$sql="delete from course where cno='{$cno}'";
			$result = mysqli_query($link, $sql);
			echo "<script>alert('该先修课程不存在！')</script>";
	    }
	}
	if($i1&&strlen($_POST['tno'])!=0)
	{
		$tno=$_POST['tno'];
		$sql="update course set tno='{$tno}' where cno='{$cno}'";	
		$result = mysqli_query($link, $sql);
	    if(!$result){
			$i3=0;
			$sql="delete from course where cno='{$cno}'";
			$result = mysqli_query($link, $sql);
			echo "<script>alert('该老师不存在！')</script>";
	    }
	}
	if($i1&&$i2&&$i3)
	{
		echo "<script>alert('操作成功！')</script>";
	}else{
		echo "<script>alert('操作失败！')</script>";
        }
}
mysqli_close($link);
?>
<html>

<head>
<title>增加课程信息</title>
<link type="text/css" rel="styleSheet"  href="./css/manager.css" />
</head>

<body>
<div id="header">
	<h1>增加课程信息</h1>
</div>

<div id="section2" style="text-align:center";>
<form method="POST">
课程号：	&nbsp;	&nbsp;	&nbsp;<input type="text" name="cno"/><br/>
课程名：	&nbsp;	&nbsp;	&nbsp;<input type="text" name="cname"/><br/>
学分：	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;<input type="text" name="ccredit"/><br/>
授课老师：	&nbsp;	&nbsp;<input type="text" name="tno"/><br/>
开课学院：&nbsp;	&nbsp;<input type="text" name="dno"/><br/>
计划学生数：<input type="num" name="num"/><br/>
先修课程号：<input type="text" name="cpno"/><br/>
<input type="submit" value="确认添加"/>
</form>
<p>若无先修课程或暂未确定授课教师可不填</p>
<button type="button" onclick="window.location='manager.php';">返回</button>
</div>
</body>
</html>