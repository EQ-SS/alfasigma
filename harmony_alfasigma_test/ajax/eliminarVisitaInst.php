<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idVisita = $_POST['idVisita'];
		$qDatos = "select i.INST_SNR, vp.USER_SNR, vp.visinstplan_snr as idPlan 
			from visitinst vp
			inner join inst i on vp.inst_SNR = i.INST_SNR
			where vp.VISinst_SNR = '".$idVisita."'";
		
		$datos = sqlsrv_fetch_array(sqlsrv_query($conn, $qDatos));
		$atualiza = "update visitinst set sync = 0, rec_stat = 2 where VISinst_SNR = '".$idVisita."'";
		if($datos['idPlan'] != ''){
			$actualizaPlan = "update VISINSTPLAN set 
				visinst_snr = '00000000-0000-0000-0000-000000000000',
				sync = 0
				where visinstplan_snr = '".$datos['idPlan']."' ";
				
			if(! sqlsrv_query($conn, $actualizaPlan)){
				echo "<script>alertEliminarPlanError();</script>";
			}
		}
		echo "<script>";
		if(sqlsrv_query($conn, $atualiza)){
			echo "alertEliminarVisitaOk();
				if($('#divCalendario').is (':visible')){
					actualizaCalendario();
				}else{
					presentaDatos('idInstTabs','".$datos['INST_SNR']."','divDatosInstituciones','','','".$datos['USER_SNR']."');
				}
				$('#divVisitasInst').hide();
				$('#divCapa3').hide();
				$('#lkVisitInst').click();
			";
		}else{
			echo "alertEliminarVisitaError();";
		}
		echo "</script>";
	}
	
?>