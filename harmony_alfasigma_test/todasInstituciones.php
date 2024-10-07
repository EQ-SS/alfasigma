<?php
	function queryInstituciones ($tipo, $ids){
		$queryInst = "SELECT 
			I.INST_SNR,
			INST_TYPE.NAME AS TIPO_INST,
			I.NAME AS NOMBRE,
			I.STREET1 AS DIRECCION,
			CP.NAME AS COLONIA,
			CP.ZIP AS CPOSTAL,
			POB.NAME AS POBLACION,
			EDO.NAME AS ESTADO,
			PAIS.NAME AS PAIS,
			bri.name as BRICK,
			I.TEL1,
			I.TEL2,
			I.WEB,
			I.EMAIL1 AS EMAIL,
			I.INFO AS COMENTARIOS,
			I.LATITUDE AS LATITUD,
			I.LONGITUDE AS LONGITUD,
			UT.USER_SNR AS USUARIO_ID, 
			U.LNAME+' '+U.FNAME AS REPRESENTANTE,
			(SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VP.INST_SNR 
			AND GETDATE() BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE
			AND vp.USER_SNR in ('".$ids."')) AS VISITAS, 
			/*I.STREET2 AS SUCURSAL,*/
			K.NAME AS RUTA
			From INST I
			left outer join  CITY  CP on CP.CITY_SNR = i.CITY_SNR 
			left outer join DISTRICT POB on POB.DISTR_SNR=CP.DISTR_SNR
			left outer join STATE EDO on EDO.STATE_SNR=CP.STATE_SNR
			left outer join INST_TYPE on INST_TYPE.INST_TYPE=I.INST_TYPE
			left outer join COUNTRY PAIS on PAIS.CTRY_SNR=CP.CTRY_SNR 
			left outer join USER_TERRIT UT on UT.INST_SNR=I.INST_SNR and UT.REC_STAT=0
			left outer join USERS U on U.USER_SNR=UT.USER_SNR
			left outer join KOMMLOC K on u.USER_SNR = K.KLOC_SNR
			LEFT OUTER JOIN BRICK bri on bri.BRICK_SNR = CP.BRICK_SNR 
			WHERE I.REC_STAT=0 
			AND I.INST_SNR<>'00000000-0000-0000-0000-000000000000' 
			and i.status_snr = 'B405F75D-499E-4EB8-AAC8-C89F2CA080A4' 
			and u.USER_SNR in ('".$ids."') ";
		if($tipo != ''){
			$queryInst .= " and INST_TYPE.NAME = '".$tipo."' ";
		}
		$queryInst .= " order by TIPO_INST, NOMBRE, DIRECCION ";
		//echo $queryInst."<br>";
		return $queryInst;
	}
	$idsEnviar = str_replace("'","",$ids);
?>
<input type="hidden" id="hdnFiltrosExportarInst" value="" />
<input type="hidden" id="hdnSelecciandoCambiarRutaInst" value="" />
<input type="hidden" id="hdnPaginaInst" value="1" />

<script>
	var actualTabInst = "tabInst1";
	var idInst = "";

	function cambiaTabInst(id) {
		actualTabInst = id;
		//alert(actualTabInst);
	} 
</script>


<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<!--#MAIN HEADER-->
			<div class="row clearfix add-padding-persona">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					<h2>
						<i class="fas fa-building"></i>
						<span>INSTITUCIONES</span>
					</h2>
				</div>
				<!--MENU OPCIONES INSTITUCIONE TOP-->
				<?php 
			if($tipoUsuario != 4){
?>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 align-right padding-0">
					<input type="checkbox" id="chkSeleccionarTodosCambiarRutaInst" class="filled-in chk-col-red p-l-15" style="display:none;" />
					<label id="lblSeleccionarTodosCambiarRutaInst" for="chkSeleccionarTodosCambiarRutaInst" style="display:none;">
						Seleccionar todos
					</label>

					<button class="btn bg-red waves-effect btn-red" id="btnAceptarCambiarRutaInst" title="Aceptar cambiar ruta" style="display:none;">Aceptar</button>
					<button class="btn bg-red waves-effect btn-red" id="btnCancelarCambiarRutaInst" title="Cancelar cambiar ruta"
					 style="display:none;">Cerrar</button>

					<button class="btn bg-red waves-effect btn-red" id="btnCambiarRutaInst" title="Cambiar ruta">Cambiar ruta</button>
				</div>
				<?php
			}else{
?>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 align-right padding-0">
					<button id="btnAprobacionesInst" title="Aprobaciones" class="btn bg-red waves-effect btn-red">Aprobaciones</button>
				</div>
				<?php
			}
