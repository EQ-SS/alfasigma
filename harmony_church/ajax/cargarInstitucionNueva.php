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
			I.LATITUDE AS LATITUD,
			I.LONGITUDE AS LONGITUD, 
			UT.USER_SNR AS USUARIO_ID, 
			U.LNAME+' '+U.FNAME AS REPRESENTANTE
			From INST I
			left outer join city cp on I.CITY_SNR=CP.CITY_SNR
			left outer join DISTRICT POB on POB.DISTR_SNR=CP.DISTR_SNR
			left outer join STATE EDO on EDO.STATE_SNR=CP.STATE_SNR
			left outer join COUNTRY AS PAIS on PAIS.CTRY_SNR=CP.CTRY_SNR
			left outer join USER_TERRIT AS UT on UT.INST_SNR=I.INST_SNR
			left outer join USERS U on U.USER_SNR=UT.USER_SNR
			left outer join brick bri on bri.brick_snr = cp.brick_snr
			left outer join INST_UD iud on iud.inst_snr = i.inst_snr 
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
		$status = 'B405F75D-499E-4EB8-AAC8-C89F2CA080A4';
		$categoria = '00000000-0000-0000-0000-000000000000';
		$idFrecuencia = 'FFD6DB91-BDA1-48CB-AA8D-25416F7E8952';		
		$tel1 = '';
		$tel2 = '';
		$web = '';
		$email = '';
		$comentarios = '';
		$latitud = '';
		$longitud = ''; 
		$repre = ''; 
		$idTipo = '';
		$numExt = '';
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
	/*$rsTipoPaciente = sqlsrv_query($conn, "select * from codelist where CLIb_SNR = (select clib_snr from codelistlib where table_nr = 483 and LIST_NR = 1003) and status = 1 order by sort_num, name");
	$checkTipoPaciente = 1;
	$contadorChecksTipoPaciente = 0;
	$descripcionesCheckTipoPaciente = '';
	while($regTipoPaciente = sqlsrv_fetch_array($rsTipoPaciente)){
		if(in_array($regTipoPaciente['CLIST_SNR'], $field05)){
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
	*/
	/* fin tipo Paciente*/
	
	/*Tipo Cliente*/
	/*$rsTipoCliente = sqlsrv_query($conn, "select * from codelist where CLIb_SNR = (select clib_snr from codelistlib where table_nr = 485 and LIST_NR = 1001) and status = 1 order by sort_num, name");
	//echo "select * from codelist where CLIb_SNR = (select clib_snr from codelistlib where table_nr = 485 and LIST_NR = 1001) and status = 1 order by sort_num, name";
	$checkTipoCliente = 1;
	$contadorChecksTipoCliente = 0;
	$descripcionesCheckTipoCliente = '';
	while($regTipoCliente = sqlsrv_fetch_array($rsTipoCliente)){
		if(in_array($regTipoCliente['CLIST_SNR'], $field01h)){
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
	*/
	/* fin tipo Cliente*/
	
	/*Prod Competencia*/
	/*$rsProdCompetencia = sqlsrv_query($conn, "select * from codelist where CLIb_SNR = (select clib_snr from codelistlib where table_nr = 485 and LIST_NR = 1008) and status = 1 order by sort_num, name");
	$checkProdCompetencia = 1;
	$contadorChecksProdCompetencia = 0;
	$descripcionesCheckProdCompetencia = '';
	while($regProdCompetencia = sqlsrv_fetch_array($rsProdCompetencia)){
		if(in_array($regProdCompetencia['CLIST_SNR'], $field08h)){
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
	*/	
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
		";
	
	/*if($tipoInst == 1){//hosp
		echo "$('#hospital1').show();
		$('#hospital2').show();
		$('#hospital3').show();";
	}else if($tipoInst == 2){//farmacias
		echo "$('#farm1').show();
		$('#farm2').show();
		$('#farm3').show();
		$('#farm4').show();
		$('#farm5').show();
		$('#farm6').show();";
	}*/
	
	echo "$('#liBasicoInst').click();
	$('#sltCategoriaInstNueva').val('".$categoria."');
	$('#sltFrecuenciaInstNueva').val('".$idFrecuencia."');	
	$('#txNombreInstNueva').val('".$nombre."');
	$('#txtCalleInstNueva').val('".$direccion."');
	$('#txtNumExtInstNueva').val('".$numExt."');
	$('#txtCPInstNueva').val('".$cp."');
	$('#txtCiudadInstNueva').val('".$poblacion."');
	$('#txtEstadoInstNueva').val('".$estado."');
	/*$('#txtBrickInstNueva').val('".$brick."');*/
	$('#txtTel1InstNueva').val('".$tel1."');
	$('#txtTel2InstNueva').val('".$tel2."');
	$('#txtEmailInstNueva').val('".$email."');
	$('#txtWebInstNueva').val('".$web."');
	$('#txtPosicionGPSInstNueva').val('');
	$('#txtComentariosInstNueva').val('".$comentarios."');
	</script>";
}
?>