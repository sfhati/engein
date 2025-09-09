<?php
/*
  use like [for:"id for this loop","start counter","count to","content loop"end for]
  [for:"lop","4","10","this is %lop% , so can use as a var like %lop-var% "end for] 
  //result
 <?php for($lop=4;$lop<10;$lop++){ ?>
    this is <?php echo "$lop"; ?> , so can use as a var like $lop   
 <?php }?>  

 */
function for_SYNTAX(array $vars): string {
    global $syntaxcode;
    
    // Process nested syntax
    if (isset($syntaxcode) && method_exists($syntaxcode, 'processSyntax')) {
        foreach ($vars as $index => $var) {
            $vars[$index] = $syntaxcode->processSyntax($var);
        }
    }
    
    $variable = $vars[0] ?? 'i';
    $start = $vars[1] ?? '0';
    $end = $vars[2] ?? '10';
    $content = $vars[3] ?? '';
    
    // Replace variable placeholders in content
    $content = str_replace("%{$variable}%", "<?php echo \${$variable}; ?>", $content);
    $content = str_replace("%{$variable}-var%", "\${$variable}", $content);
    
    return "<?php for (\${$variable} = {$start}; \${$variable} < {$end}; \${$variable}++) { ?>\n{$content}\n<?php } ?>";
}
