<?php
	/*** listado de medicos what analisis***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(350,100,300,100,100,100,100,250,1500,100, 100,100);//,200,150,100,100,100,100,100,100, 150,150,100,100,350,350,150,100,100,350, 350,150,100,100,350,350,150,100,100,350, 350,150,100,100,350,350);//,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	$estatus = $_POST['hdnEstatus'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$fechaI = $_POST['hdnFechaI'];
	$fechaF = $_POST['hdnFechaF'];
	
	//echo $ids."<br>";
	
	$qMedicos = "select distinct 
		/*DM.lname + ' ' + DM.fname as Gerente,*/
		MR.lname + ' ' + MR.fname as Representante,
		DR.date as Fecha,
		CODELIST.name as ACT,
	    (select sum(value) from day_report_code where day_report_code.dayreport_snr=dr.dayreport_snr and dc.day_code_snr=day_report_code.day_code_snr and rec_stat=0) as 'Total Horas',
		'' as 'Porc de Actividad',
		'' as 'Tiempo dia 8 hrs.',
		'' as 'Porc Dia 8 hrs.',
		ISNULL((select TOP 1 UC.LNAME+' '+UC.FNAME from kupdatelog ku, kommtran kt, USERS UC where
		ku.table_nr = 51
		and ku.kTrAN_SNR = KT.KTRAN_SNR
		and ku.OPERATION=1
		AND REC_KEY=DR.DAYREPORT_SNR
		AND kt.USER_ID=UC.USER_SNR
		order by T_DATE DESC),'ADMIN')
		AS 'Creado por',
		DR.info as Comentarios,
		/*DR.BROJ as Ruta_Repre,*/
		SUBSTRING(MR.lname,1,4) as Ruta_Repre,
		DR.creation_timestamp as Fecha_creacion,
		DC.value as 'Hrs. Actividad' /*11*/
		
		/*(select TOP 1 kt.T_DATE from kupdatelog ku, kommtran kt where
		ku.table_nr = 51
		and ku.kTrAN_SNR = KT.KTRAN_SNR
		and ku.OPERATION=1
		AND REC_KEY=DR.DAYREPORT_SNR
		order by T_DATE DESC) as Date_Creacion,
		substring(DC.Start_Time,1,2) as sh,
		substring(DC.Start_Time,4,2) as sm,
		DC.start_time,
		substring(DC.Finish_Time,1,2) as fh,
		substring(DC.Finish_Time,4,2) as fm,
		DC.Finish_Time
		UC.LNAME+' '+UC.FNAME*/
		 
		from users DM,
		KLOC_REG klr,
		users MR,
		codelist,
		codelistlib,
		day_report DR,
		DAY_REPORT_CODE as DC
		/*,KUPDATELOG KU, KOMMTRAN KT, USERS UC*/
		 
		where klr.REG_SNR = DM.USER_SNR
		and klr.kloc_snr = MR.user_snr
		and klr.REC_STAT=0
		and MR.REC_STAT=0
		and DM.REC_STAT=0
		and MR.Status in (1,2)
		and DM.Status in (1,2)
		and DM.User_type = 5
		and CODELIST.status=1
		and CODELIST.rec_stat=0
		and CODELISTLIB.clib_type=84
		and CODELIST.clib_snr=CODELISTLIB.clib_snr
		and DR.user_snr = MR.user_snr
		and DR.rec_stat=0
		and DC.rec_stat=0
		and DC.day_code_snr =codelist.clist_snr
		and DR.dayreport_snr=DC.dayreport_snr
		 
		/*AND KU.REC_KEY =DR.DAYREPORT_SNR AND KU.TABLE_NR = 51 AND KU.REC_STAT =0
		AND KT.KTRAN_SNR = KU.KTRAN_SNR
		AND UC.USER_SNR = KT.USER_ID*/
		 
		and dr.date between '".$fechaI."' and '".$fechaF."'
		/*and dr.date between '2019-01-01' and '2019-04-30'*/
		 
		and (mr.user_snr in (SELECT kloc_snr FROM kloc_reg WHERE rec_stat=0
		and kloc_snr in ('".$ids."')
		))
		 
		And exists (select * from Kommloc KL1 where KL1.rec_stat = 0 /*and kl1.Activated = 1*/ and kl1.kloc_snr = klr.kloc_snr) ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoOtrasActividades.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/2),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO OTRAS ACTIVIDADES'));
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
	$tamTabla = array_sum($tam) + 950;
	if( $tipo != 3){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO OTRAS ACTIVIDADES</td>
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
		if($i < 12){
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
			
			if($j < 12){
				if($j == 4){
					if($regMedico[3] > 0){
						$regMedico[$j] = $regMedico[3] / 8 * 100;
					}else{
						$regMedico[$j] = '';
					}
				}
				if($j == 5){
					if($regMedico[11] > 0 && is_numeric($regMedico[3]) && strpos($regMedico[3], ".")){
						$regMedico[$j] = (float)$regMedico[3] ;
					}else if($regMedico[11] > 0 && is_numeric($regMedico[3])){
						$regMedico[$j] = (float)$regMedico[3] ;
					}
				}
				if($j == 6){
					if($regMedico[5] > 0){
						$regMedico[$j] = $regMedico[5] / 8 * 100;
					}
				}
				if($tipo != 3){
					if($tipo == 2){
						$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
					}else{
						if($tipo == 1){
							$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
						}else{
							$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
						}
					}
				}else{
					$pdf->Cell($tam[$j]/2,8,$regMedico[$j],1,0,'L',$fill);
				}
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