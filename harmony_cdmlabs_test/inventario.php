<input type="hidden" id="hdnPestana" value="inventario" />
<input type="hidden" id="hdnIdProductoAjuste" value="" />

<?php
	echo "<script>
	$('#sltMotivoRechazoMuestra').empty();";
	$rsMotivo = llenaCombo($conn, 374, 638);
	while($motivo = sqlsrv_fetch_array($rsMotivo)){
		echo "$('#sltMotivoRechazoMuestra').append('<option value=\"".$motivo['id']."\">".utf8_encode($motivo['nombre'])."</option>');";
	}
	echo "</script>";
?>

<section class="content">
	<div class="container-fluid">
		<div class="block-header m-t-15">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 m-t-5">
					<h2 class="display-flex">
						<i class="material-icons font-23">shopping_cart</i>
						<span class="p-t-5 m-l-5">INVENTARIO</span>
					</h2>
				</div>

				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 align-right" id="menuFiltrosAprob"
					style="display:none;">
					<div class="display-inline">
						<button id="btnPendienteAprobacion"
							class="btn waves-effect btn-account-head btn-account-l seleccionado btn-account-sel"
							disabled>
							Pendiente
						</button>
						<button id="btnAceptadoInv" class="btn waves-effect btn-account-head btn-account-c">
							Aceptado
						</button>
						<button id="btnRechazadoInv" class="btn waves-effect btn-account-head btn-account-r">
							Rechazado
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<!--LISTA PLANES/VISITAS HOY-->
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card" style="border-top:2px #F44336 solid;">
					<div id="tabsInventario">
						<div class="header">
							<div class="row margin-0">
								<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 p-l-0 add-m-b">
									<ul class="display-inline no-style-list margin-0">
										<li onClick="pestanaInventario('inventario');">
											<a href="#inventario">
												<button id="btnInvSel"
													class="btn waves-effect btn-calendar-head btn-ch-l btn-blue-sel"
													disabled>
													Inventario
												</button>
											</a>
										</li>
										<li onClick="pestanaInventario('aprobacion');">
											<a href="#pendiente">
												<button id="btnPenSel" style="width: 121px;"
													class="btn waves-effect btn-calendar-head btn-ch-r">
													Aprobaciones
												</button>
											</a>
										</li>
									</ul>
								</div>
								<!--<input type="checkbox" id="chkExistencia" /><label for="chkExistencia">Existecia</label>-->
								<!--<label class="container">Existencia
										<input id="chkExistencia" type="checkbox" checked="checked">
										<span class="checkmark"></span>
									</label>-->

								<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 p-t-10 p-l-0">
									<input type="checkbox" id="chkExistencia" checked="checked"
										class="filled-in chk-col-blue" />
									<label for="chkExistencia" id="lblExistencia">Existencia</label>
								</div>
								<div class="col-lg-7 col-md-5 col-sm-5 col-xs-6 align-right p-r-0">
									<?php
						if($tipoUsuario != 4){
?>
									<button type="button" class="btn bg-indigo waves-effect btn-indigo"
										id="imgFiltrarAbrirInventario">
										<span class="display-flex" style="margin: 3px 0; top: 0;">
											<i class="fas fa-filter font-15"></i>
											<span style="top: 0; margin-left: 5px;">Filtrar</span>
										</span>
									</button>
									<button type="button" class="btn bg-white col-indigo waves-effect btn-indigo2"
										id="imgFiltrarCerrarInventario" style="display:none;">
										<span class="display-flex" style="margin: 3px 0; top: 0;">
											<i class="fas fa-filter font-15"></i>
											<span style="top: 0; margin-left: 5px;">Cerrar</span>
										</span>
									</button>

									<?php				
								}
?>
								</div>
							</div>
						</div>
						<div class="body">
							<div id="tblFiltrosInventario" style="display:none;">
								<div class="row">
									<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 margin-0 add-m-b">
										<?php
									if($tipoUsuario != 4){
										echo "<div class=\"form-group margin-0\">
												<label>Representante: </label>
												<select onclick=\"filtrosUsuarios('inv');\" class=\"form-control\">
													<option id=\"sltMultiSelectInv\">Seleccione</option>
												</select>
											</div>";
									}
										
								?>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 margin-0">
										<div class="form-group margin-0">
											<label>Producto: </label>
											<select id="sltProductosInv" class="form-control margin-0 add-m-b">
												<option value="00000000-0000-0000-0000-000000000000" selected></option>
												<?php
										$rsProductosInv = sqlsrv_query($conn, "select PROD_SNR,NAME from PRODUCT where REC_STAT = 0 and prod_snr in (select prod_snr from prodform where prodform_snr in (select prodform_snr from prodformbatch where rec_stat=0 and status=1)) order by name");
										while($producto = sqlsrv_fetch_array($rsProductosInv)){
											echo '<option value="'.$producto['PROD_SNR'].'">'.$producto['NAME'].'</option>';
										}
										?>
											</select>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-6 align-right margin-0">
										<div class="m-t-24">
											<button id="btnLimpiarFiltroInv" type="button"
												class="btn bg-indigo waves-effect btn-indigo">
												Limpiar
											</button>
											<button id="btnEjecutarFiltroInv" type="button"
												class="btn bg-indigo waves-effect btn-indigo m-l-5">
												Filtrar
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="inventario">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="div-tbl-aprob-pers">
											<table id="tblInventarioPrincipal" class="table-striped table-hover">
												<thead class="bg-cyan">
													<tr class="align-center">
														<td style="width:18%;">Producto</td>
														<td style="width:28%;">Presentación</td>
														<td style="width:18%;">Lote</td>
														<td style="width:12%;">Entrada</td>
														<td style="width:12%;">Salida</td>
														<td style="width:12%;">Existencia</td>
													</tr>
												</thead>
												<tbody class="align-center pointer">
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<p class="font-bold">
											Entradas
										</p>
										<div class="div-tbl-aprob-pers">
											<table class="table-striped tblEntradaSalidaInv" id="tblEntradaInv">
												<thead class="bg-cyan">
													<tr class="align-center">
														<td style="width:50%;">Fecha</td>
														<td style="width:50%;">Cantidad</td>
													</tr>
												</thead>
												<tbody class="align-center">
												</tbody>
											</table>
										</div>
									</div>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
										<p class="font-bold">
											Salidas
										</p>
										<div class="div-tbl-aprob-pers">
											<table id="tblSalidaInv" class="table-striped tblEntradaSalidaInv">
												<thead class="bg-cyan">
													<tr class="align-center">
														<td style="width:15%;">Fecha</td>
														<td style="width:40%;">Nombre</td>
														<td style="width:30%;">Producto</td>
														<td style="width:15%;">Cantidad</td>
													</tr>
												</thead>
												<tbody class="align-center">
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div id="pendiente">
								<div class="row">

									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="div-tbl-aprob-pers">
											<table id="tblInventario" class="table-striped">
												<thead class="bg-cyan">
												</thead>
												<tbody>
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
	</div>
</section>