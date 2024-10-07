<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div id="btnCancelarBuscarPersonaEvento" class="div-btn-close pointer" data-toggle="tooltip" data-placement="bottom"
			title="Cerrar">
			<i class="material-icons"> close</i>
		</div>
		<div class="card m-b--15 card-add-new">
			<div class="header row" style="padding:0 15px;">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 m-t-15">
					<div class="form-group margin-0">
						<div class="input-group">
							<input id="txtBuscarPersonaEventos" type="text" size="100" class="form-control"
								placeholder="Buscar médico por nombre...">
							<span class="input-group-addon padding-0">
								<button id="btnBuscarMedEvento" class="btn waves-effect btn-indigo2" style="padding: 3px 12px;">
									<i class="glyphicon glyphicon-search"></i>
								</button>
							</span>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12  m-t-15 m-b-15">
					<div class="form-group display-inline margin-0">
						<button id="btnAgregarInvitadosEventoEditar" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
							Agregar
						</button>
					</div>
				</div>
				<!--<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12  m-t-15 m-b-15">
					<div class="form-group display-inline margin-0">
						<label class="p-t-5 m-r-10">Ruta: </label>
						<select id="sltRutaBuscaPersonas" class="form-control">
							<option value="00000000-0000-0000-0000-000000000000"></option>
							<?php
								/*$rsUsers = sqlsrv_query($conn, "select * from users where user_snr in ('".$ids."') order by lname");
								while($regUser = sqlsrv_fetch_array($rsUsers)){
									echo '<option value="'.$regUser['USER_SNR'].'">'.$regUser['LNAME'].' '.$regUser['MOTHERS_LNAME'].' '.$regUser['FNAME'].'</option>';
								}*/
?>
						</select>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t--10 m-b-10">
					<small>Seleccione un médico</small>
				</div>-->
			</div>
			<div class="body">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="new-div">
						<div class="div-tbl-aprob-pers">
							<input type="hidden" id="hdnIvitadosEventos" />
							<table class="table-striped table-hover cargaBusquedaMed" id="tblBuscarPersonasEventos">
								<thead class="bg-cyan">
									<tr>
<?php
									if($tipoUsuario != 4){
?>
										<td width="10%">Ruta</td>
										<td width="30%">Nombre</td>
										<td width="30%">Especialidad</td>
										<td width="30%">Institución</td>
<?php
									}else{
?>
										<td width="34%">Nombre</td>
										<td width="34%">Especialidad</td>
										<td width="32%">Institución</td>
<?php 
									} 
?>
									</tr>
								</thead>
								<tbody class="pointer">
								</tbody>
							</table>
						</div>
						<div id="totalMedicosBuscadosEventos" class="font-bold"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>