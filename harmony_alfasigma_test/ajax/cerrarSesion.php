<?php
	if(! isset($_SESSION)){
		session_start();
	}
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$idUL = $_POST['idUL'];
		
		$q = "update USER_LOGGING set START_ACTION_TIME = getdate() where USER_LOGGING_SNR = '".$idUL."'";
		
		if(! sqlsrv_query($conn, $q)){
			echo $q;
		}else{
			session_destroy();
			echo "<script>$(location).attr('href','index.php');</script>";
		}
		
		
	}
	
?>