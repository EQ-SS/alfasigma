<?php
include "../conexion.php";
if(! $conn){
	echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
}else{
	
	if(isset($_POST['idDepto']) && $_POST['idDepto'] != ''){
		$idDepto = $_POST['idDepto'];
	}else{
		$idDepto = '';
	}
	
	$idCity = $_POST['idCity'];
	$estatus = $_POST['estatus'];
	$tipo = $_POST['tipo'];
	$nombre = strtoupper($_POST['nombre']);
	$responsable  = strtoupper($_POST['responsable']);
	$calle = strtoupper($_POST['calle']);
	$tel1 = $_POST['tel1'];
	$tel2 = $_POST['tel2'];
	$celular = $_POST['celular'];
	$email = $_POST['mail'];
	$comentarios = strtoupper($_POST['comentarios']);
	$idInst = $_POST['idInst'];
	
	if($idDepto == ''){//nuevo
		$queryInserta = "INSERT INTO DEPART(
			DEPART_SNR,
			INST_SNR,
			STATUS_SNR,
			DEPTYPE_SNR,
			NAME,
			STREET2,
			STREET1,
			CITY_SNR,
			TEL1,
			TEL2,
			FAX,
			EMAIL,
			INFO )
			VALUES (
			NEWID(),
			'".$idInst."',
			'".$estatus."',
			'".$tipo."',
			'".$nombre."',
			'".$responsable."',
			'".$calle."',
			'".$idCity."',
			'".$tel1."',
			'".$tel2."',
			'".$celular."',
			'".$email."',
			'".$comentarios."')";
		
		if(! sqlsrv_query($conn, $queryInserta)){
			echo $queryInserta;
		}
	}else{
		$queryActualiza = "UPDATE DEPART SET 
			STATUS_SNR = '".$estatus."',
			DEPTYPE_SNR = '".$tipo."',
			NAME = '".$nombre."',
			STREET2 = '".$responsable."',
			STREET1 = '".$calle."',
			CITY_SNR = '".$idCity."',
			TEL1 = '".$tel1."',
			TEL2 = '".$tel2."',
			FAX = '".$celular."',
			EMAIL = '".$email."',
			INFO = '".$comentarios."' 
			WHERE DEPART_SNR = '".$idDepto."' ";
		
		if(! sqlsrv_query($conn, $queryActualiza)){
			echo $queryActualiza;
		}
	}
	
	echo "<script>
		$('#tblDepartamentos tbody').empty();";
	
		$queryDepartamento = "select d.depart_snr, d.name, d.street2, d.street1, c.name as colonia, d.tel1 from depart d, city c where INST_SNR = '".$idInst."' and c.city_snr = d.city_snr and d.rec_stat = 0 order by d.name";
		$rsDepartamentos = sqlsrv_query($conn, $queryDepartamento);
		$trDepto = 1;
		while($depto = sqlsrv_fetch_array($rsDepartamentos)){
			//echo "$('#tblDepartamentos tbody').append('<tr><td>".$depto['name']."</td><td>".$depto['street2']."</td><td>".$depto['street1']."</td><td>".$depto['colonia']."</td><td>".$depto['tel1']."</td><td><img src=\"iconos/editar.png\" width=\"20px\" onClick=\"editarDepart(\'".$depto['depart_snr']."\')\"; /></td></tr>');";
			echo "$('#tblDepartamentos tbody').append('<tr id=\"trDepto".$trDepto."\" ><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" align=\"left\" width=\"350px\">".$depto['name']."</td><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" align=\"left\" width=\"350px\">".$depto['street2']."</td><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" align=\"left\" width=\"250px\">".$depto['street1']."</td><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" align=\"left\" width=\"250px\">".$depto['colonia']."</td><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" align=\"center\" width=\"100px\">".$depto['tel1']."</td><td align=\"center\" width=\"100px\"><img src=\"iconos/editar.png\" width=\"20px\" onClick=\"editarDepart(\'".$depto['depart_snr']."\')\"; /></td><td align=\"center\" width=\"100px\"><img src=\"iconos/eliminar.png\" width=\"20px\" onClick=\"eliminarDepart(\'".$depto['depart_snr']."\')\"; /></td></tr>');";
			$trDepto++;
		}
	
	echo "$('#divDepartamento').hide();
		$('#divCapa3').hide();
		</script>";
}
?>