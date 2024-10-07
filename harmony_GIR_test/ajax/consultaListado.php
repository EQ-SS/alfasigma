<?php

    include "../conexion.php";
    ini_set("memory_limit", "-1");
    ini_set("MAX_EXECUTION_TIME", "-1");
    header('Content-Type: text/html; charset=UTF-8');

    $sql = $_POST['queryListado'];

    // initilize all variable
    $params = $columns = $totalRecords = $data = array();

    $params = $_REQUEST;

    //define index of column
    /*
    $columns = array( 
        0 =>$orden,
        1 =>'PROF_ID',
        2 => 'PERSTYPE_SNR',
        3 => 'FNAME'
    );
    */

    $where = $sqlTot = $sqlRec = "";

    // check search value exist

    /*

    if( !empty($params['search']['value']) ) {   
        $where .=" AND ";
        $where .=" ( U.user_nr LIKE '".$params['search']['value']."%' ";    
        $where .=" OR U.user_nr LIKE '".$params['search']['value']."%' ";

        $where .=" OR U.user_nr LIKE '".$params['search']['value']."%' )";
    }
    */


    $sqlTot .= $sql;
    $sqlRec .= $sql;
    //concatenate search sql if value exist
    if(isset($where) && $where != '') {

        $sqlTot .= $where;
        $sqlRec .= $where;
    }


    /*
    $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   OFFSET ".$params['start']."  ROWS
    FETCH NEXT ".$params['length']." ROWS ONLY";
    */
    
    /*
    $sqlRec .="ORDER BY ".$orden;

    $sqlRec .=  " OFFSET ".$params['start']."  ROWS
    FETCH NEXT ".$params['length']." ROWS ONLY";
    */

 

    $sqlRec .=  "  OFFSET ".$params['start']."  ROWS
    FETCH NEXT ".$params['length']." ROWS ONLY";
 
 

    $params2 = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

    // echo $sqlTot;

    $queryTot = sqlsrv_query($conn, $sqlTot,$params2,$options); /* or die("database error:". mysqli_error($conn));*/
    if($queryTot===false){
        
        $errors=sqlsrv_errors();

        foreach($errors as $error){
            echo "Error: ".$error['message']."<br>";

        }

    }

    $totalRecords = sqlsrv_num_rows($queryTot);


    //echo $sqlRec;


    $queryRecords = sqlsrv_query($conn,$sqlRec,$params2,$options);/* or die("error to fetch employees data");*/

    //iterate on results row and create new index array of data
    $c=0;


    while( $row = sqlsrv_fetch_array($queryRecords) ) { 
        
        
        $data[]=$row;

        $c++;

    }

    $data=utf8_converter($data);


    $json_data = array(
        "draw"            => intval( $params['draw'] ),   
        "recordsTotal"    => intval( $totalRecords ),  
        "recordsFiltered" => intval($totalRecords),
        "data"            => $data,  // total data array
    );

    echo json_encode($json_data, JSON_UNESCAPED_UNICODE);
?>

