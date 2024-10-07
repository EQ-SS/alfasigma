<?php
include "../conexion.php";

$datos = $_POST['rowObject'];
//print_r($datos);


//echo var_dump($datos);
//return true;

echo "<script> 
$('#tblCargaDatosPersona tbody').empty();
</script>";
$color_tipo="";
$color_apat="";
$color_amat="";
$color_nom="";
$color_sexo="";
$color_SpecCartera="";
$color_SubEspec="";
$color_Cedula="";
$color_CatAudit="";
$color_Estatus="";
$color_PacxSem="";
$color_Honorarios="";
$color_tel1="";
$color_tel2="";
$color_Celular="";
$color_TelAsis="";
$color_FrecVis="";
$color_DifVis="";
$color_Inst="";
$color_PrefContct="";
$color_AceptaApoyo="";
$color_MedBot="";
$color_CompraDir="";
$color_LiderO="";


$valida=0;


for ($i = 0; $i < count($datos); $i++) {

//echo $datos[$i]['Sexo'];
//$tipo=$datos[$i]['Tipo'];

$queryValidaTipo="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=3 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Tipo']."'
order by orden, nombre";

$rsquery = sqlsrv_query($conn,$queryValidaTipo, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_tipo = sqlsrv_num_rows( $rsquery );

$queryValidaSexo="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=6 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Sexo']."'
order by orden, nombre";

$rsquerySexo = sqlsrv_query($conn,$queryValidaSexo, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_Sexo = sqlsrv_num_rows( $rsquerySexo );

$queryValidaSpecCartera="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=1 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Especialidad Cartera']."'
order by orden, nombre";

$rsquerySpecCartera = sqlsrv_query($conn,$queryValidaSpecCartera, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_SpecCartera = sqlsrv_num_rows( $rsquerySpecCartera );


$queryValidaSubEspec="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=4 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Sub Especialidad']."'
order by orden, nombre";

$rsquerySubEspec = sqlsrv_query($conn,$queryValidaSubEspec, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_SubEspec = sqlsrv_num_rows( $rsquerySubEspec );

$queryValidaCatAudit="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=5 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Categoria Audit']."'
order by orden, nombre";

$rsqueryCatAudit = sqlsrv_query($conn,$queryValidaCatAudit, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_CatAudit = sqlsrv_num_rows( $rsqueryCatAudit );


$queryValidaEstatus="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=11 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Estatus']."'
order by orden, nombre";

$rsqueryEstatus = sqlsrv_query($conn,$queryValidaEstatus, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_Estatus = sqlsrv_num_rows( $rsqueryEstatus );

$queryValidaPacxSem="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=8 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Pacientes por semana']."'
order by orden, nombre";

$rsqueryPacxSem = sqlsrv_query($conn,$queryValidaPacxSem, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_PacxSem = sqlsrv_num_rows( $rsqueryPacxSem );


$queryValidaHonoarios="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=7 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Honorarios']."'
order by orden, nombre";

$rsqueryHonorarios = sqlsrv_query($conn,$queryValidaHonoarios, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_Honorarios = sqlsrv_num_rows( $rsqueryHonorarios );

$queryValidaFrecVis="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=2 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Frecuencia de la visita']."'
order by orden, nombre";

$rsqueryFrecVis = sqlsrv_query($conn,$queryValidaFrecVis, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_FrecVis = sqlsrv_num_rows( $rsqueryFrecVis );

$queryValidaDifVis="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=9 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Dificultad de la visita']."'
order by orden, nombre";

$rsqueryDifVis = sqlsrv_query($conn,$queryValidaDifVis, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_DifVis = sqlsrv_num_rows( $rsqueryDifVis );


$queryValidaPrefContact="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=19 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Preferencia de contacto']."'
order by orden, nombre";

$rsqueryPrefConact = sqlsrv_query($conn,$queryValidaPrefContact, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_PefContact = sqlsrv_num_rows( $rsqueryPrefConact );

$queryValidaAceptaApoyo="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=15 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Acepta apoyo']."'
order by orden, nombre";

$rsqueryAceptaApoyo = sqlsrv_query($conn,$queryValidaAceptaApoyo, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_AceptaApoyo = sqlsrv_num_rows( $rsqueryAceptaApoyo );


$queryValidaMedBot="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=18 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Medico Botiquin']."'
order by orden, nombre";

$rsqueryMedBot = sqlsrv_query($conn,$queryValidaMedBot, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_MedBot = sqlsrv_num_rows( $rsqueryMedBot );

$queryValidaCompraDir="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=17 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Compra directa']."'
order by orden, nombre";

$rsqueryCompraDir = sqlsrv_query($conn,$queryValidaCompraDir, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_CompraDir = sqlsrv_num_rows( $rsqueryCompraDir );

$queryValidaLiderO="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=16 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Lider de Opinion']."'
order by orden, nombre";

$rsqueryLiderO = sqlsrv_query($conn,$queryValidaLiderO, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_LiderO = sqlsrv_num_rows( $rsqueryLiderO );

$queryValidaSpeaker="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=20 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Speaker']."'
order by orden, nombre";

$rsquerySpeaker = sqlsrv_query($conn,$queryValidaSpeaker, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_Speaker = sqlsrv_num_rows( $rsquerySpeaker );

$queryValidaTipoCons="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=21 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Tipo de Consulta']."'
order by orden, nombre";

$rsqueryTipoCons = sqlsrv_query($conn,$queryValidaTipoCons, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_TipoCons = sqlsrv_num_rows( $rsqueryTipoCons );

$queryValidaCuadroBas="select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
and codelistlib.table_nr=19 and codelistlib.list_nr=14 and CODELIST.REC_STAT = 0 
and  codelist.name =  '".$datos[$i]['Cuadro Basico']."'
order by orden, nombre";

$rsqueryCuadroBas = sqlsrv_query($conn,$queryValidaCuadroBas, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_CuadroBas = sqlsrv_num_rows( $rsqueryCuadroBas );




$queryValidaRepre="SELECT USER_SNR FROM USERS WHERE USER_NR='".$datos[$i]['Representante']."' AND REC_STAT=0";

$rsqueryRepre = sqlsrv_query($conn,$queryValidaRepre, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_Repre = sqlsrv_num_rows( $rsqueryRepre );


$queryValidaCP="select C.CITY_SNR
from city c, DISTRICT d, STATE e, BRICK b
where c.zip = '".$datos[$i]['C.P']."' 
and c.REC_STAT = 0 
and c.DISTR_SNR = d.DISTR_SNR
and c.STATE_SNR = e.STATE_SNR
and c.BRICK_SNR = b.BRICK_SNR
order by c.name";

$rsqueryCP = sqlsrv_query($conn,$queryValidaCP, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_CP = sqlsrv_num_rows( $rsqueryCP );









if($count_tipo == 0){
  $color_tipo="red";
  $datos[$i]['Tipo']="DATO INCORRECTO";
  $valida++;

}

if($datos[$i]['Apellido Paterno']==""){
  $color_apat="red";
  $datos[$i]['Apellido Paterno']="CAMPO OBLIGATORIO";
  $valida++;
}

if($datos[$i]['Apellido Materno']==""){
  $color_amat="red";
  $datos[$i]['Apellido Materno']="CAMPO OBLIGATORIO";
  $valida++;

}

if($datos[$i]['Nombre(s)']==""){
  $color_nom="red";
  $datos[$i]['Nombre(s)']="CAMPO OBLIGATORIO";
  $valida++;

}


if($datos[$i]['Sexo']==""){

  $datos[$i]['Sexo']="00000000-0000-0000-0000-000000000000";
  
}else if($count_Sexo == 0){
  $color_sexo="red";
  $datos[$i]['Sexo']="DATO INCORRECTO";
  $valida++;
}



if($datos[$i]['Especialidad Cartera']==""){


  $datos[$i]['Especialidad Cartera']="CAMPO OBLIGATORIO";
  $color_SpecCartera="red";
  $valida++;
  
}else if($count_SpecCartera == 0){
  $color_SpecCartera="red";
  $datos[$i]['Especialidad Cartera']="DATO INCORRECTO";
  $valida++;
}


if($datos[$i]['Sub Especialidad']==""){

  $datos[$i]['Sub Especialidad']="00000000-0000-0000-0000-000000000000";
  
}else if($count_SubEspec == 0){
  $color_SubEspec="red";
  $datos[$i]['Sub Especialidad']="DATO INCORRECTO";
  $valida++;
}


if($datos[$i]['Cedula']==""){

  $color_Cedula="red";
  $datos[$i]['Cedula']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if(strlen($datos[$i]['Cedula']) > 8){
  $color_Cedula="red";
  $datos[$i]['Cedula']="CEDULA MAYOR A 8 CARACTERES";
  $valida++;
}else if(strlen($datos[$i]['Cedula']) < 7){
  $color_Cedula="red";
  $datos[$i]['Cedula']="CEDULA MENOR A 7 CARACTERES";
  $valida++;

}


if($datos[$i]['Categoria Audit']==""){

  $datos[$i]['Categoria Audit']="00000000-0000-0000-0000-000000000000";
  
}else if($count_CatAudit == 0){
  $color_CatAudit="red";
  $datos[$i]['Categoria Audit']="DATO INCORRECTO";
  $valida++;
}

if($datos[$i]['Estatus']==""){

  $color_Estatus="red";
  $datos[$i]['Estatus']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if($count_Estatus == 0){
  $color_Estatus="red";
  $datos[$i]['Estatus']="DATO INCORRECTO";
  $valida++;
}

if($datos[$i]['Pacientes por semana']==""){

  $color_PacxSem="red";
  $datos[$i]['Pacientes por semana']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if($count_PacxSem == 0){
  $color_PacxSem="red";
  $datos[$i]['Pacientes por semana']="DATO INCORRECTO";
  $valida++;
}

if($datos[$i]['Honorarios']==""){

  $color_Honorarios="red";
  $datos[$i]['Honorarios']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if($count_Honorarios == 0){
  $color_Honorarios="red";
  $datos[$i]['Honorarios']="DATO INCORRECTO";
  $valida++;
}


if($datos[$i]['Tel1']==""){

  $color_Tel1="red";
  $datos[$i]['Tel1']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if(strlen($datos[$i]['Tel1']) < 10){
  $color_Tel1="red";
  $datos[$i]['Tel1']="TEL CON MENOS DE 10 DIGITOS";
  $valida++;
}else if(strlen($datos[$i]['Tel1']) > 10){
  $color_Tel1="red";
  $datos[$i]['Tel1']="TEL MAYOR A 10 DIGITOS";
  $valida++;

}


 if(strlen($datos[$i]['Tel2']) < 10){
  $color_tel2="red";
  $datos[$i]['Tel2']="TEL2 CON MENOS DE 10 DIGITOS";
  $valida++;
}else if(strlen($datos[$i]['Tel2']) > 10){
  $color_tel2="red";
  $datos[$i]['Tel2']="TEL2 MAYOR A 10 DIGITOS";
  $valida++;

}

if(strlen($datos[$i]['Celular']) < 10){
  $color_Celular="red";
  $datos[$i]['Celular']="CELULAR CON MENOS DE 10 DIGITOS";
  $valida++;
}else if(strlen($datos[$i]['Celular']) > 10){
  $color_Celular="red";
  $datos[$i]['Celular']="CELULAR MAYOR A 10 DIGITOS";
  $valida++;

}


if(strlen($datos[$i]['Telefono del asistente']) < 10){
  $color_TelAsis="red";
  $datos[$i]['Telefono del asistente']="TELEFONO CON MENOS DE 10 DIGITOS";
  $valida++;
}else if(strlen($datos[$i]['Celular']) > 10){
  $color_TelAsis="red";
  $datos[$i]['Telefono del asistente']="TELEFONO MAYOR A 10 DIGITOS";
  $valida++;

}


if($datos[$i]['Frecuencia de la visita']==""){

  $color_FrecVis="red";
  $datos[$i]['Frecuencia de la visita']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if($count_FrecVis == 0){
  $color_FrecVis="red";
  $datos[$i]['Frecuencia de la visita']="DATO INCORRECTO";
  $valida++;
}


if($datos[$i]['Dificultad de la visita']==""){

  $datos[$i]['Dificultad de la visita']="00000000-0000-0000-0000-000000000000";
  
}else if($count_DifVis == 0){
  $color_DifVis="red";
  $datos[$i]['Dificultad de la visita']="DATO INCORRECTO";
  $valida++;
}

if($datos[$i]['Calle']==""){

  $color_Calle="red";
  $datos[$i]['Calle']="CAMPO OBLIGATORIO";
  $valida++;
  
}

if($datos[$i]['C.P']==""){

  $color_CP="red";
  $datos[$i]['C.P']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if($count_CP==0){

  $color_CP="red";
  $datos[$i]['C.P']="CP NO EXISTE EN LA BASE";
  $valida++;

}

if($datos[$i]['Colonia']==""){

  $color_Colonia="red";
  $datos[$i]['Colonia']="CAMPO OBLIGATORIO";
  $valida++;
  
}

if($datos[$i]['Ciudad']==""){

  $color_Ciudad="red";
  $datos[$i]['Ciudad']="CAMPO OBLIGATORIO";
  $valida++;
  
}

if($datos[$i]['Estado']==""){

  $color_Estado="red";
  $datos[$i]['Estado']="CAMPO OBLIGATORIO";
  $valida++;
  
}



if($datos[$i]['Preferencia de contacto']==""){

  $datos[$i]['Preferencia de contacto']="00000000-0000-0000-0000-000000000000";
  
}else if($count_PefContact == 0){
  $color_PrefContct="red";
  $datos[$i]['Preferencia de contacto']="DATO INCORRECTO";
  $valida++;
}

if($datos[$i]['Acepta apoyo']==""){

  $datos[$i]['Acepta apoyo']="00000000-0000-0000-0000-000000000000";
  
}else if($count_AceptaApoyo == 0){
  $color_AceptaApoyo="red";
  $datos[$i]['Acepta apoyo']="DATO INCORRECTO";
  $valida++;
}


if($datos[$i]['Medico Botiquin']==""){

  $color_FrecVis="red";
  $datos[$i]['Medico Botiquin']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if($count_MedBot == 0){
  $color_MedBot="red";
  $datos[$i]['Medico Botiquin']="DATO INCORRECTO";
  $valida++;
}


if($datos[$i]['Compra directa']==""){

  $color_CompraDir="red";
  $datos[$i]['Compra directa']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if($count_CompraDir == 0){
  $color_CompraDir="red";
  $datos[$i]['Compra directa']="DATO INCORRECTO";
  $valida++;
}

if($datos[$i]['Lider de Opinion']==""){

  $color_LiderO="red";
  $datos[$i]['Lider de Opinion']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if($count_LiderO == 0){
  $color_LiderO="red";
  $datos[$i]['Lider de Opinion']="DATO INCORRECTO";
  $valida++;
}


if($datos[$i]['Speaker']==""){

  $datos[$i]['Speaker']="00000000-0000-0000-0000-000000000000";
  
}else if($count_Speaker == 0){
  $color_Speaker="red";
  $datos[$i]['Speaker']="DATO INCORRECTO";
  $valida++;
}


if($datos[$i]['Tipo de Consulta']==""){

  $color_TipoCons="red";
  $datos[$i]['Tipo de Consulta']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if($count_TipoCons == 0){
  $color_TipoCons="red";
  $datos[$i]['Tipo de Consulta']="DATO INCORRECTO";
  $valida++;
}


if($datos[$i]['Representante']==""){

  $color_Repre="red";
  $datos[$i]['Representante']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if($count_Repre == 0){
  $color_Repre="red";
  $datos[$i]['Representante']="DATO INCORRECTO";
  $valida++;
}

if($datos[$i]['Cuadro Basico']==""){

  $color_CuadroBas="red";
  $datos[$i]['Cuadro Basico']="CAMPO OBLIGATORIO";
  $valida++;
  
}else if($count_MedBot == 0){
  $color_CuadroBas="red";
  $datos[$i]['Cuadro Basico']="DATO INCORRECTO";
  $valida++;
}


if($datos[$i]['Comentarios Generales']==""){

  $color_ComentariosGen="red";
  $datos[$i]['Comentarios Generales']="CAMPO OBLIGATORIO";
  $valida++;
  
}


$queryValidaInst="select top 1
i.inst_snr, it.name as tipo, 
i.name as nombre, 
i.STREET1 as calle, 
city.name as colonia, 
d.NAME as delegacion, 
state.NAME as estado, 
city.zip as cp, 
u.user_nr as ruta, 
u.user_snr 
from inst i 
left outer join USER_TERRIT ut on i.INST_SNR = ut.inst_SNR and ut.REC_STAT = 0 
left outer join INST_TYPE it on it.INST_TYPE = i.INST_TYPE 
left outer join city on city.CITY_SNR = i.CITY_SNR 
left outer join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR 
left outer join STATE on state.STATE_SNR = city.STATE_SNR 
left outer join BRICK bri on bri.BRICK_SNR = city.BRICK_SNR 
left outer join USERS u on u.user_snr = ut.user_snr 
left outer join CODELIST estatus on estatus.clist_snr = i.status_snr 
where u.user_snr in (SELECT USER_SNR FROM USERS WHERE USER_TYPE=4 AND REC_STAT=0) 
and i.rec_stat = 0 and estatus.name = 'ACTIVO'
and  i.STREET1='".$datos[$i]['Calle']."'
and city.name='".$datos[$i]['Colonia']."'
and state.NAME='".$datos[$i]['Estado']."'
and city.zip='".$datos[$i]['C.P']."'
and u.user_nr = '".$datos[$i]['Representante']."'
order by nombre";

$rsqueryInst = sqlsrv_query($conn,$queryValidaInst, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$count_Inst = sqlsrv_num_rows( $rsqueryInst );





echo "<script>  $('#tblCargaDatosPersona tbody').append('<tr ><td style=\"color:$color_tipo\" >" . $datos[$i]['Tipo'] . "</td> <td style=\"color:$color_apat\">" . $datos[$i]['Apellido Paterno'] . "</td><td style=\"color:$color_amat\">" . $datos[$i]['Apellido Materno'] . "</td><td style=\"color:$color_nom\">" . $datos[$i]['Nombre(s)'] . "</td><td style=\"color:$color_sexo\">" . $datos[$i]['Sexo'] . "</td><td style=\"color:$color_SpecCartera\" >".$datos[$i]['Especialidad Cartera']."</td><td style=\"color:$color_SubEspec\" >".$datos[$i]['Sub Especialidad']."</td><td style=\"color:$color_Cedula\" >".$datos[$i]['Cedula']."</td><td style=\"color:$color_CatAudit\" >" . $datos[$i]['Categoria Audit'] . "</td><td style=\"color:$color_Estatus\" >" . $datos[$i]['Estatus'] . "</td><td style=\"color:$color_PacxSem\" >".$datos[$i]['Pacientes por semana']."</td><td style=\"color:$color_Honorarios\" >".$datos[$i]['Honorarios']."</td><td>".$datos[$i]['Fecha de nacimiento']."</td><td style=\"color:$color_Tel1\" >".$datos[$i]['Tel1']."</td><td style=\"color:$color_tel2\" >".$datos[$i]['Tel2']."</td><td style=\"color:$color_Celular\" >".$datos[$i]['Celular']."</td><td>".$datos[$i]['Email1']."</td><td>".$datos[$i]['Email2']."</td><td>".$datos[$i]['Nombre del asistente']."</td><td style=\"color:$color_TelAsis\" >".$datos[$i]['Telefono del asistente']."</td><td>".$datos[$i]['Email del asistente']."</td><td style=\"color:$color_FrecVis\" >".$datos[$i]['Frecuencia de la visita']."</td><td style=\"color:$color_DifVis\" >".$datos[$i]['Dificultad de la visita']."</td><td style=\"color:$color_Inst\" >".$datos[$i]['Nombre de la Institucion']."</td><td style=\"color:$color_Calle\" >".$datos[$i]['Calle']."</td><td style=\"color:$color_Inst\" >".$datos[$i]['Num Interior']."</td><td style=\"color:$color_CP\" >".$datos[$i]['C.P']."</td><td style=\"color:$color_Colonia\" >".$datos[$i]['Colonia']."</td><td style=\"color:$color_Ciudad\" >".$datos[$i]['Ciudad']."</td><td style=\"color:$color_Estado\" >".$datos[$i]['Estado']."</td><td style=\"color:$color_Inst\" >".$datos[$i]['Nombre del Brick']."</td><td style=\"color:$color_Inst\" >".$datos[$i]['Brick']."</td><td>".$datos[$i]['Full nombre hospital']."</td><td style=\"color:$color_PrefContct\" >".$datos[$i]['Preferencia de contacto']."</td><td style=\"color:$color_AceptaApoyo\">".$datos[$i]['Acepta apoyo']."</td><td>".$datos[$i]['¿Porque?']."</td><td style=\"color:$color_MedBot\" >".$datos[$i]['Medico Botiquin']."</td><td style=\"color:$color_CompraDir\" >".$datos[$i]['Compra directa']."</td><td style=\"color:$color_LiderO\" >".$datos[$i]['Lider de Opinion']."</td><td style=\"color:$color_Speaker\" >".$datos[$i]['Speaker']."</td><td style=\"color:$color_TipoCons\"  >".$datos[$i]['Tipo de Consulta']."</td><td style=\"color:$color_Repre\" >".$datos[$i]['Representante']."</td><td style=\"color:$color_CuadroBas\" >".$datos[$i]['Cuadro Basico']."</td> <td  >".$datos[$i]['Objetivo corto']."</td> <td  >".$datos[$i]['Objetivo largo']."</td><td style=\"color:$color_ComentariosGen\"  >".$datos[$i]['Comentarios Generales']."</td></tr>');</script>";

if($datos[$i]['Nombre de la Institucion']==""){
  $datos[$i]['Nombre de la Institucion']="SIN NOMBRE";

}


}
$cadena =json_encode($datos);



echo "<script>
$(\"#hdnTextValidaCargaPersona\").val('".$valida."');
$(\"#hdnTextDatosCargaPersona\").val('".$cadena."');
</script>";



?>

