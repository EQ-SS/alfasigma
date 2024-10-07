<?php
	include "../conexion.php";
	$flonorm = $_POST['flonorm'];
	$vessel = $_POST['vessel'];
	$ateka = $_POST['ateka'];
	$zirfos = $_POST['zirfos'];
	$esoxx = $_POST['esoxx'];
	$cuenta = 0;
	
	$query = "select c.NAME from CODELIST c WHERE CLIST_SNR in('".$flonorm."','".$vessel."','".$ateka."','".$zirfos."','".$esoxx."')";
	$rs = sqlsrv_query($conn, $query);
	
	if( $rs === false) {
		die( print_r( sqlsrv_errors(), true) );
	}
	
	while($registros = sqlsrv_fetch_array($rs)){
		if($registros['NAME'] == "SI")
			$cuenta++;
	}
	
	echo $cuenta;
	
?>