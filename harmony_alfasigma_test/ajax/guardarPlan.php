<?php
	include "../conexion.php";
	//echo "pantalla".$_POST['pantalla'];
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idPlan = $_POST['idPlan'];
		$fechaPlan = $_POST['fechaPlan'];
		$horaPlan = $_POST['horaPlan'];
		$codigoPlan = (empty($_POST['codigoPlan'])) ? '00000000-0000-0000-0000-000000000000' : $_POST['codigoPlan'] ;
		$objetivoPlan = strtoupper($_POST['objetivoPlan']);
		
		$idPersona = $_POST['idPersona'];
		$idUsuario = $_POST['idUsuario'];
		
		$ruta = $_POST['ruta'];
		$tipoUsuario = $_POST['tipoUsuario'];
		$idUser = $_POST['idUser'];
		
		$rsDatos = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from PERS_SREP_WORK where pers_snr = '$idPersona' and REC_STAT = 0"));
		$rsExiste = sqlsrv_query($conn, "select * from vispersplan where plan_date = '$fechaPlan' and pers_snr = '$idPersona' and rec_stat = 0", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		//echo "select * from vispersplan where datum = '$fechaPlan' and pers_snr = '$idPersona'";
		
		//valida si es domingo
		if(date('N', strtotime($fechaPlan)) == 7){
			echo "<script>alertPlaneaDomingo();
				$('#btnGuardarPlan').prop('disabled', false);
				</script>";
			return;
		}
		
		if($idPlan == ''){
			if($fechaPlan < date("Y-m-d")){
				echo "<script>alertPlaneaFechasAnt();
					$('#btnGuardarPlan').prop('disabled', false);
				</script>";
				return;
			}
			if(sqlsrv_num_rows($rsExiste) > 0){
				echo "<script>
					alertPlanExistente();
					$('#btnGuardarPlan').prop('disabled', false);
					</script>";
				return;
			}
		}else{
			$rsDatosPlan = sqlsrv_query($conn, "select * from vispersplan where VISPERSPLAN_SNR = '$idPlan'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			//echo "select * from vispersplan where VISPERSPLAN_SNR = '$idPlan'";
			$arrPlanTabla = sqlsrv_fetch_array($rsDatosPlan);
			$idPlanTabla = $arrPlanTabla['VISPERSPLAN_SNR'];
			//echo $arrPlanTabla['DATUM']."<br>";
			if(is_object($arrPlanTabla['PLAN_DATE'])){
				foreach ($arrPlanTabla['PLAN_DATE'] as $key => $val) {
					if(strtolower($key) == 'date'){
						$idFechaPlanTabla = substr($val, 0, 10);
					}
				}
			}
			
			/*if($fechaPlan < date("Y-m-d") && $idFechaPlanTabla != $fechaPlan){
				echo "<script>alert('No se puede planear en fechas anteriores al día de hoy!!!');</script>";
				return;
			}*/
			
			if(sqlsrv_num_rows($rsExiste) > 0 && $idFechaPlanTabla != $fechaPlan){
				echo "<script>alertPlanExistente();</script>";
				return;
			}
		}
		
		if($idPlan != ''){//checar
			if(! isset($_POST['vis'])){
				$query = "update vispersplan set 
					plan_date = '$fechaPlan',
					time = '$horaPlan',
					plan_code_snr = '$codigoPlan',
					info = '$objetivoPlan'
					where vispersplan_snr = '$idPlan'";
			}else{
				$query = "";
			}
		}else{
			$queryIdPlan = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPlan from VISPERSPLAN  where vispersplan_snr = '00000000-0000-0000-0000-000000000000'"));
			$idPlan = $queryIdPlan['idPlan'];
			/*if($rsDatos['PWORK_SNR'] == '0'){
				$rsDatos['PWORK_SNR'] = '00000000-0000-0000-0000-000000000000';
			}*/
			$query = "insert into vispersplan (
				VISPERSPLAN_SNR,
				PLAN_DATE,
				TIME,
				PERS_SNR,
				USER_SNR,
				PWORK_SNR,
				INFO,
				VISPERS_SNR,
				REC_STAT,
				PLAN_CODE_SNR,
				CREATION_TIMESTAMP,
				PERS_APPROVAL_SNR,
				ESCORT_SNR,
				SYNC,
				CHANGED_TIMESTAMP,
				SYNC_TIMESTAMP)
			values(
				'$idPlan',
				'$fechaPlan',
				'$horaPlan',
				'$idPersona',
				'".$idUsuario."',
				'".$rsDatos['PWORK_SNR']."',
				'$objetivoPlan',
				'00000000-0000-0000-0000-000000000000',
				0,
				'$codigoPlan',
				getdate(),
				'00000000-0000-0000-0000-000000000000',
				'00000000-0000-0000-0000-000000000000',
				0,
				getdate(),
				null)";
			$idUsuario = $rsDatos['USER_SNR'];
			
		}
		if(sqlsrv_query($conn, $query)){
			//echo $idUsuario."<br>";
			echo "<script>";
			//echo "alert('".$_POST['pantalla']."');";
			if(! isset($_POST['vis'])){
				$queryPlanes = "select plan_date as Fecha_Plan, vp.time as Hora_Plan, u.user_nr, u.lname +' '+ u.fname as Rep, cd.name as Tipo_Plan, 
					vp.info as Objetivo, vp.vispersplan_snr, vp.user_snr, vp.pers_snr, 
					(select top 1 info from visitpers where visitpers.pers_snr=vp.pers_snr and creation_timestamp in (select max(creation_timestamp) from visitpers)) as info_Ult_Vis,
					c.NAME
					from vispersplan vp, users u, codelist cd, CYCLES c 
					where cd.clist_snr=vp.PLAN_CODE_SNR  
					and vp.user_snr=u.user_snr 
					and vp.rec_stat=0 ";
					if($tipoUsuario == 4){
						$queryPlanes .= "and vp.user_snr in ('".$idUsuario."') ";
					}else{
						if($ruta != ''){
							$queryPlanes .= "and vp.user_snr in ('".$idUser."','".$ruta."') ";
						}else{
							$queryPlanes .= "and vp.user_snr in ('".$idUser."') ";
						}
					}
					$queryPlanes .= "and vp.pers_snr='".$idPersona."'
					and vp.plan_date between START_DATE and FINISH_DATE
					order by plan_date desc";
			//echo $queryPlanes;
				$rsPlanes = sqlsrv_query($conn, $queryPlanes);
				echo "$('#tblPlan tbody').empty();";
				while($plan = sqlsrv_fetch_array($rsPlanes)){
					foreach ($plan['Fecha_Plan'] as $key => $val) {
						if(strtolower($key) == 'date'){
							$fecha_plan = substr($val, 0, 10);
						}
					}
					
					echo "$('#tblPlan tbody').append('<tr onClick=\"muestraPlan(\'".$plan['vispersplan_snr']."\');\"><td style=\"width:15%;\">".substr($plan['NAME'],0,5).substr($plan['NAME'],8,2)."</td><td style=\"width:10%;\">".$plan['user_nr']."</td><td style=\"width:15%;\">".$fecha_plan."</td><td style=\"width:15%;\">".$plan['Hora_Plan']."</td><td style=\"width:45%;\">".$plan['Objetivo']."</td></tr>');";
				}
				
				$queryCiclos = "select substring(ciclos.name,1,4) anio, 
						substring(ciclos.name,6,2) ciclo, 
						count(*) total 
						from vispersplan vp, cycles ciclos 
						where vp.plan_date between ciclos.start_date and ciclos.finish_date 
						and vp.rec_stat=0 ";
						if($tipoUsuario == 4){
							$queryCiclos .= "and vp.user_snr in ('".$idUsuario."') ";
						}else{
							if($ruta != ''){
								$queryCiclos .= "and vp.user_snr in ('".$idUser."','".$ruta."') ";
							}else{
								$queryCiclos .= "and vp.user_snr in ('".$idUser."') ";
							}
						}
						$queryCiclos .= "and vp.pers_snr='".$idPersona."' 
						and substring(ciclos.name,1,4) = (select max(substring(name,1,4)) from cycles) 
						group by ciclos.name";
						
				//echo $queryCiclos;
						
				$rsCiclos = sqlsrv_query($conn, $queryCiclos);
				
				for($i=1;$i<14;$i++){
					echo "$('#ciclo'+".$i.").text('0');
						$('#cicloVisita'+".$i.").text('0');";
					
				}
				$total = 0;
				while($ciclo = sqlsrv_fetch_array($rsCiclos)){
					echo "$('#ciclo'+".ltrim($ciclo['ciclo'], '0').").text('".$ciclo['total']."');";
					$total += $ciclo['total'];
				}
				echo "$('#acumulado').text('".$total."');";

					
			}else{
				//echo "muestraVisita('','$idPlan');";
				echo '$("#divPlanes").hide();
				$("#divVisitas").show();';
			}
			
			if(isset($_POST['pantalla']) && $_POST['pantalla'] == 'cal'){
				//echo "actualizaCalendario(); ";
			}
			
			if(! isset($_POST['vis'])){
				
				echo '
				if($("#hdnRegreso").val() == "visita"){
					$("#divVisitas").show();
					$("#divPlanes").hide();
				}else{
					$("#hdnIdPlan").val("");
					$("#hdnIdPlanReportar").val("");
					$("#divPlanes").hide();
					$("#divCapa3").hide();
					notificationPlanGuardado();
				}';
				//echo "window.close();";
			}
			echo "$('#btnGuardarPlan').prop('disabled',false);
			</script>";
		}else{
			//echo "query: ".$query;
			echo "<script>alertErrorGuardarRegistro();</script>
			$('#divPlanes').hide()";
		}
		//echo $queryCiclosVisitas
		
	}
	
?>