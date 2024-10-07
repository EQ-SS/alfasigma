<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		
		$idEvento = $_POST['idEvento'];
		
		$query = "select ep.EVENT_PERS_SNR,
			p.LNAME + ' ' + p.MOTHERS_LNAME + ' ' + p.FNAME as nombre,
			esp.name as especialidad, 
			tipoInst.name as tipoInst,
			cat.NAME as categoria ,
			catA.NAME as CategoriaA,
			ep.attended
			from EVENT_PERS ep
			inner join person p on p.pers_snr = ep.pers_snr
			left outer join CODELIST esp on esp.CLIST_SNR = p.SPEC_SNR
			inner join PERS_SREP_WORK psw on psw.PERS_SNR = p.PERS_SNR
			inner join inst i on i.inst_snr = psw.INST_SNR
			left outer join CODELIST tipoInst on tipoInst.CLIST_SNR = i.TYPE_SNR
			left outer join CODELIST cat on cat.CLIST_SNR = p.CATEGORY_SNR 
			inner join PERSON_UD pud on pud.PERS_SNR = p.PERS_SNR
			left outer join CODELIST catA on catA.CLIST_SNR =  pud.FIELD_13_SNR
			where p.REC_STAT = 0
			and psw.REC_STAT = 0
			and i.REC_STAT = 0 
			and ep.rec_stat = 0 
			and ep.EVENT_SNR = '".$idEvento."'";
		
		//echo $query;
		
		$rs = sqlsrv_query($conn, $query, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		echo "<script>
			$('#tblAsistentesEventos tbody').empty();
			$('#hdnAsistentes').empty();
			$('#hdnFaltantes').empty();";
		
		$liIdEvento = 1;
		$hdnAsistentes = "";
		$hdnFaltantes = "";
		while($invitado = sqlsrv_fetch_array($rs)){
			$nombre = $invitado['nombre'];
			$esp = $invitado['especialidad'];
			$tipo = $invitado['tipoInst'];
			$categoria = $invitado['categoria'];
			$categoriaA = $invitado['CategoriaA'];
			$idEvenPers = $invitado['EVENT_PERS_SNR'];
			
			if($invitado['attended'] == 1){
				$hdnAsistentes .= $idEvenPers.";";
				echo "$('#tblAsistentesEventos tbody').append('<tr><td style=\"width:30%;\">".$nombre."</td><td style=\"width:20%;\">".$esp."</td><td style=\"width:15%;\">".$tipo."</td><td style=\"width:12%;\">".$categoria."</td><td style=\"width:12%;\">".$categoriaA."</td></td><td align=\"center\" style=\"width:10%;\"><i id=\"liIdEvento".$liIdEvento."\" class=\"fa fa-calendar-check-o\" aria-hidden=\"true\" onClick=\"marcarAsistir(\'".$idEvento."\',\'".$idEvenPers."\',".$liIdEvento.");\"></i></td></tr>');";
			}else{
				$hdnFaltantes .= $idEvenPers.";";
				echo "$('#tblAsistentesEventos tbody').append('<tr><td style=\"width:30%;\">".$nombre."</td><td style=\"width:20%;\">".$esp."</td><td style=\"width:15%;\">".$tipo."</td><td style=\"width:12%;\">".$categoria."</td><td style=\"width:12%;\">".$categoriaA."</td><td align=\"center\" style=\"width:10%;\"><i id=\"liIdEvento".$liIdEvento."\" class=\"fa fa-calendar-times-o\" aria-hidden=\"true\" onClick=\"marcarAsistir(\'".$idEvento."\',\'".$idEvenPers."\',".$liIdEvento.");\"></i></td></tr>');";
			}
			$liIdEvento++;
		}
		echo "$('#hdnAsistentes').val('".$hdnAsistentes."');
			$('#hdnFaltantes').val('".$hdnFaltantes."');
			</script>";
	}
?>