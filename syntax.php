<?php

/**
 * 25 March 2015. version 1.0
 * 
 * Template engine for PHP,
 * 
 * License: http://creativecommons.org/licenses/LGPL/2.1/
 * 
 * ----------------------------------------------------------------------
 * 
 * examples of usage :
 *   $syntaxcode = new __SYNTAX( );
 *   $arrthis = $syntaxcode->openfile('template/sfhati/mobile.inc');
 * 
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
set_time_limit('5');
ini_set('memory_limit', '8M');
ini_set('display_errors', '1');
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/**
 * translate template code to php file and store it in cache folder to use as php files
 *
 * @param string $cache_path defult __DIR__ , full path for cache store php files output
 * @param string $conf_file defult conf , name file for cached template export
 * @param boolean $write_source defult true , write template code in export php file as commant
 * @return string php code  
 */
class __SYNTAX {

    private $ALL_SYNTAX = '';
    private $cache_path = '';
    private $conf_file = 'conf.tmp';
    public $filename = '';

    public function __construct($cache_path = '', $conf_file = 'conf.tmp') {
        if ($cache_path)
            $this->cache_path = $cache_path;
        else
            $this->cache_path = __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        $this->conf_file = $conf_file;
    }

    /**
     * open template file 
     *
     * @param string $templatefile full path for template file
     * @return string php code  
     */
    public function openfile($templatefile) {
        //check if exist file template
        if (!file_exists($templatefile))
            return 'File not found!';

        $export_php_file = $this->cache_path . md5_file($templatefile) . '.php';
        //check if already translate by search in md5 file return 
        if ($this->check_cache(md5($templatefile), md5($export_php_file)))
            return $export_php_file;

        $this->filename = $templatefile;

        //open file & get content to ALL_SYNTAX
        $this->ALL_SYNTAX = implode(file($templatefile), '');
        //start translate template code
        $t = $this->Syntax($this->ALL_SYNTAX);
        //write php file
        $this->filewrite($export_php_file, $t);
        //set in conf cache file
        if (file_exists($this->conf_file)) {
            $DataConf = file($this->conf_file);
            $set_content = '';
            foreach ($DataConf as $row) {
                $search_row = explode(';', $row);
                if (trim($search_row[0]) != trim(md5($templatefile)))
                    $set_content.=$row;
            }
        }
        $this->filewrite($this->conf_file, md5($templatefile) . ';' . md5($export_php_file) . "\n" . $set_content);
        return $export_php_file;
    }

    /**
     * check cache file
     *
     * @param string $templatefile full path for template file
     * @param string $md5 md5 for template file
     * @return boolean true for exist cache file otherwise false  
     */
    private function check_cache($templatefile, $md5) {
        if (!file_exists($this->conf_file))
            return false;
        if (in_array(trim($templatefile . ';' . $md5), array_map('trim', file($this->conf_file))))
            return true;
        return false;
    }

    /**
     * translate syntax form template code      
     *
     * @param string $t syntax code
     * @return string php code  
     */
    public function Syntax($t) {
        $syn = $this->GetSyantax($t);
        if (is_array($syn) && $syn[0]) {
            $orginalSyn = $syn[1] . $syn[3] . $syn[2];
            $vars = $this->parse_variable('[XXX:"' . $syn[3] . '"end XXX]', $syn[0]);
            // $vars : $syn[0]; here must go to function
            $t = str_replace($orginalSyn, $vars, $t);

            return $this->Syntax($t);
        } else {
            return $t;
        }
        return $t;
    }

    /**
     * pares first syntac form template code 
     *
     * @param string $t template code
     * @return array first syntax code , command
     */
    private function GetSyantax($t) {
        //search for pattern like [if:"
        preg_match("/\[(\w)+:\"/", $t, $out);
        $word = str_replace('[', '', $out[0]);
        $word = str_replace(':"', '', $word);
        // nothing to return if not found syntax 
        if (!$word)
            return '';

        //not our syntax so convert it to html code 
        if (!function_exists(trim($word) . '_SYNTAX')) {
            $t = str_replace("[{$word}:", "&#91;{$word}:&#93;", $t);
            $t = str_replace("{$word}]", "{$word}&#93;", $t);
            // search agean in template code 
            return $this->GetSyantax($t);
        }

        //start syntax with command
        $start = "[{$word}:";
        $end = "end {$word}]";
        //cut syntax level 1
        $xsyn = $this->extractBetweenDelimetersx($t, $start, $end);
        //return array
        $return[0] = $word;
        $return[1] = $start;
        $return[2] = $end;
        $return[3] = $xsyn;
        return $return;
    }

