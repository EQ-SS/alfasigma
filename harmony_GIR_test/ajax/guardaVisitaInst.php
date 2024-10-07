<?php
	include "../conexion.php";
	
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r", "\t");
	$reemplazar=array(" ", " ", " ", " "," ");
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		//$archivo = "logs/queriesVisitas.txt";
		//$file = fopen($archivo, "w");

		$idVisita = $_POST['idVisita'];
		
		$idInst = $_POST['idInst'];
		$idUsuario = $_POST['idUsuario'];
		$firma = str_replace('data:image/png;base64,','',$_POST['firma']);
		$fecha = $_POST['fecha'];
		$hora = $_POST['hora'];
		$codigoVisita = $_POST['codigoVisita'];
		if(isset($_POST['motivoNoVisita'])){
			$motivoNoVisita = $_POST['motivoNoVisita'];
		}else{
			$motivoNoVisita = "";
		}
		
		$visitaAcompa = $_POST['visitaAcompa'];
		$comentariosVisita = strtoupper(utf8_decode($_POST['comentariosVisita']));
		$infoSiguienteVisita = strtoupper(utf8_decode($_POST['infoSiguienteVisita']));
		//$enfermedades_atiende = strtoupper($_POST['comentariosMedico']);
		$latitude = $_POST['lat'];
		$longitude = $_POST['lon'];
		
		$idUser = $_POST['idUser'];
		$tipoUsuario = $_POST['tipoUsuario'];
		$ruta = $_POST['ruta'];
		
		if(isset($_POST['idTipoInst']) && $_POST['idTipoInst']){
			$idTipoInst = $_POST['idTipoInst'];
		}else{
			$idTipoInst = "";
		}
		
		if(isset($_POST['productosStock']) && $_POST['productosStock'] != ''){
			$productosStock = $_POST['productosStock'];
			$productoStockExistencia = $_POST['productoStockExistencia'];
			$productoStockPrecio = $_POST['productoStockPrecio'];
			$productoStockDesplazamiento = $_POST['productoStockDesplazamiento'];
			//$productoStockSugerido = $_POST['productoStockSugerido'];
			//$productoStockAgotado = $_POST['productoStockAgotado'];
			//$productoStockCadenas = $_POST['productoStockCadenas'];
			//$productoStockPromociones = $_POST['productoStockPromociones'];
		}else{
			$productosStock = '';
		}
		
		if($fecha > date("Y-m-d")){
			echo "<script>
				alertPlaneaFechasPos();
				$('#btnGuardarVisitasInst').show();
				$('#btnGuardarVisitasInst').prop('disabled', false);
			</script>";
			return;
		}else{
			$diasReportar = sqlsrv_fetch_array(sqlsrv_query($conn, "select REPORT_DAYS_BACK from users where user_snr = '".$idUsuario."'"))['REPORT_DAYS_BACK'];
			$nuevaFecha = date ( 'Y-m-d' , strtotime ( '-'.$diasReportar.' day' , strtotime ( date('Y-m-d') ) ) );
			if($nuevaFecha > $fecha){
				echo "<script>
					alertReportarError();
					$('#btnGuardarVisitasInst').show();
					$('#btnGuardarVisitasInst').prop('disabled', false);
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
			if(isset($_POST['estrategias']) && $_POST['estrategias'] != ''){
				$estrategias = substr($_POST['estrategias'], 0, -1);
			}else{
				$estrategias = '';
			}
			
			$arrEstrategias = explode("|", $estrategias);
		}
		if(! empty($_POST['productosPromocionados']) && ! empty($_POST['cantidadProductosPromocionados'])){
			$productosPromocionados = substr($_POST['productosPromocionados'], 0, -1);
			$cantidadProductosPromocionados = substr($_POST['cantidadProductosPromocionados'], 0, -1);
			$arrProductosPromocionados = explode("|", $productosPromocionados);
			$arrCantidadProductosPromocionados = explode("|", $cantidadProductosPromocionados);
		}
		
		if($idVisita == ''){//nueva visita
			$queryIdVisita = "select NEWID() as idVisita from VISITINST where  VISINST_SNR = '00000000-0000-0000-0000-000000000000'";
			$rsIdVisita = sqlsrv_query($conn, $queryIdVisita);
			$regId = sqlsrv_fetch_array($rsIdVisita);
			$idVisi = $regId['idVisita'];
			$query = "insert into VISITINST (
			VISINST_SNR,
			USER_SNR,
			visit_code_snr,
			INFO,
			REC_STAT,
			INST_SNR,
			VISIT_DATE,
			time,
			creation_timestamp,
			escort_snr,
			LATITUDE,
			LONGITUDE,
			SYNC,
			VISINSTPLAN_SNR,
			info_nextvisit) 
			values(
			'".$idVisi."',
			'".$idUsuario."',
			'".$codigoVisita."',
			'".$comentariosVisita."',
			0,
			'".$idInst."',
			'".$fecha."',
			'".$hora."',
			getdate(),
			'".$visitaAcompa."',
			'".$latitude."',
			'".$longitude."',
			0,
			'".$idPlan."',
			'".strtoupper($infoSiguienteVisita)."' 
			)"; 
			
			if($idPlan != '' && $idPlan != '00000000-0000-0000-0000-000000000000'){
				if(!sqlsrv_query($conn, "update visinstplan set visinst_snr = '".$idVisi."' where VISINSTPLAN_SNR = '".$idPlan."'")){
					echo "<script>alertErrorPlan();</script>";
					//echo "<script>alert(\"".$idPlan."\");</script>";
					//echo "update vispersplan set vispers_snr = '$idVisi' where vispersplan_snr = '$idPlan'";
				}/*else{
					echo "<script>alert(\"ya se armo!!!\");</script>";
				}*/
			}
				
			if(! sqlsrv_query($conn, $query)){
				echo $query."<br>";
				echo '<script>alertErrorVisita();</script>';
			}
		}else{
			$idVisi = $idVisita; 
			$query = "update visitinst set 
				VISIT_DATE = '".$fecha."',
				time = '".$hora."',
				visit_code_snr = '".$codigoVisita."',
				escort_snr = '".$visitaAcompa."',
				INFO = '".$comentariosVisita."',
				SYNC = 0,
				info_nextvisit = '".strtoupper($infoSiguienteVisita)."'
				where VISINST_SNR = '".$idVisita."'";
				
				//echo $query;
			if(! sqlsrv_query($conn, $query)){
				echo "<script>alertErrorActualizar();</script>";
				//fwrite($file, $query."\r\n");
				echo $query;
			}else{
				//echo "<script>alert('Visita actualizada');</script>";
			}
		}
		//echo "productos: ".$_POST['productosSeleccionados']."<br>";
		if(! empty($_POST['productosSeleccionados'])){
			for($i=0;$i<count($arrProductosSeleccionados);$i++){
				$pos = $i+1;
				if($arrEstrategias[$i] == ''){
					$arrEstrategias[$i] = '00000000-0000-0000-0000-000000000000';
				}
				$queryAgregaProductoSeleccionado = "insert into VISITINST_PROD
					(
						VISINSTPROD_SNR,
						PROD_SNR,
						VISINST_SNR,
						position,
						percentage,
						rec_stat,
						sync,
						strategy_snr
					)values(
						NEWID(),
						'".$arrProductosSeleccionados[$i]."',
						'".$idVisi."',
						".$pos.",
						'".$arrPorcentajesProductosSeleccionados[$i]."',
						0,
						0,
						'".$arrEstrategias[$i]."'
					)
					";
				if(! sqlsrv_query($conn, $queryAgregaProductoSeleccionado)){
					echo $queryAgregaProductoSeleccionado."<br>";
					echo "<script>alert('hola');alertErrorTopProductos();</script>";
				}
			}
		}
		if(! empty($_POST['productosPromocionados']) && ! empty($_POST['cantidadProductosPromocionados'])){
			for($j=0;$j<count($arrProductosPromocionados);$j++){
				$arrproducto = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from prodform where prodform_snr = '".$arrProductosPromocionados[$j]."'"));
				$queryMuestras = "insert into VISITINST_PRODBATCH( 
					VISINST_PRODBATCH_SNR,
					VISINSTPROD_SNR,
					VISINST_SNR,
					PRODFBATCH_SNR,
					REC_STAT,
					quantity,
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
					
				if(! sqlsrv_query($conn, $queryMuestras)){
					echo "<script>alertErrorMuestra();</script>";
					//fwrite($file, $queryMuestras."\r\n");
					echo $queryMuestras."<br>";
				}
				/*$queryDescuentaMuestra = "insert into STOCK_PRODFORM_USER(
					STPRODF_USER_SNR,
					ENTRYDATE,
					USER_SNR,
					PRODFORM_SNR,
					QUANTITY,
					REC_STAT,
					TABLE_NR,
					REC_KEY,
					INFO_DATE,
					PRODFprodbatch_snr,
					SYNC)
					values(
					NEWID(),
					'".$fecha."',
					'".$idUsuario."',
					'".$arrProductosPromocionados[$j]."',
					'-".$arrCantidadProductosPromocionados[$j]."',
					0,
					'34',
					'".$idVisi."',
					'".$fecha."',
					'00000000-0000-0000-0000-000000000000',
					0)";
				if(! sqlsrv_query($conn, $queryDescuentaMuestra)){
					echo "<script>alert('No se grabo descuenta muestra');</script>";
				}*/
			}
		}
		if(isset($_POST['pantalla']) && $_POST['pantalla'] == 'cal'){
			echo "<script>actualizaCalendario();</script>";
		}else{
			$queryVisitas = "select visit_date as Fecha_Vis,
					time,u.lname + ' ' + u.fname as Rep,u.user_nr,
					codigo_vis.name as Tipo_Vis,
					vp.info as informacion_vis,
					vp.info_nextvisit as obj_vis,
					vp.visinst_snr,vp.user_snr,
					vp.inst_snr,
					(select info from visinstplan where visinstplan.inst_snr=vp.inst_snr and creation_timestamp in (select max(creation_timestamp) from visinstplan)) info_Ult_Plan,
					substring(c.name, 1, 4) + substring(c.name, 8, 3) as ciclo
					from visitinst vp
					inner join users u on u.USER_SNR = vp.USER_SNR
					inner join codelist codigo_vis on codigo_vis.clist_snr=vp.visit_code_snr 
					left outer join cycles c on vp.visit_date between c.START_DATE and c.FINISH_DATE ";
					if($tipoUsuario == 4){
						$queryVisitas .= "where vp.user_snr = '".$idUser."' ";
					}else{
						if($ruta != ''){
							$queryVisitas .= "where vp.user_snr in ('".$idUser."','".$ruta."') ";
						}else{
							$queryVisitas .= "where vp.user_snr in ('".$idUser."') ";
						}
					}
					$queryVisitas .= "and vp.rec_stat=0 
					and vp.inst_snr = '".$idInst."' 
					order by vp.visit_date desc";
				
				//echo $queryVisitas;
				
				$rsVisitas = sqlsrv_query($conn, $queryVisitas);
				
				$queryCiclosVisitas = "select substring(ciclos.name,1,4) anio, 
					substring(ciclos.name,6,2) ciclo, 
					count(*) total 
					from visitinst vp, cycles ciclos 
					where vp.visit_date between ciclos.start_date and ciclos.finish_date 
					and vp.rec_stat=0 
					and vp.inst_snr='".$idInst."' ";
					if($tipoUsuario == 4){
						$queryCiclosVisitas .= "and vp.user_snr='".$idUser."' ";
					}else{
						if($ruta != ''){
							$queryCiclosVisitas .= "and vp.user_snr in ('".$idUser."','".$ruta."') ";
						}else{
							$queryCiclosVisitas .= "and vp.user_snr in ('".$idUser."') ";
						}
					}
					$queryCiclosVisitas .= "and substring(ciclos.name,1,4) = (select max(substring(name,1,4)) from cycles) 
					group by ciclos.name ";
				
				//echo $queryCiclosVisitas;
				
				$rsCiclosVisitas = sqlsrv_query($conn, $queryCiclosVisitas);
				
				echo "<script>
					$('#tblVisitasInst tbody').empty();";
				
				while($visita = sqlsrv_fetch_array($rsVisitas)){
					foreach ($visita['Fecha_Vis'] as $key => $val) {
						if(strtolower($key) == 'date'){
							$fecha_vis = substr($val, 0, 10);
						}
					}
					//echo "$('#tblVisitasInst tbody').append('<tr onClick=\"muestraVisitaInst(\'".$visita['visinst_snr']."\');\"><td style=\"width:15%;\">".$fecha_vis."</td><td style=\"width:10%;\">".$visita['time']."</td><td style=\"width:35%;\">".$visita['Rep']."</td><td style=\"width:30%;\">".utf8_encode($visita['Tipo_Vis'])."</td><td style=\"width:10%;\">".$visita['ciclo']."</td></tr>');";
					$registro = "<tr onClick=\"muestraVisitaInst(\'".$visita['visinst_snr']."\');\">";
					$registro .= "<td style=\"width:11%;\">".$visita['ciclo']."</td>";
					$registro .= "<td style=\"width:8%;\">".$visita['user_nr']."</td>";
					$registro .= "<td style=\"width:13%;\">".$fecha_vis."</td>";
					$registro .= "<td style=\"width:9%;\">".$visita['time']."</td>";
					$registro .= "<td style=\"width:19%;\">".utf8_encode($visita['Tipo_Vis'])."</td>";
					$registro .= "<td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($visita['informacion_vis']))."</td>";
					$registro .= "<td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($visita['obj_vis']))."</td>";
					$registro .= "</tr>";
					echo "$('#tblVisitasInst tbody').append('".$registro."');";
				}
				//echo $visitas;
				$totalVisitas = 0;
				while($cicloVisita = sqlsrv_fetch_array($rsCiclosVisitas)){
					echo "$('#cicloVisitaInst'+".ltrim($cicloVisita['ciclo'], '0').").text('".$cicloVisita['total']."');";
					$totalVisitas += $cicloVisita['total'];
				}
				echo "$('#cicloVisitasAcumuladoInst').text('".$totalVisitas."');";
				echo "$('#hdnIdInst').val('".$idInst."');";
				echo "</script>";
		}
		
		if($firma != ''){
			////revisa si ya existe la firma
			$rsFirma = sqlsrv_query($conn, "select * from binarydata where table_nr = '34' and record_key = '".$idVisi."'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ) );
			if(sqlsrv_num_rows($rsFirma) < 1){//no existe la firma
				$queryFirma = "insert into binarydata values (
						NEWID(),
						'34',
						'".$idVisi."',
						'".$firma."',
						'0',
						getdate(),
						'00000000-0000-0000-0000-000000000000',
						'0',
						'0'
						)";
			}
			if(! sqlsrv_query($conn, $queryFirma)){
				echo "Error firma ::: ".$queryFirma."<br>";
			}
		}
		//echo "<script>alert('".$idTipoInst."');</script>";
		if($idTipoInst == 2){//grabar stock
			//echo "productos stock: ".$productosStock.":::";
			if($productosStock != ''){
				$arrProductosStock = explode(",", substr($productosStock, 0, -1));
				$arrExistencia = explode(",", substr($productoStockExistencia, 0, -1));
				$arrPrecio = explode(",", substr($productoStockPrecio, 0, -1));
				$arrDesplazamiento = explode(",", substr($productoStockDesplazamiento, 0, -1));
				//$arrSugerido = explode(",", substr($productoStockSugerido, 0, -1));
				//$arrAgotado = explode(",", substr($productoStockAgotado, 0, -1));
				//$arrCadena = explode(",", substr($productoStockCadenas, 0, -1));
				//$arrPromociones = explode(",", substr($productoStockPromociones, 0, -1));
				for($i=0;$i<count($arrProductosStock);$i++){
					$queryStock = "insert into INST_STOCK ( 
					INST_STOCK_SNR,
					INST_SNR,
					PRODFORM_SNR,
					EXIST,
					REC_STAT,
					VISINST_SNR,
					CREATION_TIMESTAMP,
					USER_SNR,
					PRICE,
					DISPLACE,
					SYNC) values ( 
					NEWID(),
					'".$idInst."', 
					'".$arrProductosStock[$i]."',
					'".$arrExistencia[$i]."',
					'0',
					'".$idVisi."',
					getdate(),
					'".$idUsuario."',
					'".$arrPrecio[$i]."',
					'".$arrDesplazamiento[$i]."',
					0)";
					
					if(! sqlsrv_query($conn, $queryStock)){
						echo $queryStock;
					}
					
					//echo $queryStock."<br><br>";
				}
			}
			
			if(isset($_POST['idProductoCompetidores']) && $_POST['idProductoCompetidores'] != ''){
				$arrIdCompetidores = explode(",",substr($_POST['idProductoCompetidores'], 0, -1));
				$arrExistenciaCompetidores = explode(",", substr($_POST['existenciaCompetidores'], 0, -1));
				$arrPrecioCompetidores = explode(",", substr($_POST['precioCompetidores'], 0, -1));
				for($i=0;$i<count($arrIdCompetidores);$i++){
					$queryCompetidor = "insert into INST_STOCK ( 
					INST_STOCK_SNR,
					INST_SNR,
					PRODFORM_SNR,
					QUANTITY,
					REC_STAT,
					PRICE,
					VISINST_SNR,
					CREATION_TIMESTAMP,
					USER_SNR,
					SUGEESTED,
					SUGGEST,
					SYNC ) values ( 
					NEWID(),
					'".$idInst."', 
					'".$arrIdCompetidores[$i]."',
					'".$arrExistenciaCompetidores[$i]."',
					'0',
					'".$arrPrecioCompetidores[$i]."',
					'".$idVisi."',
					getdate(),
					'".$idUsuario."',
					NULL,
					NULL,
					0 )";
					
					if(! sqlsrv_query($conn, $queryCompetidor)){
						echo $queryCompetidor;
					}
					
					//echo $queryCompetidor."<br><br>";
				}
			}
		}
		
		echo "<script>";
		if(isset($_POST['pantalla']) && $_POST['pantalla'] == 'cal'){
			echo "actualizaCalendario();";
		}/*else{
			if($idPlan == '' || $idPlan == '00000000-0000-0000-0000-000000000000'){
				actualizaTablas($conn, $idPers, $idUsuario);
			}
		}	*/

		echo "var pagina = $('#hdnPaginaInst').val();
			var ids = $('#hdnIds').val();
			var hoy = $('#hdnHoy').val();
			notificationVisitaGuardada();
			$('#divVisitasInst').hide();
			$('#divCapa3').hide();
			if($('#divCalendario').is(':visible')){
				actualizaCalendario();
			}else if($('#divDatosInstituciones').is(':visible')){
				$('#btnActualizarInst').click();
			}
			</script>";
	}
?>