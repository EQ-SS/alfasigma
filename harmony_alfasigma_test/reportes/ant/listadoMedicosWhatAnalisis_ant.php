<?php
	/*** listado de medicos what analisis***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,250,250,250,100,150,300,300,300,100, 250,200,200,150,100,100,100,100,100,100, 150,150,100,100,350,350,150,100,100,350, 350,150,100,100,350,350,150,100,100,350, 350,150,100,100,350,350);//,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	$estatus = $_POST['hdnEstatus'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	
	$qMedicos = "Select
			cl.name as Linea,
			upper(U.lname)+' '+upper(U.fname) as Representante,
			'{'+CAST(P.pers_snr AS VARCHAR(36))+'}' as 'Código Médico',
			'{'+CAST(I.inst_snr AS VARCHAR(36))+'}' as 'Código Inst',
			upper(P.lname) as Paterno,
			upper(P.mothers_lname) as Materno,
			upper(P.fname) as Nombre,
			upper(I.street1) as Calle,
			City.name as Colonia,
			City.zip as CP,
			IMS.name as Brick,
			Dst.name as Población,
			State.name as Estado,
			ESP.name as Especialidad,
			ST.name as Estatus,
			FV.name as 'Frec Vis',
			CATAW.NAME AS 'Categ AW',
			P.prof_id as Cedula,
			CAST(CAST(isnull(isnull((select TOP 1 kt.T_DATE from kupdatelog ku, kommtran kt where
			ku.table_nr = 19
			and ku.OPERATION=1
			and ku.kTrAN_SNR = KT.KTRAN_SNR
			AND KU.REC_STAT=0
			AND KU.REC_KEY=P.PERS_SNR
			order by KT.T_DATE DESC
			), VP.create_date), P.CREATION_TIMESTAMP) AS DATE) AS VARCHAR(10)) as 'Fecha Alta', 
			CAST(CAST(isnull((select top 1 kt.T_DATE from kupdatelog ku, kommtran kt where
			ku.table_nr = 19
			and ku.OPERATION=2
			and ku.kTrAN_SNR = KT.KTRAN_SNR
			AND KU.REC_STAT=0 
			AND KU.REC_KEY=P.PERS_SNR
			order by KT.T_DATE DESC
			),P.CHANGED_TIMESTAMP) AS DATE) AS VARCHAR(10)) as 'Fecha Mod', 			
			/*P.FOTO_NAME AS 'Nombre CU',*/
			pwhat1.NAME as Producto1,
			pud.Prod1W as W1,
			pud.Prod1H as H1,
			pud.Prod1A as A1,
			pud.Prod1T as T1,
			pwhat2.NAME as Producto2,
			pud.Prod2W as W2,
			pud.Prod2H as H2,
			pud.Prod2A as A2,
			pud.Prod2T as T2,
			pwhat3.NAME as Producto3,
			pud.Prod3W as W3,
			pud.Prod3H as H3,
			pud.Prod3A as A3,
			pud.Prod3T as T3,
			pwhat4.NAME as Producto4,
			pud.Prod4W as W4,
			pud.Prod4H as H4,
			pud.Prod4A as A4,
			pud.Prod4T as T4,
			pwhat5.NAME as Producto5,
			pud.Prod5W as W5,
			pud.Prod5H as H5,
			pud.Prod5A as A5,
			pud.Prod5T as T5
			
			/*upper(IT.NAME) AS Tipo_Inst,
			upper(type.name) as Tipo_Cons,
			upper(P.lname)+' '+upper(P.mothers_lname)+' '+upper(P.fname) as Medico,
			case when I.num_ext='0' then '' else I.num_ext end as Num_Ext,
			case when I.numint=0 then '' else I.numint end as Num_Int,
			SEXO.name as Sexo,
			CATEG.name as Categ,			
			ESP2.name as Sub_Esp,
			HON.name as Hon,
			PT.name as Pac,
			P.birth_year as Ano_Nac,
			cast(p.BIRTHDATE as DATE) as Fecha_Nac,
			PLW.tel as Tel1, --Celular,
			PLW.GSM as Celular, --Tel1,
			I.tel2 as Tel2,
			PLW.email,
			--PLW.fax as Cedula,
			P.info as Comentarios,
			PUESTO.NAME AS Puesto,
			CAST(CAST((CASE WHEN PSW.CHANGED_TIMESTAMP IS NOT NULL THEN 
			(CASE WHEN PSW.CREATION_TIMESTAMP IS NOT NULL THEN 
			(CASE WHEN PSW.CREATION_TIMESTAMP>PSW.CHANGED_TIMESTAMP THEN CAST(PSW.CREATION_TIMESTAMP AS DATE) ELSE CAST(PSW.CHANGED_TIMESTAMP AS DATE) END)
			ELSE CAST(PSW.CHANGED_TIMESTAMP AS DATE) END)
			ELSE '2017-01-01' END)
			AS DATE) AS VARCHAR(10)) as Fecha_Ruta,
			,I.latitude
			,I.longitude*/
			
			from person P
			inner join perslocwork PLW on P.pers_snr = PLW.pers_snr and PLW.rec_stat=0
			inner join inst I on I.inst_snr = PLW.INST_SNR and I.rec_stat=0
			left outer join inst_Type IT on IT.inst_type=I.inst_type and IT.rec_Stat=0
			inner join pers_srep_work PSW on PSW.pwork_snr=PLW.pwork_SNR and PSW.rec_Stat=0 
			INNER join City on City.city_snr = I.city_snr
			LEFT OUTer join District as Dst on city.distr_snr = Dst.distr_snr
			LEFT OUTer join State on Dst.state_snr = State.state_snr
			left outer join Brick as IMS on IMS.brick_snr = City.brick_snr
			left outer join Smart_Fechas_Med VP on VP.pers_snr = P.pers_snr
			inner join User_Territ as UT on psw.user_snr= ut.user_snr and i.inst_snr = ut.inst_snr and ut.rec_stat=0 /*UT.inst_snr = I.inst_snr*/
			inner join Users as U on U.user_snr = UT.user_snr and U.rec_stat=0
			inner join compline as cl on U.cline_snr = cl.cline_snr
			left outer join codelist type on I.type_snr = type.clist_snr
			left outer join codelist ST on P.status_snr = ST.clist_snr
			left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
			left outer join codelist CATEG on P.category_snr = CATEG.clist_snr
			LEFT OUTER JOIN CODELIST CATAW ON CATAW.CLIST_SNR=P.category_snr AND P.REC_STAT=0
			left outer join codelist PT on P.patperweek_snr = PT.clist_snr AND PT.REC_STAT=0 AND PT.STATUS=1
			left outer join codelist ESP on P.Spec_snr = ESP.clist_snr
			left outer join codelist ESP2 on P.subSpec_snr = ESP2.clist_snr AND ESP2.STATUS=1 AND ESP2.REC_STAT=0
			left outer join codelist HON on P.PERSTYPE_SNR = HON.clist_snr
			left outer join codelist FV on P.frecvis_snr=FV.clist_snr and FV.rec_stat=0 and FV.status=1
			LEFT OUTER JOIN PERSON_CONTACT PC ON PC.PERS_SNR=P.PERS_SNR AND PC.REC_STAT=0
			left outer join codelist PUESTO on PC.FUNCTION_snr = PUESTO.clist_snr AND PC.PERS_SNR=P.PERS_SNR AND PC.REC_STAT=0 AND PUESTO.STATUS=1
			 
			LEFT OUTER JOIN PERSON_UD PUD ON PUD.PERS_SNR=P.PERS_SNR AND PUD.REC_STAT=0
			left outer join CODELIST pwhat1 ON PUD.Prod1What_snr=pwhat1.CLIST_SNR and pwhat1.REC_STAT=0 AND pwhat1.STATUS=1
			left outer join CODELIST pwhat2 ON PUD.Prod2What_snr=pwhat2.CLIST_SNR and pwhat2.REC_STAT=0 AND pwhat2.STATUS=1
			left outer join codelist pwhat3 on PUD.Prod3What_snr=pwhat3.clist_snr and pwhat3.rec_stat=0 and pwhat3.status=1
			left outer join codelist pwhat4 on PUD.Prod4What_snr=pwhat4.clist_snr and pwhat4.rec_stat=0 and pwhat4.status=1
			left outer join codelist pwhat5 on PUD.Prod5What_snr=pwhat5.clist_snr and pwhat5.rec_stat=0 and pwhat5.status=1
			 
			 
			where
			P.pers_snr <> '00000000-0000-0000-0000-000000000000'
			and P.rec_stat=0
			and U.status=1
			and U.user_type=4
			and P.status_snr in ('".$estatus."')
			and U.user_snr in ('".$ids."') ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoMedicosWhatAnalisis.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/2),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE MÉDICOS WHAT ANALISIS'));
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
	$tamTabla = array_sum($tam) + 550;
	if( $tipo != 3){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO DE MÉDICOS WHAT ANALISIS</td>
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
		//if($i < 47){
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
		//}
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
			if($j == 2){//pers_snr
				$qAdeguradoras = "select c.NAME from PERSON_BANK p, CODELIST c
					where p.bank_snr = c.CLIST_SNR
					and p.REC_STAT = 0
					and p.PERS_SNR = '".$regMedico[$j]."'
					order by c.SORT_NUM";
				$rsAseguradoras = sqlsrv_query($conn, $qAdeguradoras);
				$arrAseguradoras = array();
				while($aseguradora = sqlsrv_fetch_array($rsAseguradoras)){
					$arrAseguradoras[] = $aseguradora['NAME'];
				}
			}
			/*if($j == 40){
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