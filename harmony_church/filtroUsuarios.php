<input type="hidden" id="hdnIdsFiltroUsuarios" value="" />
<input type="hidden" id="hdnNombresFiltroUsuarios" value="" />
<input type="hidden" id="hdnPantallaFiltroUsuarios" value="" />

<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Seleccionado (s)
					</h2>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">

				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCancelarFiltro" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body" id="tblFiltroUsuarios">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="new-div add-scroll-y">
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12  display-inline">
								<button id="btnSeleccionarTodos" class="m-t-5 btn bg-indigo waves-effect btn-indigo">Seleccionar Todos</button></td>
								<button id="btnQuitarSeleccion" class="m-t-5 btn bg-indigo waves-effect btn-indigo m-l-10">Quitar Selección</button></td>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12  align-right">
								<button id="btnEjecutarFiltroUsuarios"  class="m-t-5 btn bg-indigo waves-effect btn-indigo">Aceptar</button></td>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Buscar</label>
									<input type="text" class="form-control" id="txtBuscarFiltroUsuarios" value="" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="multiSltRepre">
									<table id="tblUsuariosFiltros" width="100%" class="pointer">
									</table>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="multiSltRepre pull-right">
									<table id="tblUsuariosSeleccionados" class="pointer">
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
