<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2 class="display-flex">
				<i class="fas fa-calendar-check"></i>
				<span class="p-t-5 m-l-5">EVENTOS</span>
			</h2>
		</div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card" style="border-top:2px #F44336 solid;">
					<div class="body">
						<div id="divEventos">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="div-tbl-aprob-pers">
										<table id="tblEventos" class="table-striped" width="100%">
											<thead class="bg-cyan">
												<tr class="align-center">
													<td style="width:25%;">Tipo</td>
													<td style="width:25%;">Nombre</td>
													<td style="width:25%;">Lugar</td>
													<td style="width:15%;">Fecha Inicial</td>
													<td style="width:15%;">Fecha Final</td>
													<td style="width:20%;">Comentarios</td>
													<td style="width:5%;">Editar</td>
													<!--<td style="width:5%;">Eliminar</td>-->
												</tr>
											</thead>
											<tbody style="height: 300px">
											</tbody>
										</table>
									</div>
									<hr>
									<div class="div-tbl-aprob-pers">
										<table id="tblAsistentesEventos" class="table-striped" width="100%">
											<thead class="bg-cyan">
												<tr class="align-center">
													<td style="width:25%;">Nombre</td>
													<td style="width:25%;">Especialidad</td>
													<td style="width:15%;">Tipo de Institución</td>
													<td style="width:25%;">Dirección</td>
													<td style="width:10%;">Asistió</td>
												</tr>
											</thead>
											<tbody style="height: 300px">
											</tbody>
										</table>
									</div>
									<input type="hidden" id="hdnAsistentes" />
									<input type="hidden" id="hdnFaltantes" />
								</div>
							</div>
						</div>
						<div class="fixed-action-btn">
							<a class="btn-floating btn-large btn-txt bg-red btn-red" id="imgAgregarEvento">
								<i class="material-icons">add</i><span id="spanP" class="col-white m-l-10"
									style="display:none;">AGREGAR</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>