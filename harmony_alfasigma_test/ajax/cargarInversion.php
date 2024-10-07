<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		//$idInversion = $_POST['idInversion'];
		
		if(isset($_POST['pantalla']) && $_POST['pantalla'] == 'cal'){
			$pantalla = 'cal';
		}else{
			$pantalla = '';
		}
		
		if(isset($_POST['regreso']) && $_POST['regreso'] != ''){
			$regreso = $_POST['regreso'];
		}else{
			$regreso = '';
		}
		
		if(isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] != ''){
			$tipoUsuario = $_POST['tipoUsuario'];
		}else{
			$tipoUsuario = 4;
		}
		
		if(isset($_POST['idUser']) && $_POST['idUser'] != ''){
			$idUser = $_POST['idUser'];
		}else{
			$idUser = '00000000-0000-0000-0000-000000000000';
		}
		
		if(isset($_POST['idInversion']) && $_POST['idInversion'] != ''){
			$idInversion = $_POST['idInversion'];
			$query = "select p.LNAME + ' ' + p.MOTHERS_LNAME + ' ' + p.FNAME as nombre,
				esp.NAME as especialidad,
				cat.name as categoria,
				u.USER_NR as ruta, u.LNAME + ' ' + u.MOTHERS_LNAME + ' ' + u.FNAME as repre,
				ui.name as concepto,
				ui.TYPE_SNR as tipo,
				ui.PROD_SNR,
				ui.DATE,
				ui.AMOUNT_INVESTED as monto,
				ui.info,
				ui.PERS_SNR,
				ui.user_snr
				from USER_INVESTMENT ui
				inner join person p on ui.PERS_SNR = p.PERS_SNR
				left outer join CODELIST esp on esp.CLIST_SNR = p.SPEC_SNR
				left outer join CODELIST cat on cat.CLIST_SNR = p.CATEGORY_SNR
				left outer join users u on u.USER_SNR = ui.USER_SNR
				where ui.USER_INVESTMENT_SNR = '".$idInversion."' ";
		}else if(isset($_POST['idPersona']) && $_POST['idPersona'] != ''){
			$idInversion = '';
			$idPersona = $_POST['idPersona'];
			$query = "select p.lname + ' ' + p.mothers_lname + ' ' + p.FNAME as nombre,
				esp.name as especialidad,
				u.USER_NR as ruta, u.LNAME + ' ' + u.mothers_lname + ' ' + u.FNAME as repre, 
				psw.user_snr, 
				cat.name as categoria
				from person p
				inner JOIN CODELIST ESP ON ESP.CLIST_SNR=P.SPEC_SNR
				inner join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR
				inner join users u on u.user_snr = psw.USER_SNR
				left outer join CODELIST cat on cat.CLIST_SNR = p.CATEGORY_SNR
				where p.pers_snr = '$idPersona'
				and psw.REC_STAT = 0";
		}
		
		//echo "inversion: ".$query."<br>";
		
		$inversion = sqlsrv_fetch_array(sqlsrv_query($conn, $query));
		
		$medico = $inversion['nombre'];
		
		if($idInversion == ''){
			if(isset($_POST['ruta']) && $_POST['ruta'] != ''){
				$idUsuario = $_POST['ruta'];
			}else{
				$idUsuario = $inversion['user_snr'];
			}
			$concepto = '';
			$tipoInversion = '00000000-0000-0000-0000-000000000000';
			$arrProductos = array();
			if( isset($_POST['fechaInversion']) && $_POST['fechaInversion'] != ''){
				$fecha_inversion = $_POST['fechaInversion'];
			}else{
				$fecha_inversion = date("Y-m-d");
			}
			$cantidadInvertida = 0;
			$objetivoInversion = '';
		}else{
			$concepto = $inversion['concepto'];
			$tipoInversion = $inversion['tipo'];
			$arrProductos = explode(";", $inversion['PROD_SNR']);
			foreach ($inversion['DATE'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha_inversion = substr($val, 0, 10);
				}
			}
			$cantidadInvertida = $inversion['monto'];
			$objetivoInversion = $inversion['info'];
			$idPersona = $inversion['PERS_SNR'];
			$idUsuario = $inversion['user_snr'];
		}
		
		echo "<script>";
		
		/*Productos*/
		$rsProductos = sqlsrv_query($conn, "select name as nombre, prod_snr as id from PRODUCT where prod_snr <> '00000000-0000-0000-0000-000000000000' order by name");
		$checkInversiones = 1;
		$contadorChecksInv = 0;
		$descripcionesCheckInv = '';
		while($regProductos = sqlsrv_fetch_array($rsProductos)){
			if(in_array($regProductos['id'], $arrProductos)){
				echo "$('#inversion".$checkInversiones."').prop('checked', true);";
				$descripcionesCheckInv .= $regProductos['nombre']."; ";
			}else{
				echo "$('#inversion".$checkInversiones."').prop('checked', false);";
			}
			$checkInversiones++;
			$contadorChecksInv++;
		}
		echo "$('#hdnTotalChecksInversiones').val('".$contadorChecksInv."');
			$('#hdnDescripcionChkInversiones').val('".$descripcionesCheckInv."');
			$('#sltMultiSelectInversiones').text('".$descripcionesCheckInv."');";
		
		/* fin productos*/
		
		echo "$('#hdnIdPersona').val('".$idPersona."');
			$('#hdnIdInversion').val('".$idInversion."');
			$('#hdnPantallaInversion').val('".$pantalla."');
			$('#btnGuardarInversion').prop('disabled', false);
			$('#lblMedicoInversion').text('".$medico."');
			$('#lblEspecialidadInversion').text('".$inversion['especialidad']."');
			$('#lblCategoriaInversion').text('".$inversion['categoria']."');
			$('#txtConceptoInversion').val('".$concepto."');
			$('#lstCodigoInversion').val('".$tipoInversion."');
			$('#txtFechaInversion').val('".$fecha_inversion."');
			$('#txtCantidadInversion').val('".$cantidadInvertida."');
			$('#objetivoInversion').val('".$objetivoInversion."'); 
			$('#sltRepreInversion').empty();";

		$nameDividido = explode('-',$inversion['repre']);
		$nameSinNR = count($nameDividido) >1 ? $nameDividido[1] : $nameDividido[0];
		$inversion['repre'] = $inversion['ruta'].' - '.$nameSinNR;
		if($tipoUsuario == 4){
			echo "$('#sltRepreInversion').append('<option value=\"".$inversion['user_snr']."\" selected=\"selected\">".$inversion['repre']."</option>');";
		}else{
			$regSuper = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from users where user_snr = '".$idUser."'"));
			$nameDividido = explode('-',$regSuper['LNAME']);
			$nameSinNR = count($nameDividido) >1 ? $nameDividido[1] : $nameDividido[0];
			$regSuper['LNAME'] = $regSuper['USER_NR'].' - '.$nameSinNR;

			if($tipoUsuario == 2){
				echo "$('#sltRepreInversion').append('<option value=\"".$inversion['user_snr']."\" >".$inversion['repre']."</option>');";

				if($idUsuario == '00000000-0000-0000-0000-000000000000'){
					echo "$('#sltRepreInversion').val('".$inversion['user_snr']."');";
				}else{
					echo "$('#sltRepreInversion').val('".$inversion['user_snr']."');";
				}
			}else{
				echo "$('#sltRepreInversion').append('<option value=\"".$inversion['user_snr']."\" >".$inversion['repre']."</option>');
				$('#sltRepreInversion').append('<option value=\"".$regSuper['USER_SNR']."\">".$regSuper['LNAME']." ".$regSuper['FNAME']."</option>');
				";
				$varUser = $inversion['user_snr'];

				if($idUsuario == '00000000-0000-0000-0000-000000000000'){
					echo "$('#sltRepreInversion').val('".$regSuper['USER_SNR']."');";
				}else{
					echo "$('#sltRepreInversion').val('".$inversion['user_snr']."');";
				}
			}
			
		}
		if($idInversion == ''){
			echo "$('#btnReportarInversion').hide();
				$('#hdnIdInversion').val('');
				$('#hdnIdInversionReportar').val('');";
		}else{
			echo "$('#btnReportarInversion').show();";
		}
		if($regreso == 'visita'){
			echo "$('#hdnRegreso').val('visita');";
		}else{
			echo "$('#hdnRegreso').val('');";
		}
		echo "</script>";
		//echo "select * from users where user_snr = '".$idUser."'";
	}
	//echo "tipoU: ".$idUser."<br>";
	
?>