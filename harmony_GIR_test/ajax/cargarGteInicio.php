<?php
    include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
        $tipoUsuario = $_POST['tipoUsuario'];
        $ids = str_replace(",","','",$_POST['ids']);
        $ids = str_replace("'',''","','",$ids);
        
        //if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
        $idGte = $_POST['idRuta2'];
        //}
        //echo $idGte."<br>";
        if($idGte != ''){
            if($tipoUsuario == 2){
                $queryUsuarios = "select USER_SNR from kloc_reg k, users u
                    where reg_snr = '".$idGte."'
                    and k.REC_STAT = 0 
                    and KLOC_SNR = USER_SNR
                    and u.REC_STAT = 0
                    and u.USER_TYPE = 4
                    order by u.LNAME ";
            }
			//echo $queryUsuarios;
            $ids = '';
            $rsids = sqlsrv_query($conn, $queryUsuarios);
            while($rowId = sqlsrv_fetch_array($rsids)){
                $ids .= $rowId['USER_SNR']."','";
            }
            $ids = substr($ids, 0, -3);

            $queryRutas = "select * from users where user_snr in ('".$ids."') order by lname";
            $rsRutas = sqlsrv_query($conn, $queryRutas);
            $cont = 0;
            
            while($regRutas = sqlsrv_fetch_array($rsRutas)){
                if($cont == 0){
                    echo '<option value="">Seleccione</option>';
                }
                echo '<option value="'.$regRutas["USER_SNR"].'"> '.$regRutas["USER_NR"].' - '.utf8_encode($regRutas["LNAME"].' '.$regRutas["FNAME"]).'</option>';	
                $cont++;			
            }
        }else{
            echo '<option value="">Seleccione</option>';
            $queryRutas = "select * from users where user_snr in ('".$ids."') order by lname";
            $rsRutas = sqlsrv_query($conn, $queryRutas);
            while($regRutas = sqlsrv_fetch_array($rsRutas)){
                echo '<option value="'.$regRutas["USER_SNR"].'"> '.$regRutas["USER_NR"].'  -   '.utf8_encode($regRutas["LNAME"].' '.$regRutas["FNAME"]).'</option>';				
            }
        }
    }
?>