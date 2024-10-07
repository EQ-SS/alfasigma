<?php
	include "../conexion.php";
	
	//echo "pantalla".$_POST['pantalla'];
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idEvento = $_POST['idEvento'];
		$idUsuarioEvento = $_POST['idUsuarioEvento'];
		$tipoEvento = $_POST['tipoEvento'];
		$lugarEvento = strtoupper($_POST['lugarEvento']);
		$nombreEvento = strtoupper($_POST['nombreEvento']);
		$fechaInicial = $_POST['fechaInicial'];
		$horaInicial = $_POST['horaInicial'];
		$fechaFinal = $_POST['fechaFinal'];
		$horaFinal = $_POST['horaFinal'];
		$tipoParticipacion = $_POST['tipoParticipacion'];
		$numeroParticipantes = $_POST['numeroParticipantes'];
		$especialidadEvento = $_POST['especialidadEvento'];
		$grupoTerapeutico = $_POST['grupoTerapeutico'];
		$comentarios = strtoupper($_POST['comentarios']);
		$idUser = $_POST['idUser'];
		$invitados = substr($_POST['invitados'], 0, -1);
		$invitados = explode(";", $invitados);
	
		if($idEvento == ''){
			$idEvento = sqlsrv_fetch_array(sqlsrv_query($conn, "select newid() as id from EVENT where EVENT_SNR = '00000000-0000-0000-0000-000000000000'"))['id'];
			/////guardar evento
			$qInsertEvento = "insert into EVENT (
			EVENT_SNR,
			TYPE_SNR,
			PART_TYPE_SNR,
			NAME,
			PLACE,
			ATTENDED_QUANTITY,
			INFO,
			START_DATE,
			START_TIME,
			FINISH_DATE,
			FINISH_TIME,
			USER_SNR,
			SPECIALTY_SNR,
			THGROUP_SNR,
			USER_CREATOR_SNR,
			REC_STAT,
			SYNC,
			CREATION_TIMESTAMP
			) values (
			'".$idEvento."',
			'".$tipoEvento."',
			'".$tipoParticipacion."',
			'".$nombreEvento."',
			'".$lugarEvento."',
			'".$numeroParticipantes."',
			'".$comentarios."',
			'".$fechaInicial."',
			'".$horaInicial."',
			'".$fechaFinal."',
			'".$horaFinal."',
			'".$idUsuarioEvento."',
			'".$especialidadEvento."',
			'".$grupoTerapeutico."',
			'".$idUser."',
			0,
			0,
			getdate()
			)";
			
			if(! sqlsrv_query($conn, $qInsertEvento)){
				echo "inserta Evento: ".$qInsertEvento."<br>";
			}
			
			////guardar event user
			
			$qInsertaUserEvent = "insert into event_user (
				EVENT_USER_SNR,
				USER_SNR,
				EVENT_SNR,
				REC_STAT,
				SYNC,
				CREATION_TIMESTAMP
				) values (
				NEWID(),
				'".$idUser."',
				'".$idEvento."',
				0,
				0,
				getdate()
				)";
				
			if(! sqlsrv_query($conn, $qInsertaUserEvent)){
				echo "insert user event: ".$qInsertaUserEvent."<br>";
			}
		}else{
			$qUpdateEvento = "update EVENT set 
			TYPE_SNR = '".$tipoEvento."',
			PART_TYPE_SNR = '".$tipoParticipacion."',
			NAME = '".$nombreEvento."',
			PLACE = '".$lugarEvento."',
			ATTENDED_QUANTITY = '".$numeroParticipantes."',
			INFO = '".$comentarios."',
			START_DATE = '".$fechaInicial."',
			START_TIME = '".$horaInicial."',
			FINISH_DATE = '".$fechaFinal."',
			FINISH_TIME = '".$horaFinal."',
			SPECIALTY_SNR = '".$especialidadEvento."',
			THGROUP_SNR = '".$grupoTerapeutico."',
			CHANGED_TIMESTAMP = getdate()
			WHERE EVENT_SNR = '".$idEvento."'";
			
			if(! sqlsrv_query($conn, $qUpdateEvento)){
				echo "actualiza Evento: ".$qUpdateEvento."<br>";
			}
		}
		
		/////inserta invitados
		for($i=0; $i<count($invitados); $i++ ){
			$qInsertaInvitado = "insert into event_pers (
				EVENT_PERS_SNR,
				EVENT_SNR,
				USER_SNR,
				PERS_SNR,
				ATTENDED,
				REC_STAT,
				SYNC,
				CREATION_TIMESTAMP
				) values (
				NEWID(),
				'".$idEvento."',
				'".$idUser."',
				'".$invitados[$i]."',
				0,
				0,
				0,
				getdate()
				)";
				
			if(! sqlsrv_query($conn, $qInsertaInvitado)){
				echo "inserta invitado: ".$qInsertaInvitado."<br>";
			}
		}
		
		echo "<script>
				$('#btnGuardarEventoEditar').attr('disabled', true);
				$('#divEventoEditar').hide();
				$('#divCapa3').hide();
				$('#imgEventos').click();
			</script>";
	}
?>