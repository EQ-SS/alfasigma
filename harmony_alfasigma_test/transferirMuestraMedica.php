<input type="hidden" id="hdnPiezasDisponibles" value=""/>
<input type="hidden" id="hdnIdRepreTransferirMuestraMedica" value=""/>
<input type="hidden" id="hdnIdStockransferirMuestraMedica" value=""/>

<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Transferir Muestra Médica
					</h2>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">
					<button id="btnGuardarTransferirMuestraMedica" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
						Guardar
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCerrarTransferirMuestraMedica" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<!--<div class="new-div">-->
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="bg-cyan nombre-cuenta font-16">
									<i class="fas fa-user-md font-16"></i>
									<span id="lblRepreTransferirMuestraMedica" class="p-l-5"></span>
								</div>
								<div class="card margin-0 card-plan-visita">
									<div class="body">
										<div id="datos">
											<p><span id="lblPiezasTransferirMuestraMedica" class="label bg-red label-esp"></span></p>
											<b>Producto: </b><span id="lblProductoTransferirMuestraMedica"></span><br>
											<b>Presentación: </b><span id="lblPresentaciónTransferirMuestraMedica"></span><br>
											<b>Lote: </b> <span id="lblLoteTransferirMuestraMedica"></span><br>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								Buscar representante: 
								<input type="text" class="form-control" id="txtBuscarRepreTransferirMuestraMedica" />
								<br/>
								<div style="height:300px;overflow:auto;">
									<table id="tblRepresentantesMuestraMedica" class="table table-striped">
										<thead>
											<tr class="bg-cyan">
												<td>Seleccione el representante</td>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<span id="lblProductoTransferirMuestraMedica">
									Ingrese la cantidad a transferir
								</span>
								<input type="text" id="txtCantidadTransferir" class="form-control align-right"  />
								<br><br><br>
								<div style="height:300px;overflow:auto;" class="border border-primary">
									<center>
										<span class="font-bold col-indigo text-inline">
											Piezas disponibles después de transferir
										</span><br>
										<h1 id="lblRestanteMuestraMedica" class="font-bold col-indigo text-inline">
											0
										</h1>
									</center>
								</div>
							</div>
						</div>
					<!--</div>-->
				</div>
			</div>
		</div>
	</div>
</div>