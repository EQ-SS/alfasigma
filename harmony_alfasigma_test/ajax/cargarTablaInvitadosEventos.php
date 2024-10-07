<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$invitados = $_POST['invitados'];
		$invitados = substr($invitados, 0, -1);
		$invitados = str_replace(";", "','", $invitados);
		$idEvento = $_POST['idEvento'];
		$idPersona = $_POST['idPersona'];
		
		echo "<script>
					$('#tblInvitadosEditar tbody').empty();";
		
		if($idPersona){
			$qActualizaEventPers = "update event_pers 
				set REC_STAT = 2,
				CHANGED_TIMESTAMP = getdate(),
				sync = 0
				where pers_snr = '".$idPersona."' 
				and event_snr = '".$idEvento."'";
				
			if(! sqlsrv_query($conn, $qActualizaEventPers)){
				echo "actualiza event pers: ".$qActualizaEventPers."<br>";
			}
		}
		
		$qInvitadosEventPers = "select p.pers_snr, p.LNAME + ' ' +  p.MOTHERS_LNAME + ' ' + p.FNAME as nombre,
			e.NAME as especialidad, i.STREET1 as calle, 'EXT. ' + NUM_EXT as numExt,
			c.NAME as colonia, c.ZIP as cp, d.NAME as distrito, s.NAME as estado,
			case when categ.NAME = '' then 'NC' else categ.NAME end as categoria 
			from event_pers ep 
			inner join person p on p.pers_snr = ep.pers_snr 
			inner join CODELIST e on p.SPEC_SNR = e.CLIST_SNR
			inner join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR
			inner join inst i on i.INST_SNR = psw.INST_SNR
			inner join CITY c on c.CITY_SNR = i.CITY_SNR
			inner join DISTRICT d on d.DISTR_SNR = c.DISTR_SNR 
			inner join state s on s.STATE_SNR = c.STATE_SNR
			left outer join CODELIST categ on categ.CLIST_SNR = p.CATEGORY_SNR
			where event_snr = '".$idEvento."' 
			and ep.rec_stat = 0 
			and psw.rec_stat = 0 
			and p.rec_stat = 0 ";
			
		$rsInvitadosEventPers = sqlsrv_query($conn, $qInvitadosEventPers);
		
		while($invitadoEventPers = sqlsrv_fetch_array($rsInvitadosEventPers)){
			$idPersona = $invitadoEventPers['pers_snr'];
			$nombre = $invitadoEventPers['nombre'];
			$especialidad = $invitadoEventPers['especialidad'];
			$dir = $invitadoEventPers['calle'].' CP. '.$invitadoEventPers['cp'].' COL. '.$invitadoEventPers['colonia'].', '.$invitadoEventPers['distrito'].', '.$invitadoEventPers['estado'];
			$categoria = $invitadoEventPers['categoria'];
			echo "$('#tblInvitadosEditar tbody').append('<tr><td style=\"width:5%\"><button type=\'button\' class=\'btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button\' onClick=\'eliminarPersonaEvento(\"".$idPersona."\");\'><i class=\'material-icons pointer\' data-toggle=\'tooltip\' data-placement=\'left\' title=\'Eliminar\'>delete</i></button></td><td style=\"width:25%;\">".$nombre."</td><td style=\"width:15%;\">".$especialidad."</td><td style=\"width:45%;\">".$dir."</td><td style=\"width:10%;\" align=\"center\">".$categoria."</td></tr>');";
		}
		
		if($invitados){
			$qInvitados  = "select p.pers_snr, p.LNAME + ' ' +  p.MOTHERS_LNAME + ' ' + p.FNAME as nombre, 
				e.NAME as especialidad, i.STREET1 as calle, 'EXT. ' + NUM_EXT as numExt,
				c.NAME as colonia, c.ZIP as cp, d.NAME as distrito, s.NAME as estado,
				case when categ.NAME = '' then 'NC' else categ.NAME end as categoria
				from person p
				inner join CODELIST e on p.SPEC_SNR = e.CLIST_SNR
				inner join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR
				inner join inst i on i.INST_SNR = psw.INST_SNR
				inner join CITY c on c.CITY_SNR = i.CITY_SNR
				inner join DISTRICT d on d.DISTR_SNR = c.DISTR_SNR 
				inner join state s on s.STATE_SNR = c.STATE_SNR
				left outer join CODELIST categ on categ.CLIST_SNR = p.CATEGORY_SNR 
				where p.REC_STAT = 0
				and psw.REC_STAT = 0
				and i.REC_STAT = 0
				and p.pers_snr in ('".$invitados."') ";
				
			$rsInvitados = sqlsrv_query($conn, $qInvitados, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
			while($invitado = sqlsrv_fetch_array($rsInvitados)){
				$idPersona = $invitado['pers_snr'];
				$nombre = $invitado['nombre'];
				$especialidad = $invitado['especialidad'];
				$dir = $invitado['calle'].' CP. '.$invitado['cp'].' COL. '.$invitado['colonia'].', '.$invitado['distrito'].', '.$invitado['estado'];
				$categoria = $invitado['categoria'];
				echo "$('#tblInvitadosEditar tbody').append('<tr><td style=\"width:5%\"><button type=\'button\' class=\'btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button\' onClick=\'eliminarPersonaEvento(\"".$idPersona."\");\'><i class=\'material-icons pointer\' data-toggle=\'tooltip\' data-placement=\'left\' title=\'Eliminar\'>delete</i></button></td><td style=\"width:25%;\">".$nombre."</td><td style=\"width:15%;\">".$especialidad."</td><td style=\"width:45%;\">".$dir."</td><td style=\"width:10%;\" align=\"center\">".$categoria."</td></tr>');";
			}
		}
		echo "</script>";
	}
?>