<?php 
/** 
 * Charts 4 PHP 
 * 
 * @author Shani <support@chartphp.com> - http://www.chartphp.com 
 * @version 2.0 
 * @license: see license.txt included in package 
 */ 

include_once("../chartphp/config.php"); 
include_once(CHARTPHP_LIB_PATH . "/inc/chartphp_dist.php"); 

$p = new chartphp(); 

include("../chartphp/example_data.php"); 
$p->data=$bar_chart_data; 
$p->chart_type = "bar"; 

// Common Options 
$p->title = "Bar Chart"; 
$p->xlabel = "Months"; 
$p->ylabel = "Purchase"; 
$p->showxticks = true; 
$p->showyticks = true; 
$p->showpointlabel = true; 
$out = $p->render('c1'); 
?> 
<!DOCTYPE html> 
<html> 
    <head> 
        <link rel="stylesheet" href="../chartphp/lib/js/chartphp.css"> 
        <script src="../chartphp/lib/js/jquery.min.js"></script> 
        <script src="../chartphp/lib/js/chartphp.js"></script> 
    </head> 
    <body> 
        <div> 
            <?php echo $out; ?> 
        </div> 
    </body> 
</html> 