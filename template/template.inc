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
                        <li role="presentation" class=""><a href="http://sfhati.com">sfhati site</a></li>
                        <li role="presentation"><a href="http://www.phpclasses.org/package/9106-PHP-Template-engine-that-generates-PHP-compiled-files.html">phpclasses</a></li>
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
                    <h4>install & config</h4>
                    <p>unzip file you already downloaded</p>
                    <p></p>
                    <code>include('syntax.php'); </code>
                    <p>include syntax.php file</p>
                    <code>$syntaxcode = new __SYNTAX(); </code>
                    <p>use same variable name in this line:</p>

                    <code>$syntaxcode = new __SYNTAX('full_path_folder_here/'); </code>
                    <p>you can add path for folder cache like this </p>

                    <h4>index.php file content</h4>
                    <p>we make this file to use it in your project, so this will help you:)</p>
                    <pre><code>

error_reporting(E_ERROR | E_WARNING | E_PARSE);


// include class file
include('syntax.php'); 
 $syntaxcode = new __SYNTAX();
// include all plugin
$dir = dirname(realpath(__FILE__)) . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR;
if ($dh = opendir($dir)) {

    while (($file = readdir($dh)) !== false) {
        if ($file != '.' && $file != '..' && filetype($dir . $file) != 'dir') {
            include($dir . $file);
        }
    }
    closedir($dh);
}

// this function can translate template file and return execute php file content as a variable , 
// so you can make things in out content!
function include_file_template($template_name) {
    global $syntaxcode;
 
    if (end(explode('.', $template_name)) != 'inc')
        $template_name = $template_name . '.inc';
    $export_filename = $syntaxcode->openfile($template_name);
    
    ob_start();   
    include($export_filename);
    return ob_get_clean();
}

// translate template.inc file using include_file_template() function 
$my_simple_tmplt = include_file_template('template/template.inc');

// print out exeute php file 
echo $my_simple_tmplt;
              
          </code></pre>

                </div>

                <div class="col-lg-6">
                    <h4>use code in template file</h4>
                    <h5><b>var plugin</b> option you can used : <code>-var</code> , <code>-sess</code> , <code>-cons</code></h5>

                    <code>&#91;var:"variable"end var&#93;</code>
                    result
                    <pre><code>echo $variable;</code></pre>
                    <code>&#91;var:"variable-var"end var&#93;</code>
                    result
                    <pre><code>$variable</code></pre>
                    <code>&#91;var:"variable-sess"end var&#93;</code>
                    result
                    <pre><code>echo $_SESSION[variable];</code></pre>
                    <code>&#91;var:"variable-sess-var"end var&#93;</code>
                    result
                    <pre><code>$_SESSION[variable]</code></pre>
                    <code>&#91;var:"variable[word]"end var&#93;</code>
                    result
                    <pre><code>echo $variable[word];</code></pre>
                    <code>&#91;var:"variable-cons"end var&#93;</code>
                    result
                    <pre><code>echo variable;</code></pre>
                    </p>

                    <hr>
                    
                    <h5><b>if plugin</b></h5>

                    use like<code>&#91;if:"expr","statement"end if&#93;</code><br>
                    Real Ex.<br>
                    <code>&#91;if:"&#91;var:"a-var"end var&#93;==1","yes a = 1 &#91;else&#93; no its not 1!"end if&#93;</code>
                    result
                    <pre><code><?php if ($a==1) { ?>
    yes a = 1 <?php }else{ ?> no its not 1!     
<?php } ?></code></pre>
                    </p>



                    <hr>
                    
                    <h5><b>for plugin</b></h5>
  use like <code>&#91;for:"id for this loop","start counter","count to","content loop"end for&#93;</code><br>
  Real Ex.<br>
  <code>&#91;for:"lop","4","10","this is %lop% , so can use as a var like %lop-var% "end for&#93;</code> <br>
  Result<br>
<pre><code><?php for ($lop = 4; $lop < 10; $lop++) { ?>
    this is <?php echo "$lop"; ?> , so can use as a var like $lop  
<?php } ?></code></pre> 
    
  other Ex.
  <code>&#91;for:"lop","4","10","<br>
      use value as a variable inside if statement!<br> 
      &#91;if:"%lop-var%==1","yes a = 1 "end if&#93; <br> 
"end for&#93;</code> 
  
  <br>Result<br>
<pre><?php for ($lop = 4; $lop < 10; $lop++) { ?>
    use value as a variable inside if statement! 
    <?php if ($lop == 1) { ?>
        yes a = 1      
    <?php } ?>
<?php } ?></pre>
                    </p>

 <hr>
 

  <h5><b>each plugin</b> option can use : <code>-sess</code> for session array</h5>
  use like <code>&#91;each:"array_expression-option","statement"end each&#93;</code><br>
  Real Ex.<br>
  <code>&#91;each:"array_expression","<br>
 statement use :<br>
  %array_expression:key% // print key<br>
  %array_expression:val% // print value<br> 
  %array_expression:val-var% //not print value just for use as a variable<br>
  %array_expression:% //print 0/1<br>
  %array_expression:#% //print counter row<br>
  %array_expression:val[word]% // if value is array you can print item form it<br>
  %array_expression:val[word-var]% // if value is array you can use item value as variable<br>
  %array_expression:val['word word']% // you can use ' for word contain space chr<br>      
"end each&#93;</code> <br>


  <br>Result<br>
<pre><?php
$countrnd166arrayexpression = 0;
if (is_array($array_expression))
    foreach ($array_expression as $krnd166arrayexpression => $vrnd166arrayexpression) {
        $countrnd166arrayexpression++;
        $stlrnd166arrayexpression = ($stlrnd166arrayexpression == 1) ? 0 : 1;
        ?>

        statement use :
        <?php echo $krnd166arrayexpression; ?> // print key
        <?php echo $vrnd166arrayexpression; ?> // print value
        $vrnd166arrayexpression //not print value just for use as a variable
        <?php echo $stlrnd166arrayexpression; ?> //print 0/1
        <?php echo $countrnd166arrayexpression; ?> //print counter row
        <?php echo $vrnd166arrayexpression[word]; ?> // if value is array you can print item form it
        $vrnd166arrayexpression[word] // if value is array you can use item value as variable
        <?php echo $vrnd166arrayexpression['word word']; ?> // you can use ' for word contain space chr

    <?php } ?></pre>
                    </p>

                    <hr>
                    
                    <h5><b>include plugin</b></h5>

                    use like<code>&#91;include:"template_file_without.inc"end include&#93;</code><br>
                    Real Ex.<br>
                    <code><br>
  &#91;include:"temp"end include&#93; //get content from template file name temp.inc in same folder of template source<br>
  &#91;include:"../temp"end include&#93;<br>
  &#91;include:"../temp.inc"end include&#93;<br>
  &#91;include:"{template}temp"end include&#93; // this value {template} use in sfhati framework to get template folder<br>
  &#91;include:"{plugin}temp"end include&#93; // this value {template} use in sfhati framework to get plugin folder<br>
  &#91;include:"{tmp}temp"end include&#93; // this value {template} use in sfhati framework to get tmp folder<br>
  &#91;include:"{cache}temp"end include&#93; // this value {template} use in sfhati framework to get cache folder<br>
  &#91;include:"{uploaded}temp"end include&#93; // this value {template} use in sfhati framework to get uploaded folder<br>
                    <br>
                    </code>
                   
                    </p>



                </div>
 </div>
                <footer class="footer">
                    <p>&copy; Company 2014</p>
                </footer>

            </div> <!-- /container -->



    </body>
</html>

