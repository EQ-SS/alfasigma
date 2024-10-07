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
			I.HTTP AS WEB,
			I.EMAIL,
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
			I.STREET2 AS SUCURSAL,
			K.NAME AS RUTA
			From INST I
			left outer join  CITY  CP on CP.CITY_SNR = i.CITY_SNR 
			left outer join DISTRICT POB on POB.DISTR_SNR=CP.DISTR_SNR
			left outer join STATE EDO on EDO.STATE_SNR=CP.STATE_SNR
			left outer join INST_TYPE on INST_TYPE.INST_TYPE=I.INST_TYPE
			left outer join COUNTRY PAIS on PAIS.CTRY_SNR=CP.CTRY_SNR 
			left outer join USER_TERRIT UT on UT.TER_SNR=I.INST_SNR and UT.REC_STAT=0
			left outer join USERS U on U.USER_SNR=UT.USER_SNR
			left outer join KOMMLOC K on u.USER_SNR = K.KLOC_SNR
			LEFT OUTER JOIN IMS_BRICK bri on bri.IMSBRICK_SNR = CP.IMSBRICK_SNR
			WHERE I.REC_STAT=0 
			AND I.INST_SNR<>'00000000-0000-0000-0000-000000000000' 
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
<input type="hidden" id="hdnFiltrosExportarInst" value=""/>
<input type="hidden" id="hdnSelecciandoCambiarRutaInst" value="" />
<input type="hidden" id="hdnPaginaInst" value="1" />
<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2>
				<i class="material-icons">business</i>
				 INSTITUCIONES
			</h2>
			<div class="align-right" style="margin: -28px 35px 19px 0px;">
				<?php 
					if($tipoUsuario != 4){
				?>
					<input type="checkbox" id="chkSeleccionarTodosCambiarRutaInst" style="display:none;"/><font size="2"><label id="lblSeleccionarTodosCambiarRutaInst" style="display:none;">Seleccionar Todos</label></font>
					<button class="btn bg-red waves-effect" id="btnAceptarCambiarRutaInst" title="Aceptar cambiar ruta" style="display:none;">Aceptar</button>
					<button class="btn bg-red waves-effect" id="btnCancelarCambiarRutaInst" title="Cancelar cambiar ruta" style="display:none;">Cerrar</button>
					<button class="btn bg-red waves-effect" id="btnCambiarRutaInst" title="Cambiar ruta">Cambiar ruta</button>
					
				<?php
					}//else{
				?>
					<!--<button id="btnAprobacionesInst" title="Aprobaciones" class="btn bg-red waves-effect">Aprobaciones</button>-->
				<?php
					//}
				?>
				<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','re' );" id="btnReVisitadosInst" class="btn bg-red waves-effect"> Re-Visitados </button>
				<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','visitados' );" id="btnVisitadosInst" class="btn bg-red waves-effect"> Visitados </button>
				<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','no' );" id="btnNoVisitadosInst" class="btn bg-red waves-effect"> No Visitados </button>
				<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','' );" id="btnTodosInst" class="btn bg-red waves-effect"> Todos </button>
			</div>
		</div>
		
		<div id="tabsInstituciones" style="font-size:10px" >
			<div class="right-sidebar-person">
				<div style="display: inline-grid;">
					<ul class="no-style-list">
						<li><a href="#infoInstitucion"><button type="button" class="btn btn-default waves-effect add-margin-bottom">
							<i class="material-icons pointer">contact_mail</i>
						</button></a></li>
						<li><a href="#mapaInstitucion" id="lkMapaInstituciones"><button type="button" class="btn btn-default waves-effect add-margin-bottom">
							<i class="material-icons pointer">map</i>
						</button></a></li>
						<li><a href="#planInstitucion"><button type="button" class="btn btn-default waves-effect add-margin-bottom">
							<i class="material-icons pointer">today</i>
						</button></a></li>
						<li><a href="#visitasInstitucion"><button type="button" class="btn btn-default waves-effect add-margin-bottom">
							<i class="material-icons pointer">pan_tool</i>
						</button></a></li>
						<li><button type="button" class="btn btn-default waves-effect add-margin-bottom">
							<i class="material-icons pointer">question_answer</i>
						</button></li>
						<li><a href="#MuestrasInstitucion"><button type="button" class="btn btn-default waves-effect add-margin-bottom">
							<i class="material-icons pointer">event_note</i>
						</button></a></li>
						<li><button type="button" class="btn btn-default waves-effect add-margin-bottom">
							<i class="material-icons pointer">layers</i>
						</button></li>
						<li><button type="button" class="btn btn-default waves-effect add-margin-bottom">
							<i class="material-icons pointer">ondemand_video</i>
						</button> </li>
					</ul>
				</div>
			</div>
		<!-- #END# Right Sidebar -->
		
		<!-- Exportable Table -->
			<div class="row clearfix">
				<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 add-padding-listm">
					<div class="card">
						<div class="header">
							<h2>
								Farmacias
							</h2>
							<!--<div class="header-dropdown" style="margin-top:-9px;">-->
							<div class="align-right" style="margin: -29px 0px -11px 0px;">
								<button  class="btn bg-blue btn-circle waves-effect waves-circle waves-float" id="btnActualizarInst" title="Actualizar">
									<i class="material-icons">sync</i>
								</button>
								
								<button onClick="exportarExcelPersonas('<?= $hoy ?>','<?= $idsEnviar ?>');" id="btnExportarInst" title="Descargar" class="btn bg-blue btn-circle waves-effect waves-circle waves-float">
									<i class="material-icons">cloud_download</i>
								</button>
							</div>
						</div>
						<div class="body">
							<div id="divGridInstituciones" >	
