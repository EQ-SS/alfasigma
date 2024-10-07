<?php
	include ("../conexion.php");
	
	$idsPersonas = $_POST['idsPersonas'];
	$ids = $_POST['ids'];
	$rutaNueva = $_POST['rutaNueva'];
	
	//echo "idPersonas: ".$idsPersonas."<br>";
	
	if($idsPersonas == ''){
	
		if(isset($_POST['visitados']) && $_POST['visitados'] != ''){
			$visitados = $_POST['visitados'];
		}else{
			$visitados = '';
		}
		
		if(isset($_POST['tipoPersona']) && $_POST['tipoPersona'] != '' && $_POST['tipoPersona'] != '00000000-0000-0000-0000-000000000000' && $_POST['tipoPersona'] != '0'){
			$tipoPersona = $_POST['tipoPersona'];
		}else{
			$tipoPersona = '';
		}
		if(isset($_POST['nombre']) && $_POST['nombre'] != ''){
			$nombre = $_POST['nombre'];
		}else{
			$nombre = '';
		}
		if(isset($_POST['apellidos']) && $_POST['apellidos'] != '' ){
			$apellidos = $_POST['apellidos'];
		}else{
			$apellidos = '';
		}
		if(isset($_POST['especialidad']) && $_POST['especialidad'] != '' && $_POST['especialidad'] != '00000000-0000-0000-0000-000000000000'){
			$especialidad = $_POST['especialidad'];
		}else{
			$especialidad = '';
		}
		if(isset($_POST['categoria']) && $_POST['categoria'] != '' && $_POST['categoria'] != '00000000-0000-0000-0000-000000000000'){
			$categoria = $_POST['categoria'];
		}else{
			$categoria = '';
		}
		if(isset($_POST['inst']) && $_POST['inst'] != '' ){
			$inst = $_POST['inst'];
		}else{
			$inst = '';
		}
		if(isset($_POST['dir']) && $_POST['dir'] != '' ){
			$dir = $_POST['dir'];
		}else{
			$dir = '';
		}
		if(isset($_POST['del']) && $_POST['del'] != '' ){
			$del = $_POST['del'];
		}else{
			$del = '';
		}
		if(isset($_POST['estado']) && $_POST['estado'] != '' ){
			$estado = $_POST['estado'];
		}else{
			$estado = '';
		}
		if(isset($_POST['repre']) && $_POST['repre'] != ''){
			$repre = str_replace(",","','",substr($_POST['repre'], 0, -1));
		}else{
			$repre = '';
		}
	
		$queryPersonas = "select p.pers_snr,  psw.user_snr
			from person p
			inner join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR
			inner join CODELIST c on p.SPEC_SNR = c.CLIST_SNR
			inner join CODELIST categ on p.CATEGORY_SNR = categ.CLIST_SNR
			inner join inst i on i.INST_SNR = psw.INST_SNR
			inner join city on city.CITY_SNR = i.CITY_SNR
			inner join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
			inner join STATE on state.STATE_SNR = city.STATE_SNR
			inner join BRICK bri on bri.BRICK_SNR = city.BRICK_SNR
			inner join CODELIST freq on freq.CLIST_SNR = p.TITEL_SNR
			inner join kommloc k on k.kloc_snr = psw.user_snr 
			where psw.USER_SNR in ('".$ids."')
			and p.REC_STAT = 0
			and psw.REC_STAT = 0
			and c.REC_STAT = 0
			and categ.REC_STAT = 0
			and i.REC_STAT = 0
			and city.REC_STAT = 0
			and d.REC_STAT = 0
			and STATE.REC_STAT = 0
			and bri.REC_STAT = 0 ";
					
		if($visitados == 'visitados'){
			$queryPersonas .= " and (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) > 0 ";
		}else if($visitados == 'no'){
			$queryPersonas .= " and (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) = 0 ";
		}else if($visitados == 're'){
			$queryPersonas .= " and (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) > 1 ";
		}
		
		if($tipoPersona != ''){
			$queryPersonas .= " and p.perstype_snr = '".$tipoPersona."' ";
		}
		if($nombre != ''){
			$queryPersonas .= " and p.fname like '%".$nombre."%' ";
		}
		if($apellidos != '' ){
			$queryPersonas .= " and p.lname + ' ' + mothers_lname like '%".$apellidos."%' ";
		}
		if($especialidad != ''){
			$queryPersonas .= " and p.spec_snr = '".$especialidad."' ";
		}
		if($categoria != ''){
			$queryPersonas .= " and p.category_snr = '".$categoria."' ";
		}
		if($inst != '' ){
			$queryPersonas .= " and i.name like '%".$inst."%' ";
		}
		if($dir != '' ){
			$queryPersonas .= " and i.street1 like '%".$dir."%' ";
		}
		if($del != '' ){
			$queryPersonas .= " and d.name like '%".$del."%' ";
		}
		if($estado != '' ){
			$queryPersonas .= " and state.name like '%".$estado."%' ";
		}
		if($repre != ''){
			$queryPersonas .= " and psw.USER_SNR in ('".$repre."')";
		}
	
		$queryPersonas .= " order by lname, mothers_lname, fname ";
		
		$rsPersonas = sqlsrv_query($conn, $queryPersonas);
	
		$ids = '';
		$rutaAnterior = '';
	
		while($regPersonas = sqlsrv_fetch_array($rsPersonas)){
			$ids .= $regPersonas['pers_snr'].",";
			$rutaAnterior .= $regPersonas['user_snr'].",";
		}	
		
		$ids = substr($ids, 0, -1);
		
	}else{
		$ids = substr($idsPersonas, 0, -1);
	}
	
	//echo "ids: ".$ids."<br>";
	
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
			$inst_snr = $row['INST_SNR'];
			$pers_snr = $row['PERS_SNR'];
			$pwork_snr = $row['PWORK_SNR'];
			$rutaAnterior = $row['USER_SNR'];
		}
		
		/**** enviar tablas a la nueva ruta *****/
		$queryActualizaPLW = "update perslocwork set sync = 0 where PWORK_SNR = '".$pwork_snr."' ";
		$queryActualizaInst = "update INST set sync = 0 where INST_SNR = '".$inst_snr."' ";
		
		$queryUD = "select * from person_ud where pers_snr = '".$pers_snr."' ";
		$numRegUD = sqlsrv_num_rows(sqlsrv_query($conn, $queryUD, array(), array( "Scrollable" => 'static' )));
		if($numRegUD > 0){////////existe
			$queryActualizaUD = "update person_ud set sync = 0 where pers_snr = '".$pers_snr."' ";
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
		
		$queryDesativoUserTerritAnterior = "update user_territ set rec_stat = 2622, sync = 0 where inst_snr = '".$inst_snr."' and user_snr = '".$rutaAnterior."' and rec_stat = 0";
		
		$queryValidarUser_territ = "select * from user_territ ";
		$queryValidarUser_territ .= "where user_snr = '".$rutaNueva."' ";
		$queryValidarUser_territ .= "and inst_snr = '".$inst_snr."' ";
		
		$existeRegistroUser_territ = sqlsrv_num_rows(sqlsrv_query($conn, $queryValidarUser_territ, array(), array( "Scrollable" => 'static' )));
		
		if($existeRegistroUser_territ > 0){////////existe
			$queryUserTerritNuevo = "update user_territ set rec_stat = 0, sync = 0 where user_snr = '".$rutaNueva."' and inst_snr = '".$inst_snr."' ";
		}else{
			$queryUserTerritNuevo = "insert into user_territ (
				UTER_SNR, 
				INST_SNR, 
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
		
		if(! sqlsrv_query($conn, $queryActualizaPLW)){
			echo $queryActualizaPLW;
		}
		
		if(! sqlsrv_query($conn, $queryActualizaInst)){
			echo $queryActualizaInst;
		}
		
		if(! sqlsrv_query($conn, $queryActualizaUD)){
			echo $queryActualizaUD;
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
	
	echo "<script>
		alertCambioRutaRealizado();
		for(var i=1;i<21;i++){
			$('#chkCambiarRutaPersona'+i).prop('checked', false);
		}
		cerrarInformacion();
		$('#imgPersonas').click();
		</script>";
	
?>