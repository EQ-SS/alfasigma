<?php
	include "../conexion.php";
	$fechaIni = $_POST['fechaIni'];
	$fechaFin = $_POST['fechaFin'];
	$visitados = $_POST['visitados'];
	$idUsuario = $_POST['idUsuario'];
	$ids = $_POST['ids'];
	$queryPlanes = "select vp.datum,
			vp.vrijeme,
			p.fname + ' ' + p.lname + ' ' + p.name_father as nombre,
			vp.info
			from VISPERSPLAN vp, person p
			where vp.REC_STAT = 0 
			and p.REC_STAT = 0
			and p.pers_snr = vp.pers_snr 
			and vp.datum >= '".$fechaIni."'
			and vp.datum <= '".$fechaFin."'  
			and vp.user_snr in ('".$ids."') ";
			
	if($visitados == 'visitados'){
		$queryPlanes .= "and vp.vispers_snr <> '00000000-0000-0000-0000-000000000000' ";
	}else if($visitados == 'novisitados') {
		$queryPlanes .= "and vp.vispers_snr = '00000000-0000-0000-0000-000000000000' ";
	}
	
	$queryPlanes .="union
			select vp.datum,
			vp.vrijeme,
			p.name as nombre,
			vp.info
			from VISINSTPLAN vp, INST p
			where vp.REC_STAT = 0 
			and p.REC_STAT = 0
			and p.inst_snr = vp.inst_snr 
			and vp.datum >= '".$fechaIni."'
			and vp.datum <= '".$fechaFin."'  
			and vp.user_snr in ('".$ids."')";
			
	if($visitados == 'visitados'){
		$queryPlanes .= "and vp.VISINST_SNR <> '00000000-0000-0000-0000-000000000000' ";
	}else if($visitados == 'novisitados') {
		$queryPlanes .= "and vp.VISINST_SNR = '00000000-0000-0000-0000-000000000000' ";
	}

	$queryPlanes .= "order by datum, vrijeme, nombre "; 
	
	//echo $queryPlanes;
	
	$rsPlanes = sqlsrv_query($conn, $queryPlanes, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	
	echo "<script>
		$('#tblCopiarPlanes tbody').empty();
		$('#tblCopiarPlanes tfoot').empty();";
		
	while($plan = sqlsrv_fetch_array($rsPlanes)){
		foreach ($plan['datum'] as $key => $val) {
			if(strtolower($key) == 'date'){
				$fecha = substr($val, 0, 10);
			}
		}
		echo "$('#tblCopiarPlanes tbody').append('<tr><td width=\"60px\">".$fecha."</td><td width=\"60px\">".$plan['vrijeme']."</td><td width=\"240px\">".$plan['nombre']."</td><td width=\"240px\">".$plan['info']."</td></tr>');";
	}
	echo "$('#tblCopiarPlanes tfoot').append('<tr><td width=\"600px\" colspan=\"4\">".sqlsrv_num_rows($rsPlanes)."</td></tr>');
	</script>";
?>