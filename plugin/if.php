<?php
/*
  use like [if:"expr","statement"end if]
  or like [if:"expr","statement [else] statement"end if]
 */

function if_SYNTAX($vars) {
    global $syntaxcode;
    foreach ($vars as $v => $var) {
        $vars[$v] = $syntaxcode->Syntax($var);
    }
    $vars[1] = str_replace('[else]', "<?php }else{ ?>", $vars[1]);
    return "
   <?php if ($vars[0]) { ?>
 $vars[1]     
<?php } ?>
";
}
