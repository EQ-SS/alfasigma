<?php
include "../conexion.php";
if(! $conn){
	echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
}else{	
	if(isset($_POST['idInst']) && $_POST['idInst'] != ''){
		$idInst = $_POST['idInst'];
		
		/*$queryInst = "SELECT 
			I.INST_SNR,
			I.TYPE_SNR,
			I.TYPE_SNR AS TIPO_INST,
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
			U.LNAME+' '+U.FNAME AS REPRESENTANTE, 
			ud.NiveldeVentas,
			ud.NombComerFarm,
			ud.RotacionSilanes,
			ud.TipodePacientes,
			ud.NumEmp,
			ud.CtesporDia,
			ud.NumMedAlFarm,
			ud.AccesFarm,
			ud.NumMostAtiendenCtes,
			ud.RecibeVendedores,
			ud.VentasdeGenericos,
			ud.TamanoFarmacia,
			ud.Mayorista1,
			ud.Mayorista2,
			ud.NumdeAnaquel,
			ud.NumVisitasCiclo,
			ud.TurnosVisita,
			ud.TrabInstPublicas,
			ud.UbicFarmacia,
			ih.NumCamas,
			ih.NumQuirofanos,
			ih.NumSalasExpulsion,
			ih.NumCunas,
			ih.NumIncubadoras,
			ih.TerapiaIntensiva,
			ih.UnidadCuidadosIntensivos,
			ih.Infectologia,
			ih.Laboratorio,
			ih.Urgencias,
			ih.RayosX,
			ih.Farmacia,
			ih.Botiquin,
			ih.Endoscopia,
			ih.ConsultaExterna,
			ih.CirugiaAmbulatoria,
			ih.Scanner,
			ih.Ultrasonido,
			ih.Dialisis,
			ih.Hemodialisis,
			ih.ResonanciaMagnetica
			From INST I
			left outer join city cp on I.CITY_SNR=CP.CITY_SNR
			left outer join DISTRICT POB on POB.DISTR_SNR=CP.DISTR_SNR
			left outer join inst_pharmacy ud on ud.INST_SNR = i.inst_snr
			left outer join STATE EDO on EDO.STATE_SNR=CP.STATE_SNR
			left outer join COUNTRY AS PAIS on PAIS.CTRY_SNR=CP.CTRY_SNR
			left outer join USER_TERRIT AS UT on UT.INST_SNR=I.INST_SNR
			left outer join USERS U on U.USER_SNR=UT.USER_SNR
			left outer join inst_hosp ih on i.INST_SNR = ih.inst_snr
			left outer join brick bri on bri.brick_snr = cp.brick_snr
			WHERE I.REC_STAT=0  
			AND UT.REC_STAT=0 
			and i.INST_SNR = '".$idInst."'"; */
			
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
			U.LNAME+' '+U.FNAME AS REPRESENTANTE, 
			u.user_snr,
			UD.FIELD_01_SNR,
			UD.FIELD_02_SNR,
			UD.FIELD_03_SNR,
			UD.FIELD_04_SNR,
			UD.FIELD_05_SNR,
			UD.FIELD_06_SNR,
			UD.FIELD_07_SNR 
			From INST I
			left outer join city cp on I.CITY_SNR=CP.CITY_SNR
			left outer join DISTRICT POB on POB.DISTR_SNR=CP.DISTR_SNR
			left outer join inst_pharmacy ud on ud.INST_SNR = i.inst_snr
			left outer join STATE EDO on EDO.STATE_SNR=CP.STATE_SNR
			left outer join COUNTRY AS PAIS on PAIS.CTRY_SNR=CP.CTRY_SNR
			left outer join USER_TERRIT AS UT on UT.INST_SNR=I.INST_SNR
			left outer join USERS U on U.USER_SNR=UT.USER_SNR
			left outer join brick bri on bri.brick_snr = cp.brick_snr
			WHERE I.REC_STAT=0  
			AND UT.REC_STAT=0 
			and i.INST_SNR = '".$idInst."'"; 
			
			$arrInst = sqlsrv_fetch_array(sqlsrv_query($conn, $queryInst));
			
		//echo $queryInst;
		
		$idUsuario = $arrInst['user_snr'];
		
	}else{
		$idInst = '';
		$idUsuario = $_POST['idUsuario'];
		if(isset($_POST['tabActivo']) && $_POST['tabActivo'] != ''){
			$tabActivo = $_POST['tabActivo'];
		}else{
			$tabActivo = 0;
		}
	}
	
	$tipoUsuario = sqlsrv_fetch_array(sqlsrv_query($conn, "select user_type from users where user_snr = '".$idUsuario."'"))['user_type'];
	
	if($idInst != ''){
		$tipoInst = $arrInst['TIPO_INST'];
		$idSubTipo = $arrInst['SUBTIPO'];
		$idFormato = $arrInst['FORMATO'];
		$nombre = $arrInst['NOMBRE'];
		$direccion = $arrInst['DIRECCION'];
		$numExt = $arrInst['NUM_EXT'];
		$colonia = $arrInst['COLONIA'];
		$cp = $arrInst['CPOSTAL'];
		$poblacion = $arrInst['POBLACION'];
		$estado = $arrInst['ESTADO'];
		$pais = $arrInst['PAIS'];
		$brick = $arrInst['BRICK'];
		$status = $arrInst['status'];
		$categoria = $arrInst['categoria'];
		$idFrecuencia = $arrInst['FRECUENCIA'];
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
		/*
		$nivelVenta = ($arrInst['NiveldeVentas'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['NiveldeVentas'];
		$nombreComercialFarmacia = $arrInst['NombComerFarm'];
		$rotacion = ($arrInst['RotacionSilanes'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['RotacionSilanes'];
		$tipoPaciente = ($arrInst['TipodePacientes'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['TipodePacientes'];
		$numEmp = $arrInst['NumEmp'];
		$clientesXdia = ($arrInst['CtesporDia'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['CtesporDia'];
		$numMedFarm = $arrInst['NumMedAlFarm'];
		$accesibilidad = ($arrInst['AccesFarm'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['AccesFarm'];
		$numCtes = $arrInst['NumMostAtiendenCtes'];
		$recibeVendedores =	($arrInst['RecibeVendedores'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['RecibeVendedores'];
		$ventaGenericos = ($arrInst['VentasdeGenericos'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['VentasdeGenericos'];
		$tamanoFarmacia = ($arrInst['TamanoFarmacia'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['TamanoFarmacia'];
		$mayorista1 = ($arrInst['Mayorista1'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['Mayorista1'];
		$mayorista2 = ($arrInst['Mayorista2'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['Mayorista2'];
		$numAnaqueles = $arrInst['NumdeAnaquel'];
		$numVisitasXciclo =	$arrInst['NumVisitasCiclo'];
		$turnos = ($arrInst['TurnosVisita'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['TurnosVisita'];
		$trabajaInstPublica = ($arrInst['TrabInstPublicas'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['TrabInstPublicas'];
		$ubicacion = ($arrInst['UbicFarmacia'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrInst['UbicFarmacia'];
		*/
		/*$numCamas = $arrInst['NumCamas'];
		$numQuirofanos = $arrInst['NumQuirofanos'];
		$numSalasExpulsion = $arrInst['NumSalasExpulsion'];
		$numCunas = $arrInst['NumCunas'];
		$numIncubadoras = $arrInst['NumIncubadoras'];
		$terapiaIntensiva = $arrInst['TerapiaIntensiva'];
		$unidadCuidadosIntensivos = $arrInst['UnidadCuidadosIntensivos'];
		$infectologia = $arrInst['Infectologia'];
		$laboratorio = $arrInst['Laboratorio'];
		$urgencias = $arrInst['Urgencias'];
		$rayosx = $arrInst['RayosX'];
		$farmacia = $arrInst['Farmacia'];
		$botiquin = $arrInst['Botiquin'];
		$endoscopia = $arrInst['Endoscopia'];
		$consultaExterna = $arrInst['ConsultaExterna'];
		$cirugiaAmbulatoria = $arrInst['CirugiaAmbulatoria'];
		$scanner = $arrInst['Scanner'];
		$ultrasonido = $arrInst['Ultrasonido'];
		$dialisis = $arrInst['Dialisis'];
		$hemodialisis = $arrInst['Hemodialisis'];
		$resonanciaMagnetica = $arrInst['ResonanciaMagnetica'];*/
		$idTipo = $arrInst['TYPE_SNR'];
		$ruta = $arrInst['user_snr'];

		$mayoristas = $arrInst['FIELD_01_SNR'];
		$flonorm = $arrInst['FIELD_02_SNR'];
		$vessel = $arrInst['FIELD_03_SNR'];
		$ateka = $arrInst['FIELD_04_SNR'];
		$zirfos = $arrInst['FIELD_05_SNR'];
		$esoxx = $arrInst['FIELD_06_SNR'];
		$catAlfa=$arrInst['FIELD_07_SNR'];
		
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
		$idFrecuencia = '00000000-0000-0000-0000-000000000000';		
		$tel1 = '';
		$tel2 = '';
		/*$fax = '';*/
		$web = '';
		$email = '';
		$comentarios = '';
		$latitud = '';
		$longitud = ''; 
		$repre = ''; 
		/*$sucursal = '';*/
		$nivelVenta = '00000000-0000-0000-0000-000000000000';
		$nombreComercialFarmacia = '';
		$rotacion = '00000000-0000-0000-0000-000000000000';
		$tipoPaciente = '00000000-0000-0000-0000-000000000000';
		$numEmp = '';
		$clientesXdia = '00000000-0000-0000-0000-000000000000';
		$numMedFarm = '';
		$accesibilidad = '00000000-0000-0000-0000-000000000000';
		$numCtes = '';
		$recibeVendedores =	'00000000-0000-0000-0000-000000000000';
		$ventaGenericos = '00000000-0000-0000-0000-000000000000';
		$tamanoFarmacia = '00000000-0000-0000-0000-000000000000';
		$mayorista1 = '00000000-0000-0000-0000-000000000000';
		$mayorista2 = '00000000-0000-0000-0000-000000000000';
		$numAnaqueles = '';
		$numVisitasXciclo =	'';
		$turnos = '00000000-0000-0000-0000-000000000000';
		$trabajaInstPublica = '00000000-0000-0000-0000-000000000000';
		$ubicacion = '00000000-0000-0000-0000-000000000000';
		/*$numCamas = '';
		$numQuirofanos = '';
		$numSalasExpulsion = '';
		$numCunas = '';
		$numIncubadoras = '';
		$terapiaIntensiva = 'false';
		$unidadCuidadosIntensivos = 'false';
		$infectologia = 'false';
		$laboratorio = 'false';
		$urgencias = 'false';
		$rayosx = 'false';
		$farmacia = 'false';
		$botiquin = 'false';
		$endoscopia = 'false';
		$consultaExterna = 'false';
		$cirugiaAmbulatoria = 'false';
		$scanner = 'false';
		$ultrasonido = 'false';
		$dialisis = 'false';
		$hemodialisis = 'false';
		$resonanciaMagnetica = 'false';*/
		$idTipo = '';
		$numExt = '';
		$ruta = '00000000-0000-0000-0000-000000000000';

		$mayoristas ='00000000-0000-0000-0000-000000000000';

		$flonorm = '00000000-0000-0000-0000-000000000000';

		$vessel = '00000000-0000-0000-0000-000000000000';

		$ateka = '00000000-0000-0000-0000-000000000000';

		$zirfos = '00000000-0000-0000-0000-000000000000';

		$esoxx = '00000000-0000-0000-0000-000000000000';

		$catAlfa='00000000-0000-0000-0000-000000000000';

	}
	
	echo "<script>
		$('#sltColoniaInstNueva').empty();";
	/*if($tipoInst == '1'){
		echo "$('#liHospitalesNueva').show();
		$('#liFarmaciasNueva').hide();";
	}else if($tipoInst == '2'){
		echo "$('#liFarmaciasNueva').show();
		$('#liHospitalesNueva').hide();";
	}else{
		echo "$('#liFarmaciasNueva').hide();
		$('#liHospitalesNueva').hide();";
	}*/
	if($colonia == ''){
		echo "$('#sltColoniaInstNueva').append('<option value=\"0\" hidden>Seleccione</option>');";
	}else{
		echo "$('#sltColoniaInstNueva').append('<option value=\"".$colonia."\">".$colonia."</option>');";
	}
	echo "$('#hdnCityInstNueva').val('".$city."');
		$('#sltTipoInstNueva').val('".$idTipo."');
		";
	//echo "tipoInst: ".$tipoInst."<br>";
	if($tipoInst != ''){
		//echo "tipo: ".$tipoInst;
		echo "$('#sltSubtipoInstNueva').empty();";
		if($idTipo != ''){
			$rsSubtipo = llenaComboInst($conn, "14", "2", $idTipo);
			/*echo $query = "select * 
			from CODELIST cl
			where cl.REC_STAT = 0
			and cl.status=1
			and cl.clcat_snr = '".$idTipo."' ";*/
			echo "$('#sltSubtipoInstNueva').append('<option value=\"00000000-0000-0000-0000-000000000000\" ></option>');";
			while($subtipoArr = sqlsrv_fetch_array($rsSubtipo)){
				if($subtipoArr['id'] == $idSubTipo) {
					echo "$('#sltSubtipoInstNueva').append('<option value=\"".$subtipoArr['id']."\" selected>".$subtipoArr['nombre']."</option>');";
				} else {
					echo "$('#sltSubtipoInstNueva').append('<option value=\"".$subtipoArr['id']."\" >".$subtipoArr['nombre']."</option>');";
				}
			}
			//echo "subtipo: ".$idSubTipo."<br>";
			echo "$('#sltFormatoInstNueva').empty();
			$('#sltFormatoInstNueva').append('<option value=\"00000000-0000-0000-0000-000000000000\" selected></option>');";
			if($idSubTipo != ''){
				//$idSubTipo = '00000000-0000-0000-0000-000000000000';
				$rsFormato = llenaComboInst($conn, "14", "3", $idSubTipo);
				/*echo $query = "select * 
					from CODELIST cl
					where cl.REC_STAT = 0
					and cl.status=1
					and cl.clcat_snr = '".$idSubTipo."' ";*/
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
	if($status == '00000000-0000-0000-0000-000000000000'){
		echo "$('#sltEstatusInstNueva').val('ACTIVO');";
	}else{
		echo "$('#sltEstatusInstNueva').val('".$status."');";
	}
	//aqui van los campos en el espacio
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


	$('#sltMayoristas').val('".$mayoristas."');
	$('#sltFlonorm').val('".$flonorm."');
	$('#sltVessel').val('".$vessel."');
	$('#sltAteka').val('".$ateka."');
	$('#sltZirfos').val('".$zirfos."');
	$('#sltEsoxx').val('".$esoxx."');
	$('#sltCatAlfa').val('".$catAlfa."');
	
	
	
	
	$('#sltRutaInstNueva').val('".$ruta."'); 
	if('".$tipoUsuario."' != 4){
		$('#divRutaInst').show();
	}else{
		$('#divRutaInst').hide();
	}";
	if($idInst == ''){
		echo "$('#sltRutaInstNueva').prop('disabled', false);";
	}else{
		echo "$('#sltRutaInstNueva').prop('disabled', true);";
	}
	echo "</script>";
}
/*$('#txtNumCamasInstNueva').val('".$numCamas."');
	$('#txtNumQuirofanosInstNueva').val('".$numQuirofanos."');
	$('#txtNumSalasExpulsionInstNueva').val('".$numSalasExpulsion."');
	$('#txtNumCunasInstNueva').val('".$numCunas."');
	$('#txtNumIncubadorasInstNueva').val('".$numIncubadoras."');
	$('#chkTerapiaIntensiva').prop('checked', ".$terapiaIntensiva.");
	$('#chkUnidadCuidadosIntensivos').prop('checked', ".$unidadCuidadosIntensivos.");
	$('#chkInfectologia').prop('checked', ".$infectologia.");
	$('#chkLaboratorio').prop('checked', ".$laboratorio.");
	$('#chkUrgencias').prop('checked', ".$urgencias.");
	$('#chkRayosx').prop('checked', ".$rayosx.");
	$('#chkFarmacia').prop('checked', ".$farmacia.");
	$('#chkBotiquin').prop('checked', ".$botiquin.");
	$('#chkEndoscopia').prop('checked', ".$endoscopia.");
	$('#chkConsultaExterna').prop('checked', ".$consultaExterna.");
	$('#chkCirugiaAmbulatoria').prop('checked', ".$cirugiaAmbulatoria.");
	$('#chkScanner').prop('checked', ".$scanner.");
	$('#chkUltrasonido').prop('checked', ".$ultrasonido.");
	$('#chkDialisis').prop('checked', ".$dialisis.");
	$('#chkHemodialisis').prop('checked', ".$hemodialisis.");
	$('#chkResonanciaMagnetica').prop('checked', ".$resonanciaMagnetica."); */
//echo "hola";
?>