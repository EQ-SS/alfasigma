<?php
	include "conexion.php";


	$queryTipoUsuario = "select * from users where rec_stat = 0 and user_snr = '".$_GET['idUser']."'";
	$rsTipoUsuario = sqlsrv_query($conn, $queryTipoUsuario, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	if(sqlsrv_num_rows($rsTipoUsuario) > 0){
		while($row = sqlsrv_fetch_array($rsTipoUsuario)){
			$tipoUsuario = $row['USER_TYPE'];
		}
		if($tipoUsuario == 4)///es representante
		{
			$ids = $_GET['idUser'];
		}else if($tipoUsuario == 5)///es gerente
		{
			$queryUsuarios = "select USER_SNR from kloc_reg k, users u
					where reg_snr = '".$_GET['idUser']."'
					and k.REC_STAT = 0 
					and KLOC_SNR = USER_SNR
					and u.REC_STAT = 0
					and u.USER_TYPE = 4
					order by u.LNAME ";
		}else///admin o cualquier otro usuario
		{
			$queryUsuarios = "select USER_SNR from users where USER_TYPE in (4,5) and rec_stat = 0 ";
		}
		//echo $queryUsuarios"<br>";
		if($tipoUsuario != 4){
			$ids = '';
			$rsids = sqlsrv_query($conn, $queryUsuarios);
			while($rowId = sqlsrv_fetch_array($rsids)){
				$ids .= $rowId['USER_SNR']."','";
			}
			$ids = substr($ids, 0, -3);
		}
	}else{
		die('El usuario no es válido!!!');
	}
?>
<html>
	<head>
	</head>
	<body>
		<table id="tblPlanesInicio" border="0" >
			<thead>
				<tr>
				<th style="width:60%;">Datos del médico</th>
				<th class="align-center" style="width:20%;">Fecha Plan</th>
				<th class="align-center" style="width:20%;">Hora Plan</th>
				</tr>
			</thead>
			<tbody class="pointer">
			<?php

				$queryPlanes ="select 'p' as tipo, vpp.VISPERSPLAN_SNR as idPlan, p.fname + ' / ' + p.LNAME + ' ' + p.mothers_lname as nombre, cl.NAME as especialidad, i.street1, t.ZIP, 
				t.name as colonia, brick.NAME as brick, vpp.PLAN_DATE AS DATUM, vpp.TIME AS VRIJEME, 
				u.lname as ruta, 
				vpp.vispers_snr as idVisita 
				from VISPERSPLAN vpp 
				left outer join person p on vpp.PERS_SNR = p.PERS_SNR 
				left outer join CODELIST cl on p.SPEC_SNR = cl.CLIST_SNR 
				left outer join PERSLOCWORK plw on plw.PWORK_SNR = vpp.PWORK_SNR and plw.REC_STAT = 0
				left outer join INST i on plw.INST_SNR = i.INST_SNR 
				left outer join CITY t on i.CITY_SNR = t.CITY_SNR 
				left outer join BRICK brick on t.BRICK_SNR = brick.BRICK_SNR 
				left outer join USERS u on vpp.USER_SNR = u.USER_SNR 
				where vpp.USER_SNR in ('".$ids."')
				and vpp.PLAN_DATE = '".date("Y-m-d")."'
				and vpp.USER_SNR = vpp.USER_SNR 
				and p.PERS_SNR = vpp.PERS_SNR
				union
				select 'i' as tipo, vpp.VISINSTPLAN_SNR as idPlan, i.name as nombre, '' as especialidad,  i.street1, t.ZIP,
				t.name as colonia, brick.NAME as brick, vpp.PLAN_DATE AS DATUM, vpp.TIME AS VRIJEME, u.lname as ruta, 
				vpp.VISINST_SNR as idVisita 
				from VISINSTPLAN vpp
				inner join INST i on vpp.INST_SNR = i.INST_SNR 
				inner join CITY t on i.CITY_SNR = t.CITY_SNR
				inner join BRICK brick on t.BRICK_SNR = brick.BRICK_SNR
				inner join USERS u on vpp.USER_SNR = u.USER_SNR 
				where vpp.USER_SNR in ('".$ids."')
				and vpp.PLAN_DATE = '".date("Y-m-d")."'
				order by VRIJEME ";
						
												
				$rsPlanes = sqlsrv_query($conn, $queryPlanes, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
					while($regPlanes = sqlsrv_fetch_array($rsPlanes)){
						foreach ($regPlanes["DATUM"] as $key => $val) {
							if(strtolower($key) == 'date'){
								$fechaPlan = substr($val, 8, 2)."/".substr($val, 5, 2)."/".substr($val, 0, 4);
							}
						}
						echo '<tr>
							<td style="width:60%;"><b>'.$regPlanes["nombre"].'<br>
								'.$regPlanes["especialidad"].'</b><br>
								'.$regPlanes["street1"].'<br>
								'.$regPlanes["ZIP"].' '.$regPlanes["colonia"].'<br>
								'.$regPlanes["brick"].'
							</td>
							<td class="align-center" style="width:20%;">'.$fechaPlan.'</td>
							<td class="align-center" style="width:20%;">'.$regPlanes["VRIJEME"].'</td>
							</tr>';
					}
			?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" class="text-center font-bold">
						Total: <?= sqlsrv_num_rows($rsPlanes) ?>
					</td>
				</tr>
			</tfoot>
		</table>
		<script>
			window.print();
		</script>
	</body>
</head>