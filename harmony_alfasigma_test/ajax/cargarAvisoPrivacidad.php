<?php
	include "../conexion.php";

	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array(" ", " ", " ", " ");
	$reemplazar1=array("", "", "", "");
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$idPersona = $_POST['idPersona'];
		
		$qPersona = "SELECT LNAME, MOTHERS_LNAME, FNAME, AUTHORIZED_PRIVACY, AUTHORIZED_PRIVACY_DATE 
			FROM PERSON 
			WHERE PERS_SNR = '".$idPersona."' 
			AND REC_STAT = 0 ";

		$rsPersona = sqlsrv_query($conn, $qPersona);

		$arrPersona = sqlsrv_fetch_array($rsPersona);

		$nombre = $arrPersona['LNAME'].' '.$arrPersona['MOTHERS_LNAME'].' '.$arrPersona['FNAME'];
		$fecha = date('Y-m-d');
		if(is_object($arrPersona['AUTHORIZED_PRIVACY_DATE'])){
			foreach ($arrPersona['AUTHORIZED_PRIVACY_DATE'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha = substr($val, 0, 10);
				}
			}
		}

		$qFirma = "SELECT DATASTREAM 
			FROM BINARYDATA_PRIVACY 
			WHERE RECORD_KEY = '".$idPersona."'";

		$rsFirma = sqlsrv_query($conn, $qFirma);

		$arrFirma = sqlsrv_fetch_array($rsFirma);

		$firma = $arrFirma['DATASTREAM'];

		echo "<script>
			$('#hdnIdPersonaAvisoPrivacidad').val('".$idPersona."');
			$('#txtNombreMedicoAviso').val('".$nombre."');
			$('#txtFechaAvisoPrivacidad').val('".$fecha."');";
		if($firma != ''){
			echo "
				$('#tblFirmaAviso').hide();
				$('#imgFirmaAvisoPrivacidad').show();
				$('#imgFirmaAvisoPrivacidad').attr('src', 'data:image/png;base64,".str_ireplace($buscar,$reemplazar1,str_replace(" ","+",trim($firma)))."');
				$('#f64').val('".str_ireplace($buscar,$reemplazar,trim($firma))."');
				$('#btnGuardarAvisoPrivacidad').prop('disabled', true);";
		}else{
			echo "
			$('#imgFirmaAvisoPrivacidad').hide();
			$('#tblFirmaAviso').show();
			$('#btnGuardarAvisoPrivacidad').prop('disabled', false);
			";
		}
		echo "</script>";
	}
	
	
?>