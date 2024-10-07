<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idPlan = $_POST['idPlan'];
		$qDatos = "select i.INST_SNR, vp.USER_SNR,
			vp.vispers_snr as idVisita 
			from visinstplan vp
			inner join inst i on vp.inst_SNR = i.INST_SNR
			where vp.VISinstPLAN_SNR = '".$idPlan."'";
		
		$datos = sqlsrv_fetch_array(sqlsrv_query($conn, $qDatos));
		$atualiza = "update visinstplan set sync = 0, rec_stat = 2 where visinstplan_snr = '".$idPlan."'";
		if($datos['idVisita'] != ''){
			$actualizaVisita = "update visitinst set 
				visinstplan_snr = '00000000-0000-0000-0000-000000000000', 
				sync = 0
				where visinst_snr = '".$datos['idVisita']."'";
			if(! sqlsrv_query($conn, $actualizaVisita)){
				echo "<script>alert('La visita no se eliminó');</script>";
			}
		}
		echo "<script>";
		if(sqlsrv_query($conn, $atualiza)){
			echo "alertEliminarPlanOk();
				if($('#divCalendario').is (':visible')){
					actualizaCalendario();
				}else{
					presentaDatos('idInstTabs','".$datos['INST_SNR']."','divDatosInstituciones','','".$datos['USER_SNR']."');
				}
				$('#divPlanesInst').hide();
				$('#divCapa3').hide();
				$('#lkPlanInst').click();
			";
		}else{
			echo "alertEliminarPlanError();";
		}
		echo "</script>";
	}
	
?>