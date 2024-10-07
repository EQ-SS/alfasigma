<input type="hidden" value="" id="hdnFechaRuteo" />

<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="div-btn-close pointer" onClick="cerrarInformacion();" data-toggle="tooltip" data-placement="bottom" title="Cerrar">
			<i class="material-icons"> close</i>
		</div>
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-15 m-b-15">
					<div class="display-inline" id="tblOpcionesCalendario">
						<button id="btnPlanRuteo" class="seleccionado btn waves-effect btn-aprob-head btn-aprob-l btn-aprob-sel" disabled>
							Planes
						</button>
						<button id="btnVisitaRuteo" class="noSeleccionado btn waves-effect btn-aprob-head btn-aprob-r">
							Visitas
						</button>
					</div>
				</div>
			</div>
			<div class="body">
				<div class="new-div add-scroll-y">
					<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
						<div id="divPlanesVisitasRuteo" style="height:500px;overflow:auto;">
							<table id="tblPlanesVisitasRuteo" class="table table-striped table-hover pointer">
								<?php
					//$arrPlanesVisitasRuteo = planesVisitasCalendario(date("Y-m-d"), 'plan', $conn, $idUsuario);
					//echo $arrPlanesVisitasRuteo[0];
?>
							</table>
						</div>
					</div>
					<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
						<div id="map_canvas3" style="width:100%;height:500px;"></div><br>
						<table id="tblSimbologiaMarcadoresRuteo" style="display:none;" border="0">
							<tr>
								<td colspan="3">
									<b>Ubicación GPS del contacto.</b>
								</td>
								<td width="50px">
									
								</td>
								<td colspan="3">
									<b>Geolocalización de la visita.</b>
								</td>
							</tr>
							<tr>
								<td>
									<img src="markers/pushpin/blue.png" width="20%">Médicos
								</td>
								<td>
									<img src="markers/pushpin/ltblue.png" width="20%">Hospitales
								</td>
								<td>
									<img src="markers/pushpin/green.png" width="20%">Farmacias
								</td>
								<td>
									
								</td>
								<td>
									<img src="markers/normal/blue.png" width="20%">Médicos
								</td>
								<td>
									<img src="markers/normal/ltblue.png" width="20%">Hospitales
								</td>
								<td>
									<img src="markers/normal/green.png" width="20%">Farmacias
								</td>
							</tr>
						</table>
						<!--<div id='map_canvas2' style="width:800px; height:350px;"></div>-->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>