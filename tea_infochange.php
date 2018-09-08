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

function getAge($birthday){
    //格式化出生时间年月日
    $byear=date('Y',$birthday);
    $bmonth=date('m',$birthday);
    $bday=date('d',$birthday);

    //格式化当前时间年月日
    $tyear=date('Y');
    $tmonth=date('m');
    $tday=date('d');

    //开始计算年龄
    $age=$tyear-$byear;
    if($bmonth>$tmonth || $bmonth==$tmonth && $bday>$tday){
        $age--;
    }
    return $age;
}

if(isset($_POST['name'])||isset($_POST['sex'])||isset($_POST['tbday']))
{
	$i1=0;
	$i2=0;
	$i3=0;
	if(isset($_POST['name']))	
	{
		$name=$_POST['name'];
		if($name!='')
		{
			$sql="update teacher set tname='{$name}' where tno='{$account}'";
			if ($result = mysqli_query($link, $sql))
				{
				$i1=1;
				}
		}
	}
	if(isset($_POST['sex']))	
	{
		$sex=$_POST['sex'];
		$sql="select tsex from teacher where tno='{$account}'";
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
	    $pastsex=$row['tsex'];
		if($pastsex!=$sex)
		{
			$sql="update teacher set tsex='{$sex}' where tno='{$account}' and tsex!='{$sex}'";
			$result = mysqli_query($link, $sql);
			if ($result = mysqli_query($link, $sql))
				{
					$i2=1;
				}
		}
	}
	if(isset($_POST['tbday']))	
	{
		$tbday=$_POST['tbday'];
		if($tbday!='')
		{
			$sql="update teacher set tbday='{$tbday}' where tno='{$account}'";
			if ($result = mysqli_query($link, $sql))
			{
				$i3=1;
			}
		}
	}
	if($i1&&$i2&&$i3)
	{
		echo "<script>alert('所有信息修改成功！')</script>";
    }else if($i1||$i2||$i3)
		{
			echo "<script>alert('部分信息修改成功！')</script>";
		}
	
}
$sql = "SELECT * FROM teacher,department WHERE tno='{$account}' and department.dno=teacher.dno";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
mysqli_close($link);
?>
<html>

<head>
<title>教师个人信息修改</title>
<link type="text/css" rel="styleSheet"  href="./css/teacher.css" />
</head>

<body>
<div id="header">
	<h1>教师个人信息修改</h1>
</div

<div id="section1">
<h2 style="text-align:left">教师个人信息</h2>
	<p>教工号：<?php echo $row['tno']; ?><br/>
	   姓名：<?php echo $row['tname']; ?><br/>
	   性别：<?php echo $row['tsex']; ?><br/>
	   年龄：
			<?php 
				$birthday=strtotime($row['tbday']);
				$age=getAge($birthday);
				echo $age;
			?><br/>
	   院系：<?php echo $row['dname']; ?><br/>
	   	<button type="button" onclick="window.location='teacher.php';">返回</button>
	</p>
</div>	
<div id="section2" style="text-align:left";>
<h2 style="text-align:left">信息修改</h2>
<form method="POST">
姓名：<input type="text" name="name"/><br/>
出生日期：<input type="date" name="tbday" min="1918-09-03" max="1998-09-03"><br>
性别：<select name="sex">
                    <option value="男">男</option>
                    <option value="女">女</option>
	   </select><br/>
<input type="submit" value="确认修改"/>
</form>
</div>
</body>
</html>