<?php
function php_SYNTAX(array $vars): string {
    global $syntaxcode;
    
    $phpCode = $vars[0] ?? '';
    
    if (isset($syntaxcode) && method_exists($syntaxcode, 'processSyntax')) {
        $phpCode = $syntaxcode->processSyntax($phpCode);
    }
    
    return "<?php {$phpCode}; ?>";
}
