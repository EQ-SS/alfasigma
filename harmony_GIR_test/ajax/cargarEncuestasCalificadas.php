<?php
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array(" ", " ", " ", " ");
	$reemplazar1=array("", "", "", "");
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		$idEncuesta = $_POST['idEncuesta'];
		$ids = $_POST['ids'];
		$tipoUsuario = $_POST['tipoUsuario'];

		$queryRepres="with sq as (
		select ROW_NUMBER() over(partition by c.coaching_snr, ca.user_snr, ca.coaching_user_snr order by c.coaching_snr, ca.user_snr, ca.coaching_user_snr, convert(varchar(10),ca.date,120)) as orden,
		c.coaching_snr, ca.user_snr, ca.coaching_user_snr, c.name, convert(varchar(10),ca.date,120) as fecha  
		from coaching c
		left outer join coaching_answered ca on ca.coaching_snr=c.coaching_snr
		where c.rec_stat=0
		and ca.rec_stat=0
		and ca.user_snr in ('".$ids."')
		group by c.coaching_snr, ca.user_snr, ca.coaching_user_snr, c.name, convert(varchar(10),ca.date,120)
		)
		select
		cu.coaching_user_snr,
		coaching.coaching_snr,
		cu.user_snr,
		coaching.name,
		u.USER_NR+' - '+u.LNAME+' '+u.MOTHERS_LNAME+' '+u.FNAME as nombre,
		isnull((select fecha from sq where sq.coaching_snr=coaching.coaching_snr and sq.coaching_user_snr=cu.coaching_user_snr and sq.user_snr=cu.user_snr and orden=1),'') as fecha1,
		isnull((select fecha from sq where sq.coaching_snr=coaching.coaching_snr and sq.coaching_user_snr=cu.coaching_user_snr and sq.user_snr=cu.user_snr and orden=2),'') as fecha2,
		isnull((select fecha from sq where sq.coaching_snr=coaching.coaching_snr and sq.coaching_user_snr=cu.coaching_user_snr and sq.user_snr=cu.user_snr and orden=3),'') as fecha3
		from coaching
		inner join coaching_users cu on cu.coaching_snr=coaching.coaching_snr
		inner join USERS u on u.USER_SNR=cu.USER_SNR
		where coaching.coaching_snr<>'00000000-0000-0000-0000-000000000000'
		and coaching.rec_stat=0
		and cu.coaching_snr in (select coaching_snr from coaching_answered where rec_stat=0)
		and cu.user_snr in ('".$ids."')
		order by u.user_nr,coaching.name";

	/*	$queryRepres="select u.user_snr, su.COACHING_SNR, 
		u.USER_NR+' - '+u.LNAME + ' ' + u.MOTHERS_LNAME + ' ' + u.FNAME as nombre,
		case when su.replied is null or su.replied=''  then 'SIN REPLICA' else 'CON REPLICA' end as replica,
		(select top 1 
		cast(year(DATE) as varchar) + '-' + format(month(DATE), '00') + '-' + format(day(DATE), '00') as fecha 
		from COACHING_ANSWERED 
		where user_snr = u.user_snr and COACHING_snr = su.COACHING_SNR) as fecha,
		CASE WHEN su.CLOSED=0 THEN 'EN PROCESO' ELSE 'TERMINADA' END AS ESTATUS,
		su.COACHING_USER_SNR
		from users u
		left outer join COACHING_USERS su on su.USER_SNR = u.USER_SNR
		where u.user_snr in (
		select user_snr from COACHING_ANSWERED 
		where COACHING_SNR = '".$idEncuesta."' 
		group by USER_SNR) 
		and su.COACHING_SNR = '".$idEncuesta."'
		and u.user_snr in ('".$ids."') 
		order by fecha desc";*/

		
		$rsRepres = sqlsrv_query($conn, $queryRepres);
		
		echo $queryRepres;
		
		echo "<script>
			$('#tblEncuestasCalificadas tbody').empty();
			";
				
		while($repre = sqlsrv_fetch_array($rsRepres)){
			$nombre = utf8_encode($repre['nombre']);
			//$replica = $repre['replica'];
			//$fecha = $repre['fecha'];
			$fecha1 = $repre['fecha1'];
			$fecha2 = $repre['fecha2'];
			$fecha3 = $repre['fecha3'];
			$idUsuario = $repre['user_snr'];
			//$estatus=$repre['ESTATUS'];
			$idCoachingUser=$repre['coaching_user_snr'];

			/*$queryFechasCiclo="SELECT  convert(varchar(10),DATE ,120) as fecha 
			FROM COACHING_ANSWERED 
			WHERE COACHING_ANSWERED_SNR<>'00000000-0000-0000-0000-000000000000'
			AND REC_STAT=0
			AND COACHING_USER_SNR='".$idCoachingUser."'
			group by convert(varchar(10),DATE ,120)
			order by convert(varchar(10),DATE ,120) ";
			$rsFechasCi = sqlsrv_query($conn, $queryFechasCiclo);
			$fecha="";
			while($fechaCi = sqlsrv_fetch_array($rsFechasCi)){
				$fecha.=$fechaCi['fecha']." ,";
			}
			$fecha=substr($fecha,0,-1);*/


			echo "$('#tblEncuestasCalificadas').append('<tr onclick=\"traeEncuestaCalificadaGerente(\'".$idEncuesta."\',\'".$idUsuario."\',\'".$idCoachingUser."\');\"><td style=\"width:50%;\">".$nombre."</td><td style=\"width:25%;\" class=\"align-center\">".$fecha1."</td><td style=\"width:25%;\" class=\"align-center\">".$fecha2."</td><td style=\"width:25%;\" class=\"align-center\">".$fecha3."</td></tr>');";
		}
	}
?>