﻿<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2 class="display-flex">
				<i class="fas fa-calendar-check"></i>
				<span class="p-t-5 m-l-5">Coaching</span>
			</h2>
		</div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card" style="border-top:2px #F44336 solid;">
					<div class="body">
						<div id="divEncuestas">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="div-tbl-aprob-pers">
										<table id="tblEncuestas" class="table-striped" width="100%">
											<thead class="bg-cyan">
												<tr class="align-center">
													<td style="width:<?= $tipoUsuario == 4 ? '50' : '40' ?>%;">Nombre</td>
													<td style="width:25%;">Fecha Inicial</td>
													<td style="width:25%;">Fecha Final</td>
<?php
													if($tipoUsuario != 4){
?>
														<td style="width:10%;">Calificar</td>
<?php
													}
?>
												</tr>
											</thead>
											<tbody style="height: 300px">
											</tbody>
										</table>
									</div>
									<hr>
									<div class="div-tbl-aprob-pers">
										<table id="tblEncuestasCalificadas" class="table-striped" width="100%">
											<thead class="bg-cyan">
												<tr class="align-center">
													<td style="width:50%;">Ruta y Nombre</td>
													<td style="width:25%;">Fecha 1</td>
													<td style="width:25%;">Fecha 2</td>
													<td style="width:25%;">Fecha 3</td>
												<!--	<td style="width:25%;">Comentario</td>
													<td style="width:25%;">Estatus</td>-->
												</tr>
											</thead>
											<tbody style="height: 300px">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<!--<div class="fixed-action-btn">
							<a class="btn-floating btn-large btn-txt bg-red btn-red" id="imgAgregarEvento">
								<i class="material-icons">add</i><span id="spanP" class="col-white m-l-10"
									style="display:none;">AGREGAR</span>
							</a>
						</div>-->
					</div>
				</div>
			</div>
		</div>
	</div>
</section>