<?php
								/*$registrosPorPaginaInst = 10;
								
								$queryInst = queryInstituciones('', $ids);
								//echo $queryInst."<br>";
								$tope = "OFFSET 0 ROWS 
									FETCH NEXT ".$registrosPorPaginaInst." ROWS ONLY ";
								
								$rsInst = sqlsrv_query($conn, $queryInst, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
								
								$totalRegistrosInst = sqlsrv_num_rows($rsInst);
								
								$queryInst20 = sqlsrv_query($conn, $queryInst.$tope);
								
								//echo $queryInst.$tope;
								
								$paginasInst = ceil($totalRegistrosInst / $registrosPorPaginaInst);
								$tabla = '';
								$i = 1;*/
?>
								<div id="tbFarmacias" >
									<!--<table class="table table-striped table-hover dataTable">
											<thead>
												
											</thead>
											
											<tbody>
<?php 
										/*$queryFarm = queryInstituciones('FARMACIAS', $ids);
							
										$rsFarm = sqlsrv_query($conn, $queryFarm, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
										
										$totalRegistrosFarm = sqlsrv_num_rows($rsFarm);
										
										$queryInst20Farm = sqlsrv_query($conn, $queryFarm.$tope);
										
										//echo $queryInst.$tope;
										
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
														<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer' onclick='presentaDatos(\"".$farmacia['INST_SNR']."\",\"divDatosInstituciones\",\"FARMACIAS\",\"\",\"".$farmacia['USUARIO_ID']."\");'>
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
																<i class='material-icons pointer'>edit</i>
															</button>
															
															<button type='button' class='btn bg-indigo btn bg-indigo waves-effect little-button' title='Eliminar' onClick='eliminarInst(\"".$farmacia['INST_SNR']."\",\"".$datosInst."\",\"".$farmacia['USUARIO_ID']."\");'>
																<i class='material-icons pointer'>delete</i>
															</button>
														</div>								
													</div>
												</td>
											</tr>";
										}*/
?>
										</tbody>
										<tfoot>
												<tr>
													<td colspan="14" align="center">
<?php
														/*for($i=1;$i<=$paginasFarm;$i++){
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
														echo "Pag. 1 de ".$paginasFarm."&nbsp;&nbsp;&nbsp; Registros : ".$totalRegistrosFarm;*/
