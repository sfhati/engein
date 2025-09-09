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

function var_SYNTAX(array $vars): string {
    global $syntaxcode;
    
    // Process nested syntax if engine is available
    if (isset($syntaxcode) && method_exists($syntaxcode, 'processSyntax')) {
        foreach ($vars as $index => $var) {
            $vars[$index] = $syntaxcode->processSyntax($var);
        }
    }
    
    $variable = $vars[0] ?? '';
    $returnPattern = "<?php echo %S#; ?>";
    
    // Check for -var flag (don't echo, just return variable)
    if (str_contains($variable, '-var')) {
        $variable = str_replace('-var', '', $variable);
        $returnPattern = '%S#';
    }
    
    // Check for -sess flag (session variable)
    if (str_contains($variable, '-sess')) {
        $variable = str_replace('-sess', '', $variable);
        $returnPattern = str_replace('%S#', '$_SESSION[\'%S#\']', $returnPattern);
    }
    // Check for -cons flag (constant)
    elseif (str_contains($variable, '-cons')) {
        $variable = str_replace('-cons', '', $variable);
        // Keep as is for constants
    }
    else {
        $returnPattern = str_replace('%S#', '$%S#', $returnPattern);
    }
    
    return str_replace('%S#', $variable, $returnPattern);
}
