<?php
$qUsusario = "select user_nr + ' - ' + lname + ' ' + mothers_lname + ' ' + fname as remitente from users where user_snr = '".$_GET['idUser']."'";
$remitente = sqlsrv_fetch_array(sqlsrv_query($conn, $qUsusario))['remitente'];
$nameDividido = explode('-',$remitente);
$nameSinNR = count($nameDividido) >2 ? $nameDividido[0].' - '.$nameDividido[2] : $nameDividido[0].' - '.$nameDividido[1];
$remitente = $nameSinNR;
?>
<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Mensaje Nuevo
					</h2>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">
					<button id="btnEnviarMensaje" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
						Enviar Mensaje
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCancelarMensaje" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="new-div add-scroll-y">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div id="tblDatos">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group margin-0">
												<label>Representante</label>
												<p class="form-control"> <?= $remitente ?></p>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="form-group margin-0">
												<label>Fecha</label>
												<p class="form-control"> <?= date("Y-m-d H:i:s") ?></p>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group  margin-0">
												<label class="col-red">Para *</label>
												<div class="selectBox">
													<select class="form-control">
														<option id="sltMultiSelectMensajes" >Seleccione</option>
													</select>
												</div>
												<!--<select id="lstParaMensaje" class="form-control">
													<option value="" hidden>Seleccioneeeee</option>
												</select>-->
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group  margin-0">
												<label class="col-red">Asunto *</label>
												<input type="text" id="txtAsuntoMensaje" class="form-control"/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-0">
											<div class="form-group margin-0">
												<label class="col-red">Mensaje *</label>
												<textarea id="txtMensaje" rows="9" class="text-notas2"></textarea>
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
	</div>
</div>