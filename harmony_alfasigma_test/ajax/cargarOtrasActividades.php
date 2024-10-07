<?php
	include "../conexion.php";
	
	$idOA = $_POST['id'];
	$planVisita = $_POST['planVisita'];
	$tipoUsuario = $_POST['tipoUsuario'];
	//echo $tipoUsuario;
	
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
		
		$qOA = "select vp.DAYREPORT_SNR as idOA, vp.DATE as fecha, vp.info,
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
	

	$info="";
	while($regOA = sqlsrv_fetch_array($rsOA)){
		$info=$regOA['info'];
		$info = str_replace(array("\r", "\n"), '', $info);

		if($i == 1){
			foreach ($regOA['fecha'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha = substr($val, 8, 2)."/".substr($val, 5, 2)."/".substr($val, 0, 4);
				}
			}
			echo "$('#hdnIdOA').val('".$regOA['idOA']."');
				$('#hdnIdUsuarioOA').val('".$regOA['user_snr']."');
				$('#txtAreaReportarOtrasActividades').val('".$info."');
				$('#txtFechaReportarOtrasActividades').val('".$fecha."');
				$('#sltMultiSelectOA').prop('disabled', true);
				$('#sltMultiSelectOA').text('".$regOA['repre']."'); 
				$('#txtFechaReportarOtrasActividades').prop('disabled', true);
				$('#btnGuardarPeriodo').prop('disabled', true);
				
				for(i=1;i<=$('#hdnTotalChkOA').val();i++){
					$('#chkOA'+i).prop('checked', false);
				}";
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

	if($idOA == ''){
		echo "<script>$('#txtAreaReportarOtrasActividades').val('');
		$('#sltMultiSelectOA').prop('disabled', false);
		$('#txtFechaReportarOtrasActividades').prop('disabled', false);
		$('#btnGuardarPeriodo').prop('disabled', false);
		$('#sltMultiSelectOA').text(''); 
		
		for(i=1;i<=$('#hdnTotalChkOA').val();i++){
			$('#chkOA'+i).prop('checked', false);
		}
		
		</script>";
		echo "<script>";
		for($k=0;$k<count($arrActividades);$k++){
			echo "
				for(i=1;i<=$('#hdnTotalChkOA').val();i++){
					if($('#chkOA'+i).val() == '".$arrActividades[$k]."'){
						
						$('#txtOA'+i).val('');
					}
				}";
		}
		echo "</script>";
	}

	if($tipoUsuario==2 || $tipoUsuario==5  && $idOA !=""){
		echo "<script>
		$('#txtFechaReportarOtrasActividades').prop('disabled', false);
		</script>";


	}
	/*print_r($arrActividades);
	echo "<br><br>";
	print_r($arrHoras);*/
?>