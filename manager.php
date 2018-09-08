<?php
if (!isset($_COOKIE['account'])) exit('身份验证过期！请先登录!');

$link = mysqli_connect('localhost', 'lyf', 'root', 'schooldata');
mysqli_query($link, 'set names utf8');
if(!$link)
{
	echo "<script>alert('服务器连接失败！');</script>";
	echo "<script>window.location.href='login.php';</script> ";
}
//计算年龄函数
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

if(isset($_POST['cno']))
{
	$cno=$_POST['cno'];
	$sql="delete from course where cno='{$cno}'";
	$result = mysqli_query($link, $sql);
	if($result)
	{
		echo "<script>alert('删除成功！');</script>";
	}else{
		  echo "<script>alert('删除失败！该课程未结束');</script>";
	}
}

if(isset($_POST['sno']))
{
	$sno=$_POST['sno'];
	$sql="delete from student where sno='{$sno}'";
	$result = mysqli_query($link, $sql);
	if($result)
	{
		echo "<script>alert('删除成功！');</script>";
	}else{
		  echo "<script>alert('删除失败！该学生仍有选课');</script>";
	}
}

if(isset($_POST['tno']))
{
	$tno=$_POST['tno'];
	$sql="delete from teacher where tno='{$tno}'";
	$result = mysqli_query($link, $sql);
	if($result)
	{
		echo "<script>alert('删除成功！');</script>";
	}else{
		  echo "<script>alert('删除失败！该教师有课程未结课');</script>";
	}
}

$account = $_COOKIE['account'];


$sql="select * from profession";
$result5=mysqli_query($link, $sql);	
foreach($result5 as $row5)
{
	$pno=$row5['pno'];
	$pname=$row5['pname'];
	$instructor=$row5['instructor'];
	$tele=$row5['tele'];
	$dno=$row5['dno'];
	
	$sql="select count(sno) as stunum from profession,student where profession.pno=student.pno and profession.pno='{$pno}' ";
	$result6=mysqli_query($link, $sql);
	$row6=mysqli_fetch_array($result6);
	$stunum=$row6['stunum'];
	
	$sql="insert into ptemp values('{$pno}','{$pname}','{$instructor}','{$tele}',{$stunum},'{$dno}')";
	$result6=mysqli_query($link,$sql);
}

$sql="select department.dno,dname,tname,dtele from department,teacher where dmaster=tno";
$result5=mysqli_query($link, $sql);	
foreach($result5 as $row5)
{
	$dno=$row5['dno'];
	$dname=$row5['dname'];
	$tname=$row5['tname'];
	$dtele=$row5['dtele'];
	
	$sql="select count(pno) as pronum from profession where dno='{$dno}'";
	$result6=mysqli_query($link, $sql);
	echo mysqli_error($link);
	$row6=mysqli_fetch_array($result6);
	$pronum=$row6['pronum'];
	
	$sql="select count(cno) as coursenum from course where dno='{$dno}'";
	$result6=mysqli_query($link, $sql);
	$row6=mysqli_fetch_array($result6);
	$coursenum=$row6['coursenum'];
	
	$sql="select count(tno) as teanum from teacher where dno='{$dno}'";
	$result6=mysqli_query($link, $sql);
	$row6=mysqli_fetch_array($result6);
	$teanum=$row6['teanum'];
	
	$sql="select count(sno) as stunum from department,profession,student where department.dno='{$dno}' and profession.dno=department.dno and student.pno=profession.pno";
	$result6=mysqli_query($link, $sql);
	$row6=mysqli_fetch_array($result6);
	$stunum=$row6['stunum'];
	
	$sql="insert into stemp values('{$dno}','{$dname}','{$tname}','{$dtele}',{$pronum},{$coursenum},{$teanum},{$stunum})";
	$result6=mysqli_query($link,$sql);
}


$sql="select * from teacher ";
$teachershowway = isset($_POST['teachershowway']) ? $_POST['teachershowway'] : '';
switch($teachershowway)
{
	case "agewayincrease":
		$sql .= "order by tbday desc";
		break;
	case "agewaydecrease":
		$sql .= "order by tbday";
		break;
	default:
		$sql .= "";
		break;
} 	
$result1 = mysqli_query($link, $sql);
echo mysqli_error($link);

