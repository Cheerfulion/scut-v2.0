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
$sql = "SELECT * FROM teacher,department WHERE tno='{$account}' and department.dno=teacher.dno";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

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

if(isset($_POST['sno'])&&isset($_POST['grade'])&&isset($_POST['cno']))
{
	$sno=$_POST['sno'];
	$grade=$_POST['grade'];
	$cno=$_POST['cno'];
	if($grade>100||$grade<0)
	{
		echo "<script>alert('成绩输入有误！请重新输入！')</script>";
	}else{
		 $sql="update sc set grade='{$grade}' where sno='{$sno}' and cno='{$cno}'";
		 $result1=mysqli_query($link, $sql);
	     }	
}

$sql = "SELECT distinct cno,cname,ccredit,num,studentnum FROM teacher,course WHERE teacher.tno='{$account}' and course.tno = teacher.tno ";
$result1=mysqli_query($link, $sql);
$opencoursenum=$result1->num_rows;
$result1=mysqlI_fetch_all($result1);	
foreach($result1 as $row1)
{
	$cno=$row1[0];
	$cname=$row1[1];
	$ccredit=$row1[2];
	$num=$row1[3];
	$studentnum=$row1[4];
	
	$sql="select count(sno) as gradednum from sc where cno='{$cno}' and grade is not NULL";
	$result1 = mysqli_query($link, $sql);
    $row1 = mysqli_fetch_array($result1);
	$gradednum=$row1['gradednum'];
	
	$sql="select avg(grade) as avgrade from sc where cno='{$cno}' and grade is not NULL";
	$result1 = mysqli_query($link, $sql);
    $row1 = mysqli_fetch_array($result1);
	$avgrade=$row1['avgrade'];
	$avgrade=(float)$avgrade;
	
	$sql="select count(cno) as passnum from sc where cno='{$cno}' and grade>=60";
	$result1 = mysqli_query($link, $sql);
    $row1 = mysqli_fetch_array($result1);
	$passnum=$row1['passnum'];
	$t1=(float)$passnum;
	$t2=(float)$gradednum;
	if($t2==0)
	{
		$passpercents=0;
	}else{
	$passpercents=$t1/$t2*100;
	}

	$nopassnum=$gradednum-$passnum;
	if($gradednum==0)
	{
		$nopasspercents=0;
	}else{
			$nopasspercents=100-$passpercents;
	     }
	
	$sql="select count(cno) as exstudentnum from sc where cno='{$cno}' and grade>=90";
	$result1 = mysqli_query($link, $sql);
    $row1 = mysqli_fetch_array($result1);
	$exstudentnum=$row1['exstudentnum'];
     
	$t1=(float)$exstudentnum;
	$t2=(float)$gradednum;
	if($t2==0)
	{
		$expercents=0;
	}else{
	$expercents=$t1/$t2*100;
	}
	
	$sql="select count(cno) as mannum from sc,student where cno='{$cno}' and sc.sno=student.sno and ssex='男'";
	$result1 = mysqli_query($link, $sql);
    $row1 = mysqli_fetch_array($result1);
	$mannum=$row1['mannum'];
	
	$sql="select count(cno) as womannum from sc,student where cno='{$cno}' and sc.sno=student.sno and ssex='女'";
	$result1 = mysqli_query($link, $sql);
    $row1 = mysqli_fetch_array($result1);
	$womannum=$row1['womannum'];
	
	$sql="insert into temp values ('{$cno}','{$cname}',{$ccredit},{$num},{$studentnum},{$gradednum},{$avgrade},{$passnum},{$passpercents},{$nopassnum},{$nopasspercents},{$exstudentnum},{$expercents},{$mannum},{$womannum});";
	$result1 = mysqli_query($link, $sql);
}
$sql="select * from temp ";
$courseshowway = isset($_POST['courseshowway']) ? $_POST['courseshowway'] : '';
switch($courseshowway)
{
	case "ccreditwayincrease":
		$sql .= "order by ccredit";
		break;
	case "cstudentnumincrease":
		$sql .= "order by studentnum";
		break;
	case "cavrgradeincrease":
		$sql .= "order by avgrade";
		break;
	case "studentpassincrease":
		$sql .= "order by passpercents";
		break;
	case "studentgreatincrease":
		$sql .= "order by expercents";
	    break;
	case "studentgirlincrease":
		$sql .= "order by womannum";
		break;
		
	case "ccreditwaydecrease":
		$sql .= "order by ccredit desc";
		break;
	case "cstudentnumdecrease":
		$sql .= "order by studentnum desc";
		break;
	case "cavrgradedecrease":
		$sql .= "order by avgrade desc";
		break;
	case "studentpassdecrease":
		$sql .= "order by passpercents desc";
		break;
	case "studentgreatdecrease":
		$sql .= "order by expercents desc";
	    break;
	case "studentgirldecrease":
		$sql .= "order by womannum desc";
		break;
		
	default:
		$sql .= "";
		break;
} 
$result1=mysqli_query($link, $sql);
$sql="select cno,cname from course where tno='{$account}'";
$result2=mysqli_query($link, $sql);
$studentshowway = isset($_POST['studentshowway']) ? $_POST['studentshowway'] : '';
$sql="select yesornot from judge where name='load'";
$result3 = mysqli_query($link, $sql);
$row3 = mysqli_fetch_array($result3);
$judge=$row3[0];
?>

