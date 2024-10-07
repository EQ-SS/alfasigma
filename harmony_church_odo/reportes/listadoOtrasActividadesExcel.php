<?php
	set_time_limit(0);
	//ini_set("memory_limit", "2056M");
	include_once('../../clases/PHP_XLSXWriter/xlsxwriter.class.php'); /*you can get xlsxwriter.class.php from given GitHub link*/
	include "../conexion.php";
	function registro($row, $conn){
		$registro = array();
		$i = 0;
		foreach ($row as $clave=>$valor){
			if(strlen($clave)>2){
				if($i == 3){
					$j = $valor;
				}
				if($i == 4){
					if($j > 0){
						$valor = $j / 8 * 100;
					}else{
						$valor = '';
					}
				}
				if($i == 5){
					$k = $valor;
				}
				if($i == 6){
					if($k > 0){
						$valor = $k / 8 * 100;
					}
				}
				
				$registro[$clave] = utf8_encode($valor);
				$i++;
			}
		}
		
		//print_r($registro);
		//echo "<br><br>";
		return $registro;
	}
	
	$registrosPorPagina = 5000;
	$qMedicos = $_POST['hdnQueryListado'];	
	//echo $qMedicos;
	$rsMedicosTotal = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	
	$registros = sqlsrv_num_rows($rsMedicosTotal);
	$iteraciones = ceil($registros/$registrosPorPagina);
	$writer = new XLSXWriter();
	$arrayTotal = array();
	$cabeceras = array();
	foreach(sqlsrv_field_metadata($rsMedicosTotal) as $field){
		$cabeceras[$field['Name']] = utf8_encode($field['Name']);
	}
	$data[] = $cabeceras;
	$arrayTotal = array_merge($arrayTotal, $data);
	for($i=1;$i<=$iteraciones;$i++){
		$registroIni = $i * $registrosPorPagina - $registrosPorPagina;
		$data = array();
		//$queryTop = $qMedicos."OFFSET ".$registroIni." ROWS FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
		$queryTop = $qMedicos;
		$rsTop = sqlsrv_query($conn, utf8_decode($queryTop));
		while($row = sqlsrv_fetch_array($rsTop)){
			$data[] = registro($row, $conn);
		}
		$arrayTotal = array_merge($arrayTotal, $data);
	}
	//print_r($arrayTotal);
	$writer->writeSheet($arrayTotal);
	$writer->writeToFile('listadoOtrasActividades.xlsx');
	header("Location: listadoOtrasActividades.xlsx");
?>