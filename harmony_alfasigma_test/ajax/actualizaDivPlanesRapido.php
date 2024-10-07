<?php
	/*echo $_POST['div']."<br>";
	echo $_POST['tipo']."<br>";
	echo $_POST['id']."<br>";
	echo "desde ajax";*/
	$id = $_POST['div'];
	echo "<script>
		$('#".$id."').empty();
	</script>";
	
?>