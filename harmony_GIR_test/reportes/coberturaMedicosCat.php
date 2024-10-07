<?php
	/*** cobertura de medicos ***/
	include "../conexion.php";
			
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$ciclo = $_POST['hdnCicloReporte'];
	
	$qMedicos = "DECLARE @CICLO as VARCHAR(36)
		DECLARE @DIAS_IN as DATE
		DECLARE @DIAS_FIN as DATE
		DECLARE @Dias_ciclo as FLOAT
		DECLARE @Cuota as FLOAT
		DECLARE @STATUS as VARCHAR(36)
		DECLARE @STATUS_INST as VARCHAR(36)
		DECLARE @Cat1 as VARCHAR(36)
		DECLARE @Cat2 as VARCHAR(36)
		DECLARE @Cat3 as VARCHAR(36)
		DECLARE @Cat4 as VARCHAR(36)
		DECLARE @Cat5 as VARCHAR(36)
		
		SET @CICLO = '".$ciclo."'
		/*SET @CICLO = (Select CYCLE_SNR from CYCLES where rec_stat=0 and NAME = '2023-02') */
		SET @DIAS_IN = (Select START_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @DIAS_FIN = (Select FINISH_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @Dias_ciclo = (Select cast(DAYS as int) from CYCLES where CYCLE_SNR = @CICLO)
		SET @Cuota = (Select cast(CONTACTS as int) from CYCLES where CYCLE_SNR = @CICLO)
		SET @STATUS = '19205DEC-F9F6-441A-9482-DB08D3394057'
		SET @STATUS_INST = 'C1141A15-E7AD-4099-A8D4-26C571298B21'
		SET @Cat1 = 'A23008D4-E873-4335-9A0E-F4FFC3D68A35' /*A*/
		SET @Cat2 = '183A3C90-B876-4148-BBBA-52DE4D8BB851' /*B*/
		SET @Cat3 = '69E26C7C-B4F1-410E-9241-C2D0B112A7E3' /*C*/
		SET @Cat4 = '14FC1F87-4B38-45F4-9DC7-582983EE6987' /*D*/
		SET @Cat5 = 'ED08AF79-A9C8-4A1C-82A9-08A598625C3E' /*SC*/
		
		;with Horas as (select ROW_NUMBER() over(partition by DAY_REPORT.user_snr, DAY_REPORT.DATE, DAY_REPORT_CODE.DAY_CODE_SNR order by DAY_REPORT.user_snr, DAY_REPORT.DATE, DAY_REPORT_CODE.DAY_CODE_SNR) as orden,
		DAY_REPORT.USER_SNR, DAY_REPORT.DATE, DAY_REPORT_CODE.DAY_CODE_SNR, DAY_REPORT.CREATOR_USER_SNR, DAY_REPORT_CODE.DAYREPORT_SNR, DAY_REPORT_CODE.DAYREPCOD_SNR/*,COUNT(*)tot*/
		from DAY_REPORT, DAY_REPORT_CODE
		where DAY_REPORT.DAYREPORT_SNR=DAY_REPORT_CODE.DAYREPORT_SNR
		and DAY_REPORT.rec_stat=0
		and DAY_REPORT_CODE.rec_stat=0
		and DAY_REPORT.DAYREPORT_SNR<>'00000000-0000-0000-0000-000000000000'
		and cast(DAY_REPORT.DATE as DATE) not in (select cast(c_date as DATE) from CYCLE_DETAILS where CYCLE_SNR = @CICLO and rec_stat=0 and c_day=0 and datepart(DW,c_date) not in (1,7))
		and DAY_REPORT.date between @DIAS_IN and @DIAS_FIN )
		/* order by DAY_REPORT.user_snr, DAY_REPORT.DATE desc, DAY_REPORT_CODE.DAY_CODE_SNR ) */
		
		Select 
		LINEA.name as Linea,
		klr.REG_SNR,
		DM.lname + ' ' + DM.fname as RM,
		DM.user_nr as Ruta_Gte,
		MR.user_nr as Ruta,
		MR.lname + ' ' + MR.fname as SR,
		@DIAS_IN as Inicio_Ciclo,
		@DIAS_FIN as Fin_Ciclo,
		@Dias_ciclo as Dias_Ciclo,
		@Cuota as Cuota,
		(@Cuota * @Dias_ciclo) as Contactos,
		
		'A' as Cat1,
		'B' as Cat2,
		'C' as Cat3,
		'D' as Cat4,
		'SC' as Cat5,
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and P.category_snr = @Cat1
		and P.pers_snr = PLW.pers_snr
		and PLW.pwork_snr = PSW.pwork_snr
		and PSW.user_snr = MR.user_snr
		) as DR_NR_Cat1,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and P.category_snr = @Cat1
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot_Cat1, 
		
		(Select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat1
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Cat1, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat1
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida_Cat1,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat1
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem_Cat1, 
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and P.category_snr = @Cat2
		and P.pers_snr = PLW.pers_snr
		and PLW.pwork_snr = PSW.pwork_snr
		and PSW.user_snr = MR.user_snr
		) as DR_NR_Cat2,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and P.category_snr = @Cat2
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot_Cat2, 
		
		(Select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat2
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Cat2, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat2
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida_Cat2,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat2
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem_Cat2, 
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and P.category_snr = @Cat3
		and P.pers_snr = PLW.pers_snr
		and PLW.pwork_snr = PSW.pwork_snr
		and PSW.user_snr = MR.user_snr
		) as DR_NR_Cat3,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and P.category_snr = @Cat3
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot_Cat3, 
		
		(Select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat3
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Cat3, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat3
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida_Cat3,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat3
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem_Cat3, 
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and P.category_snr = @Cat4
		and P.pers_snr = PLW.pers_snr
		and PLW.pwork_snr = PSW.pwork_snr
		and PSW.user_snr = MR.user_snr
		) as DR_NR_Cat4,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and P.category_snr = @Cat4
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot_Cat4, 
		
		(Select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat4
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Cat4, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat4
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida_Cat4,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat4
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem_Cat4, 
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and P.category_snr = @Cat5
		and P.pers_snr = PLW.pers_snr
		and PLW.pwork_snr = PSW.pwork_snr
		and PSW.user_snr = MR.user_snr
		) as DR_NR_Cat5,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and P.category_snr = @Cat5
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot_Cat5, 
		
		(Select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat5
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Cat5, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat5
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida_Cat5,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.category_snr = @Cat5
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem_Cat5, 
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and P.pers_snr = PLW.pers_snr
		and PLW.pwork_snr = PSW.pwork_snr
		and PSW.user_snr = MR.user_snr
		) as DR_NR,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot, 
		
		(Select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as One_Vis, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem 
		
		
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
		
		Order by DM.user_nr,DM.lname,DM.fname,MR.user_nr,MR.lname,MR.fname,klr.reg_snr ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=reporteCoberturaCateg.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		ob_start();
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(1300,2700));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Reporte de Cobertura por Categoria'));
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

	$tamTabla = 5400;
	$tabla = '';
	if( $tipo != 3){
			$tabla .= '<table border="0">
				<tr>
					<td>
						<table>
							<tr>
								<td colspan="10" class="nombreReporte">Reporte de Cobertura por Categoria</td>
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
	$totalMeds = 0;
	$totalVis = 0;
	$totalVisTot = 0;
	$totalRevis = 0;
	$totalVisFallida = 0;
	$totalAccionComp = 0;
	$totalAlcance = 0;
	$totalMedsCat1 = 0;
	$totalVisCat1 = 0;
	$totalVisTotCat1 = 0;
	$totalRevisCat1 = 0;
	$totalVisFallidaCat1 = 0;
	$totalAccionCompCat1 = 0;
	$totalAlcanceCat1 = 0;
	$totalPartCat1 = 0;
	$totalMedsCat2 = 0;
	$totalVisCat2 = 0;
	$totalVisTotCat2 = 0;
	$totalRevisCat2 = 0;
	$totalVisFallidaCat2 = 0;
	$totalAccionCompCat2 = 0;
	$totalAlcanceCat2 = 0;
	$totalPartCat2 = 0;
	$totalMedsCat3 = 0;
	$totalVisCat3 = 0;
	$totalVisTotCat3 = 0;
	$totalRevisCat3 = 0;
	$totalVisFallidaCat3 = 0;
	$totalAccionCompCat3 = 0;
	$totalAlcanceCat3 = 0;
	$totalPartCat3 = 0;
	$totalMedsCat4 = 0;
	$totalVisCat4 = 0;
	$totalVisTotCat4 = 0;
	$totalRevisCat4 = 0;
	$totalVisFallidaCat4 = 0;
	$totalAccionCompCat4 = 0;
	$totalAlcanceCat4 = 0;
	$totalPartCat4 = 0;
	$totalMedsCat5 = 0;
	$totalVisCat5 = 0;
	$totalVisTotCat5 = 0;
	$totalRevisCat5 = 0;
	$totalVisFallidaCat5 = 0;
	$totalAccionCompCat5 = 0;
	$totalAlcanceCat5 = 0;
	$totalPartCat5 = 0;

	while($reg = sqlsrv_fetch_array($rsMedicos)){
		////suma nacional
		$totalMeds += $reg['DR_NR'];
		$totalVis += $reg['One_Vis'];
		$totalVisTot += $reg['VisTot'];
		$totalRevis += $reg['VisTot'] - $reg['One_Vis'];
		$totalVisFallida += $reg['Vis_Fallida'];
		$totalAccionComp += $reg['VisAcc_Complem'];
		if ($totalMeds > 0 && $totalVis > 0 ){
			$totalAlcance = ($totalVis / $totalMeds) * 100;
		}else{ 
			$totalAlcance = 0;
		}
		$totalMedsCat1 += $reg['DR_NR_Cat1'];
		$totalVisCat1 += $reg['Vis_Cat1'];
		$totalVisTotCat1 += $reg['VisTot_Cat1'];
		$totalRevisCat1 += $reg['VisTot_Cat1'] - $reg['Vis_Cat1'];
		$totalVisFallidaCat1 += $reg['Vis_Fallida_Cat1'];
		$totalAccionCompCat1 += $reg['VisAcc_Complem_Cat1'];
		if ($totalMedsCat1 > 0 && $totalVisCat1 > 0 ){
			$totalAlcanceCat1 = ($totalVisCat1 / $totalMedsCat1) * 100;
		}else{ 
			$totalAlcanceCat1 = 0;
		}
		if ($totalMedsCat1 > 0 && $totalMeds > 0 ){
			$totalPartCat1 = ($totalMedsCat1 / $totalMeds) * 100;
		}else{ 
			$totalPartCat1 = 0;
		}
		$totalMedsCat2 += $reg['DR_NR_Cat2'];
		$totalVisCat2 += $reg['Vis_Cat2'];
		$totalVisTotCat2 += $reg['VisTot_Cat2'];
		$totalRevisCat2 += $reg['VisTot_Cat2'] - $reg['Vis_Cat2'];
		$totalVisFallidaCat2 += $reg['Vis_Fallida_Cat2'];
		$totalAccionCompCat2 += $reg['VisAcc_Complem_Cat2'];
		if ($totalMedsCat2 > 0 && $totalVisCat2 > 0 ){
			$totalAlcanceCat2 = ($totalVisCat2 / $totalMedsCat2) * 100;
		}else{ 
			$totalAlcanceCat2 = 0;
		}
		if ($totalMedsCat2 > 0 && $totalMeds > 0 ){
			$totalPartCat2 = ($totalMedsCat2 / $totalMeds) * 100;
		}else{ 
			$totalPartCat2 = 0;
		}
		$totalMedsCat3 += $reg['DR_NR_Cat3'];
		$totalVisCat3 += $reg['Vis_Cat3'];
		$totalVisTotCat3 += $reg['VisTot_Cat3'];
		$totalRevisCat3 += $reg['VisTot_Cat3'] - $reg['Vis_Cat3'];
		$totalVisFallidaCat3 += $reg['Vis_Fallida_Cat3'];
		$totalAccionCompCat3 += $reg['VisAcc_Complem_Cat3'];
		if ($totalMedsCat3 > 0 && $totalVisCat3 > 0 ){
			$totalAlcanceCat3 = ($totalVisCat3 / $totalMedsCat3) * 100;
		}else{ 
			$totalAlcanceCat3 = 0;
		}
		if ($totalMedsCat3 > 0 && $totalMeds > 0 ){
			$totalPartCat3 = ($totalMedsCat3 / $totalMeds) * 100;
		}else{ 
			$totalPartCat3 = 0;
		}
		$totalMedsCat4 += $reg['DR_NR_Cat4'];
		$totalVisCat4 += $reg['Vis_Cat4'];
		$totalVisTotCat4 += $reg['VisTot_Cat4'];
		$totalRevisCat4 += $reg['VisTot_Cat4'] - $reg['Vis_Cat4'];
		$totalVisFallidaCat4 += $reg['Vis_Fallida_Cat4'];
		$totalAccionCompCat4 += $reg['VisAcc_Complem_Cat4'];
		if ($totalMedsCat4 > 0 && $totalVisCat4 > 0 ){
			$totalAlcanceCat4 = ($totalVisCat4 / $totalMedsCat4) * 100;
		}else{ 
			$totalAlcanceCat4 = 0;
		}
		if ($totalMedsCat4 > 0 && $totalMeds > 0 ){
			$totalPartCat4 = ($totalMedsCat4 / $totalMeds) * 100;
		}else{ 
			$totalPartCat4 = 0;
		}
		$totalMedsCat5 += $reg['DR_NR_Cat5'];
		$totalVisCat5 += $reg['Vis_Cat5'];
		$totalVisTotCat5 += $reg['VisTot_Cat5'];
		$totalRevisCat5 += $reg['VisTot_Cat5'] - $reg['Vis_Cat5'];
		$totalVisFallidaCat5 += $reg['Vis_Fallida_Cat5'];
		$totalAccionCompCat5 += $reg['VisAcc_Complem_Cat5'];
		if ($totalMedsCat5 > 0 && $totalVisCat5 > 0 ){
			$totalAlcanceCat5 = ($totalVisCat5 / $totalMedsCat5) * 100;
		}else{ 
			$totalAlcanceCat5 = 0;
		}
		if ($totalMedsCat5 > 0 && $totalMeds > 0 ){
			$totalPartCat5 = ($totalMedsCat5 / $totalMeds) * 100;
		}else{ 
			$totalPartCat5 = 0;
		}
		
		if($i == 1){
			////imprimir encabezados
			if($tipo != 3){
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="100px">Linea</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="100px">Ruta</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="400px">Nombre</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="8" width="800px" align="center">'.$reg['Cat1'].'</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="8" width="800px" align="center">'.$reg['Cat2'].'</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="8" width="800px" align="center">'.$reg['Cat3'].'</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="8" width="800px" align="center">'.$reg['Cat4'].'</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="8" width="800px" align="center">'.$reg['Cat5'].'</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="7" width="700px" align="center">Total</td></tr>';
				$tabla .= '<tr><td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Participacion</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Participacion</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Participacion</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Participacion</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Participacion</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
			}else{
				$pdf->Ln();
				$pdf->Cell(50,10,'','LRT',0,'L',1);
				$pdf->Cell(50,10,'','LRT',0,'L',1);
				$pdf->Cell(200,10,'','LRT',0,'L',1);
				$pdf->Cell(400,10,$reg['Cat1'],1,0,'C',1);
				$pdf->Cell(400,10,$reg['Cat2'],1,0,'C',1);
				$pdf->Cell(400,10,$reg['Cat3'],1,0,'C',1);
				$pdf->Cell(400,10,$reg['Cat4'],1,0,'C',1);
				$pdf->Cell(400,10,$reg['Cat5'],1,0,'C',1);
				$pdf->Cell(350,10,'Total',1,0,'C',1);
				$pdf->Ln();	
				$pdf->Cell(50,10,'Linea','LRB',0,'L',1);
				$pdf->Cell(50,10,'Ruta','LRB',0,'L',1);
				$pdf->Cell(200,10,'Nombre','LRB',0,'L',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'% Participacion',1,0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'% Participacion',1,0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'% Participacion',1,0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'% Participacion',1,0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'% Participacion',1,0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
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
			$gteMeds = $reg['DR_NR'];
			$gteVis = $reg['One_Vis'];
			$gteVisTot = $reg['VisTot'];
			$gteRevis = $reg['VisTot'] - $reg['One_Vis'];
			$gteVisFallida = $reg['Vis_Fallida'];
			$gteAccionComp = $reg['VisAcc_Complem'];
			if ($reg['One_Vis'] > 0 && $reg['DR_NR'] > 0 ){
				$gteAlcance = ($reg['One_Vis'] / $reg['DR_NR']) * 100;
			}else{ 
				$gteAlcance = 0;
			}
			$gteMedsCat1 = $reg['DR_NR_Cat1'];
			$gteVisCat1 = $reg['Vis_Cat1'];
			$gteVisTotCat1 = $reg['VisTot_Cat1'];
			$gteRevisCat1 = $reg['VisTot_Cat1'] - $reg['Vis_Cat1'];
			$gteVisFallidaCat1 = $reg['Vis_Fallida_Cat1'];
			$gteAccionCompCat1 = $reg['VisAcc_Complem_Cat1'];
			if ($reg['Vis_Cat1'] > 0 && $reg['DR_NR_Cat1'] > 0 ){
				$gteAlcanceCat1 = ($reg['Vis_Cat1'] / $reg['DR_NR_Cat1']) * 100;
			}else{ 
				$gteAlcanceCat1 = 0;
			}
			$gtePartCat1 = 0;
			$gteMedsCat2 = $reg['DR_NR_Cat2'];
			$gteVisCat2 = $reg['Vis_Cat2'];
			$gteVisTotCat2 = $reg['VisTot_Cat2'];
			$gteRevisCat2 = $reg['VisTot_Cat2'] - $reg['Vis_Cat2'];
			$gteVisFallidaCat2 = $reg['Vis_Fallida_Cat2'];
			$gteAccionCompCat2 = $reg['VisAcc_Complem_Cat2'];
			if ($reg['Vis_Cat2'] > 0 && $reg['DR_NR_Cat2'] > 0 ){
				$gteAlcanceCat2 = ($reg['Vis_Cat2'] / $reg['DR_NR_Cat2']) * 100;
			}else{ 
				$gteAlcanceCat2 = 0;
			}
			$gtePartCat2 = 0;
			$gteMedsCat3 = $reg['DR_NR_Cat3'];
			$gteVisCat3 = $reg['Vis_Cat3'];
			$gteVisTotCat3 = $reg['VisTot_Cat3'];
			$gteRevisCat3 = $reg['VisTot_Cat3'] - $reg['Vis_Cat3'];
			$gteVisFallidaCat3 = $reg['Vis_Fallida_Cat3'];
			$gteAccionCompCat3 = $reg['VisAcc_Complem_Cat3'];
			if ($reg['Vis_Cat3'] > 0 && $reg['DR_NR_Cat3'] > 0 ){
				$gteAlcanceCat3 = ($reg['Vis_Cat3'] / $reg['DR_NR_Cat3']) * 100;
			}else{ 
				$gteAlcanceCat3 = 0;
			}
			$gtePartCat3 = 0;
			$gteMedsCat4 = $reg['DR_NR_Cat4'];
			$gteVisCat4 = $reg['Vis_Cat4'];
			$gteVisTotCat4 = $reg['VisTot_Cat4'];
			$gteRevisCat4 = $reg['VisTot_Cat4'] - $reg['Vis_Cat4'];
			$gteVisFallidaCat4 = $reg['Vis_Fallida_Cat4'];
			$gteAccionCompCat4 = $reg['VisAcc_Complem_Cat4'];
			if ($reg['Vis_Cat4'] > 0 && $reg['DR_NR_Cat4'] > 0 ){
				$gteAlcanceCat4 = ($reg['Vis_Cat4'] / $reg['DR_NR_Cat4']) * 100;
			}else{ 
				$gteAlcanceCat4 = 0;
			}
			$gtePartCat4 = 0;
			$gteMedsCat5 = $reg['DR_NR_Cat5'];
			$gteVisCat5 = $reg['Vis_Cat5'];
			$gteVisTotCat5 = $reg['VisTot_Cat5'];
			$gteRevisCat5 = $reg['VisTot_Cat5'] - $reg['Vis_Cat5'];
			$gteVisFallidaCat5 = $reg['Vis_Fallida_Cat5'];
			$gteAccionCompCat5 = $reg['VisAcc_Complem_Cat5'];
			if ($reg['Vis_Cat5'] > 0 && $reg['DR_NR_Cat5'] > 0 ){
				$gteAlcanceCat5 = ($reg['Vis_Cat5'] / $reg['DR_NR_Cat5']) * 100;
			}else{ 
				$gteAlcanceCat5 = 0;
			}
			$gtePartCat5 = 0;
			
		}else{
			////sumas gerentes
			$gerente = $reg['REG_SNR'];
			if($tempGerente == $gerente){
				$sumMedsEsp = $reg['DR_NR'];
				$gteMeds += $sumMedsEsp;
				$sumVisEsp = $reg['One_Vis'];
				$gteVis += $sumVisEsp;
				$sumVisTotEsp = $reg['VisTot'];
				$gteVisTot += $sumVisTotEsp;
				$sumRevis = $reg['VisTot'] - $reg['One_Vis'];
				$gteRevis += $sumRevis;
				$sumVisFallidaEsp = $reg['Vis_Fallida'];
				$gteVisFallida += $sumVisFallidaEsp;
				$sumAccionCompEsp = $reg['VisAcc_Complem'];
				$gteAccionComp += $sumAccionCompEsp;
				if ($gteVis > 0 && $gteMeds > 0 ){
					$gteAlcance = ($gteVis / $gteMeds) * 100;
				}else{ 
					$gteAlcance = 0;
				}
				$sumMedsCat1 = $reg['DR_NR_Cat1'];
				$gteMedsCat1 += $sumMedsCat1;
				$sumVisCat1 = $reg['Vis_Cat1'];
				$gteVisCat1 += $sumVisCat1;
				$sumVisTotCat1 = $reg['VisTot_Cat1'];
				$gteVisTotCat1 += $sumVisTotCat1;
				$sumRevisCat1 = $reg['VisTot_Cat1'] - $reg['Vis_Cat1'];
				$gteRevisCat1 += $sumRevisCat1;
				$sumVisFallidaCat1 = $reg['Vis_Fallida_Cat1'];
				$gteVisFallidaCat1 += $sumVisFallidaCat1;
				$sumAccionCompCat1 = $reg['VisAcc_Complem_Cat1'];
				$gteAccionCompCat1 += $sumAccionCompCat1;
				if ($gteVisCat1 > 0 && $gteMedsCat1 > 0 ){
					$gteAlcanceCat1 = ($gteVisCat1 / $gteMedsCat1) * 100;
				}else{ 
					$gteAlcanceCat1 = 0;
				}
				if ($gteMedsCat1 > 0 && $gteMeds > 0){
					$gtePartCat1 = ($gteMedsCat1 / $gteMeds) * 100;
				}else{
					$gtePartCat1 = 0;
				}
				$sumMedsCat2 = $reg['DR_NR_Cat2'];
				$gteMedsCat2 += $sumMedsCat2;
				$sumVisCat2 = $reg['Vis_Cat2'];
				$gteVisCat2 += $sumVisCat2;
				$sumVisTotCat2 = $reg['VisTot_Cat2'];
				$gteVisTotCat2 += $sumVisTotCat2;
				$sumRevisCat2 = $reg['VisTot_Cat2'] - $reg['Vis_Cat2'];
				$gteRevisCat2 += $sumRevisCat2;
				$sumVisFallidaCat2 = $reg['Vis_Fallida_Cat2'];
				$gteVisFallidaCat2 += $sumVisFallidaCat2;
				$sumAccionCompCat2 = $reg['VisAcc_Complem_Cat2'];
				$gteAccionCompCat2 += $sumAccionCompCat2;
				if ($gteVisCat2 > 0 && $gteMedsCat2 > 0 ){
					$gteAlcanceCat2 = ($gteVisCat2 / $gteMedsCat2) * 100;
				}else{ 
					$gteAlcanceCat2 = 0;
				}
				if ($gteMedsCat2 > 0 && $gteMeds > 0){
					$gtePartCat2 = ($gteMedsCat2 / $gteMeds) * 100;
				}else{
					$gtePartCat2 = 0;
				}
				$sumMedsCat3 = $reg['DR_NR_Cat3'];
				$gteMedsCat3 += $sumMedsCat3;
				$sumVisCat3 = $reg['Vis_Cat3'];
				$gteVisCat3 += $sumVisCat3;
				$sumVisTotCat3 = $reg['VisTot_Cat3'];
				$gteVisTotCat3 += $sumVisTotCat3;
				$sumRevisCat3 = $reg['VisTot_Cat3'] - $reg['Vis_Cat3'];
				$gteRevisCat3 += $sumRevisCat3;
				$sumVisFallidaCat3 = $reg['Vis_Fallida_Cat3'];
				$gteVisFallidaCat3 += $sumVisFallidaCat3;
				$sumAccionCompCat3 = $reg['VisAcc_Complem_Cat3'];
				$gteAccionCompCat3 += $sumAccionCompCat3;
				if ($gteVisCat3 > 0 && $gteMedsCat3 > 0 ){
					$gteAlcanceCat3 = ($gteVisCat3 / $gteMedsCat3) * 100;
				}else{ 
					$gteAlcanceCat3 = 0;
				}
				if ($gteMedsCat3 > 0 && $gteMeds > 0){
					$gtePartCat3 = ($gteMedsCat3 / $gteMeds) * 100;
				}else{
					$gtePartCat3 = 0;
				}
				$sumMedsCat4 = $reg['DR_NR_Cat4'];
				$gteMedsCat4 += $sumMedsCat4;
				$sumVisCat4 = $reg['Vis_Cat4'];
				$gteVisCat4 += $sumVisCat4;
				$sumVisTotCat4 = $reg['VisTot_Cat4'];
				$gteVisTotCat4 += $sumVisTotCat4;
				$sumRevisCat4 = $reg['VisTot_Cat4'] - $reg['Vis_Cat4'];
				$gteRevisCat4 += $sumRevisCat4;
				$sumVisFallidaCat4 = $reg['Vis_Fallida_Cat4'];
				$gteVisFallidaCat4 += $sumVisFallidaCat4;
				$sumAccionCompCat4 = $reg['VisAcc_Complem_Cat4'];
				$gteAccionCompCat4 += $sumAccionCompCat4;
				if ($gteVisCat4 > 0 && $gteMedsCat4 > 0 ){
					$gteAlcanceCat4 = ($gteVisCat4 / $gteMedsCat4) * 100;
				}else{ 
					$gteAlcanceCat4 = 0;
				}
				if ($gteMedsCat4 > 0 && $gteMeds > 0){
					$gtePartCat4 = ($gteMedsCat4 / $gteMeds) * 100;
				}else{
					$gtePartCat4 = 0;
				}
				$sumMedsCat5 = $reg['DR_NR_Cat5'];
				$gteMedsCat5 += $sumMedsCat5;
				$sumVisCat5 = $reg['Vis_Cat5'];
				$gteVisCat5 += $sumVisCat5;
				$sumVisTotCat5 = $reg['VisTot_Cat5'];
				$gteVisTotCat5 += $sumVisTotCat5;
				$sumRevisCat5 = $reg['VisTot_Cat5'] - $reg['Vis_Cat5'];
				$gteRevisCat5 += $sumRevisCat5;
				$sumVisFallidaCat5 = $reg['Vis_Fallida_Cat5'];
				$gteVisFallidaCat5 += $sumVisFallidaCat5;
				$sumAccionCompCat5 = $reg['VisAcc_Complem_Cat5'];
				$gteAccionCompCat5 += $sumAccionCompCat5;
				if ($gteVisCat5 > 0 && $gteMedsCat5 > 0 ){
					$gteAlcanceCat5 = ($gteVisCat5 / $gteMedsCat5) * 100;
				}else{ 
					$gteAlcanceCat5 = 0;
				}
				if ($gteMedsCat5 > 0 && $gteMeds > 0){
					$gtePartCat5 = ($gteMedsCat5 / $gteMeds) * 100;
				}else{
					$gtePartCat5 = 0;
				}
				
			}else{	
				////imprimir gerentes
				if($tipo != 3){				
					$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
					$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';	
					$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsCat1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisCat1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisCat1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotCat1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaCat1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompCat1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceCat1,2).'%</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartCat1, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsCat2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisCat2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisCat2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotCat2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaCat2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompCat2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceCat2,2).'%</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartCat2, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsCat3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisCat3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisCat3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotCat3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaCat3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompCat3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceCat3,2).'%</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartCat3, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsCat4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisCat4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisCat4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotCat4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaCat4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompCat4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceCat4,2).'%</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartCat4, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsCat5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisCat5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisCat5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotCat5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaCat5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompCat5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceCat5,2).'%</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartCat5, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMeds).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVis).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevis.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisTot).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallida.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionComp.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcance,2).'%</td>';
					$tabla .= '</tr>';
				}else{ 
					$pdf->Cell(50,10,'',1,0,'L',1);
					$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
					$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
					$pdf->Cell(50,10,$gteMedsCat1,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisCat1,1,0,'C',1);
					$pdf->Cell(50,10,$gteRevisCat1,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisTotCat1,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallidaCat1,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionCompCat1,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcanceCat1, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePartCat1, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,$gteMedsCat2,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisCat2,1,0,'C',1);
					$pdf->Cell(50,10,$gteRevisCat2,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisTotCat2,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallidaCat2,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionCompCat2,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcanceCat2, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePartCat2, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,$gteMedsCat3,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisCat3,1,0,'C',1);
					$pdf->Cell(50,10,$gteRevisCat3,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisTotCat3,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallidaCat3,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionCompCat3,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcanceCat3, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePartCat3, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,$gteMedsCat4,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisCat4,1,0,'C',1);
					$pdf->Cell(50,10,$gteRevisCat4,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisTotCat4,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallidaCat4,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionCompCat4,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcanceCat4, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePartCat4, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,$gteMedsCat5,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisCat5,1,0,'C',1);
					$pdf->Cell(50,10,$gteRevisCat5,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisTotCat5,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallidaCat5,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionCompCat5,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcanceCat5, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePartCat5, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gteMeds),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVis),1,0,'C',1);
					$pdf->Cell(50,10,$gteRevis,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVisTot),1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallida,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionComp,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcance, 2).' %',1,1,'R',1);
				}
	
				////inicia var gerente
				$tempGerente = $reg['REG_SNR'];
				$nombreGte = $reg['RM'];
				$rutaGte = $reg['Ruta_Gte'];
				$gteMeds = $reg['DR_NR'];
				$gteVis = $reg['One_Vis'];
				$gteVisTot = $reg['VisTot'];
				$gteRevis = $reg['VisTot'] - $reg['One_Vis'];
				$gteVisFallida = $reg['Vis_Fallida'];
				$gteAccionComp = $reg['VisAcc_Complem'];
				if ($reg['DR_NR'] > 0 && $reg['One_Vis'] > 0) {
					$gteAlcance = ($reg['One_Vis'] / $reg['DR_NR']) * 100;
				}else{ 
					$gteAlcance = 0;
				}
				$gteMedsCat1 = $reg['DR_NR_Cat1'];
				$gteVisCat1 = $reg['Vis_Cat1'];
				$gteVisTotCat1 = $reg['VisTot_Cat1'];
				$gteRevisCat1 = $reg['VisTot_Cat1'] - $reg['Vis_Cat1'];
				$gteVisFallidaCat1 = $reg['Vis_Fallida_Cat1'];
				$gteAccionCompCat1 = $reg['VisAcc_Complem_Cat1'];
				if ($reg['DR_NR_Cat1'] > 0 && $reg['Vis_Cat1'] > 0) {
					$gteAlcanceCat1 = ($reg['Vis_Cat1'] / $reg['DR_NR_Cat1']) * 100;
				}else{ 
					$gteAlcanceCat1 = 0;
				}
				$gtePartCat1 = 0;
				$gteMedsCat2 = $reg['DR_NR_Cat2'];
				$gteVisCat2 = $reg['Vis_Cat2'];
				$gteVisTotCat2 = $reg['VisTot_Cat2'];
				$gteRevisCat2 = $reg['VisTot_Cat2'] - $reg['Vis_Cat2'];
				$gteVisFallidaCat2 = $reg['Vis_Fallida_Cat2'];
				$gteAccionCompCat2 = $reg['VisAcc_Complem_Cat2'];
				if ($reg['DR_NR_Cat2'] > 0 && $reg['Vis_Cat2'] > 0) {
					$gteAlcanceCat2 = ($reg['Vis_Cat2'] / $reg['DR_NR_Cat2']) * 100;
				}else{ 
					$gteAlcanceCat2 = 0;
				}
				$gtePartCat2 = 0;
				$gteMedsCat3 = $reg['DR_NR_Cat3'];
				$gteVisCat3 = $reg['Vis_Cat3'];
				$gteVisTotCat3 = $reg['VisTot_Cat3'];
				$gteRevisCat3 = $reg['VisTot_Cat3'] - $reg['Vis_Cat3'];
				$gteVisFallidaCat3 = $reg['Vis_Fallida_Cat3'];
				$gteAccionCompCat3 = $reg['VisAcc_Complem_Cat3'];
				if ($reg['DR_NR_Cat3'] > 0 && $reg['Vis_Cat3'] > 0) {
					$gteAlcanceCat3 = ($reg['Vis_Cat3'] / $reg['DR_NR_Cat3']) * 100;
				}else{ 
					$gteAlcanceCat3 = 0;
				}
				$gtePartCat3 = 0;
				$gteMedsCat4 = $reg['DR_NR_Cat4'];
				$gteVisCat4 = $reg['Vis_Cat4'];
				$gteVisTotCat4 = $reg['VisTot_Cat4'];
				$gteRevisCat4 = $reg['VisTot_Cat4'] - $reg['Vis_Cat4'];
				$gteVisFallidaCat4 = $reg['Vis_Fallida_Cat4'];
				$gteAccionCompCat4 = $reg['VisAcc_Complem_Cat4'];
				if ($reg['DR_NR_Cat4'] > 0 && $reg['Vis_Cat4'] > 0) {
					$gteAlcanceCat4 = ($reg['Vis_Cat4'] / $reg['DR_NR_Cat4']) * 100;
				}else{ 
					$gteAlcanceCat4 = 0;
				}
				$gtePartCat4 = 0;
				$gteMedsCat5 = $reg['DR_NR_Cat5'];
				$gteVisCat5 = $reg['Vis_Cat5'];
				$gteVisTotCat5 = $reg['VisTot_Cat5'];
				$gteRevisCat5 = $reg['VisTot_Cat5'] - $reg['Vis_Cat5'];
				$gteVisFallidaCat5 = $reg['Vis_Fallida_Cat5'];
				$gteAccionCompCat5 = $reg['VisAcc_Complem_Cat5'];
				if ($reg['DR_NR_Cat5'] > 0 && $reg['Vis_Cat5'] > 0) {
					$gteAlcanceCat5 = ($reg['Vis_Cat5'] / $reg['DR_NR_Cat5']) * 100;
				}else{ 
					$gteAlcanceCat5 = 0;
				}
				$gtePartCat5 = 0;
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';
		$Revis = $reg['VisTot'] - $reg['One_Vis'];
		$RevisCat1 = $reg['VisTot_Cat1'] - $reg['Vis_Cat1'];
		$RevisCat2 = $reg['VisTot_Cat2'] - $reg['Vis_Cat2'];
		$RevisCat3 = $reg['VisTot_Cat3'] - $reg['Vis_Cat3'];
		$RevisCat4 = $reg['VisTot_Cat4'] - $reg['Vis_Cat4'];
		$RevisCat5 = $reg['VisTot_Cat5'] - $reg['Vis_Cat5'];
		if ($reg['DR_NR'] > 0 && $reg['One_Vis'] > 0) {
			$Alcance = ($reg['One_Vis'] / $reg['DR_NR']) * 100;
		}else{ 
			$Alcance = 0;
		}
		if ($reg['DR_NR_Cat1'] > 0 && $reg['Vis_Cat1'] > 0) {
			$AlcanceCat1 = ($reg['Vis_Cat1'] / $reg['DR_NR_Cat1']) * 100;
		}else{ 
			$AlcanceCat1 = 0;
		}
		if ($reg['DR_NR_Cat1'] > 0 && $reg['DR_NR'] > 0){
			$PartCat1 = ($reg['DR_NR_Cat1'] / $reg['DR_NR']) * 100;
		}else{
			$PartCat1 = 0;
		}
		if ($reg['DR_NR_Cat2'] > 0 && $reg['Vis_Cat2'] > 0) {
			$AlcanceCat2 = ($reg['Vis_Cat2'] / $reg['DR_NR_Cat2']) * 100;
		}else{ 
			$AlcanceCat2 = 0;
		}
		if ($reg['DR_NR_Cat2'] > 0 && $reg['DR_NR'] > 0){
			$PartCat2 = ($reg['DR_NR_Cat2'] / $reg['DR_NR']) * 100;
		}else{
			$PartCat2 = 0;
		}
		if ($reg['DR_NR_Cat3'] > 0 && $reg['Vis_Cat3'] > 0) {
			$AlcanceCat3 = ($reg['Vis_Cat3'] / $reg['DR_NR_Cat3']) * 100;
		}else{ 
			$AlcanceCat3 = 0;
		}
		if ($reg['DR_NR_Cat3'] > 0 && $reg['DR_NR'] > 0){
			$PartCat3 = ($reg['DR_NR_Cat3'] / $reg['DR_NR']) * 100;
		}else{
			$PartCat3 = 0;
		}
		if ($reg['DR_NR_Cat4'] > 0 && $reg['Vis_Cat4'] > 0) {
			$AlcanceCat4 = ($reg['Vis_Cat4'] / $reg['DR_NR_Cat4']) * 100;
		}else{ 
			$AlcanceCat4 = 0;
		}
		if ($reg['DR_NR_Cat4'] > 0 && $reg['DR_NR'] > 0){
			$PartCat4 = ($reg['DR_NR_Cat4'] / $reg['DR_NR']) * 100;
		}else{
			$PartCat4 = 0;
		}
		if ($reg['DR_NR_Cat5'] > 0 && $reg['Vis_Cat5'] > 0) {
			$AlcanceCat5 = ($reg['Vis_Cat5'] / $reg['DR_NR_Cat5']) * 100;
		}else{ 
			$AlcanceCat5 = 0;
		}
		if ($reg['DR_NR_Cat5'] > 0 && $reg['DR_NR'] > 0){
			$PartCat5 = ($reg['DR_NR_Cat5'] / $reg['DR_NR']) * 100;
		}else{
			$PartCat5 = 0;
		}
		
		////imprimir repres
		if($tipo != 3){
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Linea'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Ruta'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="400px">'.$reg['SR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Cat1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Cat1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$RevisCat1.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot_Cat1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida_Cat1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem_Cat1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($AlcanceCat1, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PartCat1, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Cat2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Cat2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$RevisCat2.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot_Cat2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida_Cat2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem_Cat2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($AlcanceCat2, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PartCat2, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Cat3'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Cat3'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$RevisCat3.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot_Cat3'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida_Cat3'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem_Cat3'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($AlcanceCat3, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PartCat3, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Cat4'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Cat4'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$RevisCat4.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot_Cat4'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida_Cat4'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem_Cat4'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($AlcanceCat4, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PartCat4, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Cat5'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Cat5'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$RevisCat5.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot_Cat5'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida_Cat5'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem_Cat5'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($AlcanceCat5, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PartCat5, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['One_Vis'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$Revis.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($Alcance, 2).' %</td>';
			$tabla .= '</tr>';
		}else{
			$pdf->Cell(50,10,$reg['Linea'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['Ruta'],1,0,'L',0);
			$pdf->Cell(200,10,$reg['SR'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['DR_NR_Cat1'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Cat1'],1,0,'C',0);
			$pdf->Cell(50,10,$RevisCat1,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot_Cat1'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida_Cat1'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem_Cat1'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($AlcanceCat1, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PartCat1, 2),1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR_Cat2'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Cat2'],1,0,'C',0);
			$pdf->Cell(50,10,$RevisCat2,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot_Cat2'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida_Cat2'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem_Cat2'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($AlcanceCat2, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PartCat2, 2),1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR_Cat3'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Cat3'],1,0,'C',0);
			$pdf->Cell(50,10,$RevisCat3,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot_Cat3'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida_Cat3'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem_Cat3'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($AlcanceCat3, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PartCat3, 2),1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR_Cat4'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Cat4'],1,0,'C',0);
			$pdf->Cell(50,10,$RevisCat4,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot_Cat4'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida_Cat4'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem_Cat4'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($AlcanceCat4, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PartCat4, 2),1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR_Cat5'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Cat5'],1,0,'C',0);
			$pdf->Cell(50,10,$RevisCat5,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot_Cat5'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida_Cat5'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem_Cat5'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($AlcanceCat5, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PartCat5, 2),1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['One_Vis'],1,0,'C',0);
			$pdf->Cell(50,10,$Revis,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($Alcance, 2).' %',1,1,'R',0);
		}
		$i++;
	}
	
	////imprimir ultimo gerente
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px"></td>';
		$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsCat1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisCat1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisCat1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotCat1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaCat1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompCat1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceCat1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartCat1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsCat2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisCat2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisCat2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotCat2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaCat2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompCat2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceCat2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartCat2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsCat3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisCat3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisCat3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotCat3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaCat3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompCat3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceCat3, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartCat3, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsCat4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisCat4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisCat4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotCat4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaCat4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompCat4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceCat4, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartCat4, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsCat5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisCat5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisCat5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotCat5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaCat5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompCat5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceCat5, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartCat5, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMeds).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevis.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisTot).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallida.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionComp.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcance, 2).' %</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
		$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
		$pdf->Cell(50,10,$gteMedsCat1,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisCat1,1,0,'C',1);
		$pdf->Cell(50,10,$gteRevisCat1,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisTotCat1,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallidaCat1,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionCompCat1,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcanceCat1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePartCat1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$gteMedsCat2,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisCat2,1,0,'C',1);
		$pdf->Cell(50,10,$gteRevisCat2,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisTotCat2,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallidaCat2,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionCompCat2,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcanceCat2, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePartCat2, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$gteMedsCat3,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisCat3,1,0,'C',1);
		$pdf->Cell(50,10,$gteRevisCat3,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisTotCat3,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallidaCat3,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionCompCat3,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcanceCat3, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePartCat3, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$gteMedsCat4,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisCat4,1,0,'C',1);
		$pdf->Cell(50,10,$gteRevisCat4,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisTotCat4,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallidaCat4,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionCompCat4,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcanceCat4, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePartCat4, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$gteMedsCat5,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisCat5,1,0,'C',1);
		$pdf->Cell(50,10,$gteRevisCat5,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisTotCat5,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallidaCat5,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionCompCat5,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcanceCat5, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePartCat5, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gteMeds),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVis),1,0,'C',1);
		$pdf->Cell(50,10,$gteRevis,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVisTot),1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallida,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionComp,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcance, 2).' %',1,1,'R',1);
	}	

	////imprimir nacional
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="400px">Total General</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMedsCat1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisCat1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevisCat1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisTotCat1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallidaCat1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionCompCat1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcanceCat1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPartCat1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMedsCat2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisCat2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevisCat2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisTotCat2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallidaCat2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionCompCat2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcanceCat2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPartCat2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMedsCat3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisCat3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevisCat3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisTotCat3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallidaCat3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionCompCat3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcanceCat3, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPartCat3, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMedsCat4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisCat4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevisCat4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisTotCat4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallidaCat4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionCompCat4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcanceCat4, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPartCat4, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMedsCat5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisCat5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevisCat5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisTotCat5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallidaCat5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionCompCat5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcanceCat5, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPartCat5, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMeds).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisTot).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallida).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionComp).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcance, 2).' %</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(200,10,'Total General',1,0,'L',1);
		$pdf->Cell(50,10,number_format($totalMedsCat1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisCat1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevisCat1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisTotCat1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallidaCat1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionCompCat1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcanceCat1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPartCat1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMedsCat2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisCat2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevisCat2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisTotCat2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallidaCat2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionCompCat2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcanceCat2, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPartCat2, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMedsCat3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisCat3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevisCat3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisTotCat3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallidaCat3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionCompCat3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcanceCat3, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPartCat3, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMedsCat4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisCat4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevisCat4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisTotCat4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallidaCat4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionCompCat4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcanceCat4, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPartCat4, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMedsCat5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisCat5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevisCat5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisTotCat5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallidaCat5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionCompCat5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcanceCat5, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPartCat5, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMeds),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVis),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevis),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisTot),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallida),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionComp),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcance, 2).' %',1,1,'R',1);
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