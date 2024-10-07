<?php
include "../conexion.php";

$combo=$_POST['combo'];
		$queryRutas = "select * from users users inner join compline linea on linea.CLINE_SNR=users.CLINE_SNR  where linea.CLINE_SNR='".$combo."' order by lname";
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
					
					$id=utf8_encode($regRutas["USER_NR"]);
					$lname=utf8_encode($regRutas["LNAME"]);
					$mothers_lname=utf8_encode($regRutas["MOTHERS_LNAME"]);
					$fname=utf8_encode($regRutas["FNAME"]);
					
					
					$repre=$id."-".$lname." ".$mothers_lname." ".$fname;
					
					
					echo "var data = {
								id: '".$regRutas["USER_SNR"]."' ,
								text: '".$repre."'
									};
									
									var newOption = new Option(data.text, data.id, false, false);
									$('#sltRepreRadar').append(newOption);
										";
				
				
										}
										
									

			echo "</script>";
										
					
?>