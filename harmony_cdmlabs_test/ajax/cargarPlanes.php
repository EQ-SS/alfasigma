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
		
		$queryPlanes = "select PLAN_DATE as Fecha_Plan, vp.time as Hora_Plan, u.user_nr, u.lname +' '+ u.fname as Rep, cd.name as Tipo_Plan, 
			vp.info as Objetivo, vp.vispersplan_snr, vp.user_snr, vp.pers_snr, 
			(select top 1 info from visitpers where visitpers.pers_snr=vp.pers_snr and CREATION_TIMESTAMP in (select max(CREATION_TIMESTAMP) from visitpers)) as info_Ult_Vis,
			c.NAME
			from vispersplan vp, users u, codelist cd, CYCLES c 
			where cd.clist_snr=vp.plan_code_SNR 
			and vp.user_snr=u.user_snr 
			and vp.rec_stat=0 ";
			if($tipoUsuario == 4){
				$queryPlanes .= "and vp.user_snr = '".$reg['user_snr']."' ";
			}else{
				$queryPlanes .= "and vp.user_snr in ('".$reg['user_snr']."','".$idUsuario."') ";
			}
			$queryPlanes .= "and vp.pers_snr='".$idPersona."'
			and vp.PLAN_DATE between START_DATE and FINISH_DATE
			and substring(c.name,1,4) = '".$year."' 
			order by fecha_plan desc";
			
		//echo $queryPlanes;
		//vrijeme->time,vpersplan_snr->vispersplan_snr,visit_code->plan_code_SNR
			
		$rsPlanes = sqlsrv_query($conn, $queryPlanes);
			
		$queryCiclos = "select substring(ciclos.name,1,4) anio, 
				substring(ciclos.name,9,2) ciclo, 
				count(*) total 
				from vispersplan vp, cycles ciclos 
				where vp.PLAN_DATE between ciclos.start_date and ciclos.finish_date 
				and vp.rec_stat=0 
				and vp.user_snr = '".$idUsuario."' 
				and vp.pers_snr='".$idPersona."' 
				and substring(ciclos.name,1,4) = '".$year."' 
				group by ciclos.name";
				
		$rsCiclos = sqlsrv_query($conn, $queryCiclos);
		
		echo "<script>$('#tblPlan tbody').empty();";
		
		//$visitas = '';
		$contadorPlan = 0;
		$sinDatos = 'Sin datos que mostrar';
		while($plan = sqlsrv_fetch_array($rsPlanes)){
			$contadorPlan++;
			if(is_object($plan['Fecha_Plan'])){
				foreach ($plan['Fecha_Plan'] as $key => $val) {
					if(strtolower($key) == 'date'){
						$fecha_plan = substr($val, 0, 10);
					}
				}
			}
			echo "$('#tblPlan tbody').append('<tr onClick=\"muestraPlan(\'".$plan['vispersplan_snr']."\');\"><td style=\"width:15%;\">".substr($plan['NAME'],0,5).substr($plan['NAME'],8,2)."</td><td style=\"width:10%;\">".$plan['user_nr']."</td><td style=\"width:15%;\">".$fecha_plan."</td><td style=\"width:15%;\">".$plan['Hora_Plan']."</td><td style=\"width:45%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($plan['Objetivo']))."</td></tr>');";
		}
		if($contadorPlan == 0){
			echo "$('#tblPlan tbody').append('<tr><td style=\"width:100%;\">".$sinDatos."</td></tr>');";
		}
		
		//echo $visitas;
		for($i=1;$i<14;$i++){
			echo "$('#ciclo'+".$i.").text('0');";
			
		}
		$total = 0;
		while($ciclo = sqlsrv_fetch_array($rsCiclos)){
			echo "$('#ciclo'+".ltrim($ciclo['ciclo'], '0').").text('".$ciclo['total']."');";
			$total += $ciclo['total'];
		}
		echo "$('#acumulado').text('".$total."');";
		
		echo "</script>";
		
	}
?>