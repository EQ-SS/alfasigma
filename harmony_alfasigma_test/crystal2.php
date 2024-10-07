<?php

//- Variables - for your RPT and PDF
echo "Print Report Test";
//$my_report = "D:\\Folder1\\SubFolder1\\Report.rpt"; // rpt source file
$my_report = "F:\\inetpub\\wwwroot\\SVAnywhere15_TORRENT_TEST\\Analysis\\UserAnalysis\\Lst_Medicos_Torrent.rpt"; // Ruta fisica al reporte en el servidor
//$my_pdf = "D:\\Folder1\\SubFolder1\\Report.pdf"; // RPT export to pdf file
$my_pdf = "F:\\www\\smart_nuevo\\Lst_Medicos_Torrent.pdf"; // ruta fisica donde se guardara el PDF resultado en el servidor 
//-Create new COM object-depends on your Crystal Report version
$ObjectFactory= new COM("CrystalReports.ObjectFactory") or die ("Error on load"); // call COM port
$crapp = $ObjectFactory-> CreateObject("CrystalDesignRunTime.Application"); // create an instance for Crystal
$creport = $crapp->OpenReport($my_report, 1); // call rpt report

// to refresh data before

//- Set database logon info - must have
$creport->Database->Tables(1)->SetLogOnInfo("localhost", "TORRENT_MX_PH_NET_TEST", "sa", "saf");

//- field prompt or else report will hang - to get through
$creport->EnableParameterPrompting = 0;

//- DiscardSavedData - to refresh then read records
$creport->DiscardSavedData;
$creport->ReadRecords();

    
//export to PDF process
$creport->ExportOptions->DiskFileName=$my_pdf; //export to pdf
$creport->ExportOptions->PDFExportAllPages=true;
$creport->ExportOptions->DestinationType=1; // export to file
$creport->ExportOptions->FormatType=31; // PDF type
$creport->Export(false);

//------ Release the variables ------
$creport = null;
$crapp = null;
$ObjectFactory = null;

//------ Embed the report in the webpage ------
print "<embed src=\"".$my_pdf."\" width=\"100%\" height=\"100%\">";

    
    
?>