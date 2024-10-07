<?php
	include ("../conexion.php");
	
	/*ids:ids,rutaNueva:rutaNueva,idsInsts:idsInsts,
	tipo:tipo,nombre:nombre,calle:calle,
	colonia:colonia,ciudad:ciudad,estado:estado,cp:cp,
	geolocalizados:geolocalizados,
	repre:repre,tabActivo:tabActivo*/
	
	$idsInsts = $_POST['idsInsts'];
	$ids = $_POST['ids'];
	$rutaNueva = $_POST['rutaNueva'];
	
	//echo "idInsts: ".$idsInsts."<br>";
	
	if($idsInsts == ''){
	
		if(isset($_POST['visitados']) && $_POST['visitados'] != ''){
			$visitados = $_POST['visitados'];
		}else{
			$visitados = '';
		}
		
		if(isset($_POST['tipo']) && $_POST['tipo'] != '' && $_POST['tipo'] != '00000000-0000-0000-0000-000000000000'){
			$tipo = $_POST['tipo'];
		}else{
			$tipo = '';
		}
		if(isset($_POST['nombre']) && $_POST['nombre'] != ''){
			$nombre = $_POST['nombre'];
		}else{
			$nombre = '';
		}
		if(isset($_POST['calle']) && $_POST['calle'] != '' ){
			$calle = $_POST['calle'];
		}else{
			$calle = '';
		}
		if(isset($_POST['colonia']) && $_POST['colonia'] != '' && $_POST['colonia'] != '00000000-0000-0000-0000-000000000000'){
			$colonia = $_POST['colonia'];
		}else{
			$colonia = '';
		}
		if(isset($_POST['ciudad']) && $_POST['ciudad'] != '' ){
			$ciudad = $_POST['ciudad'];
		}else{
			$ciudad = '';
		}
		if(isset($_POST['estado']) && $_POST['estado'] != '' ){
			$estado = $_POST['estado'];
		}else{
			$estado = '';
		}
		if(isset($_POST['cp']) && $_POST['cp'] != '' ){
			$cp = $_POST['cp'];
		}else{
			$cp = '';
		}
		if(isset($_POST['geolocalizados']) && $_POST['geolocalizados'] != '' ){
			$geolocalizados = $_POST['geolocalizados'];
		}else{
			$geolocalizados = '';
		}
		if(isset($_POST['tabActivo']) && $_POST['tabActivo'] != '' && $_POST['tabActivo'] != '0'){
			$tabActivo = $_POST['tabActivo'];
		}else{
			$tabActivo = '';
		}
		if(isset($_POST['repre']) && $_POST['repre'] != ''){
			$repre = str_replace(",","','",substr($_POST['repre'], 0, -1));
		}else{
			$repre = '';
		}
		
		$queryInst = "select i.inst_snr, ut.user_snr
			from inst i 
			inner join USER_TERRIT ut on i.inst_snr = ut.ter_SNR 
			LEFT OUTER join city on city.CITY_SNR = i.CITY_SNR 
			LEFT OUTER join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR 
			LEFT OUTER join STATE on state.STATE_SNR = city.STATE_SNR 
			LEFT OUTER join IMS_BRICK bri on bri.IMSBRICK_SNR = city.IMSBRICK_SNR 
			LEFT OUTER join kommloc k on k.kloc_snr = ut.user_snr
			where ut.USER_SNR in ('".$ids."')
			and  i.REC_STAT = 0 
			and ut.REC_STAT = 0 
			and city.REC_STAT = 0 
			and d.REC_STAT = 0 
			and STATE.REC_STAT = 0 
			and bri.REC_STAT = 0  ";
					
		if($visitados == 'visitados'){
			$queryInst .= " and (SELECT COUNT(*) FROM VISITINST VI, CYCLES CICLOS 
				WHERE VI.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VI.INST_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) > 0 ";
		}else if($visitados == 'no'){
			$queryInst .= " and (SELECT COUNT(*) FROM VISITINST VI, CYCLES CICLOS 
				WHERE VI.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VI.INST_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) = 0 ";
		}else if($visitados == 're'){
			$queryInst .= " and (SELECT COUNT(*) FROM VISITINST VI, CYCLES CICLOS 
				WHERE VI.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VI.INST_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) > 1 ";
		}
		
		if($tipo != ''){
			$queryInst .= " and I.INST_TYPE = '".$tipo."' ";
		}
		if($nombre != ''){
			$queryInst .= " and I.NAME like '%".$nombre."%' ";
		}
		if($calle != '' ){
			$queryInst .= " and I.STREET1 like '%".$calle."%' ";
		}
		if($colonia != ''){
			$queryInst .= " and CITY.NAME like '%".$colonia."%' ";
		}
		if($ciudad != ''){
			$queryInst .= " and D.NAME like '%".$ciudad."%' ";
		}
		if($estado != '' ){
			$queryInst .= " and STATE.name like '%".$estado."%' ";
		}
		if($cp != '' ){
			$queryInst .= " and CITY.ZIP = '".$cp."' ";
		}
		
		if($geolocalizados != '' ){
			$queryInst .= " and I.LATITUDE > 0 ";
		}
		
		if($tabActivo != '' ){
			$queryInst .= " and I.INST_TYPE = '".$tabActivo."' ";
		}
		
		if($repre != ''){
			$queryInst .= " and ut.USER_SNR in ('".$repre."')";
		}
	
		//echo "150: ".$queryInst."<br>";;
		
		$rsInst = sqlsrv_query($conn, $queryInst);
	
		$idsInsts = '';
		$rutaAnteriorInst = '';
	
		while($regInst = sqlsrv_fetch_array($rsInst)){
			$idsInsts .= $regInst['inst_snr'].",";
			$rutaAnteriorInst .= $regInst['user_snr'].",";
		}	
		
		$idsInsts = substr($idsInsts, 0, -1);
		
	}else{
		$idsInsts = substr($idsInsts, 0, -1);
	}
	
	//echo "ids: ".$ids."<br>";
	$arridsInsts = explode(",", $idsInsts);
	
	for($insti = 0; $insti < count($arridsInsts); $insti++){
		/****recuperar las personas de la institucion */
		
		$qPersonas = "select pers_snr from pers_srep_work where rec_stat = 0 and inst_snr = '".$arridsInsts[$insti]."'";
		//echo "173: ".$qPersonas."<br>";
		$rsPersonas = sqlsrv_query($conn, $qPersonas);
		
		$ids = '';
		
		while($regPersonas = sqlsrv_fetch_array($rsPersonas)){
			$ids .= $regPersonas['pers_snr'].",";
		}
		if($ids != ''){
			$ids = substr($ids, 0, -1);
			
			$arrIds = explode(",",$ids);
			
			for($i=0;$i<count($arrIds);$i++){
				$queryRutaAnterior = "select  psw.PERSREP_SNR, psw.USER_SNR, psw.INST_SNR, psw.PERS_SNR, psw.PWORK_SNR  ";
				$queryRutaAnterior .= "from person p, pers_srep_work psw ";
				$queryRutaAnterior .= "where p.pers_snr = '".$arrIds[$i]."' ";
				$queryRutaAnterior .= "and p.rec_stat = 0 ";
				$queryRutaAnterior .= "and p.pers_snr = psw.pers_snr ";
				$queryRutaAnterior .= "and psw.rec_stat = 0 ";
				
				//echo "query: ".$queryRutaAnterior;
				
				$rsRutaAnterior = sqlsrv_query($conn, $queryRutaAnterior, array(), array( "Scrollable" => 'static' ));
				
				while($row = sqlsrv_fetch_array($rsRutaAnterior, SQLSRV_FETCH_ASSOC)){
					$persrep_snr = $row['PERSREP_SNR'];
					$user_snr = $row['USER_SNR'];
					$inst_snr = $row['LOC_SNR'];
					$pers_snr = $row['PERS_SNR'];
					$pwork_snr = $row['PWORK_SNR'];
					$rutaAnterior = $row['USER_SNR'];
				}
				
				$queryDesactivarRutaAnterior = "update pers_srep_work set rec_stat = '2622', sync = 0 where persrep_snr = '".$persrep_snr."' and rec_stat = 0 ";
				
				$queryValidarPers_Srep_Work = "select * from pers_srep_work ";
				$queryValidarPers_Srep_Work .= "where user_snr = '".$rutaNueva."' ";
				$queryValidarPers_Srep_Work .= "and inst_snr = '".$inst_snr."' ";
				$queryValidarPers_Srep_Work .= "and pers_snr = '".$pers_snr."' ";
				$queryValidarPers_Srep_Work .= "and pwork_snr = '".$pwork_snr."' ";
				
				$existeRegistroPers_Srep_Work = sqlsrv_num_rows(sqlsrv_query($conn, $queryValidarPers_Srep_Work, array(), array( "Scrollable" => 'static' )));
				
				if($existeRegistroPers_Srep_Work > 0){////////existe
					$queryActivaRutaNueva = "update pers_srep_work set rec_stat = '0', sync = 0 where user_snr = '".$rutaNueva."' ";
					$queryActivaRutaNueva .= "and inst_snr = '".$inst_snr."' ";
					$queryActivaRutaNueva .= "and pers_snr = '".$pers_snr."' ";
					$queryActivaRutaNueva .= "and pwork_snr = '".$pwork_snr."' ";
				}else{
					$queryActivaRutaNueva = "insert into pers_srep_work (
						PERSREP_SNR, 
						PWORK_SNR, 
						USER_SNR, 
						PERS_SNR, 
						INST_SNR, 
						REC_STAT, 
						SYNC
						) values (
						NEWID(), 
						'".$pwork_snr."', 
						'".$rutaNueva."', 
						'".$pers_snr."', 
						'".$inst_snr."',
						0,
						0 
						)";
				}
				
				$queryDesativoUserTerritAnterior = "update user_territ set rec_stat = 2622, sync = 0 where ter_snr = '".$inst_snr."' and user_snr = '".$rutaAnterior."' and rec_stat = 0";
				
				$queryValidarUser_territ = "select * from user_territ ";
				$queryValidarUser_territ .= "where user_snr = '".$rutaNueva."' ";
				$queryValidarUser_territ .= "and ter_snr = '".$inst_snr."' ";
				
				$existeRegistroUser_territ = sqlsrv_num_rows(sqlsrv_query($conn, $queryValidarUser_territ, array(), array( "Scrollable" => 'static' )));
				
				if($existeRegistroUser_territ > 0){////////existe
					$queryUserTerritNuevo = "update user_territ set rec_stat = 0, sync = 0 where user_snr = '".$rutaNueva."' and ter_snr = '".$inst_snr."' ";
				}else{
					$queryUserTerritNuevo = "insert into user_territ (
						UTER_SNR, 
						TER_SNR, 
						USER_SNR, 
						REC_STAT, 
						SYNC
					) values (	
						NEWID(), 
						'".$inst_snr."', 
						'".$rutaNueva."', 
						0, 
						0
					)";
				}
				
				if(! sqlsrv_query($conn, $queryDesactivarRutaAnterior)){
					echo $queryDesactivarRutaAnterior;
				}
				if (! sqlsrv_query($conn, $queryActivaRutaNueva)){
					echo $queryActivaRutaNueva;
				}
				if(! sqlsrv_query($conn, $queryDesativoUserTerritAnterior)){
					echo $queryDesativoUserTerritAnterior;
				}
				if(! sqlsrv_query($conn, $queryUserTerritNuevo)){
					echo $queryUserTerritNuevo;
				}
			}
			
			$queryUpdate = "update person set sync = 0 where pers_snr in ('".str_replace(",","','",$ids)."')";
			
			
			if(! sqlsrv_query($conn, $queryUpdate)){
				echo $queryUpdate;
			}
		}
		//else{//no tiene asociado ningun medico
			//inhabilitamos la ruta anterior
			sqlsrv_query($conn, "update USER_TERRIT set rec_stat = 2622, sync = 0 where INST_SNR = '".$arridsInsts[$insti]."' and REC_STAT = 0 ");
			//buscamos si ya existe ese regustro
			//echo "288: select * from USER_TERRIT where TER_SNR = '".$arridsInsts[$insti]."' and USER_SNR = '".$rutaNueva."'<br>";
			$idUT= sqlsrv_fetch_array(sqlsrv_query($conn, "select * from USER_TERRIT where INST_SNR = '".$arridsInsts[$insti]."' and USER_SNR = '".$rutaNueva."'"))['UTER_SNR'];
			
			//echo "select * from USER_TERRIT where TER_SNR = '".$arridsInsts[$insti]."' and USER_SNR = '".$rutaNueva."'";
			
			if($idUT){
				if(! sqlsrv_query($conn, "update USER_TERRIT set rec_stat = 0, sync = 0 where UTER_SNR = '".$idUT."' ")){
					echo "ut: ".$queryUserTerritNuevo."<br>";
				}
			}else{
				$queryUserTerritNuevo = "insert into user_territ (
						UTER_SNR, 
						INST_SNR, 
						USER_SNR, 
						REC_STAT, 
						SYNC
					) values (	
						NEWID(), 
						'".$arridsInsts[$insti]."', 
						'".$rutaNueva."', 
						0, 
						0
					)";
				if(! sqlsrv_query($conn, $queryUserTerritNuevo)){
					echo "ut: ".$queryUserTerritNuevo."<br>";
				}
			}
		//}
	}
	/*echo "<script>
		alert('Proceso terminado!!!')
		</script>";*/
		
	echo "<script>
		alertCambioRutaRealizado();
		var pagina = $('#hdnPaginaInst').val();
		var ids = $('#hdnIds').val();
		var hoy = $('#hdnHoy').val();
		nuevaPaginaInst(pagina,hoy,ids,'' );
		cerrarInformacion();
		$('#chkSeleccionarTodosCambiarRutaInst').prop('checked', false);
		</script>";
?>