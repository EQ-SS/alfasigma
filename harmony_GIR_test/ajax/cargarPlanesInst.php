<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$year = $_POST['year'];
		$idInst = $_POST['idInst'];
		$idUsuario = $_POST['idUsuario'];
		
		$queryPlanes = "select plan_date as Fecha_Plan,
			vp.time as Hora_Plan, u.user_nr,
			u.lname+' '+u.fname as Rep, 
			cd.name as Tipo_Plan,vp.info as Objetivo, 
			vp.VISINSTPLAN_SNR,vp.user_snr,vp.inst_snr, 
			(select info from visitinst where visitinst.inst_snr=vp.inst_snr and visit_date in (select max(visit_date) from visitinst))  info_Ult_Vis, 
			substring(c.NAME, 1, 4) +  substring(c.NAME, 8, 3) as ciclo
			from visinstplan vp
			inner join users u on vp.user_snr=u.user_snr 
			left outer join codelist cd on cd.clist_snr = vp.PLAN_CODE_SNR
			left outer join cycles c on vp.PLAN_DATE between c.START_DATE and c.FINISH_DATE
			where vp.rec_stat=0 
			and u.user_snr = '".$idUsuario."' 
			and vp.INST_SNR = '".$idInst."' 
			and substring(c.name,1,4) = '".$year."' 
			order by fecha_plan desc ";
			
		//echo $queryPlanes."<br><br>";
			
		$rsPlanes = sqlsrv_query($conn, $queryPlanes);
			
		$queryCiclos = "select substring(ciclos.name,1,4) anio, 
				substring(ciclos.name,6,2) ciclo, 
				count(*) total 
				from visinstplan vp, cycles ciclos 
				where vp.plan_date between ciclos.start_date and ciclos.finish_date 
				and vp.rec_stat=0 
				and vp.user_snr = '".$idUsuario."' 
				and vp.inst_snr='".$idInst."'  
				and substring(ciclos.name,1,4) = '".$year."' 
				group by ciclos.name";
		//echo $queryCiclos;
				
		$rsCiclos = sqlsrv_query($conn, $queryCiclos);
		
		echo "<script>
		$('#tblPlanesInstituciones tbody').empty();";
		
		//$visitas = '';
		$contadorPlan = 0;
		$sinDatos = 'Sin datos que mostrar';
		while($plan = sqlsrv_fetch_array($rsPlanes)){
			$contadorPlan++;
			foreach ($plan['Fecha_Plan'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha_plan = substr($val, 0, 10);
				}
			}
			//echo "$('#tblPlanesInstituciones tbody').append('<tr onClick=\"muestraPlan(\'".$plan['VISINSTPLAN_SNR']."\');\"><td style=\"width:15%;\">".$fecha_plan."</td><td style=\"width:10%;\">".$plan['Hora_Plan']."</td><td style=\"width:25%;\">".$plan['Rep']."</td><td style=\"width:25%;\">".$plan['Tipo_Plan']."</td><td style=\"width:10%;\">".$plan['Objetivo']."</td></tr>');";
			echo "$('#tblPlanesInstituciones tbody').append('<tr onClick=\"muestraPlanInst(\'".$plan['VISINSTPLAN_SNR']."\');\"><td style=\"width:15%;\">".$plan['ciclo']."</td><td style=\"width:10%;\">".$plan['user_nr']."</td><td style=\"width:15%;\">".$fecha_plan."</td><td style=\"width:15%;\">".$plan['Hora_Plan']."</td><td style=\"width:45%;\">".$plan['Objetivo']."</td></tr>');";
		}
		if($contadorPlan == 0){
			echo "$('#tblPlanesInstituciones tbody').append('<tr><td style=\"width:100%;\">".$sinDatos."</td></tr>');";
		}

		//echo $visitas;
		for($i=1;$i<14;$i++){
			echo "$('#cicloInst'+".$i.").text('0');";
			
		}
		$total = 0;
		while($ciclo = sqlsrv_fetch_array($rsCiclos)){
			echo "$('#cicloInst'+".ltrim($ciclo['ciclo'], '0').").text('".$ciclo['total']."');";
			$total += $ciclo['total'];
		}
		echo "$('#acumuladoInst').text('".$total."');";
		
		echo "</script>";
		
	}
?>