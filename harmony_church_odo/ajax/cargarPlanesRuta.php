<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$idRutas = $_POST['idRuta'];
		$todasRutas = false;
		if($idRutas == ''){
			$todasRutas = true;
			$queryTipoUsuario = "select * from users where rec_stat = 0 and user_snr = '".$_POST['idUsuario']."'";
			$rsTipoUsuario = sqlsrv_query($conn, $queryTipoUsuario, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsTipoUsuario) > 0){
				while($row = sqlsrv_fetch_array($rsTipoUsuario)){
					$tipoUsuario = $row['USER_TYPE'];
				}
				if($tipoUsuario == 5)///es gerente
				{
					$queryUsuarios = "select USER_SNR from kloc_reg k, users u
						where reg_snr = '".$_POST['idUsuario']."'
						and k.REC_STAT = 0 
						and KLOC_SNR = USER_SNR
						and u.REC_STAT = 0
						and u.USER_TYPE = 4
						order by u.LNAME ";
				}else///admin o cualquier otro usuario
				{
					$queryUsuarios = "select USER_SNR from users where USER_TYPE = 4 and rec_stat = 0 ";
				}
				//echo $queryUsuarios."<br>";
				if($tipoUsuario != 4){
					$ids = '';
					$rsids = sqlsrv_query($conn, $queryUsuarios);
					while($rowId = sqlsrv_fetch_array($rsids)){
						$ids .= $rowId['USER_SNR']."','";
					}
					$ids = substr($ids, 0, -3);
					$idRutas = $ids;
				}
			}
		}
	
		$tabla = '<table id="tblPlanesInicio" border="0" >
					<thead>
						<tr>';
		if($todasRutas){
			if($tipoUsuario != 4){
				$tabla .= '<th style="width:10%;">Ruta</th>
				<th style="width:50%;">Datos del médico</th>
				<th class="align-center" style="width:20%;">Fecha Plan</th>
				<th class="align-center" style="width:20%;">Hora Plan</th>';
			}else{
				$tabla .= '<th style="width:60%;">Datos del médico</th>
				<th class="align-center" style="width:20%;">Fecha Plan</th>
				<th class="align-center" style="width:20%;">Hora Plan</th>';
			}
		}else{
			$tabla .= '<th style="width:60%;">Datos del médico</th>
			<th class="align-center" style="width:20%;">Fecha Plan</th>
			<th class="align-center" style="width:20%;">Hora Plan</th>';
		}
			$tabla .= '</tr>
					</thead>
					<tbody class="pointer">';
		$queryPlanes = "select 'p' as tipo, 
			vpp.VPERSPLAN_SNR as idPlan, 
			p.fname + ' / ' + p.LNAME + ' ' + p.NAME_FATHER as nombre, 
			cl.NAME as especialidad, 
			i.street1, t.ZIP, t.name as colonia, brick.NAME as brick, 
			vpp.DATUM, vpp.VRIJEME, u.lname as ruta
			from VISPERSPLAN vpp
			and vpp.USER_SNR = vpp.USER_SNR 
			and p.PERS_SNR = vpp.PERS_SNR
			where vpp.USER_SNR in ('".$idRutas."')
			and vpp.DATUM = '".date("Y-m-d")."'
			and vpp.USER_SNR = vpp.USER_SNR 
			and p.PERS_SNR = vpp.PERS_SNR
			union 
			select 'i' as tipo, vpp.VISINSTPLAN_SNR as idPlan, i.name as nombre, '' as especialidad, 
			i.street1, t.ZIP, t.name as colonia, brick.NAME as brick, vpp.DATUM, vpp.VRIJEME, u.lname as ruta 
			from VISINSTPLAN vpp 
			inner join INST i on vpp.INST_SNR = i.INST_SNR 
			inner join CITY t on i.CITY_SNR = t.CITY_SNR 
			inner join IMS_BRICK brick on i.IMSBRICK_SNR = brick.IMSBRICK_SNR 
			inner join USERS u on vpp.USER_SNR = u.USER_SNR 
			where vpp.USER_SNR in ('".$idRutas."') 
			and vpp.DATUM = '".date("Y-m-d")."'
			order by VRIJEME";
		
		//echo $queryPlanes."<br>";
		
		$rsPlanes = sqlsrv_query($conn, $queryPlanes, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		while($regPlanes = sqlsrv_fetch_array($rsPlanes)){
			foreach ($regPlanes["DATUM"] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fechaPlan = substr($val, 8, 2)."/".substr($val, 5, 2)."/".substr($val, 0, 4);
				}
			}
			$tabla .= '<tr>';
				if($todasRutas){
					if($tipoUsuario != 4){
						$arrRuta = explode("-", $regPlanes['ruta']);
						$tabla .= '<td style="width:10%;">'.$arrRuta[0].'</td>
							<td style="width:50%;"><b>'.$regPlanes["nombre"].'<br>
								'.$regPlanes["especialidad"].'</b><br>
								'.$regPlanes["street1"].'<br>
								'.$regPlanes["ZIP"].' '.$regPlanes["colonia"].'<br>
								'.$regPlanes["brick"].'
							</td>
							<td style="width:20%;" align="center">'.$fechaPlan.'</td>
							<td style="width:20%;" align="center">'.$regPlanes["VRIJEME"].'</td>';
					}else{
						$tabla .= '<td style="width:60%;"><b>'.$regPlanes["nombre"].'<br>
								'.$regPlanes["especialidad"].'</b><br>
								'.$regPlanes["street1"].'<br>
								'.$regPlanes["ZIP"].' '.$regPlanes["colonia"].'<br>
								'.$regPlanes["brick"].'
							</td>
							<td style="width:20%;" align="center">'.$fechaPlan.'</td>
							<td style="width:20%;" align="center">'.$regPlanes["VRIJEME"].'</td>';
					}
				}else{
					$tabla .= '<td style="width:60%;"><b>'.$regPlanes["nombre"].'<br>
								'.$regPlanes["especialidad"].'</b><br>
								'.$regPlanes["street1"].'<br>
								'.$regPlanes["ZIP"].' '.$regPlanes["colonia"].'<br>
								'.$regPlanes["brick"].'
							</td>
							<td style="width:20%;" align="center">'.$fechaPlan.'</td>
							<td style="width:20%;"align="center">'.$regPlanes["VRIJEME"].'</td>';
				}
			$tabla .= '</tr>';
		}
		$tabla .= '</tbody>
			<tfoot>
				<tr>
					<td>
						Total: '.sqlsrv_num_rows($rsPlanes).' 
					</td>
				</tr>
			</tfoot>
		</table>';
		echo $tabla;
	}
	
?>