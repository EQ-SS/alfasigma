<?php
	include "../conexion.php";
	
	function grabaPlan($conn, $idUsuario, $fecha, $comentarios, $arrActividades, $arrHoras, $idUserCreator ){
		$arrActividades = explode(",",$arrActividades);
		$arrHoras = explode(",",$arrHoras);
		//checamos si existe en visdayplan
		$qExisteVDP = "select * from visdayplan vp where vp.user_snr = '".$idUsuario."' and vp.DATE = '".$fecha."'";
		$rsExisteVDP = sqlsrv_query($conn, $qExisteVDP, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		if(sqlsrv_num_rows($rsExisteVDP) > 0){//ya existe existe existe
			$rsReporte = sqlsrv_fetch_array($rsExisteVDP);
			$idReporteDia = $rsReporte['DAYPLAN_SNR'];
		}else{
			$queryReport = "select NEWID() as idReporteDia from VISDAYPLAN where DAYPLAN_SNR = '00000000-0000-0000-0000-000000000000'";
			$rsReporte = sqlsrv_fetch_array(sqlsrv_query($conn, $queryReport));
			$idReporteDia = $rsReporte['idReporteDia'];
			$query = "insert into VISDAYPLAN (
				DAYPLAN_SNR, 
				USER_SNR,
				DATE, 
				info,
				REC_STAT,  
				CREATOR_SNR, 
				SYNC,
				CREATION_TIMESTAMP)
			values(
				'$idReporteDia',
				'$idUsuario',
				'$fecha',
				'$comentarios',
				0,
				'$idUserCreator',
				0,
				getdate())";
				
			if(! sqlsrv_query($conn, $query)){
				echo "Error: query ".$query."<br>";
			}
		}
		for($j=0;$j<count($arrActividades);$j++){
			$qVDPC = "select * from VISDAYPLAN_CODE where VISDAYPLAN_SNR = '".$idReporteDia."' and DAY_CODE = '".$arrActividades[$j]."'";
			$rsVDPC = sqlsrv_query($conn, $qVDPC, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsVDPC) < 1){
				$query = "insert into VISDAYPLAN_CODE (
					DAYPLANCODE_SNR, 
					VISDAYPLAN_SNR, 
					DAY_CODE, 
					DAYCODE_WEIGHT, 
					REC_STAT, 
					SYNC)
				values(
					NEWID(),
					'$idReporteDia',
					'".$arrActividades[$j]."',
					'".$arrHoras[$j]."',
					0,
					0)";
				if(! sqlsrv_query($conn, $query)){
					echo "Error: query ".$query."<br>";
				}
			}
		}
	}
	
	function grabaVisita($conn, $idUsuario, $fecha, $comentarios, $arrActividades, $arrHoras, $fechaFin,$fechaIni, $idUserCreator){
		$arrActividades = explode(",",$arrActividades);
		$arrHoras = explode(",",$arrHoras);
		$qExisteDR = "select * from DAY_REPORT where user_snr = '".$idUsuario."' and START_DATE = '".$fecha."'";
		$rsExisteDR = sqlsrv_query($conn, $qExisteDR, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		if(sqlsrv_num_rows($rsExisteDR) > 0){//ya existe existe existe
			$rsReporte = sqlsrv_fetch_array($rsExisteDR);
			$idReporteDia = $rsReporte['DAYREPORT_SNR'];
			//activa day_report
			$qDReport = "update DAY_REPORT set rec_stat = 0, sync = 0 where DAYREPORT_SNR = '".$idReporteDia."'";
			if(! sqlsrv_query($conn, $qDReport)){
				echo "qDReport: ".$qDReport."<br>";
			}
		}else{
			$queryReport = "select NEWID() as idReporteDia from DAY_REPORT where DAYREPORT_SNR = '00000000-0000-0000-0000-000000000000'";
			$rsReporte = sqlsrv_fetch_array(sqlsrv_query($conn, $queryReport));
			$idReporteDia = $rsReporte['idReporteDia'];
			$query = "insert into DAY_REPORT (
				DAYREPORT_SNR, 
				USER_SNR,
				CREATION_TIMESTAMP, 
				INFO,
				REC_STAT,  
				START_DATE,
				FINISH_DATE,
				CREATOR_USER_SNR, 
				SYNC,
				DATE)
			values(
				'$idReporteDia',
				'$idUsuario',
				getdate(),
				'$comentarios',
				0,
				'$fecha',
				'$fechaFin',
				'$idUserCreator',
				0,
				'$fecha')";
				//echo $query."<br>";
			if(! sqlsrv_query($conn, $query)){
				echo $query."<br>";
			}
			//echo $query."<br>";
		}
		
		///revisa la cantidad de horas registradas
		$qRevisaHoras = "select sum(value) as horas
			from DAY_REPORT_CODE 
			where DAYREPORT_SNR = '".$idReporteDia."' 
			and rec_stat = 0 
			group by DAYREPORT_SNR ";
		
		$numHoras = sqlsrv_fetch_array(sqlsrv_query($conn, $qRevisaHoras))['horas'];
		
		$horasTotales = $numHoras + array_sum($arrHoras);
		
		if($numHoras > 8){
			echo "<script>otrasActividadesHorasCompletas('".getNombreRepre($conn, $idUsuario)."','".$numHoras."','Ya tiene actividades esa fecha');</script>";
		}else if($horasTotales > 8){
			echo "<script>otrasActividadesHorasCompletas('".getNombreRepre($conn, $idUsuario)."','".$numHoras."','La suma de horas excede el permitido');</script>";
		}else{
		
			for($j=0;$j<count($arrActividades);$j++){
				$qDRC = "select * from DAY_REPORT_CODE where DAYREPORT_SNR = '".$idReporteDia."' and DAY_CODE_SNR = '".$arrActividades[$j]."'";
				//echo $qDRC."<br>";
				$rsDRC = sqlsrv_query($conn, $qDRC, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if(sqlsrv_num_rows($rsDRC) < 1){//no existe y la inserta
					$query = "insert into DAY_REPORT_CODE (
						DAYREPCOD_SNR, 
						DAYREPORT_SNR, 
						DAY_CODE_SNR, 
						VALUE, 
						REC_STAT, 
						SYNC,
						CREATION_TIMESTAMP)
					values(
						NEWID(),
						'$idReporteDia',
						'".$arrActividades[$j]."',
						'".$arrHoras[$j]."',
						0,
						0,
						getdate())";
					//echo $query."<br>";
					if(! sqlsrv_query($conn, $query)){
						echo "Error: query ".$query."<br>";
					}
					//echo $query."<br>";
				}else{//existe
					$reg = sqlsrv_fetch_array($rsDRC);
					if($reg['REC_STAT'] != 0){
						$qActualiza = "update DAY_REPORT_CODE set value = '".$arrHoras[$j]."',sync = 0, rec_stat = 0 where DAY_CODE_SNR = '".$reg['DAY_CODE_SNR']."' and dayreport_snr = '".$idReporteDia."' ";
						if(! sqlsrv_query($conn, $qActualiza)){
							echo $qActualiza."<br>";
						}
					}else{
						//echo "aqui actualiza";
					}
				}
			}
		}
	}
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		//$idReporteDia = $_POST['idReporteDia'];
		$fecha = substr($_POST['fecha'],6,4)."-".substr($_POST['fecha'],3,2)."-".substr($_POST['fecha'],0,2);
		$idUsuario = $_POST['idUsuario'];
		$comentarios = strtoupper($_POST['comentarios']);
		$arrActividades = $_POST['actividades'];
		$arrHoras = $_POST['horas'];
		$planVisita = $_POST['planVisita'];
		$repre = $_POST['repre'];
		$fechaIni = substr($_POST['fecha'],6,4)."-".substr($_POST['fecha'],3,2)."-".substr($_POST['fecha'],0,2);
		/*--visitas otras actividades
		select * from DAY_REPORT
		select * from DAYREP_CODE
		 
		--plan otras actividades
		select * from VISDAYPLAN_CODE
		select * from VISDAYPLAN*/
		if($repre != ''){
			$arrRepre = explode(",", substr($repre, 0, -1));
		}
		if(isset($_POST['fechaFin']) && $_POST['fechaFin'] != ''){
			$fechaFin = substr($_POST['fechaFin'],6,4)."-".substr($_POST['fechaFin'],3,2)."-".substr($_POST['fechaFin'],0,2);
		}else{
			$fechaFin = '';
		}
		//echo "fechaFin: ".$fechaFin;
		if(isset($_POST['idOA']) && $_POST['idOA'] != ''){
			$idOA = $_POST['idOA'];
		}else{
			$idOA = '';
		}
		
		//validar fechas
		
		if($planVisita == 'plan'){
			/*if($fecha < date("Y-m-d")){
				echo "<script>alert('No se puede planear en fechas anteriores al día de hoy!!!');</script>";
				return;
			}*/
		}else{
			/*if($fecha > date("Y-m-d")){
				echo "<script>
					alert('No se puede reportar en dias posteriores a la fecha');
					</script>";
					return;
			}else{*/
				$diasReportar = sqlsrv_fetch_array(sqlsrv_query($conn, "select REPORT_DAYS_BACK from users where user_snr = '".$idUsuario."'"))['REPORT_DAYS_BACK'];
				$nuevaFecha = date ( 'Y-m-d' , strtotime ( '-'.$diasReportar.' day' , strtotime ( date('Y-m-d') ) ) );
				/*if($nuevaFecha > $fecha){
					echo "<script>
						alert('No se puede reportar en esa fecha');
					</script>";
					return;
				}*/
			//}
		}
		
		
		//echo "id: ".$idOA;
		if($idOA != ''){
			if($planVisita == 'plan'){
				$qActualizaOA = "update VISDAYPLAN set info = '".$comentarios."', sync = 0 where dayplan_snr = '".$idOA."' ";
				$qEliminaOA = "delete from VISDAYPLAN_CODE where visdayplan_snr = '".$idOA."' ";
			}else{
				$qActualizaOA = "update DAY_REPORT set info = '".$comentarios."', sync = 0 where dayreport_snr = '".$idOA."' ";
				$qEliminaOA = "update DAY_REPORT_CODE set sync = 0, rec_stat = 2 where dayreport_snr = '".$idOA."' ";
			}
			if(! sqlsrv_query($conn, $qActualizaOA)){
				echo "Error: qActualizaOA ".$qActualizaOA."<br>";
			}
			if(! sqlsrv_query($conn, $qEliminaOA)){
				echo "Error: qEliminaOA ".$qEliminaOA."<br>";
			}/*else{
				echo "Error: qEliminaOA ".$qEliminaOA."<br>";
			}*/
			//echo $qActualizaOA."<br>";
			//echo $qEliminaOA."<br>";
		}
		//echo 'fechaFin'.$fechaFin."<br>";
		if($fechaFin != ''){
			//defino fecha 1 
			$ano1 = substr($fecha, 0, 4); 
			$mes1 = substr($fecha, 5, 2);  
			$dia1 = substr($fecha, 8, 2); 

			//defino fecha 2 
			$ano2 = substr($fechaFin, 0, 4); 
			$mes2 = substr($fechaFin, 5, 2); 
			$dia2 = substr($fechaFin, 8, 2);  

			//calculo timestam de las dos fechas 
			$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
			$timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2); 
			
			//resto a una fecha la otra 
			$segundos_diferencia = $timestamp1 - $timestamp2; 
			//echo $segundos_diferencia; 

			//convierto segundos en días 
			$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 

			//obtengo el valor absoulto de los días (quito el posible signo negativo) 
			$dias_diferencia = abs($dias_diferencia); 

			//quito los decimales a los días de diferencia 
			$dias_diferencia = floor($dias_diferencia); 
			$fechaDias = date("Y-m-d",$timestamp1);
			//echo $dias_diferencia."<br>";
			for($i=0;$i<=$dias_diferencia;$i++){
				$fechaDias = strtotime ( '+'.$i.' day' , strtotime ( $fecha ) ) ;
				if('Sun' != date("D", $fechaDias)){
					$fechaNueva = date("Y-m-d", $fechaDias);
					if($planVisita == 'plan'){
						if($repre != ''){
							for($usu=0;$usu<count($arrRepre);$usu++){
								grabaPlan($conn, $arrRepre[$usu], $fechaNueva, $comentarios, $arrActividades, $arrHoras, $idUsuario );
							}
						}else{
							grabaPlan($conn, $idUsuario, $fechaNueva, $comentarios, $arrActividades, $arrHoras, $idUsuario );
						}
					}else{
						if($repre != ''){
							for($usu=0;$usu<count($arrRepre);$usu++){
								$qExisteOA = "";
								grabaVisita($conn, $arrRepre[$usu], $fechaNueva, $comentarios, $arrActividades, $arrHoras,$fechaFin,$fechaIni, $idUsuario);
							}
						}else{
							grabaVisita($conn, $idUsuario, $fechaNueva, $comentarios, $arrActividades, $arrHoras,$fechaFin,$fechaIni, $idUsuario);
						}
					}
				}
			}
		}else{
			if($planVisita == 'plan'){
				if($repre != ''){
					for($usu=0;$usu<count($arrRepre);$usu++){
						grabaPlan($conn, $arrRepre[$usu], $fecha, $comentarios, $arrActividades, $arrHoras, $idUsuario);
					}
				}else{
					grabaPlan($conn, $idUsuario, $fecha, $comentarios, $arrActividades, $arrHoras, $idUsuario);
				}
			}else{
				if($repre != ''){
					for($usu=0;$usu<count($arrRepre);$usu++){
						grabaVisita($conn, $arrRepre[$usu], $fecha, $comentarios, $arrActividades, $arrHoras,$fechaFin,$fechaIni, $idUsuario);
					}
				}else{
					grabaVisita($conn, $idUsuario, $fecha, $comentarios, $arrActividades, $arrHoras,$fechaFin,$fechaIni, $idUsuario);
				}
			}
		}
		echo "<script>actualizaCalendario();</script>";
	}
?>