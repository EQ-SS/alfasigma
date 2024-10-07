<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idPlan = $_POST['idPlan'];
		$fechaPlan = $_POST['fechaPlan'];
		$horaPlan = $_POST['horaPlan'];
		$codigoPlan = (empty($_POST['codigoPlan'])) ? '00000000-0000-0000-0000-000000000000' : $_POST['codigoPlan'] ;
		$objetivoPlan = strtoupper($_POST['objetivoPlan']);
		$idUsuario = $_POST['idUsuario'];
		$idInst = $_POST['idInst'];
		$idRepre = $_POST['idRepre'];
		$tipoUsuario = $_POST['tipoUsuario'];
		$idUser = $_POST['idUser'];
		
		if($idPlan == ''){
			if($fechaPlan < date("Y-m-d")){
				echo "<script>alertPlaneaFechasAnt();</script>";
				return;
			}
			//echo "select * from visinstplan where plan_date = '$fechaPlan' and inst_snr = '$idInst'<br>";
			$rsExiste = sqlsrv_query($conn, "select * from visinstplan where plan_date = '$fechaPlan' and inst_snr = '$idInst'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsExiste) > 0){
				echo "<script>alertPlanExistente();</script>";
				return;
			}
		}
		
		if($idPlan != ''){
			$query = "update visinstplan set 
				plan_date = '$fechaPlan',
				time = '$horaPlan',
				plan_code_snr = '$codigoPlan',
				info = '$objetivoPlan'
				where visinstplan_snr = '$idPlan'";
		}else{
			$queryIdPlan = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPlan from VISINSTPLAN  where visinstplan_snr = '00000000-0000-0000-0000-000000000000'"));
			$idPlan = $queryIdPlan['idPlan'];
			$query = "insert into visinstplan (
				VISINSTPLAN_SNR,
				USER_SNR,
				plan_date,
				time,
				INFO,
				INST_SNR,
				REC_STAT,
				creation_timestamp,
				plan_code_snr,
				SYNC ) values(
				'$idPlan',
				'$idUsuario',
				'$fechaPlan',
				'$horaPlan',
				'$objetivoPlan',
				'$idInst',
				0,
				getdate(),
				'$codigoPlan',
				0)";
		}
		//echo $query;
		if(sqlsrv_query($conn, $query)){
			echo "<script>";
			if(!isset($_POST['vis'])){
				//echo "alert('Registro Guardado!!!');";
				$queryPlanes = "select plan_date as Fecha_Plan,
					vp.time as Hora_Plan, u.user_nr,
					u.lname+' '+u.fname as Rep, 
					cd.name as Tipo_Plan,vp.info as Objetivo, 
					vp.visinstplan_snr,vp.user_snr,vp.inst_snr, 
					(select info from visitinst where visitinst.inst_snr=vp.inst_snr and visit_date in (select max(visit_date) from visitinst))  info_Ult_Vis, 
					substring(c.name, 1, 4) +  substring(c.name, 8, 3) as ciclo
					from visinstplan vp
					inner join users u on vp.user_snr=u.user_snr 
					left outer join codelist cd on cd.clist_snr = vp.plan_code_snr
					left outer join cycles c on vp.plan_date between c.START_DATE and c.FINISH_DATE
					where vp.rec_stat=0 ";
					if($tipoUsuario == 4){
						$queryPlanes .= "and u.user_snr = '".$idUser."' ";
					}else{
						if($idRepre != ''){
							$queryPlanes .= "and u.user_snr in ('".$idUser."','".$idRepre."')";
						}else{
							$queryPlanes .= "and u.user_snr in ('".$idUser."')";
						}
					}
					$queryPlanes .= "and vp.INST_SNR = '".$idInst."' 
					order by fecha_plan desc ";
			
					//echo $queryPlanes;
			
					$rsPlanes = sqlsrv_query($conn, $queryPlanes);
					
				echo "$('#tblPlanesInstituciones tbody').empty();";
					
				while($plan = sqlsrv_fetch_array($rsPlanes)){
					foreach ($plan['Fecha_Plan'] as $key => $val) {
						if(strtolower($key) == 'date'){
							$fecha_plan = substr($val, 0, 10);
						}
					}
			
					//echo "$('#tblPlanesInstituciones tbody').append('<tr onClick=\"muestraPlanInst(\'".$plan['visinstplan_snr']."\');\"><td style=\"width:15%;\">".$fecha_plan."</td><td style=\"width:10%;\">".$plan['Hora_Plan']."</td><td style=\"width:25%;\">".utf8_encode($plan['Rep'])."</td><td style=\"width:25%;\">".utf8_encode($plan['Tipo_Plan'])."</td><td style=\"width:15%;\">".$plan['Objetivo']."</td><td style=\"width:10%;\">".$plan['ciclo']."</td></tr>');";
					echo "$('#tblPlanesInstituciones tbody').append('<tr onClick=\"muestraPlanInst(\'".$plan['visinstplan_snr']."\');\"><td style=\"width:15%;\">".$plan['ciclo']."</td><td style=\"width:10%;\">".$plan['user_nr']."</td><td style=\"width:15%;\">".$fecha_plan."</td><td style=\"width:15%;\">".$plan['Hora_Plan']."</td><td style=\"width:45%;\">".$plan['Objetivo']."</td></tr>');";
				}
				
				$queryCiclos = "select substring(ciclos.name,1,4) anio, 
					substring(ciclos.name,6,2) ciclo, 
					count(*) total 
					from visinstplan vp, cycles ciclos 
					where vp.plan_date between ciclos.start_date and ciclos.finish_date 
					and vp.rec_stat=0 ";
					if($tipoUsuario == 4){
						$queryCiclos .= "and vp.user_snr = '".$idUser."' ";
					}else{
						if($idRepre != ''){
							$queryCiclos .= "and vp.user_snr in ('".$idUser."','".$idRepre."')";
						}else{
							$queryCiclos .= "and vp.user_snr in ('".$idUser."')";
						}
					}
					$queryCiclos .= "and vp.inst_snr='".$idInst."' 
					and substring(ciclos.name,1,4) = (select max(substring(name,1,4)) from cycles) 
					group by ciclos.name";
				
					$rsCiclos = sqlsrv_query($conn, $queryCiclos);
					
					for($i=1;$i<14;$i++){
						echo "$('#cicloInst'+".$i.").text('0');";
					}
					$total = 0;
					while($ciclo = sqlsrv_fetch_array($rsCiclos)){
						echo "$('#cicloInst'+".ltrim($ciclo['ciclo'], '0').").text('".$ciclo['total']."');";
						$total += $ciclo['total'];
					}
					echo "$('#acumuladoInst').text('".$total."');";
			}else{
				//echo "muestraVisitaInst('','$idPlan');";
				echo '$("#divPlanesInst").hide();
				$("#divVisitasInst").show();';
			}
			if(isset($_POST['pantalla']) && $_POST['pantalla'] == 'cal'){
				echo " actualizaCalendario(); ";
			}
			if(!isset($_POST['vis'])){
				
				echo '
				if($("#hdnRegresoInst").val() == "visita"){
					$("#divVisitasInst").show();
					$("#divPlanesInst").hide();
				}else{
					$("#divPlanesInst").hide();
					$("#divCapa3").hide();
					notificationPlanGuardado();
				}';
				//echo "window.close();";
			}
			/*echo "$('#divPlanesInst').hide();
				$('#divCapa3').hide();*/
			echo "</script>";
		}else{
			echo "<script>alertErrorGuardarRegistro();</script>";
		}
		//echo $query;
	}
	
?>