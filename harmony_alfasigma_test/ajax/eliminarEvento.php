<?php
	include "../conexion.php";
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$idEvento = $_POST['idEvento'];
		$idUsuario = $_POST['idUsuario'];
		$rec_stat = 2;
		$sync = 0;
		
		$query = "update EVENT set REC_STAT = ?, SYNC = ? where EVENT_SNR = ?";
		$sql = sqlsrv_prepare($conn, $query, array(&$rec_stat, &$sync, &$idEvento));
		
		if(!$sql){
			die(print_r(sqlsrv_errors(), true));
		}
		
		if(sqlsrv_execute($sql)){
			echo "Evento eliminado";
		}else{
			die(print_r(sqlsrv_errors(), true));
		}
		
	}
?>