<html>
<head>
<title>教师查课</title>
<link type="text/css" rel="styleSheet"  href="./css/student.css" />
</head>

<body>
<div id="header">
	<h1>教师个人界面</h1>
</div>

<div id="section1">
	<h2 style="text-align:center">教师个人信息</h2>
	<p>教工号：<?php echo $row['tno'] ;?><br/>
	   姓名：<?php echo $row['tname'] ;?><br/>
	   性别：<?php echo $row['tsex'] ;?><br/>
	   年龄：<?php 
					$birthday=strtotime($row['tbday']);
					$age=getAge($birthday);
					echo $age;
			 ?><br/>
	   院系：<?php echo $row['dname'] ;?></p>
	   <button type="button" onclick="window.location='tea_infochange.php';">修改个人信息</button>
	   <button type="button" onclick="window.location='tea_passwordchange.php';">修改密码</button>
	   <button type="button" onclick="window.location='login.php';">退出登录</button>
</div>

<div id="section2">
<h2 style="text-align:center">开课总览</h2>
 <form style="margin:0 auto，float:left" method="POST">
 <select name="courseshowway">
                    <option value=""></option>
                    <option value="ccreditwayincrease">按学分升序</option>
					<option value="cstudentnumincrease">按选课人数升序</option>
					<option value="cavrgradeincrease">按平均成绩升序</option>
					<option value="studentpassincrease">按及格率升序</option>
					<option value="studentgreatincrease">按优秀率升序</option>
					<option value="studentgirlincrease">按女生人数升序</option>
                    <option value="ccreditwaydecrease">按学分降序</option>
                    <option value="cstudentnumdecrease">按选课人数降序</option>
					<option value="cavrgradedecrease">按平均成绩降序</option>
					<option value="studentpassdecrease">按及格率降序</option>
					<option value="studentgreatdecrease">按优秀率降序</option>
					<option value="studentgirldecrease">按女生人数降序</option>
</select>
<input type="submit" value="重新排序">
</form> 
<table border='1' align="center" width="1200">
 <tr>
