<!--<textarea id="hdnQueryReporte" rows="20" cols="200"></textarea>-->
<section class="content">
	<div class="container-fluid">
		<div class="block-header m-t-20">
			<h2>
				LISTADOS
			</h2>
		</div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card" style="border-top:2px #F44336 solid;">
					<div class="header p-t-15 p-b-15">
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 align-left">
								<button type="button" class="btn btn-default waves-effect btn-indigo2 btn-r-20" id="btnCerrarListado">
									<i class="material-icons">chevron_left</i>
									<span>Regresar</span>
								</button>
							</div>
							<div style="display:none;">
								<textarea rows="2" cols="60" id="txtQuery" class="text-notas2"></textarea>
							</div>
							
							<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 align-right">
								<div class="p-t-4">
									<button id="btnExportarListadoExcelPlano" type="button" class="btn bg-indigo waves-effect btn-indigo">
										Exportar a Excel sin formato
									</button>
									<button id="btnExportarListadoExcel" type="button" class="btn bg-indigo waves-effect btn-indigo">
										Exportar a Excel
									</button>
									<button type="button" class="btn bg-indigo waves-effect btn-indigo" id="btnExportarListadoPDF">
										Exportar a PDF
									</button>
								</div>
							</div> 
						</div>
					</div>
					<div class="body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div id="divListado">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<form id='formListados' target="_blank" method="POST">
	<input type="hidden" id="hdnFechaIListado" name="hdnFechaIListado" />
	<input type="hidden" id="hdnFechaFListado" name="hdnFechaFListado" />
	<input type="hidden" id="hdnIDSListado" name="hdnIDSListado" />
	<input type="hidden" id="hdnEstatusListado" name="hdnEstatusListado" />
	<input type="hidden" id="hdnEstatusInstListado" name="hdnEstatusInstListado" />
	<input type="hidden" id="hdnTipoListado" name="hdnTipoListado" />
	<input type="hidden" id="hdnCicloListado" name="hdnCicloListado" />
	<input type="hidden" id="hdnIdsProductosListado" name="hdnIdsProductosListado" />
	<input type="hidden" id="hdnQueryListado" name="hdnQueryListado" />
</form>