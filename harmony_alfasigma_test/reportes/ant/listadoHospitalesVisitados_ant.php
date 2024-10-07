<?php
	/*** listado de farmacias ***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,300,250,100,250,150,300,300,100,200, 100,100,150,100,100,150,150,150,100,100, 100,100); //,150,150,150,150);//,150,100,100);//,100, 100,100,150,250,100,100,150,100,100,100, 350,100,100,100,100,100,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	$estatus = $_POST['hdnEstatusInst'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$fechaI = $_POST['hdnFechaI'];
	$fechaF = $_POST['hdnFechaF'];
	
	//echo "coma: ".substr($_POST['hdnIDS'], -1)."<br>";
	
	$query = " Select
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST(I.inst_snr AS VARCHAR(36))+'}' as Codigo_inst,
		upper(T.name) as Tipo,
		upper(I.name) as Nombre,
		/*upper(I.street2) as Referencia,*/
		/*upper(I.branch) as Sucursal,*/
		upper(I.street1) as Direccion,
		City.name as Colonia,
		City.zip as Cod_Postal,
		Dst.name as Ciudad,
		State.name as Estado,
		stat_type.name as Status,
		cat_type.name as Categ,
		I.tel1 as Tel1,
		I.tel2 as Tel2,
		VI.visit_date as Fecha_Visita,
		(case when CAST(VI.Visit_Date as date) in (select cd.c_date from cycle_details cd
		where cd.rec_stat = 0
		AND CD.C_DATE=VI.Visit_Date and cd.c_day = 0) then 'DIA NO HABIL' else '' end) as Vis_Dia_Habil,
		VI.creation_timestamp as Fecha_Ceacion,
		VI.latitude as Lat_Visita,
		VI.longitude as Long_Visita,
		I.Latitude,
		I.Longitude
		
		from Inst I
		left outer join City on  City.city_snr=I.city_snr
		left outer join codelist cat_type on I.category_snr=cat_type.clist_snr
		left outer join codelist stat_type on I.status_snr=stat_type.clist_snr
		 
		left outer join codelist as T on I.type_snr = T.clist_snr
		 
		inner join  District as Dst on city.distr_snr=Dst.distr_snr
		inner join State on Dst.state_snr = State.state_snr
		inner join Visitinst VI on VI.inst_snr =I.inst_snr
		inner join Users as U on U.user_snr =VI.user_snr
		inner join compline as cl on U.cline_snr=cl.cline_snr
		 
		where
		I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		and I.rec_stat=0
		and I.inst_type=1
		and VI.rec_stat=0
		and U.rec_stat=0
		and U.status=1
		and U.user_type=4
		and I.status_snr in ('".$estatus."')
		and U.user_snr in ('".$ids."')
		and VI.visit_date between '".$fechaI."' and '".$fechaF."' 
		 
		 
		order by Cl.name,U.lname,U.fname,VI.visit_date,I.name,stat_type.name";
	
	//echo $query."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoHospitalesVisitados.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/2),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE HOSPITALES VISITADOS'));
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
	
	$tamTabla = array_sum($tam) + 550;
	if( $tipo != 3){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO DE HOSPITALES VISITADOS</td>
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
		//$aseguradoras = array();
		for($j=0;$j<sqlsrv_num_fields($rs);$j++){
			if(is_object($registro[$j])){
				foreach ($registro[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$registro[$j] = substr($val, 0, 10);
					}
				}
			}
			/*if($j == 2){//pers_snr
				$qAdeguradoras = "select c.NAME from PERSIMAGE p, CODELIST c
					where p.IMAGE_SNR = c.CLIST_SNR
					and p.REC_STAT = 0
					and p.PERS_SNR = '".$registro[$j]."'
					order by c.SORT_NUM";
				$rsAseguradoras = sqlsrv_query($conn, $qAdeguradoras);
				$arrAseguradoras = array();
				while($aseguradora = sqlsrv_fetch_array($rsAseguradoras)){
					$arrAseguradoras[] = $aseguradora['NAME'];
				}
			}
			if($j == 40){
				if($tipo != 3){
					if($tipo == 2){
						$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.implode(",",$arrAseguradoras).'</td>';
					}else{
						if($tipo == 1){
							$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.implode(",",$arrAseguradoras).'</td>';
						}else{
							$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.implode(",",$arrAseguradoras).'</td>';
						}
					}
				}else{
					$pdf->Cell($tam[$j]/2,8,implode(",",$arrAseguradoras),1,0,'L',$fill);
				}
			}else{*/
				if($tipo != 3){
					if($tipo == 2){
						$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.$registro[$j].'</td>';
					}else{
						if($tipo == 1){
							$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.$registro[$j].'</td>';
						}else{
							$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.$registro[$j].'</td>';
						}
					}
				}else{
					$pdf->Cell($tam[$j]/2,8,$registro[$j],1,0,'L',$fill);
				}
			//}
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