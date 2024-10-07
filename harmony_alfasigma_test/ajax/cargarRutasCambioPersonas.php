<?php
	include ("../conexion.php");
	
	/*if(isset($_POST['ruta']) && $_POST['ruta'] != ''){
		$ruta = $_POST['ruta'];
	}*/
	
	$ids = str_replace("'',''","','",$_POST['ids']);
	
	$query = "select user_snr, lname + ' ' + fname as nombre 
		from users 
		where user_type = 4 
		and rec_stat = 0 
		and user_snr in ('".$ids."') 
		order by lname, fname";
	
	$rs = sqlsrv_query($conn, $query);
	
	//echo $query;
	
	echo "<script>
		$('#tblRutasCambiarPersonas tbody').empty();";
	$i = 1;
	while($row = sqlsrv_fetch_array($rs, SQLSRV_FETCH_ASSOC)){
		echo '$("#tblRutasCambiarPersonas tbody").append(\'<tr><td class="padding-0"><input type="radio" class="with-gap radio-col-red" id="'.$row["user_snr"].'" name="radioRutas" value="'.$row["user_snr"].'"  /><label for="'.$row["user_snr"].'">'.$row["nombre"].'</label></td></tr> \');';
		$i++;
	}
	echo '$("#tblRutasCambiarPersonas tfoot").append(\'<tr><td></td></tr>\');
		</script>';
?>
