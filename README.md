# engein
template php engein
/**
 * 25 March 2015. version 1.0
 * 
 * Template engine for PHP,
 * 
 * License: http://creativecommons.org/licenses/LGPL/2.1/
 * 
 * ----------------------------------------------------------------------
 * 
$syntaxcode = new __SYNTAX();
$export_filename = $syntaxcode->openfile('template.inc');
echo $export_filename;

Template.inc code like this : 
 [var:"variable"end var] 
 
 output php file in cache folder contain this code 
 <?php echo $variable;?>
 * 
 * 
 * The __SYNTAX( ) method return output php code, as a string.
 * 
 * see http://sfhati.com/fw/syntax/ for more information.
 * 
 * Notes :
 * # need PHP 5+
 * @author Bassam al-essawi <bassam.a.a.r@gmail.com>
 * @package sfhati
 * @subpackage fw
 * 
 */
