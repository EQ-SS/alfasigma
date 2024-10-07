<?php
	include "../conexion.php";
	$arrNoGrabados = array();
	function grabaPlan($conn, $idUsuario, $fecha, $comentarios, $arrActividades, $arrHoras ){
		$arrActividades = explode(",",$arrActividades);
		$arrHoras = explode(",",$arrHoras);
		//checamos si existe en visdayplan
		$qExisteVDP = "select * 
			from visdayplan vp 
			where vp.user_snr = '".$idUsuario."' 
			and vp.DATE = '".$fecha."' 
			and rec_stat = 0";
		//echo $qExisteVDP."<br>";
		$rsExisteVDP = sqlsrv_query($conn, $qExisteVDP, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		if(sqlsrv_num_rows($rsExisteVDP) > 0){//ya existe existe existe
			$rsReporte = sqlsrv_fetch_array($rsExisteVDP);
			$idReporteDia = $rsReporte['DAYPLAN_SNR'];
		}else{
			$queryReport = "select NEWID() as idReporteDia 
				from VISDAYPLAN 
				where DAYPLAN_SNR = '00000000-0000-0000-0000-000000000000'";
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
				'$idUsuario',
				0,
				getdate())";
				
			if(! sqlsrv_query($conn, $query)){
				echo "Error visdayplan: query ".$query."<br>";
			}
		}
		for($j=0;$j<count($arrActividades);$j++){
			//revisamos que no exista un TFT en esa fecha y ese usuario
			$qROA = "SELECT DAYPLANCODE_SNR,
				DAYCODE_WEIGHT,
				REC_STAT 
				FROM VISDAYPLAN_CODE 
				WHERE VISDAYPLAN_SNR = '".$idReporteDia."' 
				AND DAY_CODE = '".$arrActividades[$j]."' ";

			//echo $qROA."<br>";
				
			$rsROA = sqlsrv_query($conn, $qROA, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

			if(sqlsrv_num_rows($rsROA) > 0){//ya existe el TFT
				$arrROA = sqlsrv_fetch_array($rsROA);

				$idPlanCode = $arrROA['DAYPLANCODE_SNR'];
				$horas = $arrROA['DAYCODE_WEIGHT'];

				if($arrROA['REC_STAT'] != 0){//la habilita y actualiza los datos
					$qActualiza = "UPDATE VISDAYPLAN_CODE SET 
						REC_STAT = 0,
						SYNC = 0,
						DAY_CODE = '".$arrActividades[$j]."',
						DAYCODE_WEIGHT = '".$arrHoras[$j]."'
						WHERE DAYPLANCODE_SNR = '".$idPlanCode."' ";
				}else{
					$totalHoras = $horas + $arrHoras[$j];
					
					$qActualiza = "UPDATE VISDAYPLAN_CODE SET 
						DAYCODE_WEIGHT = '".$totalHoras."',
						SYNC = 0
						WHERE DAYPLANCODE_SNR = '".$idPlanCode."' ";
				}
			} else {
				$qActualiza = "INSERT INTO VISDAYPLAN_CODE  
					(
						DAYPLANCODE_SNR,
						VISDAYPLAN_SNR,
						DAY_CODE,
						DAYCODE_WEIGHT,
						REC_STAT,
						SYNC
					) 
						VALUES 
					(
						NEWID(),
						'".$idReporteDia."', 
						'".$arrActividades[$j]."',
						'".$arrHoras[$j]."',
						0,
						0)";
			}

			if(! sqlsrv_query($conn, $qActualiza)){
				echo "Error: query ".$qActualiza."<br>";
			}
		}
	}
			
	function grabaVisita($conn, $idUsuario, $fecha, $comentarios, $arrActividades, $arrHoras, $fechaFin,$fechaIni, $idCreador){
		$arrActividades = explode(",",$arrActividades);
		$arrHoras = explode(",",$arrHoras);
		$qExisteDR = "SELECT DAYREPORT_SNR,
			REC_STAT
			FROM DAY_REPORT 
			WHERE user_snr = '".$idUsuario."' 
			AND START_DATE = '".$fecha."' 
			AND REC_STAT = 0 ";
		$rsExisteDR = sqlsrv_query($conn, $qExisteDR, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		//echo $qExisteDR;
		if(sqlsrv_num_rows($rsExisteDR) > 0){//ya existe existe existe
			$rsReporte = sqlsrv_fetch_array($rsExisteDR);
			$idReporteDia = $rsReporte['DAYREPORT_SNR'];
			//activa day_report
			$qDReport = "UPDATE DAY_REPORT SET 
				SYNC = 0,
				INFO = '".$comentarios."' 
				WHERE DAYREPORT_SNR = '".$idReporteDia."'";
			if(! sqlsrv_query($conn, $qDReport)){
				echo "qDReport: ".$qDReport."<br>";
			}
		}else{
			$queryReport = "SELECT NEWID() as idReporteDia 
				FROM DAY_REPORT 
				WHERE DAYREPORT_SNR = '00000000-0000-0000-0000-000000000000'";
			$rsReporte = sqlsrv_fetch_array(sqlsrv_query($conn, $queryReport));
			$idReporteDia = $rsReporte['idReporteDia'];
			$query = "INSERT INTO DAY_REPORT (
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
			VALUES(
				'$idReporteDia',
				'$idUsuario',
				getdate(),
				'$comentarios',
				0,
				'$fecha',
				'$fechaFin',
				'$idCreador',
				0,
				'$fecha')";
				//echo $query."<br>";
			if(! sqlsrv_query($conn, $query)){
				echo $query."<br>";
			}
		}
		
		for($j=0;$j<count($arrActividades);$j++){
			$qDRC = "SELECT DAYREPCOD_SNR, 
				VALUE,
				REC_STAT 
				FROM DAY_REPORT_CODE 
				WHERE DAYREPORT_SNR = '".$idReporteDia."' 
				and DAY_CODE_SNR = '".$arrActividades[$j]."' ";
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
				if(! sqlsrv_query($conn, $query)){
					echo "Error: query ".$query."<br>";
				}
				//echo $query."<br>";
			}else{//existe
				$reg = sqlsrv_fetch_array($rsDRC);
				if($reg['REC_STAT'] != 0){//habilita y actualiza las hrs.
					$qActualiza = "UPDATE DAY_REPORT_CODE SET 
						VALUE = '".$arrHoras[$j]."',
						SYNC = 0,
						REC_STAT = 0 
						WHERE DAYREPCOD_SNR = '".$reg['DAYREPCOD_SNR']."' ";
				}else{//existe y hay que sumar las horas
					$sumaHoras = $reg['VALUE'] + $arrHoras[$j];
					$qActualiza = "UPDATE DAY_REPORT_CODE SET 
						VALUE = '".$sumaHoras."',
						SYNC = 0 
						WHERE DAYREPCOD_SNR = '".$reg['DAYREPCOD_SNR']."' ";
					//echo $qActualiza;
				}
				if(! sqlsrv_query($conn, $qActualiza)){
					echo $qActualiza."<br>";
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
		
		if($repre == ''){
			$arrRepre = explode(",",$idUsuario);
		}else{
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
			
			//validar que no se pasen de horas en nigun dia.
			$arrUsuariosE = array();
			for($i=0;$i<=$dias_diferencia;$i++){
				$fechaDias = strtotime ( '+'.$i.' day' , strtotime ( $fecha ) ) ;
				if('Sun' != date("D", $fechaDias)){
					$fechaNueva = date("Y-m-d", $fechaDias);
					for($usu=0;$usu<count($arrRepre);$usu++){
						if($planVisita == 'plan'){
							$qRevisa = "SELECT V.USER_SNR,
								U.LNAME,
								U.MOTHERS_LNAME,
								U.FNAME,
								V.DATE, 
								SUM(VC.DAYCODE_WEIGHT) AS TOTAL
								FROM VISDAYPLAN V
								INNER JOIN VISDAYPLAN_CODE VC on VC.VISDAYPLAN_SNR = V.DAYPLAN_SNR
								INNER JOIN USERS U on U.USER_SNR = V.USER_SNR 
								WHERE V.REC_STAT = 0
								AND VC.REC_STAT = 0
								AND V.DATE = '".$fechaNueva."'
								AND V.USER_SNR = '".$arrRepre[$usu]."'
								GROUP BY U.LNAME, U.MOTHERS_LNAME, U.FNAME, V.USER_SNR, V.DATE ";
						} else {
							$qRevisa = "SELECT V.USER_SNR, 
								V.DATE,
								U.LNAME,
								U.MOTHERS_LNAME,
								U.FNAME,								
								SUM(VC.DAYCODE_WEIGHT) AS TOTAL
								FROM DAY_REPORT V
								INNER JOIN DAYREPORT_CODE VC on VC.VISDAYPLAN_SNR = V.DAYPLAN_SNR
								WHERE V.REC_STAT = 0
								AND VC.REC_STAT = 0
								AND V.DATE = '".$fechaNueva."'
								AND V.USER_SNR = '".$arrRepre[$usu]."'
								GROUP BY U.LNAME, U.MOTHERS_LNAME, U.FNAME, V.USER_SNR, V.DATE ";
						}
						//echo $qRevisa;
						$rsRevisa = sqlsrv_query($conn, $qRevisa);
						$horasRevisa = 0;
						while($arrRevisa = sqlsrv_fetch_array($rsRevisa)){
							$usuarioRevisa = $arrRevisa['LNAME'].' '.$arrRevisa['MOTHERS_LNAMELNAME'].' '.$arrRevisa['FNAME'];
							$horasRevisa = $arrRevisa['TOTAL'];
						}
						$totalHoras = $arrHoras + $horasRevisa;
						if($totalHoras > 8){
							$arrNoGrabados[] = array('nombre' => $usuarioRevisa, 'fecha' => $fechaNueva, 'horas' => $horasRevisa);
						}
					}
				}
			}

			if(count($arrNoGrabados) > 0){//al menos un usuario se excede en las horas
				$tabla = "<table border=\"1\" width=\"100%\">";
				$tabla .= "<thead>";
				$tabla .= "<tr>";
				$tabla .= "<td>Ruta</td>";
				$tabla .= "<td>usuario</td>";
				$tabla .= "<td>Fecha</td>";
				$tabla .= "<td>Horas</td>";
				$tabla .= "</tr>";
				$tabla .= "</thead>";
				$tabla .= "<tbody>";
				print_r($arrNoGrabados);
				for($i=0;$i<count($arrNoGrabados);$i++){
					//revisa si trae ruta en el nombre
					$arrRuta = explode(" - ", $arrNoGrabados[$i]['usuario']);
					$tabla .= "<tr>";
					if(count($arrRuta) == 1){//no trae la ruta en el apellido paterno
						$tabla .= "<td>".$arrNoGrabados[$i]['ruta']."</td>";
						$tabla .= "<td>".$arrNoGrabados[$i]['usuario']."</td>";
					}else{
						$tabla .= "<td>".$arrRuta[0]."</td>";
						$tabla .= "<td>".$arrRuta[1]."</td>";
					}
					$tabla .= "<td>".$arrNoGrabados[$i]['fecha']."</td>";
					$tabla .= "<td>".$arrNoGrabados[$i]['horas']."</td>";
					$tabla .= "</tr>";
				}
				$tabla .= "</tbody>";
				$tabla .= "</table>";
				echo "<script>alertErrorOtrasActividades('".$tabla."');</script>";
			}else{
				//echo $dias_diferencia."<br>";
				for($i=0;$i<=$dias_diferencia;$i++){
					$fechaDias = strtotime ( '+'.$i.' day' , strtotime ( $fecha ) ) ;
					if('Sun' != date("D", $fechaDias)){
						$fechaNueva = date("Y-m-d", $fechaDias);
						if($planVisita == 'plan'){
							if($repre != ''){
								for($usu=0;$usu<count($arrRepre);$usu++){
									grabaPlan($conn, $arrRepre[$usu], $fechaNueva, $comentarios, $arrActividades, $arrHoras);
								}
							}else{
								for($usu=0;$usu<count($arrRepre);$usu++){
									grabaPlan($conn, $arrRepre[$usu], $fechaNueva, $comentarios, $arrActividades, $arrHoras);
								}
							}
						}else{
							if($repre != ''){
								for($usu=0;$usu<count($arrRepre);$usu++){
									grabaVisita($conn, $arrRepre[$usu], $fechaNueva, $comentarios, $arrActividades, $arrHoras,$fechaFin,$fechaIni, $idUsuario);
								}
							}else{
								for($usu=0;$usu<count($arrRepre);$usu++){
									grabaVisita($conn,$arrRepre[$usu], $fechaNueva, $comentarios, $arrActividades, $arrHoras,$fechaFin,$fechaIni, $idUsuario);
								}
							}
						}
					}
				}
				echo "<script>
					/*$('#hdnIdsFiltroUsuarios').val('');
					actualizaCalendario();*/
					$('#btnCancelarOtrasActividades').click();
					</script>";
			}
		}else{
			///revisa que no se pasen las horas
			$arrHorasRevisa = explode(",",$arrHoras);
			$totalHoras = 0;
			for($i=0;$i<count($arrHorasRevisa);$i++){
				$totalHoras += $arrHorasRevisa[$i];
			}
			for($usu=0;$usu<count($arrRepre);$usu++){
				$qRevisa = "SELECT V.USER_SNR,
					U.USER_NR,
					U.LNAME,
					U.MOTHERS_LNAME,
					U.FNAME,
					V.DATE, ";
				if($planVisita == 'plan'){
					$qRevisa .= "SUM(VC.DAYCODE_WEIGHT) AS TOTAL
						FROM VISDAYPLAN V
						INNER JOIN VISDAYPLAN_CODE VC on VC.VISDAYPLAN_SNR = V.DAYPLAN_SNR ";
				} else {
					$qRevisa .= "SUM(VC.VALUE) AS TOTAL
						FROM DAY_REPORT V
						INNER JOIN DAY_REPORT_CODE VC on VC.DAYREPORT_SNR = V.DAYREPORT_SNR ";
				}
					$qRevisa .= "INNER JOIN USERS U on U.USER_SNR = V.USER_SNR 
					WHERE V.REC_STAT = 0
					AND VC.REC_STAT = 0
					AND V.DATE = '".$fecha."'
					AND V.USER_SNR = '".$arrRepre[$usu]."'
					GROUP BY U.USER_NR, U.LNAME, U.MOTHERS_LNAME, U.FNAME, V.USER_SNR, V.DATE ";
				//echo $qRevisa;
				$qRevisa = sqlsrv_query($conn, $qRevisa);
				$horasRevisa = 0;
				while($arrRevisa = sqlsrv_fetch_array($qRevisa)){
					$usuarioRevisa = $arrRevisa['LNAME'].' '.$arrRevisa['MOTHERS_LNAME'].' '.$arrRevisa['FNAME'];
					$horasRevisa = $arrRevisa['TOTAL'];
					$rutaRevisa = $arrRevisa['USER_NR'];
				}
				$finalHoras = $totalHoras + $horasRevisa;
				if($finalHoras > 8){
					$arrNoGrabados[] = array('ruta' => $rutaRevisa, 'usuario' => $usuarioRevisa, 'fecha' => $fecha, 'horas' => $horasRevisa);
					//echo "totalHoras: ".$totalHoras." horasRevisa: ".$horasRevisa."<br>";
				}
				
			}

			if(count($arrNoGrabados) > 0){//al menos un usuario se excede en las horas
				$tabla = "<table border=\"1\" width=\"100%\">";
				$tabla .= "<thead>";
				$tabla .= "<tr>";
				$tabla .= "<td>Ruta</td>";
				$tabla .= "<td>usuario</td>";
				$tabla .= "<td>Fecha</td>";
				$tabla .= "<td>Horas</td>";
				$tabla .= "</tr>";
				$tabla .= "</thead>";
				$tabla .= "<tbody>";
				//print_r($arrNoGrabados);
				for($i=0;$i<count($arrNoGrabados);$i++){
					//revisa si trae ruta en el nombre
					$arrRuta = explode(" - ", $arrNoGrabados[$i]['usuario']);
					$tabla .= "<tr>";
					if(count($arrRuta) == 1){//no trae la ruta en el apellido paterno
						$tabla .= "<td>".$arrNoGrabados[$i]['ruta']."</td>";
						$tabla .= "<td>".$arrNoGrabados[$i]['usuario']."</td>";
					}else{
						$tabla .= "<td>".$arrRuta[0]."</td>";
						$tabla .= "<td>".$arrRuta[1]."</td>";
					}
					$tabla .= "<td>".$arrNoGrabados[$i]['fecha']."</td>";
					$tabla .= "<td>".$arrNoGrabados[$i]['horas']."</td>";
					$tabla .= "</tr>";
				}
				$tabla .= "</tbody>";
				$tabla .= "</table>";
				echo "<script>alertErrorOtrasActividades('".$tabla."');</script>";
			}else{
				if($planVisita == 'plan'){
					if($repre != ''){
						for($usu=0;$usu<count($arrRepre);$usu++){
							grabaPlan($conn, $arrRepre[$usu], $fecha, $comentarios, $arrActividades, $arrHoras);
						}
					}else{
						for($usu=0;$usu<count($arrRepre);$usu++){
							grabaPlan($conn, $arrRepre[$usu], $fecha, $comentarios, $arrActividades, $arrHoras);
						}
					}
				}else{
					if($repre != ''){
						for($usu=0;$usu<count($arrRepre);$usu++){
							grabaVisita($conn, $arrRepre[$usu], $fecha, $comentarios, $arrActividades, $arrHoras,$fechaFin,$fechaIni,$idUsuario);
						}
					}else{
		
						if(count($arrRepre)==1){
							for($usu=0;$usu<count($arrRepre);$usu++){
								grabaVisita($conn, $idUsuario, $fecha, $comentarios, $arrActividades, $arrHoras,$fechaFin,$fechaIni,$idUsuario);
							}

						}else{
							for($usu=0;$usu<count($arrRepre);$usu++){
								grabaVisita($conn, $arrRepre[$usu], $fecha, $comentarios, $arrActividades, $arrHoras,$fechaFin,$fechaIni,$idUsuario);
							}
						}
					}
				}
				echo "<script>
					/*$('#hdnIdsFiltroUsuarios').val('');
					actualizaCalendario();*/
					$('#btnCancelarOtrasActividades').click();
					</script>";
			}
		}
	}
?>