<?php
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array(" ", " ", " ", " ");
	$reemplazar1=array("", "", "", "");
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		$idEncuesta = $_POST['idEncuesta'];
		$ids = $_POST['ids'];
		
		$queryRepres = "select * from users u
			left outer join COACHING_USERS su on su.USER_SNR = u.USER_SNR
			where u.user_snr in (
			select user_snr from COACHING_ANSWERED group by USER_SNR)
			and su.COACHING_SNR = '".$idEncuesta."' 
			and u.user_snr in ('".$ids."') 
			order by LNAME ";
		
		$rsRepres = sqlsrv_query($conn, $queryRepres);
		
		//echo $queryRepres;
		
		echo "<script>
			$('#tblEncuestasCalificadas').empty();
			";
				
		while($repre = sqlsrv_fetch_array($rsRepres)){
			echo "$('#sltRepreEncuesta').append('<option value=\"".$repre['user_snr']."\">".$repre['repre']."</option>');";
		}
		
		if($idEncuesta != ''){
			$query = "select sq.COACHING_QUESTIONS_SNR, sq.NAME, sq.MANDATORY, sag.TYPE 
				from COACHING s
				inner join COACHING_QUESTIONS sq on s.COACHING_SNR = sq.COACHING_SNR
				left outer join COACHING_ANSWER_GROUP sag on sag.COACHING_ANSWER_GROUP_SNR = sq.COACHING_ANSWER_GROUP_SNR
				where COACHING_QUESTIONS_SNR <> '00000000-0000-0000-0000-000000000000'
				and sq.REC_STAT = 0 
				and sq.COACHING_SNR = '".$idEncuesta."' 
				order by sq.SORT_NUM ";
			//echo $query;
			$rs = sqlsrv_query($conn, $query);
			$reg = 0;
			$oblitorias = "";
			while($encuesta = sqlsrv_fetch_array($rs)){
				$reg++;
				$idPregunta = $encuesta['COACHING_QUESTIONS_SNR'];
				$pregunta = utf8_encode($encuesta['NAME']);
				$obligatorio = $encuesta['MANDATORY'];
				$tipo = $encuesta['TYPE'];
				if($obligatorio){
					$oblitorias .= $reg.",";
					echo "$('#divPreguntasGerente').append('<label class=\"col-red\">".$reg." ".$pregunta." *</label><input class=\"form-control\" type=\"text\" id=\"pregunta".$reg."\">');";
				}else{
					echo "$('#divPreguntasGerente').append('<div><label>".$reg." ".$pregunta."</label><input class=\"form-control\" type=\"text\" id=\"pregunta".$reg."\"></div>');";
				}
			}
			
			echo "$('#hdnPreguntas').val('".$reg."');
				$('#hdnObligatorias').val('".substr($oblitorias, 0, -1)."');";
			
		}/*else{
			$query = "select LNAME + ' ' + MOTHERS_LNAME + ' ' + FNAME as nombre
				from USERS 
				where user_snr = '".$idUser."'";
			
			$repre = sqlsrv_fetch_array(sqlsrv_query($conn, $query))['nombre'];
			$tipoEvento = '00000000-0000-0000-0000-000000000000';
			$lugar = '';
			$nombreEvento = '';
			$fechaInicial = date("Y-m-d");
			$horaInicial = '00:00';
			$fechaFinal = date("Y-m-d");
			$horaFinal = '00:00';
			$tipoParticipacion = '00000000-0000-0000-0000-000000000000';
			$numeroParticipantes = 0;
			$especialidadEvento = '00000000-0000-0000-0000-000000000000';
			$grupoTerapeutico = '00000000-0000-0000-0000-000000000000';
			$comentarios = '';
		}
		
		echo "$('#hdnIdEventoEditar').val('".$idEvento."');
			$('#sltRepreEventoEditar').append('<option value=\"".$idUser."\" selected=\"selected\">".$repre."</option>');
			$('#lstTipoEventoEditar').val('".$tipoEvento."');
			$('#txtLugarEventoEditar').val('".$lugar."');
			$('#txtNombreEventoEditar').val('".$nombreEvento."');
			$('#txtFechaInicialEventoEditar').val('".$fechaInicial."');
			$('#lstHoraInicialEventoEditar').val('".substr($horaInicial, 0, 2)."');
			$('#lstMinutosInicialEventoEditar').val('".substr($horaInicial, 3, 2)."');
			$('#txtFechaFinalEventoEditar').val('".$fechaFinal."');
			$('#lstHoraFinalEventoEditar').val('".substr($horaFinal, 0, 2)."');
			$('#lstMinutosFinalEventoEditar').val('".substr($horaFinal, 3, 2)."');
			$('#lstTipoParticipacionEventoEditar').val('".$tipoParticipacion."');
			$('#txtNumeroParticipantesEventoEditar').val('".$numeroParticipantes."');
			$('#lstEspecialidadEventoEditar').val('".$especialidadEvento."');
			$('#lstGrupoTerapeuticoEventoEditar').val('".$grupoTerapeutico."');
			$('#txtComentariosEventoEditar').val('".$comentarios."');
			$('#hdnIvitadosEventos').val('');
			$('#btnGuardarEventoEditar').attr('disabled', false);
			</script>";
		*/
	}
?>