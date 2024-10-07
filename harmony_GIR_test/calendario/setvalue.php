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
//$repre = $_GET['repre'];
calendar($mes,$anio, 1, $conn, $idUsuario, $ids, $dia);

?>

