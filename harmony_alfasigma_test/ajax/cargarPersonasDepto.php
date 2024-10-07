<?php
	include "../conexion.php";
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$idDepto = $_POST['idDepto'];
		
		$queryConsulta = "select * from depart_person p, codelist c where p.spec_snr = c.clist_snr and p.rec_stat = 0 and p.depart_snr = '".$idDepto." '";
		
		$rsConsulta = sqlsrv_query($conn, $queryConsulta);
		
		echo "<script>
			$('#tblPersonasDepartamentoInstituciones tbody').empty();";
		$reg = 1;
		while($registro = sqlsrv_fetch_array($rsConsulta)){
			$idTr = $reg - 1;
			echo "$('#tblPersonasDepartamentoInstituciones tbody').append('<tr><td width=\"50px\">".$reg."</td><td width=\"250px\">".$registro['LNAME']."</td><td width=\"250px\">".$registro['NAME_FATHER']."</td><td width=\"250px\">".$registro['FNAME']."</td><td width=\"200px\">".$registro['NAME']."</td><td width=\"50px\" align=\"center\"><img onclick=\"editarPersonaDepto(".$idTr.",\'".$registro['LNAME']."\',\'".$registro['NAME_FATHER']."\',\'".$registro['FNAME']."\',\'".$registro['NAME']."\',\'".$registro['DEPARTPERS_SNR']."\');\" src=\"iconos/editar.png\" width=\"20px\" /></td><td width=\"50px\" align=\"center\"><img onclick=\"eliminarPersonaDepto(\'".$registro['DEPARTPERS_SNR']."\');\" src=\"iconos/eliminar.png\" width=\"20px\" /></td></tr>');";
			$reg++;
		}
		echo "$('#hdnIdpersonaDeptoEdit').val('');
		$('#btnAgregarPersonaDepartamentoDatosInstituciones').html('Agregar');
		</script>";
	}
?>