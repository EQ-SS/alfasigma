<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div id="cerrarInfoInst" class="div-btn-close pointer" onClick="cerrarInformacion();" data-toggle="tooltip"
		 data-placement="bottom" title="Cerrar">
			<i class="material-icons"> close</i>
		</div>
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-15 m-b-15">
					<div class="display-inline">
						<button id="btnPendienteAprobacionesInst" class="btn waves-effect btn-aprob-head btn-aprob-l btn-aprob-sel"
						 disabled>
							Pendiente
						</button>
						<button id="btnAceptadoAprobacionesInst" class="btn waves-effect btn-aprob-head btn-aprob-c">
							Aceptado
						</button>
						<button id="btnRechazadoAprobacionesInst" class="btn waves-effect btn-aprob-head btn-aprob-r">
							Rechazado
						</button>
					</div>
				</div>
			</div>
			<div class="body">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="new-div">
						<div class="div-tbl-aprob-pers">
							<table class="table-striped" id="tblAprobacionesInst">
								<thead class="bg-cyan">
									<tr>
										<td style="width:6%;">
											Tipo
										</td>
										<td style="width:15%;">
											Colonia
										</td>
										<td style="width:20%;">
											Nombre
										</td>
										<td style="width:20%;">
											Dirección
										</td>
										<td style="width:10%;">
											Teléfono
										</td>
										<td style="width:10%;">
											Clasificación
										</td>
										<td style="width:10%;">
											Fecha
										</td>
										<td style="width:9%;">
											Estatus
										</td>
									<tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<div class="font-bold" id="numRegistrosAprobInst"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>