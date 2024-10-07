<?php
include "conexion.php";
function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

$dir_subida = 'archivos/';
//$ruta = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from KOMMLOC where kloc_snr = '".$_POST['idUsuario']."'"))['NAME'];
$ruta = sqlsrv_fetch_array(sqlsrv_query($conn, "select user_nr from users where user_snr = '".$_POST['idUsuario']."'"))['user_nr'];

if(! file_exists($dir_subida.$ruta)){
	mkdir($dir_subida.$ruta);
}
$fichero_subido = $dir_subida . $ruta . "/" . basename($_FILES['archivo']['name']);

if(file_exists($fichero_subido)){
	echo "<script>
		var repetido = true;
	</script>";
}else{
	if (move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)) {
		$queryInsert = "insert into PERSON_PRIV_NOTE values (NEWID(),
			'00000000-0000-0000-0000-000000000000',
			'".$_POST['idUsuario']."',
			0,
			'".$_POST['txtInformacionArchivo']."',
			'".$fichero_subido."',
			getdate())";
		if(! sqlsrv_query($conn, $queryInsert)){
			echo $queryInsert;
		}
		$queryTabla = "select * from PERSON_PRIV_NOTE where user_snr = '".$_POST['idUsuario']."' and rec_stat = 0";
		//echo $queryTabla."<br>";
		$rsTabla = sqlsrv_query($conn, $queryTabla, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		//$totalArchivos = sqlsrv_num_rows($rsTabla);
		
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
			if(file_exists($registro['PATH'])){
				$tam = human_filesize(filesize($registro['PATH']))."B";
				$nombreArchivo = explode("/",$registro['PATH'])[2];
				//echo "$('#tblSubirDocumentosDatosPersonales tbody').append('<tr><td width=\"200px\">".$fecha."</td><td width=\"400px\">".$nombreArchivo."</td><td width=\"400px\">".$registro['INFO']."</td><td width=\"400px\">".$tam."</td><td width=\"150px\" align=\"center\"><a href=\"ajax/descargaArchivo.php?file=".$registro['PATH']."\"><img src=\"iconos/download24.png\" title=\"Descargar\" /></a></td><td width=\"150px\" align=\"center\"><img onClick=\"eliminarArchivo(\'".$registro['PERSPRNOTE_SNR']."\');\"  src=\"iconos/deleteFile.png\" width=\"20px\" title=\"eliminar\" /></td></tr>');";
				echo "$('#tblSubirDocumentosDatosPersonales tbody').append('<tr class=\"align-center\"><td style=\"width:15%;\" class=\"align-left\">".$fecha."</td><td style=\"width:25%;\">".$nombreArchivo."</td><td style=\"width:25%;\">".$registro['INFO']."</td><td style=\"width:15%;\">".$tam."</td><td style=\"width:10%;\"><a href=\"ajax/descargaArchivo.php?file=".$registro['PATH']."\"><i class=\"fas fa-file-download\"></i></a></td><td style=\"width:10%;\"><a onClick=\"eliminarArchivo(\'".$registro['PERSPRNOTE_SNR']."\');\"  class=\"pointer\"><i class=\"fas fa-trash-alt\"></i></a></td></tr>');";
				$totalArchivos++;
			}
		}
		echo "$('#tblSubirDocumentosDatosPersonales tfoot').append('<tr><td>Total de archivos: ".$totalArchivos."</td></tr>');
			$('#txtInformacionArchivo').val('');
			$('#archivo').val('');
			var repetido = false;
		</script>";
	}
}
?> 