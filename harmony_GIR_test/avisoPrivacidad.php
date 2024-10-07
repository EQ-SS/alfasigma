<input type="hidden" id="hdnIdPersonaAvisoPrivacidad" val="">
<div class="row m-r--15 m-l--15">
	<div class="col-lg-6 col-md-8 col-sm-10 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<h2>
						AVISO DE PRIVACIDAD
					</h2>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">
					<!--<button id="btnEliminarPlanPerson" type="button" class="btn bg-indigo waves-effect  btn-indigo" <?=($tipoUsuario !=2)
					 ? "disabled" : "" ?> >
						Borrar
					</button>-->
					<!--<button id="btnReportarPlan" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
						Reportar
					</button>-->
					<button id="btnGuardarAvisoPrivacidad" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
						Guardar
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCancelarAvisoPrivacidad" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
					<div class="new-div add-scroll-y">
						<div class="row">
							<div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<div class="card margin-0 card-plan-visita">
									<div class="body align-justify">
										<div id="datos">
<?php
											$qAvisoPrivacidad = "SELECT RULE_NAME, VALUE_STR 
												FROM SETTINGS 
												WHERE TABLE_NR = 19 
												ORDER BY RULE_NAME";

											$rsAvisoPrivacidad = sqlsrv_query($conn, $qAvisoPrivacidad);

											while($aviso = sqlsrv_fetch_array($rsAvisoPrivacidad)){
												echo '<span>'.utf8_encode($aviso['VALUE_STR']).'</span><br><br>';
											}
?>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
								<div class="form-group margin-0">
									<label>Nombre del Médico</label>
									<input type="text" class="form-control" id="txtNombreMedicoAviso" readonly value="" />
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
								<div class="form-group margin-0">
									<label>Fecha del Plan</label>
									<input type="text" class="form-control" onChange="cambiarFecha('txtFechaAvisoPrivacidad');" id="txtFechaAvisoPrivacidad" value=""
									 size="10" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-0 ">
										<center>
										<div class="sigPad" id="tblFirmaAviso" style="width:422px;">
											<div class="sig sigWrapper canvaPer">
												<div class="typed"></div>
												<canvas id="canvasFirmaAviso" class="pad" width="400" height="250"></canvas>
												<input type="hidden" id="hdnFirmaAviso" name="output-2" class="output">
											</div>
											<div style="margin:10px;" class="align-center">
												Firmar sin salir del recuadro
												<li class="clearButton">
													<a href="#clear">
														<button id="btnLimpiarFirmaAviso" class="btn btn-default waves-effect btn-indigo2">
															Limpiar
														</button>
													</a>
												</li>
											</div>
										</div>
										<img id="imgFirmaAvisoPrivacidad"/>
										<input class="form-control" type="hidden" id="f64Aviso" />
									</center>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>