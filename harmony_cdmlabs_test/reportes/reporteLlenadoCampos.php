<?php
	/*** cobertura de medicos ***/
	include "../conexion.php";
			
	//$estatus = $_POST['hdnEstatus'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	
	$qMedicos = "DECLARE @cad0 as varchar(36),
		@MedActivo as varchar(36)

		set @cad0='00000000-0000-0000-0000-000000000000'
		set @MedActivo='C3C180DC-76B8-48D8-9581-A4345BCE689A'


		select LINEA.name as LINEA,
		klr.REG_SNR,
		DM.lname + ' ' + DM.fname as RM, 
		MR.lname + ' ' + MR.fname as SR,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW
		where PSW.pwork_snr = PLW.pwork_snr 
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0 
		and P.rec_stat = 0
		and PSW.user_snr = MR.user_snr ) as Regist,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo 
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0 
		and P.rec_stat = 0
		and (P.category_snr <> @cad0 AND P.category_snr IS NOT NULL)
		and PSW.user_snr = MR.user_snr ) as Categ,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0 
		and P.rec_stat = 0
		and (P.spec_snr <> @cad0 AND P.spec_snr IS NOT NULL)
		and PSW.user_snr = MR.user_snr ) as Esp,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0 
		and P.rec_stat = 0
		and (P.fee_type_snr <> @cad0 AND P.fee_type_snr IS NOT NULL)
		and PSW.user_snr = MR.user_snr ) as Honorarios,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0 
		and P.rec_stat = 0
		and (P.patperweek_snr <> @cad0 AND P.patperweek_snr IS NOT NULL)
		and PSW.user_snr = MR.user_snr ) as Pacxsem,
		
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW, person_ud PUD
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0 
		and P.rec_stat = 0
		and PUD.pers_snr = P.pers_snr
		and PUD.rec_stat = 0
		and (PUD.field_02_snr <> @cad0 AND PUD.field_02_snr IS NOT NULL)
		and PSW.user_snr = MR.user_snr ) as Compra,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0 
		and P.rec_stat = 0
		and (P.frecvis_snr <> @cad0 AND P.frecvis_snr IS NOT NULL)
		and PSW.user_snr = MR.user_snr ) as Frec_Vis,

		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0 
		and P.rec_stat = 0
		and (P.birthdate > 0 AND P.birthdate IS NOT NULL)
		and PSW.user_snr = MR.user_snr ) as Ano_Nac,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and (len(P.tel1)>0 AND P.tel1 is not null)
		and PSW.user_snr = MR.user_snr ) as Tel1,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and (len(P.tel2)>0 AND P.tel2 is not null)
		and PSW.user_snr = MR.user_snr ) as Tel2,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and (len(P.mobile)>0 AND P.mobile is not null)
		and PSW.user_snr = MR.user_snr ) as Celular,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and (len(P.email1)>0 AND P.email1 is not null)
		and PSW.user_snr = MR.user_snr ) as Email,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and (len(P.prof_id)>0 AND P.prof_id is not null)
		and PSW.user_snr = MR.user_snr ) as Cedula,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW, Inst I
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and I.Inst_snr = PLW.inst_snr
		and I.rec_stat = 0
		and (LEN(I.street1) >0 AND I.street1 is not null)
		and PSW.user_snr = MR.user_snr ) as Direccion,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW, Inst I, City Cy
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and I.Inst_snr = PLW.inst_snr
		and I.rec_stat = 0
		and (I.city_snr <> @cad0 AND I.city_snr is not null)
		and I.city_snr = Cy.city_snr
		and Cy.rec_stat = 0
		and (LEN(Cy.zip) >0 OR LEN(Cy.name) >0 OR Cy.zip<>'00000')
		and PSW.user_snr = MR.user_snr ) as Cp_Colonia,

		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW, Inst I, City Cy
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and I.Inst_snr = PLW.inst_snr
		and I.rec_stat = 0
		and (I.city_snr <> @cad0 AND I.city_snr is not null)
		and I.city_snr = Cy.city_snr
		and Cy.rec_stat = 0
		and Cy.distr_snr <> @cad0
		and PSW.user_snr = MR.user_snr ) as Ciudad,
		 
		(Select count(distinct plw.pwork_snr) From perslocwork PLW, person P, pers_srep_work PSW, Inst I, City Cy
		where PSW.pwork_snr = PLW.pwork_snr
		and PLW.pwork_snr <> @cad0 
		and PLW.pers_snr = P.pers_snr
		and P.status_snr = @MedActivo
		and PLW.rec_stat = 0 
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and I.Inst_snr = PLW.inst_snr
		and I.rec_stat = 0
		and (I.city_snr <> @cad0 AND I.city_snr is not null)
		and I.city_snr = Cy.city_snr
		and Cy.rec_stat = 0
		and Cy.state_snr <> @cad0
		and PSW.user_snr = MR.user_snr ) as Estado

		
		From users DM, KLOC_REG klr, users MR, compline LINEA
		where klr.REG_SNR = DM.USER_SNR
		and klr.kloc_snr = MR.user_snr
		and klr.REC_STAT=0
		and MR.REC_STAT=0
		and DM.REC_STAT=0
		and MR.Status=1
		and DM.Status=1
		and DM.User_type = 5
		and MR.User_type in (4)
		and MR.cline_snr = LINEA.cline_snr
		and LINEA.rec_stat=0
		 
		and (mr.user_snr in (SELECT kloc_snr FROM kloc_reg WHERE rec_stat=0 and kloc_snr in ('".$ids."')
		))
		 
		And exists (select * from Kommloc KL1 where KL1.rec_stat = 0 and kl1.Activated = 1 and kl1.kloc_snr = klr.kloc_snr)

		Order by DM.lname,DM.FNAME,MR.LNAME,MR.FNAME,klr.REG_SNR ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=reporteLlenadoCampos.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(900,1000));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Reporte de Llenado de Campos'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Consorcio Dermatológico de México');
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

	$tamTabla = 1950;
	$tabla = '';
	if( $tipo != 3){
			$tabla .= '<table border="0">
				<tr>
					<td>
						<table>
							<tr>
								<td colspan="10" class="nombreReporte">Reporte de Llenado de Campos</td>
							</tr>
							<tr>
								<td colspan="10" class="clienteReporte">Consorcio Dermatológico de México</td>
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
			$tabla .= '<thead style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000"><tr>';
		}else{
			$tabla .= '<thead><tr>';
		}
	}else{
		$pdf->SetFillColor(169,188,245);
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
		$estilocabecera = 'style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000"';
		$estilorepre = 'style="border: 1px solid #000;white-space:nowrap;"';
		$estilogte = 'style="background-color: #A9BCF5;border: 1px solid #000;white-space:nowrap;"';
	}

	
	$i=1;
	//inicia var nacional
	$totalRegis = 0;
	$sumTotalCateg = 0;
	$sumTotalEsp = 0;
	$sumTotalHon = 0;
	$sumTotalPacxsem = 0;
	$sumTotalCompra = 0;
	$sumTotalFrecVis = 0;
	$sumTotalTel1 = 0;
	$sumTotalTel2 = 0;
	$sumTotalCelular = 0;
	$sumTotalEmail = 0;
	$sumTotalCedula = 0;
	$sumTotalDir = 0;
	$sumTotalCpCol = 0;
	$sumTotalCiudad = 0;
	$sumTotalEstado = 0;
	$totalCateg = 0;
	$totallEsp = 0;
	$totalHon = 0;
	$totalPacxsem = 0;
	$totalCompra = 0;
	$totalFrecVis = 0;
	$totalTel1 = 0;
	$totalTel2 = 0;
	$totalCelular = 0;
	$totalEmail = 0;
	$totalCedula = 0;
	$totalDir = 0;
	$totalCpCol = 0;
	$totalCiudad = 0;
	$totalEstado = 0;

	while($reg = sqlsrv_fetch_array($rsMedicos)){
		////suma nacional
		$totalRegis += $reg['Regist'];
		$sumTotalCateg += $reg['Categ'];
		$sumTotalEsp += $reg['Esp'];
		$sumTotalHon += $reg['Honorarios'];
		$sumTotalPacxsem += $reg['Pacxsem'];
		$sumTotalCompra += $reg['Compra'];
		$sumTotalFrecVis += $reg['Frec_Vis'];
		$sumTotalTel1 += $reg['Tel1'];
		$sumTotalTel2 += $reg['Tel2'];
		$sumTotalCelular += $reg['Celular'];
		$sumTotalEmail += $reg['Email'];
		$sumTotalCedula += $reg['Cedula'];
		$sumTotalDir += $reg['Direccion'];
		$sumTotalCpCol += $reg['Cp_Colonia'];
		$sumTotalCiudad += $reg['Ciudad'];
		$sumTotalEstado += $reg['Estado'];				
		if ($totalRegis > 0 && $sumTotalCateg > 0){
			$totalCateg = ($sumTotalCateg / $totalRegis) * 100;
		}else{
			$totalCateg = 0;
		}
		if ($totalRegis > 0 && $sumTotalEsp > 0){
			$totalEsp = ($sumTotalEsp / $totalRegis) * 100;
		}else{
			$totalEsp = 0;
		}
		if ($totalRegis > 0 && $sumTotalHon > 0){
			$totalHon = ($sumTotalHon / $totalRegis) * 100;
		}else{
			$totalHon = 0;
		}
		if ($totalRegis > 0 && $sumTotalPacxsem > 0){
			$totalPacxsem = ($sumTotalPacxsem / $totalRegis) * 100;
		}else{
			$totalPacxsem = 0;
		}
		if ($totalRegis > 0 && $sumTotalCompra > 0){
			$totalCompra = ($sumTotalCompra / $totalRegis) * 100;
		}else{
			$totalCompra = 0;
		}
		if ($totalRegis > 0 && $sumTotalFrecVis > 0){
			$totalFrecVis = ($sumTotalFrecVis / $totalRegis) * 100;
		}else{
			$totalFrecVis = 0;
		}
		if ($totalRegis > 0 && $sumTotalTel1 > 0){
			$totalTel1 = ($sumTotalTel1 / $totalRegis) * 100;
		}else{
			$totalTel1 = 0;
		}
		if ($totalRegis > 0 && $sumTotalTel2 > 0){
			$totalTel2 = ($sumTotalTel2 / $totalRegis) * 100;
		}else{
			$totalTel2 = 0;
		}
		if ($totalRegis > 0 && $sumTotalCelular > 0){
			$totalCelular = ($sumTotalCelular / $totalRegis) * 100;
		}else{
			$totalCelular = 0;
		}
		if ($totalRegis > 0 && $sumTotalEmail > 0){
			$totalEmail = ($sumTotalEmail / $totalRegis) * 100;
		}else{
			$totalEmail = 0;
		}
		if ($totalRegis > 0 && $sumTotalCedula > 0){
			$totalCedula = ($sumTotalCedula / $totalRegis) * 100;
		}else{
			$totalCedula = 0;
		}
		if ($totalRegis > 0 && $sumTotalDir > 0){
			$totalDir = ($sumTotalDir / $totalRegis) * 100;
		}else{
			$totalDir = 0;
		}
		if ($totalRegis > 0 && $sumTotalCpCol > 0){
			$totalCpCol = ($sumTotalCpCol / $totalRegis) * 100;
		}else{
			$totalCpCol = 0;
		}
		if ($totalRegis > 0 && $sumTotalCiudad > 0){
			$totalCiudad = ($sumTotalCiudad / $totalRegis) * 100 ;
		}else{
			$totalCiudad = 0;
		}
		if ($totalRegis > 0 && $sumTotalEstado > 0){
			$totalEstado = ($sumTotalEstado / $totalRegis) * 100 ;
		}else{
			$totalEstado = 0;
		}
		
		if($i == 1){
			////imprimir encabezados
			if($tipo != 3){
				$tabla .= '<td '.$estilocabecera.' width="400px">Ruta - Nombre</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Num Meds</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Categoria</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Especialidad</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Honorarios</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Pac x Sem</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Compra</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Frec Visita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Tel1</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Tel2</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Celular</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Email</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cedula</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Direccion</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cp - Colonia</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Ciudad</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Estado</td>';
			}else{
				$pdf->Ln();			
				$pdf->Cell(200,10,'Ruta - Nombre',1,0,'L',1);
				$pdf->Cell(50,10,'Num Meds',1,0,'C',1);
				$pdf->Cell(50,10,'% Categoria',1,0,'C',1);
				$pdf->Cell(50,10,'% Especialidad',1,0,'C',1);
				$pdf->Cell(50,10,'% Honorarios',1,0,'C',1);
				$pdf->Cell(50,10,'% Pac x Sem',1,0,'C',1);
				$pdf->Cell(50,10,'% Compra',1,0,'C',1);
				$pdf->Cell(50,10,'% Frec Visita',1,0,'C',1);
				$pdf->Cell(50,10,'% Tel1',1,0,'C',1);
				$pdf->Cell(50,10,'% Tel2',1,0,'C',1);
				$pdf->Cell(50,10,'% Celular',1,0,'C',1);
				$pdf->Cell(50,10,'% Email',1,0,'C',1);
				$pdf->Cell(50,10,'% Cedula',1,0,'C',1);
				$pdf->Cell(50,10,'% Direccion',1,0,'C',1);
				$pdf->Cell(50,10,'% Cp - Colonia',1,0,'C',1);
				$pdf->Cell(50,10,'% Ciudad',1,0,'C',1);
				$pdf->Cell(50,10,'% Estado',1,0,'C',1);
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
				$gteRegis = $reg['Regist'];
				$sumCateg = $reg['Categ'];
				$sumEsp = $reg['Esp'];
				$sumHon = $reg['Honorarios'];
				$sumPacxsem = $reg['Pacxsem'];
				$sumCompra = $reg['Compra'];
				$sumFrecVis = $reg['Frec_Vis'];
				$sumTel1 = $reg['Tel1'];
				$sumTel2 = $reg['Tel2'];
				$sumCelular = $reg['Celular'];
				$sumEmail = $reg['Email'];
				$sumCedula = $reg['Cedula'];
				$sumDir = $reg['Direccion'];
				$sumCpCol = $reg['Cp_Colonia'];
				$sumCiudad = $reg['Ciudad'];
				$sumEstado = $reg['Estado'];
				$gteCateg = 0;
				$gteEsp = 0;
				$gteHon = 0;
				$gtePacxsem = 0;
				$gteCompra = 0;
				$gteFrecVis = 0;
				$gteTel1 = 0;
				$gteTel2 = 0;
				$gteCelular = 0;
				$gteEmail = 0;
				$gteCedula = 0;
				$gteDir = 0;
				$gteCpCol = 0;
				$gteCiudad = 0;
				$gteEstado = 0;
			
		}else{
			////sumas gerentes
			$gerente = $reg['REG_SNR'];		
			if($tempGerente == $gerente){
				$gteRegis += $reg['Regist'];
				$sumCateg += $reg['Categ'];
				$sumEsp += $reg['Esp'];
				$sumHon += $reg['Honorarios'];
				$sumPacxsem += $reg['Pacxsem'];
				$sumCompra += $reg['Compra'];
				$sumFrecVis += $reg['Frec_Vis'];
				$sumTel1 += $reg['Tel1'];
				$sumTel2 += $reg['Tel2'];
				$sumCelular += $reg['Celular'];
				$sumEmail += $reg['Email'];
				$sumCedula += $reg['Cedula'];
				$sumDir += $reg['Direccion'];
				$sumCpCol += $reg['Cp_Colonia'];
				$sumCiudad += $reg['Ciudad'];
				$sumEstado += $reg['Estado'];				
				if ($gteRegis > 0 && $sumCateg > 0){
					$gteCateg = ($sumCateg / $gteRegis) * 100;
				}else{
					$gteCateg = 0;
				}
				if ($gteRegis > 0 && $sumEsp > 0){
					$gteEsp = ($sumEsp / $gteRegis) * 100;
				}else{
					$gteEsp = 0;
				}
				if ($gteRegis > 0 && $sumHon > 0){
					$gteHon = ($sumHon / $gteRegis) * 100;
				}else{
					$gteHon = 0;
				}
				if ($gteRegis > 0 && $sumPacxsem > 0){
					$gtePacxsem = ($sumPacxsem / $gteRegis) * 100;
				}else{
					$gtePacxsem = 0;
				}
				if ($gteRegis > 0 && $sumCompra > 0){
					$gteCompra = ($sumCompra / $gteRegis) * 100;
				}else{
					$gteCompra = 0;
				}
				if ($gteRegis > 0 && $sumFrecVis > 0){
					$gteFrecVis = ($sumFrecVis / $gteRegis) * 100;
				}else{
					$gteFrecVis = 0;
				}
				if ($gteRegis > 0 && $sumTel1 > 0){
					$gteTel1 = ($sumTel1 / $gteRegis) * 100;
				}else{
					$gteTel1 = 0;
				}
				if ($gteRegis > 0 && $sumTel2 > 0){
					$gteTel2 = ($sumTel2 / $gteRegis) * 100;
				}else{
					$gteTel2 = 0;
				}
				if ($gteRegis > 0 && $sumCelular > 0){
					$gteCelular = ($sumCelular / $gteRegis) * 100;
				}else{
					$gteCelular = 0;
				}
				if ($gteRegis > 0 && $sumEmail > 0){
					$gteEmail = ($sumEmail / $gteRegis) * 100;
				}else{
					$gteEmail = 0;
				}
				if ($gteRegis > 0 && $sumCedula > 0){
					$gteCedula = ($sumCedula / $gteRegis) * 100;
				}else{
					$gteCedula = 0;
				}
				if ($gteRegis > 0 && $sumDir > 0){
					$gteDir = ($sumDir / $gteRegis) * 100;
				}else{
					$gteDir = 0;
				}
				if ($gteRegis > 0 && $sumCpCol > 0){
					$gteCpCol = ($sumCpCol / $gteRegis) * 100;
				}else{
					$gteCpCol = 0;
				}
				if ($gteRegis > 0 && $sumCiudad > 0){
					$gteCiudad = ($sumCiudad / $gteRegis) * 100 ;
				}else{
					$gteCiudad = 0;
				}
				if ($gteRegis > 0 && $sumEstado > 0){
					$gteEstado = ($sumEstado / $gteRegis) * 100 ;
				}else{
					$gteEstado = 0;
				}
				
			}else{	
				////imprimir gerentes
				if($tipo != 3){	
					$tabla .= '<tr><td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteRegis).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteCateg, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteEsp, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteHon, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gtePacxsem, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteCompra, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteFrecVis, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteTel1, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteTel2, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteCelular, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteEmail, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteCedula, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteDir, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteCpCol, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteCiudad, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteEstado, 2).' %</td>';
					$tabla .= '</tr>';
				}else{ 
					$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
					$pdf->Cell(50,10,number_format($gteRegis),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteCateg, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteEsp, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteHon, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gtePacxsem, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteCompra, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteFrecVis, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteTel1, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteTel2, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteCelular, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteEmail, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteCedula, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteDir, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteCpCol, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteCiudad, 2).' %',1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteEstado, 2).' %',1,1,'C',1);
				}
	
				////inicia var gerente
				$tempGerente = $reg['REG_SNR'];
				$nombreGte = $reg['RM'];
				$gteRegis = $reg['Regist'];
				$sumCateg = $reg['Categ'];
				$sumEsp = $reg['Esp'];
				$sumHon = $reg['Honorarios'];
				$sumPacxsem = $reg['Pacxsem'];
				$sumCompra = $reg['Compra'];
				$sumFrecVis = $reg['Frec_Vis'];
				$sumTel1 = $reg['Tel1'];
				$sumTel2 = $reg['Tel2'];
				$sumCelular = $reg['Celular'];
				$sumEmail = $reg['Email'];
				$sumCedula = $reg['Cedula'];
				$sumDir = $reg['Direccion'];
				$sumCpCol = $reg['Cp_Colonia'];
				$sumCiudad = $reg['Ciudad'];
				$sumEstado = $reg['Estado'];
				$gteCateg = 0;
				$gteEsp = 0;
				$gteHon = 0;
				$gtePacxsem = 0;
				$gteCompra = 0;
				$gteFrecVis = 0;
				$gteTel1 = 0;
				$gteTel2 = 0;
				$gteCelular = 0;
				$gteEmail = 0;
				$gteCedula = 0;
				$gteDir = 0;
				$gteCpCol = 0;
				$gteCiudad = 0;
				$gteEstado = 0;
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';
		if ($reg['Regist'] > 0 && $reg['Categ'] > 0){
			$pCateg = ($reg['Categ'] / $reg['Regist']) * 100;
		}else{
			$pCateg = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Esp'] > 0){
			$pEsp = ($reg['Esp'] / $reg['Regist']) * 100;
		}else{
			$pEsp = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Honorarios'] > 0){
			$pHon = ($reg['Honorarios'] / $reg['Regist']) * 100;
		}else{
			$pHon = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Pacxsem'] > 0){
			$pPacxsem = ($reg['Pacxsem'] / $reg['Regist']) * 100;
		}else{
			$pPacxsem = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Compra'] > 0){
			$pCompra = ($reg['Compra'] / $reg['Regist']) * 100;
		}else{
			$pCompra = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Frec_Vis'] > 0){
			$pFrecVis = ($reg['Frec_Vis'] / $reg['Regist']) * 100;
		}else{
			$pFrecVis = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Tel1'] > 0){
			$pTel1 = ($reg['Tel1'] / $reg['Regist']) * 100;
		}else{
			$pTel1 = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Tel2'] > 0){
			$pTel2 = ($reg['Tel2'] / $reg['Regist']) * 100;
		}else{
			$pTel2 = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Celular'] > 0){
			$pCelular = ($reg['Celular'] / $reg['Regist']) * 100;
		}else{
			$pCelular = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Email'] > 0){
			$pEmail = ($reg['Email'] / $reg['Regist']) * 100;
		}else{
			$pEmail = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Cedula'] > 0){
			$pCedula = ($reg['Cedula'] / $reg['Regist']) * 100;
		}else{
			$pCedula = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Direccion'] > 0){
			$pDir = ($reg['Direccion'] / $reg['Regist']) * 100;
		}else{
			$pDir = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Cp_Colonia'] > 0){
			$pCpCol = ($reg['Cp_Colonia'] / $reg['Regist']) * 100;
		}else{
			$pCpCol = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Ciudad'] > 0){
			$pCiudad = ($reg['Ciudad'] / $reg['Regist']) * 100 ;
		}else{
			$pCiudad = 0;
		}
		if ($reg['Regist'] > 0 && $reg['Estado'] > 0){
			$pEstado = ($reg['Estado'] / $reg['Regist']) * 100 ;
		}else{
			$pEstado = 0;
		}

		////imprimir repres
		if($tipo != 3){
			$tabla .= '<td '.$estilorepre.' width="400px">'.$reg['SR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Regist'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pCateg, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pEsp, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pHon, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pPacxsem, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pCompra, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pFrecVis, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pTel1, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pTel2, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pCelular, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pEmail, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pCedula, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pDir, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pCpCol, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pCiudad, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($pEstado, 2).' %</td>';
			$tabla .= '</tr>';
		}else{
			$pdf->Cell(200,10,$reg['SR'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['Regist'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($pCateg, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pEsp, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pHon, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pPacxsem, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pCompra, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pFrecVis, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pTel1, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pTel2, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pCelular, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pEmail, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pCedula, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pDir, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pCpCol, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pCiudad, 2).' %',1,0,'C',0);
			$pdf->Cell(50,10,number_format($pEstado, 2).' %',1,1,'C',0);
		}
		$i++;
	}
	
	////imprimir ultimo gerente
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteRegis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteCateg, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteEsp, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteHon, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gtePacxsem, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteCompra, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteFrecVis, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteTel1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteTel2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteCelular, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteEmail, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteCedula, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteDir, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteCpCol, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteCiudad, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteEstado, 2).' %</td>';
		$tabla .= '</tr>';
	}else{
		$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
		$pdf->Cell(50,10,number_format($gteRegis, 2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteCateg, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteEsp, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteHon, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gtePacxsem, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteCompra, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteFrecVis, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteTel1, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteTel2, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteCelular, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteEmail, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteCedula, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteDir, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteCpCol, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteCiudad, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteEstado, 2).' %',1,1,'C',1);
	}	

	////imprimir nacional
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="400px">Total General</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRegis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalCateg, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalEsp, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalHon, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalPacxsem, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalCompra, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalFrecVis, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalTel1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalTel2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalCelular, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalEmail, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalCedula, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalDir, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalCpCol, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalCiudad, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalEstado, 2).' %</td>';
		$tabla .= '</tr>';
	}else{
		$pdf->Cell(200,10,'Total General',1,0,'L',1);
		$pdf->Cell(50,10,number_format($totalRegis, 2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalCateg, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalEsp, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalHon, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalPacxsem, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalCompra, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalFrecVis, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalTel1, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalTel2, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalCelular, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalEmail, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalCedula, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalDir, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalCpCol, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalCiudad, 2).' %',1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalEstado, 2).' %',1,1,'C',1);
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