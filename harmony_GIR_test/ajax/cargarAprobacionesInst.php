<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		
		$idUsuario = $_POST['idUsuario'];
		$estatus = $_POST['estatus'];
		
		$query = "select i.i_movement_type, city.name as colonia, i.i_name, 
            i.i_street1, i.i_tel1, '' as clasificacion, a.DATE_CHANGE as FECHA, 
            CASE WHEN i.i_rec_stat = 0 THEN 'ACTIVO' ELSE 'INACTIVO' END as status, 
			motivo.name as motivo
			from inst_approval i, approval_status a, city, codelist motivo
            where a.approved_status = '".$estatus."' and a.inst_approval_snr = i.inst_approval_snr 
            and i.i_city_snr = city.city_snr
			and motivo.clist_snr = a.reject_reason_snr			
			and i.INST_APPROVAL_SNR <> '00000000-0000-0000-0000-000000000000' 
			and a.change_user_snr = '".$idUsuario."' 
			and a.rec_stat = 0 and i.rec_stat = 0 
			order by  a.DATE_CHANGE";
		
		//echo $query;
		
		$rs = sqlsrv_query($conn, $query);
		
		echo "<script>
				$('#tblAprobacionesInst thead').empty();
				$('#tblAprobacionesInst tbody').empty();";
				
		if($estatus == 3){
			echo "$('#tblAprobacionesInst thead').append('<tr><td style=\"width:6%;\">Tipo</td><td style=\"width:15%;\">Colonia</td><td style=\"width:20%;\">Nombre</td><td style=\"width:20%;\">Dirección</td><td style=\"width:10%;\">Teléfono</td><td style=\"width:11%;\">Clasificación</td><td style=\"width:10%;\">Fecha</td><td style=\"width:8%;\">Motivo</td></tr>');";
		}else{
			echo "$('#tblAprobacionesInst thead').append('<tr><td style=\"width:6%;\">Tipo</td><td style=\"width:15%;\">Colonia</td><td style=\"width:20%;\">Nombre</td><td style=\"width:20%;\">Dirección</td><td style=\"width:10%;\">Teléfono</td><td style=\"width:11%;\">Clasificación</td><td style=\"width:10%;\">Fecha</td><td style=\"width:8%;\">Estatus</td></tr>');";
		}
		$registros = 0;
		while($aproba = sqlsrv_fetch_array($rs)){
			foreach ($aproba['FECHA'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha = substr($val, 0, 10);
				}
			}
			if($estatus == 3){
				echo "$('#tblAprobacionesInst tbody').append('<tr><td style=\"width:6%;\">".$aproba['i_movement_type']."</td><td style=\"width:15%;\">".$aproba['colonia']."</td><td style=\"width:20%;\">".$aproba['i_name']."</td><td style=\"width:20%;\">".$aproba['i_street1']."</td><td style=\"width:10%;\">".$aproba['i_tel1']."</td><td style=\"width:11%;\">".$aproba['clasificacion']."</td><td style=\"width:10%;\">".$fecha."</td><td style=\"width:8%;\">".$aproba['motivo']."</td></tr>');";
			}else{
				echo "$('#tblAprobacionesInst tbody').append('<tr><td style=\"width:6%;\">".$aproba['i_movement_type']."</td><td style=\"width:15%;\">".$aproba['colonia']."</td><td style=\"width:20%;\">".$aproba['i_name']."</td><td style=\"width:20%;\">".$aproba['i_street1']."</td><td style=\"width:10%;\">".$aproba['i_tel1']."</td><td style=\"width:11%;\">".$aproba['clasificacion']."</td><td style=\"width:10%;\">".$fecha."</td><td style=\"width:8%;\">".$aproba['status']."</td></tr>');";
			}
			$registros++;	
		}
		echo "$('#numRegistrosAprobInst').text('Registros: ".$registros."');
		</script>";
	}
?>