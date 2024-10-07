<input type="hidden" id="hdnIdOA" value="" />
<input type="hidden" id="hdnIdUsuarioOA" value="" />

<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Otras Actividades
					</h2>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">
					<button type="button" type="button" class="btn bg-indigo waves-effect btn-indigo"
						id="btnGuardarPeriodo">
						Activar período
					</button>
					<button type="button" type="button"
						class="btn bg-white col-indigo waves-effect btn-indigo2"
						id="btnQuitarPeriodo" style="display:none;">
						Desactivar período
					</button>
					<button id="btnEliminarOtrasActividades" class="btn bg-indigo waves-effect btn-indigo m-l-10" type="button" <?=($tipoUsuario==4)
					 ? "disabled" : "" ?>>
						Borrar
					</button>
					<button id="btnGuardarOtrasActividades" type="button"
						class="btn bg-indigo waves-effect btn-indigo m-l-10">
						Guardar
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCancelarOtrasActividades" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div id="tabOtrasActividades">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="new-div add-scroll-y">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

									<div class="form-group margin-0">
										<label>Representante</label>
										<?php
									if($tipoUsuario != 4){
										echo "<div class=\"selectBox\" onclick=\"filtrosUsuarios('oa');\">
											
												<select class=\"form-control\">
													<option id=\"sltMultiSelectOA\" >Seleccione</option>
												</select>
										</div>";								
									}else{//es repre
										echo '<select id="sltRepreOtrasActividades" class="form-control">';
										$queryRepres = "select user_snr, lname + ' ' + mothers_lname + ' ' + fname as nombre from users where USER_SNR in ('".$ids."') order by lname, mothers_lname, fname";
										echo $queryRepres;
										$repre = sqlsrv_fetch_array(sqlsrv_query($conn, $queryRepres));
										//echo '<option id="0">Seleccione</option>';
										echo '<option id="'.$repre['user_snr'].'">'.$repre['nombre'].'</option>';
										echo '</select>';
									}
?>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
									<div class="form-group margin-0">
										<label>Fecha</label>
										<input class="form-control" type="text" id="txtFechaReportarOtrasActividades"
											size="10">
									</div>
									<label for="txtFechaReportarOtrasActividades"></label>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
									<div id="tdFechaOtrasActividades" style="display:none;">
										<div class="form-group margin-0">
											<label>Fecha término</label>
											<input class="form-control" type="text"
												id="txtFechaReportarOtrasActividadesFin" size="10">
										</div>
										<label for="txtFechaReportarOtrasActividadesFin"></label>
									</div>
								</div>

								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								
										<div class="form-group margin-0">
											<label>Comentarios:</label><br>
											<textarea class="form-control" id="txtAreaReportarOtrasActividades" name="textarea" ></textarea>
											
										</div>
										
									
								</div>

							</div>
							<!--<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group margin-0">
											<label>Información Adicional</label>
											<textarea id="txtComentariosOtrasActividades" class="text-notas2" rows="3"></textarea>
										</div>
									</div>
								</div>-->
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label>Actividades del día</label>
									<div id="divOtrasActividades">
										<table class="table table-striped margin-0" id="tblOtrasAct">
											<?php
														$rsOtrasActividades = llenaCombo($conn, 51, 84);
														$i=0;
														while($actividad = sqlsrv_fetch_array($rsOtrasActividades)){
															//echo $actividad['nombre']."<br>";
															$i++;
															echo '<tr>
																<td class="col-lg-9 col-md-9 col-sm-9 col-xs-9 margin-0" style="padding:5px;">
																	<div class="switch m-t-5">
																		<label>
																			<input type="checkbox" id="chkOA'.$i.'" value="'.$actividad['id'].'" checked="false">
																			<span class="lever"></span>
																			<span>'.$actividad['nombre'].'</span>
																		</label>
																	</div>
																</td>
																<td class="col-lg-3 col-md-3 col-sm-3 col-xs-3 margin-0" style="padding:5px;">
																	<div id="maxHoras" style="display:none;">La suma no puede exceder de 8 hrs</div>
																	<div class="form-group margin-0 display-flex">
																		<input class="col-lg-10 col-md-9 col-sm-9 col-xs-8 margin-0 form-control" style="height:30px;" type="number" id="txtOA'.$i.'" onkeyup="sumaHoras('.$i.');" value=""/>
																		<span class="col-lg-2 col-md-3 col-sm-3 col-xs-4 margin-0 p-t-6 p-l-5 p-r-0">Hrs.</span>
																	</div>
																</td>
															</tr>';
														}
?>
										</table>
									</div>
									<input type="hidden" id="hdnTotalChkOA" value="<?= $i ?>" />
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-0">
									<div class="form-group margin-0">
										Total de horas de todas las actividades:
										<input type="text" size="4" style="border: 0px;text-align:right;" value="0"
											id="txtTotalActividades" readonly="readonly" />
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