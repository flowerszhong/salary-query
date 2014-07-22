<?php 
include 'dbc.php';
page_protect();
$page_title = "工资表导入";
include 'includes/head.php';
include 'includes/sidebar.php';

$err = array();
$msg = array();



if ($_POST['add'])
{

	foreach($_POST as $key => $value) {
		$data[$key] = filter($value); // post variables are filtered
	}



	if($data['employee_id'] && $data['total_salary']){
		$sql_insert = "insert into `salary` 
						(employee_id,employee_name,total_salary,base_salary,overtime_salary,housing_salary,medicare_salary,subsidy,remark,month) 
						VALUES 
						('$data[employee_id]','$data[employee_name]','$data[total_salary]','$data[base_salary]','$data[overtime_salary]',
							'$data[housing_salary]','$data[medicare_salary]','$data[subsidy]','$data[remark]','$data[month]')";
		mysql_query($sql_insert) or die(mysql_error());
	}else{
		$err[] = "快速添加工资条<br>请输入工号和工资总额";
	}
}


if ($_POST['submit'] == 'Submit') {
    
    foreach ($_POST as $key => $value) {
        $data[$key] = filter($value); // post variables are filtered
    }

    require_once dirname(__FILE__) . '/PHPExcel/Classes/PHPExcel/IOFactory.php';


    if ($_FILES["file"]["error"] > 0) {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    } else {
        echo "Upload: " . $_FILES["file"]["name"] . "<br />";
        echo "Type: " . $_FILES["file"]["type"] . "<br />";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
        
        // if (file_exists("upload/" . $_FILES["file"]["name"])) {
        //     echo $_FILES["file"]["name"] . " already exists. ";
        // } else {
            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
            echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
        // }
    }

    $reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
    $PHPExcel = $reader->load("upload/" . $_FILES['file']["name"]); // 载入excel文件
    $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
    $highestRow = $sheet->getHighestRow(); // 取得总行数
    $highestColumm = $sheet->getHighestColumn(); // 取得总列数

     
    /** 循环读取每个单元格的数据 */
    for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
    	$ds = array();
        for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
            $ds[] = $sheet->getCell($column.$row)->getValue();
        }
        $sql_insert_loop = "INSERT INTO `salary`
        				(employee_id,employee_name,month,total_salary,base_salary,overtime_salary,housing_salary,medicare_salary,subsidy,remark) 
						VALUES 
						('$ds[0]','$ds[1]','$ds[2]','$ds[3]','$ds[4]','$ds[5]','$ds[6]','$ds[7]','$ds[8]','$ds[9]')
						";

		// echo $sql_insert_loop;

		mysql_query($sql_insert_loop) or die(mysql_error());

    }

}


include 'includes/errors.php';


 ?>

<div class="main">
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

		$sql_select = "select * from `salary` ORDER BY id DESC limit 50";
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


	<h3 class="title">快速添加工资条</h3>
	
	<div>
		<form action="import.php" method="post">
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
				<input type="text" name="employee_name" value=""/>
			</td>
		</tr>
		<tr>
			<td>月分</td>
			<td>
				<input type="text" name="month" value=""/>
			</td>
		</tr>
		<tr>
			<td>工资总额</td>
			<td>
				<input type="text" name="total_salary" value=""/>
			</td>
		</tr>
		<tr>
			<td>基本工资</td>
			<td>
				<input type="text" name="base_salary" value=""/>
			</td>
		</tr>
		<tr>
			<td>加班工资</td>
			<td>
				<input type="text" name="overtime_salary" value=""/>
			</td>
		</tr>
		<tr>
			<td>住房供给金</td>
			<td>
				<input type="text" name="housing_salary" value=""/>
			</td>
		</tr>
		<tr>
			<td>医疗保险</td>
			<td>
				<input type="text" name="medicare_salary" value=""/>
			</td>
		</tr>
		<tr>
			<td>补助</td>
			<td>
				<input type="text" name="subsidy" value=""/>
			</td>
		</tr>
		<tr>
			<td>备注</td>
			<td>
				<input type="text" name="remark" value=""/>
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


	<h3 class="title">批量导入工资表</h3>
	<p>
		点击下载<a href="salary.xls">样本</a>
	</p>
	<div>
		<form action="import.php" method="post" enctype="multipart/form-data">
			<label for="file">Filename:</label>
			<input type="file" name="file" id="file" /> 
			<br />
			<input type="submit" name="submit" value="Submit" />
		</form>
	</div>
	

</div>

 <?php 
$footer_scripts = array("assets/js/settings.js","assets/js/register.js","assets/js/main.js");
include 'includes/footer.php';
?>



