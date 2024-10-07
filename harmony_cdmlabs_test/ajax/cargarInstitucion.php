<?php
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r", "\t");
	$reemplazar=array(" ", " ", " ", " "," ");

	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$id = $_POST['id'];
		$idUser = $_POST['idUsuario'];
		
		if(isset($_POST['repre']) && $_POST['repre'] != ''){
			$repre = $_POST['repre'];
		}else{
			$repre = '';
		}
		
		$tipoUsuario = $_POST['tipoUsuario'];

		if($id == ''){
			$id = '131DE022-D1BE-4C9B-9153-AC360158A98A';
		}
		
		$query = "select i.name, INST_TYPE.NAME as tipo, i.STREET1 +' NUM. EXT. '+ i.num_ext + ' ' +city.name +' '+ d.NAME +' '+state.NAME as direccion,
			i.TEL1, i.TEL2, EMAIL1 as EMAIL, i.web as HTTP, i.INST_TYPE as idTipo,i.LATITUDE, i.LONGITUDE, i.FRECVIS_SNR
			from INST i
			left outer join INST_TYPE on inst_type.INST_TYPE = i.INST_TYPE
			left outer join CITY on city.CITY_SNR = i.CITY_SNR
			inner join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
			inner join STATE on state.STATE_SNR = city.STATE_SNR
			inner join BRICK bri on bri.BRICK_SNR = city.BRICK_SNR
			where i.inst_snr = '".$id."'";

		$reg = sqlsrv_fetch_array(sqlsrv_query($conn, $query));

		if($reg['FRECVIS_SNR'] == '00000000-0000-0000-0000-000000000000'){
			$reg['FRECVIS_SNR'] = 0;
		}
		
		$queryInstitucionesPersonas = "select p.LNAME as paterno, p.MOTHERS_LNAME as materno, p.FNAME as nombre, esp.NAME as especialidad, categ.NAME as categoria
			from inst i
			left outer join PERS_SREP_WORK psw on psw.inst_snr = i.INST_SNR
			left outer join PERSLOCWORK plw on plw.inst_snr = i.INST_SNR
			left outer join PERSON p on plw.PERS_SNR = p.PERS_SNR
			left outer join CODELIST esp on p.SPEC_SNR = esp.CLIST_SNR
			left outer join CODELIST categ on p.category_snr = categ.CLIST_SNR
			where psw.REC_STAT = 0
			and plw.REC_STAT = 0
			and i.inst_snr <> '00000000-0000-0000-0000-000000000000'
			and plw.pwork_SNR = psw.PWORK_SNR
			and i.REC_STAT = 0
			and i.inst_snr = '".$id."'";

		//echo $queryInstitucionesPersonas;
			
		$rsInstitucionesPersonas = sqlsrv_query($conn, $queryInstitucionesPersonas);
		
		/**********planes********/
		$queryPlanes = "select PLAN_DATE as Fecha_Plan,
			vp.TIME as Hora_Plan, u.user_nr,
			u.lname+' '+u.fname as Rep, 
			cd.name as Tipo_Plan,vp.info as Objetivo, 
			vp.visinstplan_snr,vp.user_snr,vp.inst_snr, 
			(select info from visitinst where visitinst.inst_snr=vp.inst_snr and visit_date in (select max(visit_date) from visitinst))  info_Ult_Vis, 
			substring(c.NAME, 1, 4) +  substring(c.NAME, 8, 3) as ciclo
			from visinstplan vp
			inner join users u on vp.user_snr=u.user_snr 
			left outer join codelist cd on cd.clist_snr = vp.PLAN_CODE_SNR
			left outer join cycles c on vp.PLAN_DATE between c.START_DATE and c.FINISH_DATE
			where vp.rec_stat=0 ";
			if($tipoUsuario == 4){
				$queryPlanes .= "and u.user_snr = '".$idUser."' ";
			}else{
				$queryPlanes .= "and u.user_snr in ('".$idUser."','".$repre."') ";
			}
			$queryPlanes .= "and vp.INST_SNR = '".$id."' 
			order by fecha_plan desc ";
			
			
		//echo $queryPlanes;
		$rsPlanes = sqlsrv_query($conn, $queryPlanes);
			
		$queryCiclos = "select substring(ciclos.NAME,1,4) anio, 
				substring(ciclos.NAME,6,2) ciclo, 
				count(*) total 
				from visinstplan vp, cycles ciclos 
				where vp.PLAN_DATE between ciclos.start_date and ciclos.finish_date 
				and vp.rec_stat=0 ";
				if($tipoUsuario == 4){
					$queryCiclos .= "and vp.user_snr = '".$idUser."' ";
				}else{
					$queryCiclos .= "and vp.user_snr in ('".$idUser."','".$repre."') ";
				}
				$queryCiclos .= "and vp.inst_snr='".$id."' 
				and substring(ciclos.NAME,1,4) = (select max(substring(NAME,1,4)) from cycles) 
				group by ciclos.NAME";
		
		//echo $queryCiclos;
				
		$rsCiclos = sqlsrv_query($conn, $queryCiclos);
		/***************** fin planes ***********************/
		
		/******************visitas**************************/
		$queryVisitas = "select visit_date as Fecha_Vis,
			TIME,u.lname + ' ' + u.fname as Rep, u.user_nr,
			codigo_vis.name as Tipo_Vis,
			vp.info as informacion_vis,
			vp.info_nextvisit as obj_vis,
			vp.visinst_snr,vp.user_snr,
			vp.inst_snr,
			(select info from visinstplan where visinstplan.inst_snr=vp.inst_snr and PLAN_DATE in (select max(PLAN_DATE) from visinstplan)) info_Ult_Plan,
			substring(c.NAME, 1, 4) + substring(c.NAME, 8, 3) as ciclo
			from visitinst vp
			inner join users u on u.USER_SNR = vp.USER_SNR
			inner join codelist codigo_vis on codigo_vis.clist_snr=vp.VISIT_CODE_SNR 
			left outer join cycles c on vp.visit_date between c.START_DATE and c.FINISH_DATE 
			where vp.rec_stat=0 ";
			if($tipoUsuario == 4){
				$queryVisitas .= "and  vp.user_snr = '".$idUser."' ";
			}else{
				$queryVisitas .= "and  vp.user_snr in ('".$idUser."','".$repre."') ";
			}
			$queryVisitas .= "and vp.inst_snr = '".$id."' 
			order by vp.visit_date desc";
		
		//echo $queryVisitas;
		
		$rsVisitas = sqlsrv_query($conn, $queryVisitas);
		
		$queryCiclosVisitas = "select substring(ciclos.NAME,1,4) anio, 
            substring(ciclos.NAME,6,2) ciclo, 
            count(*) total 
            from visitinst vp, cycles ciclos 
            where vp.visit_date between ciclos.start_date and ciclos.finish_date 
            and vp.rec_stat=0 
            and vp.inst_snr='".$id."' ";
			if($tipoUsuario == 4){
				$queryCiclosVisitas .= "and vp.user_snr='".$idUser."' ";
			}else{
				$queryCiclosVisitas .= "and vp.user_snr in ('".$idUser."','".$repre."') ";
			}
			$queryCiclosVisitas .= "and substring(ciclos.NAME,1,4) = (select max(substring(NAME,1,4)) from cycles) 
			group by ciclos.NAME ";
		
		//echo $queryCiclosVisitas;
		
		$rsCiclosVisitas = sqlsrv_query($conn, $queryCiclosVisitas);
		
		echo "<script>
				$('#hdnIdInst').val('".$id."');
				$('#hdnIdTipoInst').val('".$reg['idTipo']."');
				$('#hdnIdRutaDatosInst').val('".$repre."');
				$('#txtLatitudInstituciones').val('".$reg['LATITUDE']."');
				$('#txtLongitudInstituciones').val('".$reg['LONGITUDE']."');
				$('#lblInstTipo').text('".$reg['name']." - ".$reg['tipo']."');
				$('#lblNombreInst').text('".$reg['name']."');
				$('#lblTipoInst').text('".$reg['tipo']."');
				$('#lblTipoInstPlan').text('".$reg['tipo']."');
				$('#lblTipoInstVisita').text('".$reg['tipo']."');
				$('#lblTipoInst2').text('".$reg['tipo']."');
				$('#lblDireccionInst').text('".$reg['direccion']."');
				$('#lblTelefonoInst').text('".$reg['TEL1']."');
				$('#lblFaxInst').text('".$reg['TEL2']."');
				$('#lblMailInst').text('".$reg['EMAIL']."');
				$('#lblPaginaInst').text('".$reg['HTTP']."');
				$('#tblPlanesInstituciones tbody').empty();
				$('#tblVisitasInst tbody').empty();
				$('#tblPersonasInstituciones tbody').empty();
				$('#tblPersonasInstituciones tfoot').empty();
				$('#lblLatitudInst').text('".$reg['LATITUDE']."');
				$('#lblLongitudInst').text('".$reg['LONGITUDE']."');
				$('#lblFrecPlanInst').text('".$reg['FRECVIS_SNR']."');
				$('#lblFrecVisitaInst').text('".$reg['FRECVIS_SNR']."');
				";

		$contador = 0;
		$sinDatos = 'Sin datos que mostrar';
		while($institucionPersona = sqlsrv_fetch_array($rsInstitucionesPersonas)){
			$contador++;
				echo "$('#tblPersonasInstituciones tbody').append('<tr><td style=\"width:7%;\">".$contador."</td><td style=\"width:20%;\">".utf8_encode($institucionPersona['paterno'])."</td><td style=\"width:20%;\">".utf8_encode($institucionPersona['materno'])."</td><td style=\"width:25%;\">".utf8_encode($institucionPersona['nombre'])."</td><td style=\"width:20%;\">".$institucionPersona['especialidad']."</td><td style=\"width:8%;\">".$institucionPersona['categoria']."</td></tr>');";
		}
		
		if($contador == 0){
			echo "$('#tblPersonasInstituciones tbody').append('<tr><td style=\"width:100%;\">".$sinDatos."</td></tr>');";
			echo "$('#tblPersonasInstituciones tfoot').append('<tr><td>Registros: 0</td></tr>');";
		}else{
			echo "$('#tblPersonasInstituciones tfoot').append('<tr><td>Registros: '+ $('#tblPersonasInstituciones tbody tr').length +'</td></tr>');";

		}

		$contadorPlan = 0;
		while($plan = sqlsrv_fetch_array($rsPlanes)){
			$contadorPlan++;
			foreach ($plan['Fecha_Plan'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha_plan = substr($val, 0, 10);
				}
			}
			//echo "$('#tblPlanesInstituciones tbody').append('<tr onClick=\"muestraPlanInst(\'".$plan['visinstplan_snr']."\');\"><td style=\"width:15%;\">".$fecha_plan."</td><td style=\"width:10%;\">".$plan['Hora_Plan']."</td><td style=\"width:25%;\">".utf8_encode($plan['Rep'])."</td><td style=\"width:25%;\">".utf8_encode($plan['Tipo_Plan'])."</td><td style=\"width:15%;\">".$plan['Objetivo']."</td><td style=\"width:10%;\">".$plan['ciclo']."</td></tr>');";
			echo "$('#tblPlanesInstituciones tbody').append('<tr onClick=\"muestraPlanInst(\'".$plan['visinstplan_snr']."\');\"><td style=\"width:15%;\">".$plan['ciclo']."</td><td style=\"width:10%;\">".$plan['user_nr']."</td><td style=\"width:15%;\">".$fecha_plan."</td><td style=\"width:15%;\">".$plan['Hora_Plan']."</td><td style=\"width:45%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($plan['Objetivo']))."</td></tr>');";

		}

		if($contadorPlan == 0){
			echo "$('#tblPlanesInstituciones tbody').append('<tr><td style=\"width:100%;\">".$sinDatos."</td></tr>');";
		}

		for($i=1;$i<14;$i++){
			echo "$('#cicloInst'+".$i.").text('0');";
		}
		$total = 0;
		while($ciclo = sqlsrv_fetch_array($rsCiclos)){
			echo "$('#cicloInst'+".ltrim($ciclo['ciclo'], '0').").text('".$ciclo['total']."');";
			$total += $ciclo['total'];
		}
		echo "$('#acumuladoInst').text('".$total."');";
		//$visitas = '';

		$contadorVisita = 0;
		while($visita = sqlsrv_fetch_array($rsVisitas)){
			$contadorVisita++;
			foreach ($visita['Fecha_Vis'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha_vis = substr($val, 0, 10);
				}
			}
			//echo "$('#tblVisitasInst tbody').append('<tr onClick=\"muestraVisitaInst(\'".$visita['visinst_snr']."\');\"><td style=\"width:15%;\">".$fecha_vis."</td><td style=\"width:10%;\">".$visita['TIME']."</td><td style=\"width:35%;\">".$visita['Rep']."</td><td style=\"width:30%;\">".utf8_encode($visita['Tipo_Vis'])."</td><td style=\"width:10%;\">".$visita['ciclo']."</td></tr>');";
			echo "$('#tblVisitasInst tbody').append('<tr onClick=\"muestraVisitaInst(\'".$visita['visinst_snr']."\');\"><td style=\"width:11%;\">".$visita['ciclo']."</td><td style=\"width:8%;\">".$visita['user_nr']."</td><td style=\"width:13%;\">".$fecha_vis."</td><td style=\"width:9%;\">".$visita['TIME']."</td><td style=\"width:19%;\">".utf8_encode($visita['Tipo_Vis'])."</td><td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($visita['informacion_vis']))."</td><td style=\"width:20%;\">".str_ireplace($buscar,$reemplazar,utf8_encode($visita['obj_vis']))."</td></tr>');";
		}
		if($contadorVisita == 0){
			echo "$('#tblVisitasInst tbody').append('<tr><td style=\"width:100%;\">".$sinDatos."</td></tr>');";
		}
	
		$totalVisitas = 0;
		while($cicloVisita = sqlsrv_fetch_array($rsCiclosVisitas)){
			echo "$('#cicloVisitaInst'+".ltrim($cicloVisita['ciclo'], '0').").text('".$cicloVisita['total']."');";
			$totalVisitas += $cicloVisita['total'];
		}
		echo "$('#cicloVisitasAcumuladoInst').text('".$totalVisitas."');";
		echo "$('#hdnIdInst').val('".$id."');";
		if($reg['tipo'] == 'FARMACIAS'){
			echo "$('#liServicios').hide();";
		}else{
			echo "$('#liServicios').show();";
		}
			
		echo "$('#tblDepartamentos tbody').empty();
			$('#tblPersonasDepartamentoInstituciones tbody').empty();
				$('#hdnIDepto').val('');
				$('#lblNombreDepto').text('');";
	
		$queryDepartamento = "select d.depart_snr, d.name, d.street2, d.street1, c.name as colonia, d.tel1 from depart d, city c where INST_SNR = '".$id."' and c.city_snr = d.city_snr and d.rec_stat = 0 order by d.name";
		$rsDepartamentos = sqlsrv_query($conn, $queryDepartamento);
		$trDepto = 1;

		$contadorDepto = 0;
		while($depto = sqlsrv_fetch_array($rsDepartamentos)){
			$contadorDepto++;
			echo "$('#tblDepartamentos tbody').append('<tr id=\"trDepto".$trDepto."\" ><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" style=\"width:18%;\">".$depto['name']."</td><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" style=\"width:17%;\">".$depto['street2']."</td><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\"  style=\"width:15%;\">".$depto['street1']."</td><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\" style=\"width:15%;\">".$depto['colonia']."</td><td onClick=\"seleccionarDepto(\'".$depto['depart_snr']."\',\'".$depto['name']."\',".$trDepto.");\"  style=\"width:15%;\">".$depto['tel1']."</td><td style=\"width:10%;\"><img src=\"iconos/editar.png\" width=\"20px\" onClick=\"editarDepart(\'".$depto['depart_snr']."\')\"; /></td><td style=\"width:10%;\"><img src=\"iconos/eliminar.png\" width=\"20px\" onClick=\"eliminarDepart(\'".$depto['depart_snr']."\')\"; /></td></tr>');";
			$trDepto++;
		}
		if($contadorDepto == 0){
			echo "$('#tblDepartamentos tbody').append('<tr><td style=\"width:100%;\">".$sinDatos."</td></tr>');";
		}

		echo "$('#divCapa3').hide();
			$('#divDepartamento').hide();
		</script>";
		//echo $queryDepartamento;
	}
?>