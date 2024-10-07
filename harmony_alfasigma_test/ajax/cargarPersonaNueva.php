<?php
	/*if($idInst != '' && $idPersona == ''){
		echo "$('#divResp').load('ajax/cargarInstSeleccionada.php',{idInst:'".$idInst."'});";
	}*/
include "../conexion.php";
$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
$reemplazar=array(" ", " ", " ", " ");


$arrMeses = array('','Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
if(! $conn){
	echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
}else{

	if(isset($_POST['idPersona']) && $_POST['idPersona'] != ''){
		$idPersona = $_POST['idPersona'];
	}else{
		$idPersona = '';
	}
	//echo "<script>alert('".$idPersona."');</script>";
	$tipoUsuario = sqlsrv_fetch_array(sqlsrv_query($conn, "select user_type from users where user_snr = '".$_POST['idUsusario']."'"))['user_type'];
	
	if($idPersona != ''){
		$queryPersona = "select p.FNAME, p.LNAME, p.mothers_lname,
			p.SEX_SNR, p.SPEC_SNR, p.BIRTHDATE,p.fee_type_snr,p.subspec_snr,
			p.category_snr, p.prof_id, p.frecvis_snr, p.INFO,
			p.info_longtime, p.info_shorttime,
			i.inst_snr as idInst, i.name as nombre,
			i.STREET1 as calle, i.num_ext as exterior,
			city.zip as cp, city.name as colonia,
			d.NAME as del, state.NAME as estado,
			bri.name as brick, 
			p.email1 ,p.email2 , p.tel1, p.tel2, p.mobile,
			plw.tel as telEmpresa, plw.email as mailEmpresa,
			plw.TOWER, plw.floor, plw.office, plw.department, p.patperweek_snr, p.PERSTYPE_SNR,
			p.diffvis_snr,
			prod1what_snr, prod1w, prod1h, prod1a, prod1t, 
			prod2what_snr, prod2w, prod2h, prod2a, prod2t, 
			prod3what_snr, prod3w, prod3h, prod3a, prod3t, 
			prod4what_snr, prod4w, prod4h, prod4a, prod4t, 
			prod5what_snr, prod5w, prod5h, prod5a, prod5t, 
			field_01_snr, field_02_snr, field_03_snr,
			field_04_snr, field_05_snr, field_06_snr, 
			field_07_snr, field_08_snr, field_09_snr, 
			field_10_snr, field_11_snr, field_12_snr,
			field_13_snr, field_14_snr, field_15_snr, 
			field_16_snr, field_17_snr, field_18_snr,
			p.status_snr, p.rec_stat, 
			ps.user_snr 
			from person p
			inner join PERS_SREP_WORK ps on ps.PERS_SNR = p.pers_snr
			inner join inst i on ps.inst_snr = i.INST_SNR
			inner join city on city.CITY_SNR = i.CITY_SNR
			inner join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
			inner join STATE on state.STATE_SNR = city.STATE_SNR
			inner join BRICK bri on bri.brick_snr = city.brick_snr
			left outer join PERSLOCWORK plw on plw.inst_snr = i.INST_SNR and plw.PERS_SNR = p.PERS_SNR and plw.REC_STAT = 0
			left outer join PERSON_UD pud on pud.pers_snr = p.PERS_SNR
			where p.pers_snr = '".$idPersona."'
			and ps.REC_STAT = 0
			and i.REC_STAT = 0";
			
		//echo $queryPersona;
	
		//$rsPasatiempos = sqlsrv_query($conn, "select * from PERSON_BANK where pers_snr = '$idPersona' and rec_stat = 0");
		$rsPasatiempos = sqlsrv_query($conn, "select * from PERSON_BANK where pers_snr = '$idPersona' and rec_stat = 0");
		//echo "select * from PERSON_BANK where pers_snr = '$idPersona' and rec_stat = 0";
		$arrPasatiempos = array();
		
		
		while($pasatiempo = sqlsrv_fetch_array($rsPasatiempos)){
			$arrPasatiempos[] = $pasatiempo['BANK_SNR'];
		}
		//print_r($arrPasatiempos);
		/*$queryHorario = "select PWTIM_SNR AS HORARIO_ID, PERS_SNR AS PERS_ID, PWADR_SNR AS PERSDIR_ID,
			PON_AM AS LUN_AM, PON_PM AS LUN_PM, FREE1 AS PREV_LUN, BEST1 AS HOR_LUN, COMENT1 AS COM_LUN,
			UT_AM AS MAR_AM, UT_PM AS MAR_PM, FREE2 AS PREV_MAR, BEST2 AS HOR_MAR, COMENT2 AS COM_MAR,
			SR_AM AS MIE_AM, SR_PM AS MIE_PM, FREE3 AS PREV_MIE, BEST3 AS HOR_MIE, COMENT3 AS COM_MIE,
			CET_AM AS JUE_AM, CET_PM AS JUE_PM, FREE4 AS PREV_JUE, BEST4 AS HOR_JUE, COMENT4 AS COM_JUE,
			PET_AM AS VIE_AM, PET_PM AS VIE_PM, FREE5 AS PREV_VIE, BEST5 AS HOR_VIE, COMENT5 AS COM_VIE,
			SUB_AM AS SAB_AM, SUB_PM AS SAB_PM, FREE6 AS PREV_SAB, BEST6 AS HOR_SAB, COMENT6 AS COM_SAB,
			NED_AM AS DOM_AM, NED_PM AS DOM_PM, FREE7 AS PREV_DOM, BEST7 AS HOR_DOM, COMENT7 AS COM_DOM 
			from PERSWORKTIME where pers_snr = '$idPersona'";
		$rsHorario = sqlsrv_fetch_array(sqlsrv_query($conn, $queryHorario));*/
		
		$rsPersona = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPersona));
		$tipoPersona = $rsPersona['PERSTYPE_SNR'];
		$nombre = $rsPersona['FNAME'];
		$paterno = $rsPersona['LNAME'];
		$materno = $rsPersona['mothers_lname'];
		$sexo = $rsPersona['SEX_SNR'];
		$especialidad = $rsPersona['SPEC_SNR'];
		$subespecialidad = $rsPersona['subspec_snr'];
		$fecha = '';
		$dia = '';
		$mes = '';
		$anio = '';
		if(is_object($rsPersona['BIRTHDATE'])){
			foreach ($rsPersona['BIRTHDATE'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha = substr($val, 0, 10);
					//$anio = ((int)substr($fecha, 0, 4) < 1950) ? '1900' : substr($fecha, 0, 4);
					$anio = ((int)substr($fecha, 0, 4) < 1900) ? '1900' : substr($fecha, 0, 4);
					$mes = ((int)substr($fecha, 5, 2) < 1) ? '1' : substr($fecha, 5, 2);
					$dia = ((int)substr($fecha, 8, 2) < 1) ? '1' : substr($fecha, 8, 2);
				}
			}
		}

		$categoria = $rsPersona['category_snr'];
		$cedula = $rsPersona['prof_id'];
		//$frecuencia = $rsPersona['frecvis_snr'];
		$idInst = $rsPersona['idInst'];
		/*$telefono = $rsPersona['tel'];
		$telefono1 = $rsPersona['cel'];
		$email1 = $rsPersona['mail'];*/
		$corto = str_ireplace($buscar,$reemplazar,$rsPersona['info_shorttime']);
		$largo = str_ireplace($buscar,$reemplazar,$rsPersona['info_longtime']);
		$generales=str_ireplace($buscar,$reemplazar,$rsPersona['INFO']);

		$nombreInst = $rsPersona['nombre'];
		$calleInst = $rsPersona['calle'];
		$extInst = $rsPersona['exterior'];
		//$intInst = $rsPersona['interior'];
		$cpInst = $rsPersona['cp'];
		$colInst = $rsPersona['colonia'];
		$delInst = $rsPersona['del'];
		$estInst = $rsPersona['estado'];
		$briInst = $rsPersona['brick'];

		/*$abierto1 = $rsPersona['abierto1'];
		$abierto2 = $rsPersona['abierto2'];
		$abierto3 = $rsPersona['abierto3'];*/

		$email1Personal = $rsPersona['email1'];
		$telPersonal = $rsPersona['tel1'];
		$email1Personal2 = $rsPersona['email2'];
		$telPersonal2 = $rsPersona['tel2'];
		$celular = $rsPersona['mobile'];

		$TOWER = $rsPersona['TOWER'];
		$floor = $rsPersona['floor'];
		$office = $rsPersona['office'];
		$department = $rsPersona['department'];
		$telefono = $rsPersona['telEmpresa'];//plwTel
		$email = $rsPersona['mailEmpresa'];//plwMail

		$dificultadVisita = $rsPersona['diffvis_snr'];
		//$iguala = $rsPersona['ADRESA2_BANKE'];
		$pacientesSemana = $rsPersona['patperweek_snr'];
		$honorarios = $rsPersona['fee_type_snr'];
		//$botiquin = $rsPersona['RX_TYPE_SNR'];
		//$liderOpinion = $rsPersona['OL_SNR'];
		
		//$puesto = $rsPersona['FUNCTION_SNR'];
		//$tipoTrabajo = $rsPersona['EMPLOYEESTAT'];
		
		$field_01_snr = $rsPersona['field_01_snr']; 
		$field_02_snr = $rsPersona['field_02_snr']; 
		//$liderOpinion = $rsPersona['OL_SNR'];
		$field_03_snr = $rsPersona['field_03_snr']; 
		$field_04_snr = $rsPersona['field_04_snr']; 
		$field_05_snr = $rsPersona['field_05_snr']; 
		$field_06_snr = $rsPersona['field_06_snr']; 
		$field_07_snr = $rsPersona['field_07_snr'];
		$field_08_snr = $rsPersona['field_08_snr']; 
		$field_09_snr = $rsPersona['field_09_snr']; 
		$field_10_snr = $rsPersona['field_10_snr']; 
		$field_11_snr = $rsPersona['field_11_snr'];
		$field_12_snr = $rsPersona['field_12_snr'];
		$field_13_snr = $rsPersona['field_13_snr']; 
		$field_14_snr = $rsPersona['field_14_snr']; 
		$field_15_snr = $rsPersona['field_15_snr']; 
		$field_16_snr = $rsPersona['field_16_snr'];
		$field_17_snr = $rsPersona['field_17_snr'];
		$field_18_snr = $rsPersona['field_18_snr'];
		
		$prod1what_snr = $rsPersona['prod1what_snr']; 
		$prod1w = $rsPersona['prod1w']; 
		$prod1h = $rsPersona['prod1h']; 
		$prod1a = $rsPersona['prod1a']; 
		$prod1t = $rsPersona['prod1t']; 
		$prod2what_snr = $rsPersona['prod2what_snr']; 
		$prod2w = $rsPersona['prod2w']; 
		$prod2h = $rsPersona['prod2h']; 
		$prod2a = $rsPersona['prod2a']; 
		$prod2t = $rsPersona['prod2t']; 
		$prod3what_snr = $rsPersona['prod3what_snr']; 
		$prod3w = $rsPersona['prod3w']; 
		$prod3h = $rsPersona['prod3h']; 
		$prod3a = $rsPersona['prod3a']; 
		$prod3t = $rsPersona['prod3t']; 
		$prod4what_snr = $rsPersona['prod4what_snr']; 
		$prod4w = $rsPersona['prod4w']; 
		$prod4h = $rsPersona['prod4h']; 
		$prod4a = $rsPersona['prod4a']; 
		$prod4t = $rsPersona['prod4t']; 
		$prod5what_snr = $rsPersona['prod5what_snr']; 
		$prod5w = $rsPersona['prod5w']; 
		$prod5h = $rsPersona['prod5h']; 
		$prod5a = $rsPersona['prod5a']; 
		$prod5t = $rsPersona['prod5t'];  
		
		
		$estatus = $rsPersona['status_snr'];
		
		$rec_stat = $rsPersona['rec_stat'];
		
		$ruta = $rsPersona['user_snr'];
		
		/* nuevo calculo de frecuencia */	
		$qfrecuencia = "select frec.CLIST_SNR as frec
			from cycle_pers_categ_spec cpcs 
			inner join cycles c on c.cycle_snr = cpcs.cycle_snr 
			inner join CODELIST frec on frec.CLIST_SNR = cpcs.frecvis_snr
			where cpcs.rec_stat = 0 
			and '".date("Y-m-d")."' between c.start_date and c.finish_date 
			and spec_snr = '".$especialidad."' 
			and category_snr = '".$categoria."' ";
			
		//echo $qfrecuencia;
		
		$frecuencia = sqlsrv_fetch_array(sqlsrv_query($conn, $qfrecuencia))['frec'];
		
	}else{
		if(isset($_POST['idInst']) && $_POST['idInst'] != ''){
			$idInst = $_POST['idInst'];
		}else{
			$idInst = '';
		}

		$idPersona = '';
		$tipoPersona = '00000000-0000-0000-0000-000000000000';
		$nombre = '';
		$paterno = '';
		$materno = '';
		$sexo = '00000000-0000-0000-0000-000000000000';
		$especialidad = '00000000-0000-0000-0000-000000000000';
		$subespecialidad = '00000000-0000-0000-0000-000000000000';
		$fecha = '';
		$anio = '';
		$mes = '';
		$dia = '';
		$categoria = '00000000-0000-0000-0000-000000000000';
		$cedula = '';
		$frecuencia = '00000000-0000-0000-0000-000000000000';
		//$idInst = '';
		$email1Personal = '';
		$telPersonal = '';
		$email1Personal2 = '';
		$telPersonal2 = '';
		$celular = '';
		$corto = '';
		$largo = '';
		$generales = '';
		$nombreInst = '';
		$calleInst = '';
		$extInst = '';
		//$intInst = '';
		$cpInst = '';
		$colInst = '';
		$delInst = '';
		$estInst = '';
		$briInst = '';
		$arrPasatiempos = array();
		//$rsHorario = array();
		/*$email1Personal = '';
		$telPersonal = '';*/
		$TOWER = '';
		$floor = '';
		$office = '';
		$department = '';
		$telefono = '';//plwTel
		$email = '';//plwMail
		$dificultadVisita = '00000000-0000-0000-0000-000000000000';
		//$iguala = '';
		$pacientesSemana = '00000000-0000-0000-0000-000000000000';
		$honorarios = '00000000-0000-0000-0000-000000000000';
		//$botiquin = '00000000-0000-0000-0000-000000000000';
		
		//$puesto = '00000000-0000-0000-0000-000000000000';
		//$tipoTrabajo = '00000000-0000-0000-0000-000000000000';
		/*$email1Personal2 = '';
		$telPersonal2 = '';
		$celular = '';*/
		
		$prod1what_snr = '00000000-0000-0000-0000-000000000000'; 
		$prod1w = ''; 
		$prod1h = ''; 
		$prod1a = ''; 
		$prod1t = ''; 
		$prod2what_snr = '00000000-0000-0000-0000-000000000000'; 
		$prod2w = ''; 
		$prod2h = ''; 
		$prod2a = ''; 
		$prod2t = ''; 
		$prod3what_snr = '00000000-0000-0000-0000-000000000000'; 
		$prod3w = ''; 
		$prod3h = ''; 
		$prod3a = ''; 
		$prod3t = ''; 
		$prod4what_snr = '00000000-0000-0000-0000-000000000000'; 
		$prod4w = ''; 
		$prod4h = ''; 
		$prod4a = ''; 
		$prod4t = ''; 
		$prod5what_snr = '00000000-0000-0000-0000-000000000000'; 
		$prod5w = ''; 
		$prod5h = ''; 
		$prod5a = ''; 
		$prod5t = '';  
		$field_01_snr = '00000000-0000-0000-0000-000000000000'; 
		$field_02_snr = '00000000-0000-0000-0000-000000000000'; 
		$field_03_snr = '00000000-0000-0000-0000-000000000000'; 
		$field_04_snr = '00000000-0000-0000-0000-000000000000'; 
		$field_05_snr = '00000000-0000-0000-0000-000000000000'; 
		$field_06_snr = '00000000-0000-0000-0000-000000000000'; 
		$field_07_snr = '00000000-0000-0000-0000-000000000000';
		$field_08_snr = '00000000-0000-0000-0000-000000000000'; 
		$field_09_snr = '00000000-0000-0000-0000-000000000000'; 
		$field_10_snr = '00000000-0000-0000-0000-000000000000'; 
		$field_11_snr = '00000000-0000-0000-0000-000000000000';
		$field_12_snr = '00000000-0000-0000-0000-000000000000';
		$field_13_snr = '00000000-0000-0000-0000-000000000000';	
		$field_14_snr = '00000000-0000-0000-0000-000000000000'; 
		$field_15_snr = '00000000-0000-0000-0000-000000000000'; 
		$field_16_snr = '00000000-0000-0000-0000-000000000000';
		$field_17_snr = '00000000-0000-0000-0000-000000000000';
		$field_18_snr = '00000000-0000-0000-0000-000000000000';
		
		$estatus = 'B426FB78-8498-4185-882D-E0DC381460E8';
		
		$rec_stat = 0;
		
		$ruta = '00000000-0000-0000-0000-000000000000';
	}
	//$mes = substr($fecha, 5,2)*1;
	//$dia = substr($fecha, 8,2)*1;
	//echo "tipoU: ".$tipoUsuario;
	echo "<script>
		$('#hdnIdPersona').val('".$idPersona."');
		$('#hdnPersonaNueva').val('no');
		$('#sltTipoPersonaNuevo').val('".$tipoPersona."');
		$('#txtNombrePersonaNuevo').val('".$nombre."');
		$('#txtPaternoPersonaNuevo').val('".$paterno."');
		$('#txtMaternoPersonaNuevo').val('".$materno."');
		$('#sltSexoPersonaNuevo').val('".$sexo."');
		$('#sltEspecialidadPersonaNuevo').val('".$especialidad."');
		$('#sltSubEspecialidadPersonaNuevo').val('".$subespecialidad."');
		$('#sltPacientesXSemanaPersonaNuevo').val('".$pacientesSemana."');
		$('#sltHonorariosPersonaNuevo').val('".$honorarios."');
		$('#txtFechaNacimientoPersonaNuevo').val('".$fecha."');
		$('#sltDiaFechaNacimientoPersonaNuevo').val('".$dia."');
		$('#sltMesFechaNacimientoPersonaNuevo').val('".$mes."');
		$('#sltAnioFechaNacimientoPersonaNuevo').val('".$anio."');
		$('#sltCategoriaPersonaNuevo').val('".$categoria."');
		$('#txtCedulaPersonaNuevo').val('".$cedula."');
		$('#sltFrecuenciaPersonaNuevo').val('".$frecuencia."');
		$('#sltDificultadVisita').val('".$dificultadVisita."');
		$('#txtCorreoPersonalPersonaNuevo').val('".$email1Personal."');
		$('#txtCorreoPersonalPersonaNuevo2').val('".$email1Personal2."');
		$('#txtTelPersonalPersonaNuevo').val('".$telPersonal."');
		$('#txtTelPersonalPersonaNuevo2').val('".$telPersonal2."');
		$('#txtCelularPersonaNuevo').val('".$celular."');
		$('#txtNombreInstPersonaNuevo ').val('".$nombreInst."');
		$('#hdnIdInstPersonaNuevo').val('".$idInst."');
		$('#txtCalleInstPersonaNuevo').val('".$calleInst."');
		$('#txtnum_extInstPersonaNuevo').val('".$extInst."');
		$('#txtDepartamentoPersonaNuevo').val('".$department."');
		$('#txtTorrePersonaNuevo').val('".$TOWER."');
		$('#txtPisoPersonaNuevo').val('".$floor."');
		$('#txtConsultorioPersonaNuevo').val('".$office."');
		$('#txtTelefonoInstPersonaNuevo').val('".$telefono."');
		$('#txtEmailInstPersonaNuevo').val('".$email."');
		$('#txtCPInstPersonaNuevo').val('".$cpInst."');
		$('#txtColoniaInstPersonaNuevo').val('".$colInst."');
		$('#txtCiudadInstPersonaNuevo').val('".$delInst."');
		$('#txtEstadoInstPersonaNuevo').val('".$estInst."');
		$('#txtBrickInstPersonaNuevo').val('".$briInst."');
		$('#sltProducto1PersonaNuevo').val('".$prod1what_snr."');
		$('#txtWProducto1').val('".$prod1w."');
		$('#txtHProducto1').val('".$prod1h."');
		$('#txtAProducto1').val('".$prod1a."');
		$('#txtTProducto1').val('".$prod1t."');
		$('#sltProducto2PersonaNuevo').val('".$prod2what_snr."');
		$('#txtWProducto2').val('".$prod2w."');
		$('#txtHProducto2').val('".$prod2h."');
		$('#txtAProducto2').val('".$prod2a."');
		$('#txtTProducto2').val('".$prod2t."');
		$('#sltProducto3PersonaNuevo').val('".$prod3what_snr."');
		$('#txtWProducto3').val('".$prod3w."');
		$('#txtHProducto3').val('".$prod3h."');
		$('#txtAProducto3').val('".$prod3a."');
		$('#txtTProducto3').val('".$prod3t."');
		$('#sltProducto4PersonaNuevo').val('".$prod4what_snr."');
		$('#txtWProducto4').val('".$prod4w."');
		$('#txtHProducto4').val('".$prod4h."');
		$('#txtAProducto4').val('".$prod4a."');
		$('#txtTProducto4').val('".$prod4t."');
		$('#sltProducto5PersonaNuevo').val('".$prod5what_snr."');
		$('#txtWProducto5').val('".$prod5w."');
		$('#txtHProducto5').val('".$prod5h."');
		$('#txtAProducto5').val('".$prod5a."');
		$('#txtTProducto5').val('".$prod5t."');
		$('#sltDivMedicoNuevo').val('".$field_01_snr."');
		$('#sltLiderOpinionPersonaNuevo').val('".$field_02_snr."');
		$('#sltParaestatales').val('".$field_03_snr."');
		$('#sltSegmentacionFlonorPersonaNuevo').val('".$field_04_snr."');
		$('#sltSegmentacionVesselPersonaNuevo').val('".$field_05_snr."');
		$('#sltSegmentacionZirfosPersonaNuevo').val('".$field_06_snr."');
		$('#sltSegmentacionAtekaPersonaNuevo').val('".$field_07_snr."');
		$('#sltPregunta1PersonaNuevo').val('".$field_08_snr."');
		$('#sltPregunta2PersonaNuevo').val('".$field_09_snr."');
		$('#sltPregunta3PersonaNuevo').val('".$field_10_snr."');
		$('#sltPregunta4PersonaNuevo').val('".$field_11_snr."');
		$('#sltField_12').val('".$field_12_snr."');
		$('#sltField_13').val('".$field_13_snr."');
		$('#sltField_14').val('".$field_14_snr."');
		$('#sltField_15').val('".$field_15_snr."');
		$('#sltField_16').val('".$field_16_snr."');
		$('#sltField_17').val('".$field_17_snr."');
		$('#sltField_18').val('".$field_18_snr."');
		$('#sltEstatusPersonaNuevo').val('".$estatus."');
		$('#sltRutaPersonaNueva').val('".$ruta."');
		";
	//$rsPasatiempos = sqlsrv_query($conn, "select CLIST_SNR as id, name as nombre from CODELIST where CLIB_SNR = '20049108-3068-4224-AB60-92CF4B697E6B' order by name");
	//$rsPasatiempos = llenaCombo($conn, 19, 13);
	
	
	
				/********bancos / aseguradoras**********/
			$arrPasatiempos = array();
			$rsArrPasatiempos = sqlsrv_query($conn, "select BANK_SNR from PERSON where PERS_SNR = '".$idPersona."' and rec_stat = 0");
			//echo "select BANK_SNR from PERSON_BANK where PERS_SNR = '".$id."' ";
			
			while($pasatiempoArr = sqlsrv_fetch_array($rsArrPasatiempos)){
				$arrPasatiempos[] = $pasatiempoArr['BANK_SNR'];
			}
			$rsPasatiempos = llenaCombo($conn, 19, 15);
			$contador = 1;
			
			$separados = explode(';', $arrPasatiempos[0]);
			
			//print_r($arrPasatiempos);
			while($pasatiempo = sqlsrv_fetch_array($rsPasatiempos)){
				//echo $pasatiempo['id']."<br>";
				//if(in_array($pasatiempo['id'], $arrPasatiempos)){
				if(in_array($pasatiempo['id'], $separados)){
					//echo "alert('entre');";
					echo "$('#chkPasatiempoPersonaNuevo".$contador."').prop('checked', true);";
				}else{
					echo "$('#chkPasatiempoPersonaNuevo".$contador."').prop('checked', false);";
				}
				$contador++;
			}
		
			//echo "$('#hdnPasatiempo').val('".$contador."');";
			/****fin de bancos / seguradoras*******/
		
			/******************prescripciones*************/
	
	
	
