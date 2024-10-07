<?php
	/*** cobertura de medicos ***/
	include "../conexion.php";
			
	//$estatus = $_POST['hdnEstatus'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$ciclo = $_POST['hdnCicloReporte'];
	
	$qMedicos = "DECLARE @CICLO as VARCHAR(36)
		DECLARE @DIAS_IN as DATE
		DECLARE @DIAS_FIN as DATE
		DECLARE @STATUS as VARCHAR(36)
		DECLARE @PRESENCIAL as VARCHAR(36)
		DECLARE @VIRTUAL as VARCHAR(36)
		DECLARE @NOVISITA as VARCHAR(36)
		DECLARE @COMPLEMENT as VARCHAR(36)
		
		SET @CICLO = '".$ciclo."'
		/*SET @CICLO = (Select CYCLE_SNR from CYCLES where rec_stat=0 and NAME = '2023-02') */
		SET @DIAS_IN = (Select START_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @DIAS_FIN = (Select FINISH_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @STATUS = '19205DEC-F9F6-441A-9482-DB08D3394057'
		SET @PRESENCIAL = '2B3A7099-AC7D-47A3-A274-F0B029791801'
		SET @VIRTUAL = '146AA26A-502A-407A-A486-18470C9E7F23'
		SET @NOVISITA = '73253003-55D7-4B25-929F-0F4A452E6F6B'
		SET @COMPLEMENT = '036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F'
		
		Select 
		LINEA.name as Linea,
		klr.REG_SNR,
		DM.USER_NR as Ruta_Gte,
		DM.lname + ' ' + DM.fname as RM,
		MR.USER_NR as Ruta,
		MR.lname + ' ' + MR.fname as SR,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.visit_code_snr = @PRESENCIAL
		) as Vis_Presencial, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.visit_code_snr = @VIRTUAL
		) as Vis_Virtual, 
		
		(Select count(VP.PERS_SNR) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.novis_snr = @NOVISITA
		) as NoVisita, 
		
		(Select count(VP.PERS_SNR) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.visit_code_snr = @COMPLEMENT
		) as Vis_Complemen 
		
		from users DM, (select distinct reg_snr, kloc_snr, rec_stat from KLOC_REG) klr, users MR, company CIA, compline LINEA
		
		where klr.REG_SNR = DM.USER_SNR
		and klr.kloc_snr = MR.user_snr
		and klr.rec_stat=0
		and MR.rec_stat=0
		and DM.rec_stat=0
		and MR.Status in (1,2)
		and DM.Status in (1,2)
		and MR.User_type = 4
		and DM.User_type = 5
		and MR.cline_snr = LINEA.cline_snr
		and CIA.comp_snr = LINEA.comp_snr
		and CIA.rec_stat=0
		and LINEA.rec_stat=0
		and MR.user_snr in ('".$ids."') 
		
		order by DM.user_nr,DM.lname,DM.fname,MR.user_nr,MR.lname,MR.fname,klr.reg_snr ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=ResumenTipoVisita.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		ob_start();
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(1300,600));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Resumen Tipo de Visita'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Torrent');
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

	$tamTabla = 1200;
	$tabla = '';
	if( $tipo != 3){
			$tabla .= '<table border="0">
				<tr>
					<td>
						<table>
							<tr>
								<td colspan="10" class="nombreReporte">Resumen Tipo de Visita</td>
							</tr>
							<tr>
								<td colspan="10" class="clienteReporte">Torrent</td>
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
		if($tipo != 2){
			//$tabla .= '<thead style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000"><tr>';
			$tabla .= '<thead style="background-color: #11308E;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#FFF"><tr>';
		}else{
			$tabla .= '<thead><tr>';
		}
	}else{
		$pdf->SetFillColor(25,72,213);
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0);
			$pdf->SetLineWidth(1);
			$pdf->SetFont('','B');
	}
	
	if($tipo == 2){
		$estilorepre = '';
		$estilogte = '';
		$estilocabecera = '';
	}else{
		$estilocabecera = 'style="background-color: #11308E;font-weight:bold;border: 1px solid #FFF;padding: 5px 5px 5px 5px;color:#FFF"';
		$estilorepre = 'style="border: 1px solid #000;white-space:nowrap;"';
		$estilogte = 'style="background-color: #11308E;border: 1px solid #FFF;white-space:nowrap;color:#FFF"';
	}

	
	$i=1;
	//inicia var nacional
	$totalVisPresencial = 0;
	$totalVisVirtual = 0;
	$totalNoVisita = 0;
	$totalVisComplemen = 0;
	$totalTotal = 0;

	while($reg = sqlsrv_fetch_array($rsMedicos)){
		////suma nacional
		$totalVisPresencial += $reg['Vis_Presencial'];
		$totalVisVirtual += $reg['Vis_Virtual'];
		$totalNoVisita += $reg['NoVisita'];
		$totalVisComplemen += $reg['Vis_Complemen'];
		$totalTotal = $totalVisPresencial + $totalVisVirtual + $totalNoVisita + $totalVisComplemen;
		
		if($i == 1){
			////imprimir encabezados
			if($tipo != 3){
				$tabla .= '<td '.$estilocabecera.' width="100px">Linea</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px">Ruta</td>';
				$tabla .= '<td '.$estilocabecera.' width="400px">Nombre</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Presencial</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Virtual</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">No Visita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Total</td>';
			}else{
				$pdf->Ln();	
				$pdf->Cell(50,10,'Linea',1,0,'L',1);
				$pdf->Cell(50,10,'Ruta',1,0,'L',1);
				$pdf->Cell(200,10,'Nombre',1,0,'L',1);
				$pdf->Cell(50,10,'Visita Presencial',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Virtual',1,0,'C',1);
				$pdf->Cell(50,10,'No Visita',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Total',1,0,'C',1);
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
			
			////inicia var gerente
			$tempGerente = $reg['REG_SNR'];
			$gerente = $reg['REG_SNR'];
			$nombreGte = $reg['RM'];
			$rutaGte = $reg['Ruta_Gte'];
			$gteVisPresencial = $reg['Vis_Presencial'];
			$gteVisVirtual = $reg['Vis_Virtual'];
			$gteNoVisita = $reg['NoVisita'];
			$gteVisComplemen = $reg['Vis_Complemen'];
			$gteTotal = $gteVisPresencial + $gteVisVirtual + $gteNoVisita + $gteVisComplemen;
			
		}else{
			////sumas gerentes
			$gerente = $reg['REG_SNR'];		
			if($tempGerente == $gerente){
				$sumVisPresencial = $reg['Vis_Presencial'];
				$gteVisPresencial += $sumVisPresencial;
				$sumVisVirtual = $reg['Vis_Virtual'];
				$gteVisVirtual += $sumVisVirtual;
				$sumNoVisita = $reg['NoVisita'];
				$gteNoVisita += $sumNoVisita;
				$sumVisComplemen = $reg['Vis_Complemen'];
				$gteVisComplemen += $sumVisComplemen;
				$gteTotal = $gteVisPresencial + $gteVisVirtual + $gteNoVisita + $gteVisComplemen;
				
			}else{	
				////imprimir gerentes
				if($tipo != 3){	
					$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
					$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';	
					$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisPresencial).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisVirtual).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteNoVisita).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComplemen).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteTotal).'</td>';
					$tabla .= '</tr>';
				}else{ 
					$pdf->Cell(50,10,'',1,0,'L',1);
					$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
					$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
					$pdf->Cell(50,10,number_format($gteVisPresencial),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVisVirtual),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteNoVisita),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVisComplemen),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteTotal),1,1,'C',1);
				}
	
				////inicia var gerente
				$tempGerente = $reg['REG_SNR'];
				$nombreGte = $reg['RM'];
				$rutaGte = $reg['Ruta_Gte'];
				$gteVisPresencial = $reg['Vis_Presencial'];
				$gteVisVirtual = $reg['Vis_Virtual'];
				$gteNoVisita = $reg['NoVisita'];
				$gteVisComplemen = $reg['Vis_Complemen'];
				$gteTotal = $gteVisPresencial + $gteVisVirtual + $gteNoVisita + $gteVisComplemen;
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';
		$total = $reg['Vis_Presencial'] + $reg['NoVisita'] + $reg['Vis_Complemen'] ;

		////imprimir repres
		if($tipo != 3){
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Linea'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Ruta'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="400px">'.$reg['SR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Presencial'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Virtual'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['NoVisita'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Complemen'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($total).'</td>';
			$tabla .= '</tr>';
		}else{
			$pdf->Cell(50,10,$reg['Linea'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['Ruta'],1,0,'L',0);
			$pdf->Cell(200,10,$reg['SR'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['Vis_Presencial'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Virtual'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['NoVisita'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Complemen'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($total),1,1,'C',0);
		}
		$i++;
	}
	
	////imprimir ultimo gerente
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px"></td>';
		$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisPresencial).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisVirtual).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteNoVisita).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComplemen).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteTotal).'</td>';
	}else{
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
		$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
		$pdf->Cell(50,10,number_format($gteVisPresencial),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVisVirtual),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteNoVisita),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVisComplemen),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteTotal),1,1,'C',1);
	}	

	////imprimir nacional
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="400px">Total General</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPresencial).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisVirtual).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalNoVisita).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComplemen).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalTotal).'</td>';
		$tabla .= '</tr>';
	}else{
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(200,10,'Total General',1,0,'L',1);
		$pdf->Cell(50,10,number_format($totalVisPresencial),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisVirtual),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalNoVisita),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisComplemen),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalTotal),1,1,'C',1);
	}	
	
	if($tipo != 3){
		$tabla .= '</tbody> ';
		if($tipo == 2){	
			$tabla .= '<tfoot>';
		}else{
			if($tipo == 1){
				//$tabla .= '<tfoot style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000;">';
				$tabla .= '<tfoot style="background-color: #11308E;font-weight:bold;border: 1px solid #FFF;padding: 5px 5px 5px 5px;color:#FFF;">';
			}else{
				$tabla .= '<tfoot>';
			}
		}
		$numRegs = $i - 1;
				$tabla .= '<tr>
								<td colspan="10">Total regs: '.$numRegs.'</td>
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