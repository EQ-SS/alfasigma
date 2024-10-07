<?php
	include "../conexion.php";
	
	//echo "pantalla".$_POST['pantalla'];
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idEvento = $_POST['idEvento'];
		$idUsuario = $_POST['idUsuario'];
		$idPersona = $_POST['idPersona'];
		
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
			'".$idUsuario."',
			'".$idPersona."',
			0,
			0,
			0,
			getdate()
			)";
				
		if(! sqlsrv_query($conn, $qInsertaInvitado)){
			echo "inserta invitado: ".$qInsertaInvitado."<br>";
		}
		
		echo "<script>";
		
		$qTotalEventos = "select cast(c.NUMBER as int)  as numero_ciclo, 
			count(c.NUMBER) as total
			from EVENT_PERS ep
			inner join EVENT e on e.EVENT_SNR = ep.EVENT_SNR
			inner join cycles c on e.START_DATE between c.START_DATE and c.FINISH_DATE
			where ep.PERS_SNR = '".$idPersona."'
			and ep.REC_STAT = 0
			and e.REC_STAT = 0
			group by c.NUMBER ";
		
		$rsTotalEventos = sqlsrv_query($conn, $qTotalEventos);
		
		for($i=1;$i<14;$i++){
			echo "$('#ciclo".$i."Evento').text('0');";
		}
		$totaEventos = 0;
		while($totalEvento = sqlsrv_fetch_array($rsTotalEventos)){
			$totaEventos += $totalEvento['total'];
			echo "$('#ciclo".$totalEvento['numero_ciclo']."Evento').text('".$totalEvento['total']."');";
		}
		
		echo "$('#acumuladoEvento').text('".$totaEventos."');";
		
		$qEventos = "select u.user_nr as ruta, c.name as ciclo, e.name, 
			format(year(e.start_date), '0000') + '-' + format(month(e.start_date), '00') + '-' + format(day(e.start_date), '00') + ' ' + e.START_TIME as START_DATE,
			format(year(e.FINISH_DATE), '0000') + '-' + format(month(e.FINISH_DATE), '00') + '-' + format(day(e.FINISH_DATE), '00') + ' ' + e.FINISH_TIME as FINISH_DATE,
			tipo.name as tipo, par.name as participacion, e.info as comentarios
			from EVENT_PERS ep
			inner join EVENT e on e.EVENT_SNR = ep.EVENT_SNR
			inner join users u on u.USER_SNR = e.USER_SNR
			inner join cycles c on e.START_DATE between c.START_DATE and c.FINISH_DATE
			left outer join CODELIST tipo on tipo.CLIST_SNR = e.TYPE_SNR
			left outer join CODELIST par on par.CLIST_SNR = e.PART_TYPE_SNR
			where ep.PERS_SNR = '".$idPersona."'
			and ep.REC_STAT = 0
			and e.REC_STAT = 0 ";
			
		$rsEventos = sqlsrv_query($conn, $qEventos);
		
		echo "$('#tblEventoPerfil tbody').empty();";
		
		while($evento = sqlsrv_fetch_array($rsEventos)){
			$ruta = $evento['ruta'];
			$ciclo = $evento['ciclo'];
			$nombre = utf8_encode($evento['name']);
			$fechaI = $evento['START_DATE'];
			$fechaF = $evento['FINISH_DATE'];
			$tipo = utf8_encode($evento['tipo']);
			$participacion = utf8_encode($evento['participacion']);
			$comentarios = utf8_encode($evento['comentarios']);
			echo "$('#tblEventoPerfil').append('<tr><td style=\"width:10%;\">".$ciclo."</td><td style=\"width:10%;\">".$ruta."</td><td style=\"width:15%;\">".$fechaI."</td><td style=\"width:15%;\">".$fechaF."</td><td style=\"width:20%;\">".$nombre."</td><td style=\"width:20%;\">".$tipo."</td><td style=\"width:20%;\">".$participacion."</td></tr>');";
		}
		echo "$('#divEventosAgregar').hide();
			$('#divCapa3').hide();
			</script>";
	}
?>