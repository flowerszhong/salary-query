<?php 
include 'dbc.php';
page_protect();
$page_title = "职工账号管理";
include 'includes/head.php';
include 'includes/sidebar.php';

$err = array();
$msg = array();



if ($_POST['add'])
{

	foreach($_POST as $key => $value) {
		$data[$key] = filter($value); // post variables are filtered
	}



	if($data['employee_id'] && $data['name'] && $data['pwd']){
		$sql_insert = "insert into `employee` 
						(employee_id,name,sfz_id,pwd,department,level) 
						VALUES 
						('$data[employee_id]','$data[name]','$data[sfz_id]','$data[pwd]','$data[department]','$data[level]')
						";
		mysql_query($sql_insert) or die(mysql_error());
	}else{
		$err[] = "添加账号<br>请输入工号、姓名和密码";
	}
}

include 'includes/errors.php';

 ?>

<div class="main">
	<table>
	<tr>
		<td>工号</td>
		<td>姓名</td>
		<td>身份证</td>
		<td>密码</td>
		<td>部门</td>
		<td>权限</td>
	</tr>		
	<?php 

		$sql_select = "select * from `employee` ORDER BY id DESC limit 50";
		$rows_result = mysql_query($sql_select) or die(mysql_error());
	?>

		<?php while($rrow = mysql_fetch_array($rows_result)){?>
			<tr>
				<td><?php echo $rrow['employee_id'] ?></td>
				<td><?php echo $rrow['name'] ?></td>
				<td><?php echo $rrow['sfz_id'] ?></td>
				<td><?php echo $rrow['pwd'] ?></td>
				<td><?php echo $rrow['department'] ?></td>
				<td><?php echo $rrow['level'] ?></td>
			</tr>


		<?php } ?>

	</table>


	<h3 class="title">添加账号</h3>
	<div>
		<form action="employee.php" method="post">
		<table>
		<tr>
			<td>工号</td>
			<td>
				<input type="text" name="employee_id" value=""/>
			</td>
		</tr>
		<tr>
			<td>姓名</td>
			<td>
				<input type="text" name="name" value=""/>
			</td>
		</tr>
		<tr>
			<td>身份证</td>
			<td>
				<input type="text" name="sfz_id" value=""/>
			</td>
		</tr>
		<tr>
			<td>密码</td>
			<td>
				<input type="text" name="pwd" value=""/>
			</td>
		</tr>
		<tr>
			<td>部门</td>
			<td>
				<input type="text" name="department" value=""/>
			</td>
		</tr>
		<tr>
			<td>权限</td>
			<td>
				<input type="text" name="level" value="employee"/>(管理员请输入"admin",普通员工请输入"employee")
			</td>
		</tr>


		<tr>
			<td colspan="2">
				<input type="submit" value="提交" name="add"/>
			</td>
		</tr>
		</table>

		</form>
	</div>

</div>

 <?php 
$footer_scripts = array("assets/js/settings.js","assets/js/register.js","assets/js/main.js");
include 'includes/footer.php';
?>



