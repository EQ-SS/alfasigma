<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$thisPass = $_POST['pass'];
		$clave = $_POST['clave'];
		$idUsuario = $_POST['idUsuario'];

		$queryPass = "SELECT PASSWORD AS user_password from users where REC_STAT = 0 and user_snr = '".$idUsuario."'";
		$rsPass = sqlsrv_query($conn, $queryPass);

		$passUser = false;
		
		while($registro = sqlsrv_fetch_array($rsPass)){
			$passUser = $registro['user_password'];
		}
		
		echo "<script>";

		if($thisPass != $passUser){
			//reviso en app_user
			$queryPass = "select user_password from APP_USERS where rec_stat = 0 and APUSER_SNR = '".$idUsuario."'";
			$rsPass = sqlsrv_query($conn, $queryPass);
			
			$passUser = false;
			
			while($registro = sqlsrv_fetch_array($rsPass)){
				$passUser = $registro['user_password'];
			}

			if($thisPass != $passUser){
				echo "alertPassActualError();";
			} else{
				//echo "alert('ok admin');";
				$atualiza = "update APP_USERS set user_password = '".$clave."' where APUSER_SNR = '".$idUsuario."'";
			
				if(sqlsrv_query($conn, $atualiza)){
					echo "alertPassOk();
						$('#txtClave').val('');
						$('#txtRepetirClave').val('');
						$('#seguridad').hide();
						$('#seguridad').val('');
						$('#txtActualClave').val('');
						$('#muestraCambiarPassword').show('slow');
						$('#cambiarPassword').hide('slow');";
					
				}else{
					echo "alertPassErrorAct();";
				}
			}			
		}else{
			//echo "alert('ok');";
			//$atualiza = "update KOMMLOC set user_password = '".$clave."' where kloc_snr = '".$idUsuario."'";
			$atualiza = "update users set password = '".$clave."' where user_snr = '".$idUsuario."'";
			
			if(sqlsrv_query($conn, $atualiza)){
				echo "alertPassOk();
					$('#txtClave').val('');
					$('#txtRepetirClave').val('');
					$('#seguridad').hide();
					$('#seguridad').val('');
					$('#txtActualClave').val('');
					$('#muestraCambiarPassword').show('slow');
					$('#cambiarPassword').hide('slow');";
				
			}else{
				echo "alertPassErrorAct();";
			}
		}
		echo "</script>";
	}
	
?>