<?php
include "../conexion.php";

$tipoUsuario = $_POST['tipoUsuario'];
$ids = $_POST['ids'];
$idUsuario = $_POST['idUsuario'];
$existencia = $_POST['existencia'];
$expirado = $_POST['expirado'];

$queryInventario = "
select * from 
(
	SELECT U.USER_SNR, 
	U.USER_NR as RUTA,
	U.LNAME,
	LOTE.PRODFBATCH_SNR AS ID_LOTE,
	PROD.NAME AS PRODUCTO,
	PF.NAME PRES,
	LOTE.NAME AS LOTE,
	(
		select sum(a.quantity) 
		from stock_prodform_user a
		where a.rec_stat = 0 
		and a.user_snr=U.user_snr
		and lote.prodfbatch_snr = a.prodfbatch_snr
		and a.table_nr=374 
		and a.accepted=1
		and a.quantity > 0
	) as ENTRADA,
			 
	isnull
	(
		(
			select sum(quantity) 
			from visitpers_prodbatch vp, visitpers v
			where vp.vispers_snr=v.vispers_snr
			and v.rec_stat=0 
			and vp.rec_stat=0 
			and v.user_snr=U.user_snr
			and vp.prodfbatch_snr=lote.prodfbatch_snr 
		),0
	) as SALIDA,
	
	isnull
	(
		(
			select sum(quantity)*-1
			from stock_prodform_user 
			where stock_prodform_user.user_snr=u.user_snr and lote.PRODFBATCH_SNR=STOCK_PRODFORM_USER.prodfbatch_snr
			and stock_prodform_user.rec_stat=0
			and QUANTITY<0
		)
		,0
	) as TRANSFERENCIA,
			 
	isnull
	(
		(
			select sum(quantity) 
			from visitinst_prodbatch vprod, visitinst v
			where vprod.visinst_snr=v.visinst_snr
			and v.rec_stat=0 
			and vprod.rec_stat=0 
			and v.user_snr=U.user_snr
			and vprod.prodfbatch_snr=lote.prodfbatch_snr
		),0
	) as SALIDA_INST, 
			
	(
		select STPRODF_USER_SNR
		from STOCK_PRODFORM_USER
		where STPRODF_USER_SNR = SPU.STPRODF_USER_SNR
		and QUANTITY > 0
		and rec_stat=0 
	) as ID_STOCK_LOTE
			 
	FROM STOCK_PRODFORM_USER SPU
	INNER JOIN PRODFORMBATCH LOTE ON LOTE.PRODFBATCH_SNR=SPU.PRODFBATCH_SNR AND LOTE.REC_STAT=0
	INNER JOIN PRODFORM PF ON PF.PRODFORM_SNR=LOTE.PRODFORM_SNR AND PF.REC_STAT=0
	INNER JOIN PRODUCT PROD ON PROD.PROD_SNR=PF.PROD_SNR AND PROD.REC_STAT=0
	INNER JOIN USERS U ON U.USER_SNR=SPU.USER_SNR
			 
	where SPU.REC_STAT=0
	and SPU.TABLE_NR=374
	and SPU.ACCEPTED=1 ";
	
	if($tipoUsuario != 4){
		$queryInventario .= " and U.user_snr in ('".$ids."','".$idUsuario."') "; 
	}else{
		$queryInventario .= " and U.user_snr='".$idUsuario."' ";
	}
			
	if($expirado == 0){
		$queryInventario .= " and LOTE.expiration_date >= GETDATE() ";	
	}
			
			 
	$queryInventario .= " GROUP BY LOTE.PRODFBATCH_SNR, PROD.NAME, PF.NAME, LOTE.NAME, U.USER_SNR, U.LNAME, U.USER_NR, STPRODF_USER_SNR
) a WHERE a.ENTRADA>0 ";
			
if($existencia == 1){
	$queryInventario .= " and a.ENTRADA - a.SALIDA - a.SALIDA_INST - a.TRANSFERENCIA > 0 ";
}
			 
$queryInventario .= " and a.ID_STOCK_LOTE is not null 
	ORDER BY a.LNAME,a.PRODUCTO,a.PRES,a.LOTE ";


//echo $queryInventario."<br>";

$rsInv = sqlsrv_query($conn, $queryInventario);
echo "<script>
	$('#tblInventarioPrincipal tbody').empty();";
$arrLotes = array();
while($regInv = sqlsrv_fetch_array($rsInv)){
	$idLote = $regInv['ID_LOTE'];
	$idUsr = $regInv['USER_SNR'];
	$ruta = $regInv['RUTA'];
	$producto = $regInv['PRODUCTO'];
	$pres = $regInv['PRES'];
	$lote = $regInv['LOTE'];
	$entrada = $regInv['ENTRADA'];
	$idStockLote = $regInv['ID_STOCK_LOTE'];
	$total = $regInv['ENTRADA'] - ($regInv['SALIDA'] + $regInv['TRANSFERENCIA'] + $regInv['SALIDA_INST']);
	$salidasTotal = $regInv['SALIDA'] + $regInv['TRANSFERENCIA'] + $regInv['SALIDA_INST'];
	
	if(! in_array($regInv['ID_LOTE'],$arrLotes)){
		$arrLotes[] = $regInv['ID_LOTE'];
		if($tipoUsuario != 4){
			$renglon = '<tr class="align-center">';
			$renglon .= '<td style="width:10%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$ruta.'</td>';
			$renglon .= '<td style="width:18%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$producto.'</td>';
			$renglon .= '<td style="width:28%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$pres.'</td>';
			$renglon .= '<td style="width:18%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$lote.'</td>';
			$renglon .= '<td style="width:12%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$entrada.'</td>';
			$renglon .= '<td style="width:12%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$salidasTotal.'</td>';
			$renglon .= '<td style="width:12%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$total.'</td>';
			$renglon .= '<td style="width:12%;">';
			
			if($total > 0){
				$renglon .= '<button type="button" class="btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button" onClick="transferirInventario(\\\''.$idStockLote.'\\\',\\\''.$total.'\\\');">';
			}else{
				$renglon .= '<button type="button" class="btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button" onClick="transferirInventario(\\\''.$idStockLote.'\\\',\\\''.$total.'\\\');" disabled>';
			}
			$renglon .= '<i class="material-icons pointer" data-toggle="tooltip" data-placement="left" title="Transferir">sync_alt</i>';
			$renglon .= '</button>';
			$renglon .= '</td>';
			$renglon .= '</tr>';
		}else{//REPRE
			$renglon = '<tr class="align-center">';
			$renglon .= '<td style="width:18%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$producto.'</td>';
			$renglon .= '<td style="width:28%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$pres.'</td>';
			$renglon .= '<td style="width:18%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$lote.'</td>';
			$renglon .= '<td style="width:12%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$entrada.'</td>';
			$renglon .= '<td style="width:12%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$salidasTotal.'</td>';
			$renglon .= '<td style="width:12%;" onClick="traeEntradasSalidas(\\\''.$idLote.'\\\',\\\''.$idUsr.'\\\');">'.$total.'</td>';
			$renglon .= '</tr>';
		}
		echo "$('#tblInventarioPrincipal tbody').append('".$renglon."');";
	}
}
echo "</script>";
?>