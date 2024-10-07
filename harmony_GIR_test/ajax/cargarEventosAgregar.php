<?php
	include "../conexion.php";
	$buscar = array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar = array(" ", " ", " ", " ");
	$reemplazar1 = array("", "", "", "");
	$reemplazar2 = array("<br>", "<br>", "<br>", "<br>");
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		$idPersona = $_POST['idPersona'];
		$idUsuario = $_POST['idUsuario'];
		
		echo "<script>
				$('#tblEventosAgregar tbody').empty();";
		
		$query = "select e.event_snr, tipo.name as tipo, e.PLACE, e.name as nombre, 
			format(year(e.start_date), '0000') + '-' + format(month(start_date), '00') + '-' + format(day(e.start_date), '00') as START_DATE,
			format(year(e.FINISH_DATE), '0000') + '-' + format(month(FINISH_DATE), '00') + '-' + format(day(e.FINISH_DATE), '00') as FINISH_DATE,
			e.START_TIME, e.FINISH_TIME,  e.ATTENDED_QUANTITY,
			e.SPECIALTY_SNR, grupo.name, e.INFO
			from EVENT e
			left outer join CODELIST tipo on tipo.CLIST_SNR = e.TYPE_SNR		
			left outer join CODELIST part on part.CLIST_SNR = e.PART_TYPE_SNR 
			left outer join CODELIST grupo on grupo.CLIST_SNR = e.THGROUP_SNR	
			inner join event_user eu on eu.event_snr = e.event_snr 
			where e.EVENT_SNR <> '00000000-0000-0000-0000-000000000000'
			and e.event_snr not in (select event_snr from EVENT_PERS 
			where pers_snr = '".$idPersona."') 
			and eu.user_snr = '".$idUsuario."'
			order by e.start_date desc
			";
		//offset 450 rows fetch next 1 rows onlyecho $query;
			
		$rs = sqlsrv_query($conn, $query);
		$renglon = 0;
		while($evento = sqlsrv_fetch_array($rs)){
			$idEvento = $evento['event_snr'];
			$nombreEvento = utf8_encode(str_replace("'", "\'", $evento['nombre']));
			$tipoEvento = utf8_encode(str_replace("'", "\'", $evento['tipo']));
			$fechaInicial = $evento['START_DATE'];
			$horaInicial = $evento['START_TIME'];
			$fechaFinal = $evento['FINISH_DATE'];
			$horaFinal = $evento['FINISH_TIME'];
			$lugar = utf8_encode(str_replace("'", "\'", $evento['PLACE']));
			$comentarios = utf8_encode(str_ireplace($buscar,$reemplazar,str_replace("'", "\'", $evento['INFO'])));
			echo "$('#tblEventosAgregar tbody').append('<tr id=\"tr".$renglon."\" onclick=\"llenaIdEventoAgregar(\'".$idEvento."\',\'tr".$renglon."\');\"><td style=\"width:20%;\">".$tipoEvento."</td><td style=\"width:20%;\">".$nombreEvento."</td><td style=\"width:20%;\">".$lugar."</td><td style=\"width:10%;\">".$fechaInicial." ".$horaInicial."</td><td style=\"width:10%;\">".$fechaFinal." ".$horaFinal."</td><td style=\"width:20%;\">".$comentarios."</td></tr>');";
			$renglon++;
		}
		echo "</script>";
	}
?>