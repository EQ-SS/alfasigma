<?php
	/*** cobertura de medicos ***/
	include "../conexion.php";
	
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$ciclo = $_POST['hdnCicloReporte'];
	
	$qMedicos = "DECLARE @CICLO as VARCHAR(36)
		DECLARE @DIAS_IN as DATE
		DECLARE @DIAS_FIN as DATE
		DECLARE @STATUS as VARCHAR(36)
		DECLARE @FREC1 as VARCHAR(36)
		DECLARE @FREC2 as VARCHAR(36)
		
		SET @CICLO = '".$ciclo."'
		/*SET @CICLO = (Select CYCLE_SNR from CYCLES where rec_stat=0 and NAME = '2023-06') */
		SET @DIAS_IN = (Select START_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @DIAS_FIN = (Select FINISH_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @STATUS = '19205DEC-F9F6-441A-9482-DB08D3394057'
		SET @FREC1 = 'B01CBA0E-20A3-45E0-849E-995180E9C9B8'
		SET @FREC2 = 'CB1E1D7C-770E-4511-BC25-BDEF452900E4'
		
		;with Ciclos as (select CYCLE_SNR, NAME, START_DATE, FINISH_DATE, ROW_NUMBER() over(order by NAME desc) as numero from CYCLES
		where CYCLE_SNR<>'00000000-0000-0000-0000-000000000000' and rec_stat=0)
		
		,Ciclo2 as (select top 1 C2.CYCLE_SNR, C2.NAME, C2.START_DATE, C2.FINISH_DATE from CYCLES
			left outer join Ciclos C1 on C1.CYCLE_SNR = @CICLO
			left outer join Ciclos C2 on C2.numero=C1.numero+1
			left outer join Ciclos C3 on C3.numero=C1.numero+2 )
		
		,Ciclo3 as (select top 1 C3.CYCLE_SNR, C3.NAME, C3.START_DATE, C3.FINISH_DATE from CYCLES
			left outer join Ciclos C1 on C1.CYCLE_SNR = @CICLO
			left outer join Ciclos C2 on C2.numero=C1.numero+1
			left outer join Ciclos C3 on C3.numero=C1.numero+2 )
		
		,MedsVis_Frec2_Ciclo1 as (Select VP1.pers_snr, klr1.reg_snr, P1.spec_snr from visitpers VP1, person P1, kloc_reg klr1
		where VP1.pers_snr = P1.pers_snr
		and VP1.rec_stat = 0
		and VP1.user_snr = klr1.kloc_snr
		and klr1.rec_stat = 0
		and klr1.kloc_snr in (select user_snr from users where rec_stat=0)
		and VP1.visit_date between @DIAS_IN and @DIAS_FIN
		and P1.status_snr = @STATUS 
		and P1.frecvis_snr = @FREC2
		and VP1.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		group by VP1.pers_snr, klr1.reg_snr, P1.spec_snr
		having count(VP1.pers_snr)>1 )
		
		,MedsVis_Frec2_Ciclo2 as (Select VP1.pers_snr, klr1.reg_snr, P1.spec_snr from visitpers VP1, person P1, kloc_reg klr1
		where VP1.pers_snr = P1.pers_snr
		and VP1.rec_stat = 0
		and VP1.user_snr = klr1.kloc_snr
		and klr1.rec_stat = 0
		and klr1.kloc_snr in (select user_snr from users where rec_stat=0)
		and VP1.visit_date between (select start_date from Ciclo2) and (select finish_date from Ciclo2)
		and P1.status_snr = @STATUS 
		and P1.frecvis_snr = @FREC2
		and VP1.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		group by VP1.pers_snr, klr1.reg_snr, P1.spec_snr
		having count(VP1.pers_snr)>1 )
		
		,MedsVis_Frec2_Ciclo3 as (Select VP1.pers_snr, klr1.reg_snr, P1.spec_snr from visitpers VP1, person P1, kloc_reg klr1
		where VP1.pers_snr = P1.pers_snr
		and VP1.rec_stat = 0
		and VP1.user_snr = klr1.kloc_snr
		and klr1.rec_stat = 0
		and klr1.kloc_snr in (select user_snr from users where rec_stat=0)
		and VP1.visit_date between (select start_date from Ciclo3) and (select finish_date from Ciclo3)
		and P1.status_snr = @STATUS 
		and P1.frecvis_snr = @FREC2
		and VP1.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		group by VP1.pers_snr, klr1.reg_snr, P1.spec_snr
		having count(VP1.pers_snr)>1 )
		
		
		Select 
		klr.REG_SNR,
		DM.lname + ' ' + DM.fname as RM,
		DM.user_nr as Ruta_Gte,
		CLIST.name as Esp,
		(select name from CYCLES where CYCLE_SNR = @CICLO) as Ciclo1,
		(select name from Ciclo2) as Ciclo2,
		(select name from Ciclo3) as Ciclo3,
		
		(Select count(distinct PLW1.pwork_snr) from perslocwork PLW1, person P1, pers_srep_work PSW1, kloc_reg klr1
		where PLW1.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW1.rec_stat = 0
		and PSW1.rec_stat = 0
		and P1.rec_stat = 0
		and P1.status_snr = @STATUS
		and P1.frecvis_snr = @FREC2
		and P1.spec_snr = CLIST.clist_snr
		and PLW1.pers_snr = P1.pers_snr
		and PSW1.pwork_snr = PLW1.pwork_snr 
		and PSW1.user_snr = klr1.kloc_snr
		and klr1.reg_snr = klr.reg_snr
		and klr1.rec_stat = 0
		and klr1.kloc_snr in (select user_snr from users where rec_stat=0)
		) as DR_NR_Frec2, 
		
		(Select count(MedsVis.pers_snr) from MedsVis_Frec2_Ciclo1 MedsVis
		where MedsVis.reg_snr = klr.reg_snr
		and MedsVis.spec_snr = CLIST.clist_snr
		) as Vis_Frec2_Ciclo1, 
		
		(Select count(MedsVis.pers_snr) from MedsVis_Frec2_Ciclo2 MedsVis
		where MedsVis.reg_snr = klr.reg_snr
		and MedsVis.spec_snr = CLIST.clist_snr
		) as Vis_Frec2_Ciclo2, 
		
		(Select count(MedsVis.pers_snr) from MedsVis_Frec2_Ciclo3 MedsVis
		where MedsVis.reg_snr = klr.reg_snr
		and MedsVis.spec_snr = CLIST.clist_snr
		) as Vis_Frec2_Ciclo3, 
		
		(Select count(distinct PLW1.pwork_snr) from perslocwork PLW1, person P1, pers_srep_work PSW1, kloc_reg klr1
		where PLW1.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW1.rec_stat = 0
		and PSW1.rec_stat = 0
		and P1.rec_stat = 0
		and P1.status_snr = @STATUS
		and P1.frecvis_snr = @FREC1
		and P1.spec_snr = CLIST.clist_snr
		and PLW1.pers_snr = P1.pers_snr
		and PSW1.pwork_snr = PLW1.pwork_snr 
		and PSW1.user_snr = klr1.kloc_snr
		and klr1.reg_snr = klr.reg_snr
		and klr1.rec_stat = 0
		and klr1.kloc_snr in (select user_snr from users where rec_stat=0)
		) as DR_NR_Frec1, 
		
		(Select count(VP1.pers_snr) from visitpers VP1, person P1, kloc_reg klr1
		where VP1.pers_snr = P1.pers_snr
		and VP1.rec_stat = 0
		and VP1.user_snr = klr1.kloc_snr
		and klr1.reg_snr = klr.reg_snr
		and klr1.rec_stat = 0
		and klr1.kloc_snr in (select user_snr from users where rec_stat=0)
		and VP1.visit_date between @DIAS_IN and @DIAS_FIN
		and P1.status_snr = @STATUS 
		and P1.frecvis_snr = @FREC1
		and P1.spec_snr = CLIST.clist_snr
		and VP1.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Frec1_Ciclo1, 
		
		(Select count(VP1.pers_snr) from visitpers VP1, person P1, kloc_reg klr1
		where VP1.pers_snr = P1.pers_snr
		and VP1.rec_stat = 0
		and VP1.user_snr = klr1.kloc_snr
		and klr1.reg_snr = klr.reg_snr
		and klr1.rec_stat = 0
		and klr1.kloc_snr in (select user_snr from users where rec_stat=0)
		and VP1.visit_date between (select start_date from Ciclo2) and (select finish_date from Ciclo2)
		and P1.status_snr = @STATUS 
		and P1.frecvis_snr = @FREC1
		and P1.spec_snr = CLIST.clist_snr
		and VP1.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Frec1_Ciclo2, 
		
		(Select count(VP1.pers_snr) from visitpers VP1, person P1, kloc_reg klr1
		where VP1.pers_snr = P1.pers_snr
		and VP1.rec_stat = 0
		and VP1.user_snr = klr1.kloc_snr
		and klr1.reg_snr = klr.reg_snr
		and klr1.rec_stat = 0
		and klr1.kloc_snr in (select user_snr from users where rec_stat=0)
		and VP1.visit_date between (select start_date from Ciclo3) and (select finish_date from Ciclo3)
		and P1.status_snr = @STATUS 
		and P1.frecvis_snr = @FREC1
		and P1.spec_snr = CLIST.clist_snr
		and VP1.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Frec1_Ciclo3 
		
		
		from users DM, (select distinct reg_snr, kloc_snr, rec_stat from kloc_reg) klr, pers_srep_work PSW, person P, codelist CLIST
		
		where klr.reg_snr = DM.user_snr
		and klr.rec_stat=0
		and DM.rec_stat=0
		and DM.Status in (1,2)
		and DM.User_type = 5
		and klr.kloc_snr = PSW.user_snr
		and PSW.pers_snr = P.pers_snr
		and P.spec_snr = CLIST.clist_snr
		and PSW.rec_stat=0
		and P.rec_stat=0
		and CLIST.rec_stat=0
		and klr.kloc_snr in ('".$ids."') 
		
		group by klr.reg_snr,DM.lname,DM.fname,DM.user_nr,P.spec_snr,CLIST.clist_snr,CLIST.name
		
		order by DM.user_nr,DM.lname,DM.fname,CLIST.name ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=reporteFrecuenciaEsp.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		ob_start();
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(1300,800));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Reporte Frecuencia por Especialidad'));
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

	$tamTabla = 1600;
	$tabla = '';
	if( $tipo != 3){
			$tabla .= '<table border="0">
				<tr>
					<td>
						<table>
							<tr>
								<td colspan="10" class="nombreReporte">Reporte Frecuencia por Especialidad</td>
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
			$pdf->SetLinewidth(1);
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
	$totalNumReps = 0;
	$totalMedsFrec2 = 0;
	$totalsumCobFrec2Ciclo3 = 0;
	$totalCobFrec2Ciclo3 = 0;
	$totalpromCobFrec2Ciclo3 = 0;
	$totalsumCobFrec2Ciclo2 = 0;
	$totalCobFrec2Ciclo2 = 0;
	$totalpromCobFrec2Ciclo2 = 0;
	$totalsumCobFrec2Ciclo1 = 0;
	$totalCobFrec2Ciclo1 = 0;
	$totalpromCobFrec2Ciclo1 = 0;
	$totalPromFrec2 = 0;
	$totalMedsFrec1 = 0;
	$totalsumCobFrec1Ciclo3 = 0;
	$totalCobFrec1Ciclo3 = 0;
	$totalpromCobFrec1Ciclo3 = 0;
	$totalsumCobFrec1Ciclo2 = 0;
	$totalCobFrec1Ciclo2 = 0;
	$totalpromCobFrec1Ciclo2 = 0;
	$totalsumCobFrec1Ciclo1 = 0;
	$totalCobFrec1Ciclo1 = 0;
	$totalpromCobFrec1Ciclo1 = 0;
	$totalPromFrec1 = 0;
	$totalFrecT = 0;

	while($reg = sqlsrv_fetch_array($rsMedicos)){
		////suma nacional
		$totalNumReps += 1;
		$totalMedsFrec2 += $reg['DR_NR_Frec2'];
		if ($reg['Vis_Frec2_Ciclo3'] > 0 && $reg['DR_NR_Frec2'] > 0){
			$totalsumCobFrec2Ciclo3 += ($reg['Vis_Frec2_Ciclo3'] / $reg['DR_NR_Frec2']) * 100;
		}else{
			$totalsumCobFrec2Ciclo3 += 0;
		}
		$totalCobFrec2Ciclo3 = $totalsumCobFrec2Ciclo3;
		$totalpromCobFrec2Ciclo3 = $totalCobFrec2Ciclo3 / $totalNumReps;
		if ($reg['Vis_Frec2_Ciclo2'] > 0 && $reg['DR_NR_Frec2'] > 0){
			$totalsumCobFrec2Ciclo2 += ($reg['Vis_Frec2_Ciclo2'] / $reg['DR_NR_Frec2']) * 100;
		}else{
			$totalsumCobFrec2Ciclo2 += 0;
		}
		$totalCobFrec2Ciclo2 = $totalsumCobFrec2Ciclo2;
		$totalpromCobFrec2Ciclo2 = $totalCobFrec2Ciclo2 / $totalNumReps;
		if ($reg['Vis_Frec2_Ciclo1'] > 0 && $reg['DR_NR_Frec2'] > 0){
			$totalsumCobFrec2Ciclo1 += ($reg['Vis_Frec2_Ciclo1'] / $reg['DR_NR_Frec2']) * 100;
		}else{
			$totalsumCobFrec2Ciclo1 += 0;
		}
		$totalCobFrec2Ciclo1 = $totalsumCobFrec2Ciclo1;
		$totalpromCobFrec2Ciclo1 = $totalCobFrec2Ciclo1 / $totalNumReps;
		$totalPromFrec2 = ($totalpromCobFrec2Ciclo3 + $totalpromCobFrec2Ciclo2 + $totalpromCobFrec2Ciclo1) / 3;
		$totalMedsFrec1 += $reg['DR_NR_Frec1'];
		if ($reg['Vis_Frec1_Ciclo3'] > 0 && $reg['DR_NR_Frec1'] > 0){
			$totalsumCobFrec1Ciclo3 += ($reg['Vis_Frec1_Ciclo3'] / $reg['DR_NR_Frec1']) * 100;
		}else{
			$totalsumCobFrec1Ciclo3 += 0;
		}
		$totalCobFrec1Ciclo3 = $totalsumCobFrec1Ciclo3;
		$totalpromCobFrec1Ciclo3 = $totalCobFrec1Ciclo3 / $totalNumReps;
		if ($reg['Vis_Frec1_Ciclo2'] > 0 && $reg['DR_NR_Frec1'] > 0){
			$totalsumCobFrec1Ciclo2 += ($reg['Vis_Frec1_Ciclo2'] / $reg['DR_NR_Frec1']) * 100;
		}else{
			$totalsumCobFrec1Ciclo2 += 0;
		}
		$totalCobFrec1Ciclo2 = $totalsumCobFrec1Ciclo2;
		$totalpromCobFrec1Ciclo2 = $totalCobFrec1Ciclo2 / $totalNumReps;
		if ($reg['Vis_Frec1_Ciclo1'] > 0 && $reg['DR_NR_Frec1'] > 0){
			$totalsumCobFrec1Ciclo1 += ($reg['Vis_Frec1_Ciclo1'] / $reg['DR_NR_Frec1']) * 100;
		}else{
			$totalsumCobFrec1Ciclo1 += 0;
		}
		$totalCobFrec1Ciclo1 = $totalsumCobFrec1Ciclo1;
		$totalpromCobFrec1Ciclo1 = $totalCobFrec1Ciclo1 / $totalNumReps;
		$totalPromFrec1 = ($totalpromCobFrec1Ciclo3 + $totalpromCobFrec1Ciclo2 + $totalpromCobFrec1Ciclo1) / 3;
		$totalFrecT = (($totalMedsFrec2 * $totalPromFrec2) + ($totalMedsFrec1 * $totalPromFrec1)) / ($totalMedsFrec2 + $totalMedsFrec1);
		
		if($i == 1){
			////imprimir encabezados
			if($tipo != 3){
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="100px">Gerente</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="300px">Especialidad</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="5" width="500px" align="center">Frecuencia 2</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="5" width="500px" align="center">Frecuencia 1</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="100px" align="center">Total Frecuencia</td></tr>';
				$tabla .= '<tr><td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Ciclo '.$reg['Ciclo3'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Ciclo '.$reg['Ciclo2'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Ciclo '.$reg['Ciclo1'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Prom</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Ciclo '.$reg['Ciclo3'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Ciclo '.$reg['Ciclo2'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Ciclo '.$reg['Ciclo1'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Prom</td>';
			}else{
				$pdf->Ln();	
				$pdf->Cell(50,10,'','LRT',0,'L',1);
				$pdf->Cell(150,10,'','LRT',0,'L',1);
				$pdf->Cell(250,10,'Frecuencia 2','LRT',0,'C',1);
				$pdf->Cell(250,10,'Frecuencia 1','LRT',0,'C',1);
				$pdf->Cell(50,10,'','LRT',0,'C',1);
				$pdf->Ln();	
				$pdf->Cell(50,10,'Gerente','LRB',0,'C',1);
				$pdf->Cell(200,10,'Especialidad','LRB',0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Ciclo '.$reg['Ciclo3'],1,0,'C',1);
				$pdf->Cell(50,10,'Ciclo '.$reg['Ciclo2'],1,0,'C',1);
				$pdf->Cell(50,10,'Ciclo '.$reg['Ciclo1'],1,0,'C',1);
				$pdf->Cell(50,10,'Prom',1,0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Ciclo '.$reg['Ciclo3'],1,0,'C',1);
				$pdf->Cell(50,10,'Ciclo '.$reg['Ciclo2'],1,0,'C',1);
				$pdf->Cell(50,10,'Ciclo '.$reg['Ciclo1'],1,0,'C',1);
				$pdf->Cell(50,10,'Prom',1,0,'C',1);
				$pdf->Cell(50,10,'Total Frecuencia','LRB',0,'C',1);
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
			$gteNumReps = 1;
			$gteMedsFrec2 = $reg['DR_NR_Frec2'];
			if ($reg['Vis_Frec2_Ciclo3'] > 0 && $reg['DR_NR_Frec2'] > 0){
				$gteCobFrec2Ciclo3 = ($reg['Vis_Frec2_Ciclo3'] / $reg['DR_NR_Frec2']) * 100;
			}else{
				$gteCobFrec2Ciclo3 = 0;
			}
			$gtepromCobFrec2Ciclo3 = $gteCobFrec2Ciclo3;
			if ($reg['Vis_Frec2_Ciclo2'] > 0 && $reg['DR_NR_Frec2'] > 0){
				$gteCobFrec2Ciclo2 = ($reg['Vis_Frec2_Ciclo2'] / $reg['DR_NR_Frec2']) * 100;
			}else{
				$gteCobFrec2Ciclo2 = 0;
			}
			$gtepromCobFrec2Ciclo2 = $gteCobFrec2Ciclo2;
			if ($reg['Vis_Frec2_Ciclo1'] > 0 && $reg['DR_NR_Frec2'] > 0){
				$gteCobFrec2Ciclo1 = ($reg['Vis_Frec2_Ciclo1'] / $reg['DR_NR_Frec2']) * 100;
			}else{
				$gteCobFrec2Ciclo1 = 0;
			}
			$gtepromCobFrec2Ciclo1 = $gteCobFrec2Ciclo1;
			$gtePromFrec2 = ($gtepromCobFrec2Ciclo3 + $gtepromCobFrec2Ciclo2 + $gtepromCobFrec2Ciclo1) / 3;
			$gteMedsFrec1 = $reg['DR_NR_Frec1'];
			if ($reg['Vis_Frec1_Ciclo3'] > 0 && $reg['DR_NR_Frec1'] > 0){
				$gteCobFrec1Ciclo3 = ($reg['Vis_Frec1_Ciclo3'] / $reg['DR_NR_Frec1']) * 100;
			}else{
				$gteCobFrec1Ciclo3 = 0;
			}
			$gtepromCobFrec1Ciclo3 = $gteCobFrec1Ciclo3;
			if ($reg['Vis_Frec1_Ciclo2'] > 0 && $reg['DR_NR_Frec1'] > 0){
				$gteCobFrec1Ciclo2 = ($reg['Vis_Frec1_Ciclo2'] / $reg['DR_NR_Frec1']) * 100;
			}else{
				$gteCobFrec1Ciclo2 = 0;
			}
			$gtepromCobFrec1Ciclo2 = $gteCobFrec1Ciclo2;
			if ($reg['Vis_Frec1_Ciclo1'] > 0 && $reg['DR_NR_Frec1'] > 0){
				$gteCobFrec1Ciclo1 = ($reg['Vis_Frec1_Ciclo1'] / $reg['DR_NR_Frec1']) * 100;
			}else{
				$gteCobFrec1Ciclo1 = 0;
			}
			$gtepromCobFrec1Ciclo1 = $gteCobFrec1Ciclo1;
			$gtePromFrec1 = ($gtepromCobFrec1Ciclo3 + $gtepromCobFrec1Ciclo2 + $gtepromCobFrec1Ciclo1) / 3;
			$gteFrecT = (($gteMedsFrec2 * $gtePromFrec2) + ($gteMedsFrec1 * $gtePromFrec1)) / ($gteMedsFrec2 + $gteMedsFrec1);
			
		}else{
			////sumas gerentes
			$gerente = $reg['REG_SNR'];
			if($tempGerente == $gerente){
				$gteNumReps += 1;
				$sumMedsFrec2 = $reg['DR_NR_Frec2'];
				$gteMedsFrec2 += $sumMedsFrec2;
				if ($reg['Vis_Frec2_Ciclo3'] > 0 && $reg['DR_NR_Frec2'] > 0){
					$sumCobFrec2Ciclo3 = ($reg['Vis_Frec2_Ciclo3'] / $reg['DR_NR_Frec2']) * 100;
				}else{
					$sumCobFrec2Ciclo3 = 0;
				}
				$gteCobFrec2Ciclo3 += $sumCobFrec2Ciclo3;
				$gtesumCobFrec2Ciclo3 = $gteCobFrec2Ciclo3;
				$gtepromCobFrec2Ciclo3 = $gtesumCobFrec2Ciclo3 / $gteNumReps;
				if ($reg['Vis_Frec2_Ciclo2'] > 0 && $reg['DR_NR_Frec2'] > 0){
					$sumCobFrec2Ciclo2 = ($reg['Vis_Frec2_Ciclo2'] / $reg['DR_NR_Frec2']) * 100;
				}else{
					$sumCobFrec2Ciclo2 = 0;
				}
				$gteCobFrec2Ciclo2 += $sumCobFrec2Ciclo2;
				$gtesumCobFrec2Ciclo2 = $gteCobFrec2Ciclo2;
				$gtepromCobFrec2Ciclo2 = $gtesumCobFrec2Ciclo2 / $gteNumReps;
				if ($reg['Vis_Frec2_Ciclo1'] > 0 && $reg['DR_NR_Frec2'] > 0){
					$sumCobFrec2Ciclo1 = ($reg['Vis_Frec2_Ciclo1'] / $reg['DR_NR_Frec2']) * 100;
				}else{
					$sumCobFrec2Ciclo1 = 0;
				}
				$gteCobFrec2Ciclo1 += $sumCobFrec2Ciclo1;
				$gtesumCobFrec2Ciclo1 = $gteCobFrec2Ciclo1;
				$gtepromCobFrec2Ciclo1 = $gtesumCobFrec2Ciclo1 / $gteNumReps;
				$gtePromFrec2 = ($gtepromCobFrec2Ciclo3 + $gtepromCobFrec2Ciclo2 + $gtepromCobFrec2Ciclo1) / 3;
				$sumMedsFrec1 = $reg['DR_NR_Frec1'];
				$gteMedsFrec1 += $sumMedsFrec1;
				if ($reg['Vis_Frec1_Ciclo3'] > 0 && $reg['DR_NR_Frec1'] > 0){
					$sumCobFrec1Ciclo3 = ($reg['Vis_Frec1_Ciclo3'] / $reg['DR_NR_Frec1']) * 100;
				}else{
					$sumCobFrec1Ciclo3 = 0;
				}
				$gteCobFrec1Ciclo3 += $sumCobFrec1Ciclo3;
				$gtesumCobFrec1Ciclo3 = $gteCobFrec1Ciclo3;
				$gtepromCobFrec1Ciclo3 = $gtesumCobFrec1Ciclo3 / $gteNumReps;
				if ($reg['Vis_Frec1_Ciclo2'] > 0 && $reg['DR_NR_Frec1'] > 0){
					$sumCobFrec1Ciclo2 = ($reg['Vis_Frec1_Ciclo2'] / $reg['DR_NR_Frec1']) * 100;
				}else{
					$sumCobFrec1Ciclo2 = 0;
				}
				$gteCobFrec1Ciclo2 += $sumCobFrec1Ciclo2;
				$gtesumCobFrec1Ciclo2 = $gteCobFrec1Ciclo2;
				$gtepromCobFrec1Ciclo2 = $gtesumCobFrec1Ciclo2 / $gteNumReps;
				if ($reg['Vis_Frec1_Ciclo1'] > 0 && $reg['DR_NR_Frec1'] > 0){
					$sumCobFrec1Ciclo1 = ($reg['Vis_Frec1_Ciclo1'] / $reg['DR_NR_Frec1']) * 100;
				}else{
					$sumCobFrec1Ciclo1 = 0;
				}
				$gteCobFrec1Ciclo1 += $sumCobFrec1Ciclo1;
				$gtesumCobFrec1Ciclo1 = $gteCobFrec1Ciclo1;
				$gtepromCobFrec1Ciclo1 = $gtesumCobFrec1Ciclo1 / $gteNumReps;
				$gtePromFrec1 = ($gtepromCobFrec1Ciclo3 + $gtepromCobFrec1Ciclo2 + $gtepromCobFrec1Ciclo1) / 3;
				$gteFrecT = (($gteMedsFrec2 * $gtePromFrec2) + ($gteMedsFrec1 * $gtePromFrec1)) / ($gteMedsFrec2 + $gteMedsFrec1);
				
			}else{	
				////imprimir gerentes
				if($tipo != 3){				
					$tabla .= '<tr><td '.$estilogte.' width="100px">'.$rutaGte.'</td>';	
					$tabla .= '<td '.$estilogte.' width="300px"> </td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMedsFrec2).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtepromCobFrec2Ciclo3).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtepromCobFrec2Ciclo2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtepromCobFrec2Ciclo1).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePromFrec2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMedsFrec1).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtepromCobFrec1Ciclo3).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtepromCobFrec1Ciclo2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtepromCobFrec1Ciclo1).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePromFrec1).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteFrecT).' %</td>';
					$tabla .= '</tr>';
				}else{ 
					$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
					$pdf->Cell(150,10,'',1,0,'L',1);
					$pdf->Cell(50,10,number_format($gteMedsFrec2),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gtepromCobFrec2Ciclo3).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtepromCobFrec2Ciclo2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtepromCobFrec2Ciclo1).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePromFrec2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gteMedsFrec1),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gtepromCobFrec1Ciclo3).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtepromCobFrec1Ciclo2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtepromCobFrec1Ciclo1).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePromFrec1).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gteFrecT).' %',1,1,'R',1);
				}
	
				////inicia var gerente
				$tempGerente = $reg['REG_SNR'];
				$nombreGte = $reg['RM'];
				$rutaGte = $reg['Ruta_Gte'];
				$gteNumReps = 1;
				$gteMedsFrec2 = $reg['DR_NR_Frec2'];
				if ($reg['Vis_Frec2_Ciclo3'] > 0 && $reg['DR_NR_Frec2'] > 0){
					$gteCobFrec2Ciclo3 = ($reg['Vis_Frec2_Ciclo3'] / $reg['DR_NR_Frec2']) * 100;
				}else{
					$gteCobFrec2Ciclo3 = 0;
				}
				$gtepromCobFrec2Ciclo3 = $gteCobFrec2Ciclo3;
				if ($reg['Vis_Frec2_Ciclo2'] > 0 && $reg['DR_NR_Frec2'] > 0){
					$gteCobFrec2Ciclo2 = ($reg['Vis_Frec2_Ciclo2'] / $reg['DR_NR_Frec2']) * 100;
				}else{
					$gteCobFrec2Ciclo2 = 0;
				}
				$gtepromCobFrec2Ciclo2 = $gteCobFrec2Ciclo2;
				if ($reg['Vis_Frec2_Ciclo1'] > 0 && $reg['DR_NR_Frec2'] > 0){
					$gteCobFrec2Ciclo1 = ($reg['Vis_Frec2_Ciclo1'] / $reg['DR_NR_Frec2']) * 100;
				}else{
					$gteCobFrec2Ciclo1 = 0;
				}
				$gtepromCobFrec2Ciclo1 = $gteCobFrec2Ciclo1;
				$gtePromFrec2 = ($gtepromCobFrec2Ciclo3 + $gtepromCobFrec2Ciclo2 + $gtepromCobFrec2Ciclo1) / 3;
				$gteMedsFrec1 = $reg['DR_NR_Frec1'];
				if ($reg['Vis_Frec1_Ciclo3'] > 0 && $reg['DR_NR_Frec1'] > 0){
					$gteCobFrec1Ciclo3 = ($reg['Vis_Frec1_Ciclo3'] / $reg['DR_NR_Frec1']) * 100;
				}else{
					$gteCobFrec1Ciclo3 = 0;
				}
				$gtepromCobFrec1Ciclo3 = $gteCobFrec1Ciclo3;
				if ($reg['Vis_Frec1_Ciclo2'] > 0 && $reg['DR_NR_Frec1'] > 0){
					$gteCobFrec1Ciclo2 = ($reg['Vis_Frec1_Ciclo2'] / $reg['DR_NR_Frec1']) * 100;
				}else{
					$gteCobFrec1Ciclo2 = 0;
				}
				$gtepromCobFrec1Ciclo2 = $gteCobFrec1Ciclo2;
				if ($reg['Vis_Frec1_Ciclo1'] > 0 && $reg['DR_NR_Frec1'] > 0){
					$gteCobFrec1Ciclo1 = ($reg['Vis_Frec1_Ciclo1'] / $reg['DR_NR_Frec1']) * 100;
				}else{
					$gteCobFrec1Ciclo1 = 0;
				}
				$gtepromCobFrec1Ciclo1 = $gteCobFrec1Ciclo1;
				$gtePromFrec1 = ($gtepromCobFrec1Ciclo3 + $gtepromCobFrec1Ciclo2 + $gtepromCobFrec1Ciclo1) / 3;
				$gteFrecT = (($gteMedsFrec2 * $gtePromFrec2) + ($gteMedsFrec1 * $gtePromFrec1)) / ($gteMedsFrec2 + $gteMedsFrec1);
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';
		if ($reg['Vis_Frec2_Ciclo3'] > 0 && $reg['DR_NR_Frec2'] > 0){
			$CobFrec2Ciclo3 = ($reg['Vis_Frec2_Ciclo3'] / $reg['DR_NR_Frec2']) * 100;
		}else{
			$CobFrec2Ciclo3 = 0;
		}
		if ($reg['Vis_Frec2_Ciclo2'] > 0 && $reg['DR_NR_Frec2'] > 0){
			$CobFrec2Ciclo2 = ($reg['Vis_Frec2_Ciclo2'] / $reg['DR_NR_Frec2']) * 100;
		}else{
			$CobFrec2Ciclo2 = 0;
		}
		if ($reg['Vis_Frec2_Ciclo1'] > 0 && $reg['DR_NR_Frec2'] > 0){
			$CobFrec2Ciclo1 = ($reg['Vis_Frec2_Ciclo1'] / $reg['DR_NR_Frec2']) * 100;
		}else{
			$CobFrec2Ciclo1 = 0;
		}
		$PromFrec2 = ($CobFrec2Ciclo3 + $CobFrec2Ciclo2 + $CobFrec2Ciclo1) / 3;
		if ($reg['Vis_Frec1_Ciclo3'] > 0 && $reg['DR_NR_Frec1'] > 0){
			$CobFrec1Ciclo3 = ($reg['Vis_Frec1_Ciclo3'] / $reg['DR_NR_Frec1']) * 100;
		}else{
			$CobFrec1Ciclo3 = 0;
		}
		if ($reg['Vis_Frec1_Ciclo2'] > 0 && $reg['DR_NR_Frec1'] > 0){
			$CobFrec1Ciclo2 = ($reg['Vis_Frec1_Ciclo2'] / $reg['DR_NR_Frec1']) * 100;
		}else{
			$CobFrec1Ciclo2 = 0;
		}
		if ($reg['Vis_Frec1_Ciclo1'] > 0 && $reg['DR_NR_Frec1'] > 0){
			$CobFrec1Ciclo1 = ($reg['Vis_Frec1_Ciclo1'] / $reg['DR_NR_Frec1']) * 100;
		}else{
			$CobFrec1Ciclo1 = 0;
		}
		$PromFrec1 = ($CobFrec1Ciclo3 + $CobFrec1Ciclo2 + $CobFrec1Ciclo1) / 3;
		$FrecT = (($reg['DR_NR_Frec2'] * $PromFrec2) + ($reg['DR_NR_Frec1'] * $PromFrec1)) / ($reg['DR_NR_Frec2'] + $reg['DR_NR_Frec1']);
		
		////imprimir repres
		if($tipo != 3){
			$tabla .= '<td '.$estilorepre.' width="100px">'.$rutaGte.'</td>';
			$tabla .= '<td '.$estilorepre.' width="300px">'.$reg['Esp'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Frec2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($CobFrec2Ciclo3).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($CobFrec2Ciclo2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($CobFrec2Ciclo1).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PromFrec2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Frec1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($CobFrec1Ciclo3).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($CobFrec1Ciclo2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($CobFrec1Ciclo1).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PromFrec1).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($FrecT).' %</td>';
			$tabla .= '</tr>';
		}else{
			$pdf->Cell(50,10,$rutaGte,1,0,'L',0);
			$pdf->Cell(150,10,$reg['Esp'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['DR_NR_Frec2'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($CobFrec2Ciclo3).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($CobFrec2Ciclo2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($CobFrec2Ciclo1).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PromFrec2).' %',1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR_Frec1'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($CobFrec1Ciclo3).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($CobFrec1Ciclo2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($CobFrec1Ciclo1).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PromFrec1).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($FrecT).' %',1,1,'R',0);
		}
		$i++;
	}
	
	////imprimir ultimo gerente
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px">'.$rutaGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="300px"> </td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMedsFrec2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtepromCobFrec2Ciclo3).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtepromCobFrec2Ciclo2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtepromCobFrec2Ciclo1).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePromFrec2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMedsFrec1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtepromCobFrec1Ciclo3).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtepromCobFrec1Ciclo2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtepromCobFrec1Ciclo1).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePromFrec1).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteFrecT).' %</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
		$pdf->Cell(150,10,'',1,0,'L',1);
		$pdf->Cell(50,10,number_format($gteMedsFrec2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gtepromCobFrec2Ciclo3).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtepromCobFrec2Ciclo2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtepromCobFrec2Ciclo1).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePromFrec2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gteMedsFrec1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gtepromCobFrec1Ciclo3).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtepromCobFrec1Ciclo2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtepromCobFrec1Ciclo1).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePromFrec1).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gteFrecT).' %',1,1,'R',1);
	}	

	////imprimir nacional
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px"></td>';
		$tabla .= '<td '.$estilogte.' width="300px">Total General</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMedsFrec2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalpromCobFrec2Ciclo3).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalpromCobFrec2Ciclo2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalpromCobFrec2Ciclo1).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPromFrec2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMedsFrec1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalpromCobFrec1Ciclo3).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalpromCobFrec1Ciclo2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalpromCobFrec1Ciclo1).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPromFrec1).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalFrecT).' %</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(150,10,'Total General',1,0,'L',1);
		$pdf->Cell(50,10,number_format($totalMedsFrec2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalpromCobFrec2Ciclo3).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalpromCobFrec2Ciclo2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalpromCobFrec2Ciclo1).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPromFrec2).' %',1,0,'R',1);		
		$pdf->Cell(50,10,number_format($totalMedsFrec1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalpromCobFrec1Ciclo3).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalpromCobFrec1Ciclo2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalpromCobFrec1Ciclo1).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPromFrec1).' %',1,0,'R',1);		
		$pdf->Cell(50,10,number_format($totalFrecT).' %',1,1,'R',1);		
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