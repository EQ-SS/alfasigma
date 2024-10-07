<?php
include "../conexion.php";
if(! $conn){
	echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
}else{	
	if(isset($_POST['idInst']) && $_POST['idInst'] != ''){
		$idInst = $_POST['idInst'];
			
		$queryInst = "SELECT 
			I.INST_SNR,
			I.TYPE_SNR,
			I.INST_TYPE AS TIPO_INST,
			I.SUBTYPE_SNR AS SUBTIPO,
			I.FORMAT_SNR AS FORMATO,
			I.NAME AS NOMBRE,
			I.STREET1 AS DIRECCION,
			I.NUM_EXT, 
			CP.NAME AS COLONIA,
			CP.ZIP AS CPOSTAL,
			POB.NAME AS POBLACION,
			EDO.NAME AS ESTADO,
			PAIS.NAME AS PAIS,
			I.CITY_SNR,
			bri.name as BRICK,
			I.STATUS_SNR as status,
			I.CATEGORY_SNR as categoria,
			I.FRECVIS_SNR AS FRECUENCIA,
			I.TEL1,
			I.TEL2,
			I.WEB,
			I.EMAIL1 as EMAIL,
			I.INFO AS COMENTARIOS,
			I.BRANCH,
			I.LATITUDE AS LATITUD,
			I.LONGITUDE AS LONGITUD, 
			UT.USER_SNR AS USUARIO_ID, 
			U.LNAME+' '+U.FNAME AS REPRESENTANTE,
			ip.FIELD_01,
			ip.FIELD_02,
			ip.FIELD_03,
			ip.FIELD_04,
			ip.FIELD_05,
			ip.FIELD_06,
			ip.FIELD_07,
			ip.FIELD_01_SNR,
			ip.FIELD_02_SNR,
			ip.FIELD_03_SNR,
			ip.FIELD_04_SNR,
			ip.FIELD_05_SNR,
			ip.FIELD_06_SNR,
			ip.FIELD_07_SNR,
			ip.FIELD_08_SNR,
			ip.FIELD_09_SNR,
			ip.FIELD_10_SNR,
			ih.FIELD_01 as field01h,
			ih.FIELD_02 as field02h,
			ih.FIELD_03 as field03h,
			ih.FIELD_04 as field04h,
			ih.FIELD_05 as field05h,
			ih.FIELD_06 as field06h,
			ih.FIELD_07 as field07h,
			ih.FIELD_08 as field08h,
			ih.FIELD_09 as field09h,
			ih.FIELD_10 as field10h,
			ih.FIELD_11 as field11h,
			ih.FIELD_12 as field12h,
			ih.FIELD_01_SNR as field01h_snr,
			ih.FIELD_02_SNR as field02h_snr,
			ih.FIELD_03_SNR as field03h_snr,
			ih.FIELD_04_SNR as field04h_snr
			From INST I
			left outer join city cp on I.CITY_SNR=CP.CITY_SNR
			left outer join DISTRICT POB on POB.DISTR_SNR=CP.DISTR_SNR
			left outer join STATE EDO on EDO.STATE_SNR=CP.STATE_SNR
			left outer join COUNTRY AS PAIS on PAIS.CTRY_SNR=CP.CTRY_SNR
			left outer join USER_TERRIT AS UT on UT.INST_SNR=I.INST_SNR
			left outer join USERS U on U.USER_SNR=UT.USER_SNR
			left outer join brick bri on bri.brick_snr = cp.brick_snr
			left outer join INST_PHARMACY ip on ip.inst_snr = i.inst_snr
			left outer join INST_HOSPITAL ih on ih.inst_snr = i.inst_snr			
			WHERE I.REC_STAT=0  
			AND UT.REC_STAT=0 
			and i.INST_SNR = '".$idInst."'"; 
			
		$arrInst = sqlsrv_fetch_array(sqlsrv_query($conn, $queryInst));
			
		//echo $queryInst;
		
	}else{
		$idInst = '';
		$idUsuario = $_POST['idUsuario'];
		if(isset($_POST['tabActivo']) && $_POST['tabActivo'] != ''){
			$tabActivo = $_POST['tabActivo'];
		}else{
			$tabActivo = 0;
		}
		
	}
	
	if($idInst != ''){
		$tipoInst = $arrInst['TIPO_INST'];
		$idSubTipo = $arrInst['SUBTIPO'];
		$idFormato = $arrInst['FORMATO'];
		$nombre = utf8_encode($arrInst['NOMBRE']);
		$direccion = utf8_encode($arrInst['DIRECCION']);
		$numExt = $arrInst['NUM_EXT'];
		$colonia = $arrInst['COLONIA'];
		$cp = $arrInst['CPOSTAL'];
		$poblacion = $arrInst['POBLACION'];
		$estado = $arrInst['ESTADO'];
		$pais = $arrInst['PAIS'];
		$brick = $arrInst['BRICK'];
		$status = $arrInst['status'];
		$categoria = $arrInst['categoria'];
		$idFrecuencia = ($arrInst['FRECUENCIA'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['FRECUENCIA'];
		$city = $arrInst['CITY_SNR'];
		$tel1 = $arrInst['TEL1'];
		$tel2 = $arrInst['TEL2'];
		$web = $arrInst['WEB'];
		$email = $arrInst['EMAIL'];
		$comentarios = $arrInst['COMENTARIOS'];
		$latitud = $arrInst['LATITUD'];
		$longitud = $arrInst['LONGITUD'];
		$idUsuario = $arrInst['USUARIO_ID']; 
		$repre = $arrInst['REPRESENTANTE']; 
		$idTipo = $arrInst['TYPE_SNR'];
		$sucursal = $arrInst['BRANCH'];
		
		$field01 = $arrInst['FIELD_01'];
		$field02 = $arrInst['FIELD_02'];
		$field03 = $arrInst['FIELD_03'];
		$field04 = $arrInst['FIELD_04'];
		$field05 = $arrInst['FIELD_05'];
		$field06 = $arrInst['FIELD_06'];
		$field07 = $arrInst['FIELD_07'];
		
		$field01_snr = $arrInst['FIELD_01_SNR'];
		$field02_snr = explode(";", $arrInst['FIELD_02_SNR']);
		$field03_snr = $arrInst['FIELD_03_SNR'];
		$field04_snr = $arrInst['FIELD_04_SNR'];
		$field05_snr = $arrInst['FIELD_05_SNR'];
		$field06_snr = explode(";", $arrInst['FIELD_06_SNR']);
		$field07_snr = explode(";", $arrInst['FIELD_07_SNR']);
		$field08_snr = $arrInst['FIELD_08_SNR'];
		$field09_snr = explode(";", $arrInst['FIELD_09_SNR']);
		$field10_snr = $arrInst['FIELD_10_SNR'];
		
		$field01Hosp = $arrInst['field01h'];
		$field02Hosp = $arrInst['field02h'];
		$field03Hosp = $arrInst['field03h'];
		$field04Hosp = $arrInst['field04h'];
		$field05Hosp = $arrInst['field05h'];
		$field06Hosp = $arrInst['field06h'];
		$field07Hosp = $arrInst['field07h'];
		$field08Hosp = $arrInst['field08h'];
		$field09Hosp = $arrInst['field09h'];
		$field10Hosp = $arrInst['field10h'];
		$field11Hosp = $arrInst['field11h'];
		$field12Hosp = $arrInst['field12h'];
		
		$field01Hosp_snr = explode(";", $arrInst['field01h_snr']);
		$field02Hosp_snr = explode(";", $arrInst['field02h_snr']);
		$field03Hosp_snr = $arrInst['field03h_snr'];
		$field04Hosp_snr = $arrInst['field04h_snr'];
		
	}else{
		//tabActivo
		$tipoInst = $tabActivo;
		
		$idSubTipo = '00000000-0000-0000-0000-000000000000';
		$idFormato = '00000000-0000-0000-0000-000000000000';
		$nombre = '';
		$direccion = '';
		$colonia = '';
		$cp = '';
		$poblacion = '';
		$estado = '';
		$pais = '';
		$brick = '';
		$city = '';
		$status = 'C1141A15-E7AD-4099-A8D4-26C571298B21';
		$categoria = '00000000-0000-0000-0000-000000000000';
		$idFrecuencia = '310C2740-3A33-494A-9979-5A65607B4044';		
		$tel1 = '';
		$tel2 = '';
		$web = '';
		$email = '';
		$comentarios = '';
		$sucursal = '';
		$latitud = '';
		$longitud = ''; 
		$repre = ''; 
		$idTipo = sqlsrv_fetch_array(sqlsrv_query($conn, "select inst_type_snr from INST_TYPE where inst_type = ".$tabActivo))['inst_type_snr'];
		$numExt = '';
		
		$field01 = '';
		$field02 = '';
		$field03 = '';
		$field04 = '';
		$field05 = '';
		$field06 = '';
		$field07 = '';
		
		$field01_snr = '00000000-0000-0000-0000-000000000000';
		$field02_snr = array();
		$field03_snr = '00000000-0000-0000-0000-000000000000';
		$field04_snr = '00000000-0000-0000-0000-000000000000';
		$field05_snr = '00000000-0000-0000-0000-000000000000';
		$field06_snr = array();
		$field07_snr = array();
		$field08_snr = '00000000-0000-0000-0000-000000000000';
		$field09_snr = array();
		$field10_snr = '00000000-0000-0000-0000-000000000000';
		
		$field01Hosp = '';
		$field02Hosp = '';
		$field03Hosp = '';
		$field04Hosp = '';
		$field05Hosp = '';
		$field06Hosp = '';
		$field07Hosp = '';
		$field08Hosp = '';
		$field09Hosp = '';
		$field10Hosp = '';
		$field11Hosp = '';
		$field12Hosp = '';
		
		$field01Hosp_snr = array();
		$field02Hosp_snr = array();
		$field03Hosp_snr = '00000000-0000-0000-0000-000000000000';
		$field04Hosp_snr = '00000000-0000-0000-0000-000000000000';
	}
	
	echo "<script>
		$('#sltColoniaInstNueva').empty();";
	if($colonia == ''){
		echo "$('#sltColoniaInstNueva').append('<option value=\"0\" hidden>Seleccione</option>');";
	}else{
		echo "$('#sltColoniaInstNueva').append('<option value=\"".$colonia."\">".$colonia."</option>');";
	}
	echo "$('#hdnCityInstNueva').val('".$city."');
		$('#sltTipoInstNueva').val('".$idTipo."');
		";
	echo "$('#sltSubtipoInstNueva').empty();
	$('#sltFormatoInstNueva').empty();";
	//echo "tipoInst: ".$tipoInst."<br>";
	if($tipoInst != ''){
		//echo "tipo: ".$tipoInst;
		
		if($idTipo != ''){
			$rsSubtipo = llenaComboInst($conn, "14", "2", $idTipo);
			echo "$('#sltSubtipoInstNueva').append('<option value=\"00000000-0000-0000-0000-000000000000\" ></option>');";
			while($subtipoArr = sqlsrv_fetch_array($rsSubtipo)){
				if($subtipoArr['id'] == $idSubTipo) {
					echo "$('#sltSubtipoInstNueva').append('<option value=\"".$subtipoArr['id']."\" selected>".$subtipoArr['nombre']."</option>');";
				} else {
					echo "$('#sltSubtipoInstNueva').append('<option value=\"".$subtipoArr['id']."\" >".$subtipoArr['nombre']."</option>');";
				}
			}
			echo "
			$('#sltFormatoInstNueva').append('<option value=\"00000000-0000-0000-0000-000000000000\" selected></option>');";
			if($idSubTipo != ''){
				$rsFormato = llenaComboInst($conn, "14", "3", $idSubTipo);
				while($formatoArr = sqlsrv_fetch_array($rsFormato)) {
					if($formatoArr['id'] == $idFormato) {
						echo "$('#sltFormatoInstNueva').append('<option value=\"".$formatoArr['id']."\" selected>".$formatoArr['nombre']."</option>');";
					} else { 
						echo "$('#sltFormatoInstNueva').append('<option value=\"".$formatoArr['id']."\" >".$formatoArr['nombre']."</option>');";
					}
				}
			}
		}
	}
	
	/*Tipo Paciente*/
	$rsTipoPaciente = sqlsrv_query($conn, "select * from codelist where CLIb_SNR = (select clib_snr from codelistlib where table_nr = 483 and LIST_NR = 2) and status = 1 order by sort_num, name");
	$checkTipoPaciente = 1;
	$contadorChecksTipoPaciente = 0;
	$descripcionesCheckTipoPaciente = '';
	while($regTipoPaciente = sqlsrv_fetch_array($rsTipoPaciente)){
		if(in_array($regTipoPaciente['CLIST_SNR'], $field02_snr)){
			echo "$('#tipoPaciente".$checkTipoPaciente."').prop('checked', true);";
			$descripcionesCheckTipoPaciente .= $regTipoPaciente['NAME']."; ";
		}else{
			echo "$('#tipoPaciente".$checkTipoPaciente."').prop('checked', false);";
		}
		$checkTipoPaciente++;
		$contadorChecksTipoPaciente++;
	}
	echo "$('#hdnTotalChecksTipoPaciente').val('".$contadorChecksTipoPaciente."');
		$('#hdnDescripcionChkTipoPaciente').val('".$descripcionesCheckTipoPaciente."');
		$('#sltMultiSelectTipoPaciente').text('".$descripcionesCheckTipoPaciente."');";
	
	/* fin tipo Paciente*/
	
	/*field 06 */
	$rsField06 = sqlsrv_query($conn, "select * from codelist where CLIb_SNR = (select clib_snr from codelistlib where table_nr = 483 and LIST_NR = 6) and status = 1 order by sort_num, name");
	$checkField06 = 1;
	$contadorChecksField06 = 0;
	$descripcionesCheckField06 = '';
	while($regField06 = sqlsrv_fetch_array($rsField06)){
		if(in_array($regField06['CLIST_SNR'], $field06_snr)){
			echo "$('#field06".$checkField06."').prop('checked', true);";
			$descripcionesCheckField06 .= $regField06['NAME']."; ";
		}else{
			echo "$('#field06".$checkField06."').prop('checked', false);";
		}
		$checkField06++;
		$contadorChecksField06++;
	}
	echo "$('#hdnTotalChecksField06').val('".$contadorChecksField06."');
		$('#hdnDescripcionChkField06').val('".$descripcionesCheckField06."');
		$('#sltMultiSelectField06').text('".$descripcionesCheckField06."');";
	
	/* fin field 06 */
	
	/*field 07 */
	$rsField07 = sqlsrv_query($conn, "select * from codelist where CLIb_SNR = (select clib_snr from codelistlib where table_nr = 483 and LIST_NR = 7) and status = 1 order by sort_num, name");
	$checkField07 = 1;
	$contadorChecksField07 = 0;
	$descripcionesCheckField07 = '';
	while($regField07 = sqlsrv_fetch_array($rsField07)){
		if(in_array($regField07['CLIST_SNR'], $field07_snr)){
			echo "$('#field07".$checkField07."').prop('checked', true);";
			$descripcionesCheckField07 .= $regField07['NAME']."; ";
		}else{
			echo "$('#field07".$checkField07."').prop('checked', false);";
		}
		$checkField07++;
		$contadorChecksField07++;
	}
	echo "$('#hdnTotalChecksField07').val('".$contadorChecksField07."');
		$('#hdnDescripcionChkField07').val('".$descripcionesCheckField07."');
		$('#sltMultiSelectField07').text('".$descripcionesCheckField07."');";
	
	/* fin field 07 */
	
	/*field 09 */
	$rsField09 = sqlsrv_query($conn, "select * from codelist where CLIb_SNR = (select clib_snr from codelistlib where table_nr = 483 and LIST_NR = 9) and status = 1 order by sort_num, name");
	$checkField09 = 1;
	$contadorChecksField09 = 0;
	$descripcionesCheckField09 = '';
	while($regField09 = sqlsrv_fetch_array($rsField09)){
		if(in_array($regField09['CLIST_SNR'], $field09_snr)){
			echo "$('#field09".$checkField09."').prop('checked', true);";
			$descripcionesCheckField09 .= $regField09['NAME']."; ";
		}else{
			echo "$('#field09".$checkField09."').prop('checked', false);";
		}
		$checkField09++;
		$contadorChecksField09++;
	}
	echo "$('#hdnTotalChecksField09').val('".$contadorChecksField09."');
		$('#hdnDescripcionChkField09').val('".$descripcionesCheckField09."');
		$('#sltMultiSelectField09').text('".$descripcionesCheckField09."');";
	
	/* fin field 09 */
	
	/*Tipo Cliente*/
	$rsTipoCliente = sqlsrv_query($conn, "select * from codelist where CLIb_SNR = (select clib_snr from codelistlib where table_nr = 485 and LIST_NR = 1) and status = 1 order by sort_num, name");
	//echo "select * from codelist where CLIb_SNR = (select clib_snr from codelistlib where table_nr = 485 and LIST_NR = 1001) and status = 1 order by sort_num, name";
	$checkTipoCliente = 1;
	$contadorChecksTipoCliente = 0;
	$descripcionesCheckTipoCliente = '';
	while($regTipoCliente = sqlsrv_fetch_array($rsTipoCliente)){
		if(in_array($regTipoCliente['CLIST_SNR'], $field01Hosp_snr)){
			echo "$('#tipoCliente".$checkTipoCliente."').prop('checked', true);";
			$descripcionesCheckTipoCliente .= $regTipoCliente['NAME']."; ";
		}else{
			echo "$('#tipoCliente".$checkTipoCliente."').prop('checked', false);";
		}
		$checkTipoCliente++;
		$contadorChecksTipoCliente++;
	}
	echo "$('#hdnTotalChecksTipoCliente').val('".$contadorChecksTipoCliente."');
		$('#hdnDescripcionChkTipoCliente').val('".$descripcionesCheckTipoCliente."');
		$('#sltMultiSelectTipoCliente').text('".$descripcionesCheckTipoCliente."');";
	
	/* fin tipo Cliente*/
	
	/*Prod Competencia*/
	$rsProdCompetencia = sqlsrv_query($conn, "select * from codelist where CLIb_SNR = (select clib_snr from codelistlib where table_nr = 485 and LIST_NR = 2) and status = 1 order by sort_num, name");
	$checkProdCompetencia = 1;
	$contadorChecksProdCompetencia = 0;
	$descripcionesCheckProdCompetencia = '';
	while($regProdCompetencia = sqlsrv_fetch_array($rsProdCompetencia)){
		
		if(in_array($regProdCompetencia['CLIST_SNR'], $field02Hosp_snr)){
			echo "$('#prodCompetencia".$checkProdCompetencia."').prop('checked', true);";
			$descripcionesCheckProdCompetencia .= $regProdCompetencia['NAME']."; ";
		}else{
			echo "$('#prodCompetencia".$checkProdCompetencia."').prop('checked', false);";
		}
		$checkProdCompetencia++;
		$contadorChecksProdCompetencia++;
	}
	echo "$('#hdnTotalChecksProdCompetencia').val('".$contadorChecksProdCompetencia."');
		$('#hdnDescripcionChkProdCompetencia').val('".$descripcionesCheckProdCompetencia."');
		$('#sltMultiSelectProdCompetencia').text('".$descripcionesCheckProdCompetencia."');";
		
	/* fin Prod Competencia*/
	
	if($status == '00000000-0000-0000-0000-000000000000'){
		echo "$('#sltEstatusInstNueva').val('ACTIVO');";
	}else{
		echo "$('#sltEstatusInstNueva').val('".$status."');";
	}
	
	echo "
		$('#farm1').hide();
		$('#farm2').hide();
		$('#farm3').hide();
		$('#farm4').hide();
		$('#farm5').hide();
		$('#farm6').hide();
		$('#hospital1').hide();
		$('#hospital2').hide();
		$('#hospital3').hide();
		$('#txtSucursalInstNueva').prop('disabled', true);
		";
	
	if($tipoInst == 1){//hosp
		echo "$('#hospital1').show();
		$('#hospital2').show();
		$('#hospital3').show();";
	}else if($tipoInst == 2){//farmacias
		echo "$('#farm1').show();
		$('#farm2').show();
		$('#farm3').show();
		$('#farm4').show();
		$('#farm5').show();
		$('#farm6').show();
		$('#txtSucursalInstNueva').prop('disabled', false);";
	}
	
	echo "$('#liBasicoInst').click();
	$('#sltCategoriaInstNueva').val('".$categoria."');
	$('#sltFrecuenciaInstNueva').val('".$idFrecuencia."');	
	$('#txNombreInstNueva').val('".$nombre."');
	$('#txtCalleInstNueva').val('".$direccion."');
	$('#txtNumExtInstNueva').val('".$numExt."');
	$('#txtCPInstNueva').val('".$cp."');
	$('#txtCiudadInstNueva').val('".$poblacion."');
	$('#txtEstadoInstNueva').val('".$estado."');
	$('#txtBrickInstNueva').val('".$brick."');
	$('#txtTel1InstNueva').val('".$tel1."');
	$('#txtTel2InstNueva').val('".$tel2."');
	$('#txtEmailInstNueva').val('".$email."');
	$('#txtWebInstNueva').val('".$web."');
	$('#txtPosicionGPSInstNueva').val('');
	$('#txtComentariosInstNueva').val('".$comentarios."');
	$('#txtSucursalInstNueva').val('".$sucursal."');
	$('#txtField01InstNueva').val('".$field01."');
	$('#txtField02InstNueva').val('".$field02."');
	$('#txtField03InstNueva').val('".$field03."');
	$('#txtField04InstNueva').val('".$field04."');
	$('#txtField05InstNueva').val('".$field05."');
	$('#txtField06InstNueva').val('".$field06."');
	$('#txtField07InstNueva').val('".$field07."');
	$('#sltField01InstNueva').val('".$field01_snr."');
	$('#sltField03InstNueva').val('".$field03_snr."');
	$('#sltField04InstNueva').val('".$field04_snr."');
	$('#sltField05InstNueva').val('".$field05_snr."');
	$('#sltField08InstNueva').val('".$field08_snr."');
	$('#sltField10InstNueva').val('".$field10_snr."');
	
	$('#txtField01InstNuevaHosp').val('".$field01Hosp."');
	$('#txtField02InstNuevaHosp').val('".$field02Hosp."');
	$('#txtField03InstNuevaHosp').val('".$field03Hosp."');
	$('#txtField04InstNuevaHosp').val('".$field04Hosp."');
	$('#txtField05InstNuevaHosp').val('".$field05Hosp."');
	$('#txtField06InstNuevaHosp').val('".$field06Hosp."');
	$('#txtField07InstNuevaHosp').val('".$field07Hosp."');
	$('#txtField08InstNuevaHosp').val('".$field08Hosp."');
	$('#txtField09InstNuevaHosp').val('".$field09Hosp."');
	$('#txtField10InstNuevaHosp').val('".$field10Hosp."');
	$('#txtField11InstNuevaHosp').val('".$field11Hosp."');
	$('#txtField12InstNuevaHosp').val('".$field12Hosp."');
	
	$('#sltField03InstNuevaHosp').val('".$field03Hosp_snr."');
	$('#sltField04InstNuevaHosp').val('".$field04Hosp_snr."');
	
	</script>";
}

?>