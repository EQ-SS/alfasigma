<?php
	if(! isset($_GET['idUser']) || $_GET['idUser'] == ''){
		header('Location: index.php');
	}else{
		$hoy = date("Y-m-d");

		
		//$hoy = '2017-09-04';
		$queryTipoUsuario = "select * from users where rec_stat = 0 and user_snr = '".$_GET['idUser']."'";
		$rsTipoUsuario = sqlsrv_query($conn, $queryTipoUsuario, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		if(sqlsrv_num_rows($rsTipoUsuario) > 0){
			while($row = sqlsrv_fetch_array($rsTipoUsuario)){
				$tipoUsuario = $row['USER_TYPE'];
			}
			if($tipoUsuario == 4)///es representante
			{
				$ids = $_GET['idUser'];
			}else if($tipoUsuario == 5)///es gerente
			{
				$queryUsuarios = "select USER_SNR from kloc_reg k, users u
						where reg_snr = '".$_GET['idUser']."'
						and k.REC_STAT = 0 
						and KLOC_SNR = USER_SNR
						and u.REC_STAT = 0
						/*and u.USER_TYPE = 4*/
						order by u.LNAME ";
			}else///admin o cualquier otro usuario
			{
				$queryUsuarios = "select USER_SNR from users where USER_TYPE in (4,5) and rec_stat = 0 ";
			}
			//echo $queryUsuarios"<br>";
			if($tipoUsuario != 4){
				$ids = '';
				$rsids = sqlsrv_query($conn, $queryUsuarios);
				while($rowId = sqlsrv_fetch_array($rsids)){
					$ids .= $rowId['USER_SNR']."','";
				}
				$ids = substr($ids, 0, -3);
			}
		}else{
			die('El usuario no es válido!!!');
		}
	}
?>
<input type="hidden" id="hdnIdUser" value="<?= $idUsuario ?>" />
<input type="hidden" id="hdnTipoUsuario" value="<?= $tipoUsuario ?>" />
<input type="hidden" id="hdnIds" value="<?= $ids ?>" />
<input type="hidden" id="hdnHoy" value="<?= $hoy ?>" />

<section class="content">
	<div class="container-fluid">
		<div class="block-header block-headerInicio">
			<div class="row margin-0">
				<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 margin-0 p-t-10 p-l-0">
					<h2 style="text-transform:uppercase;" class="add-m-b">
						BIENVENIDO(A), <?= utf8_encode($rutaEtiqueta) ?>
					</h2>
					<div id="divTest">
					</div>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 margin-0 p-r-0 align-right">
					<div id="showSltGte">
						<?php
						if($tipoUsuario != 4){
							if($tipoUsuario == 2){
								echo '<div class="form-group display-flex margin-0 pull-right" style="width:50%;">
									<label class="m-t-5 m-r-5">Gte:</label>
									<select id="sltGte" class="form-control">
										<option value="">Todos</option>';
										$queryGte = "select * from users where USER_TYPE = '5' and REC_STAT = 0 order by lname";
										$rsGte = sqlsrv_query($conn, $queryGte);
										while($regGte = sqlsrv_fetch_array($rsGte)){
											echo '<option value="'.$regGte["USER_SNR"].'">'.utf8_encode($regGte["LNAME"].' '.$regGte["FNAME"]).'</option>';				
										}
								echo '</select></div>';
							}
						
								echo '<div id="sltRepreInicio" style="display:none;width:50%;" class="m-l-5">
								<div class="form-group display-flex margin-0">
								<label class="m-t-5 m-r-5">Ruta:</label>
								<select id="sltRutas2" class="form-control">
									<option value="">Todas las rutas</option>';
								$queryRutas = "select * from users where user_snr in ('".$ids."') order by lname";
								$rsRutas = sqlsrv_query($conn, $queryRutas);
								while($regRutas = sqlsrv_fetch_array($rsRutas)){
									echo '<option value="'.$regRutas["USER_SNR"].'">'.utf8_encode($regRutas["LNAME"].' '.$regRutas["FNAME"]).'</option>';				
								}
								echo '</select></div>
								</div>';
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<div id="tblInicio" class="row clearfix">
			<div class="row m-b-25">
				<div id="tblGraficas1" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<div class="card">
							<div class="header">
								<div class="row">
									<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<div class="display-flex">
											<?php
												if($tipoUsuario != 4){
													echo '<label class="m-t-5 m-r-5">Ruta:</label>
													<select id="sltRutas" class="form-control add-m-b">
														<option value="">Todas las rutas</option>';
													$queryRutas = "select * from users where user_snr in ('".$ids."') order by lname";
													$rsRutas = sqlsrv_query($conn, $queryRutas);
													while($regRutas = sqlsrv_fetch_array($rsRutas)){
														echo '<option value="'.$regRutas["USER_SNR"].'">'.utf8_encode($regRutas["LNAME"].' '.$regRutas["FNAME"]).'</option>';				
													}
													echo '</select>';
												} else {
													echo '<div></div>';
												}
											?>
										</div>
									</div>
									<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<div class="display-flex pull-right">
											<button type="button" class="btn bg-red waves-effect btn-red" id="btnExportarPlan">
												<i class="material-icons">file_download</i>
												<span>Descargar</span>
											</button>
											<button type="button" class="btn bg-red waves-effect btn-red m-l-5" id="btnImprimirPlan">
												<i class="material-icons">print</i>
												<span>Imprimir</span>
											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="body">
								<div id="divTablaPlanes" class="table-responsive">
									<table class="table-hover table-striped" id="tblPlanesInicio">
										<thead>
											<tr>
												<?php
													if($tipoUsuario != 4){
												?>
												<td style="width:20%;">Ruta</td>
												<td style="width:50%;">Datos del médico</td>
												<td class="align-center" style="width:15%;">Fecha Plan</td>
												<td class="align-center" style="width:15%;">Hora Plan</td>
												<?php
													}else{
												?>
												<td style="width:60%;">Datos del médico</td>
												<td class="align-center" style="width:20%;">Fecha Plan</td>
												<td class="align-center" style="width:20%;">Hora Plan</td>
												<?php
													}
												?>
											</tr>
										</thead>
										<tbody class="pointer">
										</tbody>
										<tfoot>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
								<div class="card" style="">
									<div class="header">
										<h2>Cobertura vs Fichero</h2>
										<ul class="header-dropdown m-r--5">
											<li class="dropdown">
												<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
												 aria-expanded="false">
													<i class="material-icons">more_vert</i>
												</a>
												<ul class="dropdown-menu pull-right">
													<li id='exportpng3'><a>Descargar como PNG</a></li>
													<li id='exportpdf3'><a>Descargar como PDF</a></li>
												</ul>
											</li>
										</ul>
									</div>
									<div class="body align-center grafica1-body" style="height:295px;padding:0 10px">
										<div class="square-white2"></div>
										<div class="rectangle-header"></div>
										<div id="grafica3"></div>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="info-box bg-indigo hover-expand-effect">
											<div class="icon">
												<i class="fas fa-user-md"></i>
											</div>
											<div class="content">
												<div>
													<div class="text">Fichero Médico</div>
													<div id="ficheroMedico"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="info-box bg-orange hover-expand-effect">
											<div class="icon">
												<i class="fas fa-handshake"></i>
											</div>
											<div class="content">
												<div>
													<div class="text">Visita Única</div>
													<div id="visitaUnica"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
								<div class="card" style="">
									<div class="header">
										<h2>Cobertura vs Contactos</h2>
										<ul class="header-dropdown m-r--5">
											<li class="dropdown">
												<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
												 aria-expanded="false">
													<i class="material-icons">more_vert</i>
												</a>
												<ul class="dropdown-menu pull-right">
													<li id='exportpng4'><a href="javascript:void(0);">Descargar como PNG</a></li>
													<li id='exportpdf4'><a href="javascript:void(0);">Descargar como PDF</a></li>
												</ul>
											</li>
										</ul>
									</div>
									<div class="body align-center grafica1-body" style="height:295px;padding:0 10px;">
										<div class="square-white2"></div>
										<div class="rectangle-header"></div>
										<div id="grafica4">asasas</div>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="info-box bg-indigo hover-expand-effect">
											<div class="icon">
												<i class="fas fa-users"></i>
											</div>
											<div class="content">
												<div>
													<div class="text">N° de Contactos</div>
													<div id="numeroContactos"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="info-box bg-pink hover-expand-effect">
											<div class="icon">
												<i class="fas fa-handshake"></i>
											</div>
											<div class="content">
												<div>
													<div class="text">Visita Total</div>
													<div id="visitaTotal"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="tblGraficas2" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-l-30 p-r-30" style="display:none;">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="card" style="height:550px;">
								<div class="header">
									<h2>Distribución de Secuencia por Categoría (trimestral)</h2>
									<ul class="header-dropdown m-r--5">
										<li class="dropdown">
											<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
											 aria-expanded="false">
												<i class="material-icons">more_vert</i>
											</a>
											<ul class="dropdown-menu pull-right">
												<li id='exportpng'><a href="#">Descargar como PNG</a></li>
												<li id='exportpdf'><a href="#">Descargar como PDF</a></li>
											</ul>
										</li>
									</ul>
								</div>
								<div class="body align-center grafica2-body" style="height:490px;">
									<div class="bg-light-blue lbl-chart1" style="display:none;" id="lblGrafica1">
										Contacto en: A = 3 Ciclos, B = 2 Ciclos, C = 1 Ciclo, D = Sin Contacto
									</div>
									<div class="rectangle-header2"></div>
									<div class="square-white"></div>
									<div id="grafica1">
									</div>
								</div>
							</div>
							<div class="card" id='containerFreCat'>
								<div class="body">
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-9 col-xs-8 margin-0">
											<span class="col-indigo font-bold">
												<i class="fas fa-user-md"></i>
												<span>Médicos: </span><span id="numMedFreCat"></span>
											</span>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 margin-0">
											<div class="align-right">
												<button id="showFreCat" class="btn btn-default btn-circle-sm-chart waves-effect waves-circle waves-float btn-indigo2 pull-right">
													<i class="material-icons col-indigo">keyboard_arrow_up</i>
												</button>
												<button id="hideFreCat" class="btn btn-default btn-circle-sm-chart waves-effect waves-circle waves-float btn-indigo2 pull-right" style="display:none;">
													<i class="material-icons col-indigo">keyboard_arrow_down</i>
												</button>
											</div>
										</div>
									</div>
									
									<div class="containerList">
										<table id="tblListFrecCategoria" class="tblListadoGraficas m-t-10">
											<thead id="headFrecCat">
												<tr class="align-center">
													<td style="width:40%;">Médico</td>
													<td style="width:30%;">Especialidad</td>
													<td style="width:15%;">Categoria</td>
													<td style="width:15%;">Frec</td>
												</tr>
											</thead>
											<tbody id="bodyTblFrecCat">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="card" style="height:550px;">
								<div class="header">
									<h2>Cobertura por Categoría</h2>
									<ul class="header-dropdown m-r--5">
										<li class="dropdown">
											<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
											 aria-expanded="false">
												<i class="material-icons">more_vert</i>
											</a>
											<ul class="dropdown-menu pull-right">
												<li id='exportpng2'><a href="#">Descargar como PNG</a></li>
												<li id='exportpdf2'><a href="#">Descargar como PDF</a></li>
											</ul>
										</li>
									</ul>
								</div>
								<div class="body align-center grafica2-body" style="height:490px;">
									<div class="square-white"></div>
									<div id="grafica2">
									</div>
								</div>
							</div>

							<div class="card" id='containerCobCat'>
								<div class="body">
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-9 col-xs-8 margin-0">
											<span class="col-indigo font-bold">
												<i class="fas fa-user-md"></i>
												<span>Médicos: </span><span id="numMedCobCat"></span>
											</span>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 margin-0">
											<div class="align-right">
												<button id="showCobCat" class="btn btn-default btn-circle-sm-chart waves-effect waves-circle waves-float btn-indigo2 pull-right">
													<i class="material-icons col-indigo">keyboard_arrow_up</i>
												</button>
												<button id="hideCobCat" class="btn btn-default btn-circle-sm-chart waves-effect waves-circle waves-float btn-indigo2 pull-right" style="display:none;">
													<i class="material-icons col-indigo">keyboard_arrow_down</i>
												</button>
											</div>
										</div>
									</div>
									
									<div class="containerList">
										<table id="tblListCobCat" class="tblListadoGraficas m-t-10">
											<thead id="headCobCat">
												<tr class="align-center">
													<td style="width:40%;">Médico</td>
													<td style="width:30%;">Especialidad</td>
													<td style="width:15%;">Categoria</td>
													<td style="width:15%;">Visita</td>
												</tr>
											</thead>
											<tbody id="bodyTblCobCat">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="tblGraficas3" class="col-lg-12 p-l-30 p-r-30" style="display:none;">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="card" style="height:550px;">
								<div class="header">
									<h2>Distribución de Fichero Médico</h2>
									<ul class="header-dropdown m-r--5">
										<li class="dropdown">
											<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
											 aria-expanded="false">
												<i class="material-icons">more_vert</i>
											</a>
											<ul class="dropdown-menu pull-right">
												<li id='exportpng5'><a href="#">Descargar como PNG</a></li>
												<li id='exportpdf5'><a href="#">Descargar como PDF</a></li>
											</ul>
										</li>
									</ul>
								</div>
								<div class="body align-center grafica3-body" style="height:490px;">
									<div class="square-white"></div>
									<div id="grafica5">
									</div>
								</div>
							</div>

							<div class="card" id='containerDisFichero'>
								<div class="body">
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-9 col-xs-8 margin-0">
											<span class="col-indigo font-bold">
												<i class="fas fa-user-md"></i>
												<span>Médicos: </span><span id="numMedDisFichero"></span>
											</span>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 margin-0">
											<div class="align-right">
												<button id="showDisFichero" class="btn btn-default btn-circle-sm-chart waves-effect waves-circle waves-float btn-indigo2 pull-right">
													<i class="material-icons col-indigo">keyboard_arrow_up</i>
												</button>
												<button id="hideDisFichero" class="btn btn-default btn-circle-sm-chart waves-effect waves-circle waves-float btn-indigo2 pull-right" style="display:none;">
													<i class="material-icons col-indigo">keyboard_arrow_down</i>
												</button>
											</div>
										</div>
									</div>
									
									<div class="containerList">
										<table id="tblListDisFichero" class="tblListadoGraficas m-t-10">
											<thead id="headDisFichero">
												<tr class="align-center">
													<td style="width:50%;">Médico</td>
													<td style="width:35%;">Especialidad</td>
													<td style="width:15%;">Categoria</td>
												</tr>
											</thead>
											<tbody id="bodyTblDisFichero">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="card" style="height:550px;">
								<div class="header">
									<h2>Cobertura de Visita vs Fichero Médico</h2>
									<ul class="header-dropdown m-r--5">
										<li class="dropdown">
											<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
											 aria-expanded="false">
												<i class="material-icons">more_vert</i>
											</a>
											<ul class="dropdown-menu pull-right">
												<li id='exportpng6'><a href="#">Descargar como PNG</a></li>
												<li id='exportpdf6'><a href="#">Descargar como PDF</a></li>
											</ul>
										</li>
									</ul>
								</div>
								<div class="body align-center grafica3-body" style="height:490px;">
									<div class="square-white"></div>
									<div id="grafica6">
									</div>
								</div>
							</div>

							<div class="card" id='containerCobFichero'>
								<div class="body">
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-9 col-xs-8 margin-0">
											<span class="col-indigo font-bold">
												<i class="fas fa-user-md"></i>
												<span>Médicos: </span><span id="numMedCobFichero"></span>
											</span>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 margin-0">
											<div class="align-right">
												<button id="showCobFichero" class="btn btn-default btn-circle-sm-chart waves-effect waves-circle waves-float btn-indigo2 pull-right">
													<i class="material-icons col-indigo">keyboard_arrow_up</i>
												</button>
												<button id="hideCobFichero" class="btn btn-default btn-circle-sm-chart waves-effect waves-circle waves-float btn-indigo2 pull-right" style="display:none;">
													<i class="material-icons col-indigo">keyboard_arrow_down</i>
												</button>
											</div>
										</div>
									</div>
									
									<div class="containerList">
										<table id="tblListCobFichero" class="tblListadoGraficas m-t-10">
											<thead id="headCobFichero">
												<tr class="align-center">
													<td style="width:50%;">Médico</td>
													<td style="width:35%;">Especialidad</td>
													<td style="width:15%;">Categoria</td>
												</tr>
											</thead>
											<tbody id="bodyTblCobFichero">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="tblGraficas4" class="col-lg-12 p-l-30 p-r-30" style="display:none;">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="card" style="height:550px;">
								<div class="header">
									<h2>Distribución de Fichero por Quintil (Rx)</h2>
									<ul class="header-dropdown m-r--5">
										<li class="dropdown">
											<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
											 aria-expanded="false">
												<i class="material-icons">more_vert</i>
											</a>
											<ul class="dropdown-menu pull-right">
												<li id='exportpng7'><a href="#">Descargar como PNG</a></li>
												<li id='exportpdf7'><a href="#">Descargar como PDF</a></li>
											</ul>
										</li>
									</ul>
								</div>
								<div class="body align-center grafica4-body" style="height:490px;">
									<div class="square-white"></div>
									<div id="grafica7">
									</div>
								</div>
							</div>

							<div class="card" id='containerDisQuintil'>
								<div class="body">
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-9 col-xs-8 margin-0">
											<span class="col-indigo font-bold">
												<i class="fas fa-user-md"></i>
												<span>Médicos: </span><span id="numMedDisQuintil"></span>
											</span>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 margin-0">
											<div class="align-right">
												<button id="showDisQuintil" class="btn btn-default btn-circle-sm-chart waves-effect waves-circle waves-float btn-indigo2 pull-right">
													<i class="material-icons col-indigo">keyboard_arrow_up</i>
												</button>
												<button id="hideDisQuintil" class="btn btn-default btn-circle-sm-chart waves-effect waves-circle waves-float btn-indigo2 pull-right" style="display:none;">
													<i class="material-icons col-indigo">keyboard_arrow_down</i>
												</button>
											</div>
										</div>
									</div>
									
									<div class="containerList">
										<table id="tblListDisQuintil" class="tblListadoGraficas m-t-10">
											<thead id="headDisQuintil">
												<tr class="align-center">
													<td style="width:50%;">Médico</td>
													<td style="width:35%;">Especialidad</td>
													<td style="width:15%;">Categoria</td>
												</tr>
											</thead>
											<tbody id="bodyTblDisQuintil">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="card" style="height:550px;">
								<div class="header">
									<h2>Cobertura de Visita por Quintil (Rx)</h2>
									<ul class="header-dropdown m-r--5">
										<li class="dropdown">
											<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
											 aria-expanded="false">
												<i class="material-icons">more_vert</i>
											</a>
											<ul class="dropdown-menu pull-right">
												<li id='exportpng8'><a href="#">Descargar como PNG</a></li>
												<li id='exportpdf8'><a href="#">Descargar como PDF</a></li>
											</ul>
										</li>
									</ul>
								</div>
								<div class="body align-center grafica4-body" style="height:490px;">
									<div class="square-white"></div>
									<div id="grafica8">
									</div>
								</div>
							</div>

							<div class="card" id='containerCobQuintil'>
								<div class="body">
									<div class="row">
										<div class="col-lg-10 col-md-10 col-sm-9 col-xs-8 margin-0 p-t-4">
											<span class="col-indigo font-bold">
												<i class="fas fa-user-md"></i>
												<span>Médicos: </span><span id="numMedCobQuintil"></span>
											</span>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 margin-0">
											<div class="align-right">
												<button id="showCobQuintil" class="btn btn-default btn-circle-sm-chart waves-effect waves-circle waves-float btn-indigo2 pull-right">
													<i class="material-icons col-indigo">keyboard_arrow_up</i>
												</button>
												<button id="hideCobQuintil" class="btn btn-default btn-circle-sm-chart waves-effect waves-circle waves-float btn-indigo2 pull-right" style="display:none;">
													<i class="material-icons col-indigo">keyboard_arrow_down</i>
												</button>
											</div>
										</div>
									</div>
									
									<div class="containerList">
										<table id="tblListCobQuintil" class="tblListadoGraficas m-t-15">
											<thead id="headCobQuintil">
												<tr class="align-center">
													<td style="width:50%;">Médico</td>
													<td style="width:35%;">Especialidad</td>
													<td style="width:15%;">Categoria</td>
												</tr>
											</thead>
											<tbody id="bodyTblCobQuintil">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="tblGraficas5" class="col-lg-12 p-l-30 p-r-30" style="display:none;">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="card" style="height:550px;">
								<div class="header">
									<h2>Cobertura por número de contactos</h2>
									<ul class="header-dropdown m-r--5">
										<li class="dropdown">
											<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
											 aria-expanded="false">
												<i class="material-icons">more_vert</i>
											</a>
											<ul class="dropdown-menu pull-right">
												<li id='exportpng9'><a href="#">Descargar como PNG</a></li>
												<li id='exportpdf9'><a href="#">Descargar como PDF</a></li>
											</ul>
										</li>
									</ul>
								</div>
								<div class="body align-center grafica5-body" style="height:490px;">
									<div class="square-white"></div>
									<div id="grafica9">
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="card" style="height:550px;">
								<div class="header">
									<h2>Cobertura por Fichero Médico</h2>
									<ul class="header-dropdown m-r--5">
										<li class="dropdown">
											<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
											 aria-expanded="false">
												<i class="material-icons">more_vert</i>
											</a>
											<ul class="dropdown-menu pull-right">
												<li id='exportpng10'><a href="#">Descargar como PNG</a></li>
												<li id='exportpdf10'><a href="#">Descargar como PDF</a></li>
											</ul>
										</li>
									</ul>
								</div>
								<div class="body align-center grafica5-body" style="height:490px;">
									<div class="square-white"></div>
									<div id="grafica10">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="pull-center menu-charts">
				<div class="display-flex align-center">
					<div id="btnDivGraficas1" class="bullet bullet_seleccionado">
						<i class="fas fa-tachometer-alt font-22"></i>
						<p>Inicio</p>
					</div>
					<div id="btnDivGraficas2" class="bullet m-l-5">
						<i class="fas fa-chart-bar font-22"></i>
						<p>Categoría</p>
					</div>
					<div id="btnDivGraficas3" class="bullet m-l-5">
						<i class="fas fa-chart-pie font-22"></i>
						<p>Especialidad</p>
					</div>
					<div id="btnDivGraficas4" class="bullet m-l-5">
						<i class="material-icons font-26" style="margin-top:-2px;">donut_small</i>
						<p class="m-t--5">Fichero Rx</p>
					</div>
					<div id="btnDivGraficas5" class="bullet m-l-5">
						<i class="fas fa-chart-line font-22"></i>
						<p>Cob x Ciclo</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>