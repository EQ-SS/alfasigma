<script>
	var numeros = "0123456789";
	var letras = "abcdefghyjklmnñopqrstuvwxyz";
	var letras_mayusculas = "ABCDEFGHYJKLMNÑOPQRSTUVWXYZ";

	function tiene_numeros(texto) {
		for (i = 0; i < texto.length; i++) {
			if (numeros.indexOf(texto.charAt(i), 0) != -1) {
				return 1;
			}
		}
		return 0;
	}

	function tiene_letras(texto) {
		texto = texto.toLowerCase();
		for (i = 0; i < texto.length; i++) {
			if (letras.indexOf(texto.charAt(i), 0) != -1) {
				return 1;
			}
		}
		return 0;
	}

	function tiene_minusculas(texto) {
		for (i = 0; i < texto.length; i++) {
			if (letras.indexOf(texto.charAt(i), 0) != -1) {
				return 1;
			}
		}
		return 0;
	}

	function tiene_mayusculas(texto) {
		for (i = 0; i < texto.length; i++) {
			if (letras_mayusculas.indexOf(texto.charAt(i), 0) != -1) {
				return 1;
			}
		}
		return 0;
	}

	function seguridad_clave(clave) {
		var seguridad = 0;
		if (clave.length != 0) {
			if (tiene_numeros(clave) && tiene_letras(clave)) {
				seguridad += 30;
			}
			if (tiene_minusculas(clave) && tiene_mayusculas(clave)) {
				seguridad += 30;
			}
			if (clave.length >= 4 && clave.length <= 5) {
				seguridad += 10;
			} else {
				if (clave.length >= 6 && clave.length <= 8) {
					seguridad += 30;
				} else {
					if (clave.length > 8) {
						seguridad += 40;
					}
				}
			}
		}
		return seguridad
	}

	function muestra_seguridad_clave(clave, formulario) {
		var seguridad = seguridad_clave(clave);
		if (seguridad < 40) {
			formulario.seguridad.value = "seguridad: Baja";
			formulario.seguridad.style.color = "red";
		} else if (seguridad > 39 && seguridad < 90) {
			formulario.seguridad.value = "seguridad: Media";
			formulario.seguridad.style.color = "#ffcc00";
		} else {
			formulario.seguridad.value = "seguridad: Alta";
			formulario.seguridad.style.color = "green";
		}
	}
</script>

