<?php

$mainContent = $data['mainContent'];
include_once ('header_view.php');

if (isset($data['enableCaching']) && $data['enableCaching'] == true) {
    //enable caching ---------------------------------------------------------------------
    include_once '../config/config.php';
    $cachefile = "../cache/" . $mainContent . ".html";
    $cachetime = CACHE_TIME;

    // Serve from the cache if it is younger than $cachetime
    if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))) {
        include($cachefile);
        echo "<!-- Cached " . date('jS F Y H:i', filemtime($cachefile)) . "-->";
        exit;
    }
    // start the output buffer
    ob_start();
    //usual PHP script and HTML...
    //
    include_once ($mainContent . '.php');
    include_once ('footer_view.php');
    //
    // open the cache file for writing
    $fp = fopen($cachefile, 'w');
    // save the contents of output buffer to the file
    fwrite($fp, ob_get_contents());
    // close the file
    fclose($fp);
    // Send the output to the browser
    ob_end_flush();
    //end enable caching -----------------------------------------------------------------
} else {
    include_once ($mainContent . '.php');
    include_once ('footer_view.php');
}
?>