<?php
	/*** listado de medicos ***/
	//include "../conexion.php";
	
	require('../pdf/fpdf.php');
	
	/*$estatus = $_POS['estatus'];
	$ids = $_POST['ids'];*/
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,250,250,250,100,100,100,100,150,350, 100,100,250,100,300,150,150,150,100,150, 150,150,100,100,100,100,100,100,100,100, 100,100,100,250,100,100,150,100,100,100, 100,100,100,100,100,100,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	$estatus = 'B426FB78-8498-4185-882D-E0DC381460E8';
	$ids = '64CA5A0A-8B3F-494B-B8A2-0EEFE8984293';
	
	include "queryListadoMedicos.php";
		
	$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos));
	
	$pdf=new FPDF('L', 'mm', array(3200,150));
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',10);
	$pdf->setDisplayMode(100, 'continuous');
	$pdf->Cell(40,5,utf8_decode('LISTADO DE MÉDICOS'));
	$pdf->Ln();
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(40,5,'Alfa Wassermann');
	$pdf->Ln();
	$pdf->SetFont('Arial',10);
	$pdf->Cell(40,5,'Fecha: '.date("d/m/Y h:i:s"));
	$pdf->Ln();
	
	$pdf->SetFillColor(169,188,245);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0);
	$pdf->SetLineWidth(1);
	$pdf->SetFont('','B');
	
	$i = 0;
	foreach(sqlsrv_field_metadata($rsMedicos) as $field){
		if($i<47){
			$pdf->Cell($tam[$i]/2,8,$field['Name'],1,0,'C',1);
		}
		$i++;
	}
	
	$pdf->Ln();
	//Restauración de colores y fuentes
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('');
	//Datos
	$fill=false;


	$i=1;
	while($regMedico = sqlsrv_fetch_array($rsMedicos)){
		for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
			if(is_object($regMedico[$j])){
				foreach ($regMedico[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$regMedico[$j] = substr($val, 0, 10);
					}
				}
			}
			if($j<47){
				$pdf->Cell($tam[$j]/2,8,$regMedico[$j],1,0,'L',$fill);
			}
		}
		$pdf->Ln();
		if($fill == true){
			$fill = false;
		}else{
			$fill = true;
		}
		$i++;
	}

	$pdf->Output();
?>
