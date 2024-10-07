<?php
	set_time_limit(0);
	//ini_set("memory_limit", "2056M");
	include_once('../../clases/PHP_XLSXWriter/xlsxwriter.class.php'); /*you can get xlsxwriter.class.php from given GitHub link*/
	include "../conexion.php";
	function registro($row, $conn){		
		$registro = array();
		$visitasArr = array();
		$i = 0;
		foreach ($row as $clave=>$valor){	
			if(strlen($clave)>2){
				//echo "El valor de $clave es: $valor<br>";
				if($i <= 15){
					$registro[$clave] = utf8_encode($valor);
				}else{
					if($i % 2 != 0){
						$visitasArr[] = utf8_encode($valor);
					}
				}
				$i++;
			}
		}
		
		$n = 17;
		for($m = count($visitasArr)-1; $m >= 0; $m--){
			if ($visitasArr[$m] != 9 && is_numeric($visitasArr[$m])){
				$registro[$n] = utf8_encode($visitasArr[$m]);
			}
			$n++;
		}
		//print_r($registro);
		//echo "<br><br>";
		return $registro;
	}
	
	$registrosPorPagina = 5000;	
	$qInst = $_POST['hdnQueryListado'];	
	//echo $qInst."<br>";
	$rsMedicosTotal = sqlsrv_query($conn, utf8_decode($qInst), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	
	//$registros = sqlsrv_num_rows($rsMedicosTotal);
	$registros = 80000;
	$iteraciones = ceil($registros/$registrosPorPagina);
	$writer = new XLSXWriter();
	$arrayTotal = array();
	$cabeceras = array();
	$encabezado = array();
	$k = 0;
	$j = 1;
	foreach(sqlsrv_field_metadata($rsMedicosTotal) as $field){
		if($k <= 15){
			$cabeceras[$field['Name']] = utf8_encode($field['Name']);
		}else{
			if($k % 2 == 0){
				$encabezado[] = utf8_encode($field['Name']);
			}
		}
		$k++;
	}
	while($regMedico = sqlsrv_fetch_array($rsMedicosTotal)){
		if($j == 1){
			for($l = count($encabezado)-1; $l >= 0; $l--){ 
				if ($regMedico[$encabezado[$l]] != 'A'){
					$cabeceras[] = utf8_encode($regMedico[$encabezado[$l]]);
				}
			}
		}
		$j++;
	}
		
	$data[] = $cabeceras;
	$arrayTotal = array_merge($arrayTotal, $data);
	for($i=1;$i<=$iteraciones;$i++){
		$registroIni = $i * $registrosPorPagina - $registrosPorPagina;
		$data = array();
		$queryTop = $qInst."OFFSET ".$registroIni." ROWS FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
		$rsTop = sqlsrv_query($conn, utf8_decode($queryTop));
		while($row = sqlsrv_fetch_array($rsTop)){
			//print_r($row);
			//echo "<br>";
			$data[] = registro($row, $conn);
		}
		$arrayTotal = array_merge($arrayTotal, $data);
	}
	//print_r($arrayTotal);
	$writer->writeSheet($arrayTotal);
	$writer->writeToFile('listadoHistoricoFarmaciasVisitadasCiclo.xlsx');
	header("Location: listadoHistoricoFarmaciasVisitadasCiclo.xlsx");
?>