//	$rsPasatiempos = llenaCombo($conn, 19, 15);
	//$contador = 1;
//	while($pasatiempo = sqlsrv_fetch_array($rsPasatiempos)){
	//	if(in_array($pasatiempo['id'], $arrPasatiempos)){
//			echo "$('#chkPasatiempoPersonaNuevo".$contador."').prop('checked', true);";
//		}else{
//			echo "$('#chkPasatiempoPersonaNuevo".$contador."').prop('checked', false);";
//		}
//		$contador++;
//	}
	//echo "$('#hdnPasatiempo').val('".$contador."');";
	/*if(isset($rsHorario) && $rsHorario['LUN_AM'] == 1){
		echo "$('#chkLunesAm').prop('checked', true);";
	}else{
		echo "$('#chkLunesAm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['LUN_PM'] == 1){
		echo "$('#chkLunesPm').prop('checked', true);";
	}else{
		echo "$('#chkLunesPm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['PREV_LUN'] == 1){
		echo "$('#chkLunesPrevia').prop('checked', true);";
	}else{
		echo "$('#chkLunesPrevia').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['HOR_LUN'] == 1){
		echo "$('#chkLunesFijo').prop('checked', true);";
	}else{
		echo "$('#chkLunesFijo').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['COM_LUN'] != ''){
		echo "$('#txtLunesComentarios').val('".$rsHorario['COM_LUN']."');";
	}else{
		echo "$('#txtLunesComentarios').val('');";
	}
	if(isset($rsHorario) && $rsHorario['MAR_AM'] == 1){
		echo "$('#chkMartesAm').prop('checked', true);";
	}else{
		echo "$('#chkMartesAm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['MAR_PM'] == 1){
		echo "$('#chkMartesPm').prop('checked', true);";
	}else{
		echo "$('#chkMartesPm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['PREV_MAR'] == 1){
		echo "$('#chkMartesPrevia').prop('checked', true);";
	}else{
		echo "$('#chkMartesPrevia').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['HOR_MAR'] == 1){
		echo "$('#chkMartesFijo').prop('checked', true);";
	}else{
		echo "$('#chkMartesFijo').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['COM_MAR'] != ''){
		echo "$('#txtMartesComentarios').val('".$rsHorario['COM_MAR']."');";
	}else{
		echo "$('#txtMartesComentarios').val('');";
	}
	if(isset($rsHorario) && $rsHorario['MIE_AM'] == 1){
		echo "$('#chkMiercolesAm').prop('checked', true);";
	}else{
		echo "$('#chkMiercolesAm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['MIE_PM'] == 1){
		echo "$('#chkMiercolesPm').prop('checked', true);";
	}else{
		echo "$('#chkMiercolesPm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['PREV_MIE'] == 1){
		echo "$('#chkMiercolesPrevia').prop('checked', true);";
	}else{
		echo "$('#chkMiercolesPrevia').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['HOR_MIE'] == 1){
		echo "$('#chkMiercolesFijo').prop('checked', true);";
	}else{
		echo "$('#chkMiercolesFijo').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['COM_MIE'] != ''){
		echo "$('#txtMiercolesComentarios').val('".$rsHorario['COM_MIE']."');";
	}else{
		echo "$('#txtMiercolesComentarios').val('');";
	}
	if(isset($rsHorario) && $rsHorario['JUE_AM'] == 1){
		echo "$('#chkJuevesAm').prop('checked', true);";
	}else{
		echo "$('#chkJuevesAm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['JUE_PM'] == 1){
		echo "$('#chkJuevesPm').prop('checked', true);";
	}else{
		echo "$('#chkJuevesPm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['PREV_JUE'] == 1){
		echo "$('#chkJuevesPrevia').prop('checked', true);";
	}else{
		echo "$('#chkJuevesPrevia').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['HOR_JUE'] == 1){
		echo "$('#chkJuevesFijo').prop('checked', true);";
	}else{
		echo "$('#chkJuevesFijo').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['COM_JUE'] != ''){
		echo "$('#txtJuevesComentarios').val('".$rsHorario['COM_JUE']."');";
	}else{
		echo "$('#txtJuevesComentarios').val('');";
	}
	if(isset($rsHorario) && $rsHorario['VIE_AM'] == 1){
		echo "$('#chkViernesAm').prop('checked', true);";
	}else{
		echo "$('#chkViernesAm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['VIE_PM'] == 1){
		echo "$('#chkViernesPm').prop('checked', true);";
	}else{
		echo "$('#chkViernesPm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['PREV_VIE'] == 1){
		echo "$('#chkViernesPrevia').prop('checked', true);";
	}else{
		echo "$('#chkViernesPrevia').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['HOR_VIE'] == 1){
		echo "$('#chkViernesFijo').prop('checked', true);";
	}else{
		echo "$('#chkViernesFijo').prop('checked', false);";
	}	
	if(isset($rsHorario) && $rsHorario['COM_VIE'] != ''){
		echo "$('#txtViernesComentarios').val('".$rsHorario['COM_VIE']."');";
	}else{
		echo "$('#txtViernesComentarios').val('');";
	}
	if(isset($rsHorario) && $rsHorario['SAB_AM'] == 1){
		echo "$('#chkSabadoAm').prop('checked', true);";
	}else{
		echo "$('#chkSabadoAm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['SAB_PM'] == 1){
		echo "$('#chkSabadoPm').prop('checked', true);";
	}else{
		echo "$('#chkSabadoPm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['PREV_SAB'] == 1){
		echo "$('#chkSabadoPrevia').prop('checked', true);";
	}else{
		echo "$('#chkSabadoPrevia').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['HOR_SAB'] == 1){
		echo "$('#chkSabadoFijo').prop('checked', true);";
	}else{
		echo "$('#chkSabadoFijo').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['COM_SAB'] != ''){
		echo "$('#txtSabadoComentarios').val('".$rsHorario['COM_SAB']."');";
	}else{
		echo "$('#txtSabadoComentarios').val('');";
	}
	if(isset($rsHorario) && $rsHorario['DOM_AM'] == 1){
		echo "$('#chkDomingoAm').prop('checked', true);";
	}else{
		echo "$('#chkDomingoAm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['DOM_PM'] == 1){
		echo "$('#chkDomingoPm').prop('checked', true);";
	}else{
		echo "$('#chkDomingoPm').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['PREV_DOM'] == 1){
		echo "$('#chkDomingoPrevia').prop('checked', true);";
	}else{
		echo "$('#chkDomingoPrevia').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['HOR_DOM'] == 1){
		echo "$('#chkDomingoFijo').prop('checked', true);";
	}else{
		echo "$('#chkDomingoFijo').prop('checked', false);";
	}
	if(isset($rsHorario) && $rsHorario['COM_DOM'] != ''){
		echo "$('#txtDomingoComentarios').val('".$rsHorario['COM_DOM']."');";
	}else{
		echo "$('#txtDomingoComentarios').val('');";
	}*/
	echo "
		$('#txtCorto').val('".$corto."');
		$('#txtLargo').val('".$largo."');
		$('#txtGenerales').val('".$generales."');
		if($('#divInstituciones').is(':visible')){
			instSeleccionada('".$idInst."');
			$('#btnSleccionaInst').prop('disabled', true);
		}else{
			$('#btnSleccionaInst').prop('disabled', false);
		}
		if(".$rec_stat." == 0){
			$('#btnReactivarPersonaNuevo').prop('disabled', true);
		}else{
			$('#btnReactivarPersonaNuevo').prop('disabled', false);
		}
		$('#btnGuardarPersonaNuevo').prop('disabled', false);
		
		if('".$tipoUsuario."' != 4 ){
			$('#divRuta').show();
		}else{
			$('#divRuta').hide();
		}

		if('".$idPersona."' != '' ){
			$('#sltRutaPersonaNueva').prop('disabled', true);
		}else{
			$('#sltRutaPersonaNueva').prop('disabled', false);
		}
		
		</script>";
}
?>