?>
				<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 align-right padding-0" id="menuFiltrosGnr">
					<div class="display-inline display-screen-xs">
						<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','' );" id="btnTodosInst" class="btn waves-effect btn-account-head btn-account-l btn-account-sel"
						 disabled> Todos
						</button>
						<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','visitados' );" id="btnVisitadosInst" class="btn waves-effect btn-account-head btn-account-c">
							Visitados
						</button>
						<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','re' );" id="btnReVisitadosInst" class="btn waves-effect btn-account-head btn-account-c">
							Re-Visitados
						</button>
						<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','no' );" id="btnNoVisitadosInst" class="btn waves-effect btn-account-head btn-account-r">
							No Visitados
						</button>
					</div>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12 align-right padding-0">
					<button type="button" class="btn bg-red waves-effect btn-red" id="imgFiltrar2Inst" style="height:30px;"
					 data-toggle="tooltip" data-placement="top" title="Filtrar">
						<i class="fas fa-filter font-15 top-0"></i>
					</button>
				</div>
			</div>
			<!--#MENU OPCIONES INSTITUCIONE TOP-->
		</div>

		<!--FILTRAR INSTITUCIONES-->
		<div class="row clearfix" id="trFiltrosInst" style="display:none;">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-padding-persona">
				<div class="card">
					<div class="header">
						<h2>
							Filtrar
						</h2>
						<div class="align-right" style="margin: -29px 0px -11px 0px;">
							<p title="Cerrar Filtros" id="imgFiltrarInst" class="pointer">
								<i class="material-icons">close</i>
							</p>
						</div>
					</div>
					<div class="body">
						<!--<div id="tabFiltrosInstituciones">						
										<div id="tabs-1">-->
						<div id="tblFiltros">
							<?php
												if($tipoUsuario != 4){
													echo "<div class='row'>
														<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 no-margin2'>
															<div class='form-group' style='display:inline-flex;' onclick=\"filtrosUsuarios('inst');\">
																<label class='m-r-5 p-t-5'>Representante: </label>
																<select class='form-control'>
																	<option id=\"sltMultiSelectInst\">Seleccione</option>
																</select>
															</div>
														</div>
													</div>";
												}
?>
							<div class="row">
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group" style="display:inline-flex;">
										<label class="m-r-5 p-t-5">Tipo: </label>
										<select id="sltTipoInstFiltro" class="form-control">
											<option value="">Seleccione</option>
											<?php
														$rsTipoInst = sqlsrv_query($conn, "select * from INST_TYPE where REC_STAT = 0 order by name");
														while($regInst = sqlsrv_fetch_array($rsTipoInst)){
															echo '<option value="'.$regInst['INST_TYPE'].'">'.$regInst['NAME'].'</option>';
														}
		?>
										</select>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
											<input id="txtNombreInstFiltro" type="text" class="form-control" name="nombre" placeholder="Nombre Institución">
										</div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
											<input id="txtCalleInstFiltro" type="text" class="form-control" name="calle" placeholder="Calle">
										</div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
											<input id="txtColoniaInstFiltro" type="text" class="form-control" name="colonia" placeholder="Colonia">
										</div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
											<input id="txtCiudadInstFiltro" type="text" class="form-control" name="colonia" placeholder="Deleg/Mnpio">
										</div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
											<input id="txtEstadoInstFiltro" type="text" class="form-control" name="estado" placeholder="Estado">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 no-margin2">
									<div style="display:inline-flex;">
										<input name="rbGeo" type="radio" id="rbtTodos" class="with-gap radio-col-indigo" />
										<label for="rbtTodos">Todos</label>
										<input name="rbGeo" type="radio" id="rbtGeoSi" class="with-gap radio-col-indigo" />
										<label for="rbtGeoSi">Geolocalizados</label>
										<input name="rbGeo" type="radio" id="rbtGeoNo" class="with-gap radio-col-indigo" />
										<label for="rbtGeoNo">No Geolocalizados</label>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
											<input id="txtCPInstFiltro" type="text" class="form-control" name="CP" placeholder="Código Postal">
										</div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group" style="display:inline-flex;">
										<label class="m-r-5 p-t-5">Estatus: </label>
										<select id="sltEstatusFiltrosInst" class="form-control">
											<option value="">Seleccione</option>
											<?php
														$rsEstatus = llenaCombo($conn, 14, 6);
														while($estatus = sqlsrv_fetch_array($rsEstatus)){
															echo '<option value="'.$estatus['id'].'">'.$estatus['nombre'].'</option>';
														}
