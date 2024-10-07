<?php
include "../conexion.php";

if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
	$ids = $_POST['idRuta'];
}else{
	$ids = $_POST['ids'];
}

$categoria = $_POST['categoria'];
$esVisitado = $_POST['esVisitado'];

$queryCobCat="with listado4 as (
    select
    p.pers_snr,
    p.FNAME as nombre,
    p.lname as apellidop,
    p.MOTHERS_LNAME as apellidom,
    cat.name as categ,
    esp.NAME as especialidad,
    isnull(pcc.total,0) as vis_current,
    isnull(pc1.total,0) as vis_cycle1,
    isnull(pc2.total,0) as vis_cycle2,
    case when isnull(pcc.total,0)>0 then 1 else 0 end vis_unique_current,
    case when isnull(pcc.total,0)>0 then 1 else 0 end+case when isnull(pc1.total,0)>0 then 1 else 0 end+case when isnull(pc2.total,0)>0 then 1 else 0 end total
    from person p
    inner join perslocwork plw on plw.pers_snr=p.pers_snr
    inner join pers_srep_work psw on psw.pwork_snr=plw.pwork_snr
    inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr
    inner join inst i on i.inst_snr=ut.inst_snr
    left outer join person_cycle_current pcc on pcc.pers_snr=p.pers_snr
    left outer join PERSON_CYCLE_1 pc1 on pc1.pers_snr=p.pers_snr
    left outer join PERSON_CYCLE_2 pc2 on pc2.pers_snr=p.pers_snr
    left outer join codelist cat on cat.clist_snr=p.category_snr
    left outer join codelist status on status.clist_snr=p.status_snr 
    left outer JOIN CODELIST ESP ON ESP.CLIST_SNR=P.SPEC_SNR
    where status.name='ACTIVO'
    and plw.rec_stat=0 and psw.rec_stat=0 and ut.rec_stat=0 and p.rec_stat=0 and i.rec_stat=0
    and psw.user_snr in ('".$ids."')
    )
    select 
    categ,nombre,apellidop,apellidom,especialidad,vis_unique_current 
    from listado4 
    where categ='".$categoria."'";

    if($esVisitado == 0){
        $queryCobCat .= " and vis_unique_current = ".$esVisitado."";
    }
    if($esVisitado == 1){
        $queryCobCat .= " and vis_unique_current >= ".$esVisitado."";
    }
    $queryCobCat .= "order by nombre";

//echo $queryCobCat;

$rsListCobCat = sqlsrv_query($conn, $queryCobCat);

//3seg bd (AAA)
//10-14seg con append ->7seg cadena
//1seg bd (AAA-R)
//8-10 con append -> 2seg cadena

echo "<script>
	$('#tblListCobCat tbody').empty();
	$('#numMedCobCat').empty();";

	$contador = 0;
	$sinDatos = 'Sin datos que mostrar';
    $fullName = '';
    $result='';
	while($list = sqlsrv_fetch_array($rsListCobCat)){
		$contador++;
        $fullName = $list['nombre']." ".$list['apellidop']." ".$list['apellidom'];
        $result.="<tr><td style=\"width:40%;\">".$fullName."</td><td style=\"width:30%;\" class=\"align-center\">".$list['especialidad']."</td><td style=\"width:15%;\" class=\"align-center\">".$list['categ']."</td><td style=\"width:15%;\" class=\"align-center\">".$list['vis_unique_current']."</td></tr>";
    }

	if($contador == 0){
		echo "$('#tblListCobCat tbody').append('<tr><td class=\"align-center\" style=\"width:100%;\">".$sinDatos."</td></tr>');";
	}else{
        echo "$('#tblListCobCat tbody').append('".$result."');";
    }
	echo "$('#numMedCobCat').append('".$contador."');";
echo "</script>";
?>