<?php
	include "../conexion.php";


	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array(" ", " ", " ", " ");
	$reemplazar1=array("", "", "", "");
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		//$idPlan = $_POST['idPlan'];
		
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
			$tipoUsuario = 4;
		}
		
		if(isset($_POST['idUser']) && $_POST['idUser'] != ''){
			$idUser = $_POST['idUser'];
		}else{
			$idUser = '00000000-0000-0000-0000-000000000000';
		}
		
		if(isset($_POST['idPlan']) && $_POST['idPlan'] != ''){
			$idPlan = $_POST['idPlan'];
			$query = "select frec.name as frecuencia, p.lname + ' ' + p.mothers_lname + ' ' + p.FNAME as nombre,
				esp.name as especialidad, i.name as inst, i.STREET1 as calle,city.zip as cp, 
				city.name as col, d.NAME as del, state.NAME as estado,
				u.USER_NR as ruta, u.LNAME + ' ' + u.mothers_lname + ' ' + u.FNAME as repre, 
				vpp.plan_date as fecha_plan, time as hora_plan, cd.name as Objetivo, 
				bri.name as brick, vpp.info, vpp.plan_code_snr as codigo_visita,
				vpp.PERS_SNR, vpp.vispers_snr, vpp.user_snr
				from vispersplan vpp
				inner join person p on vpp.PERS_SNR = p.PERS_SNR
				inner join CODELIST frec on p.frecvis_snr = frec.CLIST_SNR
				inner JOIN CODELIST ESP ON ESP.CLIST_SNR=P.SPEC_SNR
				inner join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR
				inner join inst i on i.INST_SNR = psw.inst_SNR
				inner join city on city.CITY_SNR = i.CITY_SNR
				inner join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
				inner join STATE on state.STATE_SNR = city.STATE_SNR
				inner join users u on u.user_snr = vpp.USER_SNR
				inner join CODELIST cd on cd.clist_snr=vpp.plan_code_snr
				inner join BRICK bri on bri.BRICK_SNR = city.BRICK_SNR
				where vispersplan_snr = '".$idPlan."'
				/*and psw.REC_STAT = 0 */";
		}else if(isset($_POST['idPersona']) && $_POST['idPersona'] != ''){
			$idPlan = '';
			$idPersona = $_POST['idPersona'];
			$query = "select frec.name as frecuencia, p.lname + ' ' + p.mothers_lname + ' ' + p.FNAME as nombre,
				esp.name as especialidad, i.name as inst, i.STREET1 as calle,city.zip as cp, city.name as col, d.NAME as del, state.NAME as estado,
				u.USER_NR as ruta, u.LNAME + ' ' + u.mothers_lname + ' ' + u.FNAME as repre, 
				bri.name as brick, psw.user_snr,
				(select top 1 info_nextvisit from visitpers where visitpers.PERS_SNR = p.PERS_SNR order by VISIT_DATE desc) as infoUltVis 
				from person p
				inner join CODELIST frec on p.FRECVIS_SNR = frec.CLIST_SNR
				inner JOIN CODELIST ESP ON ESP.CLIST_SNR=P.SPEC_SNR
				inner join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR
				inner join inst i on i.INST_SNR = psw.INST_SNR
				inner join city on city.CITY_SNR = i.CITY_SNR
				inner join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
				inner join STATE on state.STATE_SNR = city.STATE_SNR
				inner join users u on u.user_snr = psw.USER_SNR
				inner join BRICK bri on bri.BRICK_SNR = city.BRICK_SNR
				where p.pers_snr = '$idPersona'
				and psw.REC_STAT = 0";
		}
		
		//echo "plan: ".$query."<br>";
		
		$plan = sqlsrv_fetch_array(sqlsrv_query($conn, $query));
		
		$medico = $plan['nombre'];
		
		if($idPlan == ''){
			if( isset($_POST['fechaPlan']) && $_POST['fechaPlan'] != ''){
				$fecha_plan = $_POST['fechaPlan'];
			}else{
				$fecha_plan = date("Y-m-d");
			}
			$hora = "00";
			$minutos = "00";
			$objetivoPlan = str_replace("\n","", utf8_decode($plan['infoUltVis']));
			$idVisita = '';
			if(isset($_POST['ruta']) && $_POST['ruta'] != ''){
				$idUsuario = $_POST['ruta'];
			}else{
				$idUsuario = $plan['user_snr'];
			}
			$codigo_visita = '2B3A7099-AC7D-47A3-A274-F0B029791801';
		}else{
			foreach ($plan['fecha_plan'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha_plan = substr($val, 0, 10);
				}
			}
			$arrHora = explode(":", str_replace(".",":",$plan['hora_plan']));
			$hora = $arrHora[0];
			$minutos = $arrHora[1];
			//$objetivoPlan = $plan['info'];
			$objetivoPlan=str_ireplace($buscar,$reemplazar,$plan['info']);
			$idPersona = $plan['PERS_SNR'];
			$idVisita = $plan['vispers_snr'];
			$idUsuario = $plan['user_snr'];
			$codigo_visita = $plan['codigo_visita'];
		}
		
		echo "<script>
			$('#hdnIdPersona').val('".$idPersona."');
			$('#hdnIdPlan').val('".$idPlan."');
			$('#hdnPantallaPlan').val('".$pantalla."');
			$('#hdnIdVisitaPlan').val('".$idVisita."');
			$('#btnGuardarPlan').prop('disabled', false);
			$('#lblFrecuenciaPlan').text('".$plan['frecuencia']."');
			$('#lblMedicoPlan').text('".$medico."');
			$('#lblEspecialidadPlan').text('".$plan['especialidad']."');
			$('#lblInstPlan').text('".$plan['inst']."');
			$('#lblCallePlan').text('".strtoupper($plan['calle'])."');
			$('#lblCPPlan').text('".$plan['cp']."');
			$('#lblColoniaPlan').text('".strtoupper($plan['col'])."');
			$('#lblDelegacionPlan').text('".strtoupper($plan['del'])."');
			$('#lblEstadoPlan').text('".strtoupper($plan['estado'])."');
			$('#lblBrickPlan').text('".$plan['brick']."');
			$('#txtFechaPlan').val('".$fecha_plan."');
			$('#lstHoraPlan').val('".$hora."');
			$('#lstMinutosPlan').val('".$minutos."');
			$('#lstCodigoPlan').val('".$codigo_visita."');
			$('#objetivoPlan').val('".utf8_decode($objetivoPlan)."'); 
			$('#sltReprePlan').empty();";
			
			$nameDividido = explode('-',$plan['repre']);
			$nameSinNR = count($nameDividido) >1 ? $nameDividido[1] : $nameDividido[0];
			$plan['repre'] = $plan['ruta'].' - '.$nameSinNR;
		if($tipoUsuario == 4){
			echo "$('#sltReprePlan').append('<option value=\"".$plan['user_snr']."\" selected=\"selected\">".$plan['repre']."</option>');";
		}else{
			$regSuper = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from users where user_snr = '".$idUser."'"));
			$nameDividido = explode('-',$regSuper['LNAME']);
			$nameSinNR = count($nameDividido) >1 ? $nameDividido[1] : $nameDividido[0];
			$regSuper['LNAME'] = $regSuper['USER_NR'].' - '.$nameSinNR;

			if($tipoUsuario == 2){
				echo "$('#sltReprePlan').append('<option value=\"".$plan['user_snr']."\" >".$plan['repre']."</option>');";

				if($idUsuario == '00000000-0000-0000-0000-000000000000'){
					echo "$('#sltReprePlan').val('".$plan['user_snr']."');";
				}else{
					echo "$('#sltReprePlan').val('".$plan['user_snr']."');";
				}
			}else{
				echo "$('#sltReprePlan').append('<option value=\"".$plan['user_snr']."\" >".$plan['repre']."</option>');
				$('#sltReprePlan').append('<option value=\"".$regSuper['USER_SNR']."\">".$regSuper['LNAME']." ".$regSuper['FNAME']."</option>');
				";
				$varUser = $plan['user_snr'];

				if($idUsuario == '00000000-0000-0000-0000-000000000000'){
					echo "$('#sltReprePlan').val('".$regSuper['USER_SNR']."');";
				}else{
					echo "$('#sltReprePlan').val('".$plan['user_snr']."');";
				}
			}
			
		}
		if($idPlan == ''){
			echo "$('#btnReportarPlan').hide();
				$('#hdnIdPlan').val('');
				$('#hdnIdPlanReportar').val('');";
		}else{
			echo "$('#btnReportarPlan').show();";
		}
		if($regreso == 'visita'){
			echo "$('#hdnRegreso').val('visita');";
		}else{
			echo "$('#hdnRegreso').val('');";
		}
		echo "</script>";
		//echo "select * from users where user_snr = '".$idUser."'";
	}
	//echo "tipoU: ".$idUser."<br>";
	
?>