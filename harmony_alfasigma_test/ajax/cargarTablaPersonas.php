
<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idCont = 0;
		$registrosPorPagina = 5;
		$tipoUsuario = $_POST['tipoUsuario'];
		$numPagina = $_POST['pagina'];
		$hoy = $_POST['hoy'];
		$ids = str_replace(",","','",$_POST['ids']);
		$ids = str_replace("'',''","','",$ids);
		//echo "ids: ".$ids."<br>";
		$visitados = $_POST['visitados'];
		$rec_stat = $_POST['rec_stat'];
		echo "<script>$('#hdnFiltrosExportar').val('".$visitados."');</script>";
		if(isset($_POST['estatusPersona']) && $_POST['estatusPersona'] != '' && $_POST['estatusPersona'] != '00000000-0000-0000-0000-000000000000' && $_POST['estatusPersona'] != '0'){
			$estatusPersona = $_POST['estatusPersona'];
		}else{
			$estatusPersona = '';
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
		if(isset($_POST['nombreList']) && $_POST['nombreList'] != ''){
			$nombreList = $_POST['nombreList'];
		}else{
			$nombreList = '';
		}
		/*if(isset($_POST['sexo']) && $_POST['sexo'] != '' && $_POST['sexo'] != '00000000-0000-0000-0000-000000000000'){
			$sexo = $_POST['sexo'];
		}else{
			$sexo = '';
		}*/
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
		
		if(isset($_POST['motivoBaja']) && $_POST['motivoBaja'] != '' && $_POST['motivoBaja'] != '00000000-0000-0000-0000-000000000000' ){
			$motivoBaja = $_POST['motivoBaja'];
		}else{
			$motivoBaja = '';
		}

		if(isset($_POST['categoriaAlfaSigma']) && $_POST['categoriaAlfaSigma'] != '00000000-0000-0000-0000-000000000000'){
			$categoriaAlfaSigma = $_POST['categoriaAlfaSigma'];
		}else{
			$categoriaAlfaSigma = '';
		}

		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
		//echo $numPagina."-".$registrosPorPagina."-".$registrosPorPagina;
		
		$queryPersonas = "select p.pers_snr, p.LNAME,  p.MOTHERS_LNAME, p.FNAME, 
			p.AUTHORIZED_PRIVACY*1 as AUTHORIZED_PRIVACY, 
			c.NAME as especialidad, 
			categ.name as categoria, i.NAME as institucion, i.STREET1 as calle,
			city.name as colonia, d.NAME as delegacion, state.NAME as estado,
			bri.name as brick, freq.name as freq, city.zip as cp,
			(SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
			AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE
			AND vp.user_snr in ('".$ids."')) as visitas,
			u.lname as ruta, psw.USER_SNR, p.spec_snr, p.category_snr,ui.PERS_SNR as inversion, 
			p.rec_stat 
			from person p
			inner join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR 
			inner join PERSLOCWORK plw on plw.pwork_snr = psw.pwork_snr 
			inner join inst i on i.INST_SNR = psw.inst_snr 
			inner join users u on u.user_snr = psw.user_snr
			inner join user_territ ut on ut.inst_snr=i.inst_snr and ut.user_snr=u.user_snr
			left outer join city on city.CITY_SNR = i.CITY_SNR
			left outer join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
			left outer join STATE on state.STATE_SNR = city.STATE_SNR
			left outer join BRICK bri on bri.BRICK_SNR = city.BRICK_SNR
			left outer join CODELIST c on p.SPEC_SNR = c.CLIST_SNR
			left outer join CODELIST categ on p.category_snr  = categ.CLIST_SNR
			left outer join CODELIST freq on freq.CLIST_SNR = p.frecvis_snr
			left outer join (select distinct pers_snr from USER_INVESTMENT where REC_STAT = 0) ui on ui.PERS_SNR = p.PERS_SNR 
			left outer join person_ud pud on pud.pers_snr = p.pers_snr ";
				
		/*if($motivoBaja != ''){

			$queryPersonas .= "left outer join (select distinct p_pers_snr, p_movement_type, plw_del_status_snr, approved_status from person_approval pa, approval_status aps
			where pa.PERS_APPROVAL_SNR = aps.PERS_APPROVAL_SNR 
			and aps.REC_STAT = 0 and pa.REC_STAT = 0) pa on pa.p_pers_snr = p.pers_snr ";

		}*/
		
		$queryPersonas .= " where psw.USER_SNR in ('".$ids."') 
			AND P.REC_STAT = ".$rec_stat." ";
		
		/*if($motivoBaja == ''){
			$queryPersonas .= " AND P.REC_STAT=0 ";
		}*/
		
		/*if($motivoBaja != ''){
			$queryPersonas .= " and pa.p_movement_type = 'D' 
				and pa.approved_status = 2 
				and pa.plw_del_status_snr = '".$motivoBaja."' ";
		}*/
		
		$queryPersonas .= " and psw.REC_STAT = 0
				and c.REC_STAT = 0
				and categ.REC_STAT = 0
				and i.REC_STAT = 0
				and plw.rec_stat=0
				and ut.rec_stat=0
				and city.REC_STAT = 0
				and d.REC_STAT = 0
				and STATE.REC_STAT = 0
				and bri.REC_STAT = 0  ";
				
		//echo $queryPersonas;
				
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
		
		if($estatusPersona != ''){
			$queryPersonas .= " and p.STATUS_SNR = '".$estatusPersona."' ";
		}/*else{
			if($motivoBaja == ''){
				$queryPersonas .= " and p.STATUS_SNR = 'B426FB78-8498-4185-882D-E0DC381460E8' ";
			}
		}*/
		if($nombre != ''){
			$queryPersonas .= " and p.fname like '%".$nombre."%' ";
		}
		if($apellidos != '' ){
			$queryPersonas .= " and p.lname + ' ' + p.MOTHERS_LNAME like '%".$apellidos."%' ";
		}
		if($nombreList != '' ){
			$nombreListDiv = explode(' ', $nombreList);
			if(count($nombreListDiv)>1 && count($nombreListDiv)<3){$queryPersonas .= " and p.lname + ' ' + p.MOTHERS_LNAME + ' ' + p.fname like '%".$nombreListDiv[0]."%".$nombreListDiv[1]."%' ";}
			else{$queryPersonas .= " and p.lname + ' ' + p.MOTHERS_LNAME + ' ' + p.fname like '%".$nombreList."%' ";}
			//$queryPersonas .= " and p.lname + ' ' + p.MOTHERS_LNAME + ' ' + p.fname like '%".$nombreList."%' ";
		}
		/*if($sexo != ''){
			$queryPersonas .= " and p.sex_snr = '".$sexo."' ";
		}*/
		if($especialidad != ''){
			$queryPersonas .= " and p.spec_snr = '".$especialidad."' ";
		}
		if($categoria != ''){
			$queryPersonas .= " and p.category_snr  = '".$categoria."' ";
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
		if($categoriaAlfaSigma != ''){
			$queryPersonas .= " and pud.field_13_SNR = '".$categoriaAlfaSigma."' ";
		}
		
		$queryPersonas .= " order by lname, MOTHERS_LNAME, fname ";
		
		//echo $queryPersonas."<br>";
				
		$tope = "OFFSET ".$registroIni." ROWS 
				FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
	
		$rsPersonas = sqlsrv_query($conn, $queryPersonas, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
		$totalRegistros = sqlsrv_num_rows($rsPersonas);
		

		if($totalRegistros == 0){
			echo "<script>
					sinResultados = 1;
					$('#cardInfMedicos').hide();
					$('#sinResultadosMed').show();
			</script>";
		}
		else{
			echo "<script>
				sinResultados = 0;
				$('#cardInfMedicos').show();
				$('#sinResultadosMed').hide();
			</script>";
		}
		
		//echo $queryPersonas;
		echo "<div class='col-white m-t--20'>
				<p style='position:absolute; top:20px;'>
					<span class='font-bold'>Total de registros: </span>".$totalRegistros."
				</p>
			</div>";
		$queryPersonas20 = sqlsrv_query($conn, $queryPersonas.$tope);

		//echo $queryPersonas20.$tope;

		/*Primer médico*/
		$tope2 = "OFFSET 1 ROWS 
		FETCH NEXT 1 ROWS ONLY ";
		$queryPersonas1= sqlsrv_query($conn, $queryPersonas.$tope);
		$persona1 = sqlsrv_fetch_array($queryPersonas1);

		$nombre1 = $persona1['LNAME']." ".$persona1['MOTHERS_LNAME']." ".$persona1['FNAME'];
		$cargo1 = $persona1['especialidad'];
		
		echo "<script>
		idMed1 = '".$persona1['pers_snr']."';
		nombreMed1 = '".$nombre1."';
		especialidadMed1 = '".$cargo1."';	
		</script>";
		/*#END primer médico*/
			
		$paginas = ceil($totalRegistros / $registrosPorPagina);
		
		$tabla = '<table class="table table-striped table-hover margin-0" id="listamedicos">
					<thead>
						
					</thead>
					
					<tbody class="listaMedicos">';
					
					while($persona = sqlsrv_fetch_array($queryPersonas20)){
						$nombre = $persona['LNAME']." ".$persona['MOTHERS_LNAME']." ".$persona['FNAME'];
						$cargo = $persona['especialidad'];
						$ruta = explode(" ", $persona['ruta'])[0];
						//echo "freq: ".$persona['freq']." ::: ".$persona['visitas']."<br>";

						$queryFrecTipoCto="select CONTACT_TYPE_SNR as tipoContacto 
						from VISITPERS VP, CYCLES CICLOS, person p 
						where VP.REC_STAT=0 AND CICLOS.REC_STAT=0 
						and p.PERS_SNR=VP.pers_snr
						AND '".date("Y-m-d")."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE AND VISIT_DATE BETWEEN CICLOS.START_DATE 
						AND CICLOS.FINISH_DATE and vp.user_snr = '".$persona['USER_SNR']."'";
						//echo $queryFrecTipoCto;
						$rsFrecTipoCto= sqlsrv_query($conn, $queryFrecTipoCto);
						
						/* nuevo calculo de frecuencia */	
						$qfrecuencia = "select frec.name as frec
							from cycle_pers_categ_spec cpcs 
							inner join cycles c on c.cycle_snr = cpcs.cycle_snr 
							inner join CODELIST frec on frec.CLIST_SNR = cpcs.frecvis_snr
							where cpcs.rec_stat = 0 
							and '".date("Y-m-d")."' between c.start_date and c.finish_date 
							and spec_snr = '".$persona['spec_snr']."' 
							and category_snr = '".$persona['category_snr']."' ";
							
						//echo $qfrecuencia;
						
						$persona['freq'] = sqlsrv_fetch_array(sqlsrv_query($conn, $qfrecuencia))['frec'];

						if(($persona['freq'] == $persona['visitas'] && $persona['visitas'] > 0) || $persona['freq'] < $persona['visitas'] ){
							$circulo = 'circuloVerde';
						}
						if($persona['visitas'] == 0){
							$circulo = 'circuloRojo';
						}
						if($persona['visitas'] > 0 && $persona['visitas'] < $persona['freq']){
							$circulo = 'circuloAmarillo';
						}
						
						if($persona['AUTHORIZED_PRIVACY']){
							$iconoAviso = "&#xf09c;";
						}else{
							$iconoAviso = "&#xf023;";
						}
						//echo "vis: ".$persona['visitas']."<br>";
						$nombreMed = str_replace("'","",$nombre);
						$datosMedico = "<b>Consultorio: </b><span>".$persona['institucion']."</span><br>";
						$datosMedico .= "<b>Dirección: </b><span>".$persona['calle'].", ";
						$datosMedico .= $persona['cp'].", ";
						$datosMedico .= $persona['colonia'].", ";
						$datosMedico .= $persona['delegacion'].", ";
						$datosMedico .= $persona['estado']."</span>";
						//$datosMedico .= "BRICK: ".$persona['brick'];

						$idCont++;

						//$idCont+= $idCont * $numPagina;

					/*if($idCont == 1 && $numPagina == 1){
						$tabla .= "<tr id='trmed".$idCont."' onClick='enviaIdTr(this.id);' class='div-slt-lista'>";
					}else{*/
						$tabla .= "<tr id='trmed".$idCont."' onClick='enviaIdTr(this.id);'>";
					//}
					
					$tabla .="<td>
							<div class='row'>
								<div id='med".$idCont."' name='medicoLista' class='col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0' onClick='presentaDatos(\"med".$idCont."\",\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
									<div class='row'>
										<div class='col-lg-8 col-md-8 col-sm-8 col-xs-8 text-overflowA font-bold'>
											".$nombre."
										</div>";
										if($persona['inversion']){
											$tabla .="<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0'>
													<i class='fas fa-dollar-sign fa-lg' style='color: green;'></i>
												</div>";
										}
										
						$tabla .="</div>
									<div class='row'>
										<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0'>
											<div id='med".$idCont."Circulo' class='".$circulo."'></div>
										</div>
										<div class='col-lg-7 col-md-7 col-sm-7 col-xs-7 text-overflowA margin-0'>
											".$persona['especialidad']."
										</div>
										<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 margin-0'>
											".$persona['categoria']."
										</div>
									</div>
								</div>
								<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0'>
									<button type='button' class='btn bg-indigo waves-effect btn-indigo add-margin-bottom little-button' onClick='editarPersona(\"".$persona['pers_snr']."\",\"".$persona['USER_SNR']."\");' ".($persona['rec_stat'] == 0 ? '' : 'disabled').">
										<i class='material-icons pointer' data-toggle='tooltip' data-placement='left' title='Editar'>edit</i>
									</button>
									<button type='button' class='btn bg-indigo waves-effect btn-indigo add-margin-bottom little-button' onClick='avisoPrivacidad(\"".$persona['pers_snr']."\",\"".$persona['USER_SNR']."\");'>
										<i class='material-icons pointer fas' data-toggle='tooltip' data-placement='left'>".$iconoAviso."</i>
									</button>

									<button type='button' class='btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button' onClick='eliminarPersona(\"".$persona['pers_snr']."\",\"".$nombreMed."\",\"".$cargo."\",\"".$datosMedico."\",\"".$persona['USER_SNR']."\");' ".($persona['rec_stat'] == 0 ? '' : 'disabled').">
										<i class='material-icons pointer' data-toggle='tooltip' data-placement='left' title='Eliminar'>delete</i>
									</button>
								</div>								
							</div>
						</td>
						</tr>";
					}

					
					
				$tabla .= '</tbody>';

				if($totalRegistros > $registrosPorPagina){
					$tabla .= "<tfoot class='listaMedicosTfoot'><tr><td class='align-center'>";
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
					$tabla .= "<ul class='pagination margin-0'>";
					$tabla .= "<li class='active'><a>1</a></li>";
					$tabla .= "</ul>";
					$tabla .= "<p class='margin-0 font-12'>Pag. 1</p></td></tr></tfoot>";
				}
				$tabla .= "</table>";
				echo $tabla;

				
	}
	//echo '<script>$("#trmed1").click();</script>';
?>

