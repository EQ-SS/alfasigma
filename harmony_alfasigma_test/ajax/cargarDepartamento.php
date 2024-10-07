<?php
include "../conexion.php";
if(! $conn){
	echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
}else{
	
	if(isset($_POST['idDepto']) && $_POST['idDepto'] != ''){
		$idDepto = $_POST['idDepto'];
	}else{
		$idDepto = '';
	}
	
	if($idDepto != ''){
		$queryDepto = "select 
			D.DEPART_SNR,
			D.STATUS_SNR,
			D.DEPTYPE_SNR,
			D.NAME,
			D.STREET2,
			D.STREET1,
			CP.NAME AS COLONIA,
			CP.ZIP AS CPOSTAL,
			POB.NAME AS POBLACION,
			EDO.NAME AS ESTADO,
			PAIS.NAME AS PAIS,
			D.CITY_SNR,
			bri.name as BRICK,
			D.TEL1,
			D.TEL2,
			D.FAX,
			D.EMAIL,
			D.INFO
			from depart D
			left outer join city CP on d.CITY_SNR=CP.CITY_SNR 
			left outer join DISTRICT POB on POB.DISTR_SNR=CP.DISTR_SNR
			left outer join STATE EDO on EDO.STATE_SNR=CP.STATE_SNR
			left outer join COUNTRY AS PAIS on PAIS.CTRY_SNR=CP.CTRY_SNR
			left outer join ims_brick bri on bri.imsbrick_snr = cp.imsbrick_snr
			where D.DEPART_SNR = '".$idDepto."' ";
		
		$registro = sqlsrv_fetch_array(sqlsrv_query($conn, $queryDepto));
		
		$idDepto = $registro['DEPART_SNR'];
		$estatus = $registro['STATUS_SNR'];
		$tipo = $registro['DEPTYPE_SNR'];
		$nombre = $registro['NAME'];
		$responsable  = $registro['STREET2'];
		$calle = $registro['STREET1'];
		$cp = $registro['CPOSTAL'];
		$colonia = $registro['COLONIA'];
		$ciudad = $registro['POBLACION'];
		$estado = $registro['ESTADO'];
		$pais = $registro['PAIS'];
		$city = $registro['CITY_SNR'];
		$brick = $registro['BRICK'];
		$tel1 = $registro['TEL1'];
		$tel2 = $registro['TEL2'];
		$celular = $registro['FAX'];
		$email = $registro['EMAIL'];
		$comentarios = $registro['INFO'];
	}else{
		$estatus = '00000000-0000-0000-0000-000000000000';
		$tipo = '';
		$nombre = '';
		$responsable  = '';
		$calle = '';
		$cp = '';
		$colonia = '';
		$ciudad = '';
		$estado = '';
		$pais = '';
		$city = '00000000-0000-0000-0000-000000000000';
		$brick = '';
		$tel1 = '';
		$tel2 = '';
		$celular = '';
		$email = '';
		$comentarios = '';
	}
	
	echo "<script>
	$('#sltColoniaDepto').empty();";
	
	if($colonia == ''){
		echo "$('#sltColoniaDepto').append('<option value=\"0\">Seleccione</option>');";
	}else{
		echo "$('#sltColoniaDepto').append('<option value=\"".$colonia."\">".$colonia."</option>');";
	}
	echo "$('#hdnIDepto').val('".$idDepto."');
		$('#hdnCityDepto').val('".$city."');
		$('#sltTipoDepartamento').val('".$tipo."');
		$('#txtNombreDepartamento').val('".$nombre."');
		$('#txtNombreResponsableDepto').val('".$responsable."');
		$('#txtCalleDepto').val('".$calle."');
		$('#txtCPDepto').val('".$cp."');
		$('#txtCiudadDepto').val('".$ciudad."');
		$('#txtEstadoDepto').val('".$estado."');
		$('#txtBrickDepto').val('".$brick."');
		$('#txtTelefono1Depto').val('".$tel1."');
		$('#txtTelefono2Depto').val('".$tel2."');
		$('#txtCelularDepto').val('".$celular."');
		$('#txtEmailDepto').val('".$email."');
		$('#txtComentariosDepto').val('".$comentarios."');
	</script>";
}
?>