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

		if(isset($_POST['expirado']) && $_POST['expirado'] != ''){
			$expirado = $_POST['expirado'];
		}else{
			$expirado = 0;
		}
		
		//echo $expirado."<br>";
		
		if($pestana == '' || $pestana == 'aprobacion'){
			$idUsuario = $_POST['idUsuario'];
			$pendiente = $_POST['pendiente'];
			
			$query = "SELECT TOP 2000 SPU.STPRODF_USER_SNR, 
			SPU.ENTRYDATE FECHA,
			PROD.NAME AS PRODUCTO,
			PF.NAME PRES,LOTE.NAME AS LOTE,
			SPU.QUANTITY AS CANTIDAD,
			LOTE.NAME_CUSTOMER  
			FROM STOCK_PRODFORM_USER SPU 
			INNER JOIN PRODFORMBATCH LOTE ON LOTE.PRODFBATCH_SNR=SPU.PRODFBATCH_SNR 
			INNER JOIN PRODFORM PF ON LOTE.PRODFORM_SNR=PF.PRODFORM_SNR 
			INNER JOIN PRODUCT PROD ON PF.PROD_SNR=PROD.PROD_SNR 
			WHERE SPU.REC_STAT=0  
			AND SPU.ACCEPTED=".$pendiente." ";
			if($repre !=""){
				$query .=" AND SPU.USER_SNR in ('".$repre."')";
			}else{
				$query .=" AND SPU.USER_SNR in ('".$ids."')";
			}
			
			if($producto != ''){
				$query .= " and PROD.PROD_SNR = '".$producto."' ";
			}	
			$query .= " ORDER BY FECHA DESC,PRODUCTO,PRES,LOTE ";
			
			//echo $query;
			/*
			echo "<br>";
			echo "tipoUsuario: ".$tipoUsuario."<br>";
			echo "pendiente: ".$pendiente;*/
			
			$rs = sqlsrv_query($conn, $query);
		
			echo "<script>
				$('#tblInventario thead').empty();
				$('#tblInventario tbody').empty();";

				$cabecera = '<tr class="align-center">';

				if($tipoUsuario == 4 || $tipoUsuario == 5){
					if($pendiente == 0){
						$cabecera .= '<td style="width:15%;">Aceptar/Rechazar</td>';
						$cabecera .= '<td style="width:10%;">Fecha</td>';
						$cabecera .= '<td style="width:15%;">Producto</td>';
					}else{
						$cabecera .= '<td style="width:15%;">Fecha</td>';
						$cabecera .= '<td style="width:25%;">Producto</td>';
					}
				}else{
					$cabecera .= '<td style="width:15%;">Fecha</td>';
					$cabecera .= '<td style="width:25%;">Producto</td>';
				}

				$cabecera .= '<td style="width:30%;">Presentación</td>';
				$cabecera .= '<td style="width:20%;">Lote</td>';
				$cabecera .= '<td style="width:20%;">Lote almacen</td>';
				$cabecera .= '<td style="width:10%;">Cantidad</td>';
				$cabecera .= '</tr>';

				echo "$('#tblInventario thead').append('".$cabecera."');";

			while($inv = sqlsrv_fetch_array($rs)){
				foreach ($inv['FECHA'] as $key => $val) {
					if(strtolower($key) == 'date'){
						$fecha = substr($val, 0, 10);
					}
				}

				$idStock = $inv['STPRODF_USER_SNR'];
				$pres = $inv['PRES'];
				$producto = $inv['PRODUCTO'];
				$lote = $inv['LOTE'];
				$loteAlmacen = $inv['NAME_CUSTOMER'];
				$cantidad = $inv['CANTIDAD'];

				$renglon = '<tr class="align-center">';
				
				if($tipoUsuario == 4 || $tipoUsuario == 5){
					if($pendiente == 0){
						$renglon .= '<td style="width:15%;">';
						$renglon .= '<div class="display-inline">';
						$renglon .= '<button type="button" id="'.$idStock.'Acep" onClick="ajusteAceptar(\\\''.$idStock.'\\\',\\\''.$fecha.'\\\',\\\''.$pres.'\\\');" class="btn waves-effect btn-inv-head btn-inv-l">';
						$renglon .= '<i class="far fa-thumbs-up font-13 top-0"></i>';
						$renglon .= '<span class="top-0">Aceptar</span>';
						$renglon .= '</button>';
						$renglon .= '<button type="button" id="'.$idStock.'Rech" onClick="ajusteRechazar(\\\''.$idStock.'\\\',\\\''.$fecha.'\\\',\\\''.$pres.'\\\');" class="btn waves-effect btn-inv-head btn-inv-r">';
						$renglon .= '<i class="far fa-thumbs-down font-13 top-0"></i>';
						$renglon .= '<span class="top-0">Rechazar</span>';
						$renglon .= '</button>';
						$renglon .= '</div>';
						$renglon .= '</td>';
						$renglon .= '<td style="width:10%;">'.$fecha.'</td>';
						$renglon .= '<td style="width:15%;">'.$producto.'</td>';
					}else{
						$renglon .= '<td style="width:15%;">'.$fecha.'</td>';
						$renglon .= '<td style="width:25%;">'.$producto.'</td>';
					}
				}else{
					$renglon .= '<td style="width:15%;">'.$fecha.'</td>';
					$renglon .= '<td style="width:25%;">'.$producto.'</td>';
				}
				$renglon .= '<td style="width:30%;">'.$pres.'</td>';
				$renglon .= '<td style="width:20%;">'.$lote.'</td>';
				$renglon .= '<td style="width:20%;">'.$loteAlmacen.'</td>';
				$renglon .= '<td style="width:10%;">';
				if($tipoUsuario == 4 || $tipoUsuario == 5){
					if($pendiente == 0){
						$renglon .= '<span>'.$cantidad.'</span><button title="Ajustar" type="button" id="'.$idStock.'Ajus" onClick="ajusteInv(\\\''.$idStock.'\\\',\\\''.$fecha.'\\\',\\\''.$pres.'\\\');" class="btn btn-default btn-circle-sm waves-effect waves-circle waves-float btn-indigo2 pull-right"><i class="material-icons col-indigo">edit</i></button>';
					}else{
						$renglon .= $cantidad;
					}
				}else{
					$renglon .= $cantidad;
				}
				$renglon .= '</td>';
				$renglon .= '</tr>';
				echo "$('#tblInventario tbody').append('".$renglon."');";
			}
			echo "</script>";
		}else{
			//echo $repre;
			if($repre != ''){
				$idUsuario = $repre;
			}else if($ids != ''){
				$idUsuario = $ids."','".$_POST['idUsuario'];
			}else{
				$idUsuario = $_POST['idUsuario'];
			}
			$queryInventario = "select * from (
				SELECT U.USER_SNR, 
				U.USER_NR AS RUTA, 
				U.LNAME,
				LOTE.PRODFBATCH_SNR AS ID_LOTE,
				PROD.NAME AS PRODUCTO,
				PF.NAME PRES,
				LOTE.NAME AS LOTE,
				( select sum(a.quantity) from stock_prodform_user a
				where a.rec_stat = 0 and a.user_snr=U.user_snr
				and lote.prodfbatch_snr = a.prodfbatch_snr
				and a.table_nr=374 and a.accepted=1 and a.quantity > 0 ) as ENTRADA,
 
				isnull((select sum(quantity) from visitpers_prodbatch vp, visitpers v
				where vp.vispers_snr=v.vispers_snr
				and v.rec_stat=0 and vp.rec_stat=0 and v.user_snr=U.user_snr
				and vp.prodfbatch_snr=lote.prodfbatch_snr ),0) as SALIDA,

				isnull((select sum(quantity)*-1
						from stock_prodform_user 
						where stock_prodform_user.user_snr=u.user_snr 
						and lote.PRODFBATCH_SNR=STOCK_PRODFORM_USER.prodfbatch_snr
						and stock_prodform_user.rec_stat=0 
						and QUANTITY < 0),0) as TRANSFERENCIA,
 
				isnull((select sum(quantity) from visitinst_prodbatch vprod, visitinst v
				where vprod.visinst_snr=v.visinst_snr
				and v.rec_stat=0 and vprod.rec_stat=0 and v.user_snr=U.user_snr
				and vprod.prodfbatch_snr=lote.prodfbatch_snr),0) as SALIDA_INST, 

				( select STPRODF_USER_SNR from STOCK_PRODFORM_USER where STPRODF_USER_SNR = SPU.STPRODF_USER_SNR and QUANTITY > 0 and rec_stat=0 ) as ID_STOCK_LOTE  

				
 
				FROM STOCK_PRODFORM_USER SPU
				INNER JOIN PRODFORMBATCH LOTE ON LOTE.PRODFBATCH_SNR=SPU.PRODFBATCH_SNR AND LOTE.REC_STAT=0
				INNER JOIN PRODFORM PF ON PF.PRODFORM_SNR=LOTE.PRODFORM_SNR AND PF.REC_STAT=0
				INNER JOIN PRODUCT PROD ON PROD.PROD_SNR=PF.PROD_SNR AND PROD.REC_STAT=0
				INNER JOIN USERS U ON U.USER_SNR=SPU.USER_SNR
 
				where SPU.REC_STAT=0
				and SPU.TABLE_NR=374
				and SPU.ACCEPTED=1
				and U.user_snr in ('".$idUsuario."') ";

			if($expirado == 0){
				$queryInventario .= " and SPU.expiration_date >= GETDATE() ";
			}
				
			if($producto != ''){
				$queryInventario .= " and PROD.prod_snr = '".$producto."' ";
			}
					
			$queryInventario .= "GROUP BY LOTE.PRODFBATCH_SNR,PROD.NAME,PF.NAME,LOTE.NAME,U.USER_SNR,U.LNAME, U.USER_NR, STPRODF_USER_SNR ) a WHERE a.ENTRADA>0 ";
				
			if($existencia == 1){
				$queryInventario .= " and a.ENTRADA - a.SALIDA - a.SALIDA_INST - a.TRANSFERENCIA> 0 ";
			}
				 
			$queryInventario .= "   and a.ID_STOCK_LOTE is not null ORDER BY a.LNAME, a.PRODUCTO, a.PRES,a.LOTE ";

			//echo "qcargarInventario: ".$queryInventario."<br>";

			$rsInv = sqlsrv_query($conn, $queryInventario);
			echo "<script>

				$('#tblInventarioPrincipal tbody').empty();
				$('#tblEntradaInv tbody').empty();
				$('#tblSalidaInv tbody').empty();";
				
			while($regInv = sqlsrv_fetch_array($rsInv)){
				$entrada = $regInv['ENTRADA'];
				$salida = $regInv['SALIDA'];
				$salidaInst = $regInv['SALIDA_INST'];
				$transferencia = $regInv['TRANSFERENCIA'];
				$salidaTotal = $salida + $salidaInst + $transferencia;
				$existencia = $entrada - $salidaTotal;
				$idLote = $regInv['ID_LOTE'];
				$usr = $regInv['USER_SNR'];
				$ruta = $regInv['RUTA'];
				$producto = $regInv['PRODUCTO'];
				$pres = $regInv['PRES'];
				$lote = $regInv['LOTE'];
				$idStockLote = $regInv['ID_STOCK_LOTE'];

				$renglon = '<tr class="align-center">';
				if($tipoUsuario != 4){
					$renglon .= '<td style="width:10%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$usr.'\\\');">'.$ruta.'</td>';
				}
				$renglon .= '<td style="width:18%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$usr.'\\\');">'.$producto.'</td>';
				$renglon .= '<td style="width:28%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$usr.'\\\');">'.$pres.'</td>';
				$renglon .= '<td style="width:18%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$usr.'\\\');">'.$lote.'</td>';
				$renglon .= '<td style="width:12%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$usr.'\\\');">'.$entrada.'</td>';
				$renglon .= '<td style="width:12%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$usr.'\\\');">'.$salidaTotal.'</td>';
				$renglon .= '<td style="width:12%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$usr.'\\\');">'.$existencia.'</td>';
				if($tipoUsuario != 4){
					$renglon .= '<td style="width:12%;">';
					$renglon .= '<button type="button" class="btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button" onClick="transferirInventario(\\\''.$idStockLote.'\\\',\\\''.$existencia.'\\\');">';
					$renglon .= '<i class="material-icons pointer" data-toggle="tooltip" data-placement="left" title="Transferir">sync_alt</i>';
					$renglon .= '</button>';
					$renglon .= '</td>';
				}
				$renglon .= '</tr>';

				echo "$('#tblInventarioPrincipal tbody').append('".$renglon."');";
			}
			
			echo "</script>";
			
		}

		
	}
	
?>