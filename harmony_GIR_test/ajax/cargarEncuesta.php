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
		$tipoUsuario = $_POST['tipoUsuario'];
		
		$queryRepres = "select u.user_snr, 
			u.LNAME + ' ' + u.MOTHERS_LNAME + ' ' + u.FNAME as repre 
			from users u
			inner join COACHING_USERS su on su.user_snr = u.user_snr 
			where u.REC_STAT = 0 
			and u.user_type = 4 
			and u.user_snr in ('".$ids."') 
			and su.rec_stat = 0 
			and su.COACHING_snr = '".$idEncuesta."' ";
		if($tipoUsuario != 4){
			$queryRepres .= "and u.user_snr not in (
			select distinct user_snr 
			from COACHING_ANSWERED 
			where COACHING_SNR = su.COACHING_SNR
			) ";
		}
			
		$queryRepres .= "order by LNAME ";
		$rsRepres = sqlsrv_query($conn, $queryRepres);
		
		//echo $queryRepres;
		
		echo "<script>
			$('#hdnIdEncuesta').val('".$idEncuesta."');
			$('#divPreguntasGerente').empty();
			$('#sltRepreEncuesta').empty();
			$('#sltRepreEncuesta').append('<option value=\"00000000-0000-0000-0000-000000000000\">Seleccione</option>');
			";
				
		while($repre = sqlsrv_fetch_array($rsRepres)){
			$idUsuario = $repre['user_snr'];
			echo "$('#sltRepreEncuesta').append('<option value=\"".$repre['user_snr']."\">".$repre['repre']."</option>');";
		}
		
		if($tipoUsuario == 4){
			echo "$('#sltRepreEncuesta').val('".$idUsuario."');";
		}
		
		if($idEncuesta != ''){
			$query = "select sq.COACHING_QUESTION_SNR, sq.NAME, sq.MANDATORY, sag.TYPE ";
			if($tipoUsuario == 4){
				$query .= ",sa.ANSWER_STRING ";
			}
			$query .= "from COACHING s
				inner join COACHING_QUESTIONS sq on s.COACHING_SNR = sq.COACHING_SNR 
				left outer join COACHING_ANSWER_GROUP sag on sag.COACHING_ANSWER_GROUP_SNR = sq.COACHING_ANSWER_GROUP_SNR ";
			if($tipoUsuario == 4){
				$query .= "left outer join COACHING_ANSWERED sa on sa.COACHING_QUESTION_SNR = sq.COACHING_QUESTION_SNR 
					and sa.COACHING_SNR = sq.COACHING_SNR ";
			}
			$query .= "where sq.COACHING_QUESTION_SNR <> '00000000-0000-0000-0000-000000000000'
				and sq.REC_STAT = 0 
				and sq.COACHING_SNR = '".$idEncuesta."' ";
			if($tipoUsuario == 4){
				$query .= "and sa.USER_SNR = '".$idUsuario."' ";
			}
			$query .= " order by sq.SORT_NUM ";
			//echo $query;
			$rs = sqlsrv_query($conn, $query);
			$reg = 0;
			$oblitorias = "";
			while($encuesta = sqlsrv_fetch_array($rs)){
				$reg++;
				$idPregunta = $encuesta['COACHING_QUESTION_SNR'];
				$pregunta = $encuesta['NAME'];
				$obligatorio = $encuesta['MANDATORY'];
				$tipo = $encuesta['TYPE'];
				if($tipoUsuario == 4){
					$respuesta = $encuesta['ANSWER_STRING'];
				}
				if($obligatorio){
					$oblitorias .= $reg.",";
					if($tipoUsuario == 4){
						echo "$('#divPreguntasGerente').append('<label class=\"col-red\">".$reg." .- ".$pregunta." *</label><input class=\"form-control\" type=\"text\" id=\"pregunta".$reg."\" value=\"".$respuesta."\" disabled>');";
					}else{
						echo "$('#divPreguntasGerente').append('<label class=\"col-red\">".$reg." .- ".$pregunta." *</label><input class=\"form-control\" type=\"text\" id=\"pregunta".$reg."\">');";
					}
				}else{
					if($tipoUsuario == 4){
						echo "$('#divPreguntasGerente').append('<label>".$reg." .- ".$pregunta."</label><input class=\"form-control\" type=\"text\" id=\"pregunta".$reg."\" value=\"".$respuesta."\" disabled>');";
					}else{
						echo "$('#divPreguntasGerente').append('<label>".$reg." .- ".$pregunta."</label><input class=\"form-control\" type=\"text\" id=\"pregunta".$reg."\">');";
					}
				}
			}
			
			if($tipoUsuario == 4){
				$rsReplica = sqlsrv_query($conn, "select replied from COACHING_USERS where COACHING_SNR = '".$idEncuesta."' and USER_SNR = '".$ids."' ");
				$replica = '';
				while($regReplica = sqlsrv_fetch_array($rsReplica)){
					$replica = utf8_encode($regReplica['replied']);
				}
				echo "$('#divPreguntasGerente').append('<label class=\"col-red\">Réplica *</label><input class=\"form-control\" type=\"text\" id=\"txtReplica\" value=\"".$replica."\">');";
			}
			
			echo "$('#hdnPreguntas').val('".$reg."');
				$('#hdnObligatorias').val('".substr($oblitorias, 0, -1)."');";
		}
	}
?>