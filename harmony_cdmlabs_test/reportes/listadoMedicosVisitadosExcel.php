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
				
				if($i == 35){
					$latv = $valor;
				}
				if($i == 36){
					$logv = $valor;
				}
				if($i == 37){
					$lati = $valor;
				}
				if($i == 38){
					$logi = $valor;
				}
				
				if($i == 39){
					if( ($latv != '') && ($latv != '0.0') ){
						if( ($lati != '') && ($lati != '0.0') ){
							$total_latitud = abs( number_format( $latv - $lati , 4) );
						}else{
							$total_latitud = 0;
						}
					}else{ 
						$total_latitud = 0;
					}
					
					if( ($logv != '') && ($logv != '0.0') ){
						if( ($logi != '') && ($logi != '0.0') ){
							$total_langitud = abs( number_format( $logv - $logi , 4) );
						}else{
							$total_langitud = 0;
						}
					}else{ 
						$total_langitud = 0;
					}
					$valor = $total_latitud + $total_langitud;
					$res = $total_latitud + $total_langitud;
				}
				
				if($i == 40){
					if( ($latv != '') && ($latv != '0.0') && ($logv != '') && ($logv != '0.0') ){
						if( ($lati != '') && ($lati != '0.0') && ($logi != '') && ($logi != '0.0') ){
							if($res < 0.001){ 
								$valor = 'SIMILAR'; 
							}
							if( ($res >= 0.001) && ($res < 0.01) ){ 
								$valor = 'DIFERENTE'; 
							}
							if( ($res >= 0.01) && ($res < 0.1) ){ 
								$valor = 'MUY DIFERENTE'; 
							}
							if($res >= 0.1){ 
								$valor = 'EXTREMADAMENTE DIFERENTE'; 
							}						
						}else{
							$valor = 'SIN GEOLOCALIZAR MEDICO';
						}
					}else{ 
						$valor = 'SIN GEOLOCALIZAR VISITA';
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
	$writer->writeToFile('listadoMedicosVisitados.xlsx');
	header("Location: listadoMedicosVisitados.xlsx");
?>