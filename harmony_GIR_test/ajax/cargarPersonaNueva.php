<?php

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
		//txtTelPersonalPersonaNuevo
		//txtTelPersonalPersonaNuevo2
		//txtCorreoPersonalPersonaNuevo

		//txtTelefonoInstPersonaNuevo
		//txtEmailInstPersonaNuevo
		if($idPersona != ''){
			echo "<script>$('#sltReprePersonaNuevo').attr('disabled', 'true');</script>";
			$queryPersona = "select u.USER_SNR, 
				p.PERSTYPE_SNR,
				p.LNAME,
				p.mothers_lname,
				p.FNAME, 
				p.SEX_SNR, 
				p.SPEC_SNR,
				p.subspec_snr,
				p.prof_id,
				p.category_snr,
				p.status_snr,
				p.patperweek_snr, 
				p.fee_type_snr,
				p.BIRTHDATE,
				p.tel1,
				p.tel2,
				p.mobile,
				p.email1, 
				p.email2,
				p.ASSISTANT_NAME,
				p.ASSISTANT_TEL,
				p.ASSISTANT_EMAIL,
				p.frecvis_snr, 
				p.diffvis_snr,
				p.speaker_snr, 
				i.inst_snr as idInst, 
				i.name as nombre,
				i.STREET1 as calle, 
				i.num_ext as exterior,
				city.zip as cp, 
				city.name as colonia,
				d.NAME as del, 
				state.NAME as estado,
				bri.name as brick,  
				plw.num_int as interior,
				KOL_SNR,
				BASIC_LIST_SNR,
				p.INFO,
				p.info_longtime, 
				p.info_shorttime, 
				plw.tel as telEmpresa, 
				plw.email as mailEmpresa, 
				plw.TOWER, 
				plw.floor, 
				plw.office, 
				plw.department, 
				p.rec_stat, 
				p.CATEGORY_AUDIT_SNR,
				p.AILMENT_SNR,
				p.MARITAL_STATUS_SNR,
				p.brightly_snr
				from person p
				inner join PERS_SREP_WORK ps on ps.PERS_SNR = p.pers_snr
				inner join USERS u on u.USER_SNR =ps.USER_SNR
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
			/*$rsPasatiempos = sqlsrv_query($conn, "select * from PERSON_BANK where pers_snr = '$idPersona' and rec_stat = 0");
			//echo "select * from PERSON_BANK where pers_snr = '$idPersona' and rec_stat = 0";
			$arrPasatiempos = array();
			
			
			while($pasatiempo = sqlsrv_fetch_array($rsPasatiempos)){
				$arrPasatiempos[] = $pasatiempo['BANK_SNR'];
			}*/
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
			$nombre = utf8_encode($rsPersona['FNAME']);
			$paterno = utf8_encode($rsPersona['LNAME']);
			$materno = utf8_encode($rsPersona['mothers_lname']);
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
			$frecuencia = $rsPersona['frecvis_snr'];
			$idInst = $rsPersona['idInst'];
			$dificultadVisita = $rsPersona['diffvis_snr'];
			$corto = utf8_encode(str_ireplace($buscar,$reemplazar,$rsPersona['info_shorttime']));
			$largo = utf8_encode(str_ireplace($buscar,$reemplazar,$rsPersona['info_longtime']));
			$generales= utf8_encode(str_ireplace($buscar,$reemplazar,$rsPersona['INFO']));

			$nombreInst = utf8_encode($rsPersona['nombre']);
			$calleInst = utf8_encode($rsPersona['calle']);
			$extInst = $rsPersona['exterior'];
			$intInst = $rsPersona['interior'];
			$cpInst = $rsPersona['cp'];
			$colInst = utf8_encode($rsPersona['colonia']);
			$delInst = utf8_encode($rsPersona['del']);
			$estInst = utf8_encode($rsPersona['estado']);
			$briInst = $rsPersona['brick'];

			$email1Personal = $rsPersona['email1'];
			$telPersonal = $rsPersona['tel1'];
			$email1Personal2 = $rsPersona['email2'];
			$telPersonal2 = $rsPersona['tel2'];
			$celular = $rsPersona['mobile'];

			$TOWER = utf8_encode($rsPersona['TOWER']);
			$floor = utf8_encode($rsPersona['floor']);
			$office = utf8_encode($rsPersona['office']);
			$department = utf8_encode($rsPersona['department']);
			$telefono = $rsPersona['telEmpresa'];//plwTel
			$email = $rsPersona['mailEmpresa'];
			$brightly_snr = $rsPersona['brightly_snr'];
			//plwMail

			/*$pacientesSemana = $rsPersona['patperweek_snr'];
			$honorarios = $rsPersona['fee_type_snr'];*/
			
			$pacientesSemana = $rsPersona['patperweek_snr'];
			$honorarios = $rsPersona['fee_type_snr'];
			
			/*$field_01_snr = $rsPersona['field_01_snr']; 
			$field_02_snr = $rsPersona['field_02_snr']; 
			$field_03_snr = $rsPersona['field_03_snr']; 
			$field_04_snr = $rsPersona['field_04_snr']; 
			$field_05_snr = $rsPersona['field_05_snr']; 
			$field_06_snr = $rsPersona['field_06_snr'];
			$field_07_snr = $rsPersona['field_07_snr'];		
			$field_01 = $rsPersona['field_01']; 
			$field_02 = $rsPersona['field_02'];
			$field_03 = $rsPersona['field_03']; */
			
			$estatus = $rsPersona['status_snr'];
			
			$rec_stat = $rsPersona['rec_stat'];
			
			$nombreAsistente = $rsPersona['ASSISTANT_NAME'];
			$telAsistente = $rsPersona['ASSISTANT_TEL'];
			$mailAsistente = $rsPersona['ASSISTANT_EMAIL'];
			
			/*$nombreHospital = $rsPersona['HOSPITAL_NAME'];
			$preferenciaContacto = $rsPersona['PREFERRED_CONTACT_SNR'];
			$aceptaApoyo = $rsPersona['ACCEPT_SUPPORT_SNR'];
			$porqueAceptaApoyo = $rsPersona['ACCEPT_SUPPORT_INFO'];
			$botiquin = $rsPersona['AID_KIT_SNR'];
			$compraDirecta = $rsPersona['DIRECT_PURCHASE_SNR'];*/
			$liderOpinion = $rsPersona['KOL_SNR'];
			/*$speaker = $rsPersona['SPEAKER_SNR'];
			$tipoConsulta = $rsPersona['CONSULTATION_TYPE_SNR'];*/

			$padecimientosMedicos = $rsPersona['AILMENT_SNR'];
			$estadoCivil = $rsPersona['MARITAL_STATUS_SNR'];

			$idRepre=$rsPersona['USER_SNR'];
			
			$speaker_snr=$rsPersona['speaker_snr'];
			$arrPasatiempos = explode(";", $rsPersona['BASIC_LIST_SNR']);
			
		}else{

			echo "<script>$('#sltReprePersonaNuevo').removeAttr('disabled');</script>";
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
			$frecuencia = 'B01CBA0E-20A3-45E0-849E-995180E9C9B8';
			$dificultadVisita = '00000000-0000-0000-0000-000000000000';
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
			$intInst = '';
			$cpInst = '';
			$colInst = '';
			$delInst = '';
			$estInst = '';
			$briInst = '';
			$arrPasatiempos = array();
			//$rsHorario = array();
			$TOWER = '';
			$floor = '';
			$office = '';
			$department = '';
			$telefono = '';//plwTel
			$email = '';//plwMail
			$pacientesSemana = '00000000-0000-0000-0000-000000000000';
			$honorarios = '00000000-0000-0000-0000-000000000000';
			$idRepre = '00000000-0000-0000-0000-000000000000';
			$padecimientosMedicos = '00000000-0000-0000-0000-000000000000';
			$estadoCivil = '00000000-0000-0000-0000-000000000000';
			$brightly_snr = '00000000-0000-0000-0000-000000000000';
			
			$tipoConsulta = array();
			
			/*$field_01_snr = '00000000-0000-0000-0000-000000000000'; 
			$field_02_snr = '00000000-0000-0000-0000-000000000000'; 
			$field_03_snr = '00000000-0000-0000-0000-000000000000'; 
			$field_04_snr = '00000000-0000-0000-0000-000000000000'; 
			$field_05_snr = '00000000-0000-0000-0000-000000000000'; 
			$field_06_snr = '00000000-0000-0000-0000-000000000000'; 
			$field_07_snr = '00000000-0000-0000-0000-000000000000'; 
			$field_01 = ''; 
			$field_02 = '';
			$field_03 = ''; */		
			
			$estatus = '19205DEC-F9F6-441A-9482-DB08D3394057';
			
			$rec_stat = 0;
			
			$nombreAsistente = '';
			$telAsistente = '';
			$mailAsistente = '';
			
			/*$nombreHospital = '';
			$preferenciaContacto = '00000000-0000-0000-0000-000000000000';
			$aceptaApoyo = '00000000-0000-0000-0000-000000000000';
			$porqueAceptaApoyo = '';
			$botiquin = '00000000-0000-0000-0000-000000000000';
			$compraDirecta = '00000000-0000-0000-0000-000000000000';*/
			$liderOpinion = '00000000-0000-0000-0000-000000000000';
			$speaker_snr= '00000000-0000-0000-0000-000000000000';
			//$tipoConsulta = '00000000-0000-0000-0000-000000000000';
		}
		//$mes = substr($fecha, 5,2)*1;
		//$dia = substr($fecha, 8,2)*1;
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
			$('#txtCorreoPersonalPersonaNuevo').val('".$email1Personal."');
			$('#txtCorreoPersonalPersonaNuevo2').val('".$email1Personal2."');
			$('#txtTelPersonalPersonaNuevo').val('".$telPersonal."');
			$('#txtTelPersonalPersonaNuevo2').val('".$telPersonal2."');
			$('#txtCelularPersonaNuevo').val('".$celular."');
			$('#txtNombreInstPersonaNuevo ').val('".$nombreInst."');
			$('#hdnIdInstPersonaNuevo').val('".$idInst."');
			$('#txtCalleInstPersonaNuevo').val('".$calleInst."');
			$('#txtnum_extInstPersonaNuevo').val('".$extInst."');
			$('#txtNumIntInstPersonaNuevo').val('".$intInst."');
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
			/*$('#txtBrickInstPersonaNuevo').val('".$briInst."');*/
			$('#sltEstatusPersonaNuevo').val('".$estatus."');
			$('#sltDificultadVisita').val('".$dificultadVisita."');
			$('#txtNombreAsistentePersonaNuevo').val('".$nombreAsistente."');
			$('#txtTelAsistentePersonaNuevo').val('".$telAsistente."');
			$('#txtCorreoAsistentePersonaNuevo').val('".$mailAsistente."');
			$('#sltLiderOpinionPersonaNuevo').val('".$liderOpinion."');
			$('#sltPadecimientoMedicoPersonaNuevo').val('".$padecimientosMedicos."');
			$('#sltEstadoCivilPersonaNuevo').val('".$estadoCivil."');
			$('#sltPersonaSpeaker').val('".$speaker_snr."');
			$('#sltPersonabrightly').val('".$brightly_snr."');
			";
		//$rsPasatiempos = sqlsrv_query($conn, "select CLIST_SNR as id, name as nombre from CODELIST where CLIB_SNR = '20049108-3068-4224-AB60-92CF4B697E6B' order by name");
		//$rsPasatiempos = llenaCombo($conn, 19, 13);
		//print_r($arrPasatiempos);
		$rsPasatiempos = llenaCombo($conn, 19, 28);
		$contador = 1;
		while($pasatiempo = sqlsrv_fetch_array($rsPasatiempos)){
			if(in_array($pasatiempo['id'], $arrPasatiempos)){
				echo "$('#chkPasatiempoPersonaNuevo".$contador."').prop('checked', true);";
			}else{
				echo "$('#chkPasatiempoPersonaNuevo".$contador."').prop('checked', false);";
			}
			$contador++;
		}
		echo "$('#hdnPasatiempo').val('".$contador."');";
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
			$('#btnGuardarPersonaNuevo').prop('disabled', false);
			</script>";
	}
?>