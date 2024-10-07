<?php
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array(" ", " ", " ", " ");
	$reemplazar1=array("", "", "", "");
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		$idEvento = $_POST['idEvento'];
		$idUser = $_POST['idUsuario'];
		
		echo "<script>
				$('#tblInvitadosEditar tbody').empty();";
		
		if($idEvento != ''){
			$query = "select p.PERS_SNR, u.LNAME + ' ' + u.MOTHERS_LNAME + ' ' + u.FNAME as repre,
				e.TYPE_SNR, e.PLACE, e.name, e.START_TIME,
				format(year(e.start_date), '0000') + '-' + format(month(start_date), '00') + '-' + format(day(e.start_date), '00') as START_DATE,
				format(year(e.FINISH_DATE), '0000') + '-' + format(month(FINISH_DATE), '00') + '-' + format(day(e.FINISH_DATE), '00') as FINISH_DATE,
				e.FINISH_TIME, e.PART_TYPE_SNR, e.ATTENDED_QUANTITY,
				e.SPECIALTY_SNR, e.THGROUP_SNR, e.INFO, 
				p.LNAME + ' ' + p.MOTHERS_LNAME + ' ' + p.FNAME as nombre,
				esp.NAME as especialidad, i.STREET1 + ' ' + i.NUM_EXT as calle,
				c.NAME as colonia, c.ZIP as cp,
				d.name as distrito, est.name as estado,
				cat.name as categoria
				from EVENT e
				left outer join EVENT_PERS ep on ep.EVENT_SNR = e.EVENT_SNR
				left outer join users u on u.USER_SNR = e.USER_SNR
				left outer join PERSON p on p.PERS_SNR = ep.PERS_SNR
				left outer join CODELIST esp on esp.CLIST_SNR = p.SPEC_SNR
				left outer join PERS_SREP_WORK psw on psw.PERS_SNR = p.PERS_SNR
				left outer join inst i on i.INST_SNR = psw.INST_SNR
				left outer join city c on c.CITY_SNR = i.CITY_SNR
				left outer join DISTRICT d on d.DISTR_SNR = c.DISTR_SNR
				left outer join STATE est on est.STATE_SNR = c.STATE_SNR
				left outer join CODELIST cat on cat.CLIST_SNR = p.CATEGORY_SNR
				where e.EVENT_SNR <> '00000000-0000-0000-0000-000000000000'
				and psw.rec_stat = 0
				and ep.rec_stat = 0
				and e.event_snr = '".$idEvento."' ";
			//echo $query;
			$rs = sqlsrv_query($conn, $query);
			$reg = 0;
			while($evento = sqlsrv_fetch_array($rs)){
				if($reg == 0){
					$repre = utf8_encode($evento['repre']);
					$tipoEvento = $evento['TYPE_SNR'];
					$lugar = utf8_encode($evento['PLACE']);
					$nombreEvento = utf8_encode($evento['name']);
					$fechaInicial = $evento['START_DATE'];
					$horaInicial = $evento['START_TIME'];
					$fechaFinal = $evento['FINISH_DATE'];
					$horaFinal = $evento['FINISH_TIME'];
					$tipoParticipacion = $evento['PART_TYPE_SNR'];
					$numeroParticipantes = $evento['ATTENDED_QUANTITY'];
					$especialidadEvento = utf8_encode($evento['SPECIALTY_SNR']);
					$grupoTerapeutico = $evento['THGROUP_SNR'];
					$comentarios = $evento['INFO'];
				}
				$nombre = utf8_encode($evento['nombre']);
				$especialidad = utf8_encode($evento['especialidad']);
				$dir = utf8_encode($evento['calle'].' CP. '.$evento['cp'].' COL. '.$evento['colonia'].', '.$evento['distrito'].', '.$evento['estado']);
				$categoria = utf8_encode($evento['categoria']);
				$idPersona = $evento['PERS_SNR'];
				echo "$('#tblInvitadosEditar tbody').append('<tr><td style=\"width:5%;\"><button type=\'button\' class=\'btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button\' onClick=\'eliminarPersonaEvento(\"".$idPersona."\");\'><i class=\'material-icons pointer\' data-toggle=\'tooltip\' data-placement=\'left\' title=\'Eliminar\'>delete</i></button></td><td style=\"width:25%;\">".$nombre."</td><td style=\"width:15%;\">".$especialidad."</td><td style=\"width:45%;\">".$dir."</td><td style=\"width:10%;\" align=\"center\">".$categoria."</td></tr>');";
				$reg++;
			}
		}else{
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
	}
?>