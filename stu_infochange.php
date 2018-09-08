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

if(isset($_POST['name'])||isset($_POST['sex'])||isset($_POST['date']))
{
	$i1=0;
	$i2=0;
	$i3=0;
	if(isset($_POST['name']))	
	{
		$name=$_POST['name'];
		if($name!='')
		{
			$sql="update student set sname='{$name}' where sno='{$account}'";
			if ($result = mysqli_query($link, $sql))
				{
				$i1=1;
				}
		}
	}
	if(isset($_POST['sex']))	
	{
		$sex=$_POST['sex'];
		$sql="select ssex from student where sno='{$account}'";
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
	    $pastsex=$row['ssex'];
		if($pastsex!=$sex)
		{
			$sql="update student set ssex='{$sex}' where sno='{$account}' and ssex!='{$sex}'";
			$result = mysqli_query($link, $sql);
			if ($result = mysqli_query($link, $sql))
				{
					$i2=1;
				}
		}
	}
	if(isset($_POST['sbday']))	
	{
		$sbday=$_POST['sbday'];
		if($sbday!='')
		{
				$sql="update student set sbday='{$sbday}' where sno='{$account}'";
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
$sql = "SELECT * FROM student,profession,department WHERE sno='{$account}' and student.pno=profession.pno and profession.dno=department.dno";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
mysqli_close($link);
?>
<html>

<head>
<title>学生个人信息修改</title>
<link type="text/css" rel="styleSheet"  href="./css/student.css" />
</head>

<body>
<div id="header">
	<h1>学生个人信息修改</h1>
</div

<div id="section1">
<h2 style="text-align:left">学生个人信息</h2>
	<p>学号：<?php echo $row['sno'] ;?><br/>
	   姓名：<?php echo $row['sname'] ;?><br/>
	   性别：<?php echo $row['ssex'] ;?><br/>
	   年龄：<?php 
					$birthday=strtotime($row['sbday']);
					$age=getAge($birthday);
					echo $age;
			 ?><br/>
	   专业：<?php echo $row['pname']?><br/>
	   院系：<?php echo $row['dname'] ;?></p>
	   	<button type="button" onclick="window.location='student.php';">返回</button>
	</p>
</div>	
<div id="section2" style="text-align:left";>
<h2 style="text-align:left">信息修改</h2>
<form method="POST">
姓名：<input type="text" name="name"/><br/>
出生日期：<input type="date" name="sbday" min="1953-09-03" max="2008-09-03"><br>
性别：<select name="sex">
                    <option value="男">男</option>
                    <option value="女">女</option>
	   </select><br/>
<input type="submit" value="确认修改"/>
</form>
</div>
</body>
</html>