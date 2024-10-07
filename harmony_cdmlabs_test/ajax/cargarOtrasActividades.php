<?php
	include "../conexion.php";
	
	$idOA = $_POST['id'];
	$planVisita = $_POST['planVisita'];
	
	if($planVisita == 'plan'){
		$qOA = "select 
			vp.DAYPLAN_SNR as idOA, 
			vp.DATE as fecha, 
			vp.info, 
			vc.DAY_CODE as actividad, 
			vc.DAYCODE_WEIGHT as horas,
			u.lname + ' ' + mothers_lname + ' ' + fname as repre,
			u.user_snr
			from VISDAYPLAN vp, VISDAYPLAN_CODE vc, users u 
			where vp.DAYPLAN_SNR = vc.VISDAYPLAN_SNR ";
		if($idOA == ''){
			$fecha = $_POST['fecha'];
			$idUsuario = $_POST['idUsuario'];
			$qOA .= " and vp.DATE = '".$fecha."' and vp.user_snr = '".$idUsuario."' ";
		}else{
			$qOA .= " and vp.DAYPLAN_SNR = '".$idOA."' ";
		}
			$qOA .= " and u.user_snr = vp.user_snr";	
	}else{
		$qOA = "select vp.DAYREPORT_SNR as idOA, vp.START_DATE as fecha, vp.info,
			vc.DAY_CODE_SNR as actividad, 
			vc.VALUE as horas,
			u.lname + ' ' + mothers_lname + ' ' + fname as repre,
			u.user_snr
			from DAY_REPORT vp, DAY_REPORT_CODE vc, users u 
			where vp.DAYREPORT_SNR = vc.DAYREPORT_SNR ";
		if($idOA == ''){
			$fecha = $_POST['fecha'];
			$idUsuario = $_POST['idUsuario'];
			$qOA .= " and '".$fecha."' between vp.START_DATE and vp.FINISH_DATE and vp.user_snr = '".$idUsuario."' ";
		}else{
			$qOA .= "and vp.DAYREPORT_SNR = '".$idOA."' ";
		}
			
		$qOA .= "and u.user_snr = vp.user_snr and vc.REC_STAT = 0 and vp.rec_stat = 0";
	}
	//echo $qOA."<br>";
	$rsOA = sqlsrv_query($conn, $qOA);
	$i=1;
	$arrActividades = array();
	$arrHoras = array();
	echo "<script>";
	while($regOA = sqlsrv_fetch_array($rsOA)){
		if($i == 1){
			foreach ($regOA['fecha'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha = substr($val, 8, 2)."/".substr($val, 5, 2)."/".substr($val, 0, 4);
				}
			}
			echo "$('#hdnIdOA').val('".$regOA['idOA']."');
				$('#hdnIdUsuarioOA').val('".$regOA['user_snr']."');
				$('#txtComentariosOtrasActividades').val('".$regOA['info']."');
				$('#txtFechaReportarOtrasActividades').val('".$fecha."')
				$('#sltMultiSelectOA').prop('disabled', true);
				$('#sltMultiSelectOA').text('".utf8_encode($regOA['repre'])."'); 
				$('#txtFechaReportarOtrasActividades').prop('disabled', true);
				$('#btnGuardarPeriodo').prop('disabled', true);";
		}
		$arrActividades[] = $regOA['actividad'];
		$arrHoras[] = $regOA['horas'];
		$i++;
	}
	
	for($k=0;$k<count($arrActividades);$k++){
		echo "
			for(i=1;i<=$('#hdnTotalChkOA').val();i++){
				if($('#chkOA'+i).val() == '".$arrActividades[$k]."'){
					$('#chkOA'+i).prop('checked', true);
					$('#txtOA'+i).val('".$arrHoras[$k]."');
				}
			}";
	}
	echo "$('#txtTotalActividades').val('".array_sum($arrHoras)."');
	</script>";
	/*print_r($arrActividades);
	echo "<br><br>";
	print_r($arrHoras);*/
?>