    /**
     * Cut string between tow words !
     *
     * @param string $inputstr template code
     * @param string $delimeterLeft start word to cut
     * @param string $delimeterRight end word to cut
     * @return integer $i defult 1 , there is last offset postion for end word
     */
    private function extractBetweenDelimetersx($inputstr, $delimeterLeft, $delimeterRight, $i = 1) {
        //get start word postion
        $posLeft = strpos($inputstr, $delimeterLeft);
        //get end word postion
        $posRight = $this->strnpos($inputstr, $delimeterRight, $i);
        //if not found return error template code
        if ($posLeft === false || $posRight === false)
            if ($posLeft === false)
                die("Error:<br> near $delimeterLeft  _ !$posLeft || !$posRight _ <hr> $inputstr ");
            else
                die("Error:<br> near $delimeterRight _ !$posLeft || !$posRight _<hr> $inputstr ");
        //cut string from start postion to end one    
        $cut_text = substr($inputstr, $posLeft + strlen($delimeterLeft), $posRight - ($posLeft + strlen($delimeterLeft)));
        $i++;
        if ($i == 1000)
            exit();
        //check if there is same word on string to complete syntact 
        if ($this->strnpos($cut_text, $delimeterLeft, $i - 1) > 0)
            return $this->extractBetweenDelimetersx($inputstr, $delimeterLeft, $delimeterRight, $i);
        else
            return $cut_text;
    }

    /**
     * help function extractBetweenDelimetersx to find a strpos
     *
     * @param string $haystack template code
     * @param string $needle  word to search
     * @param integer $nth 
     * @return integer $offset defult 0 
     */
    private function strnpos($haystack, $needle, $nth, $offset = 0) {
        if (!$haystack)
            return "";
        else {
            for ($retOffs = $offset - 1; ($nth > 0) && ($retOffs !== FALSE); $nth--)
                $retOffs = strpos($haystack, $needle, $retOffs + 1);
            return $retOffs;
        }
    }

    /**
     * get syntax code and parse the variables for level 1 of syntax by replacing , with |,| 
     * this function pattrn writen by Casimir et Hippolyte http://stackoverflow.com/users/2255089/casimir-et-hippolyte
     *
     * @param string $t syntax code
     * @param string $command level 1 command syntax like if,for,each,var or other
     * @return string php code  
     */
    private function parse_variable($t, $command) {
        $pattern = '~
# this part defines subpatterns to be used later in the main pattern
(?(DEFINE)
    (?<nestedBrackets> \[ [^][]* (?:\g<nestedBrackets>[^][]*)*+ ] )
)

# the main pattern
(?:            # two possible entry points
    \G(?!\A)   # 1. contiguous to a previous match
  |            #   OR
    [^[]* \[   # 2. all characters until an opening bracket
)

# all possible characters until "," or the closing bracket:
[^]["]* # all that is not ] [ or "
(?:
    \g<nestedBrackets> [^]["]* # possible nested brackets
  |                            #   OR
    "(?!,") [^]["]*            # a quote not followed by ,"
)*+  # repeat as needed
\K   # remove all on the left from match result
(?:
    ","           # match the target
  |
    ] (*SKIP)(*F) # closing bracket: break the contiguity
)
~x';

        // replace , with |,| for main syntax 
        $t = preg_replace($pattern, '|,|', $t);
        $t = str_replace('[XXX:"', '', $t);
        $t = str_replace('"end XXX]', '', $t);

        // call command function with array vars 
        $com = $command . '_SYNTAX';
        return $com(explode('|,|', trim($t, '"')));
    }

    /**
     * write output file php 
     * 
     *
     * @param string $file full path and file name
     * @param string $content php code content
     * @return none!  
     */
    private function filewrite($file, $content) {
        @unlink($file);
        $fp = fopen($file, 'w');
        if (flock($fp, LOCK_EX | LOCK_NB)) {
            fwrite($fp, $content);
            fflush($fp);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

}

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


$syntaxcode = new __SYNTAX();

$export_filename = $syntaxcode->openfile('template.inc');
echo $export_filename;
//include($export_filename);


/*
[element:"type","name","lable","rules","msg rules","filter","style","more option"end element]

---Type---
group,hidden,reset,checkbox,file,color,image,password,
radio,button,submit,select,text,textarea,link,date,static,
header,html,autocomplete,

---rules---
required,maxlength,minlength,rangelength,email,regex,
lettersonly,alphanumeric,numeric,nopunctuation,
nonzero,compare,callback[function name]

---filter---
trim , lowercase,hgircase,callback[function name]

 *  */