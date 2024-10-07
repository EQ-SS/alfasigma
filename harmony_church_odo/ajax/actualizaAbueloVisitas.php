<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$idPers = $_POST['idPersona'];
		$idUsuario = $_POST['idUsuario'];
		echo "<script>";
		$queryVisitas = "select visit_date as Fecha_Vis,
			visit_time,u.lname + ' ' + u.fname as Rep,
			codigo_vis.name as Tipo_Vis,
			vp.info as informacion_vis,
			vp.vispers_snr,vp.user_snr,
			vp.pers_snr,vp.inst_snr,
			vis_acomp.name as vis_acomp,
			(select info from vispersplan where vispersplan.pers_snr=vp.pers_snr and datum in (select max(datum) from vispersplan)) info_Ult_Plan,
			vp.vpersplan_snr as idPlan, cycles.c_name as ciclo
			from visitpers vp
			inner join users u on u.USER_SNR = vp.USER_SNR
			inner join codelist codigo_vis on codigo_vis.clist_snr=vp.VISIT_CODE 
			inner join codelist vis_acomp on vis_acomp.clist_snr = vp.rm_user_snr 
			left outer join binarydata firma on firma.rec_key = vp.vispers_snr 
			left outer join cycles on vp.VISIT_DATE between cycles.start_date and cycles.FINISH_DATE 
			where vp.user_snr = '".$idUsuario."' 
			and vp.rec_stat=0 
			and vp.pers_snr = '".$idPers."' 
			order by vp.visit_date desc";
		
		$rsVisitas = sqlsrv_query($conn, $queryVisitas);
		echo "var pagina = window.opener.$('#hdnPaginaPersonas').val();
				var ids = window.opener.$('#hdnIdsEnviarPersonas').val();
				window.opener.$('#tblVisitas tbody').empty();";
				
		while($visita = sqlsrv_fetch_array($rsVisitas)){
			foreach ($visita['Fecha_Vis'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha_vis = substr($val, 0, 10);
				}
			}
			echo "window.opener.$('#tblVisitas tbody').append('<tr onClick=\"muestraVisita(\'".$visita['vispers_snr']."\',\'".$visita['idPlan']."\');\"><td>".$fecha_vis."</td><td>".$visita['visit_time']."</td><td>".$visita['Rep']."</td><td>".$visita['Tipo_Vis']."</td><td>".substr($visita['ciclo'],0,5).substr($visita['ciclo'],8,2)."</td></tr>');";
		}
		$queryCiclosVisitas = "select substring(ciclos.c_name,1,4) anio, 
			substring(ciclos.c_name,9,2) ciclo, 
			count(*) total 
			from visitpers vp, cycles ciclos 
			where vp.visit_date between ciclos.start_date and ciclos.finish_date 
			and vp.rec_stat=0 
			and vp.pers_snr='".$idPers."' 
			and vp.user_snr='".$idUsuario."' 
			and substring(ciclos.c_name,1,4) = (select max(substring(c_name,1,4)) from cycles) 
			group by ciclos.c_name ";
		
		//echo $queryCiclosVisitas;
		
		$rsCiclosVisitas = sqlsrv_query($conn, $queryCiclosVisitas);
		
		$totalVisitas = 0;
		while($cicloVisita = sqlsrv_fetch_array($rsCiclosVisitas)){
			echo "window.opener.$('#cicloVisita'+".ltrim($cicloVisita['ciclo'], '0').").text('".$cicloVisita['total']."');";
			$totalVisitas += $cicloVisita['total'];
		}
		echo "window.opener.$('#cicloVisitasAcumulado').text('".$totalVisitas."');";
		echo "window.opener.$('#hdnIdPersona').val('".$idPers."');";
		echo "window.opener.nuevaPagina(pagina,'".date("Y-m-d")."',ids,'');";
		echo "window.close();";
		echo "</script>";
	}
	
?>