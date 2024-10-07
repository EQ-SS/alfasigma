<?php
	include "../conexion.php";
	$subTipo = $_POST['subTipoInst'];
	$rsFormato = llenaComboInst($conn, "14", "3", $subTipo);
	echo "<script>
		$('#sltFormatoInstNueva').empty();";	
		while($formato = sqlsrv_fetch_array($rsFormato)){
			echo "$('#sltFormatoInstNueva').append('<option value=\"".$formato['id']."\" >".$formato['nombre']."</option>');";
		}
	echo "</script>";
?>