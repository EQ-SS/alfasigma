<input type="hidden" id="hdnIdInversion" value="" />
<input type="hidden" id="hdnIdInversionReportar" value="" />
<input type="hidden" id="hdnPantallaInversion" value="" />
<input type="hidden" id="hdnRegreso" value="" />
<input type="hidden" id="hdnIdVisitaInversion" value="" />

<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Inversiones
					</h2>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">
					<button id="btnEliminarInversionPerson" type="button" class="btn bg-indigo waves-effect  btn-indigo">
						Borrar
					</button>
					<button id="btnGuardarInversion" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
						Guardar
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCancelarInversion" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="new-div add-scroll-y">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="bg-cyan nombre-cuenta font-16">
									<i class="fas fa-user-md font-16"></i>
									<span id="lblMedicoInversion" class="p-l-5"></span>
								</div>
								<div class="card margin-0 card-plan-visita">
									<div class="body">
										<div id="datos">
											<p><span id="lblEspecialidadInversion" class="label bg-red label-esp"></span></p>
											<b>Categoría: </b><span id="lblCategoriaInversion"></span><br>
											<!--<b>Consultorio: </b><span id="lblInstInversion"></span><br>
											<b>Dirección: </b> <span id="lblCalleInversion"></span>, <span id="lblColoniaInversion"></span>,
											<span id="lblCPInversion"></span>, <span id="lblDelegacionInversion"></span>,
											<span id="lblEstadoInversion"></span>, <span id="lblBrickInversion"></span>-->
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div id="tblDatos">
									<div class="row">
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group margin-0">
												<label class="col-red">Representante *</label>
												<select id="sltRepreInversion" class="form-control" disabled>
												</select>
											</div>
										</div>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group margin-0">
												<label class="col-red">Concepto *</label>
												<input type="text" id="txtConceptoInversion" class="form-control" />
											</div>
										</div>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group  margin-0">
												<label>Tipo de Inversion</label>
												<select id="lstCodigoInversion" class="form-control">
													<option value="" hidden>Seleccione</option>
<?php
														$rsCodigo = llenaCombo($conn, 16,5);
														while($codigo = sqlsrv_fetch_array($rsCodigo)){
															echo '<option value="'.$codigo['id'].'" >'.utf8_encode($codigo['nombre']).'</option>';
														}
?>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group  margin-0">
											<label>Producto</label>
											<form>
												<div class="multiselect">
													<div class="selectBox" onclick="showCheckboxesInversiones();">
														<select class="form-control">
															<option id="sltMultiSelectInversiones">Seleccione</option>
														</select>
														<div class="overSelect"></div>
													</div>
													<div id="checkboxesInversiones" style="display:none;">
<?php
														$rsInv = sqlsrv_query($conn, "select name as nombre, prod_snr as id from PRODUCT where prod_snr <> '00000000-0000-0000-0000-000000000000' order by name");
														$contadorChecksInv = 0;
														$idChecksInv = '';
														$descripcionesCheckInv = '';
														while($inv = sqlsrv_fetch_array($rsInv)){
															$contadorChecksInv++;
															$idChk = "inversion".$contadorChecksInv;
															echo '<input type="checkbox" id="'.$idChk.'" class="filled-in chk-col-red"  onclick="agregaDesInversiones(\''.$inv['nombre'].'\',\''.$idChk.'\');" value="'.$inv['id'].'"/>';
															echo '<label for="'.$idChk.'">'.$inv['nombre'].'</label>';
														}
?>
													</div>
												</div>
												<input type="hidden" id="hdnTotalChecksInversiones" value="<?= $contadorChecksInv ?>">
												<input type="hidden" id="hdnDescripcionChkInversiones" value="<?= ($descripcionesCheckInv == '') ? "Seleccione" : $descripcionesCheck ?>" />
											</form>
											</div>
										</div>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group margin-0">
												<label class="col-red">Fecha del Inversion *</label>
												<input type="text" class="form-control" onChange="cambiarFecha('txtFechaInversion');" id="txtFechaInversion" value=""
												 size="10" readonly />
											</div>
										</div>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group margin-0">
												<label>Cantidad Invertida</label>
												<input type="number" class="form-control" id="txtCantidadInversion" value="0"/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-0">
											<div class="form-group margin-0">
												<label>Comentarios</label>
												<textarea id="objetivoInversion" rows="6" class="text-notas2"></textarea>
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
	</div>
</div>