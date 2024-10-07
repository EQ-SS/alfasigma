<?php
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r", "\t");
	$reemplazar=array(" ", " ", " ", " "," ");
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$year = $_POST['year'];
		$idPersona = $_POST['idPersona'];
		$idUsuario = $_POST['idUsuario'];
		$tipoUsuario = $_POST['tipoUsuario'];

		$queryPersona = "select u.user_snr,
				(select top 1 VISIT_DATE from visitpers where PERS_SNR = p.PERS_SNR and REC_STAT = 0 order by VISIT_DATE desc ) as ultimaVisita,
				(SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".date("Y-m-d")."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE ";
		if($tipoUsuario == 4){
			$queryPersona .= "and vp.user_snr = '".$idUsuario."') as visitas ";
		}else{
			$queryPersona .= "and vp.user_snr = '".$idPersona."') as visitas ";
		}
		$queryPersona .= "from person p
				left outer join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR and psw.REC_STAT = 0 
				left outer join users u on u.user_snr = psw.USER_SNR
				where p.pers_snr = '".$idPersona."'
				and p.REC_STAT = 0";

		$reg = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPersona));
		
		$queryVisitas = "select visit_date as Fecha_Vis,
			time,u.lname + ' ' + u.fname as Rep, u.user_nr,
			codigo_vis.name as Tipo_Vis,
			vp.info as informacion_vis,
			vp.info_nextvisit as obj_vis,
			vp.vispers_snr,vp.user_snr,
			vp.pers_snr,
			vp.pwork_snr,
			(select info from vispersplan where vispersplan.pers_snr=vp.pers_snr and visit_date in (select max(plan_date) from vispersplan)) info_Ult_Plan,
			vp.vispersplan_snr as idPlan, cycles.name as ciclo
			from visitpers vp
			inner join users u on u.USER_SNR = vp.USER_SNR
			inner join codelist codigo_vis on codigo_vis.clist_snr=vp.visit_code_snr 
			left outer join binarydata firma on firma.record_key = vp.vispers_snr 
			left outer join cycles on vp.VISIT_DATE between cycles.start_date and cycles.FINISH_DATE 
			where vp.rec_stat=0 ";
			if($tipoUsuario == 4){
				$queryVisitas .= " and  vp.user_snr = '".$reg['user_snr']."' ";
			}else{
				$queryVisitas .= " and  vp.user_snr in ('".$reg['user_snr']."','".$idUsuario."') ";
			}
			$queryVisitas .= "and vp.pers_snr = '".$idPersona."'";
			$queryVisitas .= "and substring(cycles.name,1,4) = '".$year."' 
			order by vp.visit_date desc";
		
		//echo $queryVisitas;
		
		//echo "<br><br>";
		
		$rsVisitas = sqlsrv_query($conn, $queryVisitas);
		
		$queryCiclosVisitas = "select substring(ciclos.name,1,4) anio, 
            substring(ciclos.name,9,2) ciclo, 
            count(*) total 
            from visitpers vp, cycles ciclos 
            where vp.visit_date between ciclos.start_date and ciclos.finish_date 
            and vp.rec_stat=0 
            and vp.pers_snr='".$idPersona."' 
            and vp.user_snr='".$idUsuario."' 
			and substring(ciclos.name,1,4) = '".$year."' 
			group by ciclos.name ";
		
		//echo $queryCiclosVisitas;
		
		$rsCiclosVisitas = sqlsrv_query($conn, $queryCiclosVisitas);
		
		echo "<script>$('#tblVisitas tbody').empty();";
		
		//$visitas = '';
		$contadorVisita = 0;
		$sinDatos = 'Sin datos que mostrar';
		if(is_object($reg['ultimaVisita'])){
			foreach ($reg['ultimaVisita'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$ultimaVisita = substr($val, 0, 10);
				}
			}
		}

		while($visita = sqlsrv_fetch_array($rsVisitas)){
			$contadorVisita++;
			if(is_object($visita['Fecha_Vis'])){
				foreach ($visita['Fecha_Vis'] as $key => $val) {
					if(strtolower($key) == 'date'){
						$fecha_vis = substr($val, 0, 10);
					}
				}
			}
			echo "$('#tblVisitas tbody').append('<tr onClick=\"muestraVisita(\'".$visita['vispers_snr']."\',\'".$visita['idPlan']."\');\"><td style=\"width:11%;\">".substr($visita['ciclo'],0,5).substr($visita['ciclo'],8,2)."</td><td style=\"width:8%;\">".$visita['user_nr']."</td><td style=\"width:13%;\">".$fecha_vis."</td><td style=\"width:9%;\">".$visita['time']."</td><td style=\"width:19%;\">".utf8_encode($visita['Tipo_Vis'])."</td><td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($visita['informacion_vis']))."</td><td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($visita['obj_vis']))."</td></tr>');";
		}
		if($contadorVisita == 0){
			echo "$('#tblVisitas tbody').append('<tr><td style=\"width:100%;\">".$sinDatos."</td></tr>');";
		}

		//echo $visitas;
		$totalVisitas = 0;
		for($i=1;$i<14;$i++){
			echo "$('#cicloVisita'+".ltrim($i, '0').").text('0');";
		}
		while($cicloVisita = sqlsrv_fetch_array($rsCiclosVisitas)){
			echo "$('#cicloVisita'+".ltrim($cicloVisita['ciclo'], '0').").text('".$cicloVisita['total']."');";
			$totalVisitas += $cicloVisita['total'];
		}
		echo "$('#cicloVisitasAcumulado').text('".$totalVisitas."');";
		
		echo "</script>";
	}
?>