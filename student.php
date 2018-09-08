<?php
if (!isset($_COOKIE['account'])) exit('身份验证过期！请先登录!');

$link = mysqli_connect('localhost', 'lyf', 'root', 'schooldata');
mysqli_query($link, 'set names utf8');
if(!$link)
{
	echo "<script>alert('服务器连接失败！');</script>";
	echo "<script>window.location.href='login.php';</script> ";
}

function db_query($sql)
{
	global $link;
	if ($result = mysqli_query($link, $sql)) {
		return mysqlI_fetch_all($result);
	}
	return false;
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

// setcookie('account', '', time()-3600);

$account = $_COOKIE['account'];
$sql = "SELECT * FROM student WHERE sno='{$account}'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

if(isset($_POST['cno']))
{
	$cno=$_POST['cno'];
	$sqlstudentdelete="select cno from course where cno not in  (select scy.cno from student ,course cy,sc scy where scy.cno = cy.cno and student.sno =scy.sno and student.sno='{$account}') and cno='{$cno}'";
	$resultdelete = mysqli_query($link, $sqlstudentdelete);
	if($resultdelete->num_rows ==0)
	{
	$sqlstudentdelete="delete from sc where sno='{$account}' and cno='{$cno}'";
	$resultdelete = mysqli_query($link, $sqlstudentdelete);
	$sqlstudentdelete="update course set studentnum=studentnum-1 where cno='{$cno}'";
	$resultdelete = mysqli_query($link, $sqlstudentdelete);
	$sqlstudentdelete="";
	}
}
if(isset($_POST['ccno']))
{
	$cno=$_POST['ccno'];
	$sqlstudentupdate="select sc.cno from sc,student where sc.sno=student.sno and student.sno='{$account}' and sc.cno = '{$cno}' ";
	$resultupdate = mysqli_query($link, $sqlstudentupdate);
	if($resultupdate->num_rows==0)
	{
	$sqlstudentupdate="update course set studentnum=studentnum+1 where cno='{$cno}'";
	$resultpudate = mysqli_query($link, $sqlstudentupdate);
	$sqlstudentupdate="insert into sc(sno,cno) values ('{$account}','{$cno}')";
	$resultpudate = mysqli_query($link, $sqlstudentupdate);
	$sqlstudentdelete="";
	}
}
$sql = "SELECT * FROM teacher, sc ,course WHERE sno='{$account}' and sc.cno=course.cno and course.tno = teacher.tno ";
$studentcourseshowway = isset($_POST['studentcourseshowway']) ? $_POST['studentcourseshowway'] : '';
switch($studentcourseshowway)
{
	case "ccreditwayincrease":
		$sql .= "order by ccredit";
		break;
	case "cgradeincrease":
		$sql .= "order by grade";
		break;
	case "ccreditwaydecrease":
		$sql .= "order by ccredit desc";
		break;
	case "cgradedecrease":
		$sql .= "order by grade desc";
		break;
	default:
		$sql .= "";
		break;
} 
 $result1 = mysqli_query($link, $sql);
 $sumcourse = $result1->num_rows;
 if($sumcourse != 0)
 {
	 $sql="select sum(ccredit) from sc,course where sno='{$account}' and sc.cno=course.cno and grade>=60";
	 $sumgettedccredit=intval(db_query($sql)[0][0]);
	 
	 $sql="select sc.cno from sc,course where sno='{$account}' and sc.cno=course.cno and grade < 60";
	 $failedcourse=mysqli_query($link, $sql);
	
	 
	 
	 $sql="select sum(ccredit) from sc,course where sno='{$account}' and sc.cno=course.cno";
	 $sumccredit = intval(db_query($sql)[0][0]);
	 
	 $sql="select sum(grade) from sc,course where sno='{$account}' and sc.cno=course.cno";
	 $sumgrade=intval(db_query($sql)[0][0]);
	 $sumgrade=(float)$sumgrade;
	 
	 $sql="select count(cno) as gradedsum from sc where sno='{$account}' and grade is not NULL";
	 $gradedsum=intval(db_query($sql)[0][0]);
	 $gradedsum=(float)$gradedsum;
	 if($gradedsum==0)
	 {
		 $avggrade=0;
	 }else{
	     $avggrade=$sumgrade/$gradedsum;
	      }
	 
	 $sql="select max(grade) from sc,course where sno='{$account}' and sc.cno=course.cno";
	 $maxgrade=intval(db_query($sql)[0][0]);
	 
	 $sql="select min(grade) from sc,course where sno='{$account}' and sc.cno=course.cno";
	 $mingrade=intval(db_query($sql)[0][0]);
	 
	 $sql="select sum(*) from sc,course where sno='{$account}' and sc.cno=course.cno and grade < 60";
	 $failedcoursenum=intval(db_query($sql)[0][0]);
	 
	 $sql="select max(ccredit) from sc,course where sno='{$account}' and sc.cno=course.cno";
	 $maxccredit=intval(db_query($sql)[0][0]);
	 
	 $sql="select min(ccredit) from sc,course where sno='{$account}' and sc.cno=course.cno";
	 $minccredit=intval(db_query($sql)[0][0]);
 }
?>
<html>
<head>
<title>学生选课</title>
<link type="text/css" rel="styleSheet"  href="./css/student.css" />
</head>

<body>
<div id="header">
	<h1>学生个人界面</h1>
</div>

<div id="section1">
	<h2 style="text-align:center">学生个人信息</h2>
	<p>学号：<?php echo $row[0] ;?><br/>
	   姓名：<?php echo $row[1] ;?><br/>
	   性别：<?php echo $row[2] ;?><br/>
	   年龄：<?php 
					$birthday=strtotime($row[5]);
					$age=getAge($birthday);
					echo $age;
			 ?><br/>
	   专业：<?php
				  $pno=$row[4];
				  $sql="select pname,dno from profession where pno='{$pno}'";
				  $result = mysqli_query($link, $sql);
                  $row = mysqli_fetch_array($result);
				  $pname=$row[0];
				  $dno=$row[1];
				  $sql="select dname from department where dno='{$dno}'";
				  $result = mysqli_query($link, $sql);
                  $row = mysqli_fetch_array($result);
				  $dname=$row[0];
				  echo $pname
	          ?><br/>
	   院系：<?php echo $dname ;?></p>
	   <button type="button" onclick="window.location='stu_infochange.php';">修改个人信息</button>
	   <button type="button" onclick="window.location='stu_passwordchange.php';">修改密码</button>
	   <button type="button" onclick="window.location='login.php';">退出登录</button>
</div>

<div id="section2">
<h2 style="text-align:center">学生选课表</h2>
 <form style="margin:0 auto，float:left" method="POST">
 <select name="studentcourseshowway">
                    <option value=""></option>
                    <option value="ccreditwayincrease">按学分升序</option>
					 <option value="cgradeincrease">按成绩升序</option>
                    <option value="ccreditwaydecrease">按学分降序</option>
                    <option value="cgradedecrease">按成绩降序</option>
</select>
<input type="submit" value="重新排序">
</form> 
 <table border='1' align="center" width="1200">
 <tr>
<th>序号</th>
<th>课程号</th>
<th>课程名</th>
<th>学分</th>
<th>任课教师</th>
<th>所属院系</th>
<th>课程成绩</th>
<th>是否退选</th>
</tr>
 <?php
 if ($sumcourse == 0 ) {
	 ?>
	 </table>
	 <?php
	 echo '暂无选课记录';
	} else {
		$j=1;
		foreach ($result1 as $row){?>
			<tr>
			  <td> <?php echo $j ;?></td>
			  <td> <?php echo $row['cno'] ;?></td>
			  <td> <?php echo $row['cname'] ;?></td>
			  <td> <?php echo $row['ccredit']; ?></td>
			  <td> <?php echo $row['tname'];?></td>
			  <td>
			       <?php
				        $dno=$row['dno'];
						$sql="select dname from department where dno='{$dno}'";
						$result = mysqli_query($link, $sql);
						$row1 = mysqli_fetch_array($result);
						echo $row1[0];
					?>
			  </td>
			  <td> <?php if($row['grade']==null){echo "暂无成绩";}else {echo $row['grade'];}?></td>
			  <td>
			  <?php 
			  if($row['grade']==null)
			  {?>
			  <form method="POST">
			  <input type="hidden" name="cno" value="<?php echo $row['cno']; ?>"/>
			  <input type="submit" value = "退选" style="color:blue;width:70px;height:30px;"/>					  
			  </form>
			  <?php } else {
				          echo "不可退选";
			               } ?>   
			  </td>
			</tr>
		<?php 
		$j++;
		}?>
</table>
	<?php 
	echo "共选了".$sumcourse."门课，共计".$sumccredit."学分，其中学分最高为：".$maxccredit."，最低为：".$minccredit;?>
	<br/>
	<?php
	echo "课程最高成绩为：".$maxgrade."，最低成绩为：".$mingrade."，平均成绩为：".number_format($avggrade,1);?>
	<br/>
	<?php
	echo "共有 ".$failedcourse->num_rows." 门课程不及格，"."所获学分为：".$sumgettedccredit;
	 }
$sql = "SELECT * FROM teacher,course WHERE course.tno = teacher.tno and 
		(cpno is NULL or course.cpno in (select scx.cno from student tx,course cx,sc scx where scx.cno = cx.cno and tx.sno =scx.sno and tx.sno='{$account}'and scx.grade >=60 and scx.grade is not null))and 
		course.cno not in (select scy.cno from student ,course cy,sc scy where scy.cno = cy.cno and student.sno =scy.sno and student.sno='{$account}') and course.num > course.studentnum";
$courseshowway = isset($_POST['courseshowway']) ? $_POST['courseshowway'] : '';
switch($courseshowway)
{
	case "ccreditwayincrease":
		$sql .= " order by ccredit";
		break;
	case "ccreditwaydecrease":
		$sql .= " order by ccredit desc";
		break;
	default:
		$sql .= " ";
		break;
} 
 $result2 = mysqli_query($link, $sql);
 $sumbecourse=$result2->num_rows;
?>
<h2 style="text-align:center">可选课程</h2>
<?php 
$sql="select yesornot from judge where name='choose'";
$result3 = mysqli_query($link, $sql);
$row3 = mysqli_fetch_array($result3);
$judge=$row3[0];
if($judge==1)
{
	if ( $sumbecourse == 0 ) {
		 echo '暂无可选课程！';
		} else {?>
			   <form style="margin:0 auto，float:left" method="POST">
				 <select name="courseshowway">
									<option value=""></option>
									<option value="ccreditwayincrease">按学分升序</option>
									<option value="ccreditwaydecrease">按学分降序</option>
				</select>    
				<input type="submit" value="重新排序">
			   </form>
	<table border='1' align="center" width="1200">
	 <tr>
	<th>序号</th>
	<th>课程号</th>
	<th>课程名</th>
	<th>学分</th>
	<th>任课教师</th>
	<th>选课情况</th>
	<th>所属院系</th>
	<th>选课</th>
	</tr>
	<?php
			$j=1;
			foreach ($result2 as $row){?>
				<tr>
				  <td> <?php echo $j ;?></td>
				  <td> <?php echo $row['cno'] ;?></td>
				  <td> <?php echo $row['cname'] ;?></td>
				  <td> <?php echo $row['ccredit']; ?></td>
				  <td> <?php echo $row['tname'];?></td>
				  <td><?php echo $row['studentnum'].'/'.$row['num']?></td>
				  <td>
					   <?php
							$dno=$row['dno'];
							$sql="select dname from department where dno='{$dno}'";
							$result = mysqli_query($link, $sql);
							$row1 = mysqli_fetch_array($result);
							echo $row1[0];
						?>
				  </td>
				  <td>
				  <form method="POST">
				  <input type="hidden" name="ccno" value="<?php echo $row['cno']; ?>"/>
				  <input type="submit" value = "选课" style="color:blue;width:70px;height:30px;" />					  
				  </form>
				  </td>
				</tr>
			<?php
			 $j++; 		
			}
	}
}else{
	echo '不是选课时间！';
     }
	mysqli_close($link);
	?>
</table>
</form> 
</div>
</body>
</html>