<?php

    include "../conexion.php";

    $fecha = $_POST['fecha'];
    $tipoUsuario = $_POST['tipoUsuario'];
    $idUsuario = $_POST['idUsuario'];
    $ruta = $_POST['ruta'];
    $idPers = $_POST['idPers'];
    $idMedicoC = $_POST['idMedicoC'];

    $queryFreqVis = "select freq.NAME as frecuencia, 
        (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
        WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
        AND '".date("Y-m-d")."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
        AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE ";	

    if($tipoUsuario == 4){
        $queryFreqVis .= " and vp.user_snr = '".$idUsuario."' ) as visitas ";
    }else{
        if (strlen($ruta)>10) {
            $queryFreqVis .= " and vp.user_snr = '".$ruta."' ) as visitas ";
        } else {
            $queryFreqVis .= " and vp.user_snr = '".$idUsuario."' ) as visitas ";
        }
    }
        $queryFreqVis .= " from person p
            inner join CODELIST freq on p.frecvis_SNR = freq.CLIST_SNR
            where p.pers_snr = '".$idPers."'
            and p.REC_STAT = 0 ";

    $regValFrec = sqlsrv_fetch_array(sqlsrv_query($conn, $queryFreqVis));

    $queryFrecTipoCto="select CONTACT_TYPE_SNR as tipoContacto 
        from VISITPERS VP, CYCLES CICLOS, person p 
        where VP.REC_STAT=0 AND CICLOS.REC_STAT=0 
        and p.PERS_SNR=VP.pers_snr
        AND '".date("Y-m-d")."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE AND VISIT_DATE BETWEEN CICLOS.START_DATE 
        AND CICLOS.FINISH_DATE and vp.user_snr = '".$idUsuario."'";
       // echo $queryFrecTipoCto;

    $rsFrecTipoCto= sqlsrv_query($conn, $queryFrecTipoCto);

    echo "<script>";

    if(($regValFrec['frecuencia'] == $regValFrec['visitas'] && $regValFrec['visitas'] > 0) || $regValFrec['frecuencia'] < $regValFrec['visitas'] ){
            echo "document.getElementById('".$idMedicoC."Circulo').className = 'circuloVerde'";
    }
    if($regValFrec['visitas'] > 0 && $regValFrec['visitas'] < $regValFrec['frecuencia']){
        echo "document.getElementById('".$idMedicoC."Circulo').className = 'circuloAmarillo'";
    }
    if($regValFrec['visitas'] == 0){
        echo "document.getElementById('".$idMedicoC."Circulo').className = 'circuloRojo'";
    }

    echo "</script>";
?>