$sql="select * from student ";
$studentshowway = isset($_POST['studentshowway']) ? $_POST['studentshowway'] : '';
switch($studentshowway)
{
	case "agewayincrease":
		$sql .= "order by sbday desc";
		break;
	case "agewaydecrease":
		$sql .= "order by sbday";
		break;
	default:
		$sql .= "";
		break;
} 	
$result2 = mysqli_query($link, $sql);

$sql="select * from course ";
$courseshowway = isset($_POST['courseshowway']) ? $_POST['courseshowway'] : '';
switch($courseshowway)
{
	case "ccreditwayincrease":
		$sql .= "order by ccredit";
		break;
	case "cnumincrease":
		$sql .= "order by num";
		break;
	case "cstudentnumincrease":
	    $sql .= "order by studentnum";
		break;
	case "ccreditwaydecrease":
		$sql .= "order by ccredit desc";
		break;
	case "cnumdecrease":
		$sql .= "order by num desc";
		break;
	case "cstudentnumdecrease":
	    $sql .= "order by studentnum desc";
		break;
	default:
		$sql .= "";
		break;
} 	
$result3 = mysqli_query($link, $sql);

$sql="select * from stemp ";
$departmentshowway = isset($_POST['departmentshowway']) ? $_POST['departmentshowway'] : '';
switch($departmentshowway)
{
	case "pronumincrease":
		$sql .= "order by pronum";
		break;
	case "coursenumincrease":
		$sql .= "order by coursenum";
		break;
	case "teanumincrease":
	    $sql .= "order by teanum";
		break;
	case "stunumincrease":
		$sql .= "order by stunum";
		break;
	case "pronumdecrease":
		$sql .= "order by pronum desc";
		break;
	case "coursenumdecrease":
		$sql .= "order by coursenum desc";
		break;
	case "teanumdecrease":
	    $sql .= "order by teanum desc";
		break;
	case "stunumdecrease":
		$sql .= "order by stunum desc";
		break;
	default:
		$sql .= "";
		break;
} 	
$result5 = mysqli_query($link, $sql);

$sql="select * from ptemp ";
$professionshowway = isset($_POST['professionshowway']) ? $_POST['professionshowway'] : '';
switch($professionshowway)
{
	case "stunumincrease":
		$sql .= "order by stunum";
		break;
	case "stunumdecrease":
	    $sql .= "order by stunum desc";
		break;
	default:
		$sql .= "";
		break;
} 	
$result6 = mysqli_query($link, $sql);

?>
<html>
<head>
<title>教务管理</title>
<link type="text/css" rel="styleSheet"  href="./css/manager.css" />
<script src="./js/jquery.min.js"></script>
 <script>
