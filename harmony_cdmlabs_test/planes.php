<input type="hidden" id="hdnIdPlan" value="" />
<input type="hidden" id="hdnIdPlanReportar" value="" />
<input type="hidden" id="hdnPantallaPlan" value="" />
<input type="hidden" id="hdnRegreso" value="" />
<input type="hidden" id="hdnIdVisitaPlan" value="" />

<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Planes
					</h2>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">
					<button id="btnEliminarPlanPerson" type="button" class="btn bg-indigo waves-effect  btn-indigo" <?=($tipoUsuario !=2)
					 ? "disabled" : "" ?>>
						Borrar
					</button>
					<button id="btnReportarPlan" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
						Reportar
					</button>
					<button id="btnGuardarPlan" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
						Guardar
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCancelarPlan" class="pointer p-t-5 btn-close-per">
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
									<span id="lblMedicoPlan" class="p-l-5"></span>
								</div>
								<div class="card margin-0 card-plan-visita">
									<div class="body">
										<div id="datos">
											<p><span id="lblEspecialidadPlan" class="label bg-red label-esp"></span></p>
											<b>Frecuencia: </b><span id="lblFrecuenciaPlan"></span><br>
											<b>Consultorio: </b><span id="lblInstPlan"></span><br>
											<b>Dirección: </b> <span id="lblCallePlan"></span>, <span id="lblColoniaPlan"></span>,
											<span id="lblCPPlan"></span>, <span id="lblDelegacionPlan"></span>,
											<span id="lblEstadoPlan"></span>, <span id="lblBrickPlan"></span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div id="tblDatos">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group margin-0">
												<label class="col-red">Representante *</label>
												<select id="sltReprePlan" class="form-control">
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
											<div class="form-group margin-0">
												<label class="col-red">Fecha del Plan *</label>
												<input type="text" class="form-control" onChange="cambiarFecha('txtFechaPlan');" id="txtFechaPlan" value=""
												 size="10" />
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group  margin-0">
												<label>Hora del Plan</label>
												<div class="display-flex">
													<select id="lstHoraPlan" class="form-control">
														<?php
							for($i=0;$i<24;$i++){
								echo '<option value="'.str_pad($i,2,'0', STR_PAD_LEFT).'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
							}
	?>
													</select>
													<span id="spnPuntosHora">:</span>

													<select id="lstMinutosPlan" class="form-control">
														<?php
							for($i=0;$i<60;$i++){
								echo '<option value="'.str_pad ($i,2,'0', STR_PAD_LEFT).'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
							}
	?>
													</select>
												</div>
											</div>
										</div>
										<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
											<div class="form-group  margin-0">
												<label>Código del Plan</label>
												<select id="lstCodigoPlan" class="form-control">
													<option value="" hidden>Seleccione</option>
													<?php
														$rsCodigo = llenaCombo($conn, 99,147);
														while($codigo = sqlsrv_fetch_array($rsCodigo)){
															echo '<option value="'.$codigo['id'].'" >'.utf8_encode($codigo['nombre']).'</option>';
														}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-0">
											<div class="form-group margin-0">
												<label>Información de la última visita</label>
												<textarea id="objetivoPlan" rows="6" class="text-notas2"></textarea>
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