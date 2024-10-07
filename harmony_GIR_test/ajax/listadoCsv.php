<?php
include "../conexion.php";
ini_set("memory_limit", "-1");
ini_set("MAX_EXECUTION_TIME", "-1");


$sql=$_POST['query'];
$titulo=$_POST['titulos'];
$titulos=explode(",",$titulo);
$nombreListado=$_POST['nombreListado'];

$params2 = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

$queryRecords = sqlsrv_query($conn, $sql,$params2,$options);

    
   
        //El nombre del fichero tendrá el nombre 
        $ficheroCsv=$nombreListado."_".date("d-m-Y H_i_s").".csv";
        
        //Indicamos que vamos a tratar con un fichero CSV
        
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$ficheroCsv);
                
        // Recorremos la consulta SQL y lo mostramos

                    foreach(sqlsrv_field_metadata($queryRecords) as $field){
	
                       echo $field['Name']."\t";
                    
                    }

                    echo "\n";
            
           
            
        
        while( $fila = sqlsrv_fetch_array($queryRecords) ){
            $i=0;
            foreach($titulos as $elem) {


                    

                if ($elem === end($titulos)) {
                    echo utf8_encode($fila[$titulos[$i]])."\n";
                }else{
                    echo utf8_encode($fila[$titulos[$i]])."\t";
                }
                $i++;
            
                
            
            }
            

        }
        


    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;

    



?>