?>
													</td>
												</tr>
											</tfoot>
										</table>-->
									</div>
								</div>
			
							</div>
					</div>
				</div>
				<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12 add-padding-persona">
					<div class="card">
						<div class="header">
							<h2 id="lblInstTipo" class="lblMedicos">
							</h2>
							<ul class="header-dropdown m-r--5">
								<li class="dropdown">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
										<i class="material-icons">more_vert</i>
									</a>
									<ul class="dropdown-menu pull-right">
										<li><a href="javascript:void(0);">Action</a></li>
										<li><a href="javascript:void(0);">Another action</a></li>
										<li><a href="javascript:void(0);">Something else here</a></li>
									</ul>
								</li>
							</ul>
						</div>
						<div class="body">
							<div id="divDatosInstituciones"  style="display:none;">
								<?php include "datosInstituciones.php"; ?>
							</div>
						</div>
					</div>
				</div>
			</div>				
		</div>
		<!-- #END# Datos Hospitales -->
		
		<div class="button-float pull-right list-btn-float">
			<button class="btn bg-red btn-circle waves-effect waves-circle waves-float add-margin-bottom" title="Filtrar" id="imgFiltrar2Inst"> 
				<span class="glyphicon glyphicon-filter"></span> 
			</button>
		
			<button class="btn bg-red btn-circle waves-effect waves-circle waves-float" title="Agregar" id="imgAgregarInstitucion">
				<i class="material-icons">add</i>
			</button>
		</div>
	</div>
</section>


<table width="100%">	
	<tr id="trFiltrosInst" style="display:none;">
		<td>
			<table width="100%" border="0">
				<tr>
					<td class="tituloModulo">
						Filtrar
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<!--<div id="tabFiltrosInstituciones">						
							<div id="tabs-1">-->
								<table id="tblFiltros" width="100%" border="0">
									<tr>
										<td class='negrita'>
											Tipo:
										</td>
										<td> 
											<div class="select">
											<select id="sltTipoInstFiltro" style="width:150px;">
<?php
												$rsTipoInst = sqlsrv_query($conn, "select * from INST_TYPE where REC_STAT = 0 order by name");
												while($regInst = sqlsrv_fetch_array($rsTipoInst)){
													echo '<option value="'.$regInst['INST_TYPE'].'">'.$regInst['NAME'].'</option>';
												}
?>
												
											</select>
											<div class="select_arrow"></div></div>
										</td>
										<td class='negrita'>
											Nombre:
										</td>
										<td>
											<input type="text" id="txtNombreInstFiltro" />
										</td>
										<td class='negrita'>
											Calle:
										</td>
										<td>
											<input type="text" id="txtCalleInstFiltro" />
										</td>
										<td class='negrita'>
											Colonia:
										</td>
										<td>
											<input type="text" id="txtColoniaInstFiltro" />
										</td>
										<td class='negrita'>
											Ciudad:
										</td>
										<td>
											<input type="text" id="txtCiudadInstFiltro" />
										</td>
									</tr>
									<tr>
										<td class='negrita'>
											Estado:
										</td>
										<td>
											<input type="text" id="txtEstadoInstFiltro" />
										</td>
										<td class='negrita'>
											CP:
										</td>
										<td>
											<input type="text" id="txtCPInstFiltro" />
										</td>
										<td colspan="2" class="negrita">
											<input type="radio" name="rbGeo" id="rbtTodos" value="">Todos
											<input type="radio" name="rbGeo" id="rbtGeoSi" value="GeoSi">Geolocalizados
											<input type="radio" name="rbGeo" id="rbtGeoNo" value="GeoNo">No Geolocalizados
										</td>
<?php
										if($tipoUsuario != 4){
											echo "<td class='negrita'>Representante:</td>
												<td>
													<div class=\"selectBox\" onclick=\"filtrosUsuarios('inst');\">
														<div class=\"select\">
															<select style=\"width:250px\" >
																<option id=\"sltMultiSelectInst\">Seleccione</option>
															</select><div class=\"select_arrow\"></div>
														</div>
													</div>
												</td>";
										}else{
											echo "<td>&nbsp;</td><td>&nbsp;</td>";
										}
?>
										<td colspan="2" align="center">
											<button style="width:80px;" onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','' );" id="btnEjecutarFiltroInst" type="button"> Filtrar </button>
											<button style="width:80px;" id="btnLimpiarFiltrosInst" type="button">Limpiar</button>
										</td>
									</tr>
								</table>
							<!--</div>
						</div>-->
					</td>
				</tr>
			</table>
		</td>
	</tr>
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