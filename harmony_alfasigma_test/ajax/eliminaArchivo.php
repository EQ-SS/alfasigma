<?php
	include "../conexion.php";
	
	function human_filesize($bytes, $decimals = 2) {
	  $sz = 'BKMGTP';
	  $factor = floor((strlen($bytes) - 1) / 3);
	  return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$idArchivo = $_POST['idArchivo'];
		
		$arrDatosArchivo = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from PERSON_PRIV_NOTE where persprnote_snr = '".$idArchivo."'"));
		
		$rutaArchivo = $arrDatosArchivo['PATH'];
		$idUsuario = $arrDatosArchivo['USER_SNR'];

		echo $rutaArchivo;
		
		if(! unlink ( '../'.$rutaArchivo )){
			echo "no se ha podido eliminar el archivo ";
		}
		
		$query = "update PERSON_PRIV_NOTE set rec_stat = 2 where persprnote_snr = '".$idArchivo."'";
		if(! sqlsrv_query($conn, $query)){
			echo $query;
		}
		
		$queryTabla = "select * from PERSON_PRIV_NOTE where user_snr = '".$idUsuario."' and rec_stat = 0";
		//echo $queryTabla."<br>";
		$rsTabla = sqlsrv_query($conn, $queryTabla, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		//echo $totalArchivos = sqlsrv_num_rows($rsTabla);
		
		echo '<script>
			$("#tblSubirDocumentosDatosPersonales tbody").empty();
			$("#tblSubirDocumentosDatosPersonales tfoot").empty();';
		$totalArchivos = 0;
		while($registro = sqlsrv_fetch_array($rsTabla)){
			foreach ($registro['DATE'] ? $registro['DATE'] : [] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha = substr($val, 0, 10);
				}
			}
			//echo $registro['PATH']."<br>";
			if(file_exists("../".$registro['PATH'])){
			//	$nombreArchivo = explode("/",$registro['PATH'])[2];
			//if(file_exists($registro['PATH'])){
				$nombreArchivo = explode("/",$registro['PATH'])[2];
				$tam = human_filesize(filesize("../".$registro['PATH']))."B";
				//echo "$('#tblSubirDocumentosDatosPersonales tbody').append('<tr><td width=\"200px\">".$fecha."</td><td width=\"400px\">".$nombreArchivo."</td><td width=\"400px\">".$registro['INFO']."</td><td width=\"200px\">".$tam."</td><td width=\"150px\" align=\"center\"><a href=\"ajax/descargaArchivo.php?file=".$registro['PATH']."\"><img src=\"iconos/download24.png\" title=\"Descargar\" /></a></td><td width=\"150px\" align=\"center\"><img onClick=\"eliminarArchivo(\'".$registro['PERSPRNOTE_SNR']."\');\"  src=\"iconos/deleteFile.png\" width=\"20px\" title=\"eliminar\" /></td></tr>');";
				
				echo "$('#tblSubirDocumentosDatosPersonales tbody').append('<tr class=\"align-center\"><td style=\"width:15%;\" class=\"align-left\">".$fecha."</td><td style=\"width:25%;\">".$nombreArchivo."</td><td style=\"width:25%;\">".$registro['INFO']."</td><td style=\"width:15%;\">".$tam."</td><td style=\"width:10%;\"><a href=\"archivos".$registro['PATH']."\" target=\"_blank\"><i class=\"fas fa-file-download\"></i></a></td><td style=\"width:10%;\"><a onClick=\"eliminarArchivo(\'".$registro['PERSPRNOTE_SNR']."\');\" class=\"pointer\"><i class=\"fas fa-trash-alt\"></i></a></td></tr>');";
				$totalArchivos++;
			}
		}
		echo "$('#archivo').val('');
			$('#tblSubirDocumentosDatosPersonales tfoot').append('<tr><td>Total de archivos: ".$totalArchivos."</td></tr>');
			$('#txtInformacionArchivo').val('');
		</script>";
	}
?>