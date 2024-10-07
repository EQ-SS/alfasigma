<?php
	include "conexion.php";
	include_once('../clases/PHP_XLSXWriter/xlsxwriter.class.php'); /*you can get xlsxwriter.class.php from given GitHub link*/
	$arreglo = array();
	$arreglo[] = array('Datos del médico', 'Fecha Plan', 'Hora Plan');
	
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

	$queryPlanes = "select vpp.VISPERSPLAN_SNR, p.fname + ' / ' + p.LNAME + ' ' + p.mothers_lname as nombre, cl.NAME as especialidad, i.street1, 
		t.ZIP, t.name as colonia, brick.NAME as brick, vpp.PLAN_DATE, vpp.TIME from VISPERSPLAN vpp 
		inner join person p on vpp.PERS_SNR = p.PERS_SNR 
		inner join CODELIST cl on p.SPEC_SNR = cl.CLIST_SNR 
		inner join PERS_SREP_WORK psw on vpp.USER_SNR = psw.USER_SNR 
		inner join INST i on psw.INST_SNR = i.INST_SNR 
		inner join CITY t on i.CITY_SNR = t.CITY_SNR 
		inner join BRICK brick on brick.BRICK_SNR =t.BRICK_SNR
		where vpp.USER_SNR in ('".$ids."')
		and vpp.PLAN_DATE = '".date("Y-m-d")."'
		and vpp.USER_SNR = psw.USER_SNR
		and p.PERS_SNR = psw.PERS_SNR 
		and vpp.rec_stat = 0
		order by vpp.TIME";
												
	$rsPlanes = sqlsrv_query($conn, $queryPlanes, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	while($regPlanes = sqlsrv_fetch_array($rsPlanes)){
		foreach ($regPlanes["PLAN_DATE"] as $key => $val) {
			if(strtolower($key) == 'date'){
				$fechaPlan = substr($val, 8, 2)."/".substr($val, 5, 2)."/".substr($val, 0, 4);
			}
		}
		$datos = $regPlanes["nombre"]." ".$regPlanes["especialidad"]." ".$regPlanes["street1"]." ".$regPlanes["ZIP"]." ".$regPlanes["colonia"]." ".$regPlanes["brick"];
		$arreglo[] = array($datos, $fechaPlan, $regPlanes["TIME"]);
		
	}
	$arreglo[] = array('Total: '.sqlsrv_num_rows($rsPlanes));
	$writer = new XLSXWriter();
	$writer->writeSheet($arreglo);
	$writer->writeToFile('planes.xlsx');
	header("Location: planes.xlsx");
?>