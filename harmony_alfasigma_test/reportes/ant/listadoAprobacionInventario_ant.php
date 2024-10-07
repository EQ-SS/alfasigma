<?php
	/*** listado aprobacion Inventario	***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(300,150,100,200,250,200,100,100,100,350);//, 100,100,250,100,300,150,150,150,100,150, 150,150,100,100,100,100,100,100,100,100, 100,100,150,250,100,150,150,100,100,100, 350,100,100,100,100,100,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	$estatus = $_POST['hdnEstatus'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$producto = str_replace(",","','",substr($_POST['hdnIdsProductos'], 0, -1));
	$ciclo = $_POST['hdnCicloReporte'];
	
	$qMedicos = "SELECT 
		/*ROW_NUMBER() over (order by U.lname,C.name,P.NAME,pf.name,LOTE.name,c.name) as Num_Reg*/
		U.LNAME+' '+U.FNAME as Representante,
		CL.NAME AS Linea,
		C.name as Ciclo,
		P.NAME as Producto,
		PF.NAME as Presentacion,
		LOTE.NAME as Lote,
		SPU.ENTRYDATE as Fecha_Carga,
		SPU.QUANTITY as Recibidos,
		(case SPU.APPROVAL_STATUS WHEN 0 THEN 'PENDIENTE' WHEN 1 THEN 'APROBADO' WHEN 2 THEN 'RECHAZADO' END) AS Aceptado,
		(case when len(SPU.APPROVAL_INFO)>0 AND len(SPU.INFO_CHANGE)=0 then
		SPU.APPROVAL_INFO
		WHEN len(SPU.APPROVAL_INFO)=0 AND len(SPU.INFO_CHANGE)>0 THEN SPU.INFO_CHANGE
		WHEN len(SPU.APPROVAL_INFO)>0 AND len(SPU.INFO_CHANGE)>0 then SPU.INFO_CHANGE+' '+SPU.INFO_CHANGE
		ELSE '---'
		end) as Comentarios
		 
		FROM STOCK_PRODFORM_USER_APPROVAL SPU, 
		PRODFORMBATCH LOTE,
		PRODUCT P,
		PRODFORM PF,
		USERS U,
		CYCLES C,
		COMPLINE CL

		WHERE SPU.PRODFBATCH_SNR=LOTE.PRODFBATCH_SNR
		AND SPU.APPROVAL_STATUS IN (1,2,0)
		AND PF.PRODFORM_SNR=LOTE.PRODFORM_SNR
		AND P.PROD_SNR=PF.PROD_SNR
		AND U.USER_SNR=SPU.USER_SNR
		AND U.CLINE_SNR=CL.CLINE_SNR
		AND C.CYCLE_SNR=SPU.CYCLE_SNR
		AND C.CYCLE_SNR IN ('".$ciclo."')
		AND P.PROD_SNR in('".$producto."')
		AND U.USER_SNR in ('".$ids."')
		/*AND SPU.ENTRYDATE BETWEEN C.START_DATE AND C.FINISH_DATE*/
		AND SPU.REC_STAT=0
		AND P.REC_STAT=0
		AND PF.REC_STAT=0
		AND LOTE.REC_STAT=0
		AND U.REC_STAT=0
		AND C.REC_STAT=0
		AND CL.REC_STAT=0
		AND C.CYCLE_SNR<>'00000000-0000-0000-0000-000000000000'
		AND SPU.PRODFBATCH_SNR<>'00000000-0000-0000-0000-000000000000'
		AND P.PROD_SNR <>'00000000-0000-0000-0000-000000000000'
		AND LOTE.PRODFBATCH_SNR<>'00000000-0000-0000-0000-000000000000'
		
		order by U.LNAME,U.FNAME,C.name,P.NAME,PF.NAME ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoAprobacionInventario.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/2),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE APROBACIÓN INVENTARIO'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Alfa Wassermann');
		$pdf->Ln();
		$pdf->SetFont('Arial',10);
		$pdf->Cell(40,5,'Fecha: '.date("d/m/Y h:i:s"));
		$pdf->Ln();
		
	}
	
	$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos));
	if( $rsMedicos === false ) {
		if( ($errors = sqlsrv_errors() ) != null) {
			foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".$error[ 'message']."<br />";
			}
		}
	}
	//echo $qMedicos;
	$tamTabla = array_sum($tam) + 450;
	if( $tipo != 3){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO DE APROBACIÓN INVENTARIO</td>
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
					<div id="divListadoMedicos">';
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
	foreach(sqlsrv_field_metadata($rsMedicos) as $field){
		//if($i < 5){
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
		//}
		
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
	while($regMedico = sqlsrv_fetch_array($rsMedicos)){
		if($tipo != 3){
			$tabla .= '<tr>';
		}
		$aseguradoras = array();
		for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
			if(is_object($regMedico[$j])){
				foreach ($regMedico[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$regMedico[$j] = substr($val, 0, 10);
					}
				}
			}

			if($tipo != 3){
				if($tipo == 2){
					$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
				}else{
					if($tipo == 1){
						$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
					}else{
						$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
					}
				}
			}else{
				$pdf->Cell($tam[$j]/2,8,$regMedico[$j],1,0,'L',$fill);
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