<?php

/*
  user like [each:"array_expression-option","statement"end each]
 * option can use : -sess for session array like $_SESSION['array'];
  statement use :
  %array_expression:key% // print key
  %array_expression:val% // print value
  %array_expression:val-var% //not print value just for use as a variable
  %array_expression:% //print 0/1
  %array_expression:#% //print counter row
  %array_expression:val[word]% // if value is array you can print item form it
  %array_expression:val[word-var]% // if value is array you can use item value as variable
  %array_expression:val['word word']% // you can use ' for word contain space chr
 */

function each_SYNTAX($vars) {
    global $syntaxcode;
    foreach ($vars as $v => $var) {
        $vars[$v] = $syntaxcode->Syntax($var);
    }

    if (strpos($vars[0], '-sess')) {
        $vars[0] = str_replace('-sess', '', $vars[0]);
        $vars_0 = '$_SESSION["' . $vars[0] . '"]';
    } else {
        $vars_0 = '$' . $vars[0];
    }
    $vars__0 = 'rnd' . rand(100, 1202) . preg_replace("/[^A-Za-z0-9 ]/", '', $vars[0]);
    preg_match_all("/%" . $vars[0] . ":val\[([\w\s-']+)[^%]*\]%/", $vars[1], $r);
    if (is_array($r[1]))
        foreach ($r[1] as $r_key => $r_val) {
            if (strpos($r_val, '-var')) {
                $r_val1 = str_replace('-var', '', $r_val);
                $vars[1] = str_replace('%' . $vars[0] . ':val[' . $r_val . ']%', "\$v{$vars__0}[{$r_val1}]", $vars[1]);
            } else {
                $vars[1] = str_replace('%' . $vars[0] . ':val[' . $r_val . ']%', "<?php echo \$v{$vars__0}[{$r_val}]; ?>", $vars[1]);
            }
        }

    $vars[1] = str_replace('%' . $vars[0] . ':val%', "<?php echo \$v$vars__0; ?>", $vars[1]);
    $vars[1] = str_replace('%' . $vars[0] . ':key%', "<?php echo \$k$vars__0; ?>", $vars[1]);
    $vars[1] = str_replace('%' . $vars[0] . ':val-var%', "\$v$vars__0", $vars[1]);
    $vars[1] = str_replace('%' . $vars[0] . ':key-var%', "\$k$vars__0", $vars[1]);
    $vars[1] = str_replace('%' . $vars[0] . ':%', "<?php echo \$stl$vars__0; ?>", $vars[1]);
    $vars[1] = str_replace('%' . $vars[0] . ':#%', "<?php echo \$count$vars__0; ?>", $vars[1]);


    return "
<?php \$count$vars__0=0; 
            if ( is_array($vars_0) ) 
            foreach ( $vars_0 as \$k$vars__0 => \$v$vars__0 ) {
\$count$vars__0++; 
\$stl$vars__0 = (\$stl$vars__0 == 1) ? 0 : 1;
?>
$vars[1] 
<?php } ?>
";
}