$(document).ready(function(){
	$("div#section1").find("p.all").on("click",function(){
	    $("div#section3.teacher").show();
	    $("div#section3.student").show();
		$("div#section3.course").show();
	    $("div#section3.department").show();
		$("div#section3.profession").show();
		$(this).css("background-color","#0000FF");
		$("div#section1").find("p.student").css("background-color","#FFFFFF");
		$("div#section1").find("p.course").css("background-color","#FFFFFF");
		$("div#section1").find("p.teacher").css("background-color","#FFFFFF");
		$("div#section1").find("p.department").css("background-color","#FFFFFF");
		$("div#section1").find("p.profession").css("background-color","#FFFFFF");
		
    });
	$("div#section1").find("p.department").on("click",function(){
	    $("div#section3.teacher").hide();
	    $("div#section3.student").hide();
		$("div#section3.course").hide();
		$("div#section3.department").show();
		$("div#section3.profession").hide();
		$(this).css("background-color","#0000FF");
		$("div#section1").find("p.student").css("background-color","#FFFFFF");
		$("div#section1").find("p.course").css("background-color","#FFFFFF");
		$("div#section1").find("p.all").css("background-color","#FFFFFF");
		$("div#section1").find("p.teacher").css("background-color","#FFFFFF");
		$("div#section1").find("p.profession").css("background-color","#FFFFFF");
    });
	$("div#section1").find("p.profession").on("click",function(){
	    $("div#section3.teacher").hide();
	    $("div#section3.student").hide();
		$("div#section3.course").hide();
		$("div#section3.department").hide();
		$("div#section3.profession").show();
		$(this).css("background-color","#0000FF");
		$("div#section1").find("p.student").css("background-color","#FFFFFF");
		$("div#section1").find("p.course").css("background-color","#FFFFFF");
		$("div#section1").find("p.all").css("background-color","#FFFFFF");
		$("div#section1").find("p.department").css("background-color","#FFFFFF");
		$("div#section1").find("p.teacher").css("background-color","#FFFFFF");
    });
    $("div#section1").find("p.teacher").on("click",function(){
	    $("div#section3.teacher").show();
	    $("div#section3.student").hide();
		$("div#section3.course").hide();
		$("div#section3.department").hide();
		$("div#section3.profession").hide();
		$(this).css("background-color","#0000FF");
		$("div#section1").find("p.student").css("background-color","#FFFFFF");
		$("div#section1").find("p.course").css("background-color","#FFFFFF");
		$("div#section1").find("p.all").css("background-color","#FFFFFF");
		$("div#section1").find("p.department").css("background-color","#FFFFFF");
		$("div#section1").find("p.profession").css("background-color","#FFFFFF");
    });
    $("div#section1").find("p.student").on("click",function(){
		$("div#section3.teacher").hide();
		$("div#section3.student").show();
		$("div#section3.course").hide();
		$("div#section3.department").hide();
		$("div#section3.profession").hide();
		$(this).css("background-color","#0000FF");
		$("div#section1").find("p.teacher").css("background-color","#FFFFFF");
		$("div#section1").find("p.course").css("background-color","#FFFFFF");
		$("div#section1").find("p.all").css("background-color","#FFFFFF");
		$("div#section1").find("p.department").css("background-color","#FFFFFF");
		$("div#section1").find("p.profession").css("background-color","#FFFFFF");
    });
    $("div#section1").find("p.course").on("click",function(){
		$("div#section3.teacher").hide();
		$("div#section3.student").hide();
		$("div#section3.course").show();
		$("div#section3.department").hide();
		$("div#section3.profession").hide();
		$(this).css("background-color","#0000FF");
		$("div#section1").find("p.teacher").css("background-color","#FFFFFF");
		$("div#section1").find("p.student").css("background-color","#FFFFFF");
		$("div#section1").find("p.all").css("background-color","#FFFFFF");
		$("div#section1").find("p.department").css("background-color","#FFFFFF");
		$("div#section1").find("p.profession").css("background-color","#FFFFFF");
    });
});
</script>
</head>


<body>
<div id="header">
	<h1>教务员界面</h1>
</div>

<div id="section1">
	<h2 style="text-align:center">教务员信息</h2>
	<p>教务员ID：<?php echo $account ;?><br/><br/>
	   <button type="button" onclick="window.location='man_passwordchange.php';">修改密码</button>
	   <button type="button" onclick="window.location='man_power.php';">权限界面</button>
	   <button type="button" onclick="window.location='login.php';">退出登录</button>
	   <br/>
	   <br/>
	   <p class="all" id="p1" style="background-color:#0000FF">全部显示</p>
	   <p class="department" id="p1">院系信息</p>
	   <p class="profession" id="p1">专业信息</p>
	   <p class="teacher" id="p1">教师信息</p>
       <p class="student" id="p1">学生信息</p>
       <p class="course" id="p1">课程信息</p>
</div>

<div id="section3" class="department">
<h2 style="text-align:center">院系信息</h2>
<table border='1' align="center" width="1200">
<h3>院系总览<h3>
<form style="margin:0 auto，float:left" method="POST">
 <select name="departmentshowway">
                    <option value=""></option>
                    <option value="pronumincrease">按专业数升序</option>
					<option value="coursenumincrease">按课程数升序</option>
					<option value="teanumincrease">按教师数升序</option>
					<option value="stunumincrease">按学生数升序</option>
                    <option value="pronumdecrease">按专业数降序</option>
					<option value="coursenumdecrease">按课程数降序</option>
					<option value="teanumdecrease">按教师数降序</option>
					<option value="stunumdecrease">按学生数降序</option>
