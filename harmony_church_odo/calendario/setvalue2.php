<?php
include "../conexion.php";
require('calendario.php');
$mes=$_GET['month'];
$anio=$_GET['year'];
$dia=1;
/*$idUsuario = $_GET['idUsuario'];
$ids = $_GET['ids'];
//calendar($mes,$anio,1);*/


$idUsuario = $_GET['idUsuario'];
$ids = $_GET['ids'];
/*$repre2 = $_GET['repre'];
$repreNombres2 = $_GET['repreNombres'];
$tipoUsuario = $_GET['tipoUsuario'];

if(isset($repre2) && $repre2 != ''){
    $repre = str_replace(",","','",substr($repre2, 0, -1));
}else{
    $repre = $ids;
}

if(isset($repreNombres2) && $repreNombres2 != ''){
    $repreNombres = $repreNombres2;
}else{
    $repreNombres = "Seleccione";
}*/

calendar($mes,$anio, 1, $conn, $idUsuario, $ids, $dia);

//calendar($mes,$anio, 1, $conn, $idUsuario, $ids, $dia, $repre, $repreNombres);
?>
