<?php
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r", "\t");
	$reemplazar=array(" ", " ", " ", " "," ");
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		$id = $_POST['id'];
	
		$tipoUsuario = $_POST['tipoUsuario'];
		$idUsuario = $_POST['idUsuario'];

		$queryPersona = "select p.LNAME as paterno, 
			categ.name as categoria, 
			year(GETDATE()) -  year(BIRTHDATE) as edad, 
			p.mothers_lname as materno, 
			p.prof_id as cedula, 
			p.FNAME as nombre, 
			freq.NAME as frecuencia, 
			i.NAME as institucion, 
			i.STREET1 +' '+ city.name +' '+ d.NAME +' '+state.NAME as direccion, 
			dif.name as difVisita,
			brick.name as brick_name, 
			sexo.NAME as sexo,
			esp.NAME as especialidad,
			subesp.NAME as subespecialidad,
			u.LNAME + ' ' + u.mothers_lname + ' ' + u.fname as repre, 
			(select top 1 VISIT_DATE from visitpers where PERS_SNR = p.PERS_SNR and REC_STAT = 0 order by VISIT_DATE desc ) as ultimaVisita,
			u.user_snr, plw.tower, plw.floor, plw.office,
			(SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
			AND '".date("Y-m-d")."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE ";
		if($tipoUsuario == 4){
			$queryPersona .= "and vp.user_snr = '".$idUsuario."') as visitas, ";
		}else{
			$queryPersona .= "and vp.user_snr = '".$id."') as visitas, ";
		}
		$queryPersona .= "plw.LATITUDE, plw.LONGITUDE, 
			p.info as generales,p.info_shorttime as corto, 
			p.info_longtime as largo,
			patxsem.name as pacientesxsemana, hon.name  as honorarios, pud.*, 
			prod1.NAME as prod1, prod2.NAME as prod2, prod3.NAME as prod3, prod4.NAME as prod4, prod5.NAME as prod5,
			div.name as divMed, lider.name lider, para.name as paraestatales, estatus.name as STATUS, 
			flonor.name as SegmentacionFlonorm,
			vessel.name as SegmentacionVessel, zifros.name as SegmentacionZirfos, 
			ateka.name as SegmentacionAteka,
			resp1.name as SegmentacionGanar, resp2.name as SegmentacionDesarrollar, 
			resp3.name as SegmentacionDefende, resp4.name as SegmentacioEval,
			p.spec_snr, p.category_snr,
			field12.name as field12,
			field13.name as field13,
			field14.name as field14, field15.name as field15, field16.name as field16,
			field17.name as field17, field18.name as field18,
			p.rec_stat 
			from person p
			left outer join CODELIST categ on p.category_snr = categ.CLIST_SNR
			left outer join CODELIST freq on p.frecvis_snr = freq.CLIST_SNR
			left outer join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR and psw.REC_STAT = 0 
			left outer join inst i on i.INST_SNR = psw.inst_snr
			left outer join city on city.CITY_SNR = i.CITY_SNR
			left outer join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
			left outer join STATE on state.STATE_SNR = city.STATE_SNR
			left outer join BRICK brick on brick.brick_snr = city.brick_snr
			left outer JOIN CODELIST SEXO ON SEXO.CLIST_SNR=P.SEX_SNR
			left outer JOIN CODELIST ESP ON ESP.CLIST_SNR=P.SPEC_SNR
			left outer JOIN CODELIST SUBESP ON SUBESP.CLIST_SNR=P.SUBSPEC_SNR
			left outer join PERSLOCWORK plw on p.PERS_SNR = plw.PERS_SNR and plw.REC_STAT = 0
			left outer join users u on u.user_snr = psw.USER_SNR
			left outer join CODELIST dif on dif.CLIST_SNR = p.diffvis_snr
			left outer join CODELIST patxsem on patxsem.CLIST_SNR = p.patperweek_snr
			left outer join CODELIST hon on hon.CLIST_SNR = p.FEE_TYPE_SNR
			left outer join person_ud pud on pud.PERS_SNR = p.PERS_SNR
			left outer join CODELIST prod1 on prod1.CLIST_SNR = pud.Prod1What_SNR
			left outer join CODELIST prod2 on prod2.CLIST_SNR = pud.Prod2What_SNR
			left outer join CODELIST prod3 on prod3.CLIST_SNR = pud.Prod3What_SNR
			left outer join CODELIST prod4 on prod4.CLIST_SNR = pud.Prod4What_SNR
			left outer join CODELIST prod5 on prod5.CLIST_SNR = pud.Prod5What_SNR
			left outer join CODELIST estatus on estatus.CLIST_SNR = p.STATUS_SNR
			left outer join CODELIST div on div.CLIST_SNR = pud.field_01_snr
			left outer join CODELIST lider on lider.CLIST_SNR = pud.field_02_snr
			left outer join CODELIST para on para.CLIST_SNR = pud.field_03_snr
			left outer join CODELIST flonor on flonor.CLIST_SNR = pud.field_04_snr
			left outer join CODELIST vessel on vessel.CLIST_SNR = pud.field_05_snr
			left outer join CODELIST zifros on zifros.CLIST_SNR = pud.field_06_snr
			left outer join CODELIST ateka on ateka.CLIST_SNR = pud.field_07_snr
			left outer join CODELIST resp1 on resp1.CLIST_SNR = pud.field_08_snr
			left outer join CODELIST resp2 on resp2.CLIST_SNR = pud.field_09_snr
			left outer join CODELIST resp3 on resp3.CLIST_SNR = pud.field_10_snr
			left outer join CODELIST resp4 on resp4.CLIST_SNR = pud.field_11_snr
			left outer join CODELIST field12 on field12.CLIST_SNR = pud.field_12_snr
			left outer join CODELIST field13 on field13.CLIST_SNR = pud.field_13_snr
			left outer join CODELIST field14 on field14.CLIST_SNR = pud.field_14_snr
			left outer join CODELIST field15 on field15.CLIST_SNR = pud.field_15_snr
			left outer join CODELIST field16 on field16.CLIST_SNR = pud.field_16_snr
			left outer join CODELIST field17 on field17.CLIST_SNR = pud.field_17_snr
			left outer join CODELIST field18 on field18.CLIST_SNR = pud.field_18_snr
			where p.pers_snr = '".$id."'";

		//echo $queryPersona."<br><br>";

		$reg = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPersona));

		if($reg['rec_stat'] != 0){
			echo "<script>
				$('#divMedicosInactivos').show();
				$('#cardInfMedicos').hide();
				$('#divMedicosInactivos').waitMe('hide');
			</script>";
		}else{
			/* nuevo calculo de frecuencia */	
			$qfrecuencia = "select frec.name as frec
				from cycle_pers_categ_spec cpcs 
				inner join cycles c on c.cycle_snr = cpcs.cycle_snr 
				inner join CODELIST frec on frec.CLIST_SNR = cpcs.frecvis_snr
				where cpcs.rec_stat = 0 
				and '".date("Y-m-d")."' between c.start_date and c.finish_date 
				and spec_snr = '".$reg['spec_snr']."' 
				and category_snr = '".$reg['category_snr']."' ";
			
			//echo $qfrecuencia."<br><br><br>";
			
			$reg['frecuencia'] = sqlsrv_fetch_array(sqlsrv_query($conn, $qfrecuencia))['frec'];

			$queryPersona2 = "select p.LNAME as paterno, 
				categ.name as categoria, 
				p.mothers_lname as materno, 
				p.prof_id as cedula, 
				p.FNAME as nombre, 
				freq.NAME as frecuencia, 
				i.STREET1 +' '+ city.name +' '+ d.NAME +' '+state.NAME as direccion, 
				brick.name as brick_name,
				subesp.NAME as subespecialidad,
				u.LNAME + ' ' + u.mothers_lname + ' ' + u.fname as repre,
				u.user_snr, plw.tower, plw.floor, plw.office";
			
			$queryPersona2 .= " from person p
				left outer join CODELIST categ on p.category_snr = categ.CLIST_SNR
				left outer join CODELIST freq on p.frecvis_snr = freq.CLIST_SNR
				left outer join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR
				left outer join inst i on i.INST_SNR = psw.inst_snr
				left outer join city on city.CITY_SNR = i.CITY_SNR
				left outer join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
				left outer join STATE on state.STATE_SNR = city.STATE_SNR
				left outer join BRICK brick on brick.brick_snr = city.brick_snr
				left outer JOIN CODELIST SUBESP ON SUBESP.CLIST_SNR=P.SUBSPEC_SNR
				left outer join PERSLOCWORK plw on p.PERS_SNR = plw.PERS_SNR
				left outer join users u on u.user_snr = psw.USER_SNR
				where p.pers_snr = '".$id."'";

				//echo $queryPersona2."<br>";
				
				 

			$reg2 = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPersona2));
			
			$ultimaVisita = '';
			if(is_object($reg['ultimaVisita'])){
				foreach ($reg['ultimaVisita'] as $key => $val) {
					if(strtolower($key) == 'date'){
						$ultimaVisita = substr($val, 0, 10);
					}
				}
			}
			if($ultimaVisita){
				$date1 = new DateTime(date("Y-m-d"));
				$date2 = new DateTime($ultimaVisita);
				$diff = $date1->diff($date2);
				$diasSinVisitar = $diff->days;
			}else{
				$diasSinVisitar = '';
			}
			
			//echo $queryPersona2;
			echo "<script>
					$('#lblDireccion2').text('".$reg2['direccion']."');
					$('#lblCategoria2').text('".$reg2['categoria']."');
					$('#lblCedula2').text('".$reg2['cedula']."');
					$('#lblBrick2').text('".$reg2['brick_name']."');
					$('#lblRepresentante2').text('".$reg2['repre']."');
					$('#lblConsultorio2').text('".$reg2['office']."');
					$('#lblCategoriaAudit').text('".$reg['field12']."');
					$('#lblDiasSinVisitar').text('".$diasSinVisitar."');
					$('#divMedicosInactivos').waitMe('hide');
				</script>";
			
			//echo "dias sin visitar: ".$diasSinVisitar;
			
			/**********planes********/
			$queryPlanes = "select plan_date as Fecha_Plan, vp.time as Hora_Plan, u.user_nr, u.lname +' '+ u.fname as Rep, cd.name as Tipo_Plan, 
				vp.info as Objetivo, vp.vispersplan_snr, vp.user_snr, vp.pers_snr, 
				(select top 1 info from visitpers where visitpers.pers_snr=vp.pers_snr and creation_timestamp in (select max(creation_timestamp) from visitpers)) as info_Ult_Vis,
				c.name
				from vispersplan vp, users u, codelist cd, CYCLES c 
				where cd.clist_snr=vp.plan_code_snr
				and vp.user_snr=u.user_snr 
				and vp.rec_stat=0 ";
				if($tipoUsuario == 4){
					$queryPlanes .= "and vp.user_snr = '".$reg['user_snr']."' ";
				}else{
					$queryPlanes .= "and vp.user_snr in ('".$reg['user_snr']."','".$idUsuario."') ";
				}
				$queryPlanes .= "and vp.pers_snr='".$id."'
				and vp.plan_date between START_DATE and FINISH_DATE
				order by fecha_plan desc";
			
			//echo $queryPlanes."<br><br><br>";
			
			$rsPlanes = sqlsrv_query($conn, $queryPlanes);
				
			$queryCiclos = "select  substring(ciclos.name,1,4) anio, 
					substring(ciclos.name,6,2) ciclo, 
					count(*) total 
					from vispersplan vp, cycles ciclos 
					where vp.plan_date between ciclos.start_date and ciclos.finish_date 
					and vp.rec_stat=0 ";
					if($tipoUsuario == 4){
						$queryCiclos .= "and vp.user_snr = '".$reg['user_snr']."' ";
					}else{
						$queryCiclos .= "and vp.user_snr in ('".$reg['user_snr']."','".$idUsuario."') ";
					}
					$queryCiclos .= "and vp.pers_snr='".$id."' 
					and substring(ciclos.name,1,4) = (select max(substring(name,1,4)) from cycles) 
					group by ciclos.name";
					
			$rsCiclos = sqlsrv_query($conn, $queryCiclos);
		
			//echo "<br><br>";
			//echo $queryCiclos."<br><br><br>";
			
			/***************** fin planes ***********************/
			
			/******************visitas**************************/
			$queryVisitas = "select  visit_date as Fecha_Vis,
				time,u.lname + ' ' + u.fname as Rep, u.user_nr,
				codigo_vis.name as Tipo_Vis,
				vp.info as informacion_vis,
				vp.info_nextvisit as obj_vis,
				vp.vispers_snr,vp.user_snr,
				vp.pers_snr,
				vp.pwork_snr,
				vp.vispersplan_snr as idPlan, cycles.name as ciclo
				from visitpers vp
				inner join users u on u.USER_SNR = vp.USER_SNR
				inner join codelist codigo_vis on codigo_vis.clist_snr=vp.visit_code_snr 
				left outer join binarydata firma on firma.record_key = vp.vispers_snr 
				left outer join cycles on vp.VISIT_DATE between cycles.start_date and cycles.FINISH_DATE 
				where vp.rec_stat=0 ";
				if($tipoUsuario == 4){
					$queryVisitas .= "and  vp.user_snr = '".$reg['user_snr']."' ";
				}else{
					$queryVisitas .= "and  vp.user_snr in ('".$reg['user_snr']."','".$idUsuario."') ";
				}
				$queryVisitas .= "and vp.pers_snr = '".$id."' 
				order by vp.visit_date desc";
		
			//echo $queryVisitas."<br>";
		
			$rsVisitas = sqlsrv_query($conn, $queryVisitas);
		
			$queryCiclosVisitas = "select substring(ciclos.name,1,4) anio, 
	            substring(ciclos.name,6,2) ciclo, 
	            count(*) total 
	            from visitpers vp, cycles ciclos 
	            where vp.visit_date between ciclos.start_date and ciclos.finish_date 
	            and vp.rec_stat=0 
	            and vp.pers_snr='".$id."' ";
				if($tipoUsuario == 4){
					$queryCiclosVisitas .= "and  vp.user_snr = '".$reg['user_snr']."' ";
				}else{
					$queryCiclosVisitas .= "and  vp.user_snr in ('".$reg['user_snr']."','".$idUsuario."') ";
				}
	            $queryCiclosVisitas .= "and vp.user_snr='".$reg['user_snr']."' 
				and substring(ciclos.name,1,4) = (select max(substring(name,1,4)) from cycles) 
				group by ciclos.name ";
		
			//echo $queryCiclosVisitas."<br>";
			
			$rsCiclosVisitas = sqlsrv_query($conn, $queryCiclosVisitas);
			
			//echo "<script>";

			echo "<script>
				$('#divMedicosInactivos').hide();
				$('#cardInfMedicos').show();
				$('#hdnRutaDatosPersonales').val('".$reg['user_snr']."');
				$('#hdnEspecialidadPersona').val('".$reg['especialidad']."');
				$('#lblEdad').text('".$reg['edad']."');
				$('#lblAPaterno').text('".$reg['paterno']."');
				$('#lblCategoria').text('".$reg['categoria']."');
				$('#lblAmaterno').text('".$reg['materno']."');
				$('#lblCedula').text('".$reg['cedula']."');
				$('#lblNombre').text('".$reg['nombre']."');
				$('#lblFrecuencia').text('".$reg['frecuencia']."');
				$('#lblFrecPlan').text('".$reg['frecuencia']."');
				$('#lblFrecVisita').text('".$reg['frecuencia']."');
				$('#lblDireccion').text('".$reg['direccion']."');
				$('#lblDificultadVisita').text('".$reg['difVisita']."');
				$('#lblBrick').text('".$reg['brick_name']."');
				$('#lblSexo').text('".$reg['sexo']."');
				$('#lblEspecialidad').text('".$reg['especialidad']."');
				$('#lblEspecialidad2').text('".$reg['especialidad']."');
				$('#lblSubespecialidad').text('".$reg['subespecialidad']."');
				$('#lblPacientesPorSemana').text('".$reg['pacientesxsemana']."');
				$('#lblHonorarios').text('".$reg['honorarios']."');
				$('#lblRepresentante').text('".$reg['repre']."');
				$('#lblUltimaVisita').text('".$ultimaVisita."');
					
				$('#lblCampoAbierto1').text('');
				$('#lblCampoAbierto2').text('');
				$('#lblCampoAbierto3').text('');
			
				$('#lblTorre').text('".$reg['tower']."');
				$('#lblPiso').text('".$reg['floor']."');
				$('#lblConsultorio').text('".$reg['office']."');
				$('#lblLatitudPersonas').text('Latitud: ".$reg['LATITUDE']."');
				$('#lblLongitudPersonas').text('Longitud: ".$reg['LONGITUDE']."');
				$('#lblEstatus').text('".$reg['STATUS']."');
				$('#lblProducto1').text('".$reg['prod1']."');
				$('#lblProducto1W').text('".$reg['Prod1W']."');
				$('#lblProducto1H').text('".$reg['Prod1H']."');
				$('#lblProducto1A').text('".$reg['Prod1A']."');
				$('#lblProducto1T').text('".$reg['Prod1T']."');
				$('#lblProducto2').text('".$reg['prod2']."');
				$('#lblProducto2W').text('".$reg['Prod2W']."');
				$('#lblProducto2H').text('".$reg['Prod2H']."');
				$('#lblProducto2A').text('".$reg['Prod2A']."');
				$('#lblProducto2T').text('".$reg['Prod2T']."');
				$('#lblProducto3').text('".$reg['prod3']."');
				$('#lblProducto3W').text('".$reg['Prod3W']."');
				$('#lblProducto3H').text('".$reg['Prod3H']."');
				$('#lblProducto3A').text('".$reg['Prod3A']."');
				$('#lblProducto3T').text('".$reg['Prod3T']."');
				$('#lblProducto4').text('".$reg['prod4']."');
				$('#lblProducto4W').text('".$reg['Prod4W']."');
				$('#lblProducto4H').text('".$reg['Prod4H']."');
				$('#lblProducto4A').text('".$reg['Prod4A']."');
				$('#lblProducto4T').text('".$reg['Prod4T']."');
				$('#lblProducto5').text('".$reg['prod5']."');
				$('#lblProducto5W').text('".$reg['Prod5W']."');
				$('#lblProducto5H').text('".$reg['Prod5H']."');
				$('#lblProducto5A').text('".$reg['Prod5A']."');
				$('#lblProducto5T').text('".$reg['Prod5T']."');
				$('#lblParaestatales').text('".$reg['paraestatales']."');
				$('#lbllider').text('".$reg['lider']."');
				$('#lblSegmentacionFlonor').text('".$reg['SegmentacionFlonorm']."');
				$('#lblSegmentacionVessel').text('".$reg['SegmentacionVessel']."');
				$('#lblSegmentacionZifros').text('".$reg['SegmentacionZirfos']."');
				$('#lblDivMedico').text('".$reg['divMed']."');
				$('#lblRespuesta1').text('".$reg['SegmentacionGanar']."');
				$('#lblRespuesta2').text('".$reg['SegmentacionDesarrollar']."');
				$('#lblRespuesta3').text('".$reg['SegmentacionDefende']."');
				$('#lblRespuesta4').text('".$reg['SegmentacioEval']."');
				$('#lblSegmentacionAteka').text('".$reg['SegmentacionAteka']."');
				$('#lblField_14').text('".$reg['field14']."');
				$('#lblField_15').text('".$reg['field15']."');
				$('#lblField_16').text('".$reg['field16']."');
				$('#lblField_17').text('".$reg['field17']."');
				$('#lblField_18').text('".$reg['field18']."');
				$('#lblField_13').text('".$reg['field13']."');
				$('#tblPlan tbody').empty();
				$('#tblVisitas tbody').empty();
				$('#tblSubirDocumentosDatosPersonales tbody').empty();
				$('#lstProductoDatosPersonales').empty();
				$('#tblPrescripciones').empty();
				$('#tblMuestraMedica tbody').empty();";
					
			/*if($reg['frecuencia'] >= $reg['visitas']){
				echo "$('#imgAgregarVisita').prop('disabled', true);";
			}*/
			$contadorPlan = 0;
			$sinDatos = 'Sin datos que mostrar';
			while($plan = sqlsrv_fetch_array($rsPlanes)){
				$contadorPlan++;
				foreach ($plan['Fecha_Plan'] as $key => $val) {
					if(strtolower($key) == 'date'){
						$fecha_plan = substr($val, 0, 10);
					}
				}
			
				echo "$('#tblPlan tbody').append('<tr onClick=\"muestraPlan(\'".$plan['vispersplan_snr']."\');\"><td style=\"width:15%;\">".substr($plan['name'],0,5).substr($plan['name'],8,2)."</td><td style=\"width:10%;\">".$plan['user_nr']."</td><td style=\"width:15%;\">".$fecha_plan."</td><td style=\"width:15%;\">".$plan['Hora_Plan']."</td><td style=\"width:45%;\">".str_ireplace($buscar,$reemplazar,$plan['Objetivo'])."</td></tr>');";

			}
			if($contadorPlan == 0){
				echo "$('#tblPlan tbody').append('<tr><td style=\"width:100%;\">".$sinDatos."</td></tr>');";
			}

			for($i=1;$i<14;$i++){
				echo "$('#ciclo'+".$i.").text('0');
					$('#cicloVisita'+".$i.").text('0');";
				
			}
			$total = 0;
			while($ciclo = sqlsrv_fetch_array($rsCiclos)){
				echo "$('#ciclo'+".ltrim($ciclo['ciclo'], '0').").text('".$ciclo['total']."');";
				$total += $ciclo['total'];
			}
			echo "$('#acumulado').text('".$total."');";
			//$visitas = '';

			$contadorVisita = 0;
			while($visita = sqlsrv_fetch_array($rsVisitas)){
				$contadorVisita++;
				foreach ($visita['Fecha_Vis'] as $key => $val) {
					if(strtolower($key) == 'date'){
						$fecha_vis = substr($val, 0, 10);
					}
				}
				echo "$('#tblVisitas tbody').append('<tr onClick=\"muestraVisita(\'".$visita['vispers_snr']."\',\'".$visita['idPlan']."\');\"><td style=\"width:11%;\">".$visita['ciclo']."</td><td style=\"width:8%;\">".$visita['user_nr']."</td><td style=\"width:13%;\">".$fecha_vis."</td><td style=\"width:9%;\">".$visita['time']."</td><td style=\"width:19%;\">".$visita['Tipo_Vis']."</td><td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,$visita['informacion_vis'])."</td><td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,$visita['obj_vis'])."</td></tr>');";
			}
			if($contadorVisita == 0){
				echo "$('#tblVisitas tbody').append('<tr><td style=\"width:100%;\">".$sinDatos."</td></tr>');";
			}


			//echo $visitas;
			$totalVisitas = 0;
			while($cicloVisita = sqlsrv_fetch_array($rsCiclosVisitas)){
				echo "$('#cicloVisita'+".ltrim($cicloVisita['ciclo'], '0').").text('".$cicloVisita['total']."');";
				$totalVisitas += $cicloVisita['total'];
			}
			echo "$('#cicloVisitasAcumulado').text('".$totalVisitas."');";
			echo "$('#hdnIdPersona').val('".$id."');";
			
			/*****************documentos ******************/
			$queryArchivos = "select * from PERSON_PRIV_NOTE where PERS_SNR = '".$id."' and USER_SNR = '".$reg['user_snr']."' and rec_stat = 0";
			$rsArchivos = sqlsrv_query($conn, $queryArchivos);
			$append = 0;
			while($archivo = sqlsrv_fetch_array($rsArchivos)){
				//print_r($archivo);
				if(is_object($archivo['DATE'])){
					foreach ($archivo['DATE'] as $key => $val) {
						if(strtolower($key) == 'date'){
							$fechaArchivo = substr($val, 0, 10);
						}
					}
				}else{
					$fechaArchivo = '';
				}
				echo "$('#tblSubirDocumentosDatosPersonales tbody').append('<tr><td>".$fechaArchivo."</td><td>".$archivo['PATH']."</td><td>".$archivo['INFO']."</td><td align=\"center\"><a href=\"archivos/".$archivo['PATH']."\" target=\"_blank\"><img src=\"iconos/download24.png\" title=\"Descargar\" /></a></td><td><img onClick=\"eliminarArchivo(\'".$archivo['PERSPRNOTE_SNR']."\');\" src=\"iconos/deleteFile.png\" width=\"20px\" title=\"eliminar\" /></td></tr>');";
			}
			/*****************fin documentos **************/
			
			/*****************muestras*********************/
		
			$queryMuestras = "select vp.VISIT_DATE as fecha, pf.type as tipo, p.NAME as producto,
				pf.name as presentacion, pfb.NAME as lote, vpm.quantity as cantidad
				from VISITPERS_PRODBATCH vpm
				inner join VISITPERS vp on vp.VISPERS_SNR = vpm.VISPERS_SNR
				left outer join PRODFORMBATCH pfb on pfb.PRODFBATCH_SNR = vpm.PRODFBATCH_SNR
				inner join prodform pf on pf.PRODFORM_SNR = pfb.PRODFORM_SNR
				inner join product p on p.PROD_SNR = pf.PROD_SNR
				where vp.PERS_SNR = '".$id."'
				and year(getdate()) = year(vp.VISIT_DATE)
				order by vp.VISIT_DATE ";
			$rsMuestras = sqlsrv_query($conn, $queryMuestras);
			while($muestra = sqlsrv_fetch_array($rsMuestras)){
				foreach ($muestra['fecha'] as $key => $val) {
					if(strtolower($key) == 'date'){
						$fechaEntrega = substr($val, 0, 10);
					}
				}
				if($muestra['tipo'] == 132){
					$tipoMaterial = 'Muestra Médica';
				}else if($muestra['tipo'] == 133){
					$tipoMaterial = 'Competencia';
				}else if($muestra['tipo'] == 136){
					$tipoMaterial = 'Material';
				}
				echo "$('#tblMuestraMedica tbody').append('<tr><td style=\"width:13%;\">".$fechaEntrega."</td><td style=\"width:15%;\">".$tipoMaterial."</td><td style=\"width:15%;\">".$muestra['producto']."</td><td style=\"width:25%;\">".$muestra['presentacion']."</td><td style=\"width:20%;text-transform:capitalize;\">".$muestra['lote']."</td><td style=\"width:12%;\">".$muestra['cantidad']."</td></tr>');";
			}
			/****************fin muestras******************/
		
			/****************horario de trabajo **********/
			/*$queryHorario = "SELECT 
			PWTIM_SNR AS HORARIO_ID,
			pwt.PERS_SNR AS PERS_ID,
			PWADR_SNR AS PERSDIR_ID,
			PON_AM AS LUN_AM,
			PON_PM AS LUN_PM,
			FREE1 AS PREV_LUN,
			BEST1 AS HOR_LUN,
			COMENT1 AS COM_LUN,
			UT_AM AS MAR_AM,
			UT_PM AS MAR_PM,
			FREE2 AS PREV_MAR,
			BEST2 AS HOR_MAR,
			COMENT2 AS COM_MAR,
			SR_AM AS MIE_AM,
			SR_PM AS MIE_PM,
			FREE3 AS PREV_MIE,
			BEST3 AS HOR_MIE,
			COMENT3 AS COM_MIE,
			CET_AM AS JUE_AM,
			CET_PM AS JUE_PM,
			FREE4 AS PREV_JUE,
			BEST4 AS HOR_JUE,
			COMENT4 AS COM_JUE,
			PET_AM AS VIE_AM,
			PET_PM AS VIE_PM,
			FREE5 AS PREV_VIE,
			BEST5 AS HOR_VIE,
			COMENT5 AS COM_VIE,
			SUB_AM AS SAB_AM,
			SUB_PM AS SAB_PM,
			FREE6 AS PREV_SAB,
			BEST6 AS HOR_SAB,
			COMENT6 AS COM_SAB,
			NED_AM AS DOM_AM, NED_PM AS DOM_PM, FREE7 AS PREV_DOM, BEST7 AS HOR_DOM, COMENT7 AS COM_DOM 
			FROM PERSWORKTIME pwt
			inner join person p on p.PERS_SNR = pwt.PERS_SNR
			inner join PERSLOCWORK plw on plw.pwork_snr = pwt.PWADR_SNR
			WHERE pwt.REC_STAT=0 
			and p.pers_snr = '".$id."'
			ORDER BY pwt.PERS_SNR ";
		
			$rsHorario = sqlsrv_fetch_array(sqlsrv_query($conn, $queryHorario));
			if($rsHorario['LUN_AM'] == 1){
				//echo "alert('hi');";
				echo "$('#chkLunesam').prop('checked', true);";
			}else{
				echo "$('#chkLunesam').prop('checked', false);";
			}
			if($rsHorario['LUN_PM']){
				echo "$('#chkLunespm').prop('checked', true);";
			}else{
				echo "$('#chkLunespm').prop('checked', false);";
			}
			if($rsHorario['MAR_AM']){
				echo "$('#chkMartesam').prop('checked', true);";
			}else{
				echo "$('#chkMartesam').prop('checked', false);";
			}
			if($rsHorario['MAR_PM']){
				echo "$('#chkMartespm').prop('checked', true);";
			}else{
				echo "$('#chkMartespm').prop('checked', false);";
			}
			if($rsHorario['MIE_AM']){
				echo "$('#chkMiercolesam').prop('checked', true);";
			}else{
				echo "$('#chkMiercolesam').prop('checked', false);";
			}
			if($rsHorario['MIE_PM']){
				echo "$('#chkMiercolespm').prop('checked', true);";
			}else{
				echo "$('#chkMiercolespm').prop('checked', false);";
			}
			if($rsHorario['JUE_AM']){
				echo "$('#chkJuevesam').prop('checked', true);";
			}else{
				echo "$('#chkJuevesam').prop('checked', false);";
			}
			if($rsHorario['JUE_PM']){
				echo "$('#chkJuevespm').prop('checked', true);";
			}else{
				echo "$('#chkJuevespm').prop('checked', false);";
			}
			if($rsHorario['VIE_AM']){
				echo "$('#chkViernesam').prop('checked', true);";
			}else{
				echo "$('#chkViernesam').prop('checked', false);";
			}
			if($rsHorario['VIE_PM']){
				echo "$('#chkViernespm').prop('checked', true);";
			}else{
				echo "$('#chkViernespm').prop('checked', false);";
			}
			if($rsHorario['SAB_AM']){
				echo "$('#chkSabadoam').prop('checked', true);";
			}else{
				echo "$('#chkSabadoam').prop('checked', false);";
			}
			if($rsHorario['SAB_PM']){
				echo "$('#chkSabadopm').prop('checked', true);";
			}else{
				echo "$('#chkSabadopm').prop('checked', false);";
			}
			if($rsHorario['DOM_AM']){
				echo "$('#chkDomingoam').prop('checked', true);";
			}else{
				echo "$('#chkDomingoam').prop('checked', false);";
			}
			if($rsHorario['DOM_PM']){
				echo "$('#chkDomingopm').prop('checked', true);";
			}else{
				echo "$('#chkDomingopm').prop('checked', false);";
			}
			if($rsHorario['PREV_LUN']){
				echo "$('#chkLunesCita').prop('checked', true);";
			}else{
				echo "$('#chkLunesCita').prop('checked', false);";
			}
			if($rsHorario['PREV_MAR']){
				echo "$('#chkMartesCita').prop('checked', true);";
			}else{
				echo "$('#chkMartesCita').prop('checked', false);";
			}
			if($rsHorario['PREV_MIE']){
				echo "$('#chkMiercolesCita').prop('checked', true);";
			}else{
				echo "$('#chkMiercolesCita').prop('checked', false);";
			}
			if($rsHorario['PREV_JUE']){
				echo "$('#chkJuevesCita').prop('checked', true);";
			}else{
				echo "$('#chkJuevesCita').prop('checked', false);";
			}
			if($rsHorario['PREV_VIE']){
				echo "$('#chkViernesCita').prop('checked', true);";
			}else{
				echo "$('#chkViernesCita').prop('checked', false);";
			}
			if($rsHorario['PREV_SAB']){
				echo "$('#chkSabadoCita').prop('checked', true);";
			}else{
				echo "$('#chkSabadoCita').prop('checked', false);";
			}
			if($rsHorario['PREV_DOM']){
				echo "$('#chkDomingoCita').prop('checked', true);";
			}else{
				echo "$('#chkDomingoCita').prop('checked', false);";
			}
			echo "$('#lblComentariosLunes').text('".$rsHorario['COM_LUN']."');";
			echo "$('#lblComentariosMartes').text('".$rsHorario['COM_MAR']."');";
			echo "$('#lblComentariosMiercoles').text('".$rsHorario['COM_MIE']."');";
			echo "$('#lblComentariosJueves').text('".$rsHorario['COM_JUE']."');";
			echo "$('#lblComentariosViernes').text('".$rsHorario['COM_VIE']."');";
			echo "$('#lblComentariosSabado').text('".$rsHorario['COM_SAB']."');";
			echo "$('#lblComentariosDomingo').text('".$rsHorario['COM_DOM']."');";
			/****************fin horario de trabajo*******/
		
			/********bancos / aseguradoras**********/
			$arrPasatiempos = array();
			$rsArrPasatiempos = sqlsrv_query($conn, "select BANK_SNR from PERSON where PERS_SNR = '".$id."' and rec_stat = 0");
			//echo "select BANK_SNR from PERSON_BANK where PERS_SNR = '".$id."' ";
			
			while($pasatiempoArr = sqlsrv_fetch_array($rsArrPasatiempos)){
				$arrPasatiempos[] = $pasatiempoArr['BANK_SNR'];
			}
			$rsPasatiempos = llenaCombo($conn, 19, 15);
			$contador = 1;
			
			$separados = explode(';', $arrPasatiempos[0]);
			
			//print_r($arrPasatiempos);
			while($pasatiempo = sqlsrv_fetch_array($rsPasatiempos)){
				//echo $pasatiempo['id']."<br>";
				//if(in_array($pasatiempo['id'], $arrPasatiempos)){
				if(in_array($pasatiempo['id'], $separados)){
					//echo "alert('entre');";
					echo "$('#chkBancosPersonaDatosPersonales".$contador."').prop('checked', true);";
				}else{
					echo "$('#chkBancosPersonaDatosPersonales".$contador."').prop('checked', false);";
				}
				$contador++;
			}
		
			//echo "$('#hdnPasatiempo').val('".$contador."');";
			/****fin de bancos / seguradoras*******/
		
			/******************prescripciones*************/
		
			echo "$('#hdnIdsFiltroPeriodos').val('');
				$('#hdnNombresFiltroPeriodos').val('');
				$('#hdnIdsFiltroMercados').val('');
				$('#hdnNombresFiltroMercados').val('');
				$('#sltPeriodo').text('Seleccione');
				$('#sltMercado').text('Seleccione');
				$('#tblPeriodosSeleccionadosFiltros').empty();
				$('#tblMercadosSeleccionadosFiltros').empty();";
			
			$queryPrescripciones = "select *, p.NAME as producto,
				pp.MARKET_SHARE as MARKET_SHARE,
				per.NAME as periodo,
				mdo.NAME as mdo,
				cat.NAME as categoria,
				m.MARKET_SHARE as ms,
				m.NUM_RX
				from PERSON_RX_PRODUCT pp
				inner join PERSON_RX_MARKET m on m.PERSRXMARKET_SNR = pp.PERSRXMARKET_SNR
				inner join CODELIST p on p.CLIST_SNR = pp.PRODUCT_SNR and pp.REC_STAT=0
				inner join CODELIST per on per.CLIST_SNR = m.PERIOD_SNR and per.REC_STAT=0
				inner join CODELIST mdo on mdo.CLIST_SNR = m.MARKET_SNR and mdo.REC_STAT=0
				inner join CODELIST cat on cat.CLIST_SNR = m.CATEGORY_SNR and cat.REC_STAT=0
				where pp.pers_snr = '".$id."' 
				and per.STATUS=1 
				order by per.NAME, mdo.NAME, cat.NAME, pp.MARKET_SHARE";
				
			//echo $queryPrescripciones;

			$rsPrescripciones = sqlsrv_query($conn, $queryPrescripciones);
			echo '$("#lstProductoDatosPersonales").append(new Option("Seleccione", ""));';
			$cuentaTablas = '';
			$contadorRx = 0;

			// Variables para almacenar el HTML de las tablas
			$htmlTablas = array();
			$currentPeriodo = null;
			$currentMdo = null;
			$htmlTablaActual="";

			// Inicializar la cabecera de la tabla con valores del primer registro
			if ($pres = sqlsrv_fetch_array($rsPrescripciones)) {
				$htmlTablaActual = '<table border="1" class="table table-bordered">
										<tr>
											<th style="background-color: white; color: black; text-align: center;">Periodo: ' .$pres['periodo'].'</th>
											<th style="background-color: white; color: black; text-align: center;">Mdo:  ' .$pres['mdo'].'</th>
											<th style="background-color: white; color: black; text-align: center;">Cat :' .$pres['categoria'].' Ms:' .$pres['ms'].' Pres: ' .$pres['NUM_RX'].' </th>
										</tr>
										<tr>
											<th style="background-color: gray; color: white; text-align: center;">Ranking</th>
											<th style="background-color: gray; color: white; text-align: center;">Producto</th>
											<th style="background-color: gray; color: white; text-align: center;">Market Share</th>
										</tr>';
				$contadorRx++;
				$cadenaTest = $pres['producto'];
				$cadenaTest = str_replace(' ', '_', $cadenaTest);
				$cuentaTablas .= $cadenaTest.",";
				echo '$("#lstProductoDatosPersonales").append(new Option("'.$pres['producto'].'", "'.$pres['PRODUCT_SNR'].'"));';

				// Construir una fila de la tabla con los datos de cada prescripción
				$htmlTablaActual .= '<tr>';
				$htmlTablaActual .= '<td class="text-center">' . $pres['POSITION'] . '</td>';
				$htmlTablaActual .= '<td class="text-center">' . $pres['producto'] . '</td>';
				$htmlTablaActual .= '<td class="text-center">' . $pres['MARKET_SHARE'] . '</td>';
				$htmlTablaActual .= '</tr>';

				// Guardar el periodo y el mdo actual
				$currentPeriodo = $pres['periodo'];
				$currentMdo = $pres['mdo'];
			}

			while($pres = sqlsrv_fetch_array($rsPrescripciones)){
				$contadorRx++;
				$cadenaTest = $pres['producto'];
				$cadenaTest = str_replace(' ', '_', $cadenaTest);
				$cuentaTablas .= $cadenaTest.",";

				// Verificar si el periodo o el mdo han cambiado
				if ($currentPeriodo !== $pres['periodo'] || $currentMdo !== $pres['mdo']) {
					// Si han cambiado, guardar la tabla actual en el array y empezar una nueva
					$htmlTablas[] = $htmlTablaActual;

					$htmlTablaActual = '<table border="1" class="table table-bordered">
											<tr>
												<th style="background-color: white; color: black; text-align: center;">Periodo: ' .$pres['periodo'].'</th>
												<th style="background-color: white; color: black; text-align: center;">Mdo:  ' .$pres['mdo'].'</th>
												<th style="background-color: white; color: black; text-align: center;">Cat :' .$pres['categoria'].' Ms:' .$pres['ms'].' Pres: ' .$pres['NUM_RX'].' </th>
											</tr>
											<tr>
												<th style="background-color: gray; color: white; text-align: center;">Ranking</th>
												<th style="background-color: gray; color: white; text-align: center;">Producto</th>
												<th style="background-color: gray; color: white; text-align: center;">Market Share</th>
											</tr>';
					$currentPeriodo = $pres['periodo'];
					$currentMdo = $pres['mdo'];
				}

				// Construir una fila de la tabla con los datos de cada prescripción
				$htmlTablaActual .= '<tr>';
				$htmlTablaActual .= '<td class="text-center">' . $pres['POSITION'] . '</td>';
				$htmlTablaActual .= '<td class="text-center">' . $pres['producto'] . '</td>';
				$htmlTablaActual .= '<td class="text-center">' . $pres['MARKET_SHARE'] . '</td>';
				$htmlTablaActual .= '</tr>';
			}

			// Cerrar la etiqueta de la última tabla y guardarla en el array de tablas
			$htmlTablaActual .= '</table>';
			$htmlTablas[] = $htmlTablaActual;

			// Imprimir todas las tablas en el mismo contenedor
			echo '$("#tblPrescripciones").html(' . json_encode(implode('<br>', $htmlTablas)) . ');';

			if($contadorRx == 0){
				echo "$('#tblPrescripciones').append('<div class=\"row col-indigo font-bold\"><div class=\"col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-0\"><span class=\"col-md-6 p-l-0 margin-0\">Período: </span><span class=\"col-md-6 align-right margin-0\">Mdo: </span></div><div class=\"col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-0\"><span class=\"col-md-4 margin-0 p-l-5\">Categoría: </span><span class=\"col-md-4 align-center margin-0\">MS: </span><span class=\"col-md-4 align-right p-r-5 margin-0\">Prescribe: </span></div></div><div class=\"row\"><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><div class=\"div-tbl-grey\"><table class=\"tblRxPersonas\"><thead class=\"bg-grey\"><tr class=\"align-center\"><td style=\"width:30%;\">Ranking</td><td style=\"width:40%;\">Producto</td><td style=\"width:30%;\">Market Share</td></tr></thead><tbody><tr class=\"align-center\"><td style=\"width:100%;\">Sin datos que mostrar</td></tr></tbody></table></div></div></div>');";
			}

			/******************fin de prescripciones******/
			echo "$('#hdnTablasPrescripciones').val('".$cuentaTablas."');
			//alert('".$tipoUsuario." --- ".$reg['frecuencia']." --- ".$reg['visitas']."');";
			if($tipoUsuario == 4){
				if((int)$reg['frecuencia'] > (int)$reg['visitas']){
				echo "$('#imgAgregarVisita').prop('disabled', false);";
				}else{
					//echo "alert('1'); ";
					echo "$('#imgAgregarVisita').prop('disabled', true);";
				}
				//echo "alert('".$ultimaVisita."---".date("Y-m-d")."'); ";
				if($ultimaVisita == date("Y-m-d")){
					//echo "alert('2'); ";
					echo "$('#imgAgregarVisita').prop('disabled', true);";
				}else{
					echo "$('#imgAgregarVisita').prop('disabled', false);";
				}
			}
		
			/*************objetivos*********/
			echo "
			$('#txtCortoPerson').text('".str_ireplace($buscar,$reemplazar,$reg['corto'])."');
			$('#txtLargoPerson').val('".str_ireplace($buscar,$reemplazar,$reg['largo'])."');
			$('#txtGeneralesPerson').val('".str_ireplace($buscar,$reemplazar,$reg['generales'])."');";
			/******fin de objetivos*********/

			/*******inversiones*********/
		
			$qTotalInversiones = "select cast(c.NUMBER as int)  as numero_ciclo, count(c.NUMBER) as total
				from USER_INVESTMENT ui
				inner join cycles c on ui.DATE between c.START_DATE and c.FINISH_DATE
				where ui.PERS_SNR = '".$id."'
				and ui.REC_STAT = 0
				group by c.NUMBER";
			
			$rsTotalInversiones = sqlsrv_query($conn, $qTotalInversiones);
			
			for($i=1;$i<14;$i++){
				echo "$('#cicloInversion".$i."').text('0');";
			}
			$totaInversiones = 0;
			while($totalInversion = sqlsrv_fetch_array($rsTotalInversiones)){
				$totaInversiones += $totalInversion['total'];
				echo "$('#cicloInversion".$totalInversion['numero_ciclo']."').text('".$totalInversion['total']."');";
			}
			
			echo "$('#acumuladoInversion').text('".$totaInversiones."');";
			
			echo "$('#tblInversion tbody').empty();";
		
			$qInversiones = "select ui.USER_INVESTMENT_SNR,
				c.name as ciclo,
				u.USER_NR,
				CAST(year(ui.date) as nvarchar) + '-' + right('00' + Ltrim(Rtrim(cast(month(ui.date) as nvarchar))),2) + '-' + right('00' + Ltrim(Rtrim(cast(day(ui.date) as nvarchar))),2) as date,
				ui.name,
				ui.PROD_SNR,
				ui.info
				from USER_INVESTMENT ui
				left outer join person p on ui.PERS_SNR = p.PERS_SNR
				left outer join CYCLES c on ui.date between c.START_DATE and c.FINISH_DATE
				left outer join users u on u.USER_SNR = ui.USER_SNR
				where ui.REC_STAT = 0 
				and p.REC_STAT = 0
				and ui.PERS_SNR = '".$id."'";
				
			$rsInversiones = sqlsrv_query($conn, $qInversiones);
		
			while($inversion = sqlsrv_fetch_array($rsInversiones)){
				$idInversion = $inversion['USER_INVESTMENT_SNR'];
				$ciclo = $inversion['ciclo'];
				$ruta = $inversion['USER_NR'];
				$fecha = $inversion['date'];
				$nombre = $inversion['name'];
				$idProducto = str_replace(";", "','",$inversion['PROD_SNR']);
				$comentarios = $inversion['info'];
				
				$productos = '';
				
				if($idProducto != ''){
					$rsProductos = sqlsrv_query($conn, "select name from product where prod_snr in ('".$idProducto."')");
					//echo "select name from product where prod_snr in ('".$idProducto."')";
					
					while($producto = sqlsrv_fetch_array($rsProductos)){
						$productos .= $producto['name'].", ";
					}
				}
				echo "$('#tblInversion tbody').append('<tr onClick=\"muestraInversion(\'".$idInversion."\');\"><td style=\"width:15%\">".$ciclo."</td><td style=\"width:10%\">".$ruta."</td><td style=\"width:15%\">".$fecha."</td><td style=\"width:15%\">".$nombre."</td><td style=\"width:20%\">".$productos."</td><td style=\"width:25%\">".$comentarios."</td></tr>');";
			}
		
			/******fin de inversiones***/
			
			if($tipoUsuario != 4){
				echo "$('#divMedicosInactivos').hide();
					$('#cardInfMedicos').show();";
			}

			echo "$('#hdnMed').val('".$id."')";
			echo "</script>";
		}
	}

?>