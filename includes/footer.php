
<div class="footer"></div>
<script type="text/javascript" src="assets/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="assets/js/main.js"></script>
<?php 
$scripts = "";
if ($footer_scripts) {
	foreach ($footer_scripts as $key => $value) {
		$scripts = $scripts . "<script type='text/javascript' src='" . $value ."'></script>";
	}
	echo $scripts;
}
?>

</body>
</html>