</select>
<input type="submit" value="重新排序">
</form> 
<tr>
<th>序号</th>
<th>院系号</th>
<th>院系名</th>
<th>系主任</th>
<th>系主任联系方式</th>
<th>专业数</th>
<th>课程数</th>
<th>教师人数</th>
<th>学生人数</th>
</tr>
<?php 
$j=1;
foreach($result5 as $row){?>
	<tr>
		<td><?php echo $j; ?></td>
		<td><?php echo $row['dno'];?></td>
		<td><?php echo $row['dname'];?></td>
		<td><?php 
		        if(strlen($row['tname'])===0)
				{
					echo "暂无";
				}else{
						echo $row['tname'];
				    }
			?>
		</td>
		<td>
			<?php 
			if(strlen($row['dtele'])===0)
				{
					echo "暂无";
				}else{
						echo $row['dtele'];
				    }
			?>
		</td>
		<td><?php echo $row['pronum']; ?></td>
		<td><?php echo $row['coursenum']; ?></td>
		<td><?php echo $row['teanum']; ?></td>
		<td><?php echo $row['stunum']; ?></td>
	</tr>
<?php
$j++; 
} 
$sql="delete from stemp";
$result5=mysqli_query($link,$sql);	
$sql="select dno,dname from department";
$result5=mysqli_query($link,$sql);	
?>
</table>
<h3>院系详情</h3>
<?php 
foreach($result5 as $row){
	?>
	<table border='1' align="center" width="1200">
		<caption><?php echo $row['dname'] ?></caption>
		<tr>
			<th>序号</th>
			<th>专业代码</th>
			<th>专业名</th>
			<th>辅导员</th>
			<th>辅导员联系方式</th>
			<th>学生数</th>
		</tr>
		<?php 
		    $j=1;
			$dno=$row['dno'];
			$sql="select * from ptemp where dno='{$dno}'";
			$result7=mysqli_query($link,$sql);
			foreach($result7 as $row1){?>
				<tr>
				    <td><?php echo $j; ?></td>
					<td><?php echo $row1['pno']; ?></td>
					<td><?php echo $row1['pname']; ?></td>
					<td>
						<?php 
						    if(strlen($row1['instructor'])!=0)
							{
								echo $row1['instructor'];
							}else{
									echo "暂无";
							    }
						?>
					</td>
					<td>
						<?php 
							if(strlen($row1['tele'])!=0)
							{
								echo $row1['tele']; 
							}else{
									echo "暂无";
							    }
						?>
					</td>
					<td><?php echo $row1['stunum']; ?></td>
                </tr>
			    <?php 
				$j++;
			}   ?>
	</table>
	<br/>
	<br/>
<?php } ?>
</div>

<div id="section3" class="profession">
<h2 style="text-align:center">专业信息</h2>
<table border='1' align="center" width="1200">
<h3>专业总览<h3>
<form style="margin:0 auto，float:left" method="POST">
 <select name="professionshowway">
                    <option value=""></option>
					<option value="stunumincrease">按学生数升序</option>
					<option value="stunumdecrease">按学生数降序</option>
