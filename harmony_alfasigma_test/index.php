<!DOCTYPE >
<html>
    <head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<title>Smart Scale</title>
		<link rel="icon" type="image/x-icon" href="images/favicon.ico">

		<link type="text/css" rel="stylesheet" href="plugins/bootstrap/css/bootstrap.css">
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
		<link type="text/css" rel="stylesheet" href="css/style.css">
		<link type="text/css" rel="stylesheet" href="css/smart.css">
		<!-- Waves Effect Css -->
		<link href="plugins/node-waves/waves.css" rel="stylesheet" />

        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		
		<script>
			$(document).ready(function(){
				$("#entrar").click(function(){
					var user;
					var pass;
					user = $("#txtUser").val();
					pass = $("#txtPass").val();
					if(user == '' && pass == ''){
						//alert('Debe ingresar la contraseña!!!');
						document.getElementById('errorUser').innerHTML = 'Ingrese usuario y/o contraseña';
						$("#txtUser").addClass('invalid');
						$("#txtPass").addClass('invalid');
						return 0;
					}

					else if(user == ''){
						//alert('Debe ingresar el usuario!!!');
						document.getElementById('errorUser').innerHTML = 'Ingrese usuario';
						$("#txtPass").removeClass('invalid');
						$("#txtUser").addClass('invalid');
						return 0;
					}
					
					else if(pass == ''){
						//alert('Debe ingresar la contraseña!!!');
						document.getElementById('errorUser').innerHTML = 'Ingrese contraseña';
						$("#txtUser").removeClass('invalid');
						$("#txtPass").addClass('invalid');
						return 0;
					}
					else{
						$("#txtUser").removeClass('invalid');
						$("#txtPass").removeClass('invalid');
					}
					
					$("#respuesta").load("ajax/revisaUsuario.php",{usuario:user,pass:pass});
				});
				
				$('#txtPass').bind('keyup', function (e) {
					var key = e.keyCode || e.which;
					if (key === 13) {
						$("#entrar").click();
					};
				});
				
			});
		</script>
	
    </head>
    <body class="fondo">
		<h1> Hola, bienvenido a harmony Alfa</h1>
		<div class="justify-content-end">
			<div class="col-md-offset-4 col-lg-5 col-md-8 col-sm-12 col-xs-12">
				<div class="fdb-box fdb-touch">
					<div class="row m-t-4">
						<div class="col-2 col-lg-5">
							<img src="images/logo.png" class="logo-index">
						</div>
					</div>
						<div class="row m-t-20">
							<div class="col">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon bg-white"><i class="glyphicon glyphicon-user"></i></span>
										<input id="txtUser" type="text" class="form-control font-17" placeholder="Usuario" autofocus>
									</div>
								</div>
							</div>
						</div>

						<div class="row m-t-20">
							<div class="col">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon bg-white"><i class="glyphicon glyphicon-lock"></i></span>
										<input id="txtPass" type="password" class="form-control font-17" placeholder="Contraseña">
									</div>
								</div>
								<p class="text-right m-t-5"><a href="#">Olvidé mi contraseña</a></p>
							</div>
						</div>
						<div class="row">
							<div id='errorUser' class="col-red"></div>
							<div id="respuesta" class="col-red"></div>
						</div>
						<div class="row m-t-5">
							<div class="col">
								<button id="entrar" class="btn bg-light-blue waves-effect btn-light-blue" type="button">Ingresar</button>
							</div>
						</div>
				</div>
			</div>
		</div>
    </body>
</html>

<script src="plugins/bootstrap/js/bootstrap.js"></script>
<!-- Waves Effect Plugin Js -->
<script src="plugins/node-waves/waves.js"></script>
<script src="js/admin.js"></script>