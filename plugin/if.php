<?php
/*
  use like [if:"expr","statement"end if]
  or like [if:"expr","statement [else] statement"end if]
 */
function if_SYNTAX(array $vars): string {
    global $syntaxcode;
    
    // Process nested syntax
    if (isset($syntaxcode) && method_exists($syntaxcode, 'processSyntax')) {
        foreach ($vars as $index => $var) {
            $vars[$index] = $syntaxcode->processSyntax($var);
        }
    }
    
    $condition = $vars[0] ?? 'false';
    $statement = $vars[1] ?? '';
    
    // Handle else clause
    $statement = str_replace('[else]', "<?php } else { ?>", $statement);
    
    return "<?php if ({$condition}) { ?>\n{$statement}\n<?php } ?>";
}
