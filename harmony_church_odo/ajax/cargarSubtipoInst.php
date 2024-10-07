<?php
	include "../conexion.php";
	$tipo = $_POST['tipoInst'];
	$rsSubtipo = llenaComboInst($conn, "14", "2", $tipo);
	echo "<script>
		$('#sltSubtipoInstNueva').empty();";
		echo "$('#sltSubtipoInstNueva').append('<option value=\"00000000-0000-0000-0000-000000000000\" ></option>');";
		while($subtipo = sqlsrv_fetch_array($rsSubtipo)){
			echo "$('#sltSubtipoInstNueva').append('<option value=\"".$subtipo['id']."\" >".str_replace("'","\\'",$subtipo['nombre'])."</option>');";
		}
	
	echo "</script>";
?>