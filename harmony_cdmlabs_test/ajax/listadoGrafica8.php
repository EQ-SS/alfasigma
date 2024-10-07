<?php
include "../conexion.php";

if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
	$ids = $_POST['idRuta'];
}else{
	$ids = $_POST['ids'];
}

$quintil = $_POST['quintil'];

$queryCobQuintil="with listado4 as (select
p.pers_snr,
p.fname as nombre,
p.lname as apellidop,
p.mothers_lname as apellidom,
cat.name as categMed,
esp.name as especialidad,
case when isnull(pcc.total,0)>0 then 1 else 0 end as unique_visit
from person p
inner join perslocwork plw on plw.pers_snr=p.pers_snr
inner join pers_srep_work psw on psw.pwork_snr=plw.pwork_snr
inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr
inner join inst i on i.inst_snr=ut.inst_snr
left outer join person_cycle_current pcc on pcc.pers_snr=p.pers_snr
left outer join person_ud pu on p.pers_snr=pu.pers_snr
left outer join codelist status on status.clist_snr=p.status_snr 
left outer join codelist cat on cat.clist_snr=p.CATEGORY_SNR 
left outer join codelist esp on esp.clist_snr=p.SPEC_SNR
where status.name='ACTIVO'
and plw.rec_stat=0 and psw.rec_stat=0 and ut.rec_stat=0 and p.rec_stat=0 and i.rec_stat=0
and psw.USER_SNR in ('".$ids."'))
select
nombre,apellidop,apellidom,categMed,especialidad
from listado4 
where unique_visit > 0";
/*if($quintil != 'TOTAL'){
    $queryCobQuintil.=" and quintil='".$quintil."'";
}*/
$queryCobQuintil.=" order by nombre";

//echo $queryCobQuintil;

$rsListCobQuintil = sqlsrv_query($conn, $queryCobQuintil);

echo "<script>
	$('#tblListCobQuintil tbody').empty();
	$('#numMedCobQuintil').empty();";

	$contador = 0;
	$sinDatos = 'Sin datos que mostrar';
	$fullName = '';
	$result='';
	while($list = sqlsrv_fetch_array($rsListCobQuintil)){
		$contador++;
		$fullName = $list['nombre']." ".$list['apellidop']." ".$list['apellidom'];

		$result.="<tr><td style=\"width:50%;\">".$fullName."</td><td style=\"width:35%;\" class=\"align-center\">".$list['especialidad']."</td><td style=\"width:15%;\" class=\"align-center\">".$list['categMed']."</td></tr>";
	}
	if($contador == 0){
		echo "$('#tblListCobQuintil tbody').append('<tr><td class=\"align-center\" style=\"width:100%;\">".$sinDatos."</td></tr>');";
	}else{
        echo "$('#tblListCobQuintil tbody').append('".$result."');";
    }
	echo "$('#numMedCobQuintil').append('".$contador."');";
echo "</script>";
?>