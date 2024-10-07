<?php
include "../conexion.php";
$archivo = urlencode($_POST['archivo']);
$idUsuario = $_POST['idUsuario'];

$ruta = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from kommloc where kloc_snr = '".$idUsuario."'"))['NAME'];
$rsArchivos = sqlsrv_query($conn, "select * from PERSON_PRIV_NOTE where user_snr = '".$idUsuario."'");
echo $archivo;
/*$path = "archivos/".$ruta."/".$archivo;
echo "<script>";
if(file_exists($path)){
	echo "alert('existe');";
}else{
	echo "alert('no existe');"
}
echo "</script>";*/
?> 