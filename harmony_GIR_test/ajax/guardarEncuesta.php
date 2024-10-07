<?php
	include "../conexion.php";

	if(isset($_POST['repre']) && $_POST['repre'] != ''){
		$repre = $_POST['repre'];
	}else{
		$repre = '';
	}

	if(isset($_POST['idEncuesta']) && $_POST['idEncuesta'] != ''){
		$idEncuesta = $_POST['idEncuesta'];
	}else{
		$idEncuesta = '';
	}

	if(isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] != ''){
		$tipoUsuario = $_POST['tipoUsuario'];
	}else{
		$tipoUsuario = '';
	}

	if(isset($_POST['respuestasSlt']) && $_POST['respuestasSlt'] != ''){
		$respuestasSlt = $_POST['respuestasSlt'];
		$respuestasSlt = explode(",",$respuestasSlt);
	}else{
		$respuestasSlt = '';
	}


	if(isset($_POST['respuestasTxt']) && $_POST['respuestasTxt'] != ''){
		$respuestasTxt = $_POST['respuestasTxt'];
		
		$respuestasTxt = explode("|",$respuestasTxt);

		
	}else{
		$respuestasTxt = '';
	}

	if(isset($_POST['EncuestaNueva']) && $_POST['EncuestaNueva'] != ''){
		$EncuestaNueva = $_POST['EncuestaNueva'];
	}else{
		$EncuestaNueva = '';
	}

	$closed="";
	if(isset($_POST['finCoaching']) && $_POST['finCoaching'] != ''){
		$finCoaching = $_POST['finCoaching'];
		$closed=$finCoaching;
	}else{
		$finCoaching = '';
	}

	if(isset($_POST['idCoachingUser']) && $_POST['idCoachingUser'] != ''){
		$idCoachingUser = $_POST['idCoachingUser'];
	}else{
		$idCoachingUser = '';
	}

	if(isset($_POST['replica']) && $_POST['replica'] != ''){
		$replica = $_POST['replica'];
	}else{
		$replica = '';
	}

	if(isset($_POST['replica2']) && $_POST['replica2'] != ''){
		$replica2 = $_POST['replica2'];
	}else{
		$replica2 = '';
	}

	if(isset($_POST['replica3']) && $_POST['replica3'] != ''){
		$replica3 = $_POST['replica3'];
	}else{
		$replica3 = '';
	}

	if(isset($_POST['idUsuario']) && $_POST['idUsuario'] != ''){
		$idUsuario = $_POST['idUsuario'];
	}else{
		$idUsuario = '';
	}

	if(isset($_POST['num_Preguntas']) && $_POST['num_Preguntas'] != ''){
		$num_Preguntas = $_POST['num_Preguntas'];
	}else{
		$num_Preguntas = '';
	}

	$arrDiaDeCiclo = sqlsrv_fetch_array(sqlsrv_query($conn," SELECT CYCLE_SNR,NAME FROM CYCLES WHERE  '".date("Y-m-d")."' BETWEEN START_DATE AND FINISH_DATE "));
	$ciclo = $arrDiaDeCiclo['CYCLE_SNR'];
	//$ciclo="971ED001-B4DF-4C84-B1D7-8F59A0E177EB";

	$queryIdsPreguntasEncuesta = "select sq.COACHING_QUESTION_SNR 
	from COACHING s
	inner join COACHING_QUESTIONS sq on s.COACHING_SNR = sq.COACHING_SNR
	where COACHING_QUESTION_SNR <> '00000000-0000-0000-0000-000000000000'
	and sq.REC_STAT = 0 
	and sq.COACHING_SNR = '".$idEncuesta."' 
	order by sq.SORT_NUM ";

	//echo $queryEncuesta;

	$rsPreguntas = sqlsrv_query($conn, $queryIdsPreguntasEncuesta);

	// si es admin o gerente
	if($tipoUsuario!=4){

		if($idCoachingUser==""){

			$queryCoachingUsers="INSERT INTO COACHING_USERS(COACHING_USER_SNR,COACHING_SNR,USER_SNR,REPLIED,REC_STAT,SYNC,CLOSED,CREATION_TIMESTAMP)
			VALUES(NEWID(),'".$idEncuesta."','".$repre."','',0,0,'".$closed."',GETDATE())";
	
			if(! sqlsrv_query($conn, $queryCoachingUsers)){
				echo "no se inserto cu ".$queryCoachingUsers;
			}
	
			$qIDLevantamientoU="SELECT TOP(1) COACHING_USER_SNR,
			REPLIED,
			CREATION_TIMESTAMP 
			FROM COACHING_USERS 
			WHERE REPLIED IS NOT NULL 
			ORDER BY CREATION_TIMESTAMP DESC";
	
			$rsLevantamiento=sqlsrv_query($conn, $qIDLevantamientoU);
						
			while($idlevrs = sqlsrv_fetch_array($rsLevantamiento)){
				$idlev=$idlevrs['COACHING_USER_SNR'];
			}
	
		}else{

			$queryValidaPreguntasCiclo="
			SELECT * FROM COACHING_ANSWERED
			WHERE COACHING_USER_SNR='".$idCoachingUser."'
			AND CYCLE_SNR='".$ciclo."'
			AND REC_STAT=0";
	
			$params = array();
			$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			$rsPvP = sqlsrv_query( $conn, $queryValidaPreguntasCiclo , $params, $options );
	
			$row_countVp = sqlsrv_num_rows( $rsPvP );
	
	
			//variable de preguntas * 3
			if($row_countVp >0 ){
				echo "<script>alert('ya Contestaste en este Ciclo');</script>";
				return true;
			}

	
			$idlev=$idCoachingUser;
		}

		$indice=1;
		while($pregunta = sqlsrv_fetch_array($rsPreguntas)){
			$idPregunta = $pregunta['COACHING_QUESTION_SNR'];
			$qInsertaRespuesta = "insert into COACHING_ANSWERED (
				COACHING_ANSWERED_SNR,
				USER_SNR,
				DATE,
				ANSWER_DATE,
				COACHING_SNR,
				COACHING_QUESTION_SNR,
				REC_STAT,
				ANSWER_STRING,
				ANSWER_SNR,
				SYNC,
				TIME,
				COACHING_USER_SNR,
				CYCLE_SNR
			) VALUES (
				NEWID(),
				'".$repre."',
				getdate(),
				getdate(),
				'".$idEncuesta."',
				'".$idPregunta."',
				0,
				'".utf8_decode($respuestasTxt[$indice])."',
				'".$respuestasSlt[$indice]."',
				0,
				'".date("H:i")."',
				'".$idlev."',
				'".$ciclo."'
			)";

			if(! sqlsrv_query($conn, $qInsertaRespuesta)){
				echo "no inserta respuesta ".$indice." ".$qInsertaRespuesta;
			}
			$indice++;
		}


		//envia mensaje-----------------------------------
		//include "../../Funciones/Funcion_HorarioVerano/horario_Verano.php";
		$idUser = $idUsuario;
		$idsRepresentantes=$repre;
		$arrPara = explode(",", $idsRepresentantes);
		$asunto = ' Coaching';
		$fecha = date("Y-m-d");
		$hora = date("h-m-s");
		$asunto.=$fecha;
		$mensaje="Tiene un Coaching Pendiente.";
		//return true;
		
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
				//notificationMensajeEniviado();
				$('#txtAsuntoMensaje').val('');
				$('#txtMensaje').val('');
				$('#divMensaje').hide();
				$('#divCapa3').hide();
			</script>";
		}else{
			echo "error en el query :".$qInsertaMsj;
		}
		// end envia mensaje-----------------------------------


		


	}else{
		//si es repre

		$arrReplicaEnCurso = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT TOP 1 COACHING_ANSWERED_SNR,
		ANSWER_SNR,ANSWER_STRING 
		FROM COACHING_ANSWERED CA 
		INNER JOIN COACHING_QUESTIONS CQ ON CQ.COACHING_QUESTION_SNR=CA.COACHING_QUESTION_SNR 
		INNER JOIN CYCLES CY ON CY.CYCLE_SNR=CA.CYCLE_SNR 
		WHERE CA.COACHING_USER_SNR='".$idCoachingUser."' 
		AND CA.COACHING_SNR='".$idEncuesta."' 
		AND CY.CYCLE_SNR='".$ciclo."'
		AND CQ.REC_STAT=0
		AND CA.REC_STAT=0
		AND CY.REC_STAT=0 
		ORDER BY CY.NAME,CQ.SORT_NUM DESC"));
		$idReplica = $arrReplicaEnCurso['COACHING_ANSWERED_SNR'];
		$replicaString = $arrReplicaEnCurso['ANSWER_STRING'];

		if($replicaString!=""){
			echo "<script>alert('ya tiene replica');</script>";
			return true;
		}

		$queryIngresaReplica="UPDATE COACHING_ANSWERED 
		SET ANSWER_STRING='".$respuestasTxt[$num_Preguntas]."' ,ANSWER_DATE=GETDATE()
		WHERE COACHING_ANSWERED_SNR='".$idReplica."' ";


		
		if(! sqlsrv_query($conn, $queryIngresaReplica)){
			echo "no inserta Replica :".$queryIngresaReplica;
		}

	}

	echo "<script>$('#modal_Coaching').modal('hide');</script>";
	
?>