<?php
	include "../conexion.php";
	$idDepto = $_POST['idDepto'];
	$idInst = $_POST['idInst'];
	$queryDel = "update DEPART set rec_stat = 2 where depart_snr = '".$idDepto."'";
	sqlsrv_query($conn, $queryDel);
	$queryDelPersonas = "update DEPART_PERSON set rec_stat = 2 where depart_snr = '".$idDepto."'";
	sqlsrv_query($conn, $queryDelPersonas);
	
	echo "<script>
		$('#tblDepartamentos tbody').empty();
		$('#tblPersonasDepartamentoInstituciones tbody').empty();
		$('#hdnIDepto').val('');
		$('#lblNombreDepto').text('');";
	
		$queryDepartamento = "select d.depart_snr, d.name, d.street2, d.street1, c.name as colonia, d.tel1 from depart d, city c where INST_SNR = '".$idInst."' and c.city_snr = d.city_snr and d.rec_stat = 0 order by d.name";
		$rsDepartamentos = sqlsrv_query($conn, $queryDepartamento);
		$trDepto = 1;
		while($depto = sqlsrv_fetch_array($rsDepartamentos)){
			echo "$('#tblDepartamentos tbody').append('<tr id=\"trDepto".$trDepto."\" ><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" align=\"left\" width=\"350px\">".$depto['name']."</td><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" align=\"left\" width=\"350px\">".$depto['street2']."</td><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" align=\"left\" width=\"250px\">".$depto['street1']."</td><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" align=\"left\" width=\"250px\">".$depto['colonia']."</td><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" align=\"center\" width=\"100px\">".$depto['tel1']."</td><td align=\"center\" width=\"100px\"><img src=\"iconos/editar.png\" width=\"20px\" onClick=\"editarDepart(\'".$depto['depart_snr']."\')\"; /></td><td align=\"center\" width=\"100px\"><img src=\"iconos/eliminar.png\" width=\"20px\" onClick=\"eliminarDepart(\'".$depto['depart_snr']."\')\"; /></td></tr>');";
			$trDepto++;
		}
		echo "</script>";
		//echo $queryDel."<br>".$queryDelPersonas."<br>".$queryDepartamento;
?>