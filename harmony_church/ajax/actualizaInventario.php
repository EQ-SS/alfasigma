<?php
include "../conexion.php";

$tipoUsuario = $_POST['tipoUsuario'];
$ids = $_POST['ids'];
$idUsuario = $_POST['idUsuario'];
$existencia = $_POST['existencia'];

if($tipoUsuario != 4){
	$ids .= "','".$idUsuario;
}

//echo "tipoUsuario: ".$tipoUsuario;

//echo "idUsuario:: ".$idUsuario;

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
			and SPU.ACCEPTED=1 ";
			if($tipoUsuario != 4){
				$queryInventario .= " and U.user_snr in ('".$ids."') "; 
			}else{
				$queryInventario .= " and U.user_snr='".$idUsuario."' ";
			}
			
			$queryInventario .= "and LOTE.expiration_date >= GETDATE()
			 
			GROUP BY LOTE.PRODFBATCH_SNR,PROD.NAME,PF.NAME,LOTE.NAME,U.USER_SNR,U.LNAME
			 
			) a WHERE a.ENTRADA>0 ";
			
			if($existencia == 1){
				$queryInventario .= " and a.ENTRADA - SALIDA - SALIDA_INST > 0 ";
			}
			 
			$queryInventario .= " ORDER BY a.LNAME,a.PRODUCTO,a.PRES,a.LOTE ";

//echo $queryInventario."<br>";

$rsInv = sqlsrv_query($conn, $queryInventario);

echo "<script>
	$('#tblInventarioPrincipal tbody').empty();";
while($regInv = sqlsrv_fetch_array($rsInv)){
	$total = $regInv['ENTRADA'] - ($regInv['SALIDA'] + $regInv['SALIDA_INST']);
	$salidasTotal = $regInv['SALIDA'] + $regInv['SALIDA_INST'];
	//echo "$('#tblInventarioPrincipal tbody').append('<tr onClick=\"traeEntradasSalidas(\'".$regInv['ID_LOTE']."\');\"><td width=\"400px\">".$regInv['PRODUCTO']."</td><td width=\"400px\">".$regInv['PRES']."</td><td width=\"200px\">".$regInv['LOTE']."</td><td width=\"100px\" align=\"right\">".$regInv['ENTRADA']."</td><td width=\"100px\" align=\"right\">".$regInv['SALIDA']."</td><td width=\"100px\" align=\"right\">".$total."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>');";
	echo "$('#tblInventarioPrincipal tbody').append('<tr class=\"align-center\" onClick=\"traeEntradasSalidas(\'".$regInv['ID_LOTE']."\');\"><td style=\"width:18%;\">".$regInv['PRODUCTO']."</td><td style=\"width:28%;\">".$regInv['PRES']."</td><td style=\"width:18%;\">".$regInv['LOTE']."</td><td style=\"width:12%;\">".$regInv['ENTRADA']."</td><td style=\"width:12%;\">".$salidasTotal."</td><td style=\"width:12%;\">".$total."</td></tr>');";
}
echo "</script>";
?>