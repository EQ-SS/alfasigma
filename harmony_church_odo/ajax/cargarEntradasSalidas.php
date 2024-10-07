<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$idLote = $_POST['idLote'];
		$idUsuario = $_POST['idUsuario'];
		$ids = $_POST['ids'];
		$repre = $_POST['repre'];

		if($repre != ''){
			$idUsuario = str_replace(",", "','", substr($repre, 0, -1));
		}else if($ids != ''){
			$idUsuario = $ids;
		}
				
		$queryEntradas = "select SPU.ENTRYDATE as FECHA_ENTRADA,PROD.NAME AS PRODUCTO,
				PF.NAME PRES,LOTE.NAME AS LOTE,
				SPU.QUANTITY AS ENTRADA 
				from STOCK_PRODFORM_USER SPU 
				INNER JOIN PRODFORMBATCH LOTE ON LOTE.PRODFBATCH_SNR=SPU.PRODFBATCH_SNR 
				INNER JOIN PRODFORM PF ON LOTE.PRODFORM_SNR=PF.PRODFORM_SNR 
				INNER JOIN PRODUCT PROD ON PF.PROD_SNR=PROD.PROD_SNR 
				WHERE 
				SPU.REC_STAT=0 AND TABLE_NR=374 
				AND SPU.USER_SNR in ('".$idUsuario."')  
				AND LOTE.PRODFBATCH_SNR in ('".$idLote."') 
				AND SPU.ACCEPTED = 1 
				ORDER BY FECHA_ENTRADA desc,PRODUCTO,PRES,LOTE ";
				
		$querySalidas = "SELECT VISIT_DATE AS FECHA_VIS, 
                P.LNAME + ' ' + P.MOTHERS_LNAME + ' ' + P.FNAME AS NOMBRE_MED,
				pf.NAME as PRODUCTO,
                VPROD.QUANTITY CANTIDAD 
                FROM VISITPERS_PRODBATCH VPROD 
                INNER JOIN VISITPERS V ON V.REC_STAT=0 AND V.VISPERS_SNR=VPROD.VISPERS_SNR 
				INNER JOIN PRODFORMBATCH pb on vprod.PRODFBATCH_SNR = pb.PRODFBATCH_SNR
				INNER JOIN PRODFORM pf on pf.PRODFORM_SNR = pb.PRODFORM_SNR
                LEFT OUTER JOIN PERSON P ON P.PERS_SNR=V.PERS_SNR 
                WHERE 
                VPROD.REC_STAT=0 
                AND VPROD.QUANTITY>0 
				AND VPROD.PRODFBATCH_SNR in ('".$idLote."')  
                AND V.USER_SNR in ('".$idUsuario."')
                UNION 
                SELECT VISIT_DATE AS FECHA_VIS, 
                I.NAME AS NOMBRE_MED,
				pf.NAME as PRODUCTO,
                VPROD.QUANTITY CANTIDAD 
                FROM VISITINST_PRODBATCH VPROD 
                INNER JOIN VISITINST V ON V.REC_STAT=0 AND V.VISINST_SNR=VPROD.VISINST_SNR 
				inner join PRODFORMBATCH pb on vprod.PRODFBATCH_SNR = pb.PRODFBATCH_SNR
				inner join PRODFORM pf on pf.PRODFORM_SNR = pb.PRODFORM_SNR
                LEFT OUTER JOIN INST I ON I.INST_SNR=V.INST_SNR 
                WHERE 
                VPROD.REC_STAT=0 
                AND VPROD.QUANTITY>0 
				AND VPROD.PRODFBATCH_SNR in ('".$idLote."')
				AND V.USER_SNR in ('".$idUsuario."')				
                ORDER BY FECHA_VIS DESC, NOMBRE_MED ";
				
		//echo $queryEntradas."<br>";
		//echo $querySalidas."<br>";
		
		$rsEntradas = sqlsrv_query($conn, $queryEntradas);
		$rsSalidas = sqlsrv_query($conn, $querySalidas);
		
		echo "<script>
				$('#tblEntradaInv tbody').empty();
				$('#tblSalidaInv tbody').empty();";
				
		while($entrada = sqlsrv_fetch_array($rsEntradas)){
			foreach ($entrada['FECHA_ENTRADA'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha = substr($val, 0, 10);
				}
			}
			echo "$('#tblEntradaInv tbody').append('<tr><td style=\"width:50%;\">".$fecha."</td><td style=\"width:50%;\">".$entrada['ENTRADA']."</td></tr>');";

		}
		
		while($salida = sqlsrv_fetch_array($rsSalidas)){
			foreach ($salida['FECHA_VIS'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fecha = substr($val, 0, 10);
				}
			}
			echo "$('#tblSalidaInv tbody').append('<tr ><td style=\"width:15%;\">".$fecha."</td><td style=\"width:40%;\">".$salida['NOMBRE_MED']."</td><td style=\"width:30%;\">".$salida['PRODUCTO']."</td><td style=\"width:15%;\">".$salida['CANTIDAD']."</td></tr>');";
		}
		echo "</script>";
	}
?>