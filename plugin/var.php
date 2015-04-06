<?php
/*
  use like [var:"variable"end var]
  [var:"variable"end var] //print echo $variable;
  [var:"variable-var"end var] //print $variable;
  [var:"variable-sess"end var] //print echo $_SESSION[variable];
  [var:"variable-sess-var"end var] //print $_SESSION[variable];
  [var:"variable[word]"end var] //print echo $variable[word];
  [var:"variable-cons"end var] //print echo variable;
 */

function var_SYNTAX($vars) {
    global $syntaxcode;
    foreach ($vars as $v => $var) {
        $vars[$v] = $syntaxcode->Syntax($var);
    }
    if (strpos($vars[0], '-var')) {
        $vars[0] = str_replace('-var', '', $vars[0]);
        $return = '%S#';
    } else {
        $return = "<?php echo %S#; ?>";
    }
    if (strpos($vars[0], '-sess')) {
        $vars[0] = str_replace('-sess', '', $vars[0]);
        $return = str_replace('%S#', '$_SESSION[%S#]', $return);
    } else
    if (strpos($vars[0], '-cons')) {
        $vars[0] = str_replace('-cons', '', $vars[0]);
    } else {
        $return = str_replace('%S#', '$%S#', $return);
    }

    return str_replace('%S#', $vars[0], $return);
}
