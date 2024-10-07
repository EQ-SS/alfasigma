<?php
	include "../conexion.php";
	
	function actualizaTablas($conn, $idPers, $idUsuario, $ruta, $tipoUsuario, $repre){
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r", "\t");
		$reemplazar=array(" ", " ", " ", " "," ");
		//$sentencias = '';
		//echo "idUsusario: ".$idUsuario." idRuta: ".$ruta."<br>";
		$queryVisitas = "select 
			visit_date as Fecha_Vis,
			time,
			u.lname + ' ' + u.fname as Rep, u.user_nr,
			codigo_vis.name as Tipo_Vis,
			vp.info as informacion_vis,
			vp.info_nextvisit as obj_vis,
			vp.vispers_snr,
			vp.user_snr,
			vp.pers_snr,
			vp.pwork_snr,
			vp.escort_snr,
			(select info from vispersplan where vispersplan.pers_snr=vp.pers_snr and plan_date in (select max(plan_date) from vispersplan)) info_Ult_Plan,
			vp.vispersplan_snr as idPlan, 
			cycles.name as ciclo
			from visitpers vp
			inner join users u on u.USER_SNR = vp.USER_SNR
			inner join codelist codigo_vis on codigo_vis.clist_snr=vp.visit_code_snr
			left outer join binarydata firma on firma.record_key = vp.vispers_snr 
			left outer join cycles on vp.visit_date between cycles.start_date and cycles.finish_date ";
			if($tipoUsuario == 4){
				$queryVisitas .= " where vp.user_snr = '".$idUsuario."' ";
			}else{
				if (strlen($ruta)>10) {
					$queryVisitas .= " where vp.user_snr in ('".$repre."','".$ruta."') ";
				} else {
					$queryVisitas .= " where vp.user_snr in ('".$idUsuario."')";
				}
			}
			$queryVisitas .= " and vp.rec_stat=0 
			and vp.pers_snr = '".$idPers."' 
			order by vp.visit_date desc";
		
		//echo $queryVisitas;
	
		$rsVisitas = sqlsrv_query($conn, $queryVisitas);
		echo "var pagina = $('#hdnPaginaPersonas').val();
				var ids = $('#hdnIdsEnviarPersonas').val();
				$('#tblVisitas tbody').empty();";
				
		//echo "$('#tblVisitas tbody').empty();";
				
		while($visita = sqlsrv_fetch_array($rsVisitas)){
			foreach ($visita['Fecha_Vis'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha_vis = substr($val, 0, 10);
				}
			}
			
			echo "$('#tblVisitas tbody').append('<tr onClick=\"muestraVisita(\'".$visita['vispers_snr']."\',\'".$visita['idPlan']."\');\"><td style=\"width:11%;\">".$visita['ciclo']."</td><td style=\"width:8%;\">".$visita['user_nr']."</td><td style=\"width:13%;\">".$fecha_vis."</td><td style=\"width:9%;\">".$visita['time']."</td><td style=\"width:19%;\">".$visita['Tipo_Vis']."</td><td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,$visita['informacion_vis'])."</td><td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,$visita['obj_vis'])."</td></tr>');";
			
		}
		$queryCiclosVisitas = "select substring(ciclos.name,1,4) anio, 
			substring(ciclos.name,6,2) ciclo, 
			count(*) total 
			from visitpers vp, cycles ciclos 
			where vp.visit_date between ciclos.start_date and ciclos.finish_date 
			and vp.rec_stat=0 
			and vp.pers_snr='".$idPers."' ";
			if($tipoUsuario == 4){
				$queryCiclosVisitas .= "and vp.user_snr='".$idUsuario."' ";
			}else{
				if($ruta != ''){
					$queryCiclosVisitas .= "and vp.user_snr in ('".$idUsuario."','".$ruta."') ";
				}else{
					$queryCiclosVisitas .= "and vp.user_snr in ('".$idUsuario."') ";
				}
				
				//echo "alert('".$ruta."')";
			}
			$queryCiclosVisitas .= "and substring(ciclos.name,1,4) = (select max(substring(name,1,4)) from cycles) 
			group by ciclos.name ";
							
		//echo $queryCiclosVisitas;
		
		$rsCiclosVisitas = sqlsrv_query($conn, $queryCiclosVisitas);
		
		$totalVisitas = 0;
		while($cicloVisita = sqlsrv_fetch_array($rsCiclosVisitas)){
			echo "$('#cicloVisita'+".ltrim($cicloVisita['ciclo'], '0').").text('".$cicloVisita['total']."');";
			$totalVisitas += $cicloVisita['total'];
		}
		echo "$('#cicloVisitasAcumulado').text('".$totalVisitas."');";
		echo "$('#hdnIdPersona').val('".$idPers."');";
		//echo "nuevaPagina(pagina,'".date("Y-m-d")."',ids,'');";
		
		//return $sentencias;
	}
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{


		$idVisita = (empty($_POST['idVisita'])) ? '' : $_POST['idVisita'] ;
		$idPers = (empty($_POST['idPers'])) ? '' : $_POST['idPers'] ;
		$idUsuario = (empty($_POST['idUsuario'])) ? '' : $_POST['idUsuario'] ;
		$firma = (empty($_POST['firma'])) ? '' : str_replace('data:image/png;base64,','',$_POST['firma']);
		$fecha = (empty($_POST['fecha'])) ? '' : $_POST['fecha'] ;
		$hora = (empty($_POST['hora'])) ? '' : $_POST['hora'] ;
		$codigoVisita = (empty($_POST['codigoVisita'])) ? '' : $_POST['codigoVisita'] ;
		$comentariosVisita = (empty($_POST['comentariosVisita'])) ? '' : strtoupper($_POST['comentariosVisita']);
		$infoSiguienteVisita = (empty($_POST['infoSiguienteVisita'])) ? '' : strtoupper($_POST['infoSiguienteVisita']);
		$enfermedades_atiende = (empty($_POST['comentariosMedico'])) ? '' : strtoupper($_POST['comentariosMedico']);
		$latitude = (empty($_POST['lat'])) ? '' : $_POST['lat'] ;
		$longitude = (empty($_POST['lon'])) ? '' : $_POST['lon'] ;
		$ruta = (empty($_POST['ruta'])) ? '' : $_POST['ruta'] ;
		$tipoUsuario = (empty($_POST['tipoUsuario'])) ? '' : $_POST['tipoUsuario'] ;
		$repre = (empty($_POST['repre'])) ? '' : $_POST['repre'] ;
		$visitaAcompa = (empty($_POST['visitaAcompa'])) ? '00000000-0000-0000-0000-000000000000' : $_POST['visitaAcompa'] ;
		
		if(date('N', strtotime($fecha)) == 7){
			echo "<script>alertReportaDomingo();
				$('#btnGuardarVisitas').prop('disabled', false);
				</script>";
			return;
		}
		if($tipoUsuario != 2){
			if($fecha > date("Y-m-d")){
				echo "<script>
					alertPlaneaFechasPos();
					$('#btnGuardarVisitas').prop('disabled', false);
				</script>";
				return;
			}else{
				$diasReportar = sqlsrv_fetch_array(sqlsrv_query($conn, "select report_days_back as gsm from users where user_snr = '".$idUsuario."'"))['gsm'];
				//echo "<script>alert('select report_days_back as gsm from users where user_snr = \'".$idUsuario."\');</script>";
				$nuevaFecha = date ( 'Y-m-d' , strtotime ( '-'.$diasReportar.' day' , strtotime ( date('Y-m-d') ) ) );
				if($nuevaFecha > $fecha){
					echo "<script>
						alertReportarError();
						$('#btnGuardarVisitas').prop('disabled', false);
					</script>";
				return;
				}
			}
		}
		//echo "<script>alert('".$idVisita."');</script>";
		if($idVisita == ''){
			//valida visita que no exista
			$queryValida = "select * from visitpers where visit_date = '".$fecha ."' and pers_snr = '".$idPers."' and user_snr = '".$idUsuario."' and REC_STAT=0";
			//echo $queryValida."<br><br>";
			$rsValida = sqlsrv_query($conn, $queryValida, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
			if(sqlsrv_num_rows($rsValida) > 0){
				echo "<script>
						alertVisitaExistente();
						$('#btnGuardarVisitas').prop('disabled', false);
					</script>";
				return;
			}
			
			//valida la cuota
			
			/*$queryFreqVis = "select freq.NAME as frecuencia, 
				(SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".date("Y-m-d")."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE ";*/

				/*$queryFreqVis = "select freq.NAME as frecuencia, 
				(SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$fecha."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE ";	

				if($tipoUsuario == 4){
					$queryFreqVis .= " and vp.user_snr = '".$idUsuario."' ) as visitas ";
				}else{
					if (strlen($ruta)>10) {
						$queryFreqVis .= " and vp.user_snr = '".$ruta."' ) as visitas ";
					} else {
						$queryFreqVis .= " and vp.user_snr = '".$idUsuario."' ) as visitas ";
					}
				}
				$queryFreqVis .= " from person p
					inner join CODELIST freq on p.frecvis_SNR = freq.CLIST_SNR
					where p.pers_snr = '".$idPers."'
					and p.REC_STAT = 0 ";*/
					
			$queryFreqVis = "select 
				isnull(frecvis.name,0) as frec,
				isnull(cpcs.additional_contact,0) contact,
				isnull((select total
				from person_cycle_current pcc 
				where pcc.pers_snr=p.pers_snr),0) as visits
				from person p
				left outer join cycle_pers_categ_spec cpcs on cpcs.spec_snr=p.spec_snr and cpcs.category_snr=p.category_snr
				left outer join codelist frecvis on frecvis.clist_snr=cpcs.frecvis_snr
				inner join cycles c on cpcs.CYCLE_SNR = c.CYCLE_SNR 
				where p.pers_snr='".$idPers."'
				and '".date("Y-m-d")."' between c.start_date and c.FINISH_DATE
				and cpcs.REC_STAT = 0 ";
			
			$regValFrec = sqlsrv_fetch_array(sqlsrv_query($conn, $queryFreqVis));
			
			//echo $queryFreqVis."<br>";
			//echo "<script>alert('".$idUsuario." ::: ".$ruta."');</script>";
			
			if($idUsuario == $ruta){
				$cuota = $regValFrec['frec'] + $regValFrec['contact'];
				//echo "<script>alert('".$cuota." == ".$regValFrec['visits']."');</script>";
				if($cuota == $regValFrec['visits']){
					echo "<script>
							alertCuotaOk();
						</script>";
					return;
				}
			}
			
		}else{
			//valida visita que no exista
			$queryValida = "select vispers_snr from visitpers where visit_date = '".$fecha ."' and pers_snr = '".$idPers."' and vispers_snr <> '".$idVisita."' and user_snr = '".$idUsuario."'";
			
			$rsValida = sqlsrv_query($conn, $queryValida, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
			if(sqlsrv_num_rows($rsValida) > 0){
				echo "<script>
						alertVisitaExistente();
					</script>";
				return;
			}
		}
		
		
		
		if(isset($_POST['idPlan']) && $_POST['idPlan'] != '0' && $_POST['idPlan'] != ''){
			$idPlan = $_POST['idPlan'];
		}else{
			$idPlan = '00000000-0000-0000-0000-000000000000';
		}
		
		if(! empty($_POST['productosSeleccionados'])){
			$productosSeleccionados = substr($_POST['productosSeleccionados'], 0, -1);
			$arrProductosSeleccionados = explode("|", $productosSeleccionados);
			$porcentajesProductosSeleccionados = substr($_POST['porcentajesProductosSeleccionados'], 0, -1);
			$arrPorcentajesProductosSeleccionados = explode("|", $porcentajesProductosSeleccionados);
		}
		if(! empty($_POST['productosPromocionados']) && ! empty($_POST['cantidadProductosPromocionados'])){
			$productosPromocionados = substr($_POST['productosPromocionados'], 0, -1);
			$cantidadProductosPromocionados = substr($_POST['cantidadProductosPromocionados'], 0, -1);
			$arrProductosPromocionados = explode("|", $productosPromocionados);
			$arrCantidadProductosPromocionados = explode("|", $cantidadProductosPromocionados);
		}
		
		if($idVisita == ''){//nueva visita

			$queryIdVisita = "select NEWID() as idVisita from VISITPERS where VISPERS_SNR = '00000000-0000-0000-0000-000000000000'";
			$rsIdVisita = sqlsrv_query($conn, $queryIdVisita);
			$regId = sqlsrv_fetch_array($rsIdVisita);
			$idVisi = $regId['idVisita'];
			//echo "ruta: ".$ruta."<br><br>";
			if($ruta == ''){
				$queryDatos = "select psw.PWORK_SNR, psw.INST_SNR, i.CITY_SNR from pers_srep_work psw 
					inner join inst i on i.inst_snr = psw.inst_snr 
					where psw.pers_snr = '".$idPers."' 
					and psw.user_snr = '".$idUsuario."' 
					and psw.rec_stat = 0";
			}else{
				$queryDatos = "select psw.PWORK_SNR, psw.INST_SNR, i.CITY_SNR from pers_srep_work psw 
					inner join inst i on i.inst_snr = psw.inst_snr 
					where psw.pers_snr = '".$idPers."' 
					and psw.user_snr = '".$ruta."' 
					and psw.rec_stat = 0";
			}
			//echo $queryDatos."<br><br>";
			$rsDatos = sqlsrv_query($conn, $queryDatos);
			$registro = sqlsrv_fetch_array($rsDatos);
			$pwork = ($registro['PWORK_SNR'] == '') ? '00000000-0000-0000-0000-000000000000' : $registro['PWORK_SNR'];
			$inst_snr = ($registro['INST_SNR'] == '') ? '00000000-0000-0000-0000-000000000000' : $registro['INST_SNR'];
			$city_snr = ($registro['CITY_SNR'] == '') ? '00000000-0000-0000-0000-000000000000' : $registro['CITY_SNR'];
			$query = "insert into visitpers (
			VISPERS_SNR,
			PERS_SNR,
			VISIT_CODE_SNR,
			INFO,
			PWORK_SNR,
			REC_STAT,
			USER_SNR,
			VISIT_DATE,
			TIME,
			CREATION_TIMESTAMP,
			ESCORT_SNR,
			LATITUDE,
			LONGITUDE,
			SYNC,
			VISPERSPLAN_SNR,
			INFO_NEXTVISIT) 
			values(
			'".$idVisi."',
			'".$idPers."',
			'".$codigoVisita."',
			'".strtoupper($comentariosVisita)."',
			'".$pwork."',
			0,
			'".$idUsuario."',
			'".$fecha."',
			'".$hora."',
			getdate(),
			'".$visitaAcompa."',
			'".$latitude."',
			'".$longitude."',
			0,
			'".$idPlan."',
			'".$infoSiguienteVisita."')"; 
			
			if($idPlan != '' && $idPlan != '00000000-0000-0000-0000-000000000000'){
				if(!sqlsrv_query($conn, "update vispersplan set vispers_snr = '".$idVisi."' where VISPERSPLAN_SNR = '".$idPlan."'")){
					echo "<script> alertEliminarPlanError();</script>";
				}
				//echo "update vispersplan set vispers_snr = '".$idVisi."' where VPERSPLAN_SNR = '".$idPlan."'";
			}
				
			if(sqlsrv_query($conn, $query)){
				echo '<script>';
				//echo 'alert("Visita Guardada");';
					if($idPlan != '' && $idPlan != '00000000-0000-0000-0000-000000000000'){
						//echo "window.opener.actualizaAbuelo('".$idPers."', '".$idUsuario."');";
					}
				echo '</script>';
			}else{
				echo $query."<br><br>";
				echo '<script>alertEliminarVisitaError();</script>';
			}
		}else{
			$idVisi = $idVisita; 
			$query = "update visitpers set 
				VISIT_DATE = '".$fecha."',
				TIME = '".$hora."',
				VISIT_CODE_SNR = '".$codigoVisita."',
				ESCORT_SNR = '".$visitaAcompa."',
				INFO = '".strtoupper($comentariosVisita)."',
				SYNC = 0,
				INFO_NEXTVISIT = '".$infoSiguienteVisita."' 
				where VISPERS_SNR = '".$idVisita."'";
				//echo $query;
			if(! sqlsrv_query($conn, $query)){
				echo $query."<br>";
				echo "<script>alertErrorActualizar();</script>";
				//fwrite($file, $query."\r\n");
			}
		}
		//echo "productos: ".$_POST['productosSeleccionados']."<br>";
		if(! empty($_POST['productosSeleccionados'])){
			for($i=0;$i<count($arrProductosSeleccionados);$i++){
				$pos = $i+1;
				$queryAgregaProductoSeleccionado = "insert into visitpers_prod(
					vispersprod_snr,
					prod_snr,
					vispers_snr,
					position,
					percentage,
					rec_stat,
					sync)
					values(
					NEWID(),
					'".$arrProductosSeleccionados[$i]."',
					'".$idVisi."',
					".$pos.",
					'".$arrPorcentajesProductosSeleccionados[$i]."',
					0,
					0)
					";
				if(! sqlsrv_query($conn, $queryAgregaProductoSeleccionado)){
					echo "<script>alert('No se grabo top productos ');</script>";
					echo $queryAgregaProductoSeleccionado;
				}
			}
		}
		//echo "prod: ".$_POST['productosPromocionados']." ::: ".$_POST['cantidadProductosPromocionados']."<br>";
		if(! empty($_POST['productosPromocionados']) && ! empty($_POST['cantidadProductosPromocionados'])){
			for($j=0;$j<count($arrProductosPromocionados);$j++){
				$arrproducto = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from PRODFORMBATCH pfb, PRODFORM pf where pfb.PRODFORM_SNR = pf.PRODFORM_SNR and pfb.PRODFBATCH_SNR = '".$arrProductosPromocionados[$j]."'"));
				$queryMuestras = "insert into VISITPERS_PRODBATCH( 
					VISPERS_PRODBATCH_SNR,
					VISPERSPROD_SNR,
					VISPERS_SNR,
					PRODFBATCH_SNR,
					REC_STAT,
					QUANTITY,
					sync)
					values(
					NEWID(),
					'00000000-0000-0000-0000-000000000000',
					'".$idVisi."',
					'".$arrProductosPromocionados[$j]."',
					0,
					'".$arrCantidadProductosPromocionados[$j]."',
					0)
					";
				//echo $queryMuestras."<br>";
				if(! sqlsrv_query($conn, $queryMuestras)){
					echo "<script>alertErrorMuestra();</script>";
					echo $queryMuestras."<br>";
				}
			}
		}
		
		if($firma != ''){
			////revisa si ya existe la firma
			$rsFirma = sqlsrv_query($conn, "select * from binarydata where table_nr = '33' and record_key = '".$idVisi."'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ) );
			//echo "select * from binarydata where table_nr = '33' and rec_key = '".$idVisi."'"."<br><br>";
			if(sqlsrv_num_rows($rsFirma) < 1){//no existe la firma
				$queryFirma = "insert into BINARYDATA (
					BD_SNR,
					TABLE_NR,
					RECORD_KEY,
					DATASTREAM,
					REC_STAT,
					CREATION_TIMESTAMP,
					USER_SNR,
					SYNC,
					DATATYPE)
					values (
						NEWID(),
						'33',
						'".$idVisi."',
						'".$firma."',
						'0',
						getdate(),
						'00000000-0000-0000-0000-000000000000',
						0,
						null
						)";
			}
			if(! sqlsrv_query($conn, $queryFirma)){
				echo "Error firma ::: ".$queryFirma."<br>";
			}
		}
		
		//if($enfermedades_atiende != ''){
			/*$queryEnfermedadesAtiende = "update person set enfermedades_atiende = '".$enfermedades_atiende."' where pers_snr = '".$idPers."'";
			if(! sqlsrv_query($conn, $queryEnfermedadesAtiende)){
				echo $queryEnfermedadesAtiende;
			}*/
		//}
		//fclose($file);
		echo "<script>";
		if(isset($_POST['pantalla']) && $_POST['pantalla'] == 'cal'){
			//echo "actualizaCalendario();";
		}else{
			//if($idPlan == '' || $idPlan == '00000000-0000-0000-0000-000000000000'){
				actualizaTablas($conn, $idPers, $idUsuario, $ruta, $tipoUsuario, $repre);
			//}
		}	
		
		/* actualizar la muestra medica */
		
		echo "$('#tblMuestraMedica tbody').empty();";
		$queryMuestras = "select vp.VISIT_DATE as fecha, pf.TYPE as tipo, p.NAME as producto,
			pf.name as presentacion, pfb.NAME as lote, vpm.quantity as cantidad
			from VISITPERS_PRODBATCH vpm
			inner join VISITPERS vp on vp.VISPERS_SNR = vpm.VISPERS_SNR
			left outer join PRODFORMBATCH pfb on pfb.PRODFBATCH_SNR = vpm.PRODFBATCH_SNR
			inner join prodform pf on pf.PRODFORM_SNR = pfb.PRODFORM_SNR
			inner join product p on p.PROD_SNR = pf.PROD_SNR
			where vp.PERS_SNR = '".$idPers."'
			and year(getdate()) = year(vp.VISIT_DATE)
			order by vp.VISIT_DATE ";
		$rsMuestras = sqlsrv_query($conn, $queryMuestras);
		while($muestra = sqlsrv_fetch_array($rsMuestras)){
			foreach ($muestra['fecha'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fechaEntrega = substr($val, 0, 10);
				}
			} 
			if($muestra['tipo'] == 132){
				$tipoMaterial = 'Muestra Médica';
			}else if($muestra['tipo'] == 133){
				$tipoMaterial = 'Competencia';
			}else if($muestra['tipo'] == 136){
				$tipoMaterial = 'Material';
			}else{
				$tipoMaterial = '';
			}
			echo "$('#tblMuestraMedica tbody').append('<tr><td style=\"width:13%;\">".$fechaEntrega."</td><td style=\"width:15%;\">".$tipoMaterial."</td><td style=\"width:15%;\">".$muestra['producto']."</td><td style=\"width:25%;\">".$muestra['presentacion']."</td><td style=\"width:20%;text-transform:capitalize;\">".$muestra['lote']."</td><td style=\"width:12%;\">".$muestra['cantidad']."</td></tr>');";
		}
		
		/*termina actualiza muestra*/
		
		$queryFreqVis = "select freq.NAME as frecuencia, 
				(SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".date("Y-m-d")."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE ";
		if($tipoUsuario == 4){
			$queryFreqVis .= " and vp.user_snr = '".$idUsuario."' ) as visitas ";
		}else{
			$queryFreqVis .= " and vp.user_snr = '".$repre."' ) as visitas ";
		}
			$queryFreqVis .= " from person p
				inner join CODELIST freq on p.FRECVIS_SNR = freq.CLIST_SNR
				where p.pers_snr = '".$idPers."'
				and p.REC_STAT = 0 ";
		
		$regFreqVis = sqlsrv_fetch_array(sqlsrv_query($conn, $queryFreqVis));
		//echo "freq: ".$queryFreqVis."<br>";
		//echo "alert('".$regFreqVis['frecuencia']." -- ".$regFreqVis['visitas']."');";
		if($tipoUsuario == 4){
			if((int)$regFreqVis['frecuencia'] > (int)$regFreqVis['visitas']){
				echo "$('#imgAgregarVisita').prop('disabled', false);";
			}else{
				echo "$('#imgAgregarVisita').prop('disabled', true);";
			}
		}
		echo "$('#divVisitas').hide();
			$('#divCapa3').hide();
			notificationVisitaGuardada();
			$('#hdnIdPlan').val('');
			if($('#divCalendario').is(':visible')){
				/*actualizaCalendario();*/
			}else if($('#divInicio').is(':visible')){
				$('#imgHome').click();
			}
			</script>";
	}
?>