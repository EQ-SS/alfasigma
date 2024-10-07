<?php
include "../conexion.php";

$valida=$_POST['valida'];
$datos=$_POST['datos'];

$datos=json_decode($datos,true);


function regresaSrn($table,$lista,$i,$nombre,$conn){

    $id = sqlsrv_fetch_array(sqlsrv_query($conn, "select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden
    from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 
    and codelistlib.table_nr='".$table."' and codelistlib.list_nr='".$lista."' and CODELIST.REC_STAT = 0 
    and  codelist.name =  '".$nombre."'
    order by orden, nombre"))['id'];
 
   
    return $id;
}

    //se llena arreglo con ids 
for ($i = 0; $i < count($datos); $i++) {

    

    $datos[$i]['Tipo']=regresaSrn(19,3,$i,$datos[$i]['Tipo'],$conn);

    if($datos[$i]['Sexo']!='00000000-0000-0000-0000-000000000000'){
        $datos[$i]['Sexo']=regresaSrn(19,6,$i,$datos[$i]['Sexo'],$conn);
    }

    $datos[$i]['Especialidad Cartera']=regresaSrn(19,1,$i,$datos[$i]['Especialidad Cartera'],$conn);


    if($datos[$i]['Sub Especialidad']!='00000000-0000-0000-0000-000000000000'){
        $datos[$i]['Sub Especialidad']=regresaSrn(19,4,$i,$datos[$i]['Sub Especialidad'],$conn);
    }

    if($datos[$i]['Categoria Audit']!='00000000-0000-0000-0000-000000000000'){
        $datos[$i]['Categoria Audit']=regresaSrn(19,5,$i,$datos[$i]['Categoria Audit'],$conn);
    }

    $datos[$i]['Estatus']=regresaSrn(19,11,$i,$datos[$i]['Estatus'],$conn);

    $datos[$i]['Pacientes por semana']=regresaSrn(19,8,$i,$datos[$i]['Pacientes por semana'],$conn);

    $datos[$i]['Honorarios']=regresaSrn(19,7,$i,$datos[$i]['Honorarios'],$conn);

    $datos[$i]['Frecuencia de la visita']=regresaSrn(19,2,$i,$datos[$i]['Frecuencia de la visita'],$conn);

    if($datos[$i]['Dificultad de la visita']!='00000000-0000-0000-0000-000000000000'){
        $datos[$i]['Dificultad de la visita']=regresaSrn(19,9,$i,$datos[$i]['Dificultad de la visita'],$conn);
    }

    if($datos[$i]['Preferencia de contacto']!='00000000-0000-0000-0000-000000000000'){
        $datos[$i]['Preferencia de contacto']=regresaSrn(19,19,$i,$datos[$i]['Preferencia de contacto'],$conn);
    }

    if($datos[$i]['Acepta apoyo']!='00000000-0000-0000-0000-000000000000'){
        $datos[$i]['Acepta apoyo']=regresaSrn(19,15,$i,$datos[$i]['Acepta apoyo'],$conn);
    }

    $datos[$i]['Medico Botiquin']=regresaSrn(19,18,$i,$datos[$i]['Medico Botiquin'],$conn);

    $datos[$i]['Compra directa']=regresaSrn(19,17,$i,$datos[$i]['Compra directa'],$conn);

    $datos[$i]['Lider de Opinion']=regresaSrn(19,16,$i,$datos[$i]['Lider de Opinion'],$conn);

    if($datos[$i]['Speaker']!='00000000-0000-0000-0000-000000000000'){
        $datos[$i]['Speaker']=regresaSrn(19,20,$i,$datos[$i]['Speaker'],$conn);
    }

    $datos[$i]['Tipo de Consulta']=regresaSrn(19,21,$i,$datos[$i]['Tipo de Consulta'],$conn);

    $datos[$i]['Cuadro Basico']=regresaSrn(19,14,$i,$datos[$i]['Cuadro Basico'],$conn);

}

//end se llena arreglo con ids

for ($i = 0; $i < count($datos); $i++) {
//satrt guardar personas

    $rsIdPers = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPers from PERSON where PERS_SNR = '00000000-0000-0000-0000-000000000000'"));
	$idPersona = $rsIdPers['idPers'];
	
    $nr = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(nr)+1 as nr from person"))["nr"];
				$queryPerson = "insert into PERSON (
					PERS_SNR,
					PERSTYPE_SNR,
					FNAME,
					LNAME,
					MOTHERS_LNAME,
					SEX_SNR,
					SPEC_SNR,
					SUBSPEC_SNR,
					PROF_ID,
					CATEGORY_SNR,
					STATUS_SNR,
					PATPERWEEK_SNR, 
					FEE_TYPE_SNR,
					BIRTHDATE,
					TEL1,
					TEL2,
					MOBILE,
					EMAIL1,
					EMAIL2,
					ASSISTANT_NAME,
					ASSISTANT_TEL,
					ASSISTANT_EMAIL,
					FRECVIS_SNR,
					DIFFVIS_SNR,
					HOSPITAL_NAME,
					PREFERRED_CONTACT_SNR,
					ACCEPT_SUPPORT_SNR,
					ACCEPT_SUPPORT_INFO,
					AID_KIT_SNR,
					DIRECT_PURCHASE_SNR,
					KOL_SNR,
					SPEAKER_SNR,
					CONSULTATION_TYPE_SNR,
					INFO,
					INFO_SHORTTIME,
					INFO_LONGTIME,
					REC_STAT,
					CREATION_TIMESTAMP,
					SYNC,
					NR,
					BASIC_LIST_SNR
				) values(
					'$idPersona',
					'".$datos[$i]['Tipo']."',
					'".$datos[$i]['Nombre(s)']."',
					'".$datos[$i]['Apellido Paterno']."',
					'".$datos[$i]['Apellido Materno']."',
					'".$datos[$i]['Sexo']."',
					'".$datos[$i]['Especialidad Cartera']."',
					'".$datos[$i]['Sub Especialidad']."',
					'".$datos[$i]['Cedula']."',
					'".$datos[$i]['Categoria Audit']."',
					'".$datos[$i]['Estatus']."',
					'".$datos[$i]['Pacientes por semana']."',
					'".$datos[$i]['Honorarios']."',
					'".$datos[$i]['Fecha de nacimiento']."',
					'".$datos[$i]['Tel1']."',
					'".$datos[$i]['Tel2']."',
					'".$datos[$i]['Celular']."',
					'".$datos[$i]['Email1']."',
					'".$datos[$i]['Email2']."',
					'".$datos[$i]['Nombre del asistente']."',
					'".$datos[$i]['Telefono del asistente']."',
					'".$datos[$i]['Email del asistente']."',
					'".$datos[$i]['Frecuencia de la visita']."',
					'".$datos[$i]['Dificultad de la visita']."',
					'".$datos[$i]['Full nombre hospital']."',
					'".$datos[$i]['Preferencia de contacto']."',
					'".$datos[$i]['Acepta apoyo']."',
					'".$datos[$i]['¿Porque?']."',
					'".$datos[$i]['Medico Botiquin']."',
					'".$datos[$i]['Compra directa']."',
					'".$datos[$i]['Lider de Opinion']."',
					'".$datos[$i]['Speaker']."',
					'".$datos[$i]['Tipo de Consulta']."',
					'".$datos[$i]['Comentarios Generales']."',
					'".$datos[$i]['Objetivo corto']."',
					'".$datos[$i]['Objetivo largo']."',
					'0',
					getdate(),
					0,
					'$nr',
					'".$datos[$i]['Cuadro Basico']."'
				)";
				
				if(! sqlsrv_query($conn, $queryPerson)){
					echo "inserta persona: ".$queryPerson."<br><br>";
				}


//end guardar personas 

//start PERSLOCWORK
            $rsPwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPwork from PERSON_APPROVAL where PERS_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'"));
            $idPwork = $rsPwork['idPwork'];


            //regresa id inst 

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

if($count_Inst>0){

    //existe institucion


    $rsidInst = sqlsrv_fetch_array(sqlsrv_query($conn, "select top 1
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
            order by nombre"));
            $idInst = $rsidInst['inst_snr'];



}else{

    //inserta Institucion 
    $idInst = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idInst from INST where INST_SNR = '00000000-0000-0000-0000-000000000000'"))['idInst'];
    $nr = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(NR)+1 as nr from INST"))['nr'];

    $city = sqlsrv_fetch_array(sqlsrv_query($conn, "select C.CITY_SNR
    from city c, DISTRICT d, STATE e, BRICK b
    where c.zip = '".$datos[$i]['C.P']."' 
    and c.REC_STAT = 0 
    and c.DISTR_SNR = d.DISTR_SNR
    and c.STATE_SNR = e.STATE_SNR
    and c.BRICK_SNR = b.BRICK_SNR
    order by c.name
    "))['CITY_SNR'];




						$queryInst = "insert into INST (
							INST_SNR,
							REC_STAT,
							NAME,
							TYPE_SNR,
							SUBTYPE_SNR,
							FORMAT_SNR,
							NR,
							INST_TYPE,
							CITY_SNR,
							STREET1,
							NUM_EXT,
							FRECVIS_SNR,
							SYNC,
							STATUS_SNR
						) values (
							'$idInst',
							0,
							'".$datos[$i]['Nombre de la Institucion']."',
							'D822138D-7014-459F-9518-9CB6DC33BFA5',
							'DD12D106-4EBF-4FB4-AF50-70CCD3229EC0',
							'B0D12DCC-13E7-4A00-9E0D-847BA7891E0A',
							'".$nr."',
							'3',
							'".$city."',
							'".$datos[$i]['Calle']."',							
							'".$datos[$i]['Num Interior']."',	
							'310C2740-3A33-494A-9979-5A65607B4044',
							0,
							'C1141A15-E7AD-4099-A8D4-26C571298B21'
							)
							";
						if(! sqlsrv_query($conn, $queryInst)){
							echo $queryInst."<br>";
						}


                        $idUsuario = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT USER_SNR FROM USERS WHERE USER_NR='".$datos[$i]['Representante']."' AND REC_STAT=0"))['USER_SNR'];
                        $qUserTerrit = "insert into user_territ (
                            UTER_SNR,
                            INST_SNR,
                            USER_SNR,
                            REC_STAT,
                            SYNC,
                            CREATION_TIMESTAMP) values (
                            NEWID(),
                            '".$idInst."',
                            '".$idUsuario."',
                            0,
                            0,
                            getdate()
                            )";
                        if(! sqlsrv_query($conn, $qUserTerrit)){
                            echo $qUserTerrit."<br>";
                        }


}


            

            //end regresa id inst

            $consultorio='';
            $piso='';
            $torre=''; 
            $departamento='';
        $queryPLW = "insert into PERSLOCWORK( 
            PWORK_SNR, 
            PERS_SNR,	
            INST_SNR,	
            NUM_INT,
            SYNC,
            REC_STAT,
            OFFICE,
            FLOOR,
            TOWER,
            DEPARTMENT,
            CREATION_TIMESTAMP
            ) values ( 
            '$idPwork',
            '$idPersona',
            '$idInst',
            '".$datos[$i]['Num Interior']."',
            0,
            0,
            '$consultorio',
            '$piso',
            '$torre',
            '$departamento',
            getdate()
            ) ";

            $queryValidaExistPlw = "select pwork_snr from perslocwork where pwork_snr = '".$idPwork."' ";
				$rsExistPlw = sqlsrv_query($conn, $queryValidaExistPlw, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if(sqlsrv_num_rows($rsExistPlw) == 0 ){//es un plw nuevo
					if (! sqlsrv_query($conn, $queryPLW)) {
						echo "Error: queryPLW ::: ".$queryPLW."<br>";
					} else {
						//echo $queryPLW."<br>";
					}
				} else {
					$queryUpdatePlw = "update perslocwork set rec_stat=0, sync=0 where pwork_snr='".$idPwork."' "; 
					if(! sqlsrv_query($conn, $queryUpdatePlw)){
						echo "Error: queryUpdatePlw ::: ".$queryUpdatePlw."<br><br>";
					}
				}

    //end PERSLOCWORK


    // start user Territ
    $rsidUsuario = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT USER_SNR FROM USERS WHERE USER_NR='".$datos[$i]['Representante']."'"));
    $idUsuario = $rsidUsuario['USER_SNR'];

    $queryPSW = "insert into PERS_SREP_WORK (
        PERSREP_SNR,
        PWORK_SNR,
        USER_SNR,
        PERS_SNR,
        INST_SNR,
        SYNC,
        REC_STAT,
        CREATION_TIMESTAMP
    ) values (
        NEWID(),
        '$idPwork',
        '$idUsuario',
        '$idPersona',
        '$idInst',
        0,
        0,
        getdate()
    ) ";
    
if(! sqlsrv_query($conn, $queryPSW)){
    echo "Error: queryPSW ::: ".$queryPSW."<br>";
}

$queryUT = "select user_snr,INST_SNR,UTER_SNR,REC_STAT from user_territ where user_snr = '".$idUsuario."' and INST_snr = '".$idInst."'";
				$rsUT = sqlsrv_query($conn, $queryUT, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				$actualizaUT = "";
				if(sqlsrv_num_rows($rsUT) > 0){
					$idUT = sqlsrv_fetch_array($rsUT)['UTER_SNR'];
					if($rsUT['REC_STAT'] != 0){
						$actualizaUT = "update user_territ SET REC_STAT = 0 WHERE UTER_SNR = '".$idUT."'";
					}
				}else{
					$actualizaUT = "insert into user_territ (
							UTER_SNR,
							INST_SNR,
							USER_SNR,
							REC_STAT,
							SYNC,
							CREATION_TIMESTAMP
						) values (
							NEWID(),
							'$idInst',
							'$idUsuario',
							0,
							0,
							getdate()
						)";
				}
				
				if($actualizaUT != ""){
					if(! sqlsrv_query($conn, $actualizaUT)){
						echo "Error: actualizaUT ::: ".$actualizaUT."<br><br>";
					}
					
				}


    //end user territ


}

echo "<script>$('#ModalCargaDatosPersona').modal('hide');
$('#btnActualizarPers').click();</script>";






?>