<?php 
	//$rsUsuario = sqlsrv_fetch_array(sqlsrv_query($conn, "select u.LNAME + ' ' + u.FNAME as nombre , k.NAME as usuario from users u, KOMMLOC k where u.user_snr = '".$idUsuario."' and u.USER_SNR = k.KLOC_SNR "));
	$rsUsuario = sqlsrv_fetch_array(sqlsrv_query($conn, "select LNAME + ' ' + FNAME + ' ' + MOTHERS_LNAME as nombre , USER_NAME as usuario from users where user_snr = '".$idUsuario."'"));
	//echo "select LNAME + ' ' + FNAME + ' ' + MOTHERS_LNAME as nombre , USER_NAME as usuario from users where user_snr = '".$idUsuario."'<br>";

	$queryTipoUsuario = "select * from users where rec_stat = 0 and user_snr = '".$idUsuario."'";
	$rsTipoUsuario = sqlsrv_query($conn, $queryTipoUsuario, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	if(sqlsrv_num_rows($rsTipoUsuario) > 0){
		while($row = sqlsrv_fetch_array($rsTipoUsuario)){
			$tipoUsuario = $row['USER_TYPE'];
			$rutaEtiqueta = $row['LNAME']." ".$row['FNAME'];
		}

		if($tipoUsuario == 2){
			$rutaEtiqueta = substr($rutaEtiqueta, 8); 
			$adminUser = 'Admin';
		}else{
			$rutaEtiqueta = substr($rutaEtiqueta, 7); 
			$adminUser = $rsUsuario['usuario'];
		}
	}
?>

<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2 class="display-flex">
				<i class="material-icons font-23">settings</i>
				<span class="p-t-4 m-l-5">CONFIGURACIÓN</span>
			</h2>
		</div>
		<div class="row pull-center">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card" style="border-top:2px #F44336 solid;">

					<div class="body">
						<small><a id="goInicio" style="cursor:pointer;">Ir a inicio</a></small>
						<div class="row m-t--5">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h3>Información personal</h3>
								<div class="form-group margin-0">
									<label class="m-r-5 p-t-5 lblConfig">Nombre Completo: </label>
									<div class="input-group margin-0">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input disabled id="nameUser" type="text" class="form-control no-style-disabled"
											placeholder="<?= $rutaEtiqueta ?>" />
									</div>
									<!--<input type="text" id="txtNombreFiltro" />-->
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group margin-0">
									<label class="m-r-5 p-t-5 lblConfig">Usuario: </label>
									<div class="input-group margin-0">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input disabled id="user" type="text" class="form-control no-style-disabled"
											placeholder="<?= $adminUser ?>" />
									</div>
									<!--<input type="text" id="txtNombreFiltro" />-->
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h3>Seguridad</h3>
								<div class="row" id="muestraCambiarPassword">
									<div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 margin-0 p-t-5">
										Contraseña
									</div>
									<div class="col-lg-9 col-md-7 col-sm-7 col-xs-4 p-t-7 margin-0">
										***********
									</div>
									<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 align-right margin-0">
										<button id="editarPass" class="btn btn-default waves-effect btn-indigo2">
											<i class="material-icons">edit</i> <span> Editar</span>
										</button>
										<button id="editarPassxs" class="btn btn-default waves-effect btn-indigo2"
											style="display:none;">
											<i class="material-icons">edit</i> <span> Editar</span>
										</button>
									</div>
								</div>

								<div id="cambiarPassword" style="display:none;">
									<form>
										<div>
											<div class="row pull-center">
												<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12 margin-0">
													<div class="form-group">
														<label class="m-r-5 p-t-5 lblConfig">Actual contraseña: </label>
														<div class="input-group">
															<span class="input-group-addon"><i
																	class="glyphicon glyphicon-lock"></i></span>
															<input id="txtActualClave" type="password"
																class="form-control" name="clave" />
														</div>
													</div>
												</div>
											</div>
											<div class="row pull-center">
												<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12 margin-0">
													<div class="form-group margin-0">
														<label class="m-r-5 p-t-5 lblConfig">Nueva Contraseña: </label>
														<div class="input-group margin-0">
															<span class="input-group-addon"><i
																	class="glyphicon glyphicon-lock"></i></span>
															<input id="txtClave" type="password" class="form-control"
																name="clave"
																onkeyup="muestra_seguridad_clave(this.value, this.form)" />
														</div>
													</div>
												</div>
											</div>
											<div class="row pull-center">
												<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12 m-t--25 margin-0">
													<input id="seguridad" class="seguridadPass" name="seguridad" size="15" type="text"
														value="" onfocus="blur()" />
												</div>
											</div>
											<div class="row pull-center">
												<div class="col-lg-4 col-md-5 col-sm-9 col-xs-12 m-t-5">
													<div class="form-group margin-0">
														<label class="m-r-5 p-t-5 lblConfig">Repetir Nueva Contraseña:
														</label>
														<div class="input-group margin-0">
															<span class="input-group-addon"><i
																	class="glyphicon glyphicon-lock"></i></span>
															<input class="form-control" type="password" size="15"
																name="Repetirclave" id="txtRepetirClave" />
														</div>
														<!--<input type="text" id="txtNombreFiltro" />-->
													</div>
												</div>
											</div>
										</div>
										<div class="align-center">
											<button id="btnCancelarClave" type="button"
												class="m-t-5 btn btn-default waves-effect btn-indigo2">
												Cancelar
											</button>
											<button id="btnGuardarClave" type="button"
												class="m-t-5 m-l-5 btn bg-indigo waves-effect btn-indigo">
												Guardar cambios
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>