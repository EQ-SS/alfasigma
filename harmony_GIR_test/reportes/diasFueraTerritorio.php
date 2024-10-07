<?php
	/*** cobertura de medicos ***/
	include "../conexion.php";
			
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$ciclo = $_POST['hdnCicloReporte'];
	
	$qMedicos = "DECLARE @CICLO as VARCHAR(36)
		DECLARE @DIAS_IN as DATE
		DECLARE @DIAS_FIN as DATE
		DECLARE @OAct1 as VARCHAR(36)
		DECLARE @OAct2 as VARCHAR(36)
		DECLARE @OAct3 as VARCHAR(36)
		DECLARE @OAct4 as VARCHAR(36)
		DECLARE @OAct5 as VARCHAR(36)
		DECLARE @OAct6 as VARCHAR(36)
		DECLARE @OAct7 as VARCHAR(36)
		
		SET @CICLO = '".$ciclo."'
		/*SET @CICLO = (Select CYCLE_SNR from CYCLES where rec_stat=0 and NAME = '2023-02') */
		SET @DIAS_IN = (Select START_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @DIAS_FIN = (Select FINISH_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @OAct1 = '60D49A31-42C8-4A4C-81A7-30BACE0892E6' /*Capacitacion*/
		SET @OAct2 = 'DF7AF2DA-C6CB-46E4-BADC-8898A8C58C7C' /*Convencion*/
		SET @OAct3 = '76009BFC-95F7-4EC4-850C-5B8822D68C34' /*Incapacidad*/
		SET @OAct4 = 'AAE64F63-94EE-4476-9B10-21CD12235DD4' /*Junta resultados*/
		SET @OAct5 = '79D0E6AB-9869-4E0A-9071-29F73938CD97' /*Permiso personal*/
		SET @OAct6 = '89629CD1-E9A7-4BDE-AEC8-FB6A1F4DFB22' /*Vacaciones*/
		SET @OAct7 = 'A4E2826C-7809-4EE5-B125-7D6CB172C554' /*Vacante*/
		
		select 
		LINEA.name as Linea,
		klr.REG_SNR,
		DM.USER_NR as Ruta_Gte,
		DM.lname + ' ' + DM.fname as RM,
		MR.USER_NR as Ruta,
		MR.lname + ' ' + MR.fname as SR,
		
		'Capacitacion' as NomOAct1,
		'Convencion' as NomOAct2,
		'Incapacidad' as NomOAct3,
		'Junta resultados' as NomOAct4,
		'Permiso personal' as NomOAct5,
		'Vacaciones' as NomOAct6,
		'Vacante' as NomOAct7,
		 
		(Select count(DISTINCT DR.DAYREPORT_SNR) from DAY_REPORT DR, DAY_REPORT_CODE DC
		where DR.DAYREPORT_SNR = DC.DAYREPORT_SNR
		and DR.rec_stat = 0
		and DR.user_snr = MR.user_snr
		and DR.date between @DIAS_IN and @DIAS_FIN
		and DC.day_code_snr = @OAct1
		and datepart(DW,DR.DATE) not in (1,7)
		) as OAct1, 
		
		(Select count(DISTINCT DR.DAYREPORT_SNR) from DAY_REPORT DR, DAY_REPORT_CODE DC
		where DR.DAYREPORT_SNR = DC.DAYREPORT_SNR
		and DR.rec_stat = 0
		and DR.user_snr = MR.user_snr
		and DR.date between @DIAS_IN and @DIAS_FIN
		and DC.day_code_snr = @OAct2
		and datepart(DW,DR.DATE) not in (1,7)
		) as OAct2, 
		
		(Select count(DISTINCT DR.DAYREPORT_SNR) from DAY_REPORT DR, DAY_REPORT_CODE DC
		where DR.DAYREPORT_SNR = DC.DAYREPORT_SNR
		and DR.rec_stat = 0
		and DR.user_snr = MR.user_snr
		and DR.date between @DIAS_IN and @DIAS_FIN
		and DC.day_code_snr = @OAct3
		and datepart(DW,DR.DATE) not in (1,7)
		) as OAct3, 
		
		(Select count(DISTINCT DR.DAYREPORT_SNR) from DAY_REPORT DR, DAY_REPORT_CODE DC
		where DR.DAYREPORT_SNR = DC.DAYREPORT_SNR
		and DR.rec_stat = 0
		and DR.user_snr = MR.user_snr
		and DR.date between @DIAS_IN and @DIAS_FIN
		and DC.day_code_snr = @OAct4
		and datepart(DW,DR.DATE) not in (1,7)
		) as OAct4, 
		
		(Select count(DISTINCT DR.DAYREPORT_SNR) from DAY_REPORT DR, DAY_REPORT_CODE DC
		where DR.DAYREPORT_SNR = DC.DAYREPORT_SNR
		and DR.rec_stat = 0
		and DR.user_snr = MR.user_snr
		and DR.date between @DIAS_IN and @DIAS_FIN
		and DC.day_code_snr = @OAct5
		and datepart(DW,DR.DATE) not in (1,7)
		) as OAct5, 
		
		(Select count(DISTINCT DR.DAYREPORT_SNR) from DAY_REPORT DR, DAY_REPORT_CODE DC
		where DR.DAYREPORT_SNR = DC.DAYREPORT_SNR
		and DR.rec_stat = 0
		and DR.user_snr = MR.user_snr
		and DR.date between @DIAS_IN and @DIAS_FIN
		and DC.day_code_snr = @OAct6
		and datepart(DW,DR.DATE) not in (1,7)
		) as OAct6, 
		
		(Select count(DISTINCT DR.DAYREPORT_SNR) from DAY_REPORT DR, DAY_REPORT_CODE DC
		where DR.DAYREPORT_SNR = DC.DAYREPORT_SNR
		and DR.rec_stat = 0
		and DR.user_snr = MR.user_snr
		and DR.date between @DIAS_IN and @DIAS_FIN
		and DC.day_code_snr = @OAct7
		and datepart(DW,DR.DATE) not in (1,7)
		) as OAct7 
		
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
		header("Content-Disposition: filename=DiasFueraTerritorio.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		ob_start();
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(1300,750));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Dias Fuera de Territorio'));
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

	$tamTabla = 1500;
	$tabla = '';
	if( $tipo != 3){
			$tabla .= '<table border="0">
				<tr>
					<td>
						<table>
							<tr>
								<td colspan="10" class="nombreReporte">Dias Fuera de Territorio</td>
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
	$totalOAct1 = 0;
	$totalOAct2 = 0;
	$totalOAct3 = 0;
	$totalOAct4 = 0;
	$totalOAct5 = 0;
	$totalOAct6 = 0;
	$totalOAct7 = 0;
	$totalTotal = 0;

	while($reg = sqlsrv_fetch_array($rsMedicos)){
		////suma nacional
		$totalOAct1 += $reg['OAct1'];
		$totalOAct2 += $reg['OAct2'];
		$totalOAct3 += $reg['OAct3'];
		$totalOAct4 += $reg['OAct4'];
		$totalOAct5 += $reg['OAct5'];
		$totalOAct6 += $reg['OAct6'];
		$totalOAct7 += $reg['OAct7'];
		$totalTotal = $totalOAct1 + $totalOAct2 + $totalOAct3 + $totalOAct4 + $totalOAct5 + $totalOAct6 + $totalOAct7;
		
		if($i == 1){
			////imprimir encabezados
			if($tipo != 3){
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="100px">Linea</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="100px">Ruta</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="400px">Nombre</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="8" width="800px" align="center">Dias Fuera de Territorio</td>';
				$tabla .= '<tr><td '.$estilocabecera.' width="100px" align="center">'.$reg['NomOAct1'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$reg['NomOAct2'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$reg['NomOAct3'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$reg['NomOAct4'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$reg['NomOAct5'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$reg['NomOAct6'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$reg['NomOAct7'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Total</td></tr>';
			}else{
				$pdf->Ln();
				$pdf->Cell(50,10,'','LRT',0,'L',1);
				$pdf->Cell(50,10,'','LRT',0,'L',1);
				$pdf->Cell(200,10,'','LRT',0,'L',1);
				$pdf->Cell(400,10,'Dias Fuera de Territorio','LRT',0,'L',1);
				$pdf->Ln();	
				$pdf->Cell(50,10,'Linea','LRB',0,'L',1);
				$pdf->Cell(50,10,'Ruta','LRB',0,'L',1);
				$pdf->Cell(200,10,'Nombre','LRB',0,'L',1);
				$pdf->Cell(50,10,$reg['NomOAct1'],1,0,'C',1);
				$pdf->Cell(50,10,$reg['NomOAct2'],1,0,'C',1);
				$pdf->Cell(50,10,$reg['NomOAct3'],1,0,'C',1);
				$pdf->Cell(50,10,$reg['NomOAct4'],1,0,'C',1);
				$pdf->Cell(50,10,$reg['NomOAct5'],1,0,'C',1);
				$pdf->Cell(50,10,$reg['NomOAct6'],1,0,'C',1);
				$pdf->Cell(50,10,$reg['NomOAct7'],1,0,'C',1);
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
			$gteOAct1 = $reg['OAct1'];
			$gteOAct2 = $reg['OAct2'];
			$gteOAct3 = $reg['OAct3'];
			$gteOAct4 = $reg['OAct4'];
			$gteOAct5 = $reg['OAct5'];
			$gteOAct6 = $reg['OAct6'];
			$gteOAct7 = $reg['OAct7'];
			$gteTotal = $gteOAct1 + $gteOAct2 + $gteOAct3 + $gteOAct4 + $gteOAct5 + $gteOAct6 + $gteOAct7;
			
		}else{
			////sumas gerentes
			$gerente = $reg['REG_SNR'];
			if($tempGerente == $gerente){
				$sumOAct1 = $reg['OAct1'];
				$gteOAct1 += $sumOAct1;
				$sumOAct2 = $reg['OAct2'];
				$gteOAct2 += $sumOAct2;
				$sumOAct3 = $reg['OAct3'];
				$gteOAct3 += $sumOAct3;
				$sumOAct4 = $reg['OAct4'];
				$gteOAct4 += $sumOAct4;
				$sumOAct5 = $reg['OAct5'];
				$gteOAct5 += $sumOAct5;
				$sumOAct6 = $reg['OAct6'];
				$gteOAct6 += $sumOAct6;
				$sumOAct7 = $reg['OAct7'];
				$gteOAct7 += $sumOAct7;
				$gteTotal = $gteOAct1 + $gteOAct2 + $gteOAct3 + $gteOAct4 + $gteOAct5 + $gteOAct6 + $gteOAct7;
				
			}else{	
				////imprimir gerentes
				if($tipo != 3){				
					$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
					$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';	
					$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct6.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct7.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteTotal).'</td>';
					$tabla .= '</tr>';
				}else{ 
					$pdf->Cell(50,10,'',1,0,'L',1);
					$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
					$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
					$pdf->Cell(50,10,$gteOAct1,1,0,'C',1);
					$pdf->Cell(50,10,$gteOAct2,1,0,'C',1);
					$pdf->Cell(50,10,$gteOAct3,1,0,'C',1);
					$pdf->Cell(50,10,$gteOAct4,1,0,'C',1);
					$pdf->Cell(50,10,$gteOAct5,1,0,'C',1);
					$pdf->Cell(50,10,$gteOAct6,1,0,'C',1);
					$pdf->Cell(50,10,$gteOAct7,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteTotal),1,1,'C',1);
				}
	
				////inicia var gerente
				$tempGerente = $reg['REG_SNR'];
				$nombreGte = $reg['RM'];
				$rutaGte = $reg['Ruta_Gte'];
				$gteOAct1 = $reg['OAct1'];
				$gteOAct2 = $reg['OAct2'];
				$gteOAct3 = $reg['OAct3'];
				$gteOAct4 = $reg['OAct4'];
				$gteOAct5 = $reg['OAct5'];
				$gteOAct6 = $reg['OAct6'];
				$gteOAct7 = $reg['OAct7'];
				$gteTotal = $gteOAct1 + $gteOAct2 + $gteOAct3 + $gteOAct4 + $gteOAct5 + $gteOAct6 + $gteOAct7;
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';
		$total = $reg['OAct1'] + $reg['OAct2'] + $reg['OAct3'] + $reg['OAct4'] + $reg['OAct5'] + $reg['OAct6'] + $reg['OAct7'];
		
		////imprimir repres
		if($tipo != 3){
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Linea'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Ruta'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="400px">'.$reg['SR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['OAct1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['OAct2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['OAct3'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['OAct4'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['OAct5'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['OAct6'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['OAct7'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($total).'</td>';
			$tabla .= '</tr>';
		}else{
			$pdf->Cell(50,10,$reg['Linea'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['Ruta'],1,0,'L',0);
			$pdf->Cell(200,10,$reg['SR'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['OAct1'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['OAct2'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['OAct3'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['OAct4'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['OAct5'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['OAct6'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['OAct7'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($total),1,1,'C',0);
		}
		$i++;
	}
	
	////imprimir ultimo gerente
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px"></td>';
		$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct6.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteOAct7.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteTotal).'</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
		$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
		$pdf->Cell(50,10,$gteOAct1,1,0,'C',1);
		$pdf->Cell(50,10,$gteOAct2,1,0,'C',1);
		$pdf->Cell(50,10,$gteOAct3,1,0,'C',1);
		$pdf->Cell(50,10,$gteOAct4,1,0,'C',1);
		$pdf->Cell(50,10,$gteOAct5,1,0,'C',1);
		$pdf->Cell(50,10,$gteOAct6,1,0,'C',1);
		$pdf->Cell(50,10,$gteOAct7,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteTotal),1,1,'C',1);
	}	

	////imprimir nacional
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="400px">Total General</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalOAct1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalOAct2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalOAct3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalOAct4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalOAct5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalOAct6).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalOAct7).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalTotal).'</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(200,10,'Total General',1,0,'L',1);
		$pdf->Cell(50,10,number_format($totalOAct1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalOAct2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalOAct3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalOAct4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalOAct5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalOAct6),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalOAct7),1,0,'C',1);
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