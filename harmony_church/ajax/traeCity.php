<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$cp = $_POST['cp'];
		$colonia = $_POST['colonia'];
		$queryCity = "select city_snr from city where zip = '".$cp."' and name = '".$colonia."'";
		//echo $queryCity;
		$rsCity = sqlsrv_fetch_array(sqlsrv_query($conn, $queryCity));
		echo "<script>$('#hdnCityInstNueva').val('".$rsCity['city_snr']."');</script>";
	}
?>