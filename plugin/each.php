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

function each_SYNTAX(array $vars): string {
    global $syntaxcode;
    
    // Process nested syntax
    if (isset($syntaxcode) && method_exists($syntaxcode, 'processSyntax')) {
        foreach ($vars as $index => $var) {
            $vars[$index] = $syntaxcode->processSyntax($var);
        }
    }
    
    $arrayName = $vars[0] ?? 'items';
    $content = $vars[1] ?? '';
    
    // Handle session array option
    $arrayVariable = str_contains($arrayName, '-sess') 
        ? '$_SESSION[\'' . str_replace('-sess', '', $arrayName) . '\']'
        : '$' . str_replace('-sess', '', $arrayName);
    
    // Generate unique variable names to avoid conflicts
    $randomSuffix = 'rnd' . random_int(100, 9999) . preg_replace('/[^A-Za-z0-9]/', '', $arrayName);
    $keyVar = '$k' . $randomSuffix;
    $valueVar = '$v' . $randomSuffix;
    $countVar = '$count' . $randomSuffix;
    $styleVar = '$style' . $randomSuffix;
    
    // Process array value access patterns
    preg_match_all("/%{$arrayName}:val\[([\w\s'-]+)\]%/", $content, $matches);
    if (!empty($matches[1])) {
        foreach ($matches[1] as $index => $match) {
            $fullMatch = $matches[0][$index];
            if (str_contains($match, '-var')) {
                $cleanMatch = str_replace('-var', '', $match);
                $content = str_replace($fullMatch, "{$valueVar}[{$cleanMatch}]", $content);
            } else {
                $content = str_replace($fullMatch, "<?php echo {$valueVar}[{$match}]; ?>", $content);
            }
        }
    }
    
    // Replace standard placeholders
    $replacements = [
        "%{$arrayName}:val%" => "<?php echo {$valueVar}; ?>",
        "%{$arrayName}:key%" => "<?php echo {$keyVar}; ?>",
        "%{$arrayName}:val-var%" => $valueVar,
        "%{$arrayName}:key-var%" => $keyVar,
        "%{$arrayName}:%" => "<?php echo {$styleVar}; ?>",
        "%{$arrayName}:#%" => "<?php echo {$countVar}; ?>"
    ];
    
    $content = str_replace(array_keys($replacements), array_values($replacements), $content);
    
    return "<?php 
{$countVar} = 0; 
{$styleVar} = 0;
if (is_array({$arrayVariable})) {
    foreach ({$arrayVariable} as {$keyVar} => {$valueVar}) {
        {$countVar}++;
        {$styleVar} = ({$styleVar} == 1) ? 0 : 1;
?>\n{$content}\n<?php 
    }
} ?>";
}
