<?php
/*
  use like [for:"id for this loop","start counter","count to","content loop"end for]
  [for:"lop","4","10","this is %lop% , so can use as a var like %lop-var% "end for] 
  //result
 <?php for($lop=4;$lop<10;$lop++){ ?>
    this is <?php echo "$lop"; ?> , so can use as a var like $lop   
 <?php }?>  

 */
function for_SYNTAX($vars) {
    global $syntaxcode;
    foreach ($vars as $v => $var) {
        $vars[$v] = $syntaxcode->Syntax($var);
    }
    $vars[3] = str_replace('%' . $vars[0] . '%', "<?php echo \"\$$vars[0]\"; ?>", $vars[3]);
    $vars[3] = str_replace('%' . $vars[0] . '-var%', "\$$vars[0]", $vars[3]);


    return "
<?php for(\$$vars[0]=$vars[1];\$$vars[0]<$vars[2];\$$vars[0]++){ ?>
$vars[3] 
<?php }?> 
";
}
