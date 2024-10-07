<input type="hidden" id="hdnIdPersona" value="" />
<input type="hidden" id="hdnRutaDatosPersonales" value="" />
<input type="hidden" id="hdnEspecialidadPersona" value="" />

<div id="tabs-1">
	<div id="tabsPerfilPersona">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-0 m-t--40" style="margin-bottom:0;">
				<ul class="nav nav-tabs m-l--5 m-r--5 tab-col-blue p-l-5 tabDatosPer" role="tablist"
					style="background-color:#efefef;">
					<li id="perfilMedico" role="presentation" class="active">
						<a href="#tabPerfilP" id="aPerfilPersona" data-toggle="tab" class="show-tooltip">
							<i class="material-icons">person</i>
							<div class="divTooltip">Información</div>
							<span class="hidden-txt-tabs">Información</span>
						</a>
					</li>
					<!--<li role="presentation">
						<a id="aPerfilPersona2" href="#tabHorarioP" data-toggle="tab" class="show-tooltip">
							<i class="material-icons">access_time</i>
							<div class="divTooltip">Horario</div>
							<span class="hidden-txt-tabs">Horario de trabajo</span>
						</a>
					</li>
					<li role="presentation">
						<a id="aPerfilPersona3" href="#tabAnalisisP" data-toggle="tab" class="show-tooltip">
							<i class="material-icons">insert_chart</i>
							<div class="divTooltip">W Analysis</div>
							<span class="hidden-txt-tabs">What Analysis</span>
						</a>
					</li>
					<li role="presentation">
						<a id="aPerfilPersona4" href="#tabSegP" data-toggle="tab" class="show-tooltip">
							<i class="material-icons">assignment</i>
							<div class="divTooltip">Segmentación</div>
							<span class="hidden-txt-tabs">Segmentación</span>
						</a>
					</li>-->
					<li role="presentation">
						<a id="aPerfilPersona5" href="#tabBancos" data-toggle="tab" class="show-tooltip">
							<i class="material-icons">account_balance</i>
							<div class="divTooltip">Bancos/aseguradoras</div>
							<span class="hidden-txt-tabs">Cuadro Básico</span>
						</a>
					</li>
					<li role="presentation">
						<a id="aPerfilPersona6" href="#tabNotasP" data-toggle="tab" class="show-tooltip">
							<i class="material-icons">note</i>
							<div class="divTooltip">Notas</div>
							<span class="hidden-txt-tabs">Notas</span>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div style="overflow-y: auto;overflow-x: hidden;" class="m-r--20 p-t-20 p-r-20 cardDatosPer">
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div id="tabPerfilP">
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-5 col-xs-12 align-center">
								<img src="imagenes/nopic.jpg" class="user-image-doctor" />
							</div>
							<div class="col-lg-6 col-md-6 col-sm-7 col-xs-12">
								<div class="row">
									<div class="col-md-12"><span id="lblEspecialidad1"
											class="label bg-cyan label-esp"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Dirección: </p><label
											id="lblDireccion"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Brick: </p><label
											id="lblBrick"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Consultorio: </p><label
											id="lblConsultorio"></label>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-md-12" style="height:14px;">
									</div>
								</div>
								<div class="row m-t-5">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Categoría: </p><label
											id="lblCategoria"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Cédula: </p><label
											id="lblCedula"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Estatus: </p><label
											id="lblEstatusDatosPersonales"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Folio Audit: </p><label
											id="lblCategoriaAudit"></label>
									</div>
								</div>
							</div>
						</div>

						<hr class="style-hr m-t-0" />

						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 margin-0">
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Sub especialidad: </p><label
											id="lblEspecialidadAudiencia"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Pacientes por semana: </p><label
											id="lblPacientesDia"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Costo Consulta: </p><label
											id="lblHonorarios"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Fecha Nacimiento: </p><label
											id="lblFechaNacimiento"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Telefono celular: </p><label
											id="lblCelular"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Telefono 2: </p><label
											id="lblTelefono2"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Email 2: </p><label
											id="lblEmail2"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Tipo de Iguala: </p><label
											id="lblTipoIguala"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">D1VXT: </p><label
											id="lblD1vxt"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Botiquín: </p><label
											id="lblBotiquin"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Vitamedica: </p><label
											id="lblVitamedica"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Descenso de consul.: </p><label
											id="lblDescendoConsul"></label>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Frecuencia de visita: </p><label
											id="lblFrecuenciaVisita"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Dificultad de visita: </p><label
											id="lblDificultadVisita"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Estatus: </p><label
											id="lblEstatus"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Sexo: </p><label
											id="lblSexo"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Telefono 1: </p><label
											id="lblTelefono1"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Email 1: </p><label
											id="lblEmail1"></label>
									</div>
								</div>
								<!--<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Bancos/Aseg: </p><label
											id="lblBancosAseg"></label>
									</div>
								</div>-->
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Iguala: </p><label
											id="lblIguala"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Contacto virtual: </p><label
											id="lblContactoVirtual"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Producto D1VXT: </p><label
											id="lblProductoD1vxt"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Potencial: </p><label
											id="lblPotencial"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Conexión CRM: </p><label
											id="lblConexionCRM"></label>
									</div>
								</div>
							</div>
						</div>

						<hr class="style-hr" />
						
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 margin-0">
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Padecimientos Médicos: </p>
										<label id="lblPadecimientosMedicos"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Líder de Opinión: </p>
										<label id="lblLiderOpinion"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Estado civil: </p>
										<label id="lblEstadoCivil"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Tipo de Consulta: </p><label
											id="lblTipoConsulta"></label>
									</div>
								</div>
							</div>
							
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Preferencia de Contacto: </p><label
											id="lblPreferenciaContacto"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">¿Porqué?: </p><label
											id="lblPorque"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Compra Directa: </p><label
											id="lblCompraDirecta"></label>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Speaker: </p><label
											id="lblSpeaker"></label>
									</div>
								</div>
							</div>
						</div>

						<!--<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 margin-0">
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Torre: </p><label
											id="lblTorre"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Departamento: </p><label
											id="lblDepto"></label>
									</div>
								</div>
							</div>
							
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Piso: </p><label
											id="lblPiso"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Consultorio: </p><label
											id="lblConsultorio"></label>
									</div>
								</div>
							</div>
						</div>-->
					</div>

					<!--Horario-->
					<!--<div class="row" id="tabHorarioP">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-0">
							<div class="card margin-0">
								<div class="header bg-blue">
									<h2>
										Horario
									</h2>
								</div>
								<div class="body">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="table-responsive align-center">
												<table id="tblHorario" class="table margin-0">
													<tr>
														<td>&nbsp;</td>
														<td class="font-bold align-center">AM</td>
														<td class="font-bold align-center">PM</td>
														<td class="font-bold align-center">Previa cita</td>
														<td class="font-bold align-center">Horario Fijo</td>
														<td class="font-bold align-center">Comentarios</td>
													</tr>
													<tr>
														<td class="font-bold">Lunes</td>
														<td class="align-center">
															<input type="checkbox" id="chkLunesam"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkLunesam"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkLunespm"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkLunespm"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkLunesCita"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkLunesCita"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkLunesFijo2"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkLunesFijo2"></label>
														</td>
														<td class="align-center">
															<label id="lblComentariosLunes" value="" /></label>
														</td>
													</tr>
													<tr>
														<td class="font-bold">Martes</td>
														<td class="align-center">
															<input type="checkbox" id="chkMartesam"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkMartesam"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkMartespm"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkMartespm"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkMartesCita"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkMartesCita"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkMartesFijo2"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkMartesFijo2"></label>
														</td>
														<td class="align-center">
															<label id="lblComentariosMartes" value="" /></label>
														</td>
													</tr>
													<tr>
														<td class="font-bold">Miercoles</td>
														<td class="align-center">
															<input type="checkbox" id="chkMiercolesam"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkMiercolesam"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkMiercolespm"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkMiercolespm"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkMiercolesCita"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkMiercolesCita"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkMiercolesFijo2"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkMiercolesFijo2"></label>
														</td>
														<td class="align-center">
															<label id="lblComentariosMiercoles" value="" /></label>
														</td>
													</tr>
													<tr>
														<td class="font-bold">Jueves</td>
														<td class="align-center">
															<input type="checkbox" id="chkJuevesam"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkJuevesam"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkJuevespm"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkJuevespm"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkJuevesCita"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkJuevesCita"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkJuevesFijo2"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkJuevesFijo2"></label>
														</td>
														<td class="align-center">
															<label id="lblComentariosJueves" value="" /></label>
														</td>
													</tr>
													<tr>
														<td class="font-bold">Viernes</td>
														<td class="align-center">
															<input type="checkbox" id="chkViernesam"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkViernesam"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkViernespm"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkViernespm"></label>

														</td>
														<td class="align-center">
															<input type="checkbox" id="chkViernesCita"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkViernesCita"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkViernesFijo2"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkViernesFijo2"></label>
														</td>
														<td class="align-center">
															<label id="lblComentariosJueves" value="" /></label>
														</td>
													</tr>
													<tr>
														<td class="font-bold">Sábado</td>
														<td class="align-center">
															<input type="checkbox" id="chkSabadoam"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkSabadoam"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkSabadopm"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkSabadopm"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkSabadoCita"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkSabadoCita"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkSabadoFijo2"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkSabadoFijo2"></label>
														</td>
														<td class="align-center">
															<label id="lblComentariosSabado" value="" /></label>
														</td>
													</tr>
													<tr>
														<td class="font-bold">Domingo</td>
														<td class="align-center">
															<input type="checkbox" id="chkDomingoam"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkDomingoam"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkDomingopm"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkDomingopm"></label>

														</td>
														<td class="align-center">
															<input type="checkbox" id="chkDomingoCita"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkDomingoCita"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkDomingoFijo2"
																class="filled-in chk-col-red p-l-15" disabled />
															<label for="chkDomingoFijo2"></label>
														</td>
														<td class="align-center">
															<label id="lblComentariosDomingo" value="" /></label>
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					<!--#END Horario-->

					<!--What analysis-->
					<!--<div class="row" id="tabAnalisisP">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-0">
							<div class="card margin-0">
								<div class="header bg-blue">
									<h2>
										What Analysis
									</h2>
								</div>
								<div class="body table-responsive">
									<table class="table">
										<thead>
											<tr class="font-bold col-indigo align-center">
												<td>
												</td>
												<td>
													Producto
												</td>
												<td>
													W (Que producto)
												</td>
												<td>
													H (Cuanto)
												</td>
												<td>
													A (Porque)
												</td>
												<td>
													T (Que voy hacer)
												</td>
											</tr>
										</thead>
										<tbody class="align-center">
											<tr>
												<td>
													<span>1. </span>
												</td>
												<td>
													<label id="lblProducto1"></label>
												</td>
												<td><label id="lblProducto1W"></label></td>
												<td><label id="lblProducto1H"></label></td>
												<td><label id="lblProducto1A"></label></td>
												<td><label id="lblProducto1T"></label></td>
											</tr>
											<tr>
												<td>
													<span>2. </span>
												</td>
												<td>
													<label id="lblProducto2"></label>
												</td>
												<td><label id="lblProducto2W"></label></td>
												<td><label id="lblProducto2H"></label></td>
												<td><label id="lblProducto2A"></label></td>
												<td><label id="lblProducto2T"></label></td>
											</tr>
											<tr>
												<td>
													<span>3. </span>
												</td>
												<td>
													<label id="lblProducto3"></label>
												</td>
												<td><label id="lblProducto3W"></label></td>
												<td><label id="lblProducto3H"></label></td>
												<td><label id="lblProducto3A"></label></td>
												<td><label id="lblProducto3T"></label></td>
											</tr>
											<tr>
												<td>
													<span>4. </span>
												</td>
												<td>
													<label id="lblProducto4"></label>
												</td>
												<td><label id="lblProducto4W"></label></td>
												<td><label id="lblProducto4H"></label></td>
												<td><label id="lblProducto4A"></label></td>
												<td><label id="lblProducto4T"></label></td>
											</tr>
											<tr>
												<td>
													<span>5. </span>
												</td>
												<td>
													<label id="lblProducto5"></label>
												</td>
												<td><label id="lblProducto5W"></label></td>
												<td><label id="lblProducto5H"></label></td>
												<td><label id="lblProducto5A"></label></td>
												<td><label id="lblProducto5T"></label></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>-->
					<!--#END what analysis-->

					<!--SEGMENTACION-->
					<!--<div class="row" id="tabSegP">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-0">
							<div class="card margin-0">
								<div class="header bg-blue">
									<h2>
										Segmentación
									</h2>
								</div>
								<div class="body">
									<div class="row">
										<div
											class="col-lg-6 col-md-6 col-sm-6 col-xs-6 font-bold col-indigo align-center">
											Preguntas
										</div>
										<div
											class="col-lg-6 col-md-6 col-sm-6 col-xs-6 font-bold col-indigo align-center">
											Respuestas
										</div>
									</div>
									<div class="row">
										<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 font-bold">
											GANAR (¿Porque no le gusta mi producto?)
										</div>
										<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 answer-seg">
											<label id="lblRespuesta1"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 font-bold">
											DESARROLLAR (¿Que le hace falta para incrementar su Rx?)
										</div>
										<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 answer-seg">
											<label id="lblRespuesta2"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 font-bold">
											DEFENDER (¿Que es lo que más le gusta del producto?)
										</div>
										<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 answer-seg">
											<label id="lblRespuesta3"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 font-bold">
											EVALUAR (¿Porque no le gusta mi producto?)
										</div>
										<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 answer-seg">
											<label id="lblRespuesta4"></label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					<!--#END SEGMENTACION-->

					<!--BANCOS-->
					<div class="row" id="tabBancos">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<?php
						$rsPasatiempos = llenaCombo($conn, 19, 14);
						$contador = 1;
						while($pasatiempo = sqlsrv_fetch_array($rsPasatiempos)){
							if($contador == 1){
								echo "<div class='row'>";
							}
							echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin-bottom:40px;">';

							echo '<input type="checkbox" class="filled-in chk-col-red" id="chkBancosPersonaDatosPersonales'.$contador.'" value="'.$pasatiempo['id'].'" disabled />';
							echo '<label for="chkBancosPersonaDatosPersonales'.$contador.'" value="'.$pasatiempo['id'].'">'.$pasatiempo['nombre'].'</label>';
							/*if($contador ==2 || $contador ==5 || $contador ==8 || $contador ==9){
								echo '<label for="chkBancosPersonaDatosPersonales'.$contador.'" value="'.$pasatiempo['id'].'">'.$pasatiempo['nombre'].'</label>';
							}
							else{
								echo "<label for='chkBancosPersonaDatosPersonales".$contador."' value='".$pasatiempo['id']."'>
								<div style='width:80px; height:50px;'><img style='width:100%; height:100%; margin-top:-14px;' src='images/logos_bancos_seguros/chkBancosPersonaDatosPersonales".$contador.".png'></div></label>";
							}*/
								
							echo '</div>';
							if($contador%4==0){
								echo "</div><div class='row'>";
							}
							$contador++;
						}
						echo "</div>";
		?>
						</div>
						<input type="hidden" id="hdnTotalPasatiemposPersona" value="<?= $contador ?>" />
					</div>

					<!--#END BANCOS-->

					<!--NOTAS-->
					<div class="row" id="tabNotasP">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-0">
							<div class="card margin-0">
								<div class="header bg-blue">
									<h2>
										Notas
									</h2>
								</div>
								<div class="body">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<p class="font-bold">Objetivos a corto plazo:</p>
											<textarea rows="8" id="txtCortoPerson" disabled
												class="text-notas"></textarea>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<p class="font-bold">Objetivos a largo plazo:</p>
											<textarea rows="8" id="txtLargoPerson" disabled
												class="text-notas"></textarea>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<p class="font-bold">Comentarios Generales:</p>
											<textarea rows="8" id="txtGeneralesPerson" disabled
												class="text-notas"></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--#END NOTAS-->
				</div>
			</div>
		</div>
	</div>
