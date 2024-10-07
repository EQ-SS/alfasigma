<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idVisita = $_POST['idVisita'];
		$qDatos = "select p.PERS_SNR, 
			p.LNAME + ' ' + p.MOTHERS_LNAME + ' ' + p.FNAME as nombre, 
			c.name as especialidad,
			vp.VISPERSPLAN_SNR as idPlan
			from visitpers vp
			inner join person p on vp.PERS_SNR = p.PERS_SNR
			left outer join CODELIST c on c.CLIST_SNR = p.SPEC_SNR
			where vp.VISPERS_SNR = '".$idVisita."' ";
		$datos = sqlsrv_fetch_array(sqlsrv_query($conn, $qDatos));
		$atualiza = "update visitpers set 
			sync = 0, 
			rec_stat = 2 
			where vispers_snr = '".$idVisita."'";
			
		if($datos['idPlan'] != '00000000-0000-0000-0000-000000000000'){
			$actualizaPlan = "update vispersplan set 
				vispers_snr = '00000000-0000-0000-0000-000000000000',
				sync = 0
				where vispersplan_snr = '".$datos['idPlan']."' ";
				
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
					presentaDatos(idmedico,'".$datos['PERS_SNR']."','divDatosPersonales','".$datos['nombre']."','".$datos['especialidad']."');
				}
				$('#divVisitas').hide();
				$('#divCapa3').hide();
				$('#lkVisitas').click();
			";
		}else{
			echo "alertEliminarVisitaError();";
		}
		echo "</script>";
	}
	
?>