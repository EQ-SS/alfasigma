<?php
	include "../conexion.php";
	//echo "pantalla".$_POST['pantalla'];
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idUser = $_POST['idUser'];
		$arrPara = explode(",", substr($_POST['para'], 0, -1));
		$asunto = $_POST['asunto'];
		$mensaje = $_POST['mensaje'];
		$fecha = date("Y-m-d");
		$hora = date("H:i:s");
		
		$idMensaje = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as id from user_mailing where user_mailing_snr = '00000000-0000-0000-0000-000000000000'"))['id'];
		
		$qInsertaMsj = "insert into user_mailing 
			(
				USER_MAILING_SNR,
				USER_SNR,
				DATE,
				TIME,
				SUBJECT,
				MESSAGE,
				MAIL_TYPE,
				REC_STAT,
				SYNC,
				CREATION_TIMESTAMP
			) values (
				'".$idMensaje."',
				'".$idUser."',
				'".$fecha."',
				'".$hora."',
				'".$asunto."',
				'".$mensaje."',
				0,
				0,
				0,
				getdate()
			)";
				
		if(sqlsrv_query($conn, $qInsertaMsj)){
			for($i=0;$i<count($arrPara);$i++){
				$destinatario = $arrPara[$i];
				$qDestinatario = "insert into user_mailing_destination 
					(
						USER_MAILING_DESTINATION_SNR,
						USER_MAILING_SNR,
						USER_SNR,
						MESSAGE_READED,
						REC_STAT,
						SYNC
					) values (
						NEWID(),
						'".$idMensaje."',
						'".$destinatario."',
						0,
						0,
						0
					)";
				if(! sqlsrv_query($conn, $qDestinatario)){
					echo "no se guardo: ".$qDestinatario;
				}
			}
			echo "<script>
					notificationMensajeEniviado();
					$('#btnQuitarSeleccionMensaje').click();
					$('#btnEjecutarFiltroMensaje').click();
					$('#txtAsuntoMensaje').val('');
					$('#txtMensaje').val('');
					$('#divMensaje').hide();
					$('#divCapa3').hide();
				</script>";
		}
		
	}
	
?>