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
 * see http://sfhati.com/engein/ for more information.
 * 
 * Notes :
 * # need PHP 5+
 * @author Bassam al-essawi <bassam.a.a.r@gmail.com>
 * @package sfhati
 * @subpackage fw
 * 
 */

/**
 * translate template code to php file and store it in cache folder to use as php files
 *
 * @param string $cache_path defult __DIR__ , full path for cache store php files output
 * @param boolean $write_source defult true , write template code in export php file as commant
 * @return string php code  
 */
class ModernSyntaxEngine {
    
    private string $allSyntax = '';
    private string $cachePath = '';
    private string $filename = '';
    
    public function __construct(string $cachePath = '') {
        if ($cachePath) {
            $this->cachePath = rtrim($cachePath, '/\\') . DIRECTORY_SEPARATOR;
        } else {
            $this->cachePath = __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        }
        
        // Create cache directory if it doesn't exist
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }
    
    /**
     * Open template file and process it
     *
     * @param string $templateFile Full path for template file
     * @return string Path to compiled PHP file or error message
     */
    public function openFile(string $templateFile): string {
        // Check if template file exists
        if (!file_exists($templateFile)) {
            return 'Template file not found: ' . $templateFile;
        }
        
        $exportPhpFile = $this->cachePath . md5($templateFile) . '_' . md5_file($templateFile) . '.php';
        
        // Check if already compiled and up to date
        if (file_exists($exportPhpFile)) {
            return $exportPhpFile;
        }
        
        $this->filename = $templateFile;
        
        // Read template file content
        $this->allSyntax = file_get_contents($templateFile);
        if ($this->allSyntax === false) {
            return 'Error reading template file: ' . $templateFile;
        }
        
        // Escape existing PHP tags to prevent code injection
        $this->allSyntax = str_replace(['<?', '?>'], ['&lt;?', '?&gt;'], $this->allSyntax);
        
        // Process template syntax
        $compiledCode = $this->processSyntax($this->allSyntax);
        
        // Clean old cache files
        $this->clearCache(md5($templateFile));
        
        // Write compiled PHP file
        if ($this->writeFile($exportPhpFile, $compiledCode)) {
            return $exportPhpFile;
        }
        
        return 'Error writing compiled template file';
    }
    
