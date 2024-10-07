<?php
include "../conexion.php";

			$combo=$_POST['combo'];
				$queryRutas = "SELECT  EDO.STATE_SNR,EDO.NAME
								FROM PERSON P 
								INNER JOIN PERS_SREP_WORK PSW ON P.PERS_SNR=PSW.PERS_SNR AND PSW.REC_STAT=0
								INNER JOIN INST I ON I.INST_SNR=PSW.INST_SNR AND I.REC_STAT=0
								INNER JOIN USERS U ON U.USER_SNR=PSW.USER_SNR AND U.REC_STAT=0 AND U.STATUS=1 AND U.USER_TYPE=4
								INNER JOIN CITY CY ON CY.CITY_SNR=I.CITY_SNR AND CY.REC_STAT=0
								INNER JOIN DISTRICT DTO ON CY.DISTR_SNR=DTO.DISTR_SNR AND DTO.REC_STAT=0
								INNER JOIN STATE EDO ON EDO.STATE_SNR=DTO.STATE_SNR AND EDO.REC_STAT=0 
								inner join compline LINEA on LINEA.CLINE_SNR=U.CLINE_SNR 
								WHERE
								P.REC_STAT=0
								AND EDO.NAME<>''
								AND LINEA.CLINE_SNR='".$combo."'
								GROUP BY  EDO.NAME,EDO.STATE_SNR
								ORDER BY EDO.NAME";
			$rsRutas = sqlsrv_query($conn, $queryRutas);

			
			echo "<script>";
			echo "$('#sltEstado').empty();";
			echo "var data = {
								id: '0' ,
								text: '--Seleccione--'
									};
									
									var newOption = new Option(data.text, data.id, false, false);
									$('#sltEstado').append(newOption);
										";
										
			echo "var data = {
								id: '5' ,
								text: '--Todos los Estados--'
									};
									
									var newOption = new Option(data.text, data.id, false, false);
									$('#sltEstado').append(newOption);
										";
				while($regRutas = sqlsrv_fetch_array($rsRutas)){
					
					$id=utf8_encode($regRutas["STATE_SNR"]);
					$name=utf8_encode($regRutas["NAME"]);
					
					
					
					
					
					echo "var data = {
								id: '".$id."' ,
								text: '".$name."'
									};
									
									var newOption = new Option(data.text, data.id, false, false);
									$('#sltEstado').append(newOption);
										";
				
				
										}
										
									

			echo "</script>";
										
					
?>