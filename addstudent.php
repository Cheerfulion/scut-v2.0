<?php
if (!isset($_COOKIE['account'])) exit('身份验证过期！请先登录!');
$link = mysqli_connect('localhost', 'lyf', 'root', 'schooldata');
mysqli_query($link, 'set names utf8');
if(!$link)
{
	echo "<script>alert('服务器连接失败！');</script>";
	echo "<script>window.location.href='login.php';</script> ";
}

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

if(isset($_POST['sno'])&&isset($_POST['sname'])&&isset($_POST['ssex'])&&isset($_POST['sbday'])&&isset($_POST['spassword'])){
	$i1=0;
	$i2=1;
	$sno=$_POST['sno'];
	$sname=$_POST['sname'];
	$ssex=$_POST['ssex'];
	$sbday=$_POST['sbday'];
	$birthday=strtotime($sbday);
	$age=getAge($birthday);
	$spassword=$_POST['spassword'];
	if(strlen($sno)!=0&&strlen($sname)!=0&&strlen($ssex)!=0&&strlen($sbday)!=0&&strlen($spassword)!=0)
	{
		if($age<=65 and $age >=10){
			if(strlen($sno)===12)
			{
				$sql="insert into student(sno,sname,ssex,sbday,spassword) values('{$sno}','{$sname}','{$ssex}','{$sbday}','{$spassword}')";
				$result = mysqli_query($link, $sql);
				if($result)
				{
						$i1=1;
				}else{
						echo "<script>alert('学号已存在！')</script>";
					}
			}else{
				echo "<script>alert('学号不合法！')</script>";
			}
		}else{
				echo "<script>alert('年龄不合法！')</script>";
		    }
	}else{
			echo "<script>alert('请将信息填写完整！')</script>";
	    }
	if(isset($_POST['pno'])&&$i1)
	{
		if(strlen($_POST['pno'])!==0)
		{
			if(strlen($_POST['pno'])===6)
			{
				$pno=$_POST['pno'];
				$sql="update student set pno='{$pno}' where sno='{$sno}'";	
				$result = mysqli_query($link, $sql);
				if(!$result)
				{
					$i2=0;
					echo "<script>alert('无此专业！')</script>";
					$sql="delete from student where sno='{$sno}'";
					$result = mysqli_query($link, $sql);
				}
			}else{
					$i2=0;
					$sql="delete from student where sno='{$sno}'";
					$result = mysqli_query($link, $sql);
					echo "<script>alert('专业代码不合法！')</script>";
				}
		}
	}
	if($i1&&$i2)
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
<title>增加学生信息</title>
<link type="text/css" rel="styleSheet"  href="./css/manager.css" />
</head>

<body>
<div id="header">
	<h1>增加学生信息</h1>
</div>

<div id="section2" style="text-align:center";>
<form method="POST">
学号：&nbsp;	&nbsp;	&nbsp;&nbsp;<input type="text" name="sno"/><br/>
姓名：&nbsp;	&nbsp;	&nbsp;&nbsp;<input type="text" name="sname"/><br/>
密码：&nbsp;	&nbsp;	&nbsp;&nbsp;<input type="password" name="spassword"/><br/>
专业代码：<input type="text" name="pno"/><br/>
出生日期：<input type="date" name="sbday"/><br/>
性别：<select name="ssex">
                    <option value="男">男</option>
                    <option value="女">女</option>
	   </select><br/>
<input type="submit" value="确认添加"/>
</form>
<p>若尚未分配专业可不填</p>
<button type="button" onclick="window.location='manager.php';">返回</button>
</div>
</body>
</html>