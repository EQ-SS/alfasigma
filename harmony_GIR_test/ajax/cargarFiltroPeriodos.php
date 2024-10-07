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
		/*and codelist.status=1*/
		and codelistlib.table_nr = '14614' 
		and codelistlib.list_nr = '2' 
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
	echo "$('#tblPeriodosFiltros').empty();
		";
	$i = 0;
	$nombres = '';
	$idsPeriodo = '';
	while($ciclo = sqlsrv_fetch_array($rsCiclos)){
		$nombrePeriodo = $ciclo['nombre'];
		//echo "st: ".$seleccionarTodo."<br>";
		if($seleccionarTodo != ''){
			echo "$('#tblPeriodosSeleccionadosFiltros').append('<tr id=\"trPeriodoSeleccionado".$i."\" onclick=\"eliminarPeriodoSeleccionado(\'trPeriodoSeleccionado".$i."\',\'".$ciclo['id']."\',\'".$nombrePeriodo."\');\"><td>".$nombrePeriodo."</td></tr>');";
			$nombres .= $nombrePeriodo.",";
			$idsPeriodo .= $ciclo['id'].",";
			$i++;
		}else{
			echo "$('#tblPeriodosFiltros').append('<tr onclick=\"periodoSeleccionado(\'".$ciclo['id']."\',\'".$nombrePeriodo."\');\"><td>".$nombrePeriodo."</td></tr>');";
		}
	}
	if($seleccionarTodo != ''){
		echo "$('#hdnIdsFiltroPeriodos').val('".str_replace("'","",substr($idsPeriodo, 0, -1)).",');
			$('#hdnNombresFiltroPeriodos').val('".$nombres.",');";
	}
	echo "</script>";
?>