</select>
<input type="submit" value="重新排序">
</form> 
<tr>
<th>序号</th>
<th>专业代码</th>
<th>专业名</th>
<th>辅导员</th>
<th>辅导员联系方式</th>
<th>学生人数</th>
</tr>
<?php
$j=1;
foreach($result6 as $row){?>
	<tr>
	    <td><?php echo $j; ?></td>
		<td><?php echo $row['pno'] ;?></td>
		<td><?php echo $row['pname'] ;?></td>
		<td>
			<?php 
				if(strlen($row['instructor'])!=0)
				{
					echo $row['instructor'];
				}else{
						echo "暂无";
					}
			?>
		</td>
		<td>
			<?php 
				if(strlen($row['tele'])!=0)
				{
					echo $row['tele']; 
				}else{
						echo "暂无";
					}
		?>
		</td>
		<td><?php echo $row['stunum'] ;?></td>
	</tr>
	<?php
	$j++;
}
$sql="delete from ptemp";
$result5=mysqli_query($link,$sql);	
$sql="select pno,pname from profession";
$result5=mysqli_query($link,$sql);	
?>
</table>
<h3>专业详情</h3>
<?php 
foreach($result6 as $row){?>
	<table  border='1' align="center" width="1200">
		<caption><?php echo $row['pname'] ?></caption>
		<form style="margin:0 auto，float:left" method="POST">
			<select name="pstudentshowway">
						<option value=""></option>
						<option value="agewayincrease">按年龄升序</option>
						<option value="coursenumincrease">按选课数升序</option>
						<option value="avgradeincrease">按成绩升序</option>
						<option value="agewaydecrease">按年龄降序</option>
						<option value="coursenumdecrease">按选课数降序</option>
						<option value="avgradedecrease">按成绩降序</option>
			</select>
		<input type="submit" value="重新排序">
		</form> 
		<tr>
		<th>序号</th>
		<th>学号</th>
		<th>姓名</th>
		<th>性别</th>
		<th>年龄</th>
		<th>选课门数</th>
		<th>所选课程</th>
		<th>课程加权平均分</th>
		<tr>
		<?php 
		    $j=1;
			$pno=$row['pno'];
			$sql="select * from student where pno='{$pno}'";
			$result7=mysqli_query($link,$sql);
		
			foreach($result7 as $row1)
			{
				$sno=$row1['sno']; 
				$sname=$row1['sname'];
				$ssex=$row1['ssex'];
				
				$birthday=strtotime($row1['sbday']);
				$age=getAge($birthday);
				
				$sno=$row1['sno'];
				$sql="select count(cno) as sum from student,sc where sc.sno=student.sno and student.sno='{$sno}'";
				$result4 = mysqli_query($link, $sql);
				$row2 = mysqli_fetch_array($result4);
				$coursenum=$row2['sum'];
				
				$sql="select sum(ccredit*grade) as sum from course,sc where sc.cno=course.cno and sno='{$sno}' and grade is not NULL";
				$result8=mysqli_query($link,$sql);
				$row2=mysqli_fetch_array($result8);
				$sum=$row2['sum'];
				$sql="select sum(ccredit) as sumcredit from course,sc where sc.cno=course.cno and sno='{$sno}' and grade is not NULL";
				$result8=mysqli_query($link,$sql);
				echo mysqli_error($link);
				$row2=mysqli_fetch_array($result8);
				$sumcredit=$row2['sumcredit'];
				if($sumcredit==0)
				{
					$avgrade=0;
				}else{
					$sum=(float)$sum;
					$sumcredit=(float)$sumcredit;
					$avgrade=$sum/$sumcredit;
				}
				$sql="insert into stutemp values('{$sno}','{$sname}','{$ssex}',{$age},{$coursenum},{$avgrade})";
				$result8=mysqli_query($link,$sql);
			}
			$sql="select * from stutemp ";
			$pstudentshowway = isset($_POST['pstudentshowway']) ? $_POST['pstudentshowway'] : '';
			switch($pstudentshowway)
			{
				case "agewayincrease":
					$sql .= "order by sage ";
					break;
				case "coursenumincrease":
				    $sql .= "order by coursenum";
					break;
				case "avgradeincrease":
					$sql .= "order by avgrade";
					break;
				case "agewaydecrease":
					$sql .= "order by sage desc";
					break;
				case "coursenumdecrease":
				    $sql .= "order by coursenum desc";
					break;
				case "avgradedecrease":
					$sql .= "order by avgrade desc";
					break;
					
				default:
					$sql .= "";
					break;
			} 	
			$result7 = mysqli_query($link, $sql);
			foreach($result7 as $row1){?>
				<tr>
				    <td><?php echo $j; ?></td>
				    <td><?php echo $row1['sno']; ?></td>
					<td><?php echo $row1['sname']; ?></td>
					<td><?php echo $row1['ssex']; ?></td>
					<td>
						<?php 
							echo $row1['sage'];
						?>
					</td>
					<td>
					<?php 
						echo $row1['coursenum'];
					?>
					</td>
					<td>
					<?php 
					    if($row1['coursenum']!=0)
						{
							$sno=$row1['sno'];
							$sql="select cname from student,sc,course where course.cno=sc.cno and sc.sno=student.sno and student.sno='{$sno}'";
							$result4 = mysqli_query($link, $sql);
							foreach($result4 as $row3)
							{
								echo $row3['cname'];
								echo " ";
								echo " ";
							}
						}else{
							  echo "暂无";
						    }   
					?>
					</td>
					<td><?php echo $row1['avgrade']; ?></td>
                </tr>
			    <?php 
				$j++;
				$sql="delete from stutemp";
				$result8=mysqli_query($link,$sql);
			}   ?>
	</table>
	<br/>
	<br/>
<?php } ?>
</div>

