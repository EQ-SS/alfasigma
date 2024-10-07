<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$cp = $_POST['cp'];
		//$queryColonias = "select name from city where zip = '".$cp."' and REC_STAT = 0 order by name";
		if(isset($_POST['pantalla']) && $_POST['pantalla'] != ''){
			$pantalla = $_POST['pantalla'];
		}else{
			$pantalla = '';
		}
		
		$queryColonias = "select c.name colonia,
			d.name ciudad,
			e.name estado,
			b.name brick
			from city c, DISTRICT d, STATE e, BRICK b
			where c.zip = '".$cp."' 
			and c.REC_STAT = 0 
			and c.DISTR_SNR = d.DISTR_SNR
			and c.STATE_SNR = e.STATE_SNR
			and c.BRICK_SNR = b.BRICK_SNR
			order by c.name";
		
		$rsColonias = sqlsrv_query($conn, $queryColonias);
		
		if($pantalla == ''){
			echo "<script>
					$('#sltColoniaInstNueva').empty();";
			$i=0;
			while($coloniaArr = sqlsrv_fetch_array($rsColonias)){
				if($i == 0){
					$colonia = $coloniaArr['colonia'];
				}
				echo "$('#sltColoniaInstNueva').removeClass('invalid-slt');
					$('#sltColoniaInstNuevaError').hide();
					$('#sltColoniaInstNueva').append('<option value=\"".$coloniaArr['colonia']."\">".$coloniaArr['colonia']."</option>');
					$('#txtCiudadInstNueva').val('".$coloniaArr['ciudad']."');
					$('#txtEstadoInstNueva').val('".$coloniaArr['estado']."');
					$('#txtBrickInstNueva').val('".$coloniaArr['brick']."');
				";
				$i++;
			}
			$queryCity = "select city_snr from city where zip = '".$cp."' and name = '".$colonia."'";
			//echo $queryCity;
			$rsCity = sqlsrv_fetch_array(sqlsrv_query($conn, $queryCity));
			echo "$('#hdnCityInstNueva').val('".$rsCity['city_snr']."');
			</script>";
		}else if($pantalla == 'depto'){
			echo "<script>
				$('#sltColoniaDepto').empty();";
			$i=0;
			while($coloniaArr = sqlsrv_fetch_array($rsColonias)){
				if($i == 0){
					$colonia = $coloniaArr['colonia'];
				}
				echo "$('#sltColoniaDepto').append('<option value=\"".$coloniaArr['colonia']."\">".$coloniaArr['colonia']."</option>');
					$('#txtCiudadDepto').val('".$coloniaArr['ciudad']."');
					$('#txtEstadoDepto').val('".$coloniaArr['estado']."');
					$('#txtBrickDepto').val('".$coloniaArr['brick']."');
				";
				$i++;
			}
			$queryCity = "select city_snr from city where zip = '".$cp."' and name = '".$colonia."'";
			//echo $queryCity;
			$rsCity = sqlsrv_fetch_array(sqlsrv_query($conn, $queryCity));
			echo "$('#hdnCityDepto').val('".$rsCity['city_snr']."');
			</script>";
		}
	}
?>