<?php
	include "../conexion.php";
	
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array(" ", " ", " ", " ");
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		$ids = $_POST['ids'];
		
		if(isset($_POST['estatus']) && $_POST['estatus'] != ''){
			$estatus = $_POST['estatus'];
		}else{
			$estatus = '';
		}
		
		if(isset($_POST['ruta']) && $_POST['ruta'] != ''){
			$ruta = $_POST['ruta'];
		}else{
			$ruta = '';
		}
		
		if(isset($_POST['tipoMovimiento']) && $_POST['tipoMovimiento'] != ''){
			$tipoMovimiento = $_POST['tipoMovimiento'];
		}else{
			$tipoMovimiento = '';
		}

		$query = "select p.PERS_APPROVAL_SNR, p.P_MOVEMENT_TYPE, p.P_LNAME,p.P_MOTHERS_LNAME, p.P_FNAME, c.name as esp, 
		case when cityapp.name is not null then cityapp.NAME else CITY.name end  as colonia, 
		case when ia.I_NAME is not null then ia.I_NAME else i.NAME end as institucion, 
		case when ia.I_STREET1 is not null then ia.I_STREET1 else i.STREET1 end as direccion, 
		a.DATE_CHANGE as FECHA, u.LNAME + ' ' + u.FNAME as usuario, a.APPROVED_DATE as fechaAprobacion,
		u.user_nr as ruta
		from person_approval p
		left outer join codelist c on p.P_SPEC_SNR = c.CLIST_SNR
		inner join approval_status a on a.PERS_APPROVAL_SNR = p.PERS_APPROVAL_SNR  and a.rec_stat = 0
		left outer join inst i on i.INST_SNR = p.PLW_INST_SNR
		left outer join CITY on city.CITY_SNR = i.CITY_SNR
		left outer join users u on a.CHANGE_USER_SNR = u.USER_SNR 
		left outer join INST_APPROVAL ia on ia.i_inst_snr = p.plw_inst_snr and ia.INST_APPROVAL_SNR in (select INST_APPROVAL_SNR from APPROVAL_STATUS where rec_stat=0 and APPROVED_STATUS=1)
		left outer join CITY cityapp on ia.I_CITY_SNR = cityapp.CITY_SNR
		where p.PERS_APPROVAL_SNR <> '00000000-0000-0000-0000-000000000000' 
		and p.rec_stat = 0 ";
			
		if($ruta != ''){
			$query .= " and u.USER_SNR in ('".$ruta."')";
		}else{
			$query .= " and u.USER_SNR in ('".$ids."')";
		}
		//echo "estatus: ".$estatus."<br>";
		if($estatus != '' and $estatus != '1' and $estatus != '4' ){
			$query .= " and a.approved_status = '".$estatus."' ";
		}else{
			$query .= " and (a.approved_status = '1' or a.approved_status = '4')";
		}
		
		if($tipoMovimiento != ''){
			$query .= " and p.P_MOVEMENT_TYPE = '".$tipoMovimiento." '";
		}
		
		$query .= " order by a.DATE_CHANGE desc ";
		
		//echo $query;
		
		$rs = sqlsrv_query($conn, $query, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		$totalPers = sqlsrv_num_rows($rs);
		
		echo "<script>
				$('#tblAprobacionesPersGerente tbody').empty();
				$('#tblAprobacionesInstGerente tbody').empty();
				$('#tblAprobacionesPersGerente tfoot').empty();
				$('#tblAprobacionesInstGerente tfoot').empty();";
				
		while($aproba = sqlsrv_fetch_array($rs)){
			foreach ($aproba['FECHA'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha = substr($val, 0, 16);
				}
			}
			if(is_object($aproba['fechaAprobacion'])){
				foreach ($aproba['fechaAprobacion'] as $key => $val) {
					if(strtolower($key) == 'date'){
						$fechaAp = substr($val, 0, 16);
					}
				}
			}
			if($aproba['P_MOVEMENT_TYPE'] == "D"){
				$tipo = "B";
			}else{
				$tipo = $aproba['P_MOVEMENT_TYPE'];
			}
			if($estatus == 1){
				//echo "$('#tblAprobacionesPersGerente tbody').append('<tr onClick=\"muestraAprobacion(\'".$aproba['PERS_APPROVAL_SNR']."\',\'p\');\"><td width=\"50px\">".$tipo."</td><td width=\"200px\">".utf8_encode($aproba['P_LNAME'])."</td><td width=\"150px\">".utf8_encode($aproba['P_MOTHERS_LNAME'])."</td><td width=\"150px\">".utf8_encode($aproba['P_FNAME'])."</td><td width=\"150px\">".utf8_encode($aproba['esp'])."</td><td width=\"200px\">".utf8_encode($aproba['institucion'])."</td><td width=\"200px\">".utf8_encode($aproba['direccion'])."</td><td width=\"150px\">".utf8_encode($aproba['colonia'])."</td><td width=\"100px\" >".$fecha."</td><td width=\"100px\" >".$fechaAp."</td><td width=\"250px\">".utf8_encode($aproba['usuario'])."</td></tr>');";
				//echo "$('#tblAprobacionesPersGerente tbody').append('<tr onClick=\"muestraAprobacion(\'".$aproba['PERS_APPROVAL_SNR']."\',\'p\');\"><td width=\"150px\">".$tipo."</td><td width=\"200px\">".utf8_encode($aproba['P_LNAME'])."</td><td width=\"200px\">".utf8_encode($aproba['P_MOTHERS_LNAME'])."</td><td width=\"200px\">".utf8_encode($aproba['P_FNAME'])."</td><td width=\"200px\">".utf8_encode($aproba['esp'])."</td><td width=\"200px\">".utf8_encode($aproba['direccion'])."</td><td width=\"200px\">".utf8_encode($aproba['colonia'])."</td><td width=\"100px\" >".$fecha."</td><td width=\"100px\" >".$fechaAp."</td><td width=\"250px\">".utf8_encode($aproba['usuario'])."</td></tr>');";
				echo "$('#tblAprobacionesPersGerente tbody').append('<tr class=\"pointer\" onClick=\"muestraAprobacion(\'".$aproba['PERS_APPROVAL_SNR']."\',\'p\');\"><td style=\"width:5%;\">".$tipo."</td><td style=\"width:10%;\">".utf8_encode($aproba['P_LNAME'])."</td><td style=\"width:10%;\">".utf8_encode($aproba['P_MOTHERS_LNAME'])."</td><td style=\"width:11%;\">".utf8_encode($aproba['P_FNAME'])."</td><td style=\"width:11%;\">".utf8_encode($aproba['esp'])."</td><td style=\"width:13%;\">".utf8_encode($aproba['institucion'])."</td><td style=\"width:15%;\">".utf8_encode($aproba['direccion'])."</td><td style=\"width:12%;\">".utf8_encode($aproba['colonia'])."</td><td style=\"width:8%;\" >".$fecha."</td><td style=\"width:5%;\">".utf8_encode($aproba['ruta'])."</td></tr>');";
			}else{
				//echo "$('#tblAprobacionesPersGerente tbody').append('<tr><td width=\"50px\">".$tipo."</td><td width=\"150px\">".utf8_encode($aproba['P_LNAME'])."</td><td width=\"150px\">".utf8_encode($aproba['P_MOTHERS_LNAME'])."</td><td width=\"150px\">".utf8_encode($aproba['P_FNAME'])."</td><td width=\"150px\">".utf8_encode($aproba['esp'])."</td><td width=\"150px\">".utf8_encode($aproba['colonia'])."</td><td width=\"200px\">".utf8_encode($aproba['institucion'])."</td><td width=\"100px\" align=\"center\">".$fecha."</td><td width=\"100px\" align=\"center\">".$fechaAp."</td><td width=\"250px\">".utf8_encode($aproba['usuario'])."</td></tr>');";
				//echo "$('#tblAprobacionesPersGerente tbody').append('<tr><td width=\"50px\">".$tipo."</td><td width=\"150px\">".utf8_encode($aproba['P_LNAME'])."</td><td width=\"150px\">".utf8_encode($aproba['P_MOTHERS_LNAME'])."</td><td width=\"150px\">".utf8_encode($aproba['P_FNAME'])."</td><td width=\"150px\">".utf8_encode($aproba['esp'])."</td><td width=\"150px\">".utf8_encode($aproba['colonia'])."</td><td width=\"200px\">".utf8_encode($aproba['institucion'])."</td><td width=\"100px\" align=\"center\">".$fecha."</td><td width=\"100px\" align=\"center\">".$fechaAp."</td><td width=\"250px\">".utf8_encode($aproba['usuario'])."</td></tr>');";
				echo "$('#tblAprobacionesPersGerente tbody').append('<tr><td style=\"width:5%;\">".$tipo."</td><td style=\"width:10%;\">".utf8_encode($aproba['P_LNAME'])."</td><td style=\"width:10%;\">".utf8_encode($aproba['P_MOTHERS_LNAME'])."</td><td style=\"width:11%;\">".utf8_encode($aproba['P_FNAME'])."</td><td style=\"width:11%;\">".utf8_encode($aproba['esp'])."</td><td style=\"width:13%;\">".utf8_encode($aproba['institucion'])."</td><td style=\"width:15%;\">".utf8_encode($aproba['direccion'])."</td><td style=\"width:12%;\">".utf8_encode($aproba['colonia'])."</td><td style=\"width:8%;\" >".$fecha."</td><td style=\"width:5%;\">".utf8_encode($aproba['ruta'])."</td></tr>');";
			}
		}
		
		$query = "select i.inst_approval_snr, i.i_movement_type, city.name as colonia, i.i_name, 
            i.i_street1, i.i_tel1, '' as clasificacion, a.DATE_CHANGE as FECHA,
			u.LNAME + ' ' + u.FNAME as usuario, u.user_nr as ruta
			from inst_approval i 
			inner join approval_status a on a.inst_approval_snr = i.inst_approval_snr and a.rec_stat=0
			left outer join city on i.i_city_snr = city.city_snr
			left outer join users u on a.CHANGE_USER_SNR = u.USER_SNR 
            where i.rec_stat = 0
			and i.INST_APPROVAL_SNR <> '00000000-0000-0000-0000-000000000000'
			";
			
		if($ruta != ''){
			$query .= " and u.USER_SNR in ('".$ruta."')";
		}else{
			$query .= " and u.USER_SNR in ('".$ids."')";
		}
		
		if($estatus != '' and $estatus != '1' and $estatus != '4' ){
			$query .= " and a.approved_status = '".$estatus."' ";
		}else{
			$query .= " and (a.approved_status = '1' or a.approved_status = '4')";
		}
		
		if($tipoMovimiento != ''){
			$query .= " and i.i_movement_type = '".$tipoMovimiento." '";
		}
		
		$query .= " order by a.DATE_CHANGE desc ";
		
		//echo $query;
		
		$rs = sqlsrv_query($conn, $query, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		$totalInst = sqlsrv_num_rows($rs);
		while($aproba = sqlsrv_fetch_array($rs)){
			foreach ($aproba['FECHA'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha = substr($val, 0, 16);
				}
			}
			if($aproba['i_movement_type'] == "D"){
				$tipo = "B";
			}else{
				$tipo = $aproba['i_movement_type'];
			}
			if($estatus == 1){
				echo "$('#tblAprobacionesInstGerente tbody').append('<tr class=\"pointer\" onClick=\"muestraAprobacion(\'".$aproba['inst_approval_snr']."\',\'i\');\"><td style=\"width:5%;\">".utf8_encode($tipo)."</td><td style=\"width:18%;\">".utf8_encode($aproba['colonia'])."</td><td style=\"width:15%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($aproba['i_name']))."</td><td style=\"width:18%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($aproba['i_street1']))."</td><td style=\"width:12%;\">".$aproba['i_tel1']."</td><td style=\"width:12%;\">".utf8_encode($aproba['clasificacion'])."</td><td style=\"width:12%;\">".$fecha."</td><td style=\"width:8%;\">".utf8_encode($aproba['ruta'])."</td></tr>');";
			}else{
				echo "$('#tblAprobacionesInstGerente tbody').append('<tr><td style=\"width:5%;\">".utf8_encode($tipo)."</td><td style=\"width:18%;\">".utf8_encode($aproba['colonia'])."</td><td style=\"width:15%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($aproba['i_name']))."</td><td style=\"width:18%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($aproba['i_street1']))."</td><td style=\"width:12%;\">".$aproba['i_tel1']."</td><td style=\"width:12%;\">".utf8_encode($aproba['clasificacion'])."</td><td style=\"width:12%;\">".$fecha."</td><td style=\"width:8%;\">".utf8_encode($aproba['ruta'])."</td></tr>');";
			}
		}
		
		echo "$('#tblAprobacionesPersGerente tfoot').append('<tr><td>Registros: ".$totalPers."</td></tr>');
			$('#tblAprobacionesInstGerente tfoot').append('<tr><td>Registros: ".$totalInst."</td></tr>');
		</script>";
		
		//echo $query;
	}
?>