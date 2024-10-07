<?php
function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
?>

<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2 class="display-flex">
				<i class="material-icons">cloud_upload</i>
				<span class="p-t-5 m-l-5">DOCUMENTOS ENTREGADOS</span>
			</h2>
		</div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card" style="border-top:2px #F44336 solid;">
					<div class="header">
						<form enctype="multipart/form-data" id="formuploadajax" name="formuploadajax" method="POST">
							<div class="row">

								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-0">
									<div class="form-group margin-0">
										<label>Elige el Archivo a Subir</label>
										<div class="input-group margin-0">
											<label class="input-group-btn">
												<span class="btn bg-blue" id="btnArchivo"
													style="height: 34px; padding-top: 8px; box-shadow: none;">
													Seleccionar archivo<input type="file" name="archivo" id="archivo"
														class="inputfile custom">
												</span>
											</label>
											<input id="file_name" type="text" class="form-control no-style-disabled"
												placeholder="No se eligió archivo" disabled>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-0">
									<div class="form-group margin-0">
										<label>Información del archivo</label>
										<input class="form-control margin-0" type="text" size="40"
											id="txtInformacionArchivo" name="txtInformacionArchivo" />
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-0">
									<?php
									if($tipoUsuario != 4){
									?>
									<label class="m-r-10">Ruta:</label>
									<div class="form-group display-flex margin-0">

										<select class="form-control margin-0" onclick="filtrosUsuarios('docs')">
											<option id="sltMultiSelectDocs">Seleccione</option>
										</select>
										<button id="btnFiltrarDocs" style="width:65px;" type="button"
											class="btn bg-indigo waves-effect btn-indigo m-l-10">
											Filtrar
										</button>
									</div>

									<?php			
									}
									?>
								</div>

								<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 align-right margin-0">
									<button type="button" class="btn bg-indigo waves-effect m-t-17 btn-indigo" id="btnEnviarArchivo" title="Subir" data-type="basic">
										<i class="material-icons">file_upload</i>
										<span>Subir archivo</span>
									</button>
								</div>
							</div>
						</form>
					</div>
					<div class="body">
						<div id="divSubirDocumentosDatosPersonales">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="div-tbl-aprob-pers">
										<table id="tblSubirDocumentosDatosPersonales" class="table-striped">
											<thead class="bg-cyan">
												<tr class="align-center">
													<td style="width:15%;" class="align-left">Fecha
														de carga</td>
													<td style="width:25%;">Nombre del archivo</td>
													<td style="width:25%;">Información de archivo
													</td>
													<td style="width:15%;">Tamaño del archivo</td>
													<td style="width:10%;">Descargar</td>
													<td style="width:10%;">Eliminar</td>
												</tr>
											</thead>
											<tbody>
												<?php
												$queryTabla = "select * from PERSON_PRIV_NOTE where user_snr = '".$idUsuario."' and rec_stat = 0 order by DATE";
												//echo $queryTabla."<br>";
												$rsTabla = sqlsrv_query($conn, $queryTabla, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
												while($registro = sqlsrv_fetch_array($rsTabla)){
													foreach ($registro['DATE'] ? $registro['DATE'] : [] as $key => $val) {
														if(strtolower($key) == 'date'){
															$fecha = substr($val, 0, 10);
														}
													}
													if(file_exists($registro['PATH'])){
														$nombreArchivo = explode("/",$registro['PATH'])[2];
									?>
												<tr class="align-center">
													<td style="width:15%;" class="align-left">
														<?= $fecha ?></td>
													<td style="width:25%;"><?= $nombreArchivo ?>
													</td>
													<td style="width:25%;"><?= $registro['INFO'] ?>
													</td>
													<td style="width:15%;">
														<?= human_filesize(filesize($registro['PATH'])) ?>B
													</td>
													<td style="width:10%;">
														<a
															href="ajax/descargaArchivo.php?file=<?= $registro['PATH'] ?>">
															<i class="fas fa-file-download"></i>
														</a>
													</td>
													<td style="width:10%;">
														<a onClick="eliminarArchivo('<?= $registro['PERSPRNOTE_SNR'] ?>');"
															class="pointer">
															<i class="fas fa-trash-alt"></i>
														</a>
													</td>
												</tr>
												<?php
													}
												}
									?>
											</tbody>
											<tfoot class="bg-cyan">
												<tr>
													<td>
														Total de archivos: <?= sqlsrv_num_rows($rsTabla) ?>
													</td>
												</tr>
											</tfoot>
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