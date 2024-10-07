<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$hoy = $_POST['hoy'];
		$ids = str_replace(",","','",$_POST['ids']);
		$ids = str_replace("'',''","','",$ids);
		$tipoUsuario = $_POST['tipoUsuario'];
		
		if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
			$ids = $_POST['idRuta'];
		}
		//echo $ids."<br>";
		$queryPlanes = "select 'p' as tipo, vpp.VISPERSPLAN_SNR as idPlan, p.fname + ' / ' + p.LNAME + ' ' + p.mothers_lname as nombre, cl.NAME as especialidad, i.street1, t.ZIP, 
			t.name as colonia, brick.NAME as brick, vpp.PLAN_DATE, vpp.TIME AS VRIJEME, 
			u.lname as ruta, 
			vpp.vispers_snr as idVisita 
			from VISPERSPLAN vpp 
			left outer join person p on vpp.PERS_SNR = p.PERS_SNR 
			left outer join CODELIST cl on p.SPEC_SNR = cl.CLIST_SNR 
			left outer join PERSLOCWORK plw on plw.PWORK_SNR = vpp.PWORK_SNR 
			left outer join INST i on plw.INST_SNR = i.INST_SNR 
			left outer join CITY t on i.CITY_SNR = t.CITY_SNR 
			left outer join BRICK brick on t.BRICK_SNR = brick.BRICK_SNR 
			left outer join USERS u on vpp.USER_SNR = u.USER_SNR 
			where vpp.USER_SNR in ('".$ids."') 
			and vpp.PLAN_DATE = '".$hoy."' 
			and vpp.USER_SNR = vpp.USER_SNR 
			and p.PERS_SNR = vpp.PERS_SNR
			and vpp.rec_stat = 0 
			and plw.REC_STAT = 0 
			union
			select 'i' as tipo, vpp.VISINSTPLAN_SNR as idPlan, i.name as nombre, '' as especialidad,  i.street1, t.ZIP,
			t.name as colonia, brick.NAME as brick, vpp.PLAN_DATE, vpp.TIME AS VRIJEME, u.lname as ruta, 
			vpp.VISINST_SNR as idVisita 
			from VISINSTPLAN vpp
			inner join INST i on vpp.INST_SNR = i.INST_SNR 
			inner join CITY t on i.CITY_SNR = t.CITY_SNR
			inner join BRICK brick on t.BRICK_SNR = brick.BRICK_SNR
			inner join USERS u on vpp.USER_SNR = u.USER_SNR 
			where vpp.USER_SNR in ('".$ids."') 
			and vpp.PLAN_DATE = '".$hoy."'
			and vpp.rec_stat = 0 
			order by VRIJEME ";
			
		//echo $queryPlanes;
			
		$rsPlanes = sqlsrv_query($conn, $queryPlanes, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		echo "<script>
			$('#tblPlanesInicio tbody').empty();
			$('#tblPlanesInicio tfoot').empty();";
		
		//$visitas = '';
		while($plan = sqlsrv_fetch_array($rsPlanes)){
			foreach ($plan['PLAN_DATE'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha_plan = substr($val, 0, 10);
				}
			}
			if($plan['idVisita'] != '00000000-0000-0000-0000-000000000000'){
				$color = "<font color=\"#04B404\">".$plan['nombre']."</font>";
			}else{
				$color = "<font color=\"#000000\">".$plan['nombre']."</font>";
			}
			if($tipoUsuario == 4){
				if($plan['tipo'] == 'p'){
					echo "$('#tblPlanesInicio tbody').append('<tr onclick=\"muestraPlan(\'".$plan['idPlan']."\')\"><td width=\"60%\"><b>".$color."<br>".$plan["especialidad"]."</b><br>".$plan["street1"].", ".$plan["ZIP"].', '.$plan["colonia"]."<br>".$plan["brick"]."</td><td width=\"20%\" align=\"center\">".$fecha_plan."</td><td width=\"20%\" align=\"center\">".$plan["VRIJEME"]."</td></tr>');";
				}else{
					echo "$('#tblPlanesInicio tbody').append('<tr onclick=\"muestraPlanInst(\'".$plan['idPlan']."\')\"><td width=\"60%\"><b>".$color."<br>".$plan["especialidad"]."</b><br>".$plan["street1"].", ".$plan["ZIP"].', '.$plan["colonia"]."<br>".$plan["brick"]."</td><td width=\"20%\" align=\"center\">".$fecha_plan."</td><td width=\"20%\" align=\"center\">".$plan["VRIJEME"]."</td></tr>');";
				}
			}else{
				if($plan['tipo'] == 'p'){
					echo "$('#tblPlanesInicio tbody').append('<tr onclick=\"muestraPlan(\'".$plan['idPlan']."\')\"><td width=\"10%\" align=\"center\">".explode(" ",$plan["ruta"])[0]."</td><td width=\"50%\"><b>".$color."<br>".$plan["especialidad"]."</b><br>".$plan["street1"].", ".$plan["ZIP"].', '.$plan["colonia"]."<br>".$plan["brick"]."</td><td width=\"20%\" align=\"center\">".$fecha_plan."</td><td width=\"20%\" align=\"center\">".$plan["VRIJEME"]."</td></tr>');";
				}else{
					echo "$('#tblPlanesInicio tbody').append('<tr onclick=\"muestraPlanInst(\'".$plan['idPlan']."\')\"><td width=\"10%\" align=\"center\">".explode(" ",$plan["ruta"])[0]."</td><td width=\"50%\"><b>".$color."<br>".$plan["especialidad"]."</b><br>".$plan["street1"].", ".$plan["ZIP"].', '.$plan["colonia"]."<br>".$plan["brick"]."</td><td width=\"20%\" align=\"center\">".$fecha_plan."</td><td width=\"20%\" align=\"center\">".$plan["VRIJEME"]."</td></tr>');";
				}
			}
		}
		echo "$('#tblPlanesInicio tfoot').append('<tr><td>Total: ". sqlsrv_num_rows($rsPlanes) ."</td></tr>');
			</script>";
		
	}
?>