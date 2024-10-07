<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		
		$idUser = $_POST['idUser'];
		$tabMsj = $_POST['tabMsj'];
		
		//$idUser = $_GET['idUser'];
		//$tabMsj = $_GET['tabMsj'];
		
		if($tabMsj == 1){///entrada
			$qMensajes = "select umd.USER_MAILING_DESTINATION_SNR as idMensaje, 
				u.USER_NR + ' - ' + u.LNAME + ' ' +u.MOTHERS_LNAME + ' ' + u.FNAME as remitente,
				cast(year(um.DATE) as varchar) + '-' + format(month(um.date), '00') + '-' + format(day(um.date), '00') fecha,
				um.time as hora,
				um.SUBJECT as asunto,
				um.MESSAGE as mensaje 
				from USER_MAILING um
				inner join USER_MAILING_DESTINATION umd on umd.USER_MAILING_SNR = um.USER_MAILING_SNR
				inner join users u on um.user_snr = u.user_snr
				where umd.USER_SNR = '".$idUser."'
				and umd.MESSAGE_READED = 0
				and umd.REC_STAT = 0 ";
		}else if($tabMsj == 2){//todos
			$qMensajes = "select umd.USER_MAILING_DESTINATION_SNR as idMensaje, 
				u.USER_NR + ' - ' + u.LNAME + ' ' +u.MOTHERS_LNAME + ' ' + u.FNAME as remitente,
				cast(year(um.DATE) as varchar) + '-' + format(month(um.date), '00') + '-' + format(day(um.date), '00') fecha,
				um.time as hora,
				um.SUBJECT as asunto,
				um.MESSAGE as mensaje 
				from USER_MAILING um
				inner join USER_MAILING_DESTINATION umd on umd.USER_MAILING_SNR = um.USER_MAILING_SNR
				inner join users u on um.user_snr = u.user_snr
				where umd.USER_SNR = '".$idUser."'
				and umd.REC_STAT = 0 ";
		}else if($tabMsj == 3){//enviados
			$qMensajes = "select umd.USER_MAILING_DESTINATION_SNR as idMensaje, 
				u.USER_NR + ' - ' + u.LNAME + ' ' +u.MOTHERS_LNAME + ' ' + u.FNAME as remitente,
				cast(year(um.DATE) as varchar) + '-' + format(month(um.date), '00') + '-' + format(day(um.date), '00') fecha,
				um.time as hora,
				um.SUBJECT as asunto,
				um.MESSAGE as mensaje 
				from USER_MAILING um
				inner join USER_MAILING_DESTINATION umd on umd.USER_MAILING_SNR = um.USER_MAILING_SNR
				inner join users u on umd.user_snr = u.user_snr
				where um.USER_SNR = '".$idUser."'
				and umd.REC_STAT = 0 ";
		}
		//echo $qMensajes;
		$rsMensajes = sqlsrv_query($conn, $qMensajes, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		$totalRegistros = sqlsrv_num_rows($rsMensajes);
		
		//echo "<script>";

		if($totalRegistros == 0){
			echo "<h2>Sin resultados que mostrar</h2>";
		}else{
			$tabla = '<table class="table table-striped table-hover margin-0" id="listamedicos">
					<thead>
					</thead>
					<tbody >';
			$idCont = 0;	
			while($mensaje = sqlsrv_fetch_array($rsMensajes)){
				$idMensaje = $mensaje['idMensaje'];
				$remitente = $mensaje['remitente'];
				$fecha = $mensaje['fecha'];
				$hora = $mensaje['hora'];
				$asunto = utf8_encode($mensaje['asunto']);
				$mensaje = utf8_encode($mensaje['mensaje']);
				
				$idCont++;
				
				$tabla .= '<tr id="trmen'.$idCont.'">
						<td>
							<div class="row">
								<div id="men'.$idCont.'" name="medicoLista" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pointer margin-0" onClick="muestraMensaje(\''.$idMensaje.'\',\''.$tabMsj.'\');">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-overflowA font-bold">
											'.$remitente.'
										</div>	
									</div>
									<div class="row">
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-overflowA margin-0">
											<div style="margin-top: 0px;">
												<div class="imageUser">
													<img style="border-radius: 50%;" src="images/user.png" alt="User" />
												</div>
											</div>
										</div>
										<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-overflowA margin-0">
											<div>
												'.$asunto.'
											</div>
											<div>
												'.$mensaje.'
											</div>
										</div>
										<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0">
											<button type="button" class="btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button" onClick="eliminarMensaje(\''.$idMensaje.'\',\''.$tabMsj.'\');">
												<i class="material-icons pointer" data-toggle="tooltip" data-placement="left" title="Eliminar">delete</i>
											</button>
										</div>
									</div>
								</div>
															
							</div>
						</td>
					</tr>';	
				
			}
			$tabla .= '</tbody>
				</table>';
				echo $tabla;
		}
		//echo "</script>";
	}
	//echo '<script>$("#trmed1").click();</script>';
?>

