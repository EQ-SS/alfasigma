<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$hoy = date("Y-m-d");
		//$hoy = '2021-09-08';
		$idUsuario = $_POST['idUsuario'];
		$idPersona = $_POST['idPersona'];
		$nombre = $_POST['nombre'];
		$cargo = $_POST['cargo'];
		$datosMedico = $_POST['datosMedico'];
		$tipoUsuario = $_POST['tipoUsuario'];
		$limiteBajas = 0;
		echo "<script>console.log('".$tipoUsuario."');</script>";
		
		if($tipoUsuario == 2){

			$queryRepreBajaSlt="SELECT U.USER_SNR,U.USER_NR+' - '+U.LNAME+' '+U.MOTHERS_LNAME+' '+U.FNAME AS REPRE FROM PERS_SREP_WORK PSW INNER JOIN USERS U ON U.USER_SNR=PSW.USER_SNR WHERE  PERS_SNR='".$idPersona."'";
			$rsqueryRepreBajaSlt = sqlsrv_query($conn, $queryRepreBajaSlt , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

                      $value="";                           
			while($regMotivoBajaRepre = sqlsrv_fetch_array($rsqueryRepreBajaSlt)){
			
			$value= $regMotivoBajaRepre['USER_SNR'];

			}
			
			echo "<script>
			var value=\"$value\";
			

			document.querySelector('#sltRepresBaja').setValue(value);

			$('#hdnIdPersonaBaja').val('".$idPersona."');
			$('#lblNombreMedEliminar').text('".$nombre."');
			$('#lblTipoMedEliminar').text('".$cargo."');
			$('#lblDatosMedicoMotivoBaja').html('".$datosMedico."');
			$('#divMotivoBaja').show();
			$('#over').show(500);
			$('#fade').show(500);
			
			
			</script>";

		}else{
		if($tipoUsuario == 4){
			$limiteBajas = sqlsrv_fetch_array(sqlsrv_query($conn, "select DELETE_ALLOWED from users where user_snr = '".$idUsuario."'"))['DELETE_ALLOWED'];
			$qBajasCiclo = "select count(at.change_USER_SNR) as total
				from PERSON_APPROVAL pa
				inner join APPROVAL_STATUS at on pa.PERS_APPROVAL_SNR = at.PERS_APPROVAL_SNR
				inner join cycles c on pa.CREATION_TIMESTAMP between c.START_DATE and c.FINISH_DATE
				where P_MOVEMENT_TYPE = 'D'
				and at.APPROVED_STATUS in (1,2,4)
				and '".$hoy."' between c.START_DATE and c.FINISH_DATE
				and at.CHANGE_USER_SNR = '".$idUsuario."'
				group by at.change_USER_SNR";
			$bajasCiclo = sqlsrv_fetch_array(sqlsrv_query($conn, $qBajasCiclo))['total'];
			//echo "limite: ".$limiteBajas." ::: "." bajas: ".$bajasCiclo;
		}
		if($limiteBajas <= $bajasCiclo){
			echo "<script>notificationLimiteBajas('".$limiteBajas."');</script>";
		}else{
			echo "<script>
			$('#hdnIdPersonaBaja').val('".$idPersona."');
			$('#lblNombreMedEliminar').text('".$nombre."');
			$('#lblTipoMedEliminar').text('".$cargo."');
			$('#lblDatosMedicoMotivoBaja').html('".$datosMedico."');
			$('#divMotivoBaja').show();
			$('#over').show(500);
			$('#fade').show(500);
		</script>";
		}
	}
}
	
?>