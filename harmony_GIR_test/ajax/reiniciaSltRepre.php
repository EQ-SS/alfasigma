<?php
include "../conexion.php";


		$idUsuario=$_POST['idUsuario'];
		$tipoUsuario=$_POST['tipoUsuario'];
		$combo=$_POST['combo'];
			$queryRutas = "select users.user_snr,compline.name,users.user_nr,users.lname,users.fname from users
													inner join compline on COMPLINE.CLINE_SNR=users.CLINE_SNR
													where user_type in (4) AND COMPLINE.MAP=1";
													if($tipoUsuario==5){
													$queryRutas =$queryRutas ."and user_snr in (select kloc_snr from KLOC_REG where reg_snr='".$idUsuario."')";	
													}
													if($combo != '0'){
														$queryRutas =$queryRutas ." AND COMPLINE.CLINE_SNR='".$combo."' ";
													}
													
												$queryRutas =$queryRutas ."	order by Compline.SORT_NUM,user_nr";
													//echo $queryRutas;
			$rsRutas = sqlsrv_query($conn, $queryRutas);
					
			echo "<script>";
			echo "$('#sltRepreRadar').empty();";
			echo "var data = {
								id: '0' ,
								text: '--Seleccione--'
									};
									
									var newOption = new Option(data.text, data.id, false, false);
									$('#sltRepreRadar').append(newOption);
										";
			echo "var data = {
								id: '00000000-0000-0000-0000-000000000000' ,
								text: 'Todas las rutas'
									};
									
									var newOption = new Option(data.text, data.id, false, false);
									$('#sltRepreRadar').append(newOption);
										";
										
				while($regRutas = sqlsrv_fetch_array($rsRutas)){
					
					$id=utf8_encode($regRutas["user_nr"]);
					$lname=utf8_encode($regRutas["lname"]);
					$fname=utf8_encode($regRutas["fname"]);
					
					$repre=$id."-".$lname." ".$fname;
					
					
					echo "var data = {
								id: '".$regRutas["user_snr"]."' ,
								text: '".$repre."'
									};
									
									var newOption = new Option(data.text, data.id, false, false);
									$('#sltRepreRadar').append(newOption);
										";
				
				
										}
										
									

			echo "</script>";
				echo "<script>$('#btnHistorialVisitasLocalizadorRepres').attr('disabled', true);
							$('#btnTrackingLocalizadorRepres').attr('disabled', true);
					</script>";						
					
?>