<th>序号</th>
<th>课程号</th>
<th>课程名</th>
<th>学分</th>
<th>计划学生人数</th>
<th>选课学生人数</th>
<th>已登分人数</th>
<th>平均成绩</th>
<th>及格人数</th>
<th>及格率</th>
<th>不及格人数</th>
<th>不及格率</th>
<th>优秀学生人数（>90）</th>
<th>优秀率</th>
<th>男生人数</th>
<th>女生人数</th>
</tr>
<?php
$j=1;
foreach ($result1 as $row){	?>
<tr>
    <td><?php echo $j ?></td>
	<td><?php echo $row['cno'] ?></td>
	<td><?php echo $row['cname'] ?></td>
	<td><?php echo $row['ccredit'] ?></td>
	<td><?php echo $row['num'] ?></td>
	<td><?php echo $row['studentnum'] ?></td>
	<td><?php echo $row['gradednum'] ?></td>
	<td><?php echo number_format($row['avgrade'],1) ?></td>
	<td><?php echo $row['passnum'] ?></td>
	<td><?php 
	if($row['passpercents']>99.98){
		$row['passpercents']=100 ;
		echo $row['passpercents'];
		}else if($row['passpercents']<0.01){
			 $row['passpercents']=0 ;
		     echo $row['passpercents'];
		}else{ 
			 echo number_format($row['passpercents'],1);
			 }
			 echo '%' ; 
			 ?></td>
	<td><?php echo $row['nopassnum']?></td>
	<td><?php 
	if($row['nopasspercents']>99.98){
		$row['nopasspercents']=100 ;
		echo $row['nopasspercents'];
		}else if($row['nopasspercents']<0.01){
			 $row['nopasspercents']=0 ;
		     echo $row['nopasspercents'];
		}else{ 
			 echo number_format($row['nopasspercents'],1);
			 }
			 echo '%' ;  ?></td>
	<td><?php echo $row['exstudentnum']?></td>
	<td><?php 
	if($row['expercents']>99.98){
		$row['expercents']=100 ;
		echo $row['expercents'];
		}else if($row['expercents']<0.01){
			 $row['expercents']=0 ;
		     echo $row['expercents'];
		}else{ 
			 echo number_format($row['expercents'],1);
			 }
			 echo '%' ;  ?></td>
	<td><?php echo $row['mannum'] ?></td>
	<td><?php echo $row['womannum'] ?></td>
</tr>
<?php 
$j++;
}?>
</table>
<?php
$sql="delete from temp";
$result1=mysqli_query($link, $sql);
echo "您一共开了 ".$opencoursenum." 门课程 <br/>";
?>
<br/>
<br/>
</div>
<div id="section2">
<h2 style="text-align:center">开课详情</h2>
<?php
foreach($result2 as $row){
	$cno=$row['cno'];
	?>
	<table border='1' align="center" width="1200">
	<caption><?php echo $row['cname']; ?></caption>
	<form style="margin:0 auto，float:left" method="POST">
			 <select name="studentshowway">
								<option value="gradedecrease"></option>
								<option value="gradeincrease">按成绩升序</option>
								<option value="gradedecrease">按成绩降序</option>
			</select>    
			<input type="submit" value="重新排序">
    </form>
	<tr>
	<th>序号</th>
	<th>学号</th>
	<th>姓名</th>
	<th>性别</th>
	<th>分数</th>
	<th>登分</th>
	</tr>
	<?php 
	$sql="select sc.sno,sname,ssex,grade from student,sc where student.sno=sc.sno and cno='{$cno}' ";
	switch($studentshowway)
	{
		case "gradedecrease":
		$sql .="order by grade desc";
		break;
		case "gradeincrease":
		$sql .="order by grade";
		break;
		default:
		$sql .= "";
		break;
	}
	$result3=mysqli_query($link, $sql);
	$j=1;
	foreach($result3 as $row1){?>
		<tr>
		<td><?php echo $j; ?></td>
		<td><?php echo $row1['sno']; ?></td>
		<td><?php echo $row1['sname']; ?></td>
		<td><?php echo $row1['ssex']; ?></td>
		<td><?php 
			if($row1['grade']!=NULL){
				echo $row1['grade'];}else{
				echo "暂无成绩";}?>
		<td> 
			<?php 
			if($judge==1){?>
				<form method="POST">
				  <input type="hidden" name="sno" value="<?php echo $row1['sno']; ?>"/>
				  <input type="hidden" name="cno" value="<?php echo $cno; ?>"/>
				  <input type="text" name="grade" style="width:80px;height:20px;"/>
				  <input type="submit" value = "登分"/>					  
				</form>
				<?php }else{
							echo "不是登分时间";
						}?>	
		</td>
		</tr>
        <?php 
		$j=$j+1;
		} ?>
    </table>
	<table  border='1' align="center" width="1200">	   
		<caption>各分数段人数</caption>
		<tr>
		<th>100分</th>
		<th>90到100（不包括100）</th>
		<th>80到90</th>
		<th>70到80</th>
		<th>60到70</th>
		<th>50到60</th>
		<th>40到50</th>
		<th>30到40</th>
		<th>20到30</th>
		<th>10到20</th>
		<th>0到10（不包括0）</th>
		<th>0分</th>
		</tr>
		<tr>
		<td>
		<?php
		$sql="select count(sno) as snum from sc where grade=100 and cno='{$cno}'";
		$result3=mysqli_query($link, $sql);
		$row1 = mysqli_fetch_array($result3);
		echo $row1['snum'];
		?>
		</td>
		<?php
		$s1=100;
		$s2=90;
		for(;$s2>=10;){?>
			<td>
			<?php
			$sql="select count(sno) as snum from sc where grade<{$s1} and grade>={$s2} and cno='{$cno}'";
			$result3=mysqli_query($link, $sql);
			$row1 = mysqli_fetch_array($result3);
			echo $row1['snum'];
			$s1=$s1-10;
			$s2=$s2-10;
			?>
			</td>
			<?php } ?>
			<td>
		<?php
		$sql="select count(sno) as snum from sc where grade<10 and grade>0 and cno='{$cno}'";
		$result3=mysqli_query($link, $sql);
		$row1 = mysqli_fetch_array($result3);
		echo $row1['snum'];
		?>
		</td>
		<td>
		<?php
		$sql="select count(sno) as snum from sc where grade=0 and cno='{$cno}'";
		$result3=mysqli_query($link, $sql);
		$row1 = mysqli_fetch_array($result3);
		echo $row1['snum'];
		?>
		</td>
		</tr>
	</table>
	<br/>
	<br/>
	<br/>
<?php } 
mysqli_close($link);
?>
</div>
</body>
</html>