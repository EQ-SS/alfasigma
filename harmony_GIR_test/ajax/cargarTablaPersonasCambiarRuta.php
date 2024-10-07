<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$registrosPorPagina = 5;
		$numPagina = $_POST['pagina'];
		$hoy = $_POST['hoy'];
		$ids = str_replace(",","','",$_POST['ids']);
		$ids = str_replace("'',''","','",$ids);
		$visitados = $_POST['visitados'];
		$seleccionadosCambiaRuta = explode(",",substr($_POST['seleccionadosCambiaRuta'],0,-1));
		//echo "ids: ".$ids."<br>";
		echo "<script>$('#hdnFiltrosExportar').val('".$visitados."');</script>";
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
		if(isset($_POST['colonia']) && $_POST['colonia'] != '' ){
			$colonia = $_POST['colonia'];
		}else{
			$colonia = '';
		}
		if(isset($_POST['cp']) && $_POST['cp'] != '' ){
			$cp = $_POST['cp'];
		}else{
			$cp = '';
		}
		if(isset($_POST['brick']) && $_POST['brick'] != '' ){
			$brick = $_POST['brick'];
		}else{
			$brick = '';
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
		
		if(isset($_POST['tipoMedico']) && $_POST['tipoMedico'] != '' && $_POST['tipoMedico'] != '00000000-0000-0000-0000-000000000000'){
			$tipoMedico = $_POST['tipoMedico'];
		}else{
			$tipoMedico = '';
		}
		
		if(isset($_POST['frecuencia']) && $_POST['frecuencia'] != '' && $_POST['frecuencia'] != '00000000-0000-0000-0000-000000000000'){
			$frecuencia = $_POST['frecuencia'];
		}else{
			$frecuencia = '';
		}
		
		//echo "repre: ".$repre."<br>";
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
				
		$queryPersonas = "select p.pers_snr, p.LNAME, p.MOTHERS_LNAME, p.FNAME, c.NAME as especialidad, 
				categ.name as categoria, i.NAME as institucion, i.STREET1 as calle,
				city.name as colonia, d.NAME as delegacion, state.NAME as estado,
				bri.name as brick, freq.name as freq, city.zip as cp,
				(SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE
				AND vp.user_snr in ('".$ids."')) as visitas,
				u.user_nr as ruta
				from person p
				inner join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR
				inner join PERSLOCWORK plw on plw.pwork_snr = psw.pwork_snr
				inner join inst i on i.INST_SNR = psw.INST_SNR
				inner join users u on u.user_snr = psw.user_snr
				inner join user_territ ut on ut.inst_snr=i.inst_snr and ut.user_snr=u.user_snr
				left outer join city on city.CITY_SNR = i.CITY_SNR
				left outer join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
				left outer join STATE on state.STATE_SNR = city.STATE_SNR
				left outer join BRICK bri on bri.BRICK_SNR = city.BRICK_SNR
				left outer join CODELIST c on p.SPEC_SNR = c.CLIST_SNR
				left outer join CODELIST categ on p.CATEGORY_SNR = categ.CLIST_SNR
				left outer join CODELIST freq on freq.CLIST_SNR = p.FRECVIS_SNR 
				left outer join CODELIST estatus on estatus.CLIST_SNR = p.STATUS_SNR 
				where psw.USER_SNR in ('".$ids."')
				and p.REC_STAT = 0
				and psw.REC_STAT = 0
				and c.REC_STAT = 0
				and categ.REC_STAT = 0
				and i.REC_STAT = 0
				and plw.rec_stat=0
				and ut.rec_stat=0
				and city.REC_STAT = 0
				and d.REC_STAT = 0
				and STATE.REC_STAT = 0
				and bri.REC_STAT = 0 
				and estatus.NAME = 'ACTIVO' ";
				
		if($visitados == 'visitados'){
			$queryPersonas .= " and (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE
				AND vp.user_snr in ('".$ids."')) > 0 ";
		}else if($visitados == 'no'){
			$queryPersonas .= " and (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE
				AND vp.user_snr in ('".$ids."')) = 0 ";
		}else if($visitados == 're'){
			$queryPersonas .= " and (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE
				AND vp.user_snr in ('".$ids."')) > 1 ";
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
		/*if($sexo != ''){
			$queryPersonas .= " and p.sex_snr = '".$sexo."' ";
		}*/
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
		if($colonia != ''){
			$queryPersonas .= " and city.name like '%".$colonia."%' ";
		}
		if($cp != ''){
			$queryPersonas .= " and city.zip = '".$cp."' ";
		}
		if($brick != ''){
			$queryPersonas .= " and bri.name = '".$brick."' ";
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
		if($tipoMedico != ''){
			$queryPersonas .= " and p.perstype_snr  = '".$tipoMedico."' ";
		}
		if($frecuencia != ''){
			$queryPersonas .= " and p.frecvis_snr  = '".$frecuencia."' ";
		}
		
		$queryPersonas .= " order by lname, mothers_lname, fname ";
		
		//echo $queryPersonas;
		
				
		$tope = "OFFSET ".$registroIni." ROWS 
				FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
					
		$rsPersonas = sqlsrv_query($conn, $queryPersonas, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
		$totalRegistros = sqlsrv_num_rows($rsPersonas);

		echo "<div class='col-white m-t--20 m-b-20'>
				<p style='position:absolute; top:20px;'>
					<span class='font-bold'>Total de registros: </span>".$totalRegistros."
				</p>
			</div>";
			
		$queryPersonas20 = sqlsrv_query($conn, $queryPersonas.$tope);
			
		$paginas = ceil($totalRegistros / $registrosPorPagina);
		
		$tabla = '<table class="table table-striped table-hover margin-0">
		<thead>
			
		</thead>
		
		<tbody class="listaMedicos">';

					$i = 1;
					$idCont = 0;

					while($persona = sqlsrv_fetch_array($queryPersonas20)){
						$nombre = utf8_encode($persona['LNAME']." ".$persona['MOTHERS_LNAME']." ".$persona['FNAME']);
						$cargo = utf8_encode($persona['especialidad']);
						//echo "freq: ".$persona['freq']." ::: ".$persona['visitas']."<br>";
						if($persona['freq'] == $persona['visitas'] && $persona['visitas'] > 0){
							$circulo = 'circuloVerde';
						}else if($persona['visitas'] == 0){
							$circulo = 'circuloRojo';
						}else if($persona['visitas'] > 0 && $persona['visitas'] < $persona['freq']){
							$circulo = 'circuloAmarillo';
						}else{
							$circulo = 'circuloRojo';
						}
						$datosMedico = "<b>".$nombre."</b><br>".$cargo."<br>";
						$datosMedico .= $persona['institucion']." CALLE: ".$persona['calle'].", ";
						$datosMedico .= "C.P.: ".$persona['cp'].", COLONIA: ".$persona['colonia'].", ";
						$datosMedico .= "POBLACIÓN: ".$persona['delegacion'].", ";
						$datosMedico .= "ESTADO: ".$persona['estado'].".<br>";
						$datosMedico .= "BRICK: ".$persona['brick'];

						$idCont++;

						$tabla .= "<tr id='trmedR".$idCont."' onClick='enviaIdTr(this.id);'>
						<td>
							<div class='row'>
								<div id='medR".$idCont."' name='medicoLista' class='col-lg-12 col-md-12 col-sm-12 col-xs-12 pointer margin-0' onclick='presentaDatos(\"medR".$idCont."\",\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
									<div class='row'>
										<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA font-bold'>
											".$nombre."
										</div>
										<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 margin-0'>";
											if(in_array($persona['pers_snr'], $seleccionadosCambiaRuta)){
												$tabla .= "<input onClick=\"seleccionaCambiarRutaPersona('".$persona['pers_snr']."','chkCambiarRutaPersona".$i."');\" type=\"checkbox\" class=\"filled-in chk-col-indigo\" id=\"chkCambiarRutaPersona".$i."\" checked /><label for=\"chkCambiarRutaPersona".$i."\" ></label>";
											}else{
												$tabla .= "<input onClick=\"seleccionaCambiarRutaPersona('".$persona['pers_snr']."','chkCambiarRutaPersona".$i."');\" type=\"checkbox\" class=\"filled-in chk-col-indigo\" id=\"chkCambiarRutaPersona".$i."\" /><label for=\"chkCambiarRutaPersona".$i."\" ></label>";
											}														
							$tabla .= "</div>
									</div>
									<div class='row'>
										<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0'>
											<div class='".$circulo."'></div>
										</div>
										<div class='col-lg-5 col-md-5 col-sm-5 col-xs-5 text-overflowA margin-0'>
											".utf8_encode($persona['especialidad'])."
										</div>
										<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 margin-0'>
											".$persona['categoria']."
										</div>
										<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 margin-0'>
										".$persona['ruta']."
										</div>
									</div>
								</div>								
							</div>
						</td>
						</tr>";

						$i++;
					}
					
					$tabla .= '</tbody>';
					if($totalRegistros > $registrosPorPagina){
						$tabla .= "<tfoot><tr><td class='align-center'>";
						$tabla .= "<ul class='pagination margin-0'>";
						$idsEnviar = str_replace("'","",$ids);
						if($numPagina > 1){
							$anterior = $numPagina - 1;
							$tabla .= "<li><a href='#' class='waves-effect font-14' onClick='nuevaPagina(1,\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>inicio</a></li>";
							$tabla .= "<li><a href='#' class='waves-effect font-14' onClick='nuevaPagina(".$anterior.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>Anterior</a></li>";
						}
						$antes = $numPagina-5;
						$despues = $numPagina+5;
						for($i=1;$i<=$paginas;$i++){
							if($i == $numPagina){
								$tabla .= "<li class='active'><a>".$i."</a></li>";
							}else{
								if($i > $despues || $i < $antes){
									//$tabla .= "<a href='#' onClick='nuevaPagina(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>".$i."</a>&nbsp;&nbsp;";
								}
							}
						}
						if($numPagina < $paginas){
							$siguiente = $numPagina + 1;
	
							$tabla .= "<li><a href='#' class='waves-effect font-14' onClick='nuevaPagina(".$siguiente.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>Siguiente</a></li>";
							$tabla .= "<li><a href='#' class='waves-effect font-14' onClick='nuevaPagina(".$paginas.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>Fin</a></li>";
							
						}
						$tabla .= "</ul>";
	
						
	
						$tabla .= "<p class='margin-0 font-12'>Pag. ".$numPagina." de ".$paginas;
						$tabla .= "</p></td></tr></tfoot>";
					}else{
						$tabla .= "<tfoot class='listaMedicosTfoot'><tr><td class='align-center'>";
						$tabla .= "</td></tr></tfoot>";
					}
					$tabla .= "</table>";
					echo $tabla;
	}
	
?>