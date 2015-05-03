<?php
function include_file_template($template_name) {
    $syntaxcode = new __SYNTAX(CACHE_PATH);
 
    if (end(explode('.', $template_name)) != 'inc')
        $template_name = $template_name . '.inc';
    $export_filename = $syntaxcode->openfile($template_name);
    
    ob_start();   
    include($export_filename);
    return ob_get_clean();
}

/* * *****************process html code by FUNCTION_AT_END() ******************** */
$my_simple_tmplt = include_file_template('template_include');

echo $my_simple_tmplt;

?>