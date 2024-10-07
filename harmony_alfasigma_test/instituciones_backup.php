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


<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<!--#MAIN HEADER-->
			<div class="row clearfix">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
					<h2>
						INSTITUCIONES
					</h2>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 padding-0">
					<!--MENU OPCIONES INSTITUCIONE TOP-->
					<div class="align-right add-padding-persona">
						<?php 
						if($tipoUsuario != 4){
					?>
						<input type="checkbox" id="chkSeleccionarTodosCambiarRutaInst" style="display:none;" />
						<font size="2"><label id="lblSeleccionarTodosCambiarRutaInst" style="display:none;">Seleccionar Todos</label></font>
						<button class="btn bg-red waves-effect btn-red" id="btnAceptarCambiarRutaInst" title="Aceptar cambiar ruta" style="display:none;">Aceptar</button>
						<button class="btn bg-red waves-effect btn-red" id="btnCancelarCambiarRutaInst" title="Cancelar cambiar ruta" style="display:none;">Cerrar</button>
						<button class="btn bg-red waves-effect btn-red" id="btnCambiarRutaInst" title="Cambiar ruta">Cambiar ruta</button>
						<?php
						}else{
					?>
						<button id="btnAprobacionesInst" title="Aprobaciones" class="btn bg-red waves-effect btn-red">Aprobaciones</button>
						<?php
						}
					?>
						<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','re' );" id="btnReVisitadosInst" class="btn bg-red waves-effect btn-red">
							Re-Visitados </button>
						<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','visitados' );" id="btnVisitadosInst" class="btn bg-red waves-effect btn-red">
							Visitados </button>
						<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','no' );" id="btnNoVisitadosInst" class="btn bg-red waves-effect btn-red">
							No Visitados </button>
						<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','' );" id="btnTodosInst" class="btn bg-red waves-effect btn-red">
							Todos </button>
						<button type="button" class="btn bg-red waves-effect btn-red" id="imgFiltrar2Inst" style="height:30px;" data-toggle="tooltip" data-placement="top" title="Filtrar">
							<i class="fas fa-filter font-15"></i>
						</button>

					</div>
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

		<div id="divGridInstituciones">
			<div class="row">
				<!--SHOW LIST INST-->
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 add-padding-persona">
					<ul class="no-style-list display-inline margin-0">
						<li><a href="#tbTodas">
								<button type="button" class="btn bg-light-blue waves-effect btn-li-menu2 btn-light-blue">
									<i class="fas fa-th-list"></i>
									<span>Todas</span>
								</button>
							</a></li>

						<li class="m-l-0"><a href="#tbHospitales">
								<button type="button" class="btn bg-light-blue waves-effect btn-li-menu2 btn-light-blue">
									<i class="fas fa-hospital"></i>
									<span>Hospitales</span>
								</button>
							</a></li>
						<li class="m-l-0"><a href="#tbFarmacias">
								<button type="button" class="btn bg-light-blue waves-effect btn-li-menu2 btn-light-blue">
									<i class="fas fa-pills"></i>
									<span>Farmacias</span>
								</button>
							</a></li>
						<li class="m-l-0" style="display:none;"><a href="#tbConsultorios">
								<button type="button" class="btn bg-teal waves-effect btn-li-menu2 btn-light-blue">
									<i class="material-icons" style="margin-top: -3px">local_pharmacy</i>
									<span>Consultorios</span>
								</button>
							</a></li>
						<li class="m-l-0" style="display:none;"><a href="#tbClientes">
								<button type="button" class="btn bg-teal waves-effect btn-li-menu2 btn-light-blue">
									<i class="fas fa-user"></i>
									<span>Clientes</span>
								</button>
							</a></li>
					</ul>
				</div>
			</div>

			<div id="tabsInstituciones">
				<!--RIGHT BAR INSTITUCIONES-->
				<div class="right-sidebar-person">
					<div style="display: inline-grid;">
						<ul class="no-style-list">
							<li>
								<a href="#infoInstitucion" data-toggle="tooltip" data-placement="left" title="Perfil">
									<button type="button" class="btn btn-default waves-effect add-margin-bottom">
										<i class="material-icons pointer">contact_mail</i>
									</button>
								</a>
							</li>
							<li>
								<a href="#mapaInstitucion" id="lkMapaInstituciones" data-toggle="tooltip" data-placement="left" title="Mapa">
									<button type="button" class="btn btn-default waves-effect add-margin-bottom">
										<i class="material-icons pointer">map</i>
									</button>
								</a>
							</li>
							<li>
								<a href="#planInstitucion" data-toggle="tooltip" data-placement="left" title="Plan">
									<button type="button" class="btn btn-default waves-effect add-margin-bottom">
										<i class="material-icons pointer">today</i>
									</button>
								</a>
							</li>
							<li>
								<a href="#visitasInstitucion" data-toggle="tooltip" data-placement="left" title="Visitas">
									<button type="button" class="btn btn-default waves-effect add-margin-bottom">
										<i class="material-icons pointer">pan_tool</i>
									</button>
								</a>
							</li>
							<li>
								<a href="#representantesInstitucion" data-toggle="tooltip" data-placement="left" title="Representantes">
									<button type="button" class="btn btn-default waves-effect add-margin-bottom">
										<i class="material-icons pointer">supervisor_account</i>
									</button>
								</a>
							</li>
							<li>
								<a data-toggle="tooltip" data-placement="left" title="Encuesta">
									<button type="button" class="btn btn-default waves-effect add-margin-bottom">
										<i class="material-icons pointer">question_answer</i>
									</button>
								</a>
							</li>
							<li>
								<a data-toggle="tooltip" data-placement="left" title="Eventos">
									<button type="button" class="btn btn-default waves-effect add-margin-bottom">
										<i class="material-icons pointer">event_note</i>
									</button>
								</a>
							</li>
							<li>
								<a data-toggle="tooltip" data-placement="left" title="Multi Canal">
									<button type="button" class="btn btn-default waves-effect add-margin-bottom">
										<i class="material-icons pointer">layers</i>
									</button>
								</a>
							</li>
							<li>
								<a data-toggle="tooltip" data-placement="left" title="CLM">
									<button type="button" class="btn btn-default waves-effect add-margin-bottom">
										<i class="material-icons pointer">ondemand_video</i>
									</button>
								</a>
							</li>
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
											$registrosPorPaginaInst = 6;
											
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
												$tablaTodos = "<tr>
														<td>
															<div class='row margin-0'>
																<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0 padding-0' onclick='presentaDatos(\"".$inst['INST_SNR']."\",\"divDatosInstituciones\",\"\",\"\",\"".$inst['USUARIO_ID']."\");'>
																	<div class='row'>
																		<div class='col-md-12 text-overflowA font-bold margin-0 m-b-5'>
																			".$inst['NOMBRE']."
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
												<td colspan="12" align="center">
													<?php								$foot = '';
													for($i=1;$i<=$paginasInst;$i++){
														if($i == 1){
															$foot .= $i."&nbsp;&nbsp;";
														}else{
															$idsEnviar = str_replace("'","",$ids);
															//echo "<a href='#' onClick='nuevaPaginaInst(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"\");'>".$i."</a>&nbsp;&nbsp;";
														}
													}
													if($paginasInst > 1){
														$foot .= "<a href='#' onClick='nuevaPaginaInst(2,\"".$hoy."\",\"".$idsEnviar."\",\"\");'>Siguiente</a>&nbsp;&nbsp;";
														$foot .= "<a href='#' onClick='nuevaPaginaInst(".$paginasInst.",\"".$hoy."\",\"".$idsEnviar."\",\"\");'>Fin</a>&nbsp;&nbsp;";
													}
													$foot .= "<br>Pag. 1 de ".$paginasInst;
													echo $foot;
				?>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>

								<!--HOSPITALES-->
								<div id="tbHospitales">
									<table class="table table-striped table-hover dataTable" id="tblHospitales">
										<thead>

										</thead>

										<tbody>
											<?php 
										$queryHosp = queryInstituciones('HOSPITALES', $ids);
										
										$rsHosp = sqlsrv_query($conn, $queryHosp, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
										
										$totalRegistrosHosp = sqlsrv_num_rows($rsHosp);
										
										$queryInst20Hosp = sqlsrv_query($conn, $queryHosp.$tope);
										
										//echo $queryInst.$tope;
										//echo $queryInst.$tope;
										echo "<div class='bg-light-blue m-t--20 m-l--20 m-r--20 m-b-20 p-l-20 p-b-20'>
											<p style='position:absolute; top:20px;'>
												<span class='font-bold'>Hospitales registrados: </span>".$totalRegistrosHosp."
											</p>
										</div>";
										
										$paginasHosp = ceil($totalRegistrosHosp / $registrosPorPaginaInst);
										
										while($inst = sqlsrv_fetch_array($queryInst20Hosp)){
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
											
											echo "<tr>
												<td>
													<div class='row no-margin'>
														<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer' onclick='presentaDatos(\"".$inst['INST_SNR']."\",\"divDatosInstituciones\",\"HOSPITALES\",\"\",\"".$inst['USUARIO_ID']."\");'>
															<div class='row no-margin'>
																<div class='col-md-12 text-overflowA font-bold'>
																	".$inst['NOMBRE']."
																</div>
															</div>
															<div class='row no-margin'>
																<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1'>
																	<div class='".$circulo."'></div>
																</div>
																<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA'>
																	".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."  
																</div>
															</div>
														</div>
														<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1' style='padding-top: 2%;'>
															<button type='button' class='btn bg-indigo waves-effect add-margin-bottom little-button' title='Modificar' onClick='editarInst(\"".$inst['INST_SNR']."\");'>
																<i class='material-icons pointer top-0'>edit</i>
															</button>
															
															<button type='button' class='btn bg-indigo btn bg-indigo waves-effect little-button' title='Eliminar' onClick='eliminarInst(\"".$inst['INST_SNR']."\",\"".$datosInst."\",\"".$inst['USUARIO_ID']."\");'>
																<i class='material-icons pointer top-0'>delete</i>
															</button>
														</div>								
													</div>
												</td>
											</tr>";
										}
?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="14" align="center">
													<?php
												for($i=1;$i<=$paginasHosp;$i++){
													if($i == 1){
														echo $i."&nbsp;&nbsp;";
													}else{
														$idsEnviar = str_replace("'","",$ids);
														//echo "<a href='#' onClick='nuevaPaginaInst(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"HOSPITALES\");'>".$i."</a>&nbsp;&nbsp;";
													}
												}
												if($paginasHosp > 1){
													echo "<a href='#' onClick='nuevaPaginaInst(2,\"".$hoy."\",\"".$idsEnviar."\",\"HOSPITALES\");'>Siguiente</a>&nbsp;&nbsp;";
													echo "<a href='#' onClick='nuevaPaginaInst(".$paginasHosp.",\"".$hoy."\",\"".$idsEnviar."\",\"HOSPITALES\");'>Fin</a>&nbsp;&nbsp;";
												}
												echo "Pag. 1 de ".$paginasHosp;
?>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>

								<!--FARMACIAS-->
								<div id="tbFarmacias">
									<table class="table table-striped table-hover dataTable" id="tblFarmacias">
										<thead>

										</thead>

										<tbody>
											<?php 
									$queryFarm = queryInstituciones('FARMACIAS', $ids);
						
									$rsFarm = sqlsrv_query($conn, $queryFarm, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
									
									$totalRegistrosFarm = sqlsrv_num_rows($rsFarm);
									
									$queryInst20Farm = sqlsrv_query($conn, $queryFarm.$tope);
									
									//echo $queryInst.$tope;
									echo "<div class='bg-light-blue m-t--20 m-l--20 m-r--20 m-b-20 p-l-20 p-b-20'>
										<p style='position:absolute; top:20px;'>
											<span class='font-bold'>Farmacias registradas: </span>".$totalRegistrosFarm."
										</p>
									</div>";
									
									$paginasFarm = ceil($totalRegistrosFarm / $registrosPorPaginaInst);
									
									while($farmacia = sqlsrv_fetch_array($queryInst20Farm)){
										if($farmacia['VISITAS'] == 0){
											$circulo = 'circuloRojo';
										}else{
											$circulo = 'circuloVerde';
										}
										$datosInst = "<b>".$farmacia['NOMBRE']."</b><br>".$farmacia['TIPO_INST']."<br>";
										$datosInst .= "CALLE: ".$farmacia['DIRECCION'].", ";
										$datosInst .= "C.P.: ".$farmacia['CPOSTAL'].", COLONIA: ".$farmacia['COLONIA'].", ";
										$datosInst .= "POBLACIÓN: ".$farmacia['POBLACION'].", ";
										$datosInst .= "ESTADO: ".$farmacia['ESTADO'].".<br>";
										$datosInst .= "BRICK: ".$farmacia['BRICK'];
									
										echo "<tr>
											<td>
												<div class='row no-margin'>
													<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer' onclick='presentaDatos(\"".$farmacia['INST_SNR']."\",\"divDatosFarmacias\",\"FARMACIAS\",\"\",\"".$farmacia['USUARIO_ID']."\");'>
														<div class='row no-margin'>
															<div class='col-md-12 text-overflowA font-bold'>
																".$farmacia['NOMBRE']."
															</div>
														</div>
														<div class='row no-margin'>
															<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1'>
																<div class='".$circulo."'></div>
															</div>
															<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA'>
																".$farmacia['DIRECCION'].", ".$farmacia['POBLACION'].",".$farmacia['ESTADO']."  
															</div>
														</div>
													</div>
													<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1' style='padding-top: 2%;'>
														<button type='button' class='btn bg-indigo waves-effect add-margin-bottom little-button' title='Modificar' onClick='editarInst(\"".$farmacia['INST_SNR']."\");'>
															<i class='material-icons pointer top-0'>edit</i>
														</button>
														
														<button type='button' class='btn bg-indigo btn bg-indigo waves-effect little-button' title='Eliminar' onClick='eliminarInst(\"".$farmacia['INST_SNR']."\",\"".$datosInst."\",\"".$farmacia['USUARIO_ID']."\");'>
															<i class='material-icons pointer top-0'>delete</i>
														</button>
													</div>								
												</div>
											</td>
										</tr>";
									}
?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="14" align="center">
													<?php
													for($i=1;$i<=$paginasFarm;$i++){
														if($i == 1){
															echo $i."&nbsp;&nbsp;";
														}else{
															$idsEnviar = str_replace("'","",$ids);
															//echo "<a href='#' onClick='nuevaPaginaInst(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"FARMACIAS\");'>".$i."</a>&nbsp;&nbsp;";
														}
													}
													if($paginasFarm > 1){
														echo "<a href='#' onClick='nuevaPaginaInst(2,\"".$hoy."\",\"".$idsEnviar."\",\"FARMACIAS\");'>Siguiente</a>&nbsp;&nbsp;";
														echo "<a href='#' onClick='nuevaPaginaInst(".$paginasFarm.",\"".$hoy."\",\"".$idsEnviar."\",\"FARMACIAS\");'>Fin</a>&nbsp;&nbsp;";
													}
													echo "Pag. 1 de ".$paginasFarm;
?>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>

								<!--CONSULTORIOS-->
								<div id="tbConsultorios">
									<table class="table table-striped table-hover dataTable" id="tblConsultorios">
										<thead>

										</thead>

										<tbody>
											<?php 
											$queryCons = queryInstituciones('CONSULTORIOS', $ids);
											
											//echo $queryCons;
											
											$rsCons = sqlsrv_query($conn, $queryCons, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
											
											$totalRegistrosCons = sqlsrv_num_rows($rsCons);
											
											$queryInst20Cons = sqlsrv_query($conn, $queryCons.$tope);
											
											//echo $queryInst.$tope;
											
											$paginasCons = ceil($totalRegistrosCons / $registrosPorPaginaInst);
											
											while($consultorio = sqlsrv_fetch_array($queryInst20Cons)){
												if($consultorio['VISITAS'] == 0){
													$circulo = 'circuloRojo';
												}else{
													$circulo = 'circuloVerde';
												}
												$datosInst = "<b>".$consultorio['NOMBRE']."</b><br>".$consultorio['TIPO_INST']."<br>";
												$datosInst .= "CALLE: ".$consultorio['DIRECCION'].", ";
												$datosInst .= "C.P.: ".$consultorio['CPOSTAL'].", COLONIA: ".$consultorio['COLONIA'].", ";
												$datosInst .= "POBLACIÓN: ".$consultorio['POBLACION'].", ";
												$datosInst .= "ESTADO: ".$consultorio['ESTADO'].".<br>";
												$datosInst .= "BRICK: ".$consultorio['BRICK'];
												echo "<tr>
												<td>
													<div class='row no-margin'>
														<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer' onclick='presentaDatos(\"".$consultorio['INST_SNR']."\",\"divDatosInstituciones\",\"CONSULTORIOS\",\"\",\"".$consultorio['USUARIO_ID']."\");'>
															<div class='row no-margin'>
																<div class='col-md-12 text-overflowA font-bold'>
																	".$consultorio['NOMBRE']."
																</div>
															</div>
															<div class='row no-margin'>
																<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1'>
																	<div class='".$circulo."'></div>
																</div>
																<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA'>
																	".$consultorio['DIRECCION'].", ".$consultorio['POBLACION'].",".$consultorio['ESTADO']."  
																</div>
															</div>
														</div>
														<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1' style='padding-top: 2%;'>
															<button type='button' class='btn bg-indigo waves-effect add-margin-bottom little-button' title='Modificar' onClick='editarInst(\"".$consultorio['INST_SNR']."\");'>
																<i class='material-icons pointer'>edit</i>
															</button>
															
															<button type='button' class='btn bg-indigo btn bg-indigo waves-effect little-button' title='Eliminar' onClick='eliminarInst(\"".$consultorio['INST_SNR']."\",\"".$datosInst."\",\"".$consultorio['USUARIO_ID']."\");'>
																<i class='material-icons pointer'>delete</i>
															</button>
														</div>								
													</div>
												</td>
											</tr>";
											}
				?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="14" align="center">
													<?php
													for($i=1;$i<=$paginasCons;$i++){
														if($i == 1){
															echo $i."&nbsp;&nbsp;";
														}else{
															$idsEnviar = str_replace("'","",$ids);
															//echo "<a href='#' onClick='nuevaPaginaInst(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"CONSULTORIOS\");'>".$i."</a>&nbsp;&nbsp;";
														}
													}
													if($paginasCons > 1){
														echo "<a href='#' onClick='nuevaPaginaInst(2,\"".$hoy."\",\"".$idsEnviar."\",\"CONSULTORIOS\");'>Siguiente</a>&nbsp;&nbsp;";
														echo "<a href='#' onClick='nuevaPaginaInst(".$paginasCons.",\"".$hoy."\",\"".$idsEnviar."\",\"CONSULTORIOS\");'>Fin</a>&nbsp;&nbsp;";
													}
													echo "Pag. 1 de ".$paginasCons."&nbsp;&nbsp;&nbsp; Registros : ".$totalRegistrosCons;
				?>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>

								<!--CLIENTES-->
								<div id="tbClientes">
									<table class="table table-striped table-hover dataTable" id="tblClientes">
										<thead>

										</thead>

										<tbody>
											<?php 
											$queryClien = queryInstituciones('CLIENTES', $ids);
											
											$rsClien = sqlsrv_query($conn, $queryClien, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
											
											$totalRegistrosClien = sqlsrv_num_rows($rsClien);
											
											$queryInst20Clien = sqlsrv_query($conn, $queryClien.$tope);
											
											//echo $queryInst.$tope;
											
											$paginasClien = ceil($totalRegistrosClien / $registrosPorPaginaInst);
											
											while($cliente = sqlsrv_fetch_array($queryInst20Clien)){
												if($cliente['VISITAS'] == 0){
													$circulo = 'circuloRojo';
												}else{
													$circulo = 'circuloVerde';
												}
												$datosInst = "<b>".$cliente['NOMBRE']."</b><br>".$consultorio['TIPO_INST']."<br>";
												$datosInst .= "CALLE: ".$cliente['DIRECCION'].", ";
												$datosInst .= "C.P.: ".$cliente['CPOSTAL'].", COLONIA: ".$consultorio['COLONIA'].", ";
												$datosInst .= "POBLACIÓN: ".$cliente['POBLACION'].", ";
												$datosInst .= "ESTADO: ".$cliente['ESTADO'].".<br>";
												$datosInst .= "BRICK: ".$cliente['BRICK'];
												echo "<tr>
												<td>
													<div class='row no-margin'>
														<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer' onclick='presentaDatos(\"".$cliente['INST_SNR']."\",\"divDatosInstituciones\",\"CLIENTES\",\"\",\"".$clientes['USUARIO_ID']."\");'>
															<div class='row no-margin'>
																<div class='col-md-12 text-overflowA font-bold'>
																	".$cliente['NOMBRE']."
																</div>
															</div>
															<div class='row no-margin'>
																<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1'>
																	<div class='".$circulo."'></div>
																</div>
																<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA'>
																	".$cliente['DIRECCION'].", ".$cliente['POBLACION'].",".$cliente['ESTADO']."  
																</div>
															</div>
														</div>
														<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1' style='padding-top: 2%;'>
															<button type='button' class='btn bg-indigo waves-effect add-margin-bottom little-button' title='Modificar' onClick='editarInst(\"".$cliente['INST_SNR']."\");'>
																<i class='material-icons pointer'>edit</i>
															</button>
															
															<button type='button' class='btn bg-indigo btn bg-indigo waves-effect little-button' title='Eliminar' onClick='eliminarInst(\"".$cliente['INST_SNR']."\",\"".$datosInst."\",\"".$cliente['USUARIO_ID']."\");'>
																<i class='material-icons pointer'>delete</i>
															</button>
														</div>								
													</div>
												</td>
											</tr>";
											}
				?>
										</tbody>
										<tfoot>

											<tr>
												<td colspan="14" align="center">

													<?php
													for($i=1;$i<=$paginasClien;$i++){
														if($i == 1){
															echo $i."&nbsp;&nbsp;";
														}else{
															$idsEnviar = str_replace("'","",$ids);
															//echo "<a href='#' onClick='nuevaPaginaInst(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"CLIENTES\");'>".$i."</a>&nbsp;&nbsp;";
														}
													}
													if($paginasClien > 1){
														echo "<a href='#' onClick='nuevaPaginaInst(2,\"".$hoy."\",\"".$idsEnviar."\",\"CLIENTES\");'>Siguiente</a>&nbsp;&nbsp;";
														echo "<a href='#' onClick='nuevaPaginaInst(".$paginasClien.",\"".$hoy."\",\"".$idsEnviar."\",\"CLIENTES\");'>Fin</a>&nbsp;&nbsp;";
													}
													echo "Pag. 1 de ".$paginasClien."&nbsp;&nbsp;&nbsp; Registros : ".$totalRegistrosClien;
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
					<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12 add-padding-persona m-t--30">
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