<?php
	include "../conexion.php";
	//$ids = $_POST['ids'];
	$palabra = $_POST['palabra'];
	if(isset($_POST['seleccionarTodo']) && $_POST['seleccionarTodo'] != ''){
		$seleccionarTodo = $_POST['seleccionarTodo'];
	}else{
		$seleccionarTodo = '';
	}
	if(isset($_POST['idsFiltros']) && $_POST['idsFiltros']){
		$idsNotIn = str_replace(",","','",substr($_POST['idsFiltros'], 0, -1));
	}else{
		$idsNotIn = "";
	}
	$queryCiclos = "select * from product where rec_stat = 0 and prod_snr <> '00000000-0000-0000-0000-000000000000' ";
	if($palabra != ''){
		$queryCiclos .= " and NAME like '%".$palabra."%'";
	}
	if($idsNotIn != ''){
		$queryCiclos .= " and prod_snr not in ('".$idsNotIn."') ";
	}
	$queryCiclos .= " order by NAME ";
	$rsCiclos = sqlsrv_query($conn, $queryCiclos);
	//echo $queryCiclos;
	echo "<script>";
	echo "$('#tblProductosFiltros').empty();";
	$i = 0;
	$nombres = '';
	$idsProducto = '';
	while($ciclo = sqlsrv_fetch_array($rsCiclos)){
		$nombreProducto = $ciclo['NAME'];
		//echo "st: ".$seleccionarTodo."<br>";
		if($seleccionarTodo != ''){
			echo "$('#tblProductosSeleccionadosFiltros').append('<tr id=\"trProductoSeleccionado".$i."\" onclick=\"eliminarProductoSeleccionado(\'trProductoSeleccionado".$i."\',\'".$ciclo['PROD_SNR']."\',\'".$nombreProducto."\');\"><td>".$nombreProducto."</td></tr>');";
			$nombres .= $nombreProducto.",";
			$idsProducto .= $ciclo['PROD_SNR'].",";
			$i++;
		}else{
			echo "$('#tblProductosFiltros').append('<tr onclick=\"productoSeleccionado(\'".$ciclo['PROD_SNR']."\',\'".$nombreProducto."\');\"><td>".$nombreProducto."</td></tr>');";
		}
	}
	if($seleccionarTodo != ''){
		echo "$('#hdnIdsFiltroProductos').val('".str_replace("'","",substr($idsProducto, 0, -1)).",');
			$('#hdnNombresFiltroProductos').val('".$nombres.",');";
	}
	echo "</script>";
?>