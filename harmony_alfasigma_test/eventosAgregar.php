<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Eventos
					</h2>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">
					<button id="btnGuardarEventoAgregar" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
						Guardar
					</button><input type="hidden" id="hdnIdEventoAgregar"/>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCancelarEventoAgregar" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="div-tbl-aprob-pers">
							<table name="tblEventosAgregar" id="tblEventosAgregar" width="100%" class="table-striped table-hover">
								<thead class="bg-cyan">
									<tr class="align-center">
										<td style="width:20%;">Tipo</td>
										<td style="width:20%;">Nombre</td>
										<td style="width:20%;">Lugar</td>
										<td style="width:10%;">Fecha Inicial</td>
										<td style="width:10%;">Fecha Final</td>
										<td style="width:20%;">Comentarios</td>
									</tr>
								</thead>
								<tbody style="height:450px;">
								</tbody>
								<tfoot class="bg-cyan">
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>