<?php
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array("<br>", "<br>", "<br>", "<br>");
	$reemplazar1=array("", "", "", "");
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		$idMensaje = $_POST['idMensaje'];
		$tabMsj = $_POST['tabMsj'];
		
		if($idMensaje != ''){
			$query = "select  
				u.USER_NR + ' - ' + u.LNAME + ' ' +u.MOTHERS_LNAME + ' ' + u.FNAME as remitente,
				ud.USER_NR + ' - ' + ud.LNAME + ' ' +ud.MOTHERS_LNAME + ' ' + ud.FNAME as destinatario,
				cast(year(um.DATE) as varchar) + '-' + format(month(um.date), '00') + '-' + format(day(um.date), '00') fecha,
				um.time as hora,
				um.SUBJECT as asunto,
				um.MESSAGE as mensaje,
				umd.MESSAGE_READED as leido
				from USER_MAILING um
				inner join USER_MAILING_DESTINATION umd on umd.USER_MAILING_SNR = um.USER_MAILING_SNR
				inner join users u on um.user_snr = u.user_snr 
				inner join users ud on umd.USER_SNR = ud.USER_SNR
				where umd.USER_MAILING_DESTINATION_SNR = '".$idMensaje."'";
			//echo $query;
			$arrMensaje = sqlsrv_fetch_array(sqlsrv_query($conn, $query));
			
			//print_r($mensaje);
			
			$remitente = $arrMensaje['remitente'];
			$destinatario = $arrMensaje['destinatario'];
			$asunto = $arrMensaje['asunto'];
			$mensaje = str_ireplace($buscar,$reemplazar,$arrMensaje['mensaje']);
			$leido = $arrMensaje['leido'];
			//echo "<br>leido: ".$leido;
			if($tabMsj != 3 &&  $leido == 0){
				$qLeido = "update USER_MAILING_DESTINATION set 
					MESSAGE_READED = 1,
					SYNC = 0,
					read_timestamp = getdate() 
					where USER_MAILING_DESTINATION_SNR = '".$idMensaje."'";
					
				if(! sqlsrv_query($conn, $qLeido)){
					echo "<script>alert('No se pudo actualizar!!!');</script>";
				}
				//echo $qLeido;
			}
		}
		
		echo "<script>
			$('#lblRemitente').text('".$remitente."');
			$('#lblDestinatario').text('".$destinatario."');
			$('#lblAsunto').text('".$asunto."');
			$('#lblMensaje').html('".$mensaje."');
			</script>";
	}
?>