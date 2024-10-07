<table border="1" width="30%">
	<tr>
		<td>Ruta</td><td>fecha</td><td>Hora</td>
	</tr>
<?php
	$ruta = "e:/logs/cdmlabs/baja/";
	if (is_dir($ruta)) {
      if ($dh = opendir($ruta)) {
		  while (($file = readdir($dh)) !== false) {
            //esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio
            //mostraría tanto archivos como directorios
            //echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file);
            if (is_dir($ruta . $file)){
               //solo si el archivo es un directorio, distinto que "." y ".."
               //echo "<br>Directorio: $ruta$file";
            }else{
				$arrArchivo = explode("-", $file);
				$user = $arrArchivo[0];
				$fecha = substr($arrArchivo[1], 6, 2)."/".substr($arrArchivo[1], 4, 2)."/".substr($arrArchivo[1], 0, 4);
				$hora = substr($arrArchivo[1], 8, 2).":".substr($arrArchivo[1], 10, 2).":".substr($arrArchivo[1], 12, 2);
				echo "<tr><td>".$user."</td><td>".$fecha."</td><td>".$hora."</td></tr>";
				//yyyymmddhhiiss
			}
         }
      closedir($dh); 
	  }
	}else{
		echo "<br>No es ruta valida"; 
	}
?>
</table>