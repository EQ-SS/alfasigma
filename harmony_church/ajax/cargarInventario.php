<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		if(isset($_POST['pestana']) && $_POST['pestana'] != ''){
			$pestana = $_POST['pestana'];
		}else{
			$pestana = '';
		}
		
		if(isset($_POST['producto']) && $_POST['producto'] != '' && $_POST['producto'] != '00000000-0000-0000-0000-000000000000' ){
			$producto = $_POST['producto'];
		}else{
			$producto = '';
		}
		
		if(isset($_POST['repre']) && $_POST['repre'] != ''){
			if(substr($_POST['repre'], -1) == ','){
				$repre = str_replace(",", "','", substr($_POST['repre'], 0, -1));
			}else{
				$repre = str_replace(",", "','", $_POST['repre']);
			}
		}else{
			$repre = '';
		}
		
		if(isset($_POST['ids']) && $_POST['ids'] != ''){
			$ids = $_POST['ids'];
		}else{
			$ids = '';
		}
		
		//echo $repre;
		if($repre != ''){
			$idUsuario = $repre;
		}else if($ids != ''){
			$idUsuario = $ids;
		}else{
			$idUsuario = $_POST['idUsuario'];
		}
		
		if(isset($_POST['existencia']) && $_POST['existencia'] != ''){
			$existencia = $_POST['existencia'];
		}else{
			$existencia = 0;
		}

		if(isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] != ''){
			$tipoUsuario = $_POST['tipoUsuario'];
		}else{
			$tipoUsuario = '';
		}
		
		//echo $existencia."<br>";
		
		if($pestana == '' || $pestana == 'aprobacion'){
			$pendiente = $_POST['pendiente'];
			
			$query = "SELECT SPU.STPRODF_USER_SNR, 
			SPU.ENTRYDATE FECHA,
			PROD.NAME AS PRODUCTO,
			PF.NAME PRES,LOTE.NAME AS LOTE,
			SPU.QUANTITY AS CANTIDAD 
			FROM STOCK_PRODFORM_USER SPU 
			INNER JOIN PRODFORMBATCH LOTE ON LOTE.PRODFBATCH_SNR=SPU.PRODFBATCH_SNR 
			INNER JOIN PRODFORM PF ON LOTE.PRODFORM_SNR=PF.PRODFORM_SNR 
			INNER JOIN PRODUCT PROD ON PF.PROD_SNR=PROD.PROD_SNR 
			WHERE SPU.REC_STAT=0  
			AND SPU.ACCEPTED=".$pendiente." 
			AND SPU.USER_SNR in ('".$idUsuario."') ";
			
			if($producto != ''){
				$query .= " and PROD.PROD_SNR = '".$producto."' ";
			}	
			$query .= " ORDER BY FECHA DESC,PRODUCTO,PRES,LOTE ";
			
			//echo "::::".$query;
			
			$rs = sqlsrv_query($conn, $query);
		
			echo "<script>
				$('#tblInventario thead').empty();
				$('#tblInventario tbody').empty();";

				if($tipoUsuario == 4){
					if($pendiente == 0){
						//echo "$('#tblInventario tbody').append('<tr class=\"align-center pointer\" onClick=\"ajuste(\'".$inv['STPRODF_USER_SNR']."\',\'".$fecha."\',\'".$inv['PRES']."\');\"><td style=\"width:15%;\" class=\"align-left\">".$fecha."</td><td style=\"width:25%;\">".$inv['PRODUCTO']."</td><td style=\"width:30%;\">".$inv['PRES']."</td><td style=\"width:20%;\">".$inv['LOTE']."</td><td style=\"width:10%;\">".$inv['CANTIDAD']."</td></tr>');";
						echo "$('#tblInventario thead').append('<tr class=\"align-center\"><td style=\"width:15%;\">Aceptar/Rechazar</td><td style=\"width:10%;\">Fecha</td><td style=\"width:15%;\">Producto</td><td style=\"width:30%;\">Presentación</td><td style=\"width:20%;\">Lote</td><td style=\"width:10%;\">Cantidad</td></tr>');";
					}else{
						echo "$('#tblInventario thead').append('<tr class=\"align-center\"><td style=\"width:15%;\">Fecha</td><td style=\"width:25%;\">Producto</td><td style=\"width:30%;\">Presentación</td><td style=\"width:20%;\">Lote</td><td style=\"width:10%;\">Cantidad</td></tr>');";
					}
				}else{
					echo "$('#tblInventario thead').append('<tr class=\"align-center\"><td style=\"width:15%;\">Fecha</td><td style=\"width:25%;\">Producto</td><td style=\"width:30%;\">Presentación</td><td style=\"width:20%;\">Lote</td><td style=\"width:10%;\">Cantidad</td></tr>');";
				}

			while($inv = sqlsrv_fetch_array($rs)){
				foreach ($inv['FECHA'] as $key => $val) {
					if(strtolower($key) == 'date'){
						$fecha = substr($val, 0, 10);
					}
				}
				
				if($tipoUsuario == 4){
					if($pendiente == 0){
						//echo "$('#tblInventario tbody').append('<tr class=\"align-center pointer\" onClick=\"ajuste(\'".$inv['STPRODF_USER_SNR']."\',\'".$fecha."\',\'".$inv['PRES']."\');\"><td style=\"width:15%;\" class=\"align-left\">".$fecha."</td><td style=\"width:25%;\">".$inv['PRODUCTO']."</td><td style=\"width:30%;\">".$inv['PRES']."</td><td style=\"width:20%;\">".$inv['LOTE']."</td><td style=\"width:10%;\">".$inv['CANTIDAD']."</td></tr>');";
						echo "$('#tblInventario tbody').append('<tr class=\"align-center\"><td style=\"width:15%;\"><div class=\"display-inline\"><button type=\"button\" id=\"".$inv['STPRODF_USER_SNR']."Acep\" onClick=\"ajusteAceptar(\'".$inv['STPRODF_USER_SNR']."\',\'".$fecha."\',\'".$inv['PRES']."\');\" class=\"btn waves-effect btn-inv-head btn-inv-l\"><i class=\"far fa-thumbs-up font-13 top-0\"></i><span class=\"top-0\">Aceptar</span></button><button type=\"button\" id=\"".$inv['STPRODF_USER_SNR']."Rech\" onClick=\"ajusteRechazar(\'".$inv['STPRODF_USER_SNR']."\',\'".$fecha."\',\'".$inv['PRES']."\');\" class=\"btn waves-effect btn-inv-head btn-inv-r\"><i class=\"far fa-thumbs-down font-13 top-0\"></i><span class=\"top-0\">Rechazar</span></button></div></td><td style=\"width:10%;\">".$fecha."</td><td style=\"width:15%;\">".$inv['PRODUCTO']."</td><td style=\"width:30%;\">".$inv['PRES']."</td><td style=\"width:20%;\">".$inv['LOTE']."</td><td style=\"width:10%;\"><span>".$inv['CANTIDAD']."</span><button title=\'Ajustar\' type=\"button\" id=\"".$inv['STPRODF_USER_SNR']."Ajus\" onClick=\"ajusteInv(\'".$inv['STPRODF_USER_SNR']."\',\'".$fecha."\',\'".$inv['PRES']."\');\" class=\"btn btn-default btn-circle-sm waves-effect waves-circle waves-float btn-indigo2 pull-right\"><i class=\"material-icons col-indigo\">edit</i></button></td></tr>');";
					}else{
						echo "$('#tblInventario tbody').append('<tr class=\"align-center\"><td style=\"width:15%;\">".$fecha."</td><td style=\"width:25%;\">".$inv['PRODUCTO']."</td><td style=\"width:30%;\">".$inv['PRES']."</td><td style=\"width:20%;\">".$inv['LOTE']."</td><td style=\"width:10%;\">".$inv['CANTIDAD']."</td></tr>');";
					}
				}else{
					echo "$('#tblInventario tbody').append('<tr class=\"align-center\"><td style=\"width:15%;\">".$fecha."</td><td style=\"width:25%;\">".$inv['PRODUCTO']."</td><td style=\"width:30%;\">".$inv['PRES']."</td><td style=\"width:20%;\">".$inv['LOTE']."</td><td style=\"width:10%;\">".$inv['CANTIDAD']."</td></tr>');";
				}
			}
			echo "</script>";
		}else{
			$queryInventario = "select * from (
				SELECT U.LNAME,
				LOTE.PRODFBATCH_SNR AS ID_LOTE,
				PROD.NAME AS PRODUCTO,
				PF.NAME PRES,
				LOTE.NAME AS LOTE,
				(select sum(a.quantity) from stock_prodform_user a
				where a.rec_stat = 0 and a.user_snr=U.user_snr
				and lote.prodfbatch_snr = a.prodfbatch_snr
				and a.table_nr=374 and a.accepted=1) as ENTRADA,
 
				isnull((select sum(quantity) from visitpers_prodbatch vp, visitpers v
				where vp.vispers_snr=v.vispers_snr
				and v.rec_stat=0 and vp.rec_stat=0 and v.user_snr=U.user_snr
				and vp.prodfbatch_snr=lote.prodfbatch_snr ),0) as SALIDA,
 
				isnull((select sum(quantity) from visitinst_prodbatch vprod, visitinst v
				where vprod.visinst_snr=v.visinst_snr
				and v.rec_stat=0 and vprod.rec_stat=0 and v.user_snr=U.user_snr
				and vprod.prodfbatch_snr=lote.prodfbatch_snr),0) as SALIDA_INST
 
				FROM STOCK_PRODFORM_USER SPU
				INNER JOIN PRODFORMBATCH LOTE ON LOTE.PRODFBATCH_SNR=SPU.PRODFBATCH_SNR AND LOTE.REC_STAT=0
				INNER JOIN PRODFORM PF ON PF.PRODFORM_SNR=LOTE.PRODFORM_SNR AND PF.REC_STAT=0
				INNER JOIN PRODUCT PROD ON PROD.PROD_SNR=PF.PROD_SNR AND PROD.REC_STAT=0
				INNER JOIN USERS U ON U.USER_SNR=SPU.USER_SNR
 
				where SPU.REC_STAT=0
				and SPU.TABLE_NR=374
				and SPU.ACCEPTED=1
				and U.user_snr in ('".$idUsuario."')
				and LOTE.expiration_date >= GETDATE() ";
				
				if($producto != ''){
					$queryInventario .= " and PROD.prod_snr = '".$producto."' ";
				}
					
				$queryInventario .= "GROUP BY LOTE.PRODFBATCH_SNR,PROD.NAME,PF.NAME,LOTE.NAME,U.USER_SNR,U.LNAME
				 
				) a WHERE a.ENTRADA>0 ";
				
				if($existencia == 1){
					$queryInventario .= " and a.ENTRADA - SALIDA - SALIDA_INST > 0 ";
				}
				 
				$queryInventario .= " ORDER BY a.LNAME,a.PRODUCTO,a.PRES,a.LOTE ";

			$rsInv = sqlsrv_query($conn, $queryInventario);
			echo "<script>

				$('#tblInventarioPrincipal tbody').empty();
				$('#tblEntradaInv tbody').empty();
				$('#tblSalidaInv tbody').empty();";
			while($regInv = sqlsrv_fetch_array($rsInv)){
				$existencia = $regInv['ENTRADA'] - ($regInv['SALIDA'] + $regInv['SALIDA_INST']);
				$salidasTotal = $regInv['SALIDA'] + $regInv['SALIDA_INST'];
				//echo "$('#tblInventarioPrincipal tbody').append('<tr onClick=\"traeEntradasSalidas(\'".$regInv['ID_LOTE']."\');\"><td width=\"400px\">".$regInv['PRODUCTO']."</td><td width=\"400px\">".$regInv['PRES']."</td><td width=\"200px\">".$regInv['LOTE']."</td><td width=\"100px\" align=\"right\">".$regInv['ENTRADA']."</td><td width=\"100px\" align=\"right\">".$regInv['SALIDA']."</td><td width=\"100px\" align=\"right\">".$existencia."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>');";
				echo "$('#tblInventarioPrincipal tbody').append('<tr class=\"align-center\" onClick=\"traeEntradasSalidas(\'".$regInv['ID_LOTE']."\');\"><td style=\"width:18%;\">".$regInv['PRODUCTO']."</td><td style=\"width:28%;\">".$regInv['PRES']."</td><td style=\"width:18%;\">".$regInv['LOTE']."</td><td style=\"width:12%;\">".$regInv['ENTRADA']."</td><td style=\"width:12%;\">".$salidasTotal."</td><td style=\"width:12%;\">".$existencia."</td></tr>');";
			}
			echo "</script>";
			
		}
	}
?>