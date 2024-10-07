<?php
	include "../conexion.php";
	if(isset($_POST['pantalla']) && $_POST['pantalla'] == 'cal'){
		$pantalla = 'cal';
	}else{
		$pantalla = '';
	}
	if(isset($_POST['regreso']) && $_POST['regreso'] != ''){
		$regreso = $_POST['regreso'];
	}else{
		$regreso = '';
	}
	
	if(isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] != ''){
		$tipoUsuario = $_POST['tipoUsuario'];
	}else{
		$tipoUsuario = '';
	}
	
	if(isset($_POST['idUsuario']) && $_POST['idUsuario']){
		$idUser = $_POST['idUsuario'];
	}else{
		$idUser = '00000000-0000-0000-0000-000000000000';
	}
	
	
	if(isset($_POST['idPlan']) && $_POST['idPlan'] != ''){
		$idPlan = $_POST['idPlan'];
		
		$query = "select vp.plan_date as fecha_plan, 
			vp.time as hora_plan,
			vp.info as objetivo,
			vp.visinstplan_snr as plan_id,
			vp.inst_snr as inst_id,
			i.name as nombre_inst, 
			i.street1 as direccion,
			cp.name as colonia,
			cp.zip as cpostal,
			pob.name as pob,
			edo.name as edo,
			vp.user_snr as user_id,
			u.lname+' '+u.fname as repre, 
			vp.visinst_snr,
			vp.plan_code_snr as codigo_visita,
			(select info from VISINSTPLAN where VISINSTPLAN_SNR = vp.visinst_snr and plan_date in (select max(plan_date) from vispersplan)) as info_ult_vis
			from visinstplan vp,inst i, city cp, district pob, state edo, users u 
			where vp.inst_snr=i.inst_snr 
			and i.rec_stat=0 and vp.rec_stat=0 
			and cp.city_snr=i.city_snr  
			and cp.distr_snr=pob.distr_snr 
			and cp.state_snr=edo.state_snr 
			and u.user_snr=vp.user_snr 
			and vp.visinstplan_snr = '".$idPlan."'";
			
	}else if(isset($_POST['idInst']) && $_POST['idInst'] != ''){
		$idPlan = '';
		$idInst = $_POST['idInst'];
		
		if(isset($_POST['repre']) && $_POST['repre'] != ''){
			$repre = $_POST['repre'];
		}else{
			$repre = '';
		}
		
		//echo "select lname + ' ' + mother_lname + ' ' + fname as nombre from users where USER_SNR = '".$idUser."'<br>";
		
		$queryRepre = sqlsrv_fetch_array(sqlsrv_query($conn, "select lname + ' ' + mothers_lname + ' ' + fname as nombre from users where USER_SNR = '".$idUser."'"));
		
		$query ="select i.name as nombre_inst, 
			i.STREET1 as direccion,
			cp.name as colonia,
			cp.zip as cpostal,
			pob.name as pob,
			edo.name as edo,
			(select INFO_NEXTVISIT from VISITINST where INST_SNR = i.inst_snr and VISIT_DATE in (select max(VISIT_DATE) from visitinst)) as info_ult_vis 
			from inst i, city cp, district pob, state edo
			where i.INST_SNR='".$idInst."'
			and cp.city_snr=i.city_snr
			and cp.distr_snr=pob.distr_snr 
			and cp.state_snr=edo.state_snr ";
	}
	
	//echo $query;
	
	$plan = sqlsrv_fetch_array(sqlsrv_query($conn, $query));
	
	if($idPlan == ''){
		if( isset($_POST['fechaPlan']) && $_POST['fechaPlan'] != ''){
			$fecha_plan = $_POST['fechaPlan'];
		}else{
			$fecha_plan = date("Y-m-d");
		}
		$hora = "00";
		$minutos = "00";
		$objetivoPlan = $plan['info_ult_vis'];
		$idVisita = '';
		$repreNombre = $plan['repre'] = $queryRepre['nombre'];
		if($tipoUsuario == 4){
			$repre = $idUser;
		}
		$codigo_visita = '26102062-27FE-4711-A2DC-27435C84DFE1';
	}else{
		if(is_object($plan['fecha_plan'])){
			foreach ($plan['fecha_plan'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha_plan = substr($val, 0, 10);
				}
			}
		}else{
			$fecha_plan = '';
		}
		$arrHora = explode(":", str_replace(".",":",$plan['hora_plan']));
		$hora = $arrHora[0];
		$minutos = $arrHora[1];
		$objetivoPlan = $plan['objetivo'];
		$idInst = $plan['inst_id'];
		$idVisita = $plan['visinst_snr'];
		//$info_ult_vis = $plan['info_ult_vis'];
		$idUsuario = $plan['user_id'];
		$codigo_visita = $plan['codigo_visita'];
		$repre = $plan['user_id'];
		$repreNombre =  $plan['repre'];
	}
	
	echo "<script>
		$('#hdnIdInst').val('".$idInst."');
		$('#hdnIdPlanInst').val('".$idPlan."');
		$('#hdnPantallaPlanInst').val('".$pantalla."');
		$('#hdnIdVisitaPlanInst').val('".$idVisita."');
		$('#btnGuardarPlanInst').prop('disabled', false);
		$('#lblNombreInstPlanInst').text('".utf8_encode($plan['nombre_inst'])."');
		$('#lblCallePlanInst').text('".strtoupper(utf8_encode($plan['direccion']))."');
		$('#lblCPPlanInst').text('".$plan['cpostal']."');
		$('#lblColoniaPlanInst').text('".strtoupper(utf8_encode($plan['colonia']))."');
		$('#lblPoblacionPlanInst').text('".strtoupper(utf8_encode($plan['pob']))."');
		$('#lblEstadoPlanInst').text('".strtoupper(utf8_encode($plan['edo']))."');
		$('#txtFechaPlanInst').val('".$fecha_plan."');
		$('#lstHoraPlanInst').val('".$hora."');
		$('#lstMinutosPlanInst').val('".$minutos."');
		$('#lstCodigoPlanInst').val('".$codigo_visita."');
		$('#objetivoPlanInst').val('".$objetivoPlan."');
		$('#sltReprePlanInst').empty();
		";
	
	if($tipoUsuario == 4){
		echo "$('#sltReprePlanInst').append('<option value=\"".$repre."\" selected=\"selected\">".$repreNombre."</option>');";
	}else{
		if($idPlan == ''){
			$regSuper = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from users where user_snr = '".$repre."'"));
			//echo "select * from users where user_snr = '".$repre."'";
			echo "$('#sltReprePlanInst').append('<option value=\"".$idUser."\" >".$queryRepre['nombre']."</option>');
				$('#sltReprePlanInst').append('<option value=\"".$regSuper['USER_SNR']."\">".$regSuper['LNAME']." ".$regSuper['FNAME']."</option>');
			";
		}else{
			echo "$('#sltReprePlanInst').append('<option value=\"".$repre."\" >".$repreNombre."</option>');";
		}
		/*if($idUsuario == '00000000-0000-0000-0000-000000000000'){
			echo "$('#sltReprePlanInst').val('".$regSuper['USER_SNR']."');";
		}else{
			echo "$('#sltReprePlanInst').val('".$plan['user_snr']."');";
		}*/
	}
	
	if($idPlan == ''){
		echo "$('#btnReportarInst').hide();";
	}else{
		echo "$('#btnReportarInst').show();";
	}
	if($regreso == 'visita'){
		echo "$('#hdnRegresoInst').val('visita');";
	}else{
		echo "$('#hdnRegresoInst').val('');";
	}
	echo "</script>";
?>