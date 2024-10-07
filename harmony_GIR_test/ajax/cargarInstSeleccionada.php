<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idInst = $_POST['idInst'];
		$queryInst = "select i.inst_snr as id, i.name as nombre,
			STREET1 as calle, i.num_ext as exterior,
			city.zip as cp, city.name as colonia,
			d.NAME as del, state.NAME as estado,
			bri.name as brick, plw.EMAIL as mail, plw.tel, p.mobile as cel, plw.num_int as interior
			from inst i
			inner join city on city.CITY_SNR = i.CITY_SNR
			inner join PERSON p on p.PERS_SNR = p.PERS_SNR
			inner join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
			inner join STATE on state.STATE_SNR = city.STATE_SNR
			inner join BRICK bri on bri.brick_snr = city.brick_snr
			left join PERSLOCWORK plw on plw.inst_snr = i.INST_SNR
			where i.inst_snr = '".$idInst."' and i.rec_stat = 0 ";
			
			//echo $queryInst;
			
		$inst = sqlsrv_fetch_array(sqlsrv_query($conn, $queryInst));
		echo "<script>
			$('#hdnIdInstPersonaNuevo').val('".$inst['id']."');
			$('#txtNombreInstPersonaNuevo').val('".$inst['nombre']."');
			$('#txtCalleInstPersonaNuevo').val('".$inst['calle']."');
			$('#txtNumExtInstPersonaNuevo').val('".$inst['exterior']."');
			$('#txtNumIntInstPersonaNuevo').val('".$inst['interior']."');
			$('#txtCPInstPersonaNuevo').val('".$inst['cp']."');
			$('#txtColoniaInstPersonaNuevo').val('".$inst['colonia']."');
			$('#txtCiudadInstPersonaNuevo').val('".$inst['del']."');
			$('#txtEstadoInstPersonaNuevo').val('".$inst['estado']."');
			$('#txtBrickInstPersonaNuevo').val('".$inst['brick']."');
			$('#txtTelefonoInstPersonaNuevo').val('".$inst['tel']."');
			$('#txtTelefono1InstPersonaNuevo').val('".$inst['cel']."');
			$('#txtEmailInstPersonaNuevo').val('".$inst['mail']."');

			$('#txtNombreInstPersonaNuevo').removeClass('invalid');
			$('#txtNombreInstPersonaNuevoError').hide();
		</script>";
	}
	
?>