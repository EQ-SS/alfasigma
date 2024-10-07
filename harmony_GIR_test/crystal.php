<?php 
// inicializo variables 
$my_report = "F:inetpub/wwwroot/SVAnywhere15_TORRENT_TEST/Analysis/UserAnalysis/Lst_Medicos_Torrent.rpt"; // Ruta fisica al reporte en el servidor 
$exp_pdf = "F:/www/smart_nuevo/Lst_Medicos_Torrent.pdf"; // ruta fisica donde se guardara el PDF resultado en el servidor 
// Instancio el Object Factory de Crystal Reports 
$ObjectFactory = New COM("CrystalReports.ObjectFactory"); 
// Creo una instancia del Componente de Diseñador de Crystal Reports 
$crapp = $ObjectFactory->CreateObject("CrystalDesignRuntime.Application"); 
// Mando abrir mi reporte 
$creport = $crapp->OpenReport($my_report, 1); 
// Mi reporte tiene ODBC establecido, por lo cual unicamente le indico user y pass. 
$creport->Database->Tables->Item(1)->ConnectionProperties["User ID"] = "sa"; 
$creport->Database->Tables->Item(1)->ConnectionProperties["Password"] = "saf"; 
// Utilizare una formula de selección de Registros 
//print_r( com_print_typeinfo($creport)); 
$creport->FormulaSyntax = 0; 
$param = "'".$_GET['n']."'"; // Valor de parametro, para este ejemplo estatico pero bien puedes leerlo de un $_POST[], $_GET[] o asignar un valor resultado de una consulta a BD 

//Con Enable Parameter Promting evito que lanze el formulario de captura de parametros ya que el browser del usuario no puede interactuar con el escritorio o el componente que crea el formulario. 
$creport->EnableParameterPrompting = 0; 
//Inserto los valores en los campos de parametro del reporte 
$creport->ParameterFields(1)->AddCurrentValue($param); 
$zz = $creport->ParameterFields(1)->SetCurrentValue($param); 
$creport->ParameterFields(2)->AddCurrentValue($param); 
$zz1 = $creport->ParameterFields(2)->SetCurrentValue($param); 

//$creport->DiscardSavedData; 
$creport->ReadRecords(); 
$creport->ExportOptions->DiskFileName = $exp_pdf; 
$creport->ExportOptions->PDFExportAllPages = true; 
$creport->ExportOptions->DestinationType = 1; 
$creport->ExportOptions->FormatType = 31; 
// Exporto el reporte 
$creport->Export(false); 
// Limpio las variables 
$creport = null; 
$crapp = null; 
$ObjectFactory = null; 
// truco para leer el PDF resultado y enviarlo al navegador Web para descargar o abrir en el equipo del usuario 
$len = filesize($exp_pdf); 
header("Content-type: application/pdf"); 
header("Content-Length: $len"); 
header("Content-Disposition: attachment; filename=Descripcion.pdf"); 
// Con esto leeo el archivo para que en conjuncion con el envio de headers el navegador del cliente interprete el contenido del archivo. 
readfile($exp_pdf); 
exit; 
?> 