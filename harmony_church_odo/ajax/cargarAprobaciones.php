<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		
		$idUsuario = $_POST['idUsuario'];
		$estatus = $_POST['estatus'];
		
		if(isset($_POST['tipo']) && $_POST['tipo'] !=''){
			$tipo = $_POST['tipo'];
		}else{
			$tipo = '';
		}
		
		$query = "select p.PERS_APPROVAL_SNR, p.P_MOVEMENT_TYPE, p.P_LNAME,p.P_MOTHERS_LNAME, p.P_FNAME, c.name as esp, 
		case when cityapp.name is not null then cityapp.NAME else CITY.name end  as colonia, 
		case when ia.I_NAME is not null then ia.I_NAME else i.NAME end as institucion, 
		a.DATE_CHANGE as FECHA, 
		CASE WHEN p.rec_stat = '0' THEN 'ACTIVO' ELSE 'INACTIVO' END as status,
		motivo.name as motivo
		from person_approval p 
		left outer join codelist c on p.P_SPEC_SNR = c.CLIST_SNR
		inner join approval_status a on a.PERS_APPROVAL_SNR = p.PERS_APPROVAL_SNR and a.rec_stat = 0
		left outer join inst i on i.INST_SNR = p.PLW_INST_SNR
		left outer join CITY on city.CITY_SNR = i.CITY_SNR
		left outer join codelist motivo on motivo.clist_snr = a.reject_reason_snr
		left outer join INST_APPROVAL ia on ia.i_inst_snr = p.plw_inst_snr
		left outer join CITY cityapp on ia.I_CITY_SNR = cityapp.CITY_SNR
		where a.approved_status = '".$estatus."'  
		and p.PERS_APPROVAL_SNR <> '00000000-0000-0000-0000-000000000000'
		and a.change_user_snr = '".$idUsuario."' 
		and p.rec_stat = 0
		 ";
		
		if($tipo != ''){
			$query .= " and p.P_MOVEMENT_TYPE = '".$tipo."' ";
		}
		$query .= " order by a.DATE_CHANGE,p.P_MOVEMENT_TYPE ";
		
		//echo $query;
		
		$rs = sqlsrv_query($conn, $query, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		echo "<script>
				$('#tblAprobacionesPers thead').empty();
				$('#tblAprobacionesPers tbody').empty();
				$('#tblAprobacionesPers tfoot').empty();";
				
		if($estatus == 3){
			echo "$('#tblAprobacionesPers thead').append('<tr><td style=\"width:4%;\">Tipo</td><td style=\"width:10%;\">Paterno</td><td style=\"width:10%;\">Materno</td><td style=\"width:12%;\">Nombre(s)</td><td style=\"width:12%;\">Especialidad</td><td style=\"width:15%;\">Colonia</td><td style=\"width:15%;\">Institución</td><td style=\"width:7%;\">Fecha</td><td style=\"width:15%;\">Motivo</td></tr>');";
			//echo "$('#tblAprobacionesPers thead').append('<tr><td width=\"50px\">Estatus</td><td width=\"100px\">Paterno</td><td width=\"100px\">Materno</td><td width=\"160px\">Nombre(s)</td><td width=\"150px\">Especialidad</td><td width=\"150px\">Colonia</td><td width=\"310px\">Institución</td><td width=\"50px\">Fecha</td></tr>');";
		}else{
			echo "$('#tblAprobacionesPers thead').append('<tr><td style=\"width:5%;\">Tipo</td><td style=\"width:10%;\">Paterno</td><td style=\"width:10%;\">Materno</td><td style=\"width:15%;\">Nombre(s)</td><td style=\"width:15%;\">Especialidad</td><td style=\"width:15%;\">Colonia</td><td style=\"width:17%;\">Institución</td><td style=\"width:7%;\">Fecha</td><td style=\"width:6%;\">Estatus</td></tr>');";
			//echo "$('#tblAprobacionesPers thead').append('<tr><td width=\"50px\">Estatus</td><td width=\"100px\">Paterno</td><td width=\"100px\">Materno</td><td width=\"160px\">Nombre(s)</td><td width=\"150px\">Especialidad</td><td width=\"150px\">Colonia</td><td width=\"310px\">Institución</td><td width=\"50px\">Fecha</td></tr>');";
		}
		
		$registros = 0;
		while($aproba = sqlsrv_fetch_array($rs)){
			foreach ($aproba['FECHA'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha = substr($val, 0, 16);
				}
			}
			if($aproba['P_MOVEMENT_TYPE'] == 'D'){
				$tipo = 'B';
			}else{
				$tipo = $aproba['P_MOVEMENT_TYPE'];
			}
			if($estatus == 3){
				echo "$('#tblAprobacionesPers tbody').append('<tr><td style=\"width:4%;\">".$tipo."</td><td style=\"width:10%;\">".$aproba['P_LNAME']."</td><td style=\"width:10%;\">".$aproba['P_MOTHERS_LNAME']."</td><td style=\"width:12%;\">".$aproba['P_FNAME']."</td><td style=\"width:12%;\">".$aproba['esp']."</td><td style=\"width:15%;\">".$aproba['colonia']."</td><td style=\"width:15%;\">".$aproba['institucion']."</td><td style=\"width:7%;\">".$fecha."</td><td style=\"width:15%;\">".$aproba['motivo']."</td></tr>');";
				//echo "$('#tblAprobacionesPers tbody').append('<tr><td width=\"50px\">".$aproba['P_MOVEMENT_TYPE']."</td><td width=\"100px\">".$aproba['P_LNAME']."</td><td width=\"100px\">".$aproba['P_MOTHERS_LNAME']."</td><td width=\"150px\">".$aproba['P_FNAME']."</td><td width=\"150px\">".$aproba['esp']."</td><td width=\"150px\">".$aproba['colonia']."</td><td width=\"300px\">".$aproba['institucion']."</td><td width=\"50px\">".$fecha."</td></tr>');";
			}else{
				echo "$('#tblAprobacionesPers tbody').append('<tr><td style=\"width:5%;\">".$tipo."</td><td style=\"width:10%;\">".$aproba['P_LNAME']."</td><td style=\"width:10%;\">".$aproba['P_MOTHERS_LNAME']."</td><td style=\"width:15%;\">".$aproba['P_FNAME']."</td><td style=\"width:15%;\">".$aproba['esp']."</td><td style=\"width:15%;\">".$aproba['colonia']."</td><td style=\"width:17%;\">".$aproba['institucion']."</td><td style=\"width:7%;\">".$fecha."</td><td style=\"width:6%;\">".$aproba['status']."</td></tr>');";
				//echo "$('#tblAprobacionesPers tbody').append('<tr><td width=\"50px\">".$aproba['P_MOVEMENT_TYPE']."</td><td width=\"100px\">".$aproba['P_LNAME']."</td><td width=\"100px\">".$aproba['P_MOTHERS_LNAME']."</td><td width=\"150px\">".$aproba['P_FNAME']."</td><td width=\"150px\">".$aproba['esp']."</td><td width=\"150px\">".$aproba['colonia']."</td><td width=\"300px\">".$aproba['institucion']."</td><td width=\"50px\">".$fecha."</td></tr>');";
			}
			$registros++;
		}
		echo "$('#numRegistrosAprobPers').text('Registros: ".$registros."');
		</script>";
	}
?>