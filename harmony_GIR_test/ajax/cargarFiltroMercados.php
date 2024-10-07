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
	
	$queryCiclos = "select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden 
		from codelist,codelistlib 
		where codelist.clib_snr=codelistlib.clib_snr 
		and codelist.status=1 
		and codelistlib.table_nr = '14614' 
		and codelistlib.list_nr = '1' 
		and CODELIST.REC_STAT = 0 ";
		
	if($palabra != ''){
		$queryCiclos .= " and codelist.name like '%".$palabra."%'";
	}
	if($idsNotIn != ''){
		$queryCiclos .= " and codelist.CLIST_SNR not in ('".$idsNotIn."') ";
	}
	$queryCiclos .= " order by orden, nombre";
	
	$rsCiclos = sqlsrv_query($conn, $queryCiclos);
	//echo $queryCiclos;
	echo "<script>";
	echo "$('#tblMercadosFiltros').empty();
		";
	$i = 0;
	$nombres = '';
	$idsMercado = '';
	while($ciclo = sqlsrv_fetch_array($rsCiclos)){
		$nombreMercado = $ciclo['nombre'];
		//echo "st: ".$seleccionarTodo."<br>";
		if($seleccionarTodo != ''){
			echo "$('#tblMercadosSeleccionadosFiltros').append('<tr id=\"trMercadoSeleccionado".$i."\" onclick=\"eliminarMercadoSeleccionado(\'trMercadoSeleccionado".$i."\',\'".$ciclo['id']."\',\'".$nombreMercado."\');\"><td>".$nombreMercado."</td></tr>');";
			$nombres .= $nombreMercado.",";
			$idsMercado .= $ciclo['id'].",";
			$i++;
		}else{
			echo "$('#tblMercadosFiltros').append('<tr onclick=\"mercadoSeleccionado(\'".$ciclo['id']."\',\'".$nombreMercado."\');\"><td>".$nombreMercado."</td></tr>');";
		}
	}
	if($seleccionarTodo != ''){
		echo "$('#hdnIdsFiltroMercados').val('".str_replace("'","",substr($idsMercado, 0, -1)).",');
			$('#hdnNombresFiltroMercados').val('".$nombres.",');";
	}
	echo "</script>";
?>