?>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group" style="display:inline-flex;">
										<label class="m-r-5 p-t-5" style="width: 108px;">Motivo Baja: </label>
										<select id="sltMotivoBajaFiltrosInst" class="form-control">
											<option value="">Seleccione</option>
											<?php
														$rsBajas = llenaComboBajas($conn, 14, 6);
														while($baja = sqlsrv_fetch_array($rsBajas)){
															echo '<option value="'.$baja['id'].'">'.$baja['nombre'].'</option>';
														}
?>
										</select>
									</div>
								</div>
								<div class="col-lg-1 col-md-1 col-sm-6 col-xs-6 align-right no-margin2">
									<button class="btn bg-teal waves-effect btn-wid-col" id="btnLimpiarFiltrosInst" type="button">
										Limpiar
									</button>
								</div>
								<div class="col-lg-1 col-md-1 col-sm-6 col-xs-6 align-right no-margin2">
									<button class="btn bg-teal waves-effect btn-wid-col" onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','' );"
									 id="btnEjecutarFiltroInst" type="button">
										Filtrar
									</button>
								</div>
							</div>
						</div>
						<!--</div>
							</div>-->
					</div>
				</div>
			</div>
		</div>
		<!--#END FILTRAR INST-->



		<div id="tabsInstituciones">
			<!--RIGHT BAR INSTITUCIONES-->
			<div class="right-sidebar-person">
				<div style="display: inline-grid;">
					<ul class="no-style-list">
						<li>
							<a href="#infoInstitucion" data-toggle="tooltip" data-placement="left" title="Perfil">
								<button type="button" id="tabInst1" onClick="cambiaTabInst(this.id);" class="btn btn-default waves-effect add-margin-bottom btn-indigo2 btn-indigo-slt">
									<i class="material-icons pointer top-0">contact_mail</i>
								</button>
							</a>
						</li>
						<li>
							<a href="#mapaInstitucion" id="lkMapaInstituciones" data-toggle="tooltip" data-placement="left" title="Mapa">
								<button type="button" id="tabInst2" onClick="cambiaTabInst(this.id);" class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
									<i class="material-icons pointer top-0">map</i>
								</button>
							</a>
						</li>
						<li>
							<a href="#planInstitucion" data-toggle="tooltip" data-placement="left" title="Plan">
								<button type="button" id="tabInst3" onClick="cambiaTabInst(this.id);" class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
									<i class="material-icons pointer top-0">today</i>
								</button>
							</a>
						</li>
						<li>
							<a href="#visitasInstitucion" data-toggle="tooltip" data-placement="left" title="Visitas">
								<button id="tabInst4" type="button" onClick="cambiaTabInst(this.id);" class="btn btn-default waves-effect add-margin-bottom p-l-7 p-r-7 btn-indigo2">
									<i class="fas fa-handshake pointer top-0"></i>
								</button>
							</a>
						</li>
						<li>
							<a href="#representantesInstitucion" data-toggle="tooltip" data-placement="left" title="Representantes">
								<button type="button" id="tabInst5" onClick="cambiaTabInst(this.id);" class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
									<i class="material-icons pointer top-0">supervisor_account</i>
								</button>
							</a>
						</li>
						<!--<li>
								<a data-toggle="tooltip" data-placement="left" title="Encuesta">
									<button type="button" id="tabInst6" onClick="cambiaTabInst(this.id);" class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
										<i class="material-icons pointer top-0">question_answer</i>
									</button>
								</a>
							</li>
							<li>
								<a data-toggle="tooltip" data-placement="left" title="Eventos">
									<button type="button" id="tabInst7" onClick="cambiaTabInst(this.id);" class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
										<i class="material-icons pointer top-0">event_note</i>
									</button>
								</a>
							</li>
							<li>
								<a data-toggle="tooltip" data-placement="left" title="Multi Canal">
									<button type="button" id="tabInst8" onClick="cambiaTabInst(this.id);" class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
										<i class="material-icons pointer top-0">layers</i>
									</button>
								</a>
							</li>
							<li>
								<a data-toggle="tooltip" data-placement="left" title="CLM">
									<button type="button" id="tabInst9" onClick="cambiaTabInst(this.id);" class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
										<i class="material-icons pointer top-0">ondemand_video</i>
									</button>
								</a>
							</li>-->
					</ul>
				</div>
			</div>
			<!--#RIGHT BAR INSTITUCIONES-->


			<!-- SHOW INSTITUCIONES -->

			<div class="row clearfix">
				<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 add-padding-listm">
					<div class="card">
						<div class="header bg-light-blue">
							<ul class="header-dropdown">
								<li class="pointer">
									<a href="javascript:void(0);" id="btnActualizarInst" title="Actualizar" data-toggle="cardloading"
									 data-loading-effect="timer" data-loading-color="lightBlue">
										<i class="material-icons">loop</i>
									</a>
								</li>
								<li class="m-l-10 p-r-5 pointer">
									<a onClick="exportarExcelPersonas('<?= $hoy ?>','<?= $idsEnviar ?>');" id="btnExportarInst" title="Descargar">
										<i class="material-icons">cloud_download</i>
									</a>
								</li>
							</ul>
						</div>
						<div class="body">
							<!--TODAS-->
							<div id="tbTodas">

								<table class="table table-striped table-hover dataTable" id="tblTodas">
									<thead>

									</thead>

									<tbody>
										<?php
											$registrosPorPaginaInst = 5;
											$idContInst = 0;
											
											$queryInst = queryInstituciones('', $ids);
											//echo $queryInst."<br>";
											$tope = "OFFSET 0 ROWS 
												FETCH NEXT ".$registrosPorPaginaInst." ROWS ONLY ";
											
											$rsInst = sqlsrv_query($conn, $queryInst, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
											
											$totalRegistrosInst = sqlsrv_num_rows($rsInst);
											
											$queryInst20 = sqlsrv_query($conn, $queryInst.$tope);

											//echo $queryInst.$tope;
											echo "<div class='bg-light-blue m-t--20 m-l--20 m-r--20 m-b-20 p-l-20 p-b-20'>
												<p style='position:absolute; top:20px;'>
													<span class='font-bold'>Total de registros: </span>".$totalRegistrosInst."
												</p>
											</div>";
											
											$paginasInst = ceil($totalRegistrosInst / $registrosPorPaginaInst);
											$tabla = '';
											$i = 1;

											
											while($inst = sqlsrv_fetch_array($queryInst20)){
												if($inst['VISITAS'] == 0){
													$circulo = 'circuloRojo';
												}else{
													$circulo = 'circuloVerde';
												} 
												$datosInst = "<b>".$inst['NOMBRE']."</b><br>".$inst['TIPO_INST']."<br>";
												$datosInst .= "CALLE: ".$inst['DIRECCION'].", ";
												$datosInst .= "C.P.: ".$inst['CPOSTAL'].", COLONIA: ".$inst['COLONIA'].", ";
												$datosInst .= "POBLACIÓN: ".$inst['POBLACION'].", ";
												$datosInst .= "ESTADO: ".$inst['ESTADO'].".<br>";
												$datosInst .= "BRICK: ".$inst['BRICK'];
												$idContInst++;

												$tablaTodos = "<tr>
														<td>
															<div class='row margin-0'>
																<div id='inst".$idContInst."' class='col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0 padding-0' onclick='presentaDatos(\"inst".$idContInst."\",\"".$inst['INST_SNR']."\",\"divDatosInstituciones\",\"\",\"\",\"".$inst['USUARIO_ID']."\");'>
																	<div class='row'>
																		<div class='col-md-12 text-overflowA font-bold margin-0 m-b-5'><span style='display:inline-flex;'>";

																	if($inst['TIPO_INST'] == 'Consultorios'){
																		$tablaTodos .= '<i class="material-icons">local_pharmacy</i>
																		<span style="margin: 5px 0 5px 6px;">'.$inst['NOMBRE'].'</span>';
																	}
																	if($inst['TIPO_INST'] == 'Hospitales'){
																		$tablaTodos .= '<i class="fas fa-hospital"></i>
																		<span style="margin: 4px 0 5px 8px;">'.$inst['NOMBRE'].'</span>';
																	}
																	if($inst['TIPO_INST'] == 'Farmacias'){
																		$tablaTodos .= '<i class="fas fa-pills"></i>
																		<span style="margin: 4px 0 5px 8px;">'.$inst['NOMBRE'].'</span>';
																	}
																	if($inst['TIPO_INST'] == 'Otras instituciones'){
																		$tablaTodos .= '<i class="fas fa-building"></i>
																		<span style="margin: 4px 0 5px 8px;">'.$inst['NOMBRE'].'</span>';
																	}
																			
															$tablaTodos .= "</span>
																		</div>
																	</div>
																	<div class='row'>
																		<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 p-t-5'>
																			<div class='".$circulo."'></div>
																		</div>
																		<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA margin-0'>
																			".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."  
																		</div>
																	</div>
																</div>
																<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 align-right margin-0' style='min-height: 85px;display: table;'>
																	<div style='display: table-cell; vertical-align: middle;'>
																		<button type='button' class='btn bg-indigo waves-effect add-margin-bottom little-button' title='Modificar' onClick='editarInst(\"".$inst['INST_SNR']."\");'>
																			<i class='material-icons pointer top-0'>edit</i>
																		</button>
																		
																		<button type='button' class='btn bg-indigo btn bg-indigo waves-effect little-button' title='Eliminar' onClick='eliminarInst(\"".$inst['INST_SNR']."\",\"".$datosInst."\",\"".$inst['USUARIO_ID']."\");'>
																			<i class='material-icons pointer top-0'>delete</i>
																		</button>
																	</div>
																</div>								
															</div>
														</td>
													</tr>";

													/*if($tipoUsuario != 4){
														echo "<td onclick='presentaDatos(\"".$inst['INST_SNR']."\",\"divDatosInstituciones\",\"\",\"\",\"".$inst['USUARIO_ID']."\");'>
														".$inst['RUTA']."
														</td>";
													}*/

												echo $tablaTodos;
												$tabla .= "<tr>
															<td>
																<input onClick=\"seleccionaCambiarRutaInst('".$inst['INST_SNR']."','chkCambiarRutaInst".$i."');\" type=\"checkbox\" id=\"chkCambiarRutaInst".$i."\" />
															</td>
															".$tablaTodos."
															<td onclick='presentaDatos(\"".$inst['INST_SNR']."\",\"divDatosInstituciones\",\"\",\"\",\"".$inst['USUARIO_ID']."\");'>
																".$inst['RUTA']."
															</td>
													</tr>";
											$i++;
											
											}
											
				?>
									</tbody>
									<tfoot>
										<tr>
											<td class="align-center">
												<ul class="pagination margin-0">
													<?php								
													$foot = '';
													for($i=1;$i<=$paginasInst;$i++){
														if($i == 1){
															$foot .= "<li class='active'><a>".$i."</a></li>";
														}else{
															$idsEnviar = str_replace("'","",$ids);
															//echo "<a href='#' onClick='nuevaPaginaInst(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"\");'>".$i."</a>&nbsp;&nbsp;";
														}
													}
													if($paginasInst > 1){
														$foot .= "<li><a href='#' class='waves-effect font-14' onClick='nuevaPaginaInst(2,\"".$hoy."\",\"".$idsEnviar."\",\"\");'>Siguiente</a></li>";
														$foot .= "<li><a href='#' class='waves-effect font-14' onClick='nuevaPaginaInst(".$paginasInst.",\"".$hoy."\",\"".$idsEnviar."\",\"\");'>Fin</a></li>";
													}
													$foot .= "</ul>";
													$foot .= "<p>Pag. 1 de ".$paginasInst."<p>";
													echo $foot;
																					
				?>
											</td>
										</tr>
									</tfoot>
								</table>

							</div>
						</div>
					</div>
				</div>
				<!--SHOW LIST INST-->

				<!--SHOW DATOS INST-->
				<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12 add-padding-persona">
					<div class="card">
						<div class="header">
							<h2 id="lblNombreInst">
							</h2>
						</div>
						<div class="body">
							<div id="divDatosInstituciones" style="display:none;">
								<?php include "datosInstituciones.php"; ?>
							</div>
						</div>
					</div>
				</div>
				<!--#SHOW DATOS INST-->
			</div> <!-- #SHOW INSTITUCIONES -->
		</div>

		<div class="button-float pull-right list-btn-float">
			<button class="btn bg-red btn-circle waves-effect waves-circle waves-float btn-red-hf" id="imgAgregarInstitucion"
			 data-toggle="tooltip" data-placement="left" title="Agregar">
				<i class="material-icons">add</i>
			</button>
		</div>
	</div>
</section>

<table width="100%">
	<tr>
		<td>

			<div id="divCambiarRutaInst" style="display:none;">
				<table id="tblCambiarRutaInst" class="grid">
					<thead>
						<tr>
							<td style="width:50px">Seleccione</td>
							<td style="width:100px">Tipo</td>
							<td style="width:300px">Nombre</td>
							<td style="width:300px">Dirección</td>
							<td style="width:200px">Colonia</td>
							<td style="width:100px">C.P.</td>
							<td style="width:200px">Municipio</td>
							<td style="width:200px">Estado</td>
							<td style="width:200px">Brick</td>
							<td style="width:100px">Ruta</td>
						</tr>
					</thead>
					<tbody>
						<?= $tabla ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="11">
								<?= $foot ?>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</td>
	</tr>
</table>