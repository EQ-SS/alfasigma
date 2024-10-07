<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idPers = $_POST['idPersona'];
		$idUsuario = $_POST['idUsuario'];
		$firma = str_replace('data:image/png;base64,','',$_POST['firma']);
		$fecha = $_POST['fechaAviso'];

		echo $actualizaPersona = "UPDATE PERSON SET 
			AUTHORIZED_PRIVACY = 1,
			AUTHORIZED_PRIVACY_DATE = '".$fecha."', 
			SYNC = 0 
			WHERE PERS_SNR = '".$idPers. "' ";

		if(! sqlsrv_query($conn, $actualizaPersona)){
			echo $actualizaPersona;
		}else{
			$insertaFirma = "INSERT INTO BINARYDATA_PRIVACY ( 
				BD_PRIVACY_SNR,
				TABLE_NR,
				RECORD_KEY,
				DATASTREAM,
				USER_SNR,
				REC_STAT,
				SYNC,
				CREATION_TIMESTAMP
				) VALUES (
				newid(),
				19,
				'".$idPers."',
				'".$firma."',
				'".$idUsuario."',
				0,
				0,
				getdate())";

			if(! sqlsrv_query($conn, $insertaFirma)){
				echo $insertaFirma;
			}
		}


		echo "<script>
			$('#btnCancelarAvisoPrivacidad').click();
			$('#btnActualizarPers').click();
			$('#btnLimpiarFirmaAviso').click();
			</script>";
	
	}
?>