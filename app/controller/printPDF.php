<?php
/**
***************************************************************************************************
 * @Software    emediaPublishing  
 * @Author      Michel Kohon 
 * @Copyright	Copyright (c) 2012. All Rights Reserved.
 * @License		GNU GENERAL PUBLIC LICENSE  
 * This license covers all scripts, PHP, javascript, css, html, etc... called directly or indirectly from this root 
 **************************************************************************************************
 This source code is licensed under the terms of the GNU GENERAL PUBLIC LICENSE
 http://www.gnu.org/licenses/gpl.html
 **************************************************************************************************
 Copyright (c) 2012 http://emediacart.com. All Rights Reserved.
 **************************************************************************************************
**/
    $filename = $_GET['al'].'.pdf';
 
    $file = '../../galleria/pdf/'.$filename;

    $max = 60;
    for ($count=0;$count<$max;$count++) {

        if (! file_exists($file)) sleep(1);
        }
        
    if (! file_exists($file)) {
        echo "Sorry... but either the PDF file [$filename] has not been created or it's just way too big!";
        exit;
        }
//echo filesize($file); exit;

header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($file));
header('Accept-Ranges: bytes');

@readfile($file);
?>
