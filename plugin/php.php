<?php

function php_SYNTAX($vars) {
    global $syntaxcode;
    $vars = $syntaxcode->Syntax($vars[0]);
    return "<?php $vars; ?>";
}
