 
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>Sfhati PHP engine</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    
 </head>

  <body>

    <div class="container">
      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation" class="active"><a href="#">Home</a></li>
            <li role="presentation"><a href="#">About</a></li>
            <li role="presentation"><a href="#">Contact</a></li>
          </ul>
        </nav>
        <h3 class="text-muted">Sfhati PHP engine</h3>
      </div>

      <div class="jumbotron">
        <h1>Sfhati PHP engine</h1>
        <p class="lead">This package implements a template engine that generates PHP compiled files.
It takes a given template file and processes it replacing variables and other types of template constructs.
The class return the path of PHP script that is the compiled template ready to be executed.
The package comes with several plugins that implement variable replacement, conditional sections, loops, and including additional template files.</p>
        <p><a class="btn btn-lg btn-success" href="https://github.com/sfhati/engein" role="button">Download Form Github</a></p>
      </div>

      <div class="row marketing">
        <div class="col-lg-6">
          <h4>Subheading</h4>
          <p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>

          <h4>Subheading</h4>
          <p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum.</p>

          <h4>Subheading</h4>
          <p>Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
        </div>

        <div class="col-lg-6">
          <h4>Subheading</h4>
          <p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>

          <h4>Subheading</h4>
          <p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum.</p>
<div class="highlight"><pre>
<code class="language-html" data-lang="html">
function var_SYNTAX($vars) {
    global $syntaxcode;
    foreach ($vars as $v => $var) {
        $vars[$v] = $syntaxcode->Syntax($var);
    }
    if (strpos($vars[0], '-var')) {
        $vars[0] = str_replace('-var', '', $vars[0]);
        $return = '%S#';
    } else {
        $return = "&lt;?php echo %S#; ?&gt;";
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
 
</code>
</pre>
</div>
          <h4>Subheading</h4>
          <p>Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
        </div>
      </div>

      <footer class="footer">
        <p>&copy; Company 2014</p>
      </footer>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
 
  use like <?php echo $variable; ?>
  <?php echo $variable; ?> //print echo $variable;
  $variable //print $variable;
  <?php echo $_SESSION[variable]; ?> //print echo $_SESSION[variable];
  $_SESSION[variable] //print $_SESSION[variable];
  <?php echo $variable[word]; ?> //print echo $variable[word];
  $variable[word] //print  $variable[word];
  $variable[word] //print  $variable[word];
  <?php echo variable; ?> //print echo variable;
 
kldsja flkdsjaflkdjsafds<br> fdsa fdsaf dsaf sdaf<br>fdsaf sdaf asdic
kldsja flkdsjaflkdjsafds<br> fdsa fdsaf dsaf sdaf<br>fdsaf sdaf asdic
kldsja flkdsjaflkdjsafds<br> fdsa fdsaf dsaf sdaf<br>fdsaf sdaf asdic
$ERROR[PAGE_NOT_FOUND]
<?php echo $ERROR[PAGE_NOT_FOUND]; ?>

   <?php if ($ERROR[PAGE_NOT_FOUND]) { ?>
 
404 page not found! <br>
<?php }else{ ?>
 
   <?php if ($ccx==$arr[bas]) { ?>
 
 
   
 inside
 
      
<?php } ?>

     
<?php } ?>


   <?php if ($ERROR[PAGE_NOT_ACTIVE]) { ?>
 <?php echo $bassam; ?>
403 page not active!<br>

fdsgtgfds dfgsgf
<?php }else{ ?>
this is else code
     
<?php } ?>


   <?php if ($ERROR[PAGE_NOT_PERMISSION]) { ?>
 
500 you don't have permission!<br>
<br>


     
<?php } ?>
  
<br>hi man this is include template name template_include.inc!<br>
kldsja flkdsjaflkdjsafds<br> fdsa fdsaf dsaf sdaf<br>fdsaf sdaf asdic
kldsja flkdsjaflkdjsafds<br> fdsa fdsaf dsaf sdaf<br>fdsaf sdaf asdic

                    <div class="heap">
                        <ul class="heapOptions">
                            
<?php $countrnd546informationcpanel=0; 
            if ( is_array($information_cpanel) ) 
            foreach ( $information_cpanel as $krnd546informationcpanel => $vrnd546informationcpanel ) {
$countrnd546informationcpanel++; 
$stlrnd546informationcpanel = ($stlrnd546informationcpanel == 1) ? 0 : 1;
?>
   
                            <li class="heapOption"  title="<?php echo $vrnd546informationcpanel; ?>">
                                <a href='javascript:'>
                                     
   <?php if ($krnd546informationcpanel==bas2) { ?>
 Yes its bas2!     
<?php } ?>

                                    <p>[<?php echo $krnd546informationcpanel; ?>]</p><?php echo $bassam; ?>
                                    <span><?php echo $vrnd546informationcpanel; ?></span>
                                    <span><?php echo $vrnd546informationcpanel[bassam1]; ?></span>
                                    <span><?php echo $vrnd546informationcpanel['bassam essawi']; ?></span>
                                    <span><?php echo $vrnd546informationcpanel[3443]; ?></span>
                                    <span>$vrnd546informationcpanel[thisisvar]</span>
                                    <span><?php echo $stlrnd546informationcpanel; ?></span>
                                    <span><?php echo $countrnd546informationcpanel; ?></span>
                                </a>
                            
<?php $countrnd445sessionarray=0; 
            if ( is_array($_SESSION["session_array"]) ) 
            foreach ( $_SESSION["session_array"] as $krnd445sessionarray => $vrnd445sessionarray ) {
$countrnd445sessionarray++; 
$stlrnd445sessionarray = ($stlrnd445sessionarray == 1) ? 0 : 1;
?>
   
                            <li class="heapOption"  title="<?php echo $vrnd445sessionarray; ?>">
                            <li class="heapOption"  title="<?php echo $krnd445sessionarray; ?>">
                              
<?php } ?>
   
                            </li>

                             
<?php } ?>

                        </ul>
                    </div>


<ul>
    
<?php for($uu=4;$uu<10;$uu++){ ?>

    <li><?php echo "$uu"; ?></li>
    
   <?php if ($uu==6) { ?>
 Yes its six!     
<?php } ?>

     
<?php }?> 
    
</ul>
 
<?php for($lop=4;$lop<10;$lop++){ ?>
this is <?php echo "$lop"; ?> , so can use as a var like $lop  
<?php }?> 
 

[element:"text","",""end element]
