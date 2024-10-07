<input type="hidden" id="hdnCityDepto" value="" />

<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Departamento
					</h2>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">
					<button id="btnGuardarDepartamento" type="button" class="m-t-5 btn bg-indigo waves-effect btn-indigo">
						Guardar
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCancelarDepartamento" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="new-div add-scroll-y" id="datosDepartamentos">
							<form id="formAgregarDepto">
								<div class="row">
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="col-red">Tipo departamento *</label>
											<select name="tipoD" id="sltTipoDepartamento" class="form-control" required>
												<?php
												$rsTipoDepto = llenaCombo($conn, "19", "507");
												while($tipo = sqlsrv_fetch_array($rsTipoDepto)){
													echo '<option value="'.$tipo['id'].'">'.utf8_encode($tipo['nombre']).'</option>';
												}
?>
											</select>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="col-red">Nombre departamento *</label>
											<input type="text" name="nombreD" class="form-control" value="" id="txtNombreDepartamento" required/>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="col-red">Nombre del responsable *</label>
											<input type="text" class="form-control" name="nombreResD" value="" id="txtNombreResponsableDepto" required/>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>País</label>
											<select disabled class="form-control">
												<option selected>México</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Calle</label>
											<input type="text" class="form-control" value="" id="txtCalleDepto" />
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Código Postal</label>
											<input name="codigoPostalD" type="text" class="form-control" value="" id="txtCPDepto" maxlength="5"/>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Colonia</label>
											<select id="sltColoniaDepto" class="form-control">
											</select>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Ciudad</label>
											<input type="text" value="" class="form-control" id="txtCiudadDepto" disabled />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Estado</label>
											<input type="text" value="" class="form-control" id="txtEstadoDepto" disabled />
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Brick</label>
											<input type="text" class="form-control" value="" id="txtBrickDepto" disabled />
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Teléfono 1</label>
											<input type="text" class="form-control" id="txtTelefono1Depto" value="" />
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Teléfono 2</label>
											<input type="text" class="form-control" id="txtTelefono2Depto" value="" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Celular</label>
											<input type="text" class="form-control" id="txtCelularDepto" value="" />
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Email</label>
											<input name="emailD" type="text" class="form-control" id="txtEmailDepto" value="" />
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Estatus</label>
											<select id="sltEstatusDepto" disabled class="form-control">
												<?php
												$rsEstatus = llenaCombo($conn, "20", "12");
												while($arrEstatus = sqlsrv_fetch_array($rsEstatus)){
													if($arrEstatus['nombre'] == 'ACTIVO'){
														echo '<option value="'.$arrEstatus['id'].'" selected>'.utf8_encode($arrEstatus['nombre']).'</option>';
													}else{
														echo '<option value="'.$arrEstatus['id'].'">'.utf8_encode($arrEstatus['nombre']).'</option>';
													}
												}
?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Comentarios</label>
											<textarea rows="5" id="txtComentariosDepto" class="text-notas2"></textarea>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>