    /**
     * Clear old cache files for this template
     */
    private function clearCache(string $templateHash): void {
        if (!is_dir($this->cachePath)) {
            return;
        }
        
        $files = scandir($this->cachePath);
        if ($files === false) {
            return;
        }
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $pathParts = explode('_', $file);
            if (isset($pathParts[0]) && $pathParts[0] === $templateHash) {
                $fullPath = $this->cachePath . $file;
                if (is_file($fullPath)) {
                    unlink($fullPath);
                }
            }
        }
    }
    
    /**
     * Process template syntax recursively
     */
    public function processSyntax(string $content): string {
        $syntax = $this->getSyntaxCommand($content);
        
        if (!empty($syntax) && isset($syntax[0])) {
            $originalSyntax = $syntax[1] . $syntax[3] . $syntax[2];
            $compiledCode = $this->parseVariables('[XXX:"' . $syntax[3] . '"end XXX]', $syntax[0]);
            
            $content = str_replace($originalSyntax, $compiledCode, $content);
            
            return $this->processSyntax($content);
        }
        
        return $content;
    }
    
    /**
     * Get the first syntax command from template content
     */
    private function getSyntaxCommand(string $content): array {
        // Search for pattern like [command:"
        if (!preg_match('/\[(\w+):"/', $content, $matches)) {
            return [];
        }
        
        $command = $matches[1];
        
        // Check if function exists for this command
        $functionName = $command . '_SYNTAX';
        if (!function_exists($functionName)) {
            // Convert unknown syntax to HTML entities to prevent issues
            $content = str_replace("[{$command}:", "&#91;{$command}:", $content);
            $content = str_replace("end {$command}]", "end {$command}&#93;", $content);
            return $this->getSyntaxCommand($content);
        }
        
        $startTag = "[{$command}:";
        $endTag = "end {$command}]";
        
        // Extract syntax content
        $syntaxContent = $this->extractBetweenDelimiters($content, $startTag, $endTag);
        
        return [
            0 => $command,
            1 => $startTag,
            2 => $endTag,
            3 => $syntaxContent
        ];
    }
    
    /**
     * Extract content between delimiters with proper nesting support
     */
    private function extractBetweenDelimiters(string $input, string $leftDelim, string $rightDelim, int $occurrence = 1): string {
        $leftPos = strpos($input, $leftDelim);
        if ($leftPos === false) {
            throw new RuntimeException("Left delimiter not found: {$leftDelim}");
        }
        
        $rightPos = $this->findNthOccurrence($input, $rightDelim, $occurrence);
        if ($rightPos === false) {
            throw new RuntimeException("Right delimiter not found: {$rightDelim}");
        }
        
        $startPos = $leftPos + strlen($leftDelim);
        $content = substr($input, $startPos, $rightPos - $startPos);
        
        // Check for nested delimiters
        if ($occurrence < 100 && strpos($content, $leftDelim) !== false) {
            return $this->extractBetweenDelimiters($input, $leftDelim, $rightDelim, $occurrence + 1);
        }
        
        return $content;
    }
    
    /**
     * Find nth occurrence of needle in haystack
     */
    private function findNthOccurrence(string $haystack, string $needle, int $nth, int $offset = 0): int|false {
        for ($i = 0; $i < $nth; $i++) {
            $pos = strpos($haystack, $needle, $offset);
            if ($pos === false) {
                return false;
            }
            $offset = $pos + 1;
        }
        return $pos ?? false;
    }
    
    /**
     * Parse variables and call appropriate syntax function
     */
    private function parseVariables(string $syntaxString, string $command): string {
        $pattern = '~
        (?(DEFINE)
            (?<nestedBrackets> \[ [^][]* (?:\g<nestedBrackets>[^][]*)*+ ] )
        )
        
        (?:
            \G(?!\A)
          |
            [^[]* \[
        )
        
        [^]["]* 
        (?:
            \g<nestedBrackets> [^][]*
          |
            "(?!,") [^]["]*
        )*+
        \K
        (?:
            ","
          |
            ] (*SKIP)(*F)
        )
        ~x';
        
        // Replace commas with delimiter
        $processed = preg_replace($pattern, '|,|', $syntaxString);
        $processed = str_replace(['[XXX:"', '"end XXX]'], '', $processed);
        
        // Call command function with parsed variables
        $functionName = $command . '_SYNTAX';
        if (function_exists($functionName)) {
            $variables = explode('|,|', trim($processed, '"'));
            return $functionName($variables);
        }
        
        return "<!-- Unknown syntax command: {$command} -->";
    }
    
    /**
     * Write content to file with proper locking
     */
    private function writeFile(string $filename, string $content): bool {
        // Remove existing file
        if (file_exists($filename)) {
            unlink($filename);
        }
        
        $handle = fopen($filename, 'w');
        if (!$handle) {
            return false;
        }
        
        if (flock($handle, LOCK_EX)) {
            fwrite($handle, $content);
            fflush($handle);
            flock($handle, LOCK_UN);
            fclose($handle);
            return true;
        }
        
        fclose($handle);
        return false;
    }
    
    /**
     * Get template filename (for compatibility)
     */
    public function getFilename(): string {
        return $this->filename;
    }
}

// Maintain backward compatibility
class __SYNTAX extends ModernSyntaxEngine {
    public function openfile(string $templateFile): string {
        return $this->openFile($templateFile);
    }
    
    public function Syntax(string $content): string {
        return $this->processSyntax($content);
    }
}
