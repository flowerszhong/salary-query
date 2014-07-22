<?php
include 'dbc.php';
page_protect();
include 'includes/head.php';
include 'includes/sidebar.php';
?>


        
<div class="main">
  <h1 class="title">
<?php
echo '&#10023;' . $_SESSION['employee_name'] . "&#10023; ,你好！";
?>
  </h1>

  	<h3 class="sub-title">
  		你的工资记录条：
  	</h3>
	<table>
	<tr>
		<td>工号</td>
		<td>姓名</td>
		<td>月分</td>
		<td>工资总额</td>
		<td>基本工资</td>
		<td>加班工资</td>
		<td>住房供给金</td>
		<td>医疗保险</td>
		<td>补助</td>
		<td>备注</td>
	</tr>		
	<?php 
		$employee_id = $_SESSION['employee_id'];
		$sql_select = "select * from `salary` where employee_id=$employee_id ORDER BY id DESC limit 50";
		$rows_result = mysql_query($sql_select) or die(mysql_error());
	?>

		<?php while($rrow = mysql_fetch_array($rows_result)){?>
			<tr>
				<td><?php echo $rrow['employee_id'] ?></td>
				<td><?php echo $rrow['employee_name'] ?></td>
				<td><?php echo $rrow['month'] ?></td>
				<td><?php echo $rrow['total_salary'] ?></td>
				<td><?php echo $rrow['base_salary'] ?></td>
				<td><?php echo $rrow['overtime_salary'] ?></td>
				<td><?php echo $rrow['housing_salary'] ?></td>
				<td><?php echo $rrow['medicare_salary'] ?></td>
				<td><?php echo $rrow['subsidy'] ?></td>
				<td><?php echo $rrow['remark'] ?></td>
			</tr>


		<?php } ?>

	</table>





 
</div>

<?php 
$footer_scripts = array("assets/js/settings.js","assets/js/main.js");
include 'includes/footer.php'  
?>
