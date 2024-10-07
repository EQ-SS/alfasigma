<?php
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r", "\t");
	$reemplazar=array(" ", " ", " ", " "," ");
	
	function quitarCaracteres($cadena){
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r", "\t");
		$reemplazar=array(" ", " ", " ", " "," ");
		$cadena = str_ireplace($buscar,$reemplazar,utf8_encode($cadena));
		$cadena = str_replace("'","",$cadena);
		return $cadena;
	}
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$id = $_POST['id'];
		$tipoUsuario = $_POST['tipoUsuario'];
		$idUsuario = $_POST['idUsuario'];
		
		//echo "<br><br><br><br><br>";
		//echo "id ".$id." ::: tipoUsuario ".$tipoUsuario." ::: idUsuario ".$idUsuario;
		
		$queryPersona = "select p.LNAME as paterno, 
				categ.name as categoria, 
				cast(year(BIRTHDATE) as varchar(4)) + '-' +  FORMAT(month(BIRTHDATE), '00') + '-' + FORMAT(day(BIRTHDATE), '00') as fechaNacimiento, 
				p.mothers_lname as materno, 
				p.prof_id as cedula, 
				p.FNAME as nombre, 
				freq.NAME as frecuencia, 
				i.NAME as institucion, 
				i.STREET1 +' '+ 'NUM. EXT.' + ' ' + i.num_ext + ' ' + city.name + ' ' + d.NAME + ' ' + state.NAME as direccion, 
				dif.name as difVisita,
				brick.name as brick_name, 
				sexo.NAME as sexo,
				esp.NAME as especialidad,
				subesp.NAME as subespecialidad,
				u.LNAME + ' ' + u.mothers_lname + ' ' + u.fname as repre, 
				(select top 1 VISIT_DATE from visitpers where PERS_SNR = p.PERS_SNR and REC_STAT = 0 order by VISIT_DATE desc ) as ultimaVisita,
				u.user_snr, plw.tower, plw.floor, plw.office, plw.department, 
				(SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".date("Y-m-d")."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE ";
		if($tipoUsuario == 4){
			$queryPersona .= "and vp.user_snr = '".$idUsuario."') as visitas, ";
		}else{
			$queryPersona .= "and vp.user_snr = '".$id."') as visitas, ";
		}
		$queryPersona .= "plw.LATITUDE, plw.LONGITUDE, p.info as generales,
				p.info_shorttime as corto, 
				p.info_longtime as largo,
				pac.name as pacientesxsemana, 
				hon.name as honorarios, 
				estatus.name as estatus,
				p.mobile as celular,
				p.TEL2 as telefono2,
				p.email2,
				p.tel1 as telefono1,
				p.email1, 
				pad.name as AILMENT_SNR,
				marital.name as MARITAL_STATUS_SNR,
				lider.name as KOL_SNR 
				from person p 
				left outer join CODELIST categoriaAudit on categoriaAudit.CLIST_SNR = p.CATEGORY_SNR
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
				left outer join person_ud pud on pud.PERS_SNR = p.PERS_SNR
				left outer join CODELIST estatus on estatus.CLIST_SNR = p.STATUS_SNR 
				left outer join CODELIST pac on pac.CLIST_SNR = p.patperweek_snr 
				left outer join CODELIST hon on hon.CLIST_SNR = p.fee_type_snr 
				left outer join CODELIST pad on pad.clist_snr = p.AILMENT_SNR 
				left outer join CODELIST marital on marital.clist_snr = p.MARITAL_STATUS_SNR 
				left outer join CODELIST lider on lider.clist_snr = p.KOL_SNR 
				where p.pers_snr = '".$id."'
				and p.REC_STAT = 0";
			if($tipoUsuario == 4){
				$queryPersona .= "and u.user_snr = '".$idUsuario."' ";
			}/*else{
				$queryPersona .= "and u.user_snr = '".$id."' ";
			}*/

		//echo $queryPersona;

		$reg = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPersona));
		
		/*$desConection = '';
		if($reg['conection_crm_snr'] != '' && $reg['conection_crm_snr'] != '00000000-0000-0000-0000-000000000000'){
			if(substr($reg['conection_crm_snr'], -1) == ';'){
				$conection = substr($reg['conection_crm_snr'], 0, -1);
			}else{
				$conection = $reg['conection_crm_snr'];
			}
			$conection = str_replace(";", "','", $conection);
			$rsConnection = sqlsrv_query($conn, "select name from codelist where clist_snr in ('".$conection."')");
			while($conec = sqlsrv_fetch_array($rsConnection)){
				$desConection .= utf8_encode($conec['name']).",";
			}
			$desConection = substr($desConection, 0, -1);
		}*/

		$queryPersona2 = "select p.LNAME as paterno, 
				categ.name as categoria, 
				p.mothers_lname as materno, 
				p.prof_id as cedula, 
				p.FNAME as nombre, 
				freq.NAME as frecuencia, 
				i.STREET1 + ' ' + city.name +' '+ d.NAME +' '+state.NAME as direccion, 
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

		$reg2 = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPersona2));
		
		//echo $queryPersona2;
		
		echo "<script>
				$('#lblDireccion2').text('".quitarCaracteres($reg2['direccion'])."');
				$('#lblCategoria2').text('".quitarCaracteres($reg2['categoria'])."');
				$('#lblCedula2').text('".quitarCaracteres($reg2['cedula'])."');
				$('#lblBrick2').text('".quitarCaracteres($reg2['brick_name'])."');
				$('#lblRepresentante2').text('".quitarCaracteres($reg2['repre'])."');
				$('#lblConsultorio2').text('".quitarCaracteres($reg2['office'])."');
				$('#divMedicosInactivos').waitMe('hide');
			</script>";
		

		$ultimaVisita = '';
		if(is_object($reg['ultimaVisita'])){
			foreach ($reg['ultimaVisita'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$ultimaVisita = substr($val, 0, 10);
				}
			}
		}
		/**********planes********/
		$queryPlanes = "select plan_date as Fecha_Plan, vp.time as Hora_Plan, 
			u.user_nr, u.lname +' '+ u.fname as Rep, cd.name as Tipo_Plan, 
			vp.info as Objetivo, vp.vispersplan_snr, vp.user_snr, vp.pers_snr, 
			(select top 1 info from visitpers where visitpers.pers_snr=vp.pers_snr 
			and creation_timestamp in (select max(creation_timestamp) from visitpers)) as info_Ult_Vis,
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
			
		//echo $queryPlanes;
			
		$rsPlanes = sqlsrv_query($conn, $queryPlanes);
			
		$queryCiclos = "select substring(ciclos.name,1,4) anio, 
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
		//echo $queryCiclos;
		
		/***************** fin planes ***********************/
		
		/******************visitas**************************/
		$queryVisitas = "select visit_date as Fecha_Vis,
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
				$('#hdnRutaDatosPersonales').val('".$reg['user_snr']."');
				$('#hdnEspecialidadPersona').val('".$reg['especialidad']."');
				$('#lblAPaterno').text('".quitarCaracteres($reg['paterno'])."');
				$('#lblCategoria').text('".quitarCaracteres($reg['categoria'])."');
				$('#lblAmaterno').text('".quitarCaracteres($reg['materno'])."');
				$('#lblCedula').text('".$reg['cedula']."');
				$('#lblNombre').text('".quitarCaracteres($reg['nombre'])."');
				$('#lblFrecPlan').text('".$reg['frecuencia']."');
				$('#lblFrecVisita').text('".$reg['frecuencia']."');
				$('#lblDireccion').text('".quitarCaracteres($reg['direccion'])."');
				$('#lblBrick').text('".$reg['brick_name']."');
				$('#lblEspecialidad2').text('".quitarCaracteres($reg['especialidad'])."');
				$('#lblHonorarios').text('".$reg['honorarios']."');
				$('#lblRepresentante').text('".quitarCaracteres($reg['repre'])."');
				$('#lblUltimaVisita').text('".$ultimaVisita."');
				$('#lblEspecialidadAudiencia').text('".quitarCaracteres($reg['subespecialidad'])."');
				$('#lblPacientesDia').text('".quitarCaracteres($reg['pacientesxsemana'])."');
				$('#lblHonorarios').text('".quitarCaracteres($reg['honorarios'])."');
				$('#lblFechaNacimiento').text('".$reg['fechaNacimiento']."');
				$('#lblCelular').text('".$reg['celular']."');
				$('#lblTelefono2').text('".$reg['telefono2']."');
				$('#lblEmail2').text('".$reg['email2']."');
				$('#lblFrecuenciaVisita').text('".$reg['frecuencia']."');
				$('#lblDificultadVisita').text('".$reg['difVisita']."');
				$('#lblEstatus').text('".$reg['estatus']."');
				$('#lblSexo').text('".$reg['sexo']."');
				$('#lblTelefono1').text('".$reg['telefono1']."');
				$('#lblEmail1').text('".$reg['email1']."');
				$('#lblEmail2').text('".$reg['email2']."');
				$('#lblCampoAbierto1').text('');
				$('#lblCampoAbierto2').text('');
				$('#lblCampoAbierto3').text('');
				$('#lblTorre').text('".utf8_encode($reg['tower'])."');
				$('#lblPiso').text('".utf8_encode($reg['floor'])."');
				$('#lblDepto').text('".utf8_encode($reg['department'])."');
				$('#lblConsultorio').text('".utf8_encode($reg['office'])."');
				$('#lblLatitudPersonas').text('Latitud: ".$reg['LATITUDE']."');
				$('#lblLongitudPersonas').text('Longitud: ".$reg['LONGITUDE']."');
				$('#lblEstatusDatosPersonales').text('".$reg['estatus']."');
				$('#lblPadecimientosMedicos').text('".$reg['AILMENT_SNR']."');
				$('#lblLiderOpinion').text('".$reg['KOL_SNR']."');
				$('#lblEstadoCivil').text('".$reg['MARITAL_STATUS_SNR']."');
				$('#tblPlan tbody').empty();
				$('#tblVisitas tbody').empty();
				$('#tblSubirDocumentosDatosPersonales tbody').empty();
				$('#lstProductoDatosPersonales').empty();
				$('#tblPrescripciones').empty();
				$('#tblMuestraMedica tbody').empty();
				";
				
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
			
			echo "$('#tblPlan tbody').append('<tr onClick=\"muestraPlan(\'".$plan['vispersplan_snr']."\');\"><td style=\"width:15%;\">".$plan['name']."</td><td style=\"width:10%;\">".$plan['user_nr']."</td><td style=\"width:15%;\">".$fecha_plan."</td><td style=\"width:15%;\">".$plan['Hora_Plan']."</td><td style=\"width:45%;\">".utf8_encode(str_ireplace($buscar,$reemplazar,$plan['Objetivo']))."</td></tr>');";

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
		$visitas = '';

		$contadorVisita = 0;
		while($visita = sqlsrv_fetch_array($rsVisitas)){
			$contadorVisita++;
			foreach ($visita['Fecha_Vis'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha_vis = substr($val, 0, 10);
				}
			}
			
			echo "$('#tblVisitas tbody').append('<tr onClick=\"muestraVisita(\'".$visita['vispers_snr']."\',\'".$visita['idPlan']."\');\"><td style=\"width:11%;\">".$visita['ciclo']."</td><td style=\"width:8%;\">".$visita['user_nr']."</td><td style=\"width:13%;\">".$fecha_vis."</td><td style=\"width:9%;\">".$visita['time']."</td><td style=\"width:19%;\">".utf8_encode($visita['Tipo_Vis'])."</td><td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($visita['informacion_vis']))."</td><td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($visita['obj_vis']))."</td></tr>');";
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
		echo "$('#lblComentariosDomingo').text('".$rsHorario['COM_DOM']."');";*/
		/****************fin horario de trabajo*******/
		
		/********bancos / aseguradoras**********/
		//$arrPasatiempos = explode(";", $reg['basic_list_snr']);
		//$rsArrPasatiempos = sqlsrv_query($conn, "select BANK_SNR from PERSON_BANK where PERS_SNR = '".$id."' and rec_stat = 0");
		//echo "select BANK_SNR from PERSON_BANK where PERS_SNR = '".$id."' ";
		
		/*while($pasatiempoArr = sqlsrv_fetch_array($rsArrPasatiempos)){
			$arrPasatiempos[] = $pasatiempoArr['BANK_SNR'];
		}*/
		/*$rsPasatiempos = llenaCombo($conn, 19, 14);
		$contador = 1;
		//print_r($arrPasatiempos);
		while($pasatiempo = sqlsrv_fetch_array($rsPasatiempos)){
			//echo $pasatiempo['id']."<br>";
			if(in_array($pasatiempo['id'], $arrPasatiempos)){
				//echo "alert('entre');";
				echo "$('#chkBancosPersonaDatosPersonales".$contador."').prop('checked', true);";
			}else{
				echo "$('#chkBancosPersonaDatosPersonales".$contador."').prop('checked', false);";
			}
			$contador++;
		}*/
		
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
			m.MARKET_SHARE as ms 
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
		while($pres = sqlsrv_fetch_array($rsPrescripciones)){
			$contadorRx++;
			$cadenaTest = $pres['producto'];
			$cadenaTest = str_replace(' ', '_', $cadenaTest);
			$cuentaTablas .= $cadenaTest.",";
			echo '$("#lstProductoDatosPersonales").append(new Option("'.$pres['producto'].'", "'.$pres['PRODUCT_SNR'].'"));';
			echo "$('#tblPrescripciones').append('<div id=\"tbl".$cadenaTest."\"><div class=\"row col-indigo font-bold\"><div class=\"col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-0\"><span class=\"col-md-6 p-l-0 margin-0\">Período: ".$pres['periodo']."</span><span class=\"col-md-6 align-right margin-0\">Mdo: ".$pres['mdo']."</span></div><div class=\"col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-0\"><span class=\"col-md-4 margin-0 p-l-5\">Categoría: ".$pres['categoria']."</span><span class=\"col-md-4 align-center margin-0\">MS: ".$pres['ms']."</span><span class=\"col-md-4 align-right p-r-5 margin-0\">Prescribe: ".$pres['NUM_RX']."</span></div></div><div class=\"row\"><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><div class=\"div-tbl-grey\"><table width=\"100%\" class=\"tblRxPersonas\"><thead class=\"bg-grey\"><tr class=\"align-center\"><td style=\"width:30%;\">Ranking</td><td style=\"width:40%;\">Producto</td><td style=\"width:30%;\">Market Share</td></tr></thead><tbody><tr class=\"align-center\"><td style=\"width:30%;\">".$pres['POSITION']."</td><td style=\"width:40%;\">".$pres['producto']."</td><td style=\"width:30%;\">".$pres['MARKET_SHARE']."%</td></tr></tbody></table></div></div></div></div>');";
		}
		if($contadorRx == 0){
			echo "$('#tblPrescripciones').append('<div class=\"row col-indigo font-bold\"><div class=\"col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-0\"><span class=\"col-md-6 p-l-0 margin-0\">Período: </span><span class=\"col-md-6 align-right margin-0\">Mdo: </span></div><div class=\"col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-0\"><span class=\"col-md-4 margin-0 p-l-5\">Categoría: </span><span class=\"col-md-4 align-center margin-0\">MS: </span><span class=\"col-md-4 align-right p-r-5 margin-0\">Prescribe: </span></div></div><div class=\"row\"><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><div class=\"div-tbl-grey\"><table class=\"tblRxPersonas\" width=\"100%\"><thead class=\"bg-grey\"><tr class=\"align-center\"><td style=\"width:30%;\">Ranking</td><td style=\"width:40%;\">Producto</td><td style=\"width:30%;\">Market Share</td></tr></thead><tbody><tr class=\"align-center\"><td style=\"width:100%;\">Sin datos que mostrar</td></tr></tbody></table></div></div></div>');";
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
			$('#txtCortoPerson').val('".str_ireplace($buscar,$reemplazar,$reg['corto'])."');
			$('#txtLargoPerson').val('".str_ireplace($buscar,$reemplazar,$reg['largo'])."');
			$('#txtGeneralesPerson').val('".str_ireplace($buscar,$reemplazar,$reg['generales'])."');";
		/******fin de objetivos*********/
		
		/********** eventos ********/
		$qTotalEventos = "select cast(c.NUMBER as int)  as numero_ciclo, count(c.NUMBER) as total
			from EVENT_PERS ep
			inner join EVENT e on e.EVENT_SNR = ep.EVENT_SNR
			inner join cycles c on e.START_DATE between c.START_DATE and c.FINISH_DATE
			where ep.PERS_SNR = '".$id."'
			and ep.REC_STAT = 0
			and e.REC_STAT = 0
			group by c.NUMBER ";
		
		$rsTotalEventos = sqlsrv_query($conn, $qTotalEventos);
		
		for($i=1;$i<14;$i++){
			echo "$('#ciclo".$i."Evento').text('0');";
		}
		$totaEventos = 0;
		while($totalEvento = sqlsrv_fetch_array($rsTotalEventos)){
			$totaEventos += $totalEvento['total'];
			echo "$('#ciclo".$totalEvento['numero_ciclo']."Evento').text('".$totalEvento['total']."');";
		}
		
		echo "$('#acumuladoEvento').text('".$totaEventos."');";
		
		$qEventos = "select u.user_nr as ruta, c.name as ciclo, e.name, 
			format(year(e.start_date), '0000') + '-' + format(month(e.start_date), '00') + '-' + format(day(e.start_date), '00') + ' ' + e.START_TIME as START_DATE,
			format(year(e.FINISH_DATE), '0000') + '-' + format(month(e.FINISH_DATE), '00') + '-' + format(day(e.FINISH_DATE), '00') + ' ' + e.FINISH_TIME as FINISH_DATE,
			tipo.name as tipo, par.name as participacion, e.info as comentarios
			from EVENT_PERS ep
			inner join EVENT e on e.EVENT_SNR = ep.EVENT_SNR
			inner join users u on u.USER_SNR = e.USER_SNR
			inner join cycles c on e.START_DATE between c.START_DATE and c.FINISH_DATE
			left outer join CODELIST tipo on tipo.CLIST_SNR = e.TYPE_SNR
			left outer join CODELIST par on par.CLIST_SNR = e.PART_TYPE_SNR
			where ep.PERS_SNR = '".$id."'
			and ep.REC_STAT = 0
			and e.REC_STAT = 0 ";
			
		$rsEventos = sqlsrv_query($conn, $qEventos);
		
		echo "$('#tblEventoPerfil tbody').empty();";
		
		while($evento = sqlsrv_fetch_array($rsEventos)){
			$ruta = $evento['ruta'];
			$ciclo = $evento['ciclo'];
			$nombre = utf8_encode($evento['name']);
			$fechaI = $evento['START_DATE'];
			$fechaF = $evento['FINISH_DATE'];
			$tipo = utf8_encode($evento['tipo']);
			$participacion = utf8_encode($evento['participacion']);
			$comentarios = utf8_encode($evento['comentarios']);
			echo "$('#tblEventoPerfil').append('<tr><td style=\"width:10%;\">".$ciclo."</td><td style=\"width:10%;\">".$ruta."</td><td style=\"width:15%;\">".$fechaI."</td><td style=\"width:15%;\">".$fechaF."</td><td style=\"width:20%;\">".$nombre."</td><td style=\"width:20%;\">".$tipo."</td><td style=\"width:20%;\">".$participacion."</td></tr>');";
		}
		/***** fin de eventos ******/
	
		/*******fin red personas ***/
		
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
		echo "</script>";
	}
?>