</div>
<!--#END TABS-1 PERFIL-->

<div id="tabs-2">
	<div class="div-tbl-grey" style="max-height: 645px;">
		<table class="tblPlanesVisitas" id="tblRepresentantes">
			<thead class="bg-grey">
				<tr class="align-center">
					<td style="width:40%;">
						Representante
					</td>
					<td style="width:20%;">
						Última visita
					</td>
					<td style="width:20%;">
						Siguiente visita
					</td>
					<td style="width:20%;">
						No interesante
					</td>
				</tr>
			</thead>
			<tbody class="align-center">
				<tr>
					<td style="width:40%;text-transform:capitalize;">
						<p id="lblRepresentante"></p>
					</td>
					<td style="width:20%;">
						<p id="lblUltimaVisita"></p>
					</td>
					<td style="width:20%;">
						&nbsp;
					</td>
					<td style="width:20%;">
						0
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div id="tabs-3">
	<!--HISTORICO DE PLANES-->
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-9 col-xs-8">
			<label class="negrita" style="font-size: 12px">Histórico de planes</label>
			<div class="select">
				<select id="lstYearPlanes" class="form-control">
					<option value='0' hidden>Seleccione</option>
					<?php
						for($i=date("Y");$i>=date("Y") - 5;$i--){
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
					?>
				</select>
				<div class="select_arrow"></div>
			</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-3 col-xs-4">
			<button id="imgAgregarPlan" type="button" class="pull-right m-t-20 btn bg-indigo waves-effect btn-indigo"
				style="padding: 8px 12px;">
				Agregar
			</button>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="img-plan align-center">
				<div class="borde">1</div>
				<div class="td-cld-plan"><label id="ciclo1">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">2</div>
				<div class="td-cld-plan"><label id="ciclo2">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">3</div>
				<div class="td-cld-plan"><label id="ciclo3">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">4</div>
				<div class="td-cld-plan"><label id="ciclo4">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">5</div>
				<div class="td-cld-plan"><label id="ciclo5">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">6</div>
				<div class="td-cld-plan"><label id="ciclo6">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">7</div>
				<div class="td-cld-plan"><label id="ciclo7">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">8</div>
				<div class="td-cld-plan"><label id="ciclo8">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">9</div>
				<div class="td-cld-plan"><label id="ciclo9">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">10</div>
				<div class="td-cld-plan"><label id="ciclo10">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">11</div>
				<div class="td-cld-plan"><label id="ciclo11">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">12</div>
				<div class="td-cld-plan"><label id="ciclo12">0</label></div>
			</div>
			<div class="img-plan align-center" style="margin-right:15px;">
				<div class="borde">13</div>
				<div class="td-cld-plan"><label id="ciclo13">0</label></div>
			</div>
			<div class="align-center m-r-15" style="display: inline-block;">
				<div class="font-bold">Total</div>
				<div class="m-t-15"><label id="acumulado">0</label></div>
			</div>
			<div class="align-center" style="display: inline-block;">
				<div class="font-bold">Frecuencia</div>
				<div class="m-t-15"><label id="lblFrecPlan">0</label></div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="div-tbl-grey">
				<table id="tblPlan" class="table-hover tblPlanesVisitas">
					<thead class="bg-grey">
						<tr class="align-center">
							<td style="width:15%;">Ciclo</td>
							<td style="width:10%;">Ruta</td>
							<td style="width:15%;">Fecha</td>
							<td style="width:15%;">Hora</td>
							<td style="width:45%;">Obj. de la visita</td>
						</tr>
					</thead>
					<tbody class="pointer align-center">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="tabs-4">
	<!--VISITAS-->
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-9 col-xs-8">
			<label class="negrita" style="font-size: 12px">Histórico de visitas</label>
			<select id="lstYearVisitas" class="form-control">
				<option value='0' hidden>Seleccione</option>
				<?php
					for($i=date("Y");$i>=date("Y") - 12;$i--){
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
				?>
			</select>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-3 col-xs-4">
			<button id="imgAgregarVisita" type="button" class="pull-right m-t-20 btn bg-indigo waves-effect btn-indigo"
				style="padding: 8px 12px;">
				Agregar
			</button>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="img-plan align-center">
				<div class="borde">1</div>
				<div class="td-cld-plan"><label id="cicloVisita1">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">2</div>
				<div class="td-cld-plan"><label id="cicloVisita2">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">3</div>
				<div class="td-cld-plan"><label id="cicloVisita3">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">4</div>
				<div class="td-cld-plan"><label id="cicloVisita4">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">5</div>
				<div class="td-cld-plan"><label id="cicloVisita5">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">6</div>
				<div class="td-cld-plan"><label id="cicloVisita6">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">7</div>
				<div class="td-cld-plan"><label id="cicloVisita7">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">8</div>
				<div class="td-cld-plan"><label id="cicloVisita8">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">9</div>
				<div class="td-cld-plan"><label id="cicloVisita9">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">10</div>
				<div class="td-cld-plan"><label id="cicloVisita10">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">11</div>
				<div class="td-cld-plan"><label id="cicloVisita11">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">12</div>
				<div class="td-cld-plan"><label id="cicloVisita12">0</label></div>
			</div>
			<div class="img-plan align-center" style="margin-right:15px;">
				<div class="borde">13</div>
				<div class="td-cld-plan"><label id="cicloVisita13">0</label></div>
			</div>
			<div class="align-center m-r-15" style="display: inline-block;">
				<div class="font-bold">Total</div>
				<div class="m-t-15"><label id="cicloVisitasAcumulado">0</label></div>
			</div>
			<div class="align-center" style="display: inline-block;">
				<div class="font-bold">Frecuencia</div>
				<div class="m-t-15"><label id="lblFrecVisita">0</label></div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="div-tbl-grey">
				<table id="tblVisitas" class="tblPlanesVisitas table-hover">
					<thead class="bg-grey">
						<tr class="align-center">
							<td style="width:11%;">Ciclo</td>
							<td style="width:8%;">Ruta</td>
							<td style="width:13%;">Fecha</td>
							<td style="width:9%;">Hora</td>
							<td style="width:19%;">Código visita</td>
							<td style="width:20%;">Resultado visita</td>
							<td style="width:20%;">Objetivo visita</td>
						</tr>
					</thead>
					<tbody class="pointer align-center">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!--#END VISITAS-->

<div id="tabs-5">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-l-0"><label id="lblLatitudPersonas"></label></div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label id="lblLongitudPersonas"></label></div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 align-center">
			<div id='map_canvas' class="align-center map-style"></div>
		</div>
	</div>
	<!-- <input type="text" maxlength="100" id="address" placeholder="Dirección" size="50" /> 
				<input type="button" id="search" value="Buscar" />-->
	<input type="hidden" id="txtLatitud" />
	<input type="hidden" id="txtLongitud" />
	<input type="hidden" maxlength="100" id="addressF" placeholder="Dirección" />
</div>

<!--<div id="tabs-6">
		<form enctype="multipart/form-data" id="formuploadajax" name="formuploadajax" method="POST">
			<table width="100%" border="0">
				<tr>
					<td rowspan="2" valign="top">
						<label id="lblNombreMedico6" class="lblMedicos"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label id="lblEspecialidad6" class="lblMedicos"></label>
					</td>
					<td class="negrita">
						Elige el Archivo a Subir:
						<input type="file" name="archivo" id="archivo"/>
					
					</td>
					<td rowspan="2" align="center" valign="bottom">
						<input type="submit" value="Subir archivo"/>
					</td>
				</tr>
				<tr>
					<td class="negrita">
						Información del archivo: <input type="text" size="50" id="txtInformacionArchivo" name="txtInformacionArchivo" />
					</td>
				</tr>
			</table>
		</form>
		<hr>
		<div id="divSubirDocumentosDatosPersonales">
			<table border="0" width="100%" class="grid" id="tblSubirDocumentosDatosPersonales">
				<thead>
					<tr>
						<td>
							Fecha de carga
						</td>
						<td>
							Nombre del archivo
						</td>
						<td>
							Info
						</td>
						<td width="5%">
							Descargar
						</td>
						<td width="5%">
							Eliminar
						</td>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>-->

<div id="tabs-7">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="form-group">
				<label class="negrita">Seleccione el año:</label>
				<select id="lstYearMuestra" class="form-control">
					<?php
						for($i=2007;$i<date("Y") + 2;$i++){
							if($i == date("Y")){
								echo '<option value="'.$i.'" selected>'.$i.'</option>';
							}else{
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						}
					?>
				</select>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 align-right">
			<button style="margin-top: 17px;" type="button" class="btn bg-indigo waves-effect btn-indigo"
				id="btnImprimirMuestraMaterialPersonas" title="Imprimir">
				<i class="material-icons">print</i>
				<span>Imprimir</span>
			</button>
		</div>
	</div>

	<div id="divMuestraMedica" class="div-tbl-grey">
		<table id="tblMuestraMedica" class="tblPlanesVisitas">
			<thead class="bg-grey align-center">
				<tr>
					<td style="width:13%;">
						Fecha de Entrega
					</td>
					<td style="width:15%;">
						Tipo de material
					</td>
					<td style="width:15%;">
						Producto
					</td>
					<td style="width:25%;">
						Presentación
					</td>
					<td style="width:20%;">
						Lote
					</td>
					<td style="width:12%;">
						Cantidad
					</td>
				</tr>
			</thead>
			<tbody class="align-center">
			</tbody>
		</table>
	</div>
</div>

<div id="tabs-8">
	<!--HISTORICO DE Eventos---->
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-9 col-xs-8">
			<label class="negrita" style="font-size: 12px">Histórico de eventos</label>
			<div class="select">
				<select id="lstYearEventos" class="form-control">
					<option value='0' hidden>Seleccione</option>
<?php
						for($i=date("Y");$i>=date("Y") - 5;$i--){
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
?>
				</select>
				<div class="select_arrow"></div>
			</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-3 col-xs-4">
			<button id="imgAgregarEventoPerfil" type="button" class="pull-right m-t-20 btn bg-indigo waves-effect btn-indigo"
				style="padding: 8px 12px;">
				Agregar
			</button>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="img-plan align-center">
				<div class="borde">1</div>
				<div class="td-cld-plan"><label id="ciclo1Evento">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">2</div>
				<div class="td-cld-plan"><label id="ciclo2Evento">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">3</div>
				<div class="td-cld-plan"><label id="ciclo3Evento">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">4</div>
				<div class="td-cld-plan"><label id="ciclo4Evento">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">5</div>
				<div class="td-cld-plan"><label id="ciclo5Evento">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">6</div>
				<div class="td-cld-plan"><label id="ciclo6Evento">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">7</div>
				<div class="td-cld-plan"><label id="ciclo7Evento">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">8</div>
				<div class="td-cld-plan"><label id="ciclo8Evento">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">9</div>
				<div class="td-cld-plan"><label id="ciclo9Evento">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">10</div>
				<div class="td-cld-plan"><label id="ciclo10Evento">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">11</div>
				<div class="td-cld-plan"><label id="ciclo11Evento">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">12</div>
				<div class="td-cld-plan"><label id="ciclo12Evento">0</label></div>
			</div>
			<div class="img-plan align-center" style="margin-right:15px;">
				<div class="borde">13</div>
				<div class="td-cld-plan"><label id="ciclo13Evento">0</label></div>
			</div>
			<div class="align-center m-r-15" style="display: inline-block;">
				<div class="font-bold">Total</div>
				<div class="m-t-15"><label id="acumuladoEvento">0</label></div>
			</div>
			<!--<div class="align-center" style="display: inline-block;">
				<div class="font-bold">Frecuencia</div>
				<div class="m-t-15"><label id="lblFrecPlan">0</label></div>
			</div>-->
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="div-tbl-grey">
				<table id="tblEventoPerfil" class="table-hover tblPlanesVisitas">
					<thead class="bg-grey">
						<tr class="align-center">
							<td style="width:10%;">Ciclo</td>
							<td style="width:10%;">Ruta</td>
							<td style="width:15%;">Inicio</td>
							<td style="width:15%;">Término</td>
							<td style="width:20%;">Nombre</td>
							<td style="width:20%;">Tipo</td>
							<td style="width:20%;">Participación</td>
						</tr>
					</thead>
					<tbody class="pointer align-center">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="tabs-9">
	<div class="row">	
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" style="display:none;">
			<div class="form-group">
				<label>Producto</label>
				<select id="lstProductoDatosPersonales" class="form-control">
					<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
					$qProductos = 'select prod_snr as id, name as nombre from PRODUCT where REC_STAT = 0 and STATUS = 1 and MARKETING_PLAN = 1 order by name';
					$rsProductos = sqlsrv_query($conn, $qProductos);
					while($producto = sqlsrv_fetch_array($rsProductos)){
						echo '<option value="'.$producto['id'].'">'.$producto['nombre'].'</option>';
					}
?>
				</select>
				<input type="hidden" id="hdnTablasPrescripciones" />
			</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" hidden>
			<div class="form-group">
				<label>Periodo</label>
				<div style="width:100%;" onclick="filtroPeriodos();">
					<select class='form-control'>
						<option id="sltPeriodo" >Seleccione</option>
					</select>
				</div>
			</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" hidden>
			<div class="form-group">
				<label>Mercado</label>
				<div style="width:100%;" onclick="filtroMercados();">
					<select class='form-control'>
						<option id="sltMercado" >Seleccione</option>
					</select>
				</div>
			</div>
		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 align-right" hidden>
			<br>
			<button type="button" class="btn bg-red waves-effect btn-red" id="imgFiltrarRx" style="height:30px;"
				data-toggle="tooltip" data-placement="top" title="Filtrar">
				<i class="fas fa-filter font-15"></i>
			</button>
		</div>
	</div>
	<div class="row" style="height:540px; overflow-y: auto;">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div id="tblPrescripciones">
				<div id="tblprod1">
					<div class="row col-indigo font-bold">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-0">
							<span class="col-md-6 p-l-0 margin-0">
								Período: TAM 11/18
							</span>
							<span class="col-md-6 align-right margin-0">
								Mdo: ATEKA MAY17 DW
							</span>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-0">
							<span class="col-md-4 margin-0 p-l-5">
								Categoría: 5
							</span>
							<span class="col-md-4 align-center margin-0">
								MS: 0.005...
							</span>
							<span class="col-md-4 align-right p-r-5 margin-0">
								Prescribe: 1
							</span>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="div-tbl-grey">
								<table class="tblRxPersonas">
									<thead class="bg-grey">
										<tr class="align-center">
											<td style="width:30%;">Ranking</td>
											<td style="width:40%;">Producto</td>
											<td style="width:30%;">Market Share</td>
										</tr>
									</thead>
									<tbody>
										<tr class="align-center">
											<td style="width:30%;">1</td>
											<td style="width:40%;">prod1</td>
											<td style="width:30%;">50%</td>
										</tr>
										<tr class="align-center">
											<td style="width:30%;">2</td>
											<td style="width:40%;">prod2</td>
											<td style="width:30%;">50%</td>
										</tr>
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

<div id="tabs-10">
	<!--INVERSIONES-->
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-9 col-xs-8">
			<label class="negrita" style="font-size: 12px">Histórico de Inversiones</label>
			<div class="select">
				<select id="lstYearInversiones" class="form-control">
					<option value='0' hidden>Seleccione</option>
					<?php
						for($i=date("Y");$i>=date("Y") - 5;$i--){
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
					?>
				</select>
				<div class="select_arrow"></div>
			</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-3 col-xs-4">
			<button id="imgAgregarInversion" type="button" class="pull-right m-t-20 btn bg-indigo waves-effect btn-indigo"
				style="padding: 8px 12px;">
				Agregar
			</button>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="img-plan align-center">
				<div class="borde">1</div>
				<div class="td-cld-plan"><label id="cicloInversion1">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">2</div>
				<div class="td-cld-plan"><label id="cicloInversion2">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">3</div>
				<div class="td-cld-plan"><label id="cicloInversion3">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">4</div>
				<div class="td-cld-plan"><label id="cicloInversion4">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">5</div>
				<div class="td-cld-plan"><label id="cicloInversion5">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">6</div>
				<div class="td-cld-plan"><label id="cicloInversion6">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">7</div>
				<div class="td-cld-plan"><label id="cicloInversion7">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">8</div>
				<div class="td-cld-plan"><label id="cicloInversion8">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">9</div>
				<div class="td-cld-plan"><label id="cicloInversion9">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">10</div>
				<div class="td-cld-plan"><label id="cicloInversion10">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">11</div>
				<div class="td-cld-plan"><label id="cicloInversion11">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">12</div>
				<div class="td-cld-plan"><label id="cicloInversion12">0</label></div>
			</div>
			<div class="img-plan align-center" style="margin-right:15px;">
				<div class="borde">13</div>
				<div class="td-cld-plan"><label id="cicloInversion13">0</label></div>
			</div>
			<div class="align-center m-r-15" style="display: inline-block;">
				<div class="font-bold">Total</div>
				<div class="m-t-15"><label id="acumuladoInversion">0</label></div>
			</div>
			<!--<div class="align-center" style="display: inline-block;">
				<div class="font-bold">Frecuencia</div>
				<div class="m-t-15"><label id="lblFrecInversion">0</label></div>
			</div>-->
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="div-tbl-grey">
				<table id="tblInversion" class="table-hover tblPlanesVisitas">
					<thead class="bg-grey">
						<tr class="align-center">
							<td style="width:15%;">Ciclo</td>
							<td style="width:10%;">Ruta</td>
							<td style="width:15%;">Fecha</td>
							<td style="width:15%;">Concepto</td>
							<td style="width:20%;">Producto</td>
							<td style="width:25%;">Comentarios</td>
						</tr>
					</thead>
					<tbody class="pointer align-center">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!--<div id="tabs-11">
		<table width="100%" >
			<tr>
				<td class="negrita" align="left"><br>
					Objetivos a corto plazo:<br>
					<textarea rows="8" cols="40" id="txtCortoPerson" disabled></textarea>
				</td>
				<td class="negrita" align="left"><br>
					Objetivos a largo plazo:<br>
					<textarea rows="8" cols="40" id="txtLargoPerson" disabled></textarea>
				</td>
				<td class="negrita" align="left"><br>
					Comentarios Generales:<br>
					<textarea rows="8" cols="40" id="txtGeneralesPerson" disabled></textarea>
				</td>
			</tr>
		</table>
	</div>
	
	<div id="tabs-12">
		<table width="100%" >
			<thead>
				<tr>
					<td class="negrita" align="left">
						Producto
					</td>
					<td class="negrita" align="left">
						W (Que producto)
					</td>
					<td class="negrita" align="left">
						H (Cuanto)
					</td>
					<td class="negrita" align="left">
						A (Porque)
					</td>
					<td class="negrita" align="left">
						T (Que voy hacer)
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><label id="lblProducto1"></label></td>
					<td><label id="lblProducto1W"></label></td>
					<td><label id="lblProducto1H"></label></td>
					<td><label id="lblProducto1A"></label></td>
					<td><label id="lblProducto1T"></label></td>
				</tr>
				<tr>
					<td><label id="lblProducto2"></label></td>
					<td><label id="lblProducto2W"></label></td>
					<td><label id="lblProducto2H"></label></td>
					<td><label id="lblProducto2A"></label></td>
					<td><label id="lblProducto2T"></label></td>
				</tr>
				<tr>
					<td><label id="lblProducto3"></label></td>
					<td><label id="lblProducto3W"></label></td>
					<td><label id="lblProducto3H"></label></td>
					<td><label id="lblProducto3A"></label></td>
					<td><label id="lblProducto3T"></label></td>
				</tr>
				<tr>
					<td><label id="lblProducto4"></label></td>
					<td><label id="lblProducto4W"></label></td>
					<td><label id="lblProducto4H"></label></td>
					<td><label id="lblProducto4A"></label></td>
					<td><label id="lblProducto4T"></label></td>
				</tr>
				<tr>
					<td><label id="lblProducto5"></label></td>
					<td><label id="lblProducto5W"></label></td>
					<td><label id="lblProducto5H"></label></td>
					<td><label id="lblProducto5A"></label></td>
					<td><label id="lblProducto5T"></label></td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div id="tabs-13">
		<table width="100%" >
			<tr>
				<td class="negrita" align="left"><br>
					Preguntas
				</td>
				<td class="negrita" align="left"><br>
					Respuestas
				</td>
			</tr>
			<tr>
				<td class="negrita" align="left"><br>
					GANAR (¿Porque no le gusta mi producto?)
				</td>
				<td class="negrita" align="left"><br>
					<label id="lblRespuesta1"></label>
				</td>
			</tr>
			<tr>
				<td class="negrita" align="left"><br>
					DESARROLLAR (¿Que le hace falta para incrementar su Rx?)
				</td>
				<td class="negrita" align="left"><br>
					<label id="lblRespuesta2"></label>
				</td>
			</tr>
			<tr>
				<td class="negrita" align="left"><br>
					DEFENDER (¿Que es lo que más le gusta del producto?)
				</td>
				<td class="negrita" align="left"><br>
					<label id="lblRespuesta3"></label>
				</td>
			</tr>
			<tr>
				<td class="negrita" align="left"><br>
					EVALUAR (¿Porque no le gusta mi producto?)
				</td>
				<td class="negrita" align="left"><br>
					<label id="lblRespuesta4"></label>
				</td>
			</tr>
		</table>
	</div>-->