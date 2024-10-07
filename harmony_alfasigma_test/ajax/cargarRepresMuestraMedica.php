<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$palabra = $_POST['palabra'];
		$ids = $_POST['ids'];
		
		echo "<script>
				$('#tblRepresentantesMuestraMedica tbody').empty();";
		
		$qRepres = "select top 20 user_snr, 
			lname + ' ' + mothers_lname + ' ' + fname as nombre
			from users 
			where user_type = 4
			and user_snr in ('".$ids."') 
			and lname + ' ' + mothers_lname + ' ' + fname like '%".$palabra."%'
			order by lname, mothers_lname, fname";
		$rsRepres = sqlsrv_query($conn, $qRepres);
		while($repre = sqlsrv_fetch_array($rsRepres)){
			$registro = "<input class=\"with-gap radio-col-red\" type=\"radio\" id=\"".$repre['user_snr']."\" name=\"optRepres\" value=\"".$repre['user_snr']."\">";
			$registro .= "<label for=\"".$repre['user_snr']."\">".$repre['nombre']."</label>";
			echo "$('#tblRepresentantesMuestraMedica tbody').append('<tr><td>".$registro."</td></tr>');";
		}
		echo "</script>";
	}
	
?>