<div id="section3" class="teacher">
<table border='1' align="center" width="1200">
<h2>教师信息列表<h2>
<form style="margin:0 auto，float:left" method="POST">
<select name="teachershowway">
                    <option value=""></option>
                    <option value="agewayincrease">按年龄升序</option>
                    <option value="agewaydecrease">按年龄降序</option>
</select>
<input type="submit" value="重新排序">
</form> 
<tr>
<th>序号</th>
<th>教工号</th>
<th>姓名</th>
<th>性别</th>
<th>年龄</th>
<th>院系</th>
<th>开课门数</th>
<th>所开课程</th>
<th>删除教师信息</th>
</tr>
<?php 
$j=1;
foreach($result1 as $row){
	?>
	<tr>
	    <td><?php echo $j; ?></td>
		<td><?php echo $row['tno']; ?></td>
		<td><?php echo $row['tname']; ?></td>
		<td><?php echo $row['tsex']; ?></td>
		<td>
			<?php 
				$birthday=strtotime($row['tbday']);
				$age=getAge($birthday);
				echo $age;
			?>
		</td>
		<td>
			<?php 
				$dno=$row['dno']; 
				$sql="select dname from department where dno='{$dno}'";
				$result4 = mysqli_query($link, $sql);
				$row1 = mysqli_fetch_array($result4);
				echo $row1['dname'];
			?>
		</td>
		<td>
		<?php 
			$tno=$row['tno'];
			$sql="select count(cno) as sum from teacher,course where course.tno=teacher.tno and teacher.tno='{$tno}'";
			$result4 = mysqli_query($link, $sql);
			$row1 = mysqli_fetch_array($result4);
			echo $row1['sum'];
		?>
		</td>
		<td>
		<?php 
			$tno=$row['tno'];
			$sql="select cname from teacher,course where course.tno=teacher.tno and teacher.tno='{$tno}'";
			$result4 = mysqli_query($link, $sql);
			foreach($result4 as $row1)
			{
				echo $row1['cname'];
				echo " ";
				echo " ";
			}
		?>
		</td>
		<td>
			<form method="POST">
				<input type="hidden" name="tno" value="<?php echo $row['tno']; ?>"/>
				<input type="submit" value = "删除"/>					  
			 </form>
		</td>
	</tr>
<?php 
$j++;
} 
?>
</table>
<button type="button" onclick="window.location='addteacher.php';">增加教师</button>
</div>

<div id="section3" class="student">
<table border='1' align="center" width="1200">
<h2>学生信息列表<h2>
<form style="margin:0 auto，float:left" method="POST">
<select name="studentshowway">
                    <option value=""></option>
                    <option value="agewayincrease">按年龄升序</option>
                    <option value="agewaydecrease">按年龄降序</option>
</select>
<input type="submit" value="重新排序">
</form> 
<tr>
<th>序号</th>
<th>学号</th>
<th>姓名</th>
<th>性别</th>
<th>年龄</th>
<th>专业</th>
<th>院系</th>
<th>选课门数</th>
<th>所选课程</th>
<th>删除学生信息</th>
</tr>
<?php 
$j=1;
foreach($result2 as $row){?>
	<tr>
	    <td><?php echo $j; ?></td>
		<td><?php echo $row['sno']; ?></td>
		<td><?php echo $row['sname']; ?></td>
		<td><?php echo $row['ssex']; ?></td>
		<td>
			<?php 
				$birthday=strtotime($row['sbday']);
				$age=getAge($birthday);
				echo $age;
			?>
		</td>
		<td>
			<?php
			    $pno=$row['pno'];
				$sql="select pname,dno from profession where pno='{$pno}'";
				$result4 = mysqli_query($link, $sql);
				$row1 = mysqli_fetch_array($result4);
				echo $row1['pname'];
				$dno=$row1['dno']; 
				$sql="select dname from department where dno='{$dno}'";
				$result4 = mysqli_query($link, $sql);
				$row1 = mysqli_fetch_array($result4);
			?>
		</td>
		<td><?php echo $row1['dname']; ?></td>
		<td>
		<?php 
			$sno=$row['sno'];
			$sql="select count(cno) as sum from student,sc where sc.sno=student.sno and student.sno='{$sno}'";
			$result4 = mysqli_query($link, $sql);
			$row1 = mysqli_fetch_array($result4);
			echo $row1['sum'];
		?>
		</td>
		<td>
		<?php 
		    if($row1['sum']!=0)
			{
				$sno=$row['sno'];
				$sql="select cname from student,sc,course where course.cno=sc.cno and sc.sno=student.sno and student.sno='{$sno}'";
				$result4 = mysqli_query($link, $sql);
				foreach($result4 as $row1)
				{
					echo $row1['cname'];
					echo " ";
					echo " ";
				}
			}else{
					echo "暂无";
			    }
		?>
		</td>
		<td>
			<form method="POST">
				<input type="hidden" name="sno" value="<?php echo $row['sno']; ?>"/>
				<input type="submit" value = "删除"/>					  
			 </form>
		</td>
	</tr>
<?php 
$j++;
} 
?>
</table>
<button type="button" onclick="window.location='addstudent.php';">增加学生</button>
</div>

