<?php
	include "../conexion.php";
	$buscar = array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar = array(" ", " ", " ", " ");
	$reemplazar1 = array("", "", "", "");
	$reemplazar2 = array("<br>", "<br>", "<br>", "<br>");
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		$idUsuario = $_POST['idUsuario'];
		$tipoUsuario = $_POST['tipoUsuario'];
		
		echo "<script>
				$('#tblEncuestas tbody').empty();";
				
		$queryTabla = "select COACHING_SNR, name,  
			cast(year(START_DATE) as varchar) + '-' + format(month(START_DATE), '00') + '-' + format(day(START_DATE), '00') as START_DATE, 
			cast(year(FINISH_DATE) as varchar) + '-' + format(month(FINISH_DATE), '00') + '-' + format(day(FINISH_DATE), '00') as FINISH_DATE 
			from COACHING
			where COACHING_SNR <> '00000000-0000-0000-0000-000000000000'
			and REC_STAT = 0 ";
		if($tipoUsuario == 4){
			$queryTabla .= "and COACHING_SNR in (select COACHING_SNR 
				from COACHING_ANSWERED 
				where USER_SNR = '".$idUsuario."' 
				group by COACHING_snr, USER_SNR) ";
		}
		$queryTabla .= "order by START_DATE desc";
		//echo $queryTabla."<br>";
		$rsTabla = sqlsrv_query($conn, $queryTabla, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		$trEncuestas = 0;
		while($registro = sqlsrv_fetch_array($rsTabla)){
			$id = $registro['COACHING_SNR'];
			$nombre = utf8_encode(str_replace("'", "\'", $registro['name']));					
			$fecIni = $registro['START_DATE'];										
			$fecFin = $registro['FINISH_DATE'];	
			
			if($tipoUsuario == 4){
				echo "$('#tblEncuestas tbody').append('<tr id=\"trEncuestas".$trEncuestas."\" class=\"align-center\" onClick=\"traeEncuestaCalificada(\'".$id."\',\'trEncuestas".$trEncuestas."\');\"><td style=\"width:50%;\">".$nombre."</td><td style=\"width:25%;\">".$fecIni."</td><td style=\"width:25%;\">".$fecFin."</td></tr>');\r\n";
			}else{
				echo "$('#tblEncuestas tbody').append('<tr id=\"trEncuestas".$trEncuestas."\" class=\"align-center\"><td style=\"width:40%;\" onClick=\"traeEncuestaCalificada(\'".$id."\',\'trEncuestas".$trEncuestas."\');\">".$nombre."</td><td style=\"width:25%;\" onClick=\"traeEncuestaCalificada(\'".$id."\',\'trEncuestas".$trEncuestas."\');\">".$fecIni."</td><td style=\"width:25%;\" onClick=\"traeEncuestaCalificada(\'".$id."\',\'trEncuestas".$trEncuestas."\');\">".$fecFin."</td><td style=\"width:10%;\"><button type=\'button\' class=\'btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button\'  onClick=\"traeEncuesta(\'".$id."\');\"><i class=\'material-icons pointer\' data-toggle=\'tooltip\' data-placement=\'left\' title=\'Calificar\'>playlist_add_check</i></button></td></tr>');\r\n";
			}
			
			$trEncuestas++;
		}
		
		echo "</script>";
	}
?>