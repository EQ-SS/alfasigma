<?php
include "../conexion.php";

if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
	$ids = $_POST['idRuta'];
}else{
	$ids = $_POST['ids'];
}

$quintil = $_POST['quintil'];

$queryCobFichero="with listado3 as (select
p.pers_snr,
p.fname as nombre,
p.lname as apellidop,
p.mothers_lname as apellidom,
cat.name as categMed,
esp.name as especialidad,
case when cat_audit.name = ' ' then 'NC' else isnull(cat_audit.name,'NC') end as quintil
from person p
inner join perslocwork plw on plw.pers_snr=p.pers_snr
inner join pers_srep_work psw on psw.pwork_snr=plw.pwork_snr
inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr
inner join inst i on i.inst_snr=ut.inst_snr
left outer join person_cycle_current pcc on pcc.pers_snr=p.pers_snr
left outer join codelist cat_audit on cat_audit.clist_snr=p.CATEGORY_AUDIT_SNR
left outer join codelist status on status.clist_snr=p.status_snr 
left outer join codelist cat on cat.clist_snr=p.CATEGORY_SNR 
left outer join codelist esp on esp.clist_snr=p.SPEC_SNR
where status.name='ACTIVO'
and plw.rec_stat=0 and psw.rec_stat=0 and ut.rec_stat=0 and p.rec_stat=0 and i.rec_stat=0
and psw.USER_SNR in ('".$ids."'))
select
nombre,apellidop,apellidom,categMed,especialidad
from listado3 
where quintil='".$quintil."'
order by nombre";

//echo $queryCobFichero;

$rsListCobFichero = sqlsrv_query($conn, $queryCobFichero);

echo "<script>
	$('#tblListDisQuintil tbody').empty();
	$('#numMedDisQuintil').empty();";

	$contador = 0;
	$sinDatos = 'Sin datos que mostrar';
	$fullName = '';
	$result='';
	while($list = sqlsrv_fetch_array($rsListCobFichero)){
		$contador++;
		$fullName = $list['nombre']." ".$list['apellidop']." ".$list['apellidom'];

		$result.="<tr><td style=\"width:50%;\">".$fullName."</td><td style=\"width:35%;\" class=\"align-center\">".$list['especialidad']."</td><td style=\"width:15%;\" class=\"align-center\">".$list['categMed']."</td></tr>";
	}
	if($contador == 0){
		echo "$('#tblListDisQuintil tbody').append('<tr><td class=\"align-center\" style=\"width:100%;\">".$sinDatos."</td></tr>');";
	}else{
        echo "$('#tblListDisQuintil tbody').append('".$result."');";
    }
	echo "$('#numMedDisQuintil').append('".$contador."');";
echo "</script>";
?>