<div id="section3" class="course">
<table border='1' align="center" width="1200">
<h2>课程信息列表<h2>
<form style="margin:0 auto，float:left" method="POST">
<select name="courseshowway">
							<option value=""></option>
							<option value="ccreditwayincrease">按学分升序</option>
							<option value="cnumincrease">按计划学生数升序</option>
							<option value="cstudentnumincrease">按选课学生数升序</option>
							<option value="ccreditwaydecrease">按学分降序</option>
							<option value="cnumdecrease">按计划学生数降序</option>
							<option value="cstudentnumdecrease">按选课学生数降序</option>
			</select>   
<input type="submit" value="重新排序">
</form> 
<tr>
<th>序号</th>
<th>课程号</th>
<th>课程名</th>
<th>先修课程</th>
<th>学分</th>
<th>课时</th>
<th>计划学生数</th>
<th>开课老师</th>
<th>所属院系</th>
<th>选课学生数</th>
<th>修改课程信息</th>
<th>删除课程信息</th>
</tr>
<?php 
$j=1;
foreach($result3 as $row){?>
	<tr>
	    <td><?php echo $j; ?></td>
		<td><?php echo $row['cno']; ?></td>
		<td><?php echo $row['cname']; ?></td>
		<td>
		<?php 
		$cpno=$row['cpno'];
		$sql="select cname from course where cno='{$cpno}'";
		$result4 = mysqli_query($link, $sql);
		$row9 = mysqli_fetch_array($result4);
		if($row9['cname']!=NULL)
		{
			echo $row9['cname'];
		}
		else
		{
			echo "无";
		}
		?>
		</td>
		<td><?php echo $row['ccredit']; ?></td>
		<td><?php echo $row['coursetime']; ?></td>
		<td><?php echo $row['num']; ?></td>
		<td>
		<?php 
			$tno=$row['tno'];
			$sql="select tname from teacher where tno='{$tno}'";
			$result4 = mysqli_query($link, $sql);
		    $row9 = mysqli_fetch_array($result4);
			if($row9['tname']!=NULL)
			{
				echo $row9['tname'];
			}
			else
			{
				echo "无";
			}
		?>
		</td>
		<td>
		<?php
		    $dno=$row['dno'];
		    $sql="select dname from department where dno='{$dno}'";
			$result4 = mysqli_query($link, $sql);
		    $row9 = mysqli_fetch_array($result4);
			echo $row9['dname'];
		?>
		</td>
		<td><?php echo $row['studentnum']; ?></td>
		<td>
			<form action="cou_infochange.php" method="POST">
				<input type="hidden" name="ccno" value="<?php echo $row['cno']; ?>"/>
				<input type="submit" value ="修改课程信息" class="button"/>					  
			 </form>
		</td>
		<td>
			<form method="POST">
				<input type="hidden" name="cno" value="<?php echo $row['cno']; ?>"/>
				<input type="submit" value = "删除"/>					  
			 </form>
		</td>
	</tr>
<?php 
$j++;
} 
mysqli_close($link);
?>
</table>
<button type="button" onclick="window.location='addcourse.php';">增加课程</button>
</div>

</body>
</html>