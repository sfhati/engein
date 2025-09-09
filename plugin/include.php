<?php

/*
  use like [include:"template_file_without.inc"end include]
  [include:"temp"end include] //get content from template file name temp.inc in same folder of template source
  [include:"../temp"end include]
  [include:"../temp.inc"end include]
  [include:"{template}temp"end include] // this value {template} use in sfhati framework to get template folder
  [include:"{plugin}temp"end include] // this value {template} use in sfhati framework to get plugin folder
  [include:"{tmp}temp"end include] // this value {template} use in sfhati framework to get tmp folder
  [include:"{cache}temp"end include] // this value {template} use in sfhati framework to get cache folder
  [include:"{uploaded}temp"end include] // this value {template} use in sfhati framework to get uploaded folder
 */
function include_SYNTAX(array $vars): string {
    global $syntaxcode;
    
    $templateFile = $vars[0] ?? '';
    
    if (isset($syntaxcode) && method_exists($syntaxcode, 'processSyntax')) {
        $templateFile = $syntaxcode->processSyntax($templateFile);
    }
    
    // Add .inc extension if not present
    if (!str_ends_with($templateFile, '.inc')) {
        $templateFile .= '.inc';
    }
    
    // Handle framework path placeholders
    $pathReplacements = [
        '{plugin}' => defined('PLUGIN_PATH') ? PLUGIN_PATH : 'plugin/',
        '{template}' => defined('TEMPLATE_PATH') ? TEMPLATE_PATH : 'template/',
        '{tmp}' => defined('TMP_PATH') ? TMP_PATH : 'tmp/',
        '{cache}' => defined('CACHE_PATH') ? CACHE_PATH : 'cache/',
        '{uploaded}' => defined('UPLOADED_PATH') ? UPLOADED_PATH : 'uploaded/',
        '{theme}' => defined('THEME_PATH') ? THEME_PATH : 'theme/'
    ];
    
    $templateFile = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $templateFile);
    
    // Determine full path
    if (str_contains($templateFile, '}') || str_starts_with($templateFile, '/') || str_contains($templateFile, ':\\')) {
        $fullPath = $templateFile;
    } else {
        $currentDir = isset($syntaxcode) && method_exists($syntaxcode, 'getFilename') 
            ? dirname($syntaxcode->getFilename()) 
            : dirname(__FILE__);
        $fullPath = rtrim($currentDir, '/\\') . DIRECTORY_SEPARATOR . $templateFile;
    }
    
    // Include file content if exists
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        if ($content !== false && isset($syntaxcode) && method_exists($syntaxcode, 'processSyntax')) {
            return $syntaxcode->processSyntax($content);
        }
        return $content ?: '';
    }
    
    return "<div class=\"template-error\">Warning: Template file not found: {$fullPath}</div>";
}
