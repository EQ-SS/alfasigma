<?php
include "../conexion.php";

if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
	$ids = $_POST['idRuta'];
}else{
	$ids = $_POST['ids'];
}

$especialidad = $_POST['especialidad'];

$queryDisFichero="select
isnull(spec.name_short,'OTRAS') as ESP,
spec.name as especialidad,
p.fname as nombre,
p.lname as apellidop,
p.MOTHERS_LNAME as apellidom,
cat.name as categ
from codelist spec 
inner join person p on p.SPEC_SNR = spec.CLIst_SNR 
inner join pers_srep_work psw on psw.pers_snr=p.pers_snr 
inner join perslocwork plw on plw.pers_snr=p.pers_snr 
inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr 
inner join inst i on i.inst_snr=ut.inst_snr 
left outer join codelist status on status.clist_snr=p.status_snr 
left outer join compline_specs spec_target on spec_target.spec_snr=spec.clist_snr 
left outer join codelist cat on cat.clist_snr=p.CATEGORY_SNR
where spec.clib_snr in (select clib_snr from codelistlib where table_nr=19 and list_nr=1)
and spec.rec_stat=0 and spec.status=1 
and p.rec_stat=0 and status.name='ACTIVO' and p.spec_snr=spec.clist_snr
and psw.rec_stat=0 and plw.rec_stat=0 and ut.rec_stat=0 and i.rec_stat=0
and psw.user_snr in ('".$ids."') 
and spec.name_short = '".$especialidad."'
order by p.fname";

//echo $queryDisFichero;

$rsListDisFichero = sqlsrv_query($conn, $queryDisFichero);

echo "<script>
	$('#tblListDisFichero tbody').empty();
	$('#numMedDisFichero').empty();";

	$contador = 0;
	$sinDatos = 'Sin datos que mostrar';
	$fullName = '';
	$result='';
	while($list = sqlsrv_fetch_array($rsListDisFichero)){
		$contador++;
		$fullName = $list['nombre']." ".$list['apellidop']." ".$list['apellidom'];

		$result.="<tr><td style=\"width:50%;\">".$fullName."</td><td style=\"width:35%;\" class=\"align-center\">".$list['especialidad']."</td><td style=\"width:15%;\" class=\"align-center\">".$list['categ']."</td></tr>";
	}
	if($contador == 0){
		echo "$('#tblListDisFichero tbody').append('<tr><td class=\"align-center\" style=\"width:100%;\">".$sinDatos."</td></tr>');";
	}else{
        echo "$('#tblListDisFichero tbody').append('".$result."');";
    }
	echo "$('#numMedDisFichero').append('".$contador."');";
echo "</script>";
?>