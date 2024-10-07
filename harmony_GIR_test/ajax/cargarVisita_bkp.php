<?php
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array(" ", " ", " ", " ");
	$reemplazar1=array("", "", "", "");
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
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
		//echo "idPlan: ".$idPlan."<br>";
		if(isset($_POST['pantalla']) && $_POST['pantalla'] == 'cal'){
			$pantalla = 'cal';
		}else{
			$pantalla = '';
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
		
		//echo "idUsuario: ".$idUser;
		
		if(isset($_POST['especialidad']) && $_POST['especialidad'] != ''){
			$especialidad = $_POST['especialidad'];
		}else{
			$especialidad = '';
		}
		
		if(isset($_POST['cicloActivo']) && $_POST['cicloActivo']){
			$cicloActivo = $_POST['cicloActivo'];
		}else{
			$cicloActivo = '';
		}
		
		if(isset($_POST['idVisita']) && $_POST['idVisita'] != '' && $_POST['idVisita'] != '00000000-0000-0000-0000-000000000000'){
			$idVisita = $_POST['idVisita'];
			$query = "select 
				p.LNAME + ' ' + p.MOTHERS_LNAME + ' / ' + p.FNAME as nombre,
				esp.NAME as especialidad,
				i.name as inst, i.STREET1 as calle,city.zip as cp, 
				city.name as col, d.NAME as del, state.NAME as estado,
				bri.name as brick,
				visit_date as Fecha_Vis,
				time,
				u.lname+' '+u.fname as Rep,
				codigo_vis.name as Tipo_Vis,
				vp.info as informacion_vis,
				vp.vispers_snr,
				vp.user_snr,
				vp.pers_snr,
				plw.inst_snr,
				vp.escort_snr as vis_acomp,
				prod.name as producto, 
				pf.name as presentacion, 
				vpB.quantity as cantidad, 
				firma.datastream as firma,
				(select top 1 info from vispersplan where vispersplan.pers_snr=vp.pers_snr and plan_date in (select max(plan_date) from vispersplan))  info_Ult_Plan, 
				lote.name as lote,
				vp.VISIT_CODE_SNR,
				vp.ESCORT_SNR,
				vp.INFO_NEXTVISIT,
				'' enfermedades_atiende,
				vp.novis_snr as motivoNoVisita 
				from visitpers vp
				inner join users u on u.user_snr = vp.USER_SNR
				left outer join codelist codigo_vis on codigo_vis.clist_snr=vp.visit_code_snr  
				left outer join visitpers_prodbatch vpb on vpb.vispers_snr = vp.vispers_snr and vpb.rec_stat=0 
				left outer join prodformbatch lote on lote.prodfbatch_snr = vpb.prodfbatch_snr 
				left outer join prodform pf on pf.prodform_snr = lote.prodform_snr 
				left outer join product prod on prod.prod_snr = pf.prod_snr 
				left outer join binarydata firma on firma.record_key = vp.vispers_snr and firma.rec_stat=0
				inner join person p on p.PERS_SNR = vp.PERS_SNR
				inner join CODELIST esp on esp.CLIST_SNR = p.SPEC_SNR
				inner join perslocwork plw on plw.pwork_snr=vp.pwork_snr
				inner join inst i on i.INST_SNR = plw.inst_snr
				inner join city on city.CITY_SNR = i.CITY_SNR
				inner join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
				inner join STATE on state.STATE_SNR = city.STATE_SNR
				left outer join BRICK bri on bri.BRICK_SNR = city.BRICK_SNR
				where vp.rec_stat=0 
				and vp.vispers_snr = '".$idVisita."'
				order by producto, presentacion";
		}elseif(isset($_POST['idPersona']) && $_POST['idPersona'] != ''){
			$idVisita = '';
			$idPersona = $_POST['idPersona'];
			$query = "select c.name as frecuencia, 
				p.lname + ' ' + p.MOTHERS_LNAME + ' ' + p.FNAME as nombre,
				esp.name as especialidad, 
				i.name as inst, i.STREET1 as calle,
				city.zip as cp, 
				city.name as col, 
				d.NAME as del, 
				state.NAME as estado,
				u.user_nr + ' - ' + u.LNAME + ' ' + u.MOTHERS_LNAME + ' ' + u.FNAME as repre, 
				bri.name as brick, 
				u.user_snr, 
				p.pers_snr, 
				'' enfermedades_atiende,
				(select top 1 INFO_NEXTVISIT from visitpers where visitpers.pers_snr=p.pers_snr and visit_date in (select max(visit_date) from visitpers where pers_snr = p.pers_snr))  obj_sig_vis 
				from person p
				inner join CODELIST c on p.FRECVIS_SNR = c.CLIST_SNR
				inner JOIN CODELIST ESP ON ESP.CLIST_SNR=P.SPEC_SNR
				inner join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR
				inner join inst i on i.INST_SNR = psw.INST_SNR
				inner join city on city.CITY_SNR = i.CITY_SNR
				inner join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
				inner join STATE on state.STATE_SNR = city.STATE_SNR
				inner join users u on u.user_snr = psw.USER_SNR
				left outer join BRICK bri on bri.BRICK_SNR = city.BRICK_SNR
				where p.pers_snr = '".$idPersona."'
				and psw.REC_STAT = 0 ";
				if($tipoUsuario == 4){
					$query .= "and u.user_snr = '".$idUser."'";
				}
				
		}
		/*echo "\n\n";
		echo $query;
		echo "\n\n";*/
		$rsVisita = sqlsrv_query($conn, $query);
		if($idVisita == ''){
			$visita = sqlsrv_fetch_array($rsVisita);
			if(isset($_POST["fechaVisita"]) && $_POST["fechaVisita"] != ''){
				$fecha_vis = $_POST["fechaVisita"];
			}else{
				$fecha_vis = date("Y-m-d");
			}
			$hora = date("H");
			$minutos = date("i");
			$tipoVisita = "";
			$infoVisita = str_ireplace($buscar,$reemplazar,utf8_encode($visita['obj_sig_vis']));
			$visAcom = "";
			$firma = "";
			$infoUltimoPlan = "";
			$visitCode = "2B3A7099-AC7D-47A3-A274-F0B029791801";
			//$visitAcom = array("CD1BD81F-AC66-4671-B2FF-CF1AF8F3C3CB");
			$visitAcom = array();
			$infoSigVisita = "";
			$rep = $visita['repre'];
			$idPers = $visita['pers_snr'];
			$persona = utf8_encode($visita['nombre']);
			$especialidad = $visita['especialidad'];
			$dir = utf8_encode($visita['inst'])." ".strtoupper('Calle: '.utf8_encode($visita['calle']).', C.P.: '.$visita['cp'].', Colonia: '.utf8_encode($visita['col']).', Población: '.utf8_encode($visita['del']).', Estado: '.utf8_encode($visita['estado']).'.');
			$brick = $visita['brick'];
			$idUsuario = $visita['user_snr'];
			$productos = array();
			$enfermedades_atiende = utf8_encode($visita['enfermedades_atiende']);	
			$motivoNoVisita = "00000000-0000-0000-0000-000000000000";
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
					$tipoVisita = $visita['Tipo_Vis'];
					$infoVisita = str_ireplace($buscar,$reemplazar,utf8_encode($visita['informacion_vis']));
					$idPers = $visita['pers_snr'];
					$visAcomp = $visita['vis_acomp'];
					$firma = $visita['firma'];
					$infoUltimoPlan = utf8_encode($visita['info_Ult_Plan']);
					$persona = utf8_encode($visita['nombre']);
					$especialidad = $visita['especialidad'];
					$dir = utf8_encode($visita['inst'])." ".strtoupper('Calle: '.utf8_encode($visita['calle']).', C.P.: '.$visita['cp'].', Colonia: '.utf8_encode($visita['col']).', Población: '.utf8_encode($visita['del']).', Estado: '.utf8_encode($visita['estado']).'.');
					$brick = $visita['brick'];
					$visitCode = $visita['VISIT_CODE_SNR'];
					//$visita['PRATNJA_USER_SNR'];
					$visitAcom = explode(";", $visita['ESCORT_SNR']);
					$infoSigVisita = str_ireplace($buscar,$reemplazar,utf8_encode($visita['INFO_NEXTVISIT']));
					$idUsuario = $visita['user_snr'];
					$enfermedades_atiende = utf8_encode($visita['enfermedades_atiende']);
					$motivoNoVisita = $visita['motivoNoVisita'];
					if($visita['producto'] != ''){
						$producto = array('producto' => $visita['producto'],
							'presentacion' => $visita['presentacion'],
							'cantidad' => $visita['cantidad'],
							'lote' => $visita['lote']);
						$productos[] = $producto;
					}
				}else{
					$producto = array('producto' => $visita['producto'],
							'presentacion' => $visita['presentacion'],
							'cantidad' => $visita['cantidad'],
							'lote' => $visita['lote']);
					$productos[] = $producto;
				}
				$contador++;
			}
			//print_r($visitAcom);
		}
		//echo "id: ".$infoVisita."<br>";
		echo "<script>
			$('#hdnIdPersona').val('".$idPers."');
			$('#hdnIdVisita').val('".$idVisita."');
			$('#hdnPantallaVisitas').val('".$pantalla."');
			$('#btnGuardarVisitas').prop('disabled', false);
			$('#lblPersonaVisita').text('".$persona."');
			$('#lblEspecialidadVisita').text('".$especialidad."');
			$('#lblDireccionVisita').text('".$dir."');
			$('#lblBrickVisita').text('".$brick."');
			$('#sltRepreVisita').empty();
			$('#txtFechaVisita').val('".$fecha_vis."');
			$('#lstHoraVisita').val('".$hora."');
			$('#lstMinutosVisita').val('".$minutos."');
			$('#txtComentariosVisita').val('".$infoVisita."');
			$('#lstCodigoVisita').val('".$visitCode."');
			$('#lstMotivoNoVisita').val('".$motivoNoVisita."');
			$('#txtInfoSiguienteVisita').val('".$infoSigVisita."');
			$('#txtComentariosMedico').val('".$enfermedades_atiende."');
			$('#tblProductosVisitas tbody').empty();
			$('#tblMuestras tbody').empty();
			limpiar();
			limpiarChecksVisitaAcompa('personas');
			$('#sltMultiSelect').text('Selecciona');
			";
			
		if($visitCode == '73253003-55D7-4B25-929F-0F4A452E6F6B'){//no visita
			echo '$("#lstMotivoNoVisita").attr("disabled", false);';
		}else{
			echo '$("#lstMotivoNoVisita").attr("disabled", true);';
		}
			
		if($tipoUsuario == 4){
			echo "$('#sltRepreVisita').append('<option value=\"".$idUsuario."\" selected=\"selected\">".$rep."</option>');";
		}else{
			$regSuper = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from users where user_snr = '".$idUser."'"));
			$repre = $visita['user_snr'];
			if($tipoUsuario == 2){
				echo "$('#sltRepreVisita').append('<option value=\"".$visita['user_snr']."\" >".$visita['repre']."</option>');";
			}else{
				echo "$('#sltRepreVisita').append('<option value=\"".$visita['user_snr']."\" >".$visita['repre']."</option>');
				$('#sltRepreVisita').append('<option value=\"".$regSuper['USER_SNR']."\" selected>".$regSuper['LNAME']." ".$regSuper['FNAME']."</option>');
				";
			}
		}
		
		if($idVisita == ''){
			$queryProductos = "select * from product where REC_STAT = 0 order by name";
			$rsProdcutos = sqlsrv_query($conn, $queryProductos);
			$renglon = '<tr class="align-center"><td style="vertical-align:middle;">1</td><td><div class="select"><select id="lstProducto1" class="form-control" onchange="llenaSiguiente(1);">';
			while($producto = sqlsrv_fetch_array($rsProdcutos)){
				$renglon .=  '<option value="'.$producto['PROD_SNR'].'">'.$producto['NAME'].'</option>';
			}
			$renglon .= '</select><div class="select_arrow"></div></div></td>';
			$renglon .= '<td><div class="select"><select id="lstEstrategia1" class="form-control">';
			$renglon .= '<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>';
			$rsEstrategia = llenaCombo($conn, 255, 1);
			while($estrategia = sqlsrv_fetch_array($rsEstrategia)){
				$renglon .= '<option value="'.$estrategia['id'].'">'.$estrategia['nombre'].'</option>';
			}
			$renglon .= '</select><div class="select_arrow"></div></div></td>';
			$renglon .= '<td><input class="form-control" type="text" id="txtProdPos1" value="" size="3" readonly /></td></tr>';
			echo "$('#tblProductosVisitas tbody').append('".$renglon."');";
			for($i=2;$i<11;$i++){
				$rsEstrategia = llenaCombo($conn, 255, 1);
				$renglon = '<tr class="align-center"><td style="vertical-align:middle;">'.$i.'</td><td><div class="select"><select class="form-control" id="lstProducto'.$i.'" onchange="llenaSiguiente('.$i.');"><option value="00000000-0000-0000-0000-000000000000">Seleccione</option></select><div class="select_arrow"></div></div></td><td><div class="select"><select class="form-control" id="lstEstrategia'.$i.'"><option>Seleccione</option>';
				while($estrategia = sqlsrv_fetch_array($rsEstrategia)){
					$renglon .= '<option value="'.$estrategia['id'].'">'.$estrategia['nombre'].'</option>';
				}
				$renglon .= '</select><div class="select_arrow"></div></div></td><td><input class="form-control" type="text" id="txtProdPos'.$i.'" value="" size="3" readonly /></td></tr>';
				echo "$('#tblProductosVisitas tbody').append('".$renglon."');";
			}
			/* productos pre cargados */
			
			/* revisamos si esta habilitada la opción de pre-cargado */
			$promotion_enabled = sqlsrv_fetch_array(sqlsrv_query($conn, "select mplan.promotion_enabled 
				from MARKETING_PLAN mplan
				inner join cycles cycle on cycle.CYCLE_SNR=mplan.CYCLE_SNR
				inner join marketing_plan_specialty mplan_spec on mplan_spec.mplan_snr=mplan.mplan_snr and mplan_spec.rec_stat=0
				inner join CODELIST especialidad on especialidad.CLIST_SNR = mplan_spec.SPEC_SNR 
				inner join MARKETING_PLAN_USER mpu on mpu.MPLAN_SNR = mplan.MPLAN_SNR
				where mplan.REC_STAT = 0
				and substring(cycle.name,1,7)='".$cicloActivo."'
				and especialidad.name='".$especialidad."' and mplan.status = 1
				and mpu.USER_SNR = '".$idUsuario."'"))['promotion_enabled'];
				
			for($i=1;$i<11;$i++){
				echo "$('#tblProductosVisitas tbody').append('<tr class=\"align-center\"><td>".$i."</td><td><select class=\"form-control\" id=\"lstProducto".$i."\" onchange=\"llenaSiguiente(".$i.");\"><option>Seleccione</option></select></td><td><input class=\"form-control\" id=\"txtProdPos".$i."\" type=\"text\" value=\"\" size=\"3\" /></td></tr>');";
				echo "$('#lstProducto".$i."').empty();
					$('#txtProdPos".$i."').val('');";
			}
			
			if($promotion_enabled == 1){
				$qProductos = "select
					mplan.promotion_enabled,
					specialty.name as specialty,
					prod.prod_snr as product_Id,
					prod.name as product_name,
					cast(mplan_spec_prod.percentaje * 100 as int) as percentage
					from marketing_plan mplan
					inner join marketing_plan_specialty mplan_spec on mplan_spec.mplan_snr=mplan.mplan_snr and mplan_spec.rec_stat=0
					inner join marketing_plan_spec_prod mplan_spec_prod on mplan_spec.mplanspec_snr=mplan_spec_prod.mplanspec_snr and mplan_spec_prod.rec_stat=0
					inner join codelist specialty on specialty.clist_snr=mplan_spec.spec_snr
					inner join product prod on prod.prod_snr=mplan_spec_prod.prod_snr
					inner join cycles cycle on cycle.cycle_snr=mplan.cycle_snr 
					inner join MARKETING_PLAN_USER mpu on mpu.MPLAN_SNR = mplan.MPLAN_SNR 
					where mplan.rec_stat=0
					and substring(cycle.name,1,7)='".$cicloActivo."'
					and specialty.name='".$especialidad."' 
					and mpu.USER_SNR = '".$idUsuario."' 
					order by specialty.name,mplan_spec_prod.percentaje desc ";
				
				echo $qProductos;
			
			//echo "<br><br>tipoUsuario: ".$tipoUsuario."<br><br>";
			if($tipoUsuario == 4){
				$qProductos = getPersonVisProdMP($idPers, $idUser, $conn);
			}else{
				$qProductos = getPersonVisProdMP($idPers, $repre, $conn);
			}
			
			
			$rsProdcutos = sqlsrv_query($conn, $qProductos);
			$combo = 1;
			$notIn = array();
			while($prodPos = sqlsrv_fetch_array($rsProdcutos)){
				echo "$('#lstProducto".$combo."').empty();
					$('#txtProdPos".$combo."').val('');";
				$queryProductos = sqlsrv_query($conn, "select * from product where REC_STAT = 0 order by name");
				while($prod = sqlsrv_fetch_array($queryProductos)){
					if(! in_array($prod['PROD_SNR'], $notIn)){
						//echo $prod['PROD_SNR']." == ".$prodPos['product_Id'];
						if($prod['PROD_SNR'] == $prodPos['product_Id']){
							echo "$('#lstProducto".$combo."').append('<option value=\"".$prod['PROD_SNR']."\">".$prod['NAME']."</option>');
								$('#lstProducto".$combo."').val('".$prod['PROD_SNR']."');
								$('#lstEstrategia".$combo."').val('".$prodPos['strategy_Id']."');
								$('#txtProdPos".$combo."').val('".$prodPos['percentage']."');";
							$notIn[] = $prod['PROD_SNR'];
						}else{
							echo "$('#lstProducto".$combo."').append('<option value=\"".$prod['PROD_SNR']."\">".$prod['NAME']."</option>');";
						}
					}
				}
				$combo++;
			}
				
			if ($combo == 1){
				$queryProductos = "select PROD_SNR, NAME from product where REC_STAT = 0 and status = 1 order by name";
				$rsProdcutos = sqlsrv_query($conn, $queryProductos);
				echo "$('#lstProducto1').append('<option value=\"00000000-0000-0000-0000-000000000000\">Seleccione</option>');";
				while($producto = sqlsrv_fetch_array($rsProdcutos)){
					echo "$('#lstProducto1').append('<option value=\"".$producto['PROD_SNR']."\">".$producto['NAME']."</option>');";
				}
			}
			$rsAcom = llenaCombo($conn, 33, 142);
			$check = 1;
			$contadorChecks = 0;
			while($regAcom = sqlsrv_fetch_array($rsAcom)){
				echo "$('#acompa".$check."').prop('checked', false);";
				$check++;
				$contadorChecks++;
			}
			echo "$('#hdnTotalChecksVisitas').val('".$contadorChecks."');
				$('#lstMotivoNoVisita').attr('disabled', true);";
		}else{
			/*visita acompañada*/
/*			$rsAcom = llenaCombo($conn, 33, 142);
			$check = 1;
			$contadorChecks = 0;
			$descripcionesCheck = '';
			while($regAcom = sqlsrv_fetch_array($rsAcom)){
				if(in_array($regAcom['id'], $visitAcom)){
					echo "$('#acompa".$check."').prop('checked', true);";
					$descripcionesCheck .= $regAcom['nombre'].", ";
				}else{
					echo "$('#acompa".$check."').prop('checked', false);";
				}
				$check++;
				$contadorChecks++;
			}
			echo "$('#hdnTotalChecksVisitas').val('".$contadorChecks."');
				$('#hdnDescripcionChkVisitas').val('".$descripcionesCheck."');
				$('#sltMultiSelect').text('".$descripcionesCheck."');";
*/
			//// fin visita acompañada
			
			$queryProductos = "select p.prod_snr as idProducto, 
				p.name as producto, 
				vp.position as posicion, 
				estrategia.name as estrategia,
				vp.percentage as porcentaje 
				from visitpers_Prod vp  
				inner join product p on vp.prod_snr = p.prod_snr
				left outer join CODELIST estrategia on estrategia.CLIST_SNR = vp.STRATEGY_SNR
				where vp.vispers_snr = '".$idVisita."'  
				and vp.rec_stat = 0 
				order by posicion";
			
			$rsProdcutos = sqlsrv_query($conn, $queryProductos, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsProdcutos)>0){
				$cuentaProducto = 1;
				while($producto = sqlsrv_fetch_array($rsProdcutos)){
					echo "$('#tblProductosVisitas tbody').append('<tr class=\"align-center\"><td>".$cuentaProducto."</td><td><div class=\"select\"><select class=\"form-control\"><option>".$producto['producto']."</option></select><div class=\"select_arrow\"></div></div></td><td><div class=\"select\"><select  class=\"form-control\"><option>".$producto['estrategia']."</option></select><div class=\"select_arrow\"></div></div></td><td><input class=\"form-control\" type=\"text\" value=\"".$producto['porcentaje']."\" size=\"3\" readonly /></td></tr>');";
					$cuentaProducto++;
				}
				for($i=$cuentaProducto;$i<11;$i++){
					echo "$('#tblProductosVisitas tbody').append('<tr class=\"align-center\"><td>".$i."</td><td><div class=\"select\"><select class=\"form-control\"><option>Seleccione</option></select><div class=\"select_arrow\"></div></div></td><td><div class=\"select\"><select class=\"form-control\"><option>Seleccione</option></select><div class=\"select_arrow\"></div></div></td><td><input class=\"form-control\" type=\"text\" value=\"\" size=\"3\" readonly /></td></tr>');";
				}
			}else{
				$queryProductos = "select * from product where REC_STAT = 0 order by name";
				$rsProdcutos = sqlsrv_query($conn, $queryProductos);
				$renglon = '<tr class="align-center"><td style="vertical-align:middle;">1</td><td><div class="select"><select id="lstProducto1" class="form-control" onchange="llenaSiguiente(1);">';
				//$renglon .= '<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>';
				while($producto = sqlsrv_fetch_array($rsProdcutos)){
					$renglon .= '<option value="'.$producto['PROD_SNR'].'">'.$producto['NAME'].'</option>';
				}
				$renglon .= '</select><div class="select_arrow"></div></div></td>';
				$renglon .= '<td><div class="select"><select id="lstEstrategia1" class="form-control">';
				$rsEstrategia = llenaCombo($conn, 255, 1);
				while($estrategia = sqlsrv_fetch_array($rsEstrategia)){
					$renglon .= '<option value="'.$estrategia['id'].'">'.$estrategia['nombre'].'</option>';
				}
				$renglon .= '</select><div class="select_arrow"></div></div></td>';
				$renglon .= '<td><input class="form-control" id="txtProdPos1" type="text" value="" size="3" /></td></tr>';
				echo "$('#tblProductosVisitas tbody').append('".$renglon."');";
				for($i=2;$i<11;$i++){
					echo "$('#tblProductosVisitas tbody').append('<tr class=\"align-center\"><td>".$i."</td><td><select class=\"form-control\" id=\"lstProducto".$i."\" onchange=\"llenaSiguiente(".$i.");\"><option>Seleccione</option></select></td><td><select class=\"form-control\" id=\"lstEstrategia".$i."\"><option>Seleccione</option></select></td><td><input class=\"form-control\" id=\"txtProdPos".$i."\" type=\"text\" value=\"\" size=\"3\" /></td></tr>');";
				}
			}
		}
		
		/*visita acompañada*/
		$rsAcom = sqlsrv_query($conn, "select clist_snr as id, name as nombre from codelist where CLIb_SNR = (select  clib_snr from codelistlib where table_nr = 33 and LIST_NR = 142) and status = 1 order by sort_num, name");
		$check = 1;
		$contadorChecks = 0;
		$descripcionesCheck = '';
		while($regAcom = sqlsrv_fetch_array($rsAcom)){
			if(in_array($regAcom['id'], $visitAcom)){
				echo "$('#acompa".$check."').prop('checked', true);";
				$descripcionesCheck .= utf8_encode($regAcom['nombre'])."; ";
			}else{
				echo "$('#acompa".$check."').prop('checked', false);";
			}
			$check++;
			$contadorChecks++;
		}
		echo "$('#hdnTotalChecksVisitas').val('".$contadorChecks."');
			$('#hdnDescripcionChkVisitas').val('".$descripcionesCheck."');
			$('#sltMultiSelect').text('".$descripcionesCheck."');";

		//// fin visita acompañada
		
		if(count($productos)>0){
			for($i=0;$i<count($productos);$i++){
				echo "$('#tblMuestras tbody').append('<tr><td>".$productos[$i]['producto']."</td><td>".$productos[$i]['presentacion']."</td><td>".$productos[$i]['lote']."</td><td align=\'center\'>".$productos[$i]['cantidad']."</td>');";
			}
			$numTexto = 1;
		}else{
				$queryPromociones = "select * from (
					SELECT LOTE.PRODFBATCH_SNR as ID_PRODUCTO,PROD.NAME AS PRODUCTO,PF.NAME PRES,LOTE.NAME AS LOTE,PF.QUANTITY_ALLOWED_VISIT,PF.type,
					(select sum(a.quantity) 
					from stock_prodform_user a 
					where quantity>0 
					and a.rec_stat = 0 ";
					if($tipoUsuario == 4){
						$queryPromociones .= "and a.user_snr='".$idUsuario."' ";
					}else{
						$queryPromociones .= "and a.user_snr='".$idUser."' ";
					}
					$queryPromociones .= "and lote.prodfbatch_snr = a.prodfbatch_snr 
					and a.accepted=1 group by a.user_snr,a.prodfbatch_snr) as ENTRADA,
					isnull((select sum(quantity) 
					from visitpers_prodbatch, visitpers 
					where visitpers_prodbatch.vispers_snr=visitpers.vispers_snr 
					and visitpers_prodbatch.prodfbatch_snr=lote.prodfbatch_snr 
					and lote.REC_STAT=0 
					and prod.REC_STAT=0 
					and pf.REC_STAT=0 ";
					if($tipoUsuario == 4){
						$queryPromociones .= "and visitpers.user_snr='".$idUsuario."' ";
					}else{
						$queryPromociones .= "and visitpers.user_snr='".$idUser."' ";
					}
					$queryPromociones .= "and visitpers.rec_stat=0 and visitpers_prodbatch.rec_stat=0 ),0) as SALIDA, 
					isnull((select sum(quantity) 
					from visitinst_prodbatch vprod, visitinst v 
					where vprod.visinst_snr=v.visinst_snr 
					and vprod.prodfbatch_snr=lote.prodfbatch_snr ";
					if($tipoUsuario == 4){
						$queryPromociones .= "and v.user_snr='".$idUsuario."' ";
					}else{
						$queryPromociones .= "and v.user_snr='".$idUser."' ";
					}
					$queryPromociones .= "and v.rec_stat=0 and vprod.rec_stat=0),0) as SALIDA_INST,
					isnull((select quantity from Marketing_Plan mplan  
					inner join Marketing_Plan_specialty mplan_esp on mplan_esp.mplan_snr=mplan.mplan_snr and mplan_esp.rec_stat=0 
					inner join Marketing_Plan_Spec_Prod mplan_esp_prod on mplan_esp.mplanspec_snr=mplan_esp_prod.mplanspec_snr and mplan_esp_prod.rec_stat=0 
					inner join Marketing_Plan_prod_Batch mplan_prod_lote on mplan_prod_lote.mplanspecprod_snr=mplan_esp_prod.mplanspecprod_snr and mplan_prod_lote.rec_stat=0 and mplan_prod_lote.prodfbatch_snr=lote.prodfbatch_snr 
					inner join codelist esp on esp.clist_snr=mplan_esp.spec_snr 
					inner join product prod on prod.prod_snr=mplan_esp_prod.prod_snr 
					inner join cycles ciclo on ciclo.cycle_snr=mplan.cycle_snr 
					inner join MARKETING_PLAN_USER mpu on mpu.MPLAN_SNR = mplan.MPLAN_SNR
					where mplan.rec_stat=0 and ciclo.name = '".$cicloActivo."' and esp.name='".$especialidad."' ";
					if($tipoUsuario == 4){
						$queryPromociones .= "and mpu.user_snr='".$idUsuario."' ";
					}else{
						$queryPromociones .= "and mpu.user_snr='".$idUser."' ";
					}
					$queryPromociones .= " ),0) CANT_MPLAN 
					FROM PRODFORM PF 
					INNER JOIN PRODUCT PROD ON PF.PROD_SNR=PROD.PROD_SNR 
					INNER JOIN PRODFORMBATCH LOTE ON LOTE.PRODFORM_SNR=PF.PRODFORM_SNR ) a
					WHERE a.ENTRADA>0 
					AND (a.ENTRADA-(a.SALIDA+a.SALIDA_INST))>0 
					ORDER BY a.PRODUCTO,a.PRES,a.LOTE ";

			//echo "<br>".$queryPromociones."<br>";
			$rsQueryPromociones = sqlsrv_query($conn, $queryPromociones);
			$numTexto = 1;
			while($promocion = sqlsrv_fetch_array($rsQueryPromociones)){
				$existencia = $promocion['ENTRADA'] - $promocion['SALIDA'];
				echo "$('#tblMuestras tbody').append('<tr><td>".$promocion['PRODUCTO']."<div class=\"col-blue font-13 display-flex\"><i class=\"material-icons font-16\">error_outline</i>Existencia: ".$existencia."</div></td><td>".$promocion['PRES']."</td><td >".$promocion['LOTE']."</td><td align=\"right\"><input class=\"form-control input-number\" type=\"number\" size=\"5\" id=\"text".$numTexto."\" style=\"text-align:right\" onblur=\"validaCantidad(".$numTexto.",".$existencia.",".$promocion['QUANTITY_ALLOWED_VISIT'].");\" /><input class=\"form-control\" type=\"hidden\" id=\"hdnIdProducto".$numTexto."\" value=\"".$promocion['ID_PRODUCTO']."\" /><input type=\"hidden\" id=\"hdnExistencia".$numTexto."\" value=\"".$existencia."\" /><input type=\"hidden\" id=\"hdnMaximo".$numTexto."\" value=\"".$promocion['QUANTITY_ALLOWED_VISIT']."\" /><input type=\"hidden\" id=\"hdnTipoProducto".$numTexto."\" value=\"".$promocion['type']."\" /></td></tr>');";
				$numTexto++;
			}
			echo "$('#hdnTotalPromociones').val('".$numTexto."');
			$('.input-number').on('input', function () { 
				this.value = this.value.replace(/[^0-9]/g,'');
			});
			";
		}
		if($firma != ''){
			echo "
				$('#tblFirma').hide();
				$('#imgFirma').show();
				$('#imgFirma').attr('src', 'data:image/png;base64,".str_ireplace($buscar,$reemplazar1,str_replace(" ","+",trim($firma)))."');
				$('#f64').val('".str_ireplace($buscar,$reemplazar,trim($firma))."');";
		}else{
			echo "
			$('#imgFirma').hide();
			$('#tblFirma').show();
			";
		}
		echo "$('#tabVisitaPersona').click();
			$('#btnLimpiarFirma').prop('disabled', false);
			$('#btnGuardarFirma').prop('disabled', false);
			$('#btnLimpiarFirma').click();
		</script>";
	}
	//echo $queryPromociones;
	//
?>