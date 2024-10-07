<style>
    .tbl_th {
        background:#CCC; 
        color:#000;
    }
</style>

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

	//El nombre de un fichero xls
	$ficheroExcel=$nombreListado."_".date("d-m-Y H_i_s").".xls";
        
	//INDICAMOS QUE VAMOS A TRATAR CON UN ARCHIVO XLS
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=$ficheroExcel"); //Indica el nombre del archivo resultante
    header("Pragma: no-cache");
    header("Expires: 0");
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    echo $nombreListado."_".date("d-m-Y");
    // Recorremos la consulta SQL y lo mostramos
        echo "<table id=\"tbl_export_listado\">
                    <thead> 
                    <tr>";

                    foreach(sqlsrv_field_metadata($queryRecords) as $field){
	
                       echo "<th class=\"tbl_th\" >".$field['Name']."</th>";
                    
                    
                    }

                  echo "</tr>  
                  </thead>

                    <tbody> ";
                        
        
        while( $fila = sqlsrv_fetch_array($queryRecords) ){


            echo "<tr>";

            for($i=0;$i<count($titulos);$i++){

                echo " <td > ".$fila[$titulos[$i]]."</td>";

            }
                           
                echo "</tr>";

            

        }
        

      echo "  </tbody>
                    
  </table>";
   
    exit;
?>