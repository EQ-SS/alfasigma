<?php
	include "../conexion.php";
	ini_set("memory_limit", "-1");
	ini_set("MAX_EXECUTION_TIME", "-1");

    $qMedicos=$_POST['query'];
    $numCol=$_POST['numCol'];
    $nombreListado="Listado_Historico_Farmacias";
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
    $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
    $reemplazar=array(" ", " ", " ", " ");
    $tam = array(100,100,100,300,350,150,200,250,350,350, 150,250,100,200,200,150,100,100,100,100, 100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100);
	$registrosPorPagina = 20;
    $tamTabla = array_sum($tam) + 20;
    $numPagina = 1;
    $registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
    
    $rsMedicosTotal = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
    
    $totalRegistros = sqlsrv_num_rows($rsMedicosTotal);

    $rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos));

    $paginas = ceil($totalRegistros / $registrosPorPagina);
        
    $tabla = '<table border="0">
        <tr>
            <td>
                <table>
                    <tr>
                        <td colspan="10" class="nombreReporte">LISTADO DE FARMACIAS VISITADAS HISTORICO POR CICLO</td>
                    </tr>
                    <tr>
                        <td colspan="10" class="clienteReporte">Torrent</td>
                    </tr>
                    <tr>
                        <td colspan="10" class="fechaReporte">Fecha: '. date("d/m/Y h:i:s") .'</td>
                    </tr>
                    
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <div id="divListadoMedicos">';
	$tabla .= '<table id="tblListadoMedicos" width="'.$tamTabla.'px" class="tablaReportes" >';
	$tabla .= '<thead><tr>';

    $i = 0;
    $k = 0;
    $cabeceras = array();
    foreach(sqlsrv_field_metadata($rsMedicos) as $field){
        $celda = columna($i)."4";
        if($i <= $numCol){
            $tabla .= '<td style="min-width:'.$tam[$i].'px;">'.utf8_encode($field['Name']).'</td>';
            $k = $i;
        }else{
            if($i % 2 == 0){
                $cabeceras[] = utf8_encode($field['Name']);
            }
        }
        $i++;
    }
    $k++;

    $i=1;
    while($regMedico = sqlsrv_fetch_array($rsMedicos)){
        if($i == 1){
            $k++;
            for($l = count($cabeceras)-1; $l >= 0; $l--){ 
                if ($regMedico[$cabeceras[$l]] != 'A'){
                    $tabla .= '<td style="min-width:'.$tam[$k].'px;">'.utf8_encode($regMedico[$cabeceras[$l]]).'</td>';
                    $k++;					
                }
            }
            $tabla .= '</tr></thead>';
            $tabla .= '<tbody style="height:345px;">';
        }
        $tabla .= '<tr>';

        $visitasArr = array();
        for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
            if($j <= 17){
                $tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
            }else{
                if($j % 2 != 0){
                    $visitasArr[] = $regMedico[$j];
                    $k=19;
                }
            }
        }
        
        for($m = count($visitasArr)-1; $m >= 0; $m--){
            if ($visitasArr[$m] != 9){
                $tabla .= '<td style="min-width:'.$tam[$k].'px;">'.$visitasArr[$m].'</td>';
                $k++;
            }
        }
        $tabla .= '</tr>';
        $i++;
    }

	$numRegs = $i - 1;
	$tabla .= '<table width="100%" id="tblPaginasListadoMedicos"><tr style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000;"><td align="center">';

	if($totalRegistros > $registrosPorPagina){
		$idsEnviar = str_replace("'","",$ids);
		if($numPagina > 1){
			$anterior = $numPagina - 1;
			//$tabla .= "<a href='#' onClick='nuevaPaginaListados(1,\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>inicio</a>&nbsp;&nbsp;";
			//$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$anterior.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>anterior</a>&nbsp;&nbsp;";
		}
		$antes = $numPagina-5;
		$despues = $numPagina+5;
		for($i=1;$i<=$paginas;$i++){
			if($i == $numPagina){
				$tabla .= $i."&nbsp;&nbsp;";
			}else{
				if($i > $despues || $i < $antes){
					//$tabla .= "<a href='#' onClick='nuevaPagina(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>".$i."</a>&nbsp;&nbsp;";
				}
			}
		}
		if($numPagina < $paginas){
			$siguiente = $numPagina + 1;
			//$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$siguiente.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>Siguiente</a>&nbsp;&nbsp;";
			//$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$paginas.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>Fin</a>&nbsp;&nbsp;";
		}
		//$tabla .= "Pag. ".$numPagina." de ".$paginas."&nbsp;&nbsp;&nbsp; Registros : ".$totalRegistros;
	}else{
		//$tabla .= "Registros : ".$totalRegistros;
	}						
	$tabla .= '</td></tr>
	<tr>
		<td colspan="10" class="derechosReporte">© Smart-Scale</td>
	</tr>
    </table>';
        echo $tabla;
    $row = $i+4;
    exit;
?>