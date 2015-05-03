<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);


// include class file
include('syntax.php'); 
 $syntaxcode = new __SYNTAX();
// include all plugin
$dir = dirname(realpath(__FILE__)) . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR;
if ($dh = opendir($dir)) {

    while (($file = readdir($dh)) !== false) {
        if ($file != '.' && $file != '..' && filetype($dir . $file) != 'dir') {
            include($dir . $file);
        }
    }
    closedir($dh);
}

// this function can translate template file and return execute php file content as a variable , 
// so you can make things in out content!
function include_file_template($template_name) {
    global $syntaxcode;
 
    if (end(explode('.', $template_name)) != 'inc')
        $template_name = $template_name . '.inc';
    $export_filename = $syntaxcode->openfile($template_name);
    
    ob_start();   
    include($export_filename);
    return ob_get_clean();
}

// translate template.inc file using include_file_template() function 
$my_simple_tmplt = include_file_template('template/template.inc');

// print out exeute php file 
echo $my_simple_tmplt;
