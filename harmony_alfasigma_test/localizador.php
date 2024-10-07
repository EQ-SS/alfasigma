<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<div class="row p-t-15">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<h2 class="display-flex">
					<i class="material-icons">place</i>
					<span class="p-t-5 m-l-5">LOCALIZADOR</span>
				</h2>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 align-right">
					<button type="button" class="btn bg-red waves-effect" id="btnLocalizarFiltro" title="Buscar">
						<i class="material-icons">search</i>
						<span>Buscar</span>
					</button>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<!--LISTA PLANES/VISITAS HOY-->
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card" style="border-top:2px #F44336 solid;">
					<div class="body">
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 margin-0">
								<div class="form-group">
									<label>Kilómetros</label>
									<select class="form-control" id="sltKilometros">
										<option value="0.5">0.5</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="10">10</option>
										<option value="20">20</option>
									</select>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-0">
								<div class="form-group">
									<label>Visitas</label>
									<select class="form-control" id="sltVisitas">
										<option value="">--Seleccione--</option>
										<option value="0">No visitados</option>
										<option value="1">Visitados</option>
									</select>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-0">
								<div class="form-group">
									<label>Especialidad</label>
									<select id="sltEspecialidadRadar" class="form-control">
										
										<option value="00000000-0000-0000-0000-000000000000">--Seleccione--</option>
										<?php
										$rsEsp = llenaCombo($conn, 19, 1);
										while($esp = sqlsrv_fetch_array($rsEsp)){
												echo '<option value="'.$esp['id'].'">'.$esp['nombre'].'</option>';
										}
		?>
									</select>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-0">
								<div class="form-group">
									<label>Tipo</label>
									<select id="sltTipo" class="form-control">
										<option value="" >--Seleccione--</option>
										<option value="p">Personas</option>
										<option value="i">Instituciones</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 margin-0">
								<div class="form-group">
									<label>Tipo Institución</label>
									<select id="sltTipoInst" class="form-control">
										<option value="0">--Seleccione--</option>
										<?php
								$rsTipoInst = sqlsrv_query($conn, "select * from INST_TYPE where rec_stat = 0 and inst_type <> 3");
									while($tipoInst = sqlsrv_fetch_array($rsTipoInst)){
										if($tipoInst['INST_TYPE'] != 0){
											echo '<option value="'.$tipoInst['INST_TYPE'].'">'.$tipoInst['NAME'].'</option>';
										}
									}
		?>
									</select>
									<label id="txtTipoInstError" class="error2" style="display:none;">Seleccione tipo de institución</label>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-0">
								<div class="form-group">
									<label>Apellido de Médico</label>
									<input id="txtNombreMedicoLocalizador" type="text" class="form-control" placeholder="">
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-0">
								<div class="form-group">
									<label>Nombre Institución</label>
									<input id="txtNombreInstLocalizador" type="text" class="form-control" placeholder="">
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<?php
									if($tipoUsuario != 4){
										echo "<div class='form-group'>
											<label>Representante:</label>
											<div class=\"selectBox\" onclick=\"filtrosUsuarios('geo');\">
												<select class='form-control'>
													<option id=\"sltMultiSelectGeo\">Seleccione</option>
												</select>
											</div>
										</div>";
									}
		?>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-center">
								<div id="mapa" class="map-style"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>