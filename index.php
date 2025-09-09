<?php
/**
 * Updated Template Engine Demo
 * Compatible with PHP 8+
 */

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the updated syntax engine
require_once 'syntax.php';

// Initialize the template engine
$syntaxcode = new ModernSyntaxEngine();

// Load all plugins
$pluginDir = __DIR__ . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR;

if (is_dir($pluginDir)) {
    $pluginFiles = scandir($pluginDir);
    
    if ($pluginFiles !== false) {
        foreach ($pluginFiles as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $fullPath = $pluginDir . $file;
            if (is_file($fullPath) && str_ends_with($file, '.php')) {
                require_once $fullPath;
            }
        }
    }
}

/**
 * Process template file and return rendered content
 * 
 * @param string $templateName Template file name (with or without .inc extension)
 * @return string Rendered template content
 */
function includeFileTemplate(string $templateName): string {
    global $syntaxcode;
    
    try {
        // Add .inc extension if not present
        if (!str_ends_with($templateName, '.inc')) {
            $templateName .= '.inc';
        }
        
        // Compile template and get PHP file path
        $compiledFile = $syntaxcode->openFile($templateName);
        
        // Check if compilation was successful
        if (!str_ends_with($compiledFile, '.php')) {
            return "<div class=\"error\">Template compilation error: {$compiledFile}</div>";
        }
        
        // Capture output from compiled template
        ob_start();
        include $compiledFile;
        return ob_get_clean();
        
    } catch (Throwable $e) {
        return "<div class=\"error\">Template processing error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

/**
 * Set some example variables for testing
 */
$variable = "Hello, World!";
$items = [
    'item1' => 'First Item',
    'item2' => 'Second Item', 
    'item3' => 'Third Item'
];

// Example session data
if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['user_name'] = 'John Doe';
$_SESSION['user_items'] = ['Admin', 'Editor', 'User'];

// Process the main template
try {
    $renderedTemplate = includeFileTemplate('template/template.inc');
    echo $renderedTemplate;
} catch (Throwable $e) {
    echo "<div class=\"error\">Fatal error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

/**
 * Example of direct template processing
 */
function processTemplateString(string $templateString): string {
    global $syntaxcode;
    
    try {
        return $syntaxcode->processSyntax($templateString);
    } catch (Throwable $e) {
        return "Error processing template: " . htmlspecialchars($e->getMessage());
    }
}

// Example usage:
// $result = processTemplateString('[var:"variable"end var] - [if:"1==1","It works!"end if]');
// echo $result;
