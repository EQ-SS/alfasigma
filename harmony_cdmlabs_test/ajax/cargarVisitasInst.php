<?php
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r", "\t");
	$reemplazar=array(" ", " ", " ", " "," ");
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$year = $_POST['year'];
		$idInst = $_POST['idInst'];
		$idUsuario = $_POST['idUsuario'];
		$tipoUsuario = $_POST['tipoUsuario'];

		if(isset($_POST['repre']) && $_POST['repre'] != ''){
			$repre = $_POST['repre'];
		}else{
			$repre = '';
		}
			
		$queryVisitas = "select visit_date as Fecha_Vis,
		TIME,u.lname + ' ' + u.fname as Rep,u.user_nr,
		codigo_vis.name as Tipo_Vis,
		vp.info as informacion_vis,
		vp.info_nextvisit as obj_vis,
		vp.visinst_snr,vp.user_snr,
		vp.inst_snr,
		(select info from visinstplan where visinstplan.inst_snr=vp.inst_snr and PLAN_DATE in (select max(PLAN_DATE) from visinstplan)) info_Ult_Plan,
		substring(c.NAME, 1, 4) + substring(c.NAME, 8, 3) as ciclo
		from visitinst vp
		inner join users u on u.USER_SNR = vp.USER_SNR
		inner join codelist codigo_vis on codigo_vis.clist_snr=vp.VISIT_CODE_SNR 
		left outer join cycles c on vp.visit_date between c.START_DATE and c.FINISH_DATE 
		where vp.rec_stat=0 ";
		if($tipoUsuario == 4){
			$queryVisitas .= "and  vp.user_snr = '".$idUsuario."' ";
		}else{
			$queryVisitas .= "and  vp.user_snr in ('".$idUsuario."','".$repre."') ";
		}
		$queryVisitas .= "and vp.inst_snr = '".$idInst."' 
		and substring(c.name,1,4) = '".$year."' 
		order by vp.visit_date desc";
		//echo $queryVisitas;
		
		//echo "<br><br>";
		
		$rsVisitas = sqlsrv_query($conn, $queryVisitas);
		
		$queryCiclosVisitas = "select substring(ciclos.name,1,4) anio, 
            substring(ciclos.name,9,2) ciclo, 
            count(*) total 
            from visitinst vp, cycles ciclos 
            where vp.visit_date between ciclos.start_date and ciclos.finish_date 
            and vp.rec_stat=0 
            and vp.inst_snr='".$idInst."' 
            and vp.user_snr='".$idUsuario."' 
			and substring(ciclos.name,1,4) = '".$year."' 
			group by ciclos.name ";
		
		//echo $queryCiclosVisitas;
		
		$rsCiclosVisitas = sqlsrv_query($conn, $queryCiclosVisitas);
		
		echo "<script>$('#tblVisitasInst tbody').empty();";
		
		//$visitas = '';
		$contadorVisita = 0;
		$sinDatos = 'Sin datos que mostrar';
		while($visita = sqlsrv_fetch_array($rsVisitas)){
			$contadorVisita++;
			foreach ($visita['Fecha_Vis'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha_vis = substr($val, 0, 10);
				}
			}
			//echo "$('#tblVisitasInst tbody').append('<tr onClick=\"muestraVisitaInst(\'".$visita['visinst_snr']."\');\"><td style=\"width:15%;\">".$fecha_vis."</td><td style=\"width:10%;\">".$visita['time']."</td><td style=\"width:35%;\">".$visita['Rep']."</td><td style=\"width:30%;\">".$visita['Tipo_Vis']."</td><td style=\"width:10%;\">".$visita['ciclo']."</td></tr>');";
			echo "$('#tblVisitasInst tbody').append('<tr onClick=\"muestraVisitaInst(\'".$visita['visinst_snr']."\');\"><td style=\"width:11%;\">".$visita['ciclo']."</td><td style=\"width:8%;\">".$visita['user_nr']."</td><td style=\"width:13%;\">".$fecha_vis."</td><td style=\"width:9%;\">".$visita['TIME']."</td><td style=\"width:19%;\">".utf8_encode($visita['Tipo_Vis'])."</td><td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($visita['informacion_vis']))."</td><td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($visita['obj_vis']))."</td></tr>');";

		}
		if($contadorVisita == 0){
			echo "$('#tblVisitasInst tbody').append('<tr><td style=\"width:100%;\">".$sinDatos."</td></tr>');";
		}

		//echo $visitas;
		$totalVisitas = 0;
		for($i=1;$i<14;$i++){
			echo "$('#cicloVisitaInst'+".ltrim($i, '0').").text('0');";
		}
		while($cicloVisita = sqlsrv_fetch_array($rsCiclosVisitas)){
			echo "$('#cicloVisitaInst'+".ltrim($cicloVisita['ciclo'], '0').").text('".$cicloVisita['total']."');";
			$totalVisitas += $cicloVisita['total'];
		}
		echo "$('#cicloVisitasAcumuladoInst').text('".$totalVisitas."');";
		
		echo "</script>";
		
	}
?>