<?php
	/*** listado de farmacias ***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,300,250,150,250,300,350,500,250,100, 250,200,150,150,100,100,70,150,150,150 ,200,150,150,150,150,150,150,150,150,100 ,100,100,100,100,150,250,100,100,150,100 ,100,100,110,100,100,500);//,100,100,100,100,100,500);//, 50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	$estatus = $_POST['hdnEstatusInst'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$fechaI = $_POST['hdnFechaI'];
	$fechaF = $_POST['hdnFechaF'];
	
	$query = "Select
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST(I.inst_snr AS VARCHAR(36))+'}' as Codigo_inst,
		upper(T.name) as Tipo,
		upper(ST.name) as Sub_Tipo,
		upper(I.name) as Nombre,
		'' as 'Nombre Gte. Farmacia',
		upper(I.street1) as Direccion,
		City.name as Colonia,
		City.zip as Cod_Postal,
		Dst.name as Ciudad,
		State.name as Estado,
		stat_type.name as Status,
		I.tel1 as Tel1,
		I.tel2 as Tel2,
		VI.visit_date as Fecha_Visita,
		VI.time as Hora_Visita,
		VI.creation_timestamp as Fecha_Ceacion,
		VI.sync_timestamp as Fecha_Sinc,
		TipoVis.name as Tipo_Visita,
		VisAcomp.name as Visit_Acompa,
		Bonific.name as Tipo_Inversion,
		Importe.name as Importe,
		ProdF1.name --as ProdF1,
		+' '+PresF1.name as PresentF1,
		InvF1.suggest AS ExistF1,
		VtaF1.name as Vta_MensF1,
		 
		ProdF2.name --as ProdF2,
		+' '+PresF2.name as PresentF2,
		InvF2.suggest AS ExistF2,
		VtaF2.name as Vta_MensF2,
		 
		ProdF3.name --as ProdF3,
		+' '+PresF3.name as PresentF3,
		InvF3.suggest AS ExistF3,
		VtaF3.name as Vta_MensF3,
		 
		ProdV1.name -- as ProdV1,
		+' '+PresV1.name as PresentV1,
		InvV1.suggest AS Existv1,
		VtaV1.name as Vta_MensV1,
		 
		ProdV2.name -- as ProdV2,
		+' '+PresV2.name as PresentV2,
		InvV2.suggest AS Existv2,
		VtaV2.name as Vta_MensV2,
		 
		ProdZ1.name --as ProdZ1,
		+' '+PresZ1.name as PresentZ1,
		InvZ1.suggest AS ExistZ1,
		VtaZ1.name as Vta_MensZ1,
		
		VI.latitude as Lat_Visita,
		VI.longitude as Long_Visita,
		I.Latitude,
		I.Longitude,
		
		VI.info as Coment_PosVis
		
		/*upper(I.branch) as Sucursal,
		cat_type.name as Categ,
		VI.visit_time as Hora_Visita,
		(case when CAST(VI.Visit_Date as date) in (select cd.c_date from cycle_details cd
		where cd.rec_stat = 0
		AND CD.C_DATE=VI.Visit_Date and cd.c_day = 0) then 'DIA NO HABIL' else '' end) as Vis_Dia_Habil*/
		
		from Inst I
		left outer join City on City.city_snr=I.city_snr
		inner join District as Dst on city.distr_snr=Dst.distr_snr
		inner join State on Dst.state_snr = State.state_snr
		inner join Visitinst VI on VI.inst_snr =I.inst_snr
		inner join Users as U on U.user_snr =VI.user_snr
		inner join compline as cl on U.cline_snr=cl.cline_snr
		left outer join codelist as T on I.subtype_snr = T.clist_snr
		left outer join codelist as ST on I.format_snr = ST.clist_snr
		left outer join codelist cat_type on I.category_snr=cat_type.clist_snr
		left outer join codelist stat_type on I.status_snr=stat_type.clist_snr		 
			 
		left outer join codelist TipoVis on TipoVis.clist_snr=VI.visit_code_SNR and TipoVis.rec_stat=0 and TipoVis.status=1
		left outer join codelist VisAcomp on cast(VisAcomp.clist_snr as varchar(36))=cast(VI.escort_snr as varchar(36)) and VisAcomp.rec_stat=0 and VisAcomp.status=1
		left outer join codelist Bonific on Bonific.clist_Snr=VI.SUBJSTRAT_SNR and Bonific.rec_stat=0 and Bonific.status=1
		left outer join codelist Importe on Importe.clist_snr=VI.KPIS_SNR and Importe.rec_stat=0 and Importe.status=1
		 
		--left outer join INST_STOCK ISTOCK_PROD ON ISTOCK_PROD.vistinst_snr=VI.visinst_Snr and ISTOCK_PROD.REC_STAT=0
		left outer join PRODFORM PresF1 ON PresF1.prodform_snr='A1B41728-AE40-44A8-AE9E-4A7A5C1C68FD'
		left outer join PRODFORM PresF2 ON PresF2.prodform_snr='6B5CD5AF-59AC-4929-A8AF-51CC57A22072'
		left outer join PRODFORM PresF3 ON PresF3.prodform_snr='D974892A-AE36-4A08-A5EE-B0F21BFF0962'
		left outer join PRODFORM PresV1 ON PresV1.prodform_snr='5540769E-B8A2-4362-8608-2D1A2C7C4265'
		left outer join PRODFORM PresV2 ON PresV2.prodform_snr='87F195B1-F03A-4F89-9348-99BB37E6D4F0'
		left outer join PRODFORM PresZ1 ON PresZ1.prodform_snr='5944F9A5-EA86-45E6-9544-5A53B77976EF'
		 
		left outer join PRODUCT ProdF1 ON ProdF1.prod_snr=PresF1.prod_snr and PresF1.prodform_snr='A1B41728-AE40-44A8-AE9E-4A7A5C1C68FD'
		left outer join PRODUCT ProdF2 ON ProdF2.prod_snr=PresF2.prod_snr and PresF2.prodform_snr='6B5CD5AF-59AC-4929-A8AF-51CC57A22072'
		left outer join PRODUCT ProdF3 ON ProdF3.prod_snr=PresF3.prod_snr and PresF3.prodform_snr='D974892A-AE36-4A08-A5EE-B0F21BFF0962'
		left outer join PRODUCT ProdV1 ON ProdV1.prod_snr=PresV1.prod_snr and PresV1.prodform_snr='5540769E-B8A2-4362-8608-2D1A2C7C4265'
		left outer join PRODUCT ProdV2 ON ProdV2.prod_snr=PresV2.prod_snr and PresV2.prodform_snr='87F195B1-F03A-4F89-9348-99BB37E6D4F0'
		left outer join PRODUCT ProdZ1 ON ProdZ1.prod_snr=PresZ1.prod_snr and PresZ1.prodform_snr='5944F9A5-EA86-45E6-9544-5A53B77976EF'
		 
		left outer join INST_STOCK InvF1 ON InvF1.prodform_snr=PresF1.prodform_snr and InvF1.visinst_Snr=VI.visinst_Snr and InvF1.REC_STAT=0
		left outer join INST_STOCK InvF2 ON InvF2.prodform_snr=PresF2.prodform_snr and InvF2.visinst_Snr=VI.visinst_Snr and InvF2.REC_STAT=0
		left outer join INST_STOCK InvF3 ON InvF3.prodform_snr=PresF3.prodform_snr and InvF3.visinst_Snr=VI.visinst_Snr and InvF3.REC_STAT=0
		left outer join INST_STOCK InvV1 ON InvV1.prodform_snr=PresV1.prodform_snr and InvV1.visinst_Snr=VI.visinst_Snr and InvV1.REC_STAT=0
		left outer join INST_STOCK InvV2 ON InvV2.prodform_snr=PresV2.prodform_snr and InvV2.visinst_Snr=VI.visinst_Snr and InvV2.REC_STAT=0
		left outer join INST_STOCK InvZ1 ON InvZ1.prodform_snr=PresZ1.prodform_snr and InvZ1.visinst_Snr=VI.visinst_Snr and InvZ1.REC_STAT=0
		 
		left outer join codelist VtaF1 ON InvF1.stock_na_snr=VtaF1.clist_snr and VtaF1.REC_STAT=0
		left outer join codelist VtaF2 ON InvF2.stock_na_snr=VtaF2.clist_snr and VtaF2.REC_STAT=0
		left outer join codelist VtaF3 ON InvF3.stock_na_snr=VtaF3.clist_snr and VtaF3.REC_STAT=0
		left outer join codelist VtaV1 ON InvV1.stock_na_snr=VtaV1.clist_snr and VtaV1.REC_STAT=0
		left outer join codelist VtaV2 ON InvV2.stock_na_snr=VtaV2.clist_snr and VtaV2.REC_STAT=0
		left outer join codelist VtaZ1 ON InvZ1.stock_na_snr=VtaZ1.clist_snr and VtaZ1.REC_STAT=0
		 
		where
		I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		and I.rec_stat=0
		and I.inst_type=2
		and VI.rec_stat=0
		and U.rec_stat=0
		and U.status=1
		/*and U.user_type=4*/
		and I.status_snr in ('".$estatus."')
		and U.user_snr in ('".$ids."')
		and VI.visit_date between '".$fechaI."' and '".$fechaF."' 
		 
		order by Cl.name,U.lname,U.fname,VI.visit_date,I.name,stat_type.name";
	
	//echo $query."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoFarmaciasVisitadas.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/2),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE FARMACIAS VISITADAS'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Alfa Wassermann');
		$pdf->Ln();
		$pdf->SetFont('Arial',10);
		$pdf->Cell(40,5,'Fecha: '.date("d/m/Y h:i:s"));
		$pdf->Ln();
		
	}
	
	$rs = sqlsrv_query($conn, utf8_decode($query));
	if( $rs === false ) {
		if( ($errors = sqlsrv_errors() ) != null) {
			foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".$error[ 'message']."<br />";
			}
		}
	}
	
	$tamTabla = array_sum($tam) + 600;
	if( $tipo != 3){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO DE FARMACIAS VISITADAS</td>
						</tr>
						<tr>
							<td colspan="10" class="clienteReporte">Alfa Wassermann</td>
						</tr>
						<tr>
							<td colspan="10" class="fechaReporte">Fecha: '. date("d/m/Y h:i:s") .'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div id="divReportesRepo">';
						if($tipo == 0){
							$tabla .= '<table width="'.$tamTabla.'px" class="tablaReportes">';
						}else{
							$tabla .= '<table width="'.$tamTabla.'px" style="border-collapse: collapse;">';
						}
	}
	
	if($tipo != 3){
		if($tipo == 2){
			$tabla .= '<thead><tr>';
		}else{
			if($tipo == 1){
				$tabla .= '<thead style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000"><tr>';
			}else{
				$tabla .= '<thead><tr>';
			}
		}
	}else{
		$pdf->SetFillColor(169,188,245);
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0);
			$pdf->SetLineWidth(1);
			$pdf->SetFont('','B');
	}
					
	$i = 0;
	foreach(sqlsrv_field_metadata($rs) as $field){
		if($tipo != 3){
			if($tipo == 2){
				$tabla .= '<td style="min-width:'.$tam[$i].'px;">'.utf8_encode($field['Name']).'</td>'; 
			}else{
				if($tipo == 1){
					$tabla .= '<td style="background-color: #A9BCF5;border: 1px solid #000;min-width:'.$tam[$i].'px;">'.utf8_encode($field['Name']).'</td>';
				}else{
					$tabla .= '<td style="min-width:'.$tam[$i].'px;">'.utf8_encode($field['Name']).'</td>';
				}
			}	
		}else{
			$pdf->Cell($tam[$i]/2,8,$field['Name'],1,0,'C',1);
		}
		$i++;
	}
	
	if($tipo != 3){
		$tabla .= '</tr></thead>';
		$tabla .= '<tbody style="height:345px;">';
	}else{
		$pdf->Ln();
		//Restauración de colores y fuentes
		$pdf->SetFillColor(224,235,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		//Datos
		$fill=false;
	}
	$i=1;
	while($registro = sqlsrv_fetch_array($rs)){
		if($tipo != 3){
			$tabla .= '<tr>';
		}
		for($j=0;$j<sqlsrv_num_fields($rs);$j++){
			if(is_object($registro[$j])){
				foreach ($registro[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						if($j != 15){
							$registro[$j] = substr($val, 0, 16);
						}
						else {
							$registro[$j] = substr($val, 0, 10);
						}
					}
				}
			}

			if($tipo != 3){
				if($tipo == 2){
					$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.$registro[$j].'</td>';
				}else{
					if($tipo == 1){
						$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.$registro[$j].'</td>';
					}else{
						if($j < 43){
							$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($registro[$j]).'</td>';
						}else{
							if(strlen($registro[$j]) > 80){
								$registro[$j] = substr($registro[$j], 0, 79)."...";
							}
							$tabla .= '<td style="min-width:'.$tam[$j].'px;max-width:'.$tam[$j].'px;text-overflow: ellipsis;">'.utf8_encode($registro[$j]).'</td>';
						}	
					}
				}
			}else{
				$pdf->Cell($tam[$j]/2,8,$registro[$j],1,0,'L',$fill);
			}
			
		}
		if($tipo != 3){
			$tabla .= '</tr>';
		}else{
			$pdf->Ln();
			if($fill == true){
				$fill = false;
			}else{
				$fill = true;
			}
		}
		$i++;
	}
	if($tipo != 3){
		$tabla .= '</tbody> ';
		if($tipo == 2){	
			$tabla .= '<tfoot>';
		}else{
			if($tipo == 1){
				$tabla .= '<tfoot style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000;">';
			}else{
				$tabla .= '<tfoot>';
			}
		}
		$numRegs = $i - 1;
				$tabla .= '<tr>
								<td colspan="10">Total registros: '.$numRegs.'</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="10" class="derechosReporte">© Smart-Scale</td>
		</tr>
	</table>';
		echo $tabla;
	}else{
		$pdf->Output();
	}
	if($tipo == 0){
		echo '<script>
			$("#divCargando").hide();
		</script>';
	}
?>