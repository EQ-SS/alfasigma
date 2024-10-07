<input type="hidden" id="hdnIdsFiltroMercados" value="" />
<input type="hidden" id="hdnNombresFiltroMercados" value="" />
<input type="text" id="hdnPantallaFiltroMercados" value=""/>

<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Seleccionado(s)
					</h2>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">

				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCancelarFiltroMercados" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body" id="tblFiltroMercados">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="new-div add-scroll-y">
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12  display-inline">
								<button id="btnSeleccionarTodosMercados" class="m-t-5 btn bg-indigo waves-effect btn-indigo">Seleccionar Todos</button></td>
								<button id="btnQuitarSeleccionMercados" class="m-t-5 btn bg-indigo waves-effect btn-indigo m-l-10">Quitar Selección</button></td>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12  align-right">
								<button id="btnEjecutarFiltroMercados"  class="m-t-5 btn bg-indigo waves-effect btn-indigo">Aceptar</button></td>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Buscar</label>
									<input type="text" class="form-control" id="txtBuscarFiltroMercados" value="" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="multiSltRepre">
									<table id="tblMercadosFiltros" width="100%" class="pointer">
									</table>
									
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="multiSltRepre">
									<table id="tblMercadosSeleccionadosFiltros" width="100%" class="pointer">
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>