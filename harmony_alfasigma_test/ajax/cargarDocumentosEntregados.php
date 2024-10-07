<?php
	include "../conexion.php";

	function human_filesize($bytes, $decimals = 2) {
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$sz[$factor];
	  }

	$repre = $_POST['repre'];
	$ids = $_POST['ids'];
	$idUsuario = $_POST['idUsuario'];
	//echo "esto idu".$idUsuario;
	//echo "esto repre".$repre."<br>";
	//echo "esto ids".$ids;
	if($repre != ''){

		$ids = str_replace(",","','",substr($repre, 0, -1));
	}
	echo "<script>
		$('#tblSubirDocumentosDatosPersonales tbody').empty();";
		
	/*$queryTabla = "select * from PERSON_PRIV_NOTE where user_snr in ('".$ids."') and rec_stat = 0";
	//echo $queryTabla;
	$rsTabla = sqlsrv_query($conn, $queryTabla);
	$reg = 0;
	while($registro = sqlsrv_fetch_array($rsTabla)){
		foreach ($registro['DATE'] as $key => $val) {
			if(strtolower($key) == 'date'){
				$fecha = substr($val, 0, 10);
			}*/
			
	$queryTabla = "select * from PERSON_PRIV_NOTE where user_snr in ('".$ids."') and rec_stat = 0 order by DATE";
	//echo $queryTabla;
	$rsTabla = sqlsrv_query($conn, $queryTabla, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	while($registro = sqlsrv_fetch_array($rsTabla)){
		foreach ($registro['DATE'] ? $registro['DATE'] : [] as $key => $val) {
			if(strtolower($key) == 'date'){
				$fecha = substr($val, 0, 10);
			}
		}
		if(file_exists($registro['PATH'])){
			$nombreArchivo = explode("/",$registro['PATH'])[2];
		//echo "$('#tblSubirDocumentosDatosPersonales tbody').append('<tr><td width=\"200px\">".$fecha."</td><td width=\"400px\">".$registro['PATH']."</td><td width=\"400px\">".$registro['INFO']."</td><td width=\"150px\" align=\"center\"><a href=\"archivos".$registro['PATH']."\" target=\"_blank\"><img src=\"iconos/download24.png\" title=\"Descargar\" /></a></td><td width=\"150px\" align=\"center\"><img onClick=\"eliminarArchivo(\'".$registro['PERSPRNOTE_SNR']."\');\" src=\"iconos/deleteFile.png\" width=\"20px\" title=\"eliminar\" /></td></tr>');";
		
		echo "$('#tblSubirDocumentosDatosPersonales tbody').append('<tr class=\"align-center\"><td style=\"width:15%;\" class=\"align-left\">".$fecha."</td><td style=\"width:25%;\">".$registro['PATH']."</td><td style=\"width:25%;\">".$registro['INFO']."</td><td style=\"width:15%;\">".human_filesize(filesize($registro['PATH']))."B</td><td style=\"width:10%;\"><a href=\"archivos".$registro['PATH']."\" target=\"_blank\"><i class=\"fas fa-file-download\"></i></a></td><td style=\"width:10%;\"><a onClick=\"eliminarArchivo(\'".$registro['PERSPRNOTE_SNR']."\');\" class=\"pointer\"><i class=\"fas fa-trash-alt\"></i></a></td></tr>');";
		}

		//$reg .= "$('#tblSubirDocumentosDatosPersonales tbody').append('<tr><td width=\"200px\">".$fecha."</td><td width=\"400px\">".$registro['PATH']."</td><td width=\"400px\">".$registro['INFO']."</td><td width=\"150px\" align=\"center\"><a href=\"archivos".$registro['PATH']."\" target=\"_blank\"><img src=\"iconos/download24.png\" title=\"Descargar\" /></a></td><td width=\"150px\" align=\"center\"><img onClick=\"eliminarArchivo('".$registro['PERSPRNOTE_SNR']."');\"  src=\"iconos/deleteFile.png\" width=\"20px\" title=\"eliminar\" /></td></tr>');";
	}
	echo "</script>";
	//echo $reg;
?>