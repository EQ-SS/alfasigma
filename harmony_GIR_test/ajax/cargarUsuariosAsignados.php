<?php
    include "../conexion.php";
    $ids = $_POST['ids'];

    $qUsers = "SELECT U.USER_SNR,U.USER_NR+' - '+U.LNAME+' '+U.MOTHERS_LNAME+' '+U.FNAME AS REPRE 
    FROM COACHING_USERS_ASSIGNED CUA 
    INNER JOIN USERS U ON U.USER_SNR=CUA.USER_SNR
    WHERE U.REC_STAT=0 AND CUA.REC_STAT=0
    AND U.USER_SNR<>'00000000-0000-0000-0000-000000000000'
    AND U.USER_SNR IN ('".$ids."')
    ORDER BY U.USER_NR,U.LNAME,U.MOTHERS_LNAME,U.FNAME";
    echo "<script>
        $('#sltRepreCoaching').empty();
        $('#sltRepreCoaching').append('<option selected=\"selected\" value=\"00000000-0000-0000-0000-000000000000\" >Seleccione</option>');
    </script>";
					
    $rsU = sqlsrv_query($conn, $qUsers);
    while($row = sqlsrv_fetch_array($rsU)){
        echo "<script>$('#sltRepreCoaching').append('<option value=".$row['USER_SNR']." >".$row['REPRE']."</option>');</script>";
    }
?>