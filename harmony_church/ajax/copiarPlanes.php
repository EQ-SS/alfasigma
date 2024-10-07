<?php
	include "../conexion.php";
	$fechaIni = $_POST['fechaIni'];
	$fechaFin = $_POST['fechaFin'];
	$visitados = $_POST['visitados'];
	$idUsuario = $_POST['idUsuario'];
	$fechaObjetivo = $_POST['fechaObjetivo'];
	$ids = $_POST['ids'];
	
	$queryPlanes = "select 'p' as tipo, 
		vp.vpersplan_snr  as idPlan,
		vp.USER_SNR, 
		vp.VRIJEME, 
		vp.VISIT_CODE, 
		vp.INFO,  
		vp.PERS_SNR, 
		vp.PWORK_SNR, 
		vp.VISPERS_SNR, 
		vp.datum
		from VISPERSPLAN vp, person p
		where vp.REC_STAT = 0 
		and p.REC_STAT = 0
		and p.pers_snr = vp.pers_snr 
		and vp.datum >= '".$fechaIni."'
		and vp.datum <= '".$fechaFin."' 
		and vp.user_snr in ('".$ids."') ";
			
	if($visitados == 'visitados'){
		$queryPlanes .= "and vp.vispers_snr <> '00000000-0000-0000-0000-000000000000' ";
	}else if($visitados == 'novisitados') {
		$queryPlanes .= "and vp.vispers_snr = '00000000-0000-0000-0000-000000000000' ";
	}
	
	$queryPlanes .= "union select 'i' as tipo, 
			vp.visinstplan_snr  as idPlan,
			vp.USER_SNR, 
			vp.VRIJEME, 
			vp.VISIT_CODE, 
			vp.INFO,  
			vp.INST_SNR AS PERS_SNR, 
			null as PWORK_SNR, 
			vp.VISINST_SNR AS VISPERS_SNR, 
			vp.datum
			from VISINSTPLAN vp, inst p
			where vp.REC_STAT = 0 
			and p.REC_STAT = 0
			and p.inst_snr = vp.inst_snr 
			and vp.datum >= '".$fechaIni."'
			and vp.datum <= '".$fechaFin."' 
			and vp.user_snr in ('".$ids."') ";
			
	if($visitados == 'visitados'){
		$queryPlanes .= "and vp.visinst_snr <> '00000000-0000-0000-0000-000000000000' ";
	}else if($visitados == 'novisitados') {
		$queryPlanes .= "and vp.visinst_snr = '00000000-0000-0000-0000-000000000000' ";
	}
	
	$queryPlanes .= " order by datum, VRIJEME ";
	
	$rsPlanes = sqlsrv_query($conn, $queryPlanes);
	
	$i=0;
	while($plan = sqlsrv_fetch_array($rsPlanes)){
		foreach ($plan['datum'] as $key => $val) {
			if(strtolower($key) == 'date'){
				$fecha = substr($val, 0, 10);
			}
		}
		
		if($i == 0){
			$diaAnt = $fecha;
			$diaActual = $fecha;
			$month = substr($fechaObjetivo, 5, 2);
			$day = substr($fechaObjetivo, 8, 2);
			$year = substr($fechaObjetivo, 0, 4);
			$dia = date("N", mktime(0, 0, 0, $month, $day, $year));
			
			if($dia == 6){//sabado
				$nuevafecha = strtotime ( '+2 day' , strtotime ( $fechaObjetivo ) ) ;
				$fechaObjetivo = date ( 'Y-m-d' , $nuevafecha );
			}else if($dia == 7){//domingo
				$nuevafecha = strtotime ( '+1 day' , strtotime ( $fechaObjetivo ) ) ;
				$fechaObjetivo = date ( 'Y-m-d' , $nuevafecha );
			}
			$diaActual = $fechaObjetivo;
			
		}else{
			$diaActual = $fecha;
			if($diaActual != $diaAnt){//dia cambio
				$nuevafecha = strtotime ( '+1 day' , strtotime ( $fechaObjetivo ) ) ;
				$fechaObjetivo = date ( 'Y-m-d' , $nuevafecha );
				
				$month = substr($fechaObjetivo, 5, 2);
				$day = substr($fechaObjetivo, 8, 2);
				$year = substr($fechaObjetivo, 0, 4);
				$dia = date("N", mktime(0, 0, 0, $month, $day, $year));
				
				if($dia == 6){//sabado
					$nuevafecha = strtotime ( '+2 day' , strtotime ( $fechaObjetivo ) ) ;
					$fechaObjetivo = date ( 'Y-m-d' , $nuevafecha );
				}else if($dia == 7){//domingo
					$nuevafecha = strtotime ( '+1 day' , strtotime ( $fechaObjetivo ) ) ;
					$fechaObjetivo = date ( 'Y-m-d' , $nuevafecha );
				}
				$diaAnt = $diaActual;
			}
		}
		
		if($plan['tipo'] == 'p'){
			//revisa si existe el plan
			$rsExiste = sqlsrv_query($conn, "select * from vispersplan where datum = '".$fechaObjetivo."' and pers_snr = '".$plan['PERS_SNR']."'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsExiste) == 0){
				$insertaPlan = "insert into VISPERSPLAN(VPERSPLAN_SNR, USER_SNR, DATUM, VRIJEME, VISIT_CODE, INFO, rec_stat, PERS_SNR, PWORK_SNR, SYNC) 
					values(NEWID(), '".$plan['USER_SNR']."', '".$fechaObjetivo."', '".$plan['VRIJEME']."', '".$plan['VISIT_CODE']."', '".$plan['INFO']."', 0,'".$plan['PERS_SNR']."', '".$plan['PWORK_SNR']."',0)";
				if(! sqlsrv_query($conn, $insertaPlan)){
					echo "Error al copiar el plan: ".$insertaPlan."<br>";
				}
			}
		}else{
			//revisa si existe el plan
			$rsExiste = sqlsrv_query($conn, "select * from visinstplan where datum = '".$fechaObjetivo."' and inst_snr = '".$plan['PERS_SNR']."'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsExiste) == 0){
				$insertaPlan = "insert into VISINSTPLAN(VISINSTPLAN_SNR, USER_SNR, DATUM, VRIJEME, VISIT_CODE, INFO, rec_stat, INST_SNR, SYNC) 
					values(NEWID(), '".$plan['USER_SNR']."', '".$fechaObjetivo."', '".$plan['VRIJEME']."', '".$plan['VISIT_CODE']."', '".$plan['INFO']."', 0,'".$plan['PERS_SNR']."',0)";
				if(! sqlsrv_query($conn, $insertaPlan)){
					echo "Error al copiar el plan: ".$insertaPlan."<br>";
				}
			}
		}
		$i++;
	}
	echo "<script>
		alert('Se terminaron de copiar los planes');
		actualizaCalendario();
		$('#cerrarInformacion').click();
		update_calendar()
		</script>";
?>