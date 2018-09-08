<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST'&&isset($_COOKIE['account'])){
	$link = mysqli_connect('localhost', 'lyf', 'root', 'schooldata');
	mysqli_query($link, 'set names utf8');
	if(!$link)
	{
		echo "<script>alert('服务器连接失败！');</script>";
		echo "<script>window.location.href='login.php';</script> ";
	}
	$cno=$_POST['ccno'];
	setcookie('cno', $cno, time() + 3600);
}
if((isset($_GET['name'])||isset($_GET['cpno'])||isset($_GET['credit'])||isset($_GET['num'])||isset($_GET['tno'])||isset($_GET['dno']))&&isset($_COOKIE['account'])&&isset($_COOKIE['cno']))
{
	$link = mysqli_connect('localhost', 'lyf', 'root', 'schooldata');
	mysqli_query($link, 'set names utf8');
	if(!$link)
	{
		echo "<script>alert('服务器连接失败！');</script>";
		echo "<script>window.location.href='login.php';</script> ";
	}
	$cno=$_COOKIE['cno'];
	$sql="select dno from course where cno='{$cno}'";
	$result=mysqli_query($link,$sql);
	$row=mysqli_fetch_array($result);
	$dno=$row['dno'];
	$i1=0;
	$i2=0;
	$i3=0;
	$i4=0;
	$i5=0;
	if(isset($_GET['name']))	
	{
		$name=$_GET['name'];
		if($name!='')
		{
			$sql="update course set cname='{$name}' where cno='{$cno}'";
			if ($result = mysqli_query($link, $sql))
				{
				$i1=1;
				}
		}
	}
	if(isset($_GET['cpno']))	
	{
		$cpno=$_GET['cpno'];
		if($cpno!='')
		{
			if($cpno!=='无'){
				if(strlen($cpno)===4)
				{
					$sql="update course set cpno='{$cpno}' where cno='{$cno}'";
					$result = mysqli_query($link, $sql);
					if ($result = mysqli_query($link, $sql))
						{
							$i2=1;
						}else{
								echo "<script>alert('该先修课程不存在！')</script>";
							}
				}else{
						echo "<script>alert('先修课程号不合法！')</script>";
				    }
			}else{
				$sql="update course set cpno=NULL where cno='{$cno}'";
				$result = mysqli_query($link, $sql);
				$i2=1;
			}
		}
	}
	if(isset($_GET['credit']))	
	{
		$credit=$_GET['credit'];
		if($credit!='')
		{
			if($credit>=1 and $credit <=10)
			{
				$sql="update course set ccredit='{$credit}' where cno='{$cno}'";
				$result = mysqli_query($link, $sql);
				if ($result = mysqli_query($link, $sql))
					{
					$i3=1;
					}
			}else{
					echo "<script>alert('学分不合法！')</script>";
				 }
			}
	}
	if(isset($_GET['num']))	
	{
		$num=$_GET['num'];
		if($num!='')
		{
			if($num>=15 and $num <=200)
			{
				$sql="update course set num='{$num}' where cno='{$cno}'";
				if ($result = mysqli_query($link, $sql))
					{
					$i4=1;
					}
			}else{
					echo "<script>alert('计划学生数不合法！')</script>";
				 }
		}
	}
	if(isset($_GET['tno']))	
	{
		$tno=$_GET['tno'];
		if($tno!='')
		{
			if($tno!=='无')
			{
				$sql="select dno from teacher where tno='{$tno}'";
				$result = mysqli_query($link, $sql);
				$row=mysqli_fetch_array($result);
				$tdno=$row['dno'];
				if($tdno===$dno)
				{
					$sql="update course set tno='{$tno}' where cno='{$cno}'";
					$result = mysqli_query($link, $sql);
					if ($result = mysqli_query($link, $sql))
						{
							$i5=1;
						}else{
								echo "<script>alert('该教师不存在！')</script>";
							}
				}else{
						echo "<script>alert('教师所属学院与开课学院不符！')</script>";
				    }
			}else{
				$sql="update course set tno=NULL where cno='{$cno}'";
				$result = mysqli_query($link, $sql);
				$i5=1;
			}
		}
	}
	if($i1&&$i2&&$i3&&$i4&&$i5)
	{
		echo "<script>alert('所有信息修改成功！')</script>";
    }else if($i1||$i2||$i3||$i4||$i5)
		{
			echo "<script>alert('部分信息修改成功！')</script>";
		}	
}
$sql = "SELECT * FROM course,department WHERE cno='{$cno}' and course.dno=department.dno";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
?>
<html>

<head>
<title>课程信息修改</title>
<link type="text/css" rel="styleSheet"  href="./css/manager.css" />
</head>

<body>
<div id="header">
	<h1>课程信息修改</h1>
</div

<div id="section1">
<h2 style="text-align:left">课程信息</h2>
	<p>课程号：<?php echo $row['cno'] ;?><br/>
	   课程名：<?php echo $row['cname'] ;?><br/>
	   先修课程：
	   <?php 
	         $cpno=$row['cpno'];
			 $sql="select cname from course where cno='{$cpno}'";
			 $result1 = mysqli_query($link, $sql);
			 $cpnum=$result1->num_rows;
			 $row1 = mysqli_fetch_array($result1);
			 $cpname=$row1[0];
			 if($cpnum===0)
			 {
				echo "无";
			 }else{
					echo $cpname;
			    }
	   ?><br/>
	   学分：<?php echo $row['ccredit'] ;?><br/>
	   课时：<?php echo $row['coursetime'] ;?><br/>
	   计划学生数：<?php echo $row['num'] ;?><br/>
	   开课教师：
	   <?php 
			 $tno=$row['tno'];
			 $sql="select tname from teacher where tno='{$tno}'";
			 $result1 = mysqli_query($link, $sql);
			 $row1 = mysqli_fetch_array($result1);
			 $tname=$row1[0];
			 echo $tname;
			 mysqli_close($link);
	   ?><br/>
	   所属院系：<?php echo $row['dname']; ?><br/>
	   选课学生数：<?php echo $row['studentnum'];?><br/>
	   	<button type="button" onclick="window.location='manager.php';">返回</button>
	</p>
</div>	
<div id="section3" style="text-align:left";>
<h2 style="text-align:left">信息修改</h2>
<form method="GET">
课程名：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="name"/><br/>
先修课程号：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="cpno"/><br/>
学分：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="credit"/><br/>
计划学生数：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="num"/><br/>
开课教师教工号：<input type="text" name="tno"/><br/>
<input type="submit" value="确认修改"/>
<p>若无先修课程或暂未确定授课教师可填“无”</p>
</form>
</div>
</body>
</html>