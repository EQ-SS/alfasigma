<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idPlan = $_POST['idPlan'];
		$qDatos = "select p.PERS_SNR, p.LNAME + ' ' + p.MOTHERS_LNAME + ' ' + p.FNAME as nombre, 
			c.name as especialidad,
			vp.vispers_snr as idVisita
			from vispersplan vp
			inner join person p on vp.PERS_SNR = p.PERS_SNR
			left outer join CODELIST c on c.CLIST_SNR = p.SPEC_SNR
			where vp.VISPERSPLAN_SNR = '".$idPlan."' ";
		$datos = sqlsrv_fetch_array(sqlsrv_query($conn, $qDatos));
		$atualiza = "update vispersplan set sync = 0, rec_stat = 2 where vispersplan_snr = '".$idPlan."'";
		if($datos['idVisita'] != ''){
			$actualizaVisita = "update visitpers set 
				vispersplan_snr = '00000000-0000-0000-0000-000000000000', 
				sync = 0
				where vispers_snr = '".$datos['idVisita']."'";
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
					presentaDatos(idmedico,'".$datos['PERS_SNR']."','divDatosPersonales','".$datos['nombre']."','".$datos['especialidad']."');
					
				}
				$('#divPlanes').hide();
				$('#divCapa3').hide();
				$('#lkPlanPersona').click();
				
			";
		}else{
			echo "alertEliminarPlanError();";
		}
		echo "</script>";
	}
	
?>