<?php
	date_default_timezone_set('America/Mexico_City');
	// Unix
	setlocale(LC_TIME, 'es_MX.UTF-8');
	// En windows
	setlocale(LC_TIME, 'spanish');
	$hoy = date("Y-m-d");
	$fechaSlt = date("Y-m-d");

	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
	$fechaCompleta = array();
	$fechaPlanVisita = array();
	$week = date("W");
	
	$day = date('w');
	$fechaCompleta["Domingo"] = date('d/m/Y', strtotime('+'.(7-$day).' days'));
	$fechaCompleta["Lunes"] = date('d/m/Y', strtotime('+'.(1-$day).' days'));
	$fechaCompleta["Martes"] = date('d/m/Y', strtotime('+'.(2-$day).' days'));
	$fechaCompleta["Miercoles"] = date('d/m/Y', strtotime('+'.(3-$day).' days'));
	$fechaCompleta["Jueves"] = date('d/m/Y', strtotime('+'.(4-$day).' days'));
	$fechaCompleta["Viernes"] = date('d/m/Y', strtotime('+'.(5-$day).' days'));
	$fechaCompleta["Sabado"] = date('d/m/Y', strtotime('+'.(6-$day).' days'));
	$fechaPlanVisita["Domingo"] = date('Y-m-d', strtotime('+'.(7-$day).' days'));
	$fechaPlanVisita["Lunes"] = date('Y-m-d', strtotime('+'.(1-$day).' days'));
	$fechaPlanVisita["Martes"] = date('Y-m-d', strtotime('+'.(2-$day).' days'));
	$fechaPlanVisita["Miercoles"] = date('Y-m-d', strtotime('+'.(3-$day).' days'));
	$fechaPlanVisita["Jueves"] = date('Y-m-d', strtotime('+'.(4-$day).' days'));
	$fechaPlanVisita["Viernes"] = date('Y-m-d', strtotime('+'.(5-$day).' days'));
	$fechaPlanVisita["Sabado"] = date('Y-m-d', strtotime('+'.(6-$day).' days'));

	$diaL=substr($fechaCompleta['Lunes'], 0, 2);
	$mesL=substr($fechaCompleta['Lunes'], 3, 2);
	$anioL=substr($fechaCompleta['Lunes'], 6, 4);
	$diaM=substr($fechaCompleta['Martes'], 0, 2);
	$mesM=substr($fechaCompleta['Martes'], 3, 2);
	$anioM=substr($fechaCompleta['Martes'], 6, 4);
	$diaMi=substr($fechaCompleta['Miercoles'], 0, 2);
	$mesMi=substr($fechaCompleta['Miercoles'], 3, 2);
	$anioMi=substr($fechaCompleta['Miercoles'], 6, 4);
	$diaJ=substr($fechaCompleta['Jueves'], 0, 2);
	$mesJ=substr($fechaCompleta['Jueves'], 3, 2);
	$anioJ=substr($fechaCompleta['Jueves'], 6, 4);
	$diaV=substr($fechaCompleta['Viernes'], 0, 2);
	$mesV=substr($fechaCompleta['Viernes'], 3, 2);
	$anioV=substr($fechaCompleta['Viernes'], 6, 4);
	$diaS=substr($fechaCompleta['Sabado'], 0, 2);
	$mesS=substr($fechaCompleta['Sabado'], 3, 2);
	$anioS=substr($fechaCompleta['Sabado'], 6, 4);
	$diaD=substr($fechaCompleta['Domingo'], 0, 2);
	$mesD=substr($fechaCompleta['Domingo'], 3, 2);
	$anioD=substr($fechaCompleta['Domingo'], 6, 4);	
?>

<script>
	var diaLunes = '';
	var mesLunes = '';
	var anioLunes = '';
	var diaMartes = '';
	var mesMartes = '';
	var anioMartes = '';
	var diaMiercoles = '';
	var mesMiercoles = '';
	var anioMiercoles = '';
	var diaJueves = '';
	var mesJueves = '';
	var anioJueves = '';
	var diaViernes = '';
	var mesViernes = '';
	var anioViernes = '';
	var diaSabado = '';
	var mesSabado = '';
	var anioSabado = '';
	var diaDomingo = '';
	var mesDomingo = '';
	var anioDomingo = '';
</script>

<input type="hidden" name="fecha" id="fecha" />
<input type="hidden" name="anterior" id="anterior" />
<input type="hidden" id="hdnFechaCalendario" value="<?= date('Y-m-d') ?>" />
<input type="hidden" name="hdnDiaUno" id="hdnDiaUno" value="<?= date('Y-m-d', strtotime('+'.(1-$day).' days')) ?>" />
<input type="hidden" name="hdnDiaDos" id="hdnDiaDos" value="<?= date('Y-m-d', strtotime('+'.(2-$day).' days')) ?>" />
<input type="hidden" name="hdnDiaTres" id="hdnDiaTres" value="<?= date('Y-m-d', strtotime('+'.(3-$day).' days')) ?>" />
<input type="hidden" name="hdnDiaCuatro" id="hdnDiaCuatro"
	value="<?= date('Y-m-d', strtotime('+'.(4-$day).' days')) ?>" />
<input type="hidden" name="hdnDiaCinco" id="hdnDiaCinco"
	value="<?= date('Y-m-d', strtotime('+'.(5-$day).' days')) ?>" />
<input type="hidden" name="hdnDiaSeis" id="hdnDiaSeis" value="<?= date('Y-m-d', strtotime('+'.(6-$day).' days')) ?>" />
<input type="hidden" name="hdnDiaSiete" id="hdnDiaSiete"
	value="<?= date('Y-m-d', strtotime('+'.(7-$day).' days')) ?>" />

<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<div class="row m-t--10">
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<h2>
						<i class="fas fa-calendar-alt"></i>
						<span>CALENDARIO</span>
					</h2>
				</div>

				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 align-right" id="menuFiltrosGnr">
					<div class="display-inline">
						<button id="btnPlanCalendario"
							class="btn waves-effect btn-account-head btn-account-l btn-account-sel" disabled>
							Planes
						</button>
						<button id="btnVisitaCalendario" class="btn waves-effect btn-account-head btn-account-r">
							Visitas
						</button>

						<!--<button id="btnRuteo" class="btn bg-red waves-effect btn-red-hover"> Ruteo </button>-->
						<button id="btnPlanearRapido" style="display:none;"
							class="btn bg-red waves-effect btn-red-hover">Planear</button>
					</div>
				</div>
			</div>
		</div>

		<div class="row clearfix">
			<!--LISTA PLANES/VISITAS HOY-->
			<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 plan-list">
				<div class="card">
					<div class="header bg-indigo">
						<h2>
							<small>
								<span id="tituloPlanHoy">Planes para hoy: </span>
								<span id="tituloVisitaHoy" style="display:none;">Visitas realizadas hoy: </span>
								<label id="lblHoy">
									<?php echo date('d/m/Y'); ?>
								</label>
								<?php 
							//echo date('d/m/Y'); 
							$arrHoy = planesVisitasCalendario($hoy, 'plan', $conn, $idUsuario, $ids);
							//echo $arrHoy[1];
?>
							</small>
						</h2>
					</div>
					<div class="body cardInfCal" style="height: 675px; overflow: auto;">
						<!--Aqui van los planes o visitas del presente día-->
						<table class="table table-striped table-hover pointer margin-0" id="tblHoy">
							<?= $arrHoy[0] ?>
						</table>
					</div>
				</div>
			</div>
			<!--# END LISTA PLANES/VISITAS HOY-->

			<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
				<!--<div class="card" style="height:735px;">-->
				<div class="card">
					<div class="header">
						<div class="row">
							<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 align-left add-m-b">
								<div class="display-inline">
									<button onClick="changeCalendar()" id="btnWeekSelect"
										class="btn waves-effect btn-calendar-head btn-ch-l btn-blue-sel" disabled>
										Semana </button>
									<button onClick="changeCalendar()" id="btnMonthSelect"
										class="btn waves-effect btn-calendar-head btn-ch-r">
										Mes
									</button>
								</div>
							</div>

							<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
								<div class="display-flex">
									<label class="m-r-5 p-t-5" id="lblRep">Ruta: </label>
									<?php
							if($tipoUsuario != 4){
								echo "<div style='width:100%;' onclick=\"filtrosUsuarios('cal');\">
										<select class='form-control'>
											<option id=\"sltMultiSelectCal\" >Seleccione</option>
										</select>
									</div>";
							}else{
								/*if($tipoUsuario != 4){
									echo "<select multiple='multiple' class='select-multi' id='sltMultiSelectCal'>";
									$i = 0;
									$nombres = '';
									$queryRutas = "select * from users where user_snr in ('".$ids."') order by lname";
										$nombreRepre = utf8_encode($usuario['LNAME'].' '.$usuario['FNAME']);
											$rsRutas = sqlsrv_query($conn, $queryRutas);
											while($regRutas = sqlsrv_fetch_array($rsRutas)){
												echo '<option value="'.$nombreRepre.'">'.utf8_encode($regRutas["LNAME"].' '.$regRutas["FNAME"]).'</option>';	
											}
									echo	"</select>";
								}else{*/

								echo '<select id="sltRepreCalendario" class="form-control">';
								$repre = sqlsrv_query($conn, "select user_snr, lname + ' ' + fname as nombre from users where user_snr in ('".$ids."')");
								while($rep = sqlsrv_fetch_array($repre)){
									echo '<option value="'.$rep['user_snr'].'">'.$rep['nombre'].'</option>';
								}
								echo '</select>';
							}						
	?>
								</div>
							</div>
							<!--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-blue font-12 align-right p-t-3">
								<button id="btnWeekSelected" class="btn btn-default waves-effect col-blue"> HOY </button>
							</div>-->


						</div>
					</div>
					<div class="body">
						<div id="divWeek" class="cardInfCal">
							<div id="resultPrueba"></div>
							<div class="align-center  m-b-5">
								<div class="display-inline">
									<p role="button" onClick="semanaAntes();" class="margin-0 p-r-10 pointer">
										<i class="material-icons font-25 next-month">chevron_left</i>
									</p>
									<p class="font-20">
										<span id="txtFechaInicioSemana"><?= $fechaCompleta['Lunes'] ?></span> -
										<span id="txtFechaFinSemana"><?= $fechaCompleta['Viernes'] ?></span>
									</p>
									<p role="button" onClick="semanaDespues();" class="margin-0 p-l-10 pointer">
										<i class="material-icons font-25 next-month">chevron_right</i>
									</p>
								</div>
							</div>

							<div class="row" style="padding:0 15px;">
								<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-l-7 p-r-7">
									<div id="tblDiaUnoActualiza" class="divPlanVisita">
										<div class="align-center">
											<label class="titulosDias" id="lblTituloDiaUno">
												<?php 
													echo "Lunes: ".$fechaCompleta['Lunes']; 
													$arrLunes = planesVisitasCalendario($fechaPlanVisita['Lunes'], 'plan', $conn, $idUsuario, $ids);
												?>
											</label>
										</div>
										<div class="align-center">
											<i class="fas fa-calendar-week font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalOtrasActividadesDiaUno">
												<?= $arrLunes[3] ?>
											</label>
											<i class="fas fa-user-md font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalPersonasDiaUno">
												<?= $arrLunes[1] ?>
											</label>
											<i class="fas fa-building font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalInstDiaUno">
												<?= $arrLunes[2] ?>
											</label>
											<label class="p-l-15">
												<i onclick="traePlanVisitasRuteo(diaLunes,mesLunes,anioLunes)"
													class="fas fa-map-marker-alt pointer font-23 col-blue m-t-5 m-b-5"
													data-toggle="tooltip" data-placement="top" title="Ruteo"></i>
											</label>
										</div>

										<div class="planVisitListBody">
											<table id="tblDiaUno"
												class="table table-striped table-hover pointer margin-0">
												<?= $arrLunes[0] ?>
											</table>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-l-7 p-r-7">
									<div id="tblDiaDosActualiza" class="divPlanVisita">
										<div class="align-center">
											<label class="titulosDias" id="lblTituloDiaDos">
												<?php 
													echo "Martes: ".$fechaCompleta['Martes']; 
													$arrMartes = planesVisitasCalendario($fechaPlanVisita['Martes'], 'plan', $conn, $idUsuario, $ids);
												?>
											</label>
										</div>
										<div class="align-center">
											<i class="fas fa-calendar-week font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalOtrasActividadesDiaDos">
												<?= $arrMartes[3] ?>
											</label>
											<i class="fas fa-user-md font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalPersonasDiaDos">
												<?= $arrMartes[1] ?>
											</label>
											<i class="fas fa-building font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalInstDiaDos">
												<?= $arrMartes[2] ?>
											</label>
											<label class="p-l-15">
												<i onclick="traePlanVisitasRuteo(diaMartes,mesMartes,anioMartes);"
													class="fas fa-map-marker-alt pointer font-23 col-blue m-t-5 m-b-5"
													data-toggle="tooltip" data-placement="top" title="Ruteo"></i>
											</label>
										</div>
										<div class="planVisitListBody">
											<table id="tblDiaDos"
												class="table table-striped table-hover pointer margin-0">
												<?= $arrMartes[0] ?>
											</table>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-l-7 p-r-7">
									<div id="tblDiaTresActualiza" class="divPlanVisita">
										<div class="align-center">
											<label class="titulosDias" id="lblTituloDiaTres">
												<?php 
													echo "Miercoles: ".$fechaCompleta['Miercoles']; 
													$arrMiercoles = planesVisitasCalendario($fechaPlanVisita['Miercoles'], 'plan', $conn, $idUsuario, $ids);
						?>
											</label>
										</div>
										<div class="align-center">
											<i class="fas fa-calendar-week font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalOtrasActividadesDiaTres">
												<?= $arrMiercoles[3] ?></label>
											<i class="fas fa-user-md font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalPersonasDiaTres">
												<?= $arrMiercoles[1] ?></label>
											<i class="fas fa-building font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalInstDiaTres">
												<?= $arrMiercoles[2] ?></label>
											<label class="p-l-15">
												<i onclick="traePlanVisitasRuteo(diaMiercoles,mesMiercoles,anioMiercoles);"
													class="fas fa-map-marker-alt pointer font-23 col-blue m-t-5 m-b-5"
													data-toggle="tooltip" data-placement="top" title="Ruteo"></i>
											</label>
										</div>
										<div class="planVisitListBody">
											<table id="tblDiaTres"
												class="table table-striped table-hover pointer margin-0">
												<?= $arrMiercoles[0] ?>
											</table>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-l-7 p-r-7">
									<div id="tblDiaCuatroActualiza" class="divPlanVisita">
										<div class="align-center">
											<label class="titulosDias" id="lblTituloDiaCuatro">
												<?php 
													echo "Jueves: ".$fechaCompleta['Jueves']; 
													$arrJueves = planesVisitasCalendario($fechaPlanVisita['Jueves'], 'plan', $conn, $idUsuario, $ids);
						?>
											</label>
										</div>
										<div class="align-center">
											<i class="fas fa-calendar-week font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalOtrasActividadesDiaCuatro">
												<?= $arrJueves[3] ?></label>
											<i class="fas fa-user-md font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalPersonasDiaCuatro">
												<?= $arrJueves[1] ?></label>
											<i class="fas fa-building font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalInstDiaCuatro">
												<?= $arrJueves[2] ?></label>
											<label class="p-l-15">
												<i onclick="traePlanVisitasRuteo(diaJueves,mesJueves,anioJueves);"
													class="fas fa-map-marker-alt pointer font-23 col-blue m-t-5 m-b-5"
													data-toggle="tooltip" data-placement="top" title="Ruteo"></i>
											</label>
										</div>
										<div class="planVisitListBody">
											<table id="tblDiaCuatro"
												class="table table-striped table-hover pointer margin-0">
												<?= $arrJueves[0] ?>
											</table>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-l-7 p-r-7">
									<div id="tblDiaCincoActualiza" class="divPlanVisita">
										<div class="align-center">
											<label class="titulosDias" id="lblTituloDiaCinco">
												<?php 
													echo "Viernes: ".$fechaCompleta['Viernes'];
													$arrViernes = planesVisitasCalendario($fechaPlanVisita['Viernes'], 'plan', $conn, $idUsuario, $ids);
						?>
											</label>
										</div>
										<div class="align-center">
											<i class="fas fa-calendar-week font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalOtrasActividadesDiaCinco">
												<?= $arrViernes[3] ?></label>
											<i class="fas fa-user-md font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalPersonasDiaCinco">
												<?= $arrViernes[1] ?></label>
											<i class="fas fa-building font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalInstDiaCinco">
												<?= $arrViernes[2] ?></label>
											<label class="p-l-15">
												<i onclick="traePlanVisitasRuteo(diaViernes,mesViernes,anioViernes);"
													class="fas fa-map-marker-alt pointer font-23 col-blue m-t-5 m-b-5"
													data-toggle="tooltip" data-placement="top" title="Ruteo"></i>
											</label>
										</div>
										<div class="planVisitListBody">
											<table id="tblDiaCinco" class="table table-striped table-hover pointer margin-0">
												<?= $arrViernes[0] ?>
											</table>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-l-7 p-r-7">
									<div id="tblDiaSeisActualiza" class="divPlanVisita">
										<div class="align-center">
											<label class="titulosDias" id="lblTituloDiaSeis">
												<?php 
													echo "Sábado: ".$fechaCompleta['Sabado'];
													$arrSabado = planesVisitasCalendario($fechaPlanVisita['Sabado'], 'plan', $conn, $idUsuario, $ids);
						?>
											</label>
										</div>
										<div class="align-center">
											<i class="fas fa-calendar-week font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalOtrasActividadesDiaSeis">
												<?= $arrSabado[3] ?></label>
											<i class="fas fa-user-md font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalPersonasDiaSeis">
												<?= $arrSabado[1] ?></label>
											<i class="fas fa-building font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalInstDiaSeis">
												<?= $arrSabado[2] ?></label>
											<label class="p-l-15">
												<i onclick="traePlanVisitasRuteo(diaSabado,mesSabado,anioSabado);"
													class="fas fa-map-marker-alt pointer font-23 col-blue m-t-5 m-b-5"
													data-toggle="tooltip" data-placement="top" title="Ruteo"></i>
											</label>
										</div>
										<div class="planVisitListBody">
											<table id="tblDiaSeis" class="table table-striped table-hover pointer margin-0">
												<?= $arrSabado[0] ?>
											</table>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-l-7 p-r-7">
								<div id="tblDiaSieteActualiza" class="divPlanVisita">
										<div class="align-center">
											<label class="titulosDias" id="lblTituloDiaSiete">
												<?php 
													echo "Domingo: ".$fechaCompleta['Domingo'];
													$arrDomingo = planesVisitasCalendario($fechaPlanVisita['Domingo'], 'plan', $conn, $idUsuario, $ids);
						?>
											</label>
										</div>
										<div class="align-center">
											<i class="fas fa-calendar-week font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalOtrasActividadesDiaSiete">
												<?= $arrDomingo[3] ?></label>
											<i class="fas fa-user-md font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalPersonasDiaSiete">
												<?= $arrDomingo[1] ?></label>
											<i class="fas fa-building font-23 col-indigo m-t-5 m-b-5"></i>
											<label id="totalInstDiaSiete">
												<?= $arrDomingo[2] ?></label>
											<label class="p-l-15">
												<i onclick="traePlanVisitasRuteo(diaDomingo,mesDomingo,anioDomingo);"
													class="fas fa-map-marker-alt pointer font-23 col-blue m-t-5 m-b-5"
													data-toggle="tooltip" data-placement="top" title="Ruteo"></i>
											</label>
										</div>
										<div class="planVisitListBody">
											<table id="tblDiaSiete" class="table table-striped table-hover pointer margin-0">
												<?= $arrDomingo[0] ?>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<!--#END SEMANA-->

						<div id="divCalendarioCambia" style="display:none;">
							<table width="100%" border="0" bgcolor="#FFFFFF">
								<tr>
									<td>
										<table border="0" style="width:100%;">
											<tr>

											</tr>
											<tr>
												<td>
													<div id="calendario">
														<?php 
															if($tipoUsuario == 4){
																calendar_html(1, $conn, $idUsuario, $ids, '');
															}else{
																$idUsuarioTemp = $idUsuario;
																$idUsuario = $ids."','".$idUsuario;
																calendar_html(1, $conn, $idUsuario, $ids, '');
																$idUsuario = $idUsuarioTemp;
															}
														?>
													</div>
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<table border="0"
														style="border-collapse: separate;border-spacing:  5px 5px;">
														<tr>
															<td>
																<div class="circulos-tipo-evento bg-pink"></div>
															</td>
															<td class="font-bold">
																Planes
															</td>
															<td>
																<div class="circulos-tipo-evento bg-light-green"></div>
															</td>
															<td class="font-bold">
																Visitas
															</td>
															<td>
																<div class="circulos-tipo-evento bg-light-blue"></div>
															</td>
															<td class="font-bold">
																TFT
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="fixed-action-btn" id="btnCalMed">
			<!--<button id="agregarPlanVisita" class="btn bg-red btn-circle waves-effect waves-circle waves-float btn-red-hf">
				<i class="material-icons">add</i>
			</button>-->
			<a class="btn-floating btn-large bg-red btn-txt" >
				<i class="material-icons">add</i><span id="spanP" class="col-white m-l-10"
					style="display:none;">AGREGAR</span>
			</a>
			<ul class="no-style-list">
				<li id="liOtrasActividades">
					<a class="btn-floating bg-pink btn-act" onClick="abreOtrasActividades2('<?= $hoy ?>');">
						<span class="col-white m-r-10" style="display:none;">ACTIVIDAD</span>
						<i class="fas fa-calendar-week"></i>
					</a>
				</li>
				<li >
					<a class="btn-floating bg-amber btn-med" onClick="abreBuscarPersona2('<?= $hoy ?>');" >
						<span class="col-white m-r-10" style="display:none;">MÉDICO</span>
						<i class="fas fa-user-md"></i>
					</a>
				</li>
				<li >
					<a class="btn-floating bg-green btn-inst" onClick="abreBuscarFarmacia('<?= $hoy ?>');" >
						<span class="col-white m-r-10" style="display:none;">FARMACIA</span>
						<i class="fas fa-pills"></i>
					</a>
				</li>
				<li >
					<a class="btn-floating bg-blue btn-hos" onClick="abreBuscarHospital('<?= $hoy ?>');" >
						<span class="col-white m-r-10" style="display:none;">HOSPITAL</span>
						<i class="fas fa-hospital"></i>
					</a>
				</li>
			</ul>
		</div>
	</div>
</section>