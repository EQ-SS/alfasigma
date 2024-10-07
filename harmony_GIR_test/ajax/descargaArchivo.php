<?php
    $file = $_GET['file'];

    if (file_exists('../' . $file) && is_readable('../' . $file)) {
        // Configurar los encabezados HTTP
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=" . urlencode($file));
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: binary");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: public");

        // Leer y enviar el archivo
        readfile('../' . $file);
    } else {
        echo 'Error: El archivo no existe o no es accesible.';
    }
?> 