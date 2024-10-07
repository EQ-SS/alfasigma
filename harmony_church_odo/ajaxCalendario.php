<?php
	include ("conexion.php");
	require('calendario/calendario.php');
	
	$dias = array("","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado","Domingo");
	$fechaCompleta = array();
	$fecha = $_POST['fecha'];
	$planVisita = $_POST['planVisita'];
	$idUsuario = $_POST['idUsuario'];
	$ids = $_POST['ids'];
	
	//echo "fecha: ".$fecha."<br>";
	
	if(isset($_POST['repre']) && $_POST['repre'] != ''){
		$repre = str_replace(",","','",substr($_POST['repre'], 0, -1));
	}else{
		$repre = $ids;
	}
	
	if(isset($_POST['repreNombres']) && $_POST['repreNombres'] != ''){
		$repreNombres = $_POST['repreNombres'];
	}else{
		$repreNombres = "Seleccione";
	}
	
	$week = date("W",mktime(0,0,0,substr($fecha, 5, 2), substr($fecha, 8, 2), substr($fecha, 0, 4)));
	
	
	$year = substr($fecha, 0, 4);
	
	for($i=-1; $i<6; $i++){
		//echo date('D N  Y-m-d', strtotime('01/01 +' . ($week ) . ' weeks first day +' . $i . ' day')) . '<br />';
		//$fechaCompleta[$dias[date('N', strtotime('01/01 +' . ($week ) . ' weeks first day +' . $i . ' day'))]] = date('d/m/Y', strtotime('01/01 +' . ($week ) . ' weeks first day +' . $i . ' day '));
		//$fechaPlanVisita[$dias[date('N', strtotime('01/01 +' . ($week ) . ' weeks first day +' . $i . ' day'))]] = date('Y-m-d', strtotime('01/01 +' . ($week ) . ' weeks first day +' . $i . ' day'));
		//echo date('d/m/Y', strtotime('01/01 +' . ($week ) . ' weeks first day +' . $i . ' day '))."<br>";
	}
	
	for($day=0; $day<8; $day++)
	{
		//echo date('d/m/Y', strtotime($year."W".$week.$day))."<br>";
		$fechaCompleta[$dias[date('N', strtotime($year."W".$week.$day))]] = date('d/m/Y', strtotime($year."W".$week.$day));
		$fechaPlanVisita[$dias[date('N', strtotime($year."W".$week.$day))]] = date('Y-m-d', strtotime($year."W".$week.$day));
		
	}
	print_r($fechaPlanVisita);
	$hoy = date('Y-m-d');
	$arrHoy = planesVisitasCalendarioE($hoy, $planVisita, $conn, $idUsuario, $repre);

	$arrLunes = planesVisitasCalendarioE($fechaPlanVisita["Lunes"], $planVisita, $conn, $idUsuario, $repre);
	$arrMartes = planesVisitasCalendarioE($fechaPlanVisita["Martes"], $planVisita, $conn, $idUsuario, $repre);
	$arrMiercoles = planesVisitasCalendarioE($fechaPlanVisita["Miercoles"], $planVisita, $conn, $idUsuario, $repre);
	$arrJueves = planesVisitasCalendarioE($fechaPlanVisita["Jueves"], $planVisita, $conn, $idUsuario, $repre);
	$arrViernes = planesVisitasCalendarioE($fechaPlanVisita["Viernes"], $planVisita, $conn, $idUsuario, $repre);
	$arrSabado = planesVisitasCalendarioE($fechaPlanVisita["Sábado"], $planVisita, $conn, $idUsuario, $repre);
	$arrDomingo = planesVisitasCalendarioE($fechaPlanVisita["Domingo"], $planVisita, $conn, $idUsuario, $repre);

	$diaL=substr($fechaCompleta['Lunes'], 0, 2);
	$mesL=substr($fechaCompleta['Lunes'], 3, 2);
	$anioL=substr($fechaCompleta['Lunes'], 6, 4);
	$diaM=substr($fechaCompleta['Martes'], 0, 2);
	$mesM=substr($fechaCompleta['Martes'], 3, 2);
	$anioM=substr($fechaCompleta['Martes'], 6, 4);
	$diaMi=substr($fechaCompleta['Miercoles'], 0, 2);
	$mesMi=substr($fechaCompleta['Miercoles'], 3, 2);
	$anioMi=substr($fechaCompleta['Miercoles'], 6, 4);
	$diaJ=substr($fechaCompleta['Jueves'], 0, 2);
	$mesJ=substr($fechaCompleta['Jueves'], 3, 2);
	$anioJ=substr($fechaCompleta['Jueves'], 6, 4);
	$diaV=substr($fechaCompleta['Viernes'], 0, 2);
	$mesV=substr($fechaCompleta['Viernes'], 3, 2);
	$anioV=substr($fechaCompleta['Viernes'], 6, 4);
	$diaS=substr($fechaCompleta['Sábado'], 0, 2);
	$mesS=substr($fechaCompleta['Sábado'], 3, 2);
	$anioS=substr($fechaCompleta['Sábado'], 6, 4);
	$diaD=substr($fechaCompleta['Domingo'], 0, 2);
	$mesD=substr($fechaCompleta['Domingo'], 3, 2);
	$anioD=substr($fechaCompleta['Domingo'], 6, 4);

	echo "<script>
		$('#lblHoy').text('".date('d/m/Y')."');

		$('#hdnDiaUno').val('".date('Y-m-d', strtotime($year.'W'.$week.'1'))."');
		$('#hdnDiaDos').val('".date('Y-m-d', strtotime($year.'W'.$week.'2'))."');
		$('#hdnDiaTres').val('".date('Y-m-d', strtotime($year.'W'.$week.'3'))."');
		$('#hdnDiaCuatro').val('".date('Y-m-d', strtotime($year.'W'.$week.'4'))."');
		$('#hdnDiaCinco').val('".date('Y-m-d', strtotime($year.'W'.$week.'5'))."');
		$('#hdnDiaSeis').val('".date('Y-m-d', strtotime($year.'W'.$week.'6'))."');
		$('#hdnDiaSiete').val('".date('Y-m-d', strtotime($year.'W'.$week.'7'))."');


		$('#lblTituloDiaUno').text('Lunes: ".$fechaCompleta['Lunes']."');
		$('#lblTituloDiaDos').text('Martes: ".$fechaCompleta['Martes']."');
		$('#lblTituloDiaTres').text('Miércoles: ".$fechaCompleta['Miercoles']."');
		$('#lblTituloDiaCuatro').text('Jueves: ".$fechaCompleta['Jueves']."');
		$('#lblTituloDiaCinco').text('Viernes: ".$fechaCompleta['Viernes']."');
		$('#lblTituloDiaSeis').text('Sábado: ".$fechaCompleta['Sábado']."');
		$('#lblTituloDiaSiete').text('Domingo: ".$fechaCompleta['Domingo']."');
		
		$('#totalPersonasDiaUno').text('".$arrLunes[1]."');
		$('#totalInstDiaUno').text('".$arrLunes[2]."');
		$('#totalOtrasActividadesDiaUno').text('".$arrLunes[3]."');
		$('#totalPersonasDiaDos').text('".$arrMartes[1]."');
		$('#totalInstDiaDos').text('".$arrMartes[2]."');
		$('#totalOtrasActividadesDiaDos').text('".$arrMartes[3]."');
		$('#totalPersonasDiaTres').text('".$arrMiercoles[1]."');
		$('#totalInstDiaTres').text('".$arrMiercoles[2]."');
		$('#totalOtrasActividadesDiaTres').text('".$arrMiercoles[3]."');
		$('#totalPersonasDiaCuatro').text('".$arrJueves[1]."');
		$('#totalInstDiaCuatro').text('".$arrJueves[2]."');
		$('#totalOtrasActividadesDiaCuatro').text('".$arrJueves[3]."');
		$('#totalPersonasDiaCinco').text('".$arrViernes[1]."');
		$('#totalInstDiaCinco').text('".$arrViernes[2]."');
		$('#totalOtrasActividadesDiaCinco').text('".$arrViernes[3]."');
		$('#totalPersonasDiaSeis').text('".$arrSabado[1]."');
		$('#totalInstDiaSeis').text('".$arrSabado[2]."');
		$('#totalOtrasActividadesDiaSeis').text('".$arrSabado[3]."');
		$('#totalPersonasDiaSiete').text('".$arrDomingo[1]."');
		$('#totalInstDiaSiete').text('".$arrDomingo[2]."');
		$('#totalOtrasActividadesDiaSiete').text('".$arrDomingo[3]."');

		$('#txtFechaInicioSemana').text('".$fechaCompleta['Lunes']."');
		$('#txtFechaFinSemana').text('".$fechaCompleta['Domingo']."');
		
		diaLunes = ".$diaL.";
		mesLunes = ".$mesL.";
		anioLunes = ".$anioL.";
		diaMartes = ".$diaM.";
		mesMartes = ".$mesM.";
		anioMartes = ".$anioM.";
		diaMiercoles = ".$diaMi.";
		mesMiercoles = ".$mesMi.";
		anioMiercoles = ".$anioMi.";
		diaJueves = ".$diaJ.";
		mesJueves = ".$mesJ.";
		anioJueves = ".$anioJ.";
		diaViernes = ".$diaV.";
		mesViernes = ".$mesV.";
		anioViernes = ".$anioV.";
		diaSabado = ".$diaS.";
		mesSabado = ".$mesS.";
		anioSabado = ".$anioS.";
		diaDomingo = ".$diaD.";
		mesDomingo = ".$mesD.";
		anioDomingo = ".$anioD.";

		$('#tblHoy').empty();
		$('#tblDiaUno').empty();
		$('#tblDiaDos').empty();
		$('#tblDiaTres').empty();
		$('#tblDiaCuatro').empty();
		$('#tblDiaCinco').empty();
		$('#tblDiaSeis').empty();
		$('#tblDiaSiete').empty();";

		$arrHoyReg = explode("|", substr($arrHoy[0], 0, -1));
		$arrLunesReg = explode("|", substr($arrLunes[0], 0, -1));
		$arrMartesReg = explode("|", substr($arrMartes[0], 0, -1));
		$arrMiercolesReg = explode("|", substr($arrMiercoles[0], 0, -1));
		$arrJuevesReg = explode("|", substr($arrJueves[0], 0, -1));
		$arrViernesReg = explode("|", substr($arrViernes[0], 0, -1));
		$arrSabadoReg = explode("|", substr($arrSabado[0], 0, -1));
		$arrDomingoReg = explode("|", substr($arrDomingo[0], 0, -1));
		//print_r($arrLunesReg);

		if(! empty($arrHoy[0])){
			for($i=0;$i<count($arrHoyReg);$i++){
				$arrTr = explode("&", $arrHoyReg[$i]);
				if(count($arrTr) == 3){
					if($arrTr[0] == ''){//otras actividades
						echo "$('#tblHoy').append('<tr><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}else{
						echo "$('#tblHoy').append('<tr onClick=\"".str_replace("'", "\'", $arrTr[0])."\"><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}
				}
			}
		}

		if(! empty($arrLunes[0])){
			for($i=0;$i<count($arrLunesReg);$i++){
				$arrTr = explode("&", $arrLunesReg[$i]);
				if(count($arrTr) == 3){
					if($arrTr[0] == ''){//otras actividades
						echo "$('#tblDiaUno').append('<tr><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}else{
						echo "$('#tblDiaUno').append('<tr onClick=\"".str_replace("'", "\'", $arrTr[0])."\"><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}
				}
			}
		}
		
		if(! empty($arrMartes[0])){
			for($i=0;$i<count($arrMartesReg);$i++){
				$arrTr = explode("&", $arrMartesReg[$i]);
				if(count($arrTr) == 3){
					if($arrTr[0] == ''){//otras actividades
						echo "$('#tblDiaDos').append('<tr><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}else{
						echo "$('#tblDiaDos').append('<tr onClick=\"".str_replace("'", "\'", $arrTr[0])."\"><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}
				}
			}
		}
		
		if(! empty($arrMiercoles[0])){
			for($i=0;$i<count($arrMiercolesReg);$i++){
				$arrTr = explode("&", $arrMiercolesReg[$i]);
				if(count($arrTr) == 3){
					if($arrTr[0] == ''){//otras actividades
						echo "$('#tblDiaTres').append('<tr><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}else{
						echo "$('#tblDiaTres').append('<tr onClick=\"".str_replace("'", "\'", $arrTr[0])."\"><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}
				}
			}
		}
		
		if(! empty($arrJueves[0])){
			for($i=0;$i<count($arrJuevesReg);$i++){
				$arrTr = explode("&", $arrJuevesReg[$i]);
				if(count($arrTr) == 3){
					if($arrTr[0] == ''){//otras actividades
						echo "$('#tblDiaCuatro').append('<tr><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}else{
						echo "$('#tblDiaCuatro').append('<tr onClick=\"".str_replace("'", "\'", $arrTr[0])."\"><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}
				}
			}
		}
		
		if(! empty($arrViernes[0])){
			for($i=0;$i<count($arrViernesReg);$i++){
				$arrTr = explode("&", $arrViernesReg[$i]);
				if(count($arrTr) == 3){
					if($arrTr[0] == ''){//otras actividades
						echo "$('#tblDiaCinco').append('<tr><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}else{
						echo "$('#tblDiaCinco').append('<tr onClick=\"".str_replace("'", "\'", $arrTr[0])."\"><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}
				}
			}
		}

		if(! empty($arrSabado[0])){
			for($i=0;$i<count($arrSabadoReg);$i++){
				$arrTr = explode("&", $arrSabadoReg[$i]);
				if(count($arrTr) == 3){
					if($arrTr[0] == ''){//otras actividades
						echo "$('#tblDiaSeis').append('<tr><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}else{
						echo "$('#tblDiaSeis').append('<tr onClick=\"".str_replace("'", "\'", $arrTr[0])."\"><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}
				}
			}
		}

		if(! empty($arrDomingo[0])){
			for($i=0;$i<count($arrDomingoReg);$i++){
				$arrTr = explode("&", $arrDomingoReg[$i]);
				if(count($arrTr) == 3){
					if($arrTr[0] == ''){//otras actividades
						echo "$('#tblDiaSiete').append('<tr><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}else{
						echo "$('#tblDiaSiete').append('<tr onClick=\"".str_replace("'", "\'", $arrTr[0])."\"><td style=\"".$arrTr[1]."\">".$arrTr[2]."</td></tr>');";
					}
				}
			}
		}
?>
