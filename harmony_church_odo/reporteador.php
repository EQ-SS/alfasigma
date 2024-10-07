<!--<textarea id="hdnQueryReporte" rows="20" cols="200"></textarea>-->
<section class="content">
	<div class="container-fluid">
		<div class="block-header m-t-20">
			<h2>
				REPORTES
			</h2>
		</div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card" style="border-top:2px #F44336 solid;">
					<div class="header p-t-15 p-b-15">
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 align-left">
								<button type="button" class="btn btn-default waves-effect btn-indigo2 btn-r-20" id="btnCerrarReporte">
									<i class="material-icons">chevron_left</i>
									<span>Regresar</span>
								</button>
							</div>
							<!--<div>
								<textarea rows="2" cols="60" id="txtQuery"  class="text-notas2"></textarea>
							</div>-->
							<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 align-right">
								<div class="p-t-4">
									<button id="btnExportarReporteExcelPlano" type="button" class="btn bg-indigo waves-effect btn-indigo">
										Exportar a Excel sin formato
									</button>
									<button id="btnExportarReporteExcel" type="button" class="btn bg-indigo waves-effect btn-indigo">
										Exportar a Excel
									</button>
									<button type="button" class="btn bg-indigo waves-effect btn-indigo" id="btnExportarReportePDF">
										Exportar a PDF
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div id="divReporteReporteador">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<form id='formReportes' target="_blank" method="POST">
	<input type="hidden" id="hdnFechaI" name="hdnFechaI" />
	<input type="hidden" id="hdnFechaF" name="hdnFechaF" />
	<input type="hidden" id="hdnIDS" name="hdnIDS" />
	<input type="hidden" id="hdnEstatus" name="hdnEstatus" />
	<input type="hidden" id="hdnEstatusInst" name="hdnEstatusInst" />
	<input type="hidden" id="hdnTipoReporte" name="hdnTipoReporte" />
	<input type="hidden" id="hdnCicloReporte" name="hdnCicloReporte" />
	<input type="hidden" id="hdnIdsProductos" name="hdnIdsProductos" />
	<input type="hidden" id="hdnQuery" name="hdnQuery" />
</form>