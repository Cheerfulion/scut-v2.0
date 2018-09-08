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

if(isset($_POST['tno'])&&isset($_POST['tname'])&&isset($_POST['tsex'])&&isset($_POST['tbday'])&&isset($_POST['tpassword'])){
	$i1=0;
	$i2=1;
	$tno=$_POST['tno'];
	$tname=$_POST['tname'];
	$tsex=$_POST['tsex'];
	$tbday=$_POST['tbday'];
	$birthday=strtotime($tbday);
	$age=getAge($birthday);
	$tpassword=$_POST['tpassword'];
	if(strlen($tno)!=0&&strlen($tname)!=0&&strlen($tsex)!=0&&strlen($tbday)!=0&&strlen($tpassword)!=0)
	{
		if($age<=100 and $age >=20){
			if(strlen($tno)===8)
			{
				$sql="insert into teacher(tno,tname,tsex,tbday,tpassword) values('{$tno}','{$tname}','{$tsex}','{$tbday}','{$tpassword}')";
				$result = mysqli_query($link, $sql);
				if($result)
				{
						$i1=1;
				}else{
						echo "<script>alert('教工号已存在！')</script>";
					}
			}else{
				  echo "<script>alert('教工号不合法！')</script>";
			    }
		}else{
				echo "<script>alert('年龄不合法！')</script>";
		    }
	}else{
			echo "<script>alert('请将信息填写完整！')</script>";
	    }
	if(isset($_POST['dno'])&&$i1)
	{
		if(strlen($_POST['dno'])!==0)
		{
			if(strlen($_POST['dno'])===5)
			{
				$dno=$_POST['dno'];
				$sql="update teacher set dno='{$dno}' where tno='{$tno}'";	
				$result = mysqli_query($link, $sql);
				if(!$result)
				{
					$i2=0;
					echo "<script>alert('无此院系！')</script>";
					$sql="delete from teacher where tno='{$tno}'";
					$result = mysqli_query($link, $sql);
				}
			}else{
					$i2=0;
					$sql="delete from teacher where tno='{$tno}'";
					$result = mysqli_query($link, $sql);
					echo "<script>alert('院系号不合法！')</script>";
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
<title>增加教师信息</title>
<link type="text/css" rel="styleSheet"  href="./css/manager.css" />
</head>

<body>
<div id="header">
	<h1>增加教师信息</h1>
</div>

<div id="section2" style="text-align:center">
<form method="POST" style="text-align:center">
教工号：&nbsp;	&nbsp;	&nbsp;&nbsp;<input type="text" name="tno"/><br/>
姓名：&nbsp;	&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="tname"/><br/>
院系号：&nbsp;	&nbsp;	&nbsp;&nbsp;<input type="text" name="dno"/><br/>
密码：	&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" name="tpassword"/><br/>
出生日期：<input type="date" name="tbday"><br/>
性别：<select name="tsex">
                    <option value="男">男</option>
                    <option value="女">女</option>
	   </select><br/>
<input type="submit" value="确认添加"/>
</form>
<p>若未确定院系可不填</p>
<button type="button" onclick="window.location='manager.php';">返回</button>
</div>
</body>
</html>