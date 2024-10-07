<input type="hidden" id="hdnIdProdFormAjuste" />
<input type="hidden" id="hdnCantidadAjuste" />

<section class="algo">
	<div class="row">
		<div id="tblDatosAjuste" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
			<div class="card m-b--15 card-add-new">
				<div class="header row padding-0">
					<div class="col-lg-10 col-md-10 col-sm-8 col-xs-10 m-t-10 m-b-10">
						<h2 class="p-t-10">
							Información del producto
						</h2>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
						<p id="btnCancelarMuestra" class="pointer p-t-5" onClick="cerrarInformacion();">
							<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
						</p>
					</div>
				</div>
				<div class="body">
					<div class="aprobacion-div add-scroll-y">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="m-b-20">
									<label id="lblProducto" class="font-15"></label>
								</div>
								<div class="form-group">
									<label class="col-red">Cantidad Recibida *</label>
									<input type="text" class="form-control" id="txtCantidadRecibida" value="" />
								</div>
								<div class="form-group">
									<label class="col-red">Motivo *</label>
									<select id="sltMotivo" class="form-control">
										<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
										<?php
											$rsMotivo = llenaCombo($conn, 374, 638);
											while($motivo = sqlsrv_fetch_array($rsMotivo)){
												echo '<option value="'.$motivo['id'].'">'.utf8_encode($motivo['nombre']).'</option>';
											}
										?>
									</select>
								</div>
								<div class="align-center">
									<button id="btnAceptarMuestra" type="button" class="btn bg-indigo waves-effect btn-indigo">
										Aceptar
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>