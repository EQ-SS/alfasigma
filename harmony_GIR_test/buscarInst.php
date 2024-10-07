<input type="hidden" id="hdnDiaCalendario" />

<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="div-btn-close pointer" onClick="cerrarInformacion();" data-toggle="tooltip" data-placement="bottom"
			title="Cerrar">
			<i class="material-icons"> close</i>
		</div>
		<div class="card m-b--15 card-add-new">
			<div class="header row" style="padding:0 15px;">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 m-t-15">
					<div class="form-group" id='buscarInst'>
						<div class="input-group">
							<input id="txtBuscarInst" type="text" size="100" class="form-control"
								placeholder="Buscar por nombre o dirección...">
							<span class="input-group-addon padding-0">
								<button id="btnBuscarInst" class="btn waves-effect btn-indigo2" style="padding: 3px 12px;">
									<i class="glyphicon glyphicon-search"></i>
								</button>
							</span>
						</div>
					</div>
					<div class="form-group" id='buscarFarmacia' style="display:none;">
						<div class="input-group">
							<input id="txtBuscarFar2" type="text" size="100" class="form-control"
								placeholder="Buscar por nombre o dirección...">
							<span class="input-group-addon padding-0">
								<button id="btnBuscarFar2" class="btn waves-effect btn-indigo2" style="padding: 3px 12px;">
									<i class="glyphicon glyphicon-search"></i>
								</button>
							</span>
						</div>
					</div>
					<div class="form-group" id='buscarHospital' style="display:none;">
						<div class="input-group">
							<input id="txtBuscarHos" type="text" size="100" class="form-control"
								placeholder="Buscar por nombre o dirección...">
							<span class="input-group-addon padding-0">
								<button id="btnBuscarHos" class="btn waves-effect btn-indigo2" style="padding: 3px 12px;">
									<i class="glyphicon glyphicon-search"></i>
								</button>
							</span>
						</div>
					</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12  m-t-15 m-b-15">
					<div class="form-group display-inline margin-0">
						<label class="p-t-5 m-r-10">Ruta: </label>
						<select id="sltRutaBuscaInst" class="form-control">
							<option value="00000000-0000-0000-0000-000000000000"></option>
							<?php
								$rsUsers = sqlsrv_query($conn, "select * from users where user_snr in ('".$ids."') order by lname");
								while($regUser = sqlsrv_fetch_array($rsUsers)){
									echo '<option value="'.$regUser['USER_SNR'].'">'.$regUser['USER_NR'].' - '.utf8_encode($regUser['LNAME'].' '.$regUser['MOTHERS_LNAME'].' '.$regUser['FNAME']).'</option>';
								}
?>
						</select>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t--10 m-b-10">
					<small>Seleccione una institución</small>
				</div>
			</div>
			<div class="body">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="new-div">
						<div class="div-tbl-aprob-pers">
							<table class="table-striped table-hover cargaBusquedaInst buscaInst" id="tblBuscarInst">
								<thead class="bg-cyan">
									<tr>
<?php
									if($tipoUsuario != 4){
?>
										<td style="width:8%;">Ruta</td>
										<td style="width:11%;">Tipo</td>
										<td style="width:15%;">Nombre</td>
										<td style="width:15%;">Dirección</td>
										<td style="width:15%;">Colonia</td>
										<td style="width:8%;">CP</td>
										<td style="width:15%;">Población</td>
										<td style="width:13%;">Estado</td>
<?php
									}else{
?>
										<td style="width:12%;">Tipo</td>
										<td style="width:16%;">Nombre</td>
										<td style="width:16%;">Dirección</td>
										<td style="width:16%;">Colonia</td>
										<td style="width:9%;">CP</td>
										<td style="width:16%;">Población</td>
										<td style="width:15%;">Estado</td>
<?php
									}
?>
									</tr>
								</thead>
								<tbody class="pointer">
								</tbody>
							</table>
						</div>
						<div id="totalInstBuscados" class="font-bold"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>