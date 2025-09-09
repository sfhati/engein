<?php
/**
 * Complete Demo Implementation
 * Modern Template Engine for PHP 8+
 * 
 * This file demonstrates the complete usage of the updated template engine
 * with all features and improvements.
 */

// Enable comprehensive error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Set memory limit for large templates
ini_set('memory_limit', '256M');

try {
    // Include demo data first
    require_once 'demo_data.php';
    
    // Include the updated syntax engine
    require_once 'syntax.php';
    
    // Initialize the template engine with custom cache path
    $cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
    $syntaxcode = new ModernSyntaxEngine($cacheDir);
    
    // Load all plugins dynamically
    $pluginDir = __DIR__ . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR;
    $loadedPlugins = [];
    
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
                    $loadedPlugins[] = basename($file, '.php');
                }
            }
        }
    }
    
    /**
     * Enhanced template processing function
     */
    function processTemplate(string $templateName, array $variables = []): string {
        global $syntaxcode;
        
        try {
            // Extract variables to current scope
            foreach ($variables as $name => $value) {
                $GLOBALS[$name] = $value;
            }
            
            // Add .inc extension if not present
            if (!str_ends_with($templateName, '.inc')) {
                $templateName .= '.inc';
            }
            
            // Compile template
            $compiledFile = $syntaxcode->openFile($templateName);
            
            // Check compilation result
            if (!str_ends_with($compiledFile, '.php')) {
                throw new RuntimeException("Template compilation failed: {$compiledFile}");
            }
            
            // Check if compiled file exists and is readable
            if (!is_file($compiledFile) || !is_readable($compiledFile)) {
                throw new RuntimeException("Compiled template file is not accessible: {$compiledFile}");
            }
            
            // Capture output
            ob_start();
            
            // Include with error handling
            $includeResult = include $compiledFile;
            
            if ($includeResult === false) {
                ob_end_clean();
                throw new RuntimeException("Failed to include compiled template: {$compiledFile}");
            }
            
            $output = ob_get_clean();
            
            // Log successful processing in debug mode
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                error_log("Successfully processed template: {$templateName}");
            }
            
            return $output;
            
        } catch (Throwable $e) {
            // Clean output buffer if still active
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            // Log error
            error_log("Template processing error: " . $e->getMessage());
            
            // Return user-friendly error message
            return sprintf(
                '<div class="alert alert-danger"><h4>خطأ في معالجة القالب</h4><p>%s</p><small>الملف: %s</small></div>',
                htmlspecialchars($e->getMessage()),
                htmlspecialchars($templateName)
            );
        }
    }
    
    /**
     * Process template string directly
     */
    function processTemplateString(string $templateString, array $variables = []): string {
        global $syntaxcode;
        
        try {
            // Extract variables to current scope
            foreach ($variables as $name => $value) {
                $GLOBALS[$name] = $value;
            }
            
            return $syntaxcode->processSyntax($templateString);
            
        } catch (Throwable $e) {
            error_log("Template string processing error: " . $e->getMessage());
            return sprintf(
                '<div class="alert alert-warning">خطأ في معالجة النص: %s</div>',
                htmlspecialchars($e->getMessage())
            );
        }
    }
    
    /**
     * Get template engine statistics
     */
    function getEngineStats(): array {
        global $cacheDir, $loadedPlugins;
        
        $stats = [
            'cache_dir' => $cacheDir,
            'cache_files' => 0,
            'cache_size' => 0,
            'loaded_plugins' => count($loadedPlugins),
            'plugin_list' => $loadedPlugins,
            'php_version' => PHP_VERSION,
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true)
        ];
        
        // Count cache files and calculate size
        if (is_dir($cacheDir)) {
            $files = glob($cacheDir . '*.php');
            if ($files) {
                $stats['cache_files'] = count($files);
                foreach ($files as $file) {
                    $stats['cache_size'] += filesize($file);
                }
            }
        }
        
        return $stats;
    }
    
    /**
     * Clear template cache
     */
    function clearTemplateCache(): bool {
        global $cacheDir;
        
        try {
            if (!is_dir($cacheDir)) {
                return true; // Nothing to clear
            }
            
            $files = glob($cacheDir . '*.php');
            if ($files) {
                foreach ($files as $file) {
                    if (!unlink($file)) {
                        throw new RuntimeException("Failed to delete cache file: {$file}");
                    }
                }
            }
            
            return true;
            
        } catch (Throwable $e) {
            error_log("Cache clearing error: " . $e->getMessage());
            return false;
        }
    }
    
    // Handle cache clearing request
    if (isset($_GET['action']) && $_GET['action'] === 'clear_cache') {
        $clearResult = clearTemplateCache();
        $_SESSION['cache_cleared'] = $clearResult;
        
        // Redirect to prevent resubmission
        $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?');
        header("Location: {$redirectUrl}");
        exit;
    }
    
    // Display cache clearing message
    if (isset($_SESSION['cache_cleared'])) {
        if ($_SESSION['cache_cleared']) {
            $_SESSION['success_message'] = 'تم مسح ذاكرة التخزين المؤقت بنجاح!';
        } else {
            $_SESSION['error_message'] = 'فشل في مسح ذاكرة التخزين المؤقت!';
        }
        unset($_SESSION['cache_cleared']);
    }
    
    // Get engine statistics
    $engine_stats = getEngineStats();
    
    // Add engine stats to global variables
    $GLOBALS['engine_stats'] = $engine_stats;
    
    // Define some constants for template use
    define('DEBUG_MODE', true);
    define('ENGINE_VERSION', '2.0');
    define('AUTHOR', 'Updated for Modern PHP');
    
    // Set additional demo variables
    $processing_time_start = microtime(true);
    
    // Process the main template
    echo processTemplate('template/template');
    
    // Calculate processing time
    $processing_time = (microtime(true) - $processing_time_start) * 1000;
    
    // Display debug information if enabled
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        echo sprintf(
            '<div class="alert alert-info mt-3">
                <h5>معلومات التطوير</h5>
                <ul class="mb-0">
                    <li>وقت المعالجة: %.3f مللي ثانية</li>
                    <li>استخدام الذاكرة: %s</li>
                    <li>ذروة الذاكرة: %s</li>
                    <li>الإضافات المحملة: %d</li>
                    <li>ملفات الذاكرة المؤقتة: %d</li>
                </ul>
                <a href="?action=clear_cache" class="btn btn-sm btn-outline-warning mt-2">
                    <i class="bi bi-trash"></i> مسح ذاكرة التخزين المؤقت
                </a>
            </div>',
            $processing_time,
            formatFileSize($engine_stats['memory_usage']),
            formatFileSize($engine_stats['memory_peak']),
            $engine_stats['loaded_plugins'],
            $engine_stats['cache_files']
        );
    }
    
} catch (Throwable $e) {
    // Handle fatal errors gracefully
    http_response_code(500);
    
    echo sprintf(
        '<!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>خطأ في النظام</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-5">
                <div class="alert alert-danger">
                    <h1 class="h4"><i class="bi bi-exclamation-triangle"></i> خطأ في النظام</h1>
                    <p>حدث خطأ غير متوقع أثناء تشغيل محرك القوالب:</p>
                    <hr>
                    <code>%s</code>
                    <hr>
                    <small class="text-muted">
                        الملف: %s<br>
                        السطر: %d<br>
                        الوقت: %s
                    </small>
                </div>
                <div class="mt-3">
                    <a href="javascript:history.back()" class="btn btn-secondary">العودة</a>
                    <a href="?" class="btn btn-primary">إعادة المحاولة</a>
                </div>
            </div>
        </body>
        </html>',
        htmlspecialchars($e->getMessage()),
        htmlspecialchars($e->getFile()),
        $e->getLine(),
        date('Y-m-d H:i:s')
    );
    
    // Log the fatal error
    error_log("Fatal error in template engine: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
}

/**
 * Additional utility functions for advanced usage
 */

/**
 * Compile all templates in a directory
 */
function compileAllTemplates(string $templateDir): array {
    global $syntaxcode;
    
    $results = [];
    
    if (!is_dir($templateDir)) {
        return ['error' => 'Template directory not found'];
    }
    
    $files = glob($templateDir . '/*.inc');
    
    foreach ($files as $file) {
        $templateName = basename($file);
        
        try {
            $compiledFile = $syntaxcode->openFile($file);
            $results[$templateName] = [
                'status' => 'success',
                'compiled_file' => $compiledFile,
                'size' => filesize($compiledFile)
            ];
        } catch (Throwable $e) {
            $results[$templateName] = [
                'status' => 'error', 
                'error' => $e->getMessage()
            ];
        }
    }
    
    return $results;
}

/**
 * Template validation function
 */
function validateTemplate(string $templateContent): array {
    $errors = [];
    $warnings = [];
    
    // Check for unclosed tags
    $openTags = [];
    preg_match_all('/\[(\w+):/', $templateContent, $opens);
    preg_match_all('/end (\w+)\]/', $templateContent, $closes);
    
    $openCounts = array_count_values($opens[1] ?? []);
    $closeCounts = array_count_values($closes[1] ?? []);
    
    foreach ($openCounts as $tag => $count) {
        $closeCount = $closeCounts[$tag] ?? 0;
        if ($count !== $closeCount) {
            $errors[] = "Mismatched tags for '{$tag}': {$count} opening, {$closeCount} closing";
        }
    }
    
    // Check for potential security issues
    if (strpos($templateContent, '<?php') !== false) {
        $warnings[] = 'Direct PHP code found - may be security risk';
    }
    
    // Check for deprecated syntax
    if (preg_match('/\[(\w+):[^"]+end/', $templateContent)) {
        $warnings[] = 'Possible deprecated syntax without quotes detected';
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors,
        'warnings' => $warnings
    ];
}

// End of main execution
?>
