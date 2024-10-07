<?php
	include "../conexion.php";
	if(isset($_POST['lat']) && $_POST['lat'] != ''){
		$lat = $_POST['lat'];
	}else{
		$lat = '';
	}
	if(isset($_POST['lon']) && $_POST['lon'] != ''){
		$lon = $_POST['lon'];
	}else{
		$lon = '';
	}
	
	if(isset($_POST['idPlan']) && $_POST['idPlan'] != ''){
		$idPlan = $_POST['idPlan'];
	}else{
		$idPlan = '';
	}
	
	if(isset($_POST['pantalla']) && $_POST['pantalla'] == 'cal'){
		$pantalla = 'cal';
	}else{
		$pantalla = '';
	}
	
	if(isset($_POST['idTipoInst']) && $_POST['idTipoInst'] != ''){
		$idTipoInst = $_POST['idTipoInst'];
	}else{
		$idTipoInst = "";
	}
	
	if(isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] != ''){
		$tipoUsuario = $_POST['tipoUsuario'];
	}else{
		$tipoUsuario = "";
	}
	
	if(isset($_POST['idRepre']) && $_POST['idRepre'] != ''){
		$idUser = $_POST['idRepre'];
	}else{
		$idUser = $idUser = '00000000-0000-0000-0000-000000000000';
	}
	
	/*if(isset($_GET['idVisita'])){
		echo "vis".$_GET['idVisita'];
	}*/
	
	//echo "tipoUsuario: ".$tipoUsuario."<br>";
	if(isset($_POST['idVisita']) && $_POST['idVisita'] != '' && $_POST['idVisita'] != '00000000-0000-0000-0000-000000000000'){
		$idVisita = $_POST['idVisita'];
		
		$query = "select visit_date as Fecha_Vis,
			time,
			u.lname + ' ' + u.fname as Rep,
			vp.visit_code_snr,
			vp.info as informacion_vis,
			vp.visinst_snr,
			vp.user_snr,
			vp.inst_snr,
			vp.escort_snr  as visAcomp,
			prod.name as producto,
			pf.name as presentacion,
			vpb.quantity as cantidad,
			firma.datastream as firma,
			vp.info_nextvisit,
			lote.name as lote, 
			pf.type tipo,
			i.name as nombre, 
			i.street1 as calle,
			cp.name as col,
			cp.zip as cp,
			pob.name as del,
			edo.name as estado,
			bri.name as brick,
			(select top 1 info from VISITINST where VISINST_SNR = vp.visinst_snr and VISIT_DATE in (select max(VISIT_DATE) from visitinst)) as info_ult_vis,
			vp.info_nextvisit,
			i.inst_type as tipoInst
			from visitinst vp 
			inner join users u on vp.user_snr=u.user_snr 
			left outer join VISITINST_PRODBATCH vpb on vpb.visinst_snr = vp.visinst_snr 
			left outer join prodformbatch lote on lote.PRODFBATCH_SNR = vpb.PRODFBATCH_SNR 
			left outer join prodform pf on pf.prodform_snr = lote.PRODFORM_SNR
			left outer join product prod on prod.prod_snr = pf.prod_snr 
			left outer join binarydata firma on firma.record_key = vp.visinst_snr and firma.rec_stat=0 and firma.datastream is not null
			inner join inst i on i.INST_SNR = vp.INST_SNR
			inner join city cp on cp.CITY_SNR = i.CITY_SNR
			inner join DISTRICT pob on pob.DISTR_SNR = cp.DISTR_SNR
			inner join STATE edo on edo.STATE_SNR = cp.STATE_SNR
			inner join BRICK bri on bri.BRICK_SNR = cp.BRICK_SNR
			where vp.rec_stat=0  
			and vp.visinst_snr = '".$idVisita."' 
			order by fecha_vis desc ";

	}else if(isset($_POST['idInst']) && $_POST['idInst'] != ''){
		$idVisita = '';
		$idInst = $_POST['idInst'];
		$idUsuario = $_POST['idUsuario'];
		
		$queryRepre = sqlsrv_fetch_array(sqlsrv_query($conn, "select lname + ' ' + mothers_lname + ' ' + fname as nombre from users where USER_SNR = '".$idUsuario."'"));
		
		$query ="select i.name as nombre_inst, 
			i.STREET1 as direccion,
			cp.name as colonia,
			cp.zip as cpostal,
			pob.name as pob,
			edo.name as edo,
			bri.name as brick,
			i.inst_type as tipoInst,
			(select top 1 info_nextvisit from VISITINST where INST_SNR = i.inst_snr and VISIT_DATE in (select max(VISIT_DATE) from VISITINST where inst_snr = i.inst_snr)) as info_ult_vis
			from inst i, city cp, district pob, state edo, BRICK bri, CODELIST tipo 
			where i.INST_SNR='".$idInst."'
			and cp.city_snr=i.city_snr
			and cp.distr_snr=pob.distr_snr 
			and cp.state_snr=edo.state_snr 
			and tipo.clist_snr = i.type_snr
			and bri.BRICK_SNR = cp.BRICK_SNR";
	}
	//(select info from VISINSTPLAN where INST_SNR = i.inst_snr and datum in (select max(datum) from vispersplan)) as info_ult_vis
	//echo "hello: ".$query;
	$rsVisita = sqlsrv_query($conn, $query);
	if($idVisita == ''){
		$visita = sqlsrv_fetch_array($rsVisita);
		if(isset($_POST['fechaVisita']) && $_POST['fechaVisita'] != ''){
			$fecha_vis = $_POST['fechaVisita'];
		}else{
			$fecha_vis = date("Y-m-d");
		}
		$hora = date("H");
		$minutos = date("i");
		$tipoVisita = "";
		$infoVisita = $visita['info_ult_vis'];
		$visAcomp = "";
		$firma = "";
		$infoUltimoPlan = "";
		$visitAcom = array("CD1BD81F-AC66-4671-B2FF-CF1AF8F3C3CB");
		$infoSigVisita = "";
		$rep = $queryRepre['nombre'];
		//$idPers = $visita['pers_snr'];
		//$persona = $visita['nombre'];
		//$especialidad = $visita['especialidad'];
		$dir = $visita['direccion']." ".strtoupper('Calle: '.$visita['direccion'].', C.P.: '.$visita['cpostal'].', Colonia: '.$visita['colonia'].', '.utf8_decode('Población').': '.$visita['pob'].', Estado: '.$visita['edo'].'.');
		$brick = $visita['brick'];
		//$idUsuario = $visita['user_snr'];
		$productos = array();
		//$enfermedades_atiende = $visita['enfermedades_atiende'];
		$inst = $visita['nombre_inst'];
		$idRepre = $idUsuario;
		$tipoInst = $visita['tipoInst'];
		if($tipoInst == 2){
			$visitCode = "A57BDD86-54A6-41BE-9D7D-7FE08264E632";
		}else{
			$visitCode = "F8FA7B25-70BF-4BC3-8D4D-B213CFD69042";
		}
	}else{
		$contador = 0;
		$productos = array();
		while($visita = sqlsrv_fetch_array($rsVisita)){
			if($contador == 0){
				foreach ($visita['Fecha_Vis'] as $key => $val) {
					if(strtolower($key) == 'date'){
						$fecha_vis = substr($val, 0, 10);
					}
				}
				$arrHora = explode(":", str_replace(".",":",$visita['time']));
				$hora = $arrHora[0];
				$minutos = $arrHora[1];
				$rep = $visita['Rep'];
				$idRepre = $visita['user_snr'];
				//$tipoVisita = $visita['Tipo_Vis'];
				$infoVisita = $visita['informacion_vis'];
				$idInst = $visita['inst_snr'];
				//$visAcomp = explode(";", $visita['visAcomp']);
				$firma = $visita['firma'];
				$infoUltimoPlan = $visita['info_ult_vis'];
				$inst = $visita['nombre'];
				//$especialidad = $visita['especialidad'];
				$dir = $visita['nombre']." ".strtoupper('Calle: '.$visita['calle'].', C.P.: '.$visita['cp'].', Colonia: '.$visita['col'].', Población: '.$visita['del'].', Estado: '.$visita['estado'].'.');
				$brick = $visita['brick'];
				$visitCode = $visita['visit_code_snr'];
				$visitAcom = explode(";", $visita['visAcomp']);
				$infoSigVisita = $visita['info_nextvisit'];
				$idUsuario = $visita['user_snr'];
				//$enfermedades_atiende = $visita['enfermedades_atiende'];
				if($visita['producto'] != ''){
					$producto = array('producto' => $visita['producto'],
						'presentacion' => $visita['presentacion'],
						'cantidad' => $visita['cantidad'],
						'lote' => $visita['lote']);
					$productos[] = $producto;
				}
				$tipoInst = $visita['tipoInst'];
			}else{
				$producto = array('producto' => $visita['producto'],
						'presentacion' => $visita['presentacion'],
						'cantidad' => $visita['cantidad'],
						'lote' => $visita['lote']);
				$productos[] = $producto;
			}
			$contador++;
		}
	}
	
	//echo "tipoInst: ".$tipoInst;
	
	echo "<script>
		$('#hdnIdInst').val('".$idInst."');
		$('#hdnPantallaVisitasInst').val('".$pantalla."');
		$('#hdnIdVisitaInst').val('".$idVisita."');
		$('#hdnIdPlan').val('".$idPlan."');
		$('#hdnIdProductosCompetidores').val('');
		$('#hdnExistenciaCompetidores').val('');
		$('#hdnPrecioCompetidores').val('');
		$('#btnGuardarVisitasInst').prop('disabled', false);
		$('#lblInstVisitasInst').text('".utf8_encode($inst)."');
		$('#lblDirVisitasInst').text('".utf8_encode($dir)."');
		$('#lblBrickVisitasInst').text('".$brick."');
		$('#txtFechaVisitasInst').val('".$fecha_vis."');
		$('#lstHoraVisistasInst').val('".$hora."');
		$('#lstMinutosVisitasInst').val('".$minutos."');
		$('#txtComentariosVisitaInst').val('".utf8_encode($infoVisita)."');
		$('#txtInfoSiguienteVisitaInst').val('".utf8_encode($infoSigVisita)."');
		$('#tblProductosVisitasInst tbody').empty();
		$('#tblMuestrasVisitasInst tbody').empty();
		limpiarInst();
		limpiarChecksVisitaAcompa('instituciones');
		$('#hdnTotalProdcutosSeleccionadosStock').val(0);
		$('#hdnTotalProdcutosSeleccionados').val(0);
		$('#sltProductoStock').val('00000000-0000-0000-0000-000000000000');
		$('#sltRepreVisitasInst').empty();
		$('#lstCodigoVisitaInst').empty();
		$('#sltMultiSelectInstVisita').text('');";
		
	if($tipoInst == 2){//pharmacia 
		$rsCodigo = llenaCombo($conn, 34, 1);
	}else if($tipoInst == 1){//hospital
		$rsCodigo = llenaCombo($conn, 34, 2);
	}
	
	while($codigo = sqlsrv_fetch_array($rsCodigo)){
		echo "$('#lstCodigoVisitaInst').append('<option value=\"".$codigo['id']."\" selected=\"selected\">".utf8_encode($codigo['nombre'])."</option>');";
	}
	
	echo "$('#lstCodigoVisitaInst').val('".$visitCode."');";
	
	if($idVisita == ''){
		echo "$('#sltRepreVisitasInst').append('<option value=\"".$idRepre."\" selected=\"selected\">".$rep."</option>');";
		if($tipoUsuario != 4){
			$regUser = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from users where user_snr = '".$idUser."' "));
			//echo "select * from users where user_snr = '".$idUser."' ";
			$rep = $regUser['LNAME'].' '.$regUser['FNAME'].' '.$regUser['MOTHERS_LNAME'];
			echo "$('#sltRepreVisitasInst').append('<option value=\"".$regUser['USER_SNR']."\" selected=\"selected\">".utf8_encode($rep)."</option>');";
		}
		$queryProductos = "select * from product where REC_STAT = 0 order by name";
		$rsProdcutos = sqlsrv_query($conn, $queryProductos);
		$renglon = '<tr class="align-center"><td style="vertical-align:middle;">1</td><td><div class="select"><select id="lstProductoInst1" onchange="llenaSiguienteVisitasInst(1);" class="form-control">';
		while($producto = sqlsrv_fetch_array($rsProdcutos)){
			$renglon .= '<option value="'.$producto['PROD_SNR'].'">'.$producto['NAME'].'</option>';
		}
		$renglon .= '</select><div class="select_arrow"></div></div></td><td><input class="form-control" type="text" id="txtProdPosInst1" value="" size="3" readonly /></td></tr>';
		echo "$('#tblProductosVisitasInst tbody').append('".$renglon."');";
		for($i=2;$i<11;$i++){
			echo "$('#tblProductosVisitasInst tbody').append('<tr class=\"align-center\"><td style=\"vertical-align:middle;\">".$i."</td><td><div class=\"select\"><select class=\"form-control\" id=\"lstProductoInst".$i."\" onchange=\"llenaSiguienteVisitasInst(".$i.");\"><option>Seleccione</option></select><div class=\"select_arrow\"></div></div></td><td><input class=\"form-control\" type=\"text\" id=\"txtProdPosInst".$i."\" value=\"\" size=\"3\" readonly /></td></tr>');";
		}
		echo "$('#sltProductoStock').empty();
			$('#tblContenedorStock').show();
			$('#tblProductosStockConsulta').hide();";
		$rsProductos = sqlsrv_query($conn, "select * from PRODUCT where STOCKVISIBLE = 1 and REC_STAT = 0 order by cast(customer_code as int )");
		echo "$('#sltProductoStock').append('<option value=\"\" >Seleccione</option>');";
		while($producto = sqlsrv_fetch_array($rsProductos)){
			echo "$('#sltProductoStock').append('<option value=\"".$producto['PROD_SNR']."\" >".$producto['NAME']."</option>');";
		}
	}else{
		echo "$('#sltRepreVisitasInst').append('<option value=\"".$idRepre."\" selected=\"selected\">".$rep."</option>');";
		/*visita acompañada*/
/*		$rsAcom = sqlsrv_query($conn, "select * from codelist where CLIb_SNR = (select  clib_snr from codelistlib where table_nr = 33 and LIST_NR = 142) and status = 1 order by sort_num, name");
		$check = 1;
		$contadorChecks = 0;
		$descripcionesCheck = '';
		while($regAcom = sqlsrv_fetch_array($rsAcom)){
			if(in_array($regAcom['CLIST_SNR'], $visitAcom)){
				echo "$('#acompaInst".$check."').prop('checked', true);";
				$descripcionesCheck .= $regAcom['NAME']."; ";
			}else{
				echo "$('#acompaInst".$check."').prop('checked', false);";
			}
			$check++;
			$contadorChecks++;
		}
		echo "$('#hdnTotalChecksInst').val('".$contadorChecks."');
			$('#hdnDescripcionChkVisitasInst').val('".$descripcionesCheck."');
			$('#sltMultiSelectInstVisita').text('".$descripcionesCheck."');";
*/			
		/* fin visita acompañada*/
		$queryProductos = "select p.prod_snr as idProducto, p.name as producto, vp.POSITION as posicion, vp.PERCENTAGE as porcentaje
			from VISITINST_PROD vp, product p 
			where vp.visinst_snr = '".$idVisita."' 
			and vp.prod_snr = p.prod_snr
			and vp.rec_stat = 0 
			order by posicion";
			//echo $queryProductos;
		$rsProdcutos = sqlsrv_query($conn, $queryProductos, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		if(sqlsrv_num_rows($rsProdcutos)>0){
			$cuentaProducto = 1;
			while($producto = sqlsrv_fetch_array($rsProdcutos)){
				echo "$('#tblProductosVisitasInst tbody').append('<tr class=\"align-center\"><td>".$cuentaProducto."</td><td><div class=\"select\"><select class=\"form-control\"><option>".$producto['producto']."</option></select></select><div class=\"select_arrow\"></td><td><input class=\"form-control\" type=\"text\" value=\"".$producto['porcentaje']."\" size=\"3\" readonly /></td></tr>');";
				$cuentaProducto++;
			}
			for($i=$cuentaProducto;$i<11;$i++){
				echo "$('#tblProductosVisitasInst tbody').append('<tr class=\"align-center\"><td>".$i."</td><td><div class=\"select\"><select class=\"form-control\"><option>Seleccione</option></select></select><div class=\"select_arrow\"></td><td><input class=\"form-control\" type=\"text\" value=\"\" size=\"3\" readonly /></td></tr>');";
			}
		}else{
			$queryProductos = "select * from product where REC_STAT = 0 order by name";
			$rsProdcutos = sqlsrv_query($conn, $queryProductos);
			$renglon = '<tr class="align-center"><td style="vertical-align:middle;">1</td><td><select id="lstProductoInst1" class="form-control" onchange="llenaSiguienteVisitasInst(1);">';
			while($producto = sqlsrv_fetch_array($rsProdcutos)){
				$renglon .= '<option value="'.$producto['PROD_SNR'].'">'.$producto['NAME'].'</option>';
			}
			$renglon .= '</select></td><td><input class="form-control" id="txtProdPosInst" type="text" value="" size="3" readonly /></td></tr>';
			echo "$('#tblProductosVisitasInst tbody').append('".$renglon."');";
			for($i=2;$i<11;$i++){
				echo "$('#tblProductosVisitasInst tbody').append('<tr class=\"align-center\"><td>".$i."</td><td><select class=\"form-control\" id=\"lstProductoInst".$i."\" onchange=\"llenaSiguienteVisitasInst(".$i.");\"><option>Seleccione</option></select></td><td><input class=\"form-control\" id=\"txtProdPosInst".$i."\" type=\"text\" value=\"\" size=\"3\" /></td></tr>');";
			}
		}
		
		$queryStock = "select p.name producto, pf.name presentacion, ipf.quantity existencia, ipf.PRICE precio, ipf.DISPLACE pedido, ipf.SUGGEST sugerido 
			from INST_STOCK ipf, PRODFORM pf, product p
			where ipf.VISINST_SNR = '".$idVisita."'
			and ipf.PRODFORM_SNR = pf.PRODFORM_SNR
			and p.PROD_SNR = pf.PROD_SNR
			and pf.type = 132
			order by p.name, pf.NAME ";
		$rsStock = sqlsrv_query($conn, $queryStock);
		echo "$('#tblProductosStockConsulta tbody').empty();
			$('#tblContenedorStock').hide();
			$('#tblProductosStockConsulta').show();";
		while($stock = sqlsrv_fetch_array($rsStock)){
			echo "$('#tblProductosStockConsulta tbody').append('<tr><td width=\"65%\" >".$stock['producto']." ".$stock['presentacion']."</td><td width=\"100px\" align=\"center\">".$stock['existencia']."</td><td width=\"100px\" align=\"center\">".$stock['precio']."</td><td width=\"100px\" align=\"center\">".$stock['pedido']."</td><td width=\"100px\" align=\"center\">".$stock['sugerido']."</td></tr>');";
		}
	}
	
	/*visita acompañada*/
	$rsAcom = sqlsrv_query($conn, "select * from codelist where CLIb_SNR = (select  clib_snr from codelistlib where table_nr = 33 and LIST_NR = 142) and status = 1 order by sort_num, name");
	$check = 1;
	$contadorChecks = 0;
	$descripcionesCheck = '';
	while($regAcom = sqlsrv_fetch_array($rsAcom)){
		if(in_array($regAcom['CLIST_SNR'], $visitAcom)){
			echo "$('#acompaInst".$check."').prop('checked', true);";
			$descripcionesCheck .= $regAcom['NAME']."; ";
		}else{
			echo "$('#acompaInst".$check."').prop('checked', false);";
		}
		$check++;
		$contadorChecks++;
	}
	echo "$('#hdnTotalChecksInst').val('".$contadorChecks."');
		$('#hdnDescripcionChkVisitasInst').val('".$descripcionesCheck."');
		$('#sltMultiSelectInstVisita').text('".$descripcionesCheck."');";
		
	/* fin visita acompañada*/
	
	if(count($productos)>0){
		for($i=0;$i<count($productos);$i++){
			echo "$('#tblMuestrasVisitasInst tbody').append('<tr><td>".$productos[$i]['producto']."</td><td>".$productos[$i]['presentacion']."</td><td>".$productos[$i]['lote']."</td><td align=\'center\'>".$productos[$i]['cantidad']."</td></tr>');";
		}
		$numTexto = 1;
	}else{
		$queryPromociones = "select * from (
			SELECT LOTE.PRODFBATCH_SNR AS ID_PRODUCTO,
			PROD.NAME AS PRODUCTO,
			PF.NAME PRES,
			LOTE.name AS LOTE,
			PF.quantity_allowed_visit,
			PF.type, (select sum(a.quantity) from stock_prodform_user a where quantity>0 
			and a.rec_stat = 0 and a.user_snr='".$idUsuario."' 
			and lote.prodfbatch_snr = a.prodfbatch_snr 
			and a.accepted=1 group by a.user_snr,a.prodfbatch_snr) as ENTRADA,
			isnull((select sum(quantity) 
			from visitpers_prodbatch, visitpers 
			where visitpers_prodbatch.vispers_snr=visitpers.vispers_snr 
			and visitpers_prodbatch.prodfbatch_snr=lote.prodfbatch_snr 
			and lote.REC_STAT=0 
			and prod.REC_STAT=0 
			and pf.REC_STAT=0 and visitpers.user_snr='".$idUsuario."' 
			and visitpers.rec_stat=0 and visitpers_prodbatch.rec_stat=0 ),0) as SALIDA, 
			isnull((select sum(quantity) 
			from visitinst_prodbatch vprod, visitinst v 
			where vprod.visinst_snr=v.visinst_snr 
			and vprod.prodfbatch_snr=lote.prodfbatch_snr and v.user_snr='".$idUsuario."' and v.rec_stat=0 and vprod.rec_stat=0),0) as SALIDA_INST,
			isnull((select quantity from MARKETING_PLAN mplan  
			inner join MARKETING_PLAN_SPECIALTY mplan_esp on mplan_esp.mplan_snr=mplan.mplan_snr and mplan_esp.rec_stat=0 
			inner join MARKETING_PLAN_SPEC_PROD mplan_esp_prod on mplan_esp.mplanspec_snr=mplan_esp_prod.mplanspec_snr and mplan_esp_prod.rec_stat=0 
			inner join MARKETING_PLAN_PROD_BATCH mplan_prod_lote on mplan_prod_lote.mplanspecprod_snr=mplan_esp_prod.mplanspecprod_snr and mplan_prod_lote.rec_stat=0 and mplan_prod_lote.prodfbatch_snr=lote.prodfbatch_snr 
			inner join codelist esp on esp.clist_snr=mplan_esp.spec_snr 
			inner join product prod on prod.prod_snr=mplan_esp_prod.prod_snr 
			inner join cycles ciclo on ciclo.cycle_snr=mplan.cycle_snr 
			where mplan.rec_stat=0 /*and ciclo.name = '2018-11-11' and esp.name='PEDIATRIA'*/),0) CANT_MPLAN
			FROM PRODFORM PF 
			inner join PRODUCT PROD  on PF.PROD_SNR=PROD.PROD_SNR
			LEFT OUTER JOIN PRODFORMBATCH LOTE ON LOTE.PRODFORM_SNR = PF.PRODFORM_SNR) a
			WHERE ENTRADA>0 
			AND (ENTRADA-SALIDA)>0 
			ORDER BY PRODUCTO,PRES,LOTE ";
		
		//echo $queryPromociones;
		
		$rsQueryPromociones = sqlsrv_query($conn, $queryPromociones);
		$numTexto = 1;
		while($promocion = sqlsrv_fetch_array($rsQueryPromociones)){
			$existencia = $promocion['ENTRADA'] - $promocion['SALIDA'];
			echo "$('#tblMuestrasVisitasInst tbody').append('<tr><td>".$promocion['PRODUCTO']."<div class=\"col-blue font-13 display-flex\"><i class=\"material-icons font-16\">error_outline</i>Existencia: ".$existencia."</div></td><td>".$promocion['PRES']."</td><td>".$promocion['LOTE']."</td><td align=\"right\"><input  class=\"form-control input-number\" type=\"number\" size=\"5\" id=\"textInst".$numTexto."\" style=\"text-align:right\" onblur=\"validaCantidad(".$numTexto.",".$existencia.",".$promocion['quantity_allowed_visit'].");\" /><input type=\"hidden\" id=\"hdnIdProductoInst".$numTexto."\" value=\"".$promocion['ID_PRODUCTO']."\" /><input type=\"hidden\" id=\"hdnExistenciaInst".$numTexto."\" value=\"".$existencia."\" /><input type=\"hidden\" id=\"hdnMaximoInst".$numTexto."\" value=\"".$promocion['quantity_allowed_visit']."\" /><input type=\"hidden\" id=\"hdnTipoProductoInst".$numTexto."\" value=\"".$promocion['type']."\" /></td></tr>');";
			
			//echo "$('#tblMuestrasVisitasInst tbody').append('<tr><td onclick=\"existencia(".$existencia.");\">".$promocion['PRODUCTO']."</td><td onclick=\"existencia(".$existencia.");\">".$promocion['PRES']."</td><td onclick=\"existencia(".$existencia.");\">".$promocion['LOTE']."</td><td align=\"right\"><input type=\"text\" size=\"5\" id=\"textInst".$numTexto."\" style=\"text-align:right\" onblur=\"validaCantidad(".$numTexto.",".$existencia.",".$promocion['quantity_allowed_visit'].");\" /><input type=\"hidden\" id=\"hdnIdProductoInst".$numTexto."\" value=\"".$promocion['ID_PRODUCTO']."\" /><input type=\"hidden\" id=\"hdnExistenciaInst".$numTexto."\" value=\"".$existencia."\" /><input type=\"hidden\" id=\"hdnMaximoInst".$numTexto."\" value=\"".$promocion['quantity_allowed_visit']."\" /><input type=\"hidden\" id=\"hdnTipoProductoInst".$numTexto."\" value=\"".$promocion['type']."\" /></td></tr>');";

			$numTexto++;
		}
		echo "$('#hdnTotalPromocionesVisitasInst').val('".$numTexto."');
		$('.input-number').on('input', function () { 
			this.value = this.value.replace(/[^0-9]/g,'');
		});
		";
	}
	if($firma != ''){
		echo "$('#tblFirmaInst').hide();
			$('#imgFirmaInst').show();
			$('#imgFirmaInst').attr('src', 'data:image/png;base64,".$firma."');";
	}else{
		echo "$('#imgFirmaInst').hide();
		$('#tblFirmaInst').show();";
	}
	
	if($tipoInst == 2){
		echo "$('#liStock').show();";
		if($idVisita == ''){
			echo "$('#tblProductoSeleccionado tbody').empty();
				$('#tblProductosSeleccionados tbody').empty();";
		}
	}else{
		echo "$('#liStock').hide();";
	}
	
	echo "$('#liVisitaInst').click();
		$('#btnLimpiarFirmaInst').prop('disabled', false);
		$('#btnGuardarFirmaInst').prop('disabled', false);
	</script>";
?>