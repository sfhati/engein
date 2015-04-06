<?php
/*
  use like [include:"template_file_without.inc"end include]
  [include:"temp"end include] //get content from template file name temp.inc in same folder of template source
  [include:"../temp"end include] 
  [include:"../temp.inc"end include] 
  [include:"{template}temp"end include] // this value {template} use in sfhati framework to get template folder
  [include:"{plugin}temp"end include] // this value {template} use in sfhati framework to get plugin folder
  [include:"{tmp}temp"end include] // this value {template} use in sfhati framework to get tmp folder
  [include:"{cache}temp"end include] // this value {template} use in sfhati framework to get cache folder
  [include:"{uploaded}temp"end include] // this value {template} use in sfhati framework to get uploaded folder
 */

function include_SYNTAX($vars) {
    global $syntaxcode;
    $vars = $syntaxcode->Syntax($vars[0]);
    $incfile = end(explode('/', $vars));
    if (end(explode('.', $incfile)) != 'inc') {
        $incfile.='.inc';
        $vars.='.inc';
    }
    // replace static folder name 
    if (strpos($vars, '}')) {
        $vars = str_replace('{plugin}', PLUGIN_PATH, $vars);
        $vars = str_replace('{template}', TEMPLATE_PATH, $vars);
        $vars = str_replace('{tmp}', TMP_PATH, $vars);
        $vars = str_replace('{cache}', CACHE_PATH, $vars);
        $vars = str_replace('{uploaded}', UPLOADED_PATH, $vars);
        $path = str_replace('//', '/', $vars);
    } else {
        $path = rtrim(realpath(dirname($syntaxcode->filename)), '/') . '/' . $vars;
    }
    if (file_exists($path)) {
        return $syntaxcode->Syntax(file_get_contents($path));
    }
    return "<br> Worning File path : $path Not Found!<br>";
    $vars = md5_file($vars) . '.php';
}
