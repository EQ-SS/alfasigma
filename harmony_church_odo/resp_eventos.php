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
													<td style="width:15%;">Fecha Inicial</td>
													<td style="width:15%;">Fecha Final</td>
													<td style="width:20%;">Comentarios</td>
												</tr>
											</thead>
											<tbody style="height: 300px">
												<?php
												$queryTabla = "select e.EVENT_SNR, tipo.name as tipo, e.name, 
													format(day(e.start_date), '00') + '-' + format(month(e.start_date), '00') + '-' + cast(year(e.START_DATE) as varchar) as start_date,
													format(day(e.FINISH_DATE), '00') + '-' + format(month(e.finish_date), '00') + '-' + cast(year(e.finish_DATE) as varchar) as finish_date,
													e.INFO
													from EVENT e
													left outer join CODELIST tipo on tipo.CLIST_SNR = TYPE_SNR
													where e.REC_STAT = 0
													and e.EVENT_SNR <> '00000000-0000-0000-0000-000000000000'";
												//echo $queryTabla."<br>";
												$rsTabla = sqlsrv_query($conn, $queryTabla, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
												while($registro = sqlsrv_fetch_array($rsTabla)){
									?>
													<tr class="align-center" onClick="traeAsistentesEventos('<?= $registro['EVENT_SNR'] ?>');">
														<td style="width:25%;">
															<?= utf8_encode($registro['tipo']) ?></td>
														<td style="width:25%;">
															<?= utf8_encode($registro['name']) ?>
														</td>
														<td style="width:15%;">
															<?= $registro['start_date'] ?>
														</td>
														<td style="width:15%;">
															<?= $registro['finish_date'] ?>
														</td>
														<td style="width:20%;">
															<?= utf8_encode($registro['INFO']) ?>
														</td>
													</tr>
<?php
												}
?>
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
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>