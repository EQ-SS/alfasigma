<?php
	session_start();
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$user = $_POST['usuario'];
		$pass = $_POST['pass'];
	
		//$query = "select * from USERS where rec_stat = 0 and USER_NAME = '".$user."' and PWDCOMPARE('".$pass."', PASSWORD)= 1 ";
		$query = "select * from USERS where rec_stat = 0 and USER_NAME = '".$user."' and PASSWORD = '".$pass."' ";
		$rsUsuario = sqlsrv_query($conn, $query);
		
		$idUser = false;
		
		while($registro = sqlsrv_fetch_array($rsUsuario)){
			$idUser = $registro['USER_SNR'];
		}
		//echo $idUser;
		if(! $idUser){
			//reviso en app_user
			/*$query = "select * from APP_USERS where rec_stat = 0 and NAME = '".$user."' and USER_PASSWORD = '".$pass."'";
			$rsUsuario = sqlsrv_query($conn, $query);
			
			$idUser = false;
			
			while($registro = sqlsrv_fetch_array($rsUsuario)){
				$idUser = $registro['APUSER_SNR'];
			}
			if(! $idUser){*/
				echo "<script>	document.getElementById('errorUser').innerHTML = 'Usuario y/o clave incorrecta.';
							$('#txtUser').addClass('invalid');
							$('#txtPass').addClass('invalid');
					</script>";
			/*}else{
				echo "<script>
						document.getElementById('errorUser').innerHTML = '';
						document.getElementById('errorUser').style.display='none';
						$(location).attr('href','principal.php?idUser=".$idUser."');
					</script>";
			}*/
		}else{
			$_SESSION["usuario"]=$user;
			echo "<script>
				document.getElementById('errorUser').innerHTML = '';
				document.getElementById('errorUser').style.display='none';
				$(location).attr('href','principal.php?idUser=".$idUser."');</script>";
		}
	}
	
?>