<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="div-btn-close pointer" onClick="cerrarInformacion();" data-toggle="tooltip" data-placement="bottom" title="Cerrar">
			<i class="material-icons"> close</i>
		</div>
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<div class="form-group display-flex margin-0">
						<label class="m-r-5" style="width: 160px; padding-top: 7px;">Tipo Movimiento: </label>
						<select id="sltTipoMovimientoAprobacionesPers" class="form-control m-b-15">
							<option value="" hidden>Seleccione</option>
							<option value="">Todos</option>
							<option value="D">Borrar</option>
							<option value="C">Cambio</option>
							<option value="N">Nuevo</option>
						</select>
					</div>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 align-right m-t-15 menu-aprobP-sm">
					<div class="display-inline">
						<button id="btnPendienteAprobacionesPers" class="btn waves-effect btn-aprob-head btn-aprob-l btn-aprob-sel" disabled> 
							Pendiente
						</button>
						<button id="btnAceptadoAprobacionesPers" class="btn waves-effect btn-aprob-head btn-aprob-c">
							Aceptado
						</button>
						<button id="btnRechazadoAprobacionesPers" class="btn waves-effect btn-aprob-head btn-aprob-r">
							Rechazado
						</button>
					</div>
				</div>
				<!--<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 align-right m-t-10 m-b-10">
					<button id="btnPendienteAprobacionesPers" class="btn bg-blue waves-effect btn-blue">Pendiente</button>
					<button id="btnAceptadoAprobacionesPers" class="btn bg-blue waves-effect btn-blue">Aceptado</button>
					<button id="btnRechazadoAprobacionesPers" class="btn bg-blue waves-effect btn-blue">Rechazado</button>
				</div>-->
			</div>
			<div class="body">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="new-div">
						<div class="div-tbl-aprob-pers">
							<table class="table-striped" id="tblAprobacionesPers">
								<thead class="bg-cyan">
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<div class="font-bold" id="numRegistrosAprobPers"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>