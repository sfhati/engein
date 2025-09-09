<?php

namespace ModernTemplateEngine\Tests;

use PHPUnit\Framework\TestCase;
use ModernSyntaxEngine;

class TemplateEngineTest extends TestCase
{
    private ModernSyntaxEngine $engine;
    private string $tempDir;
    private string $cacheDir;
    
    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/template_engine_test_' . uniqid();
        $this->cacheDir = $this->tempDir . '/cache';
        
        mkdir($this->tempDir, 0755, true);
        mkdir($this->cacheDir, 0755, true);
        
        $this->engine = new ModernSyntaxEngine($this->cacheDir);
    }
    
    protected function tearDown(): void
    {
        $this->recursiveRemoveDirectory($this->tempDir);
    }
    
    private function recursiveRemoveDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->recursiveRemoveDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
    
    private function createTemplateFile(string $name, string $content): string
    {
        $filename = $this->tempDir . '/' . $name;
        file_put_contents($filename, $content);
        return $filename;
    }
    
    public function testEngineInitialization(): void
    {
        $this->assertInstanceOf(ModernSyntaxEngine::class, $this->engine);
    }
    
    public function testSimpleVariableProcessing(): void
    {
        $template = '[var:"test_variable"end var]';
        $result = $this->engine->processSyntax($template);
        
        $this->assertStringContains('<?php echo $test_variable; ?>', $result);
    }
    
    public function testVariableWithoutEcho(): void
    {
        $template = '[var:"test_variable-var"end var]';
        $result = $this->engine->processSyntax($template);
        
        $this->assertStringContains('$test_variable', $result);
        $this->assertStringNotContains('<?php echo', $result);
    }
    
    public function testSessionVariable(): void
    {
        $template = '[var:"user_id-sess"end var]';
        $result = $this->engine->processSyntax($template);
        
        $this->assertStringContains('$_SESSION[\'user_id\']', $result);
    }
    
    public function testConstantVariable(): void
    {
        $template = '[var:"SITE_NAME-cons"end var]';
        $result = $this->engine->processSyntax($template);
        
        $this->assertStringContains('SITE_NAME', $result);
    }
    
    public function testSimpleConditional(): void
    {
        $template = '[if:"1==1","It works!"end if]';
        $result = $this->engine->processSyntax($template);
        
        $this->assertStringContains('<?php if (1==1) { ?>', $result);
        $this->assertStringContains('It works!', $result);
        $this->assertStringContains('<?php } ?>', $result);
    }
    
    public function testConditionalWithElse(): void
    {
        $template = '[if:"1==2","True[else]False"end if]';
        $result = $this->engine->processSyntax($template);
        
        $this->assertStringContains('<?php if (1==2) { ?>', $result);
        $this->assertStringContains('True', $result);
        $this->assertStringContains('<?php } else { ?>', $result);
        $this->assertStringContains('False', $result);
        $this->assertStringContains('<?php } ?>', $result);
    }
    
    public function testForLoop(): void
    {
        $template = '[for:"i","1","5","Item %i%"end for]';
        $result = $this->engine->processSyntax($template);
        
        $this->assertStringContains('<?php for ($i = 1; $i < 5; $i++) { ?>', $result);
        $this->assertStringContains('Item <?php echo "$i"; ?>', $result);
        $this->assertStringContains('<?php } ?>', $result);
    }
    
    public function testEachLoop(): void
    {
        $template = '[each:"items","%items:key% - %items:val%"end each]';
        $result = $this->engine->processSyntax($template);
        
        $this->assertStringContains('if (is_array($items))', $result);
        $this->assertStringContains('foreach ($items as', $result);
        $this->assertStringContains('<?php echo $k', $result);
        $this->assertStringContains('<?php echo $v', $result);
    }
    
    public function testPhpPlugin(): void
    {
        // Load PHP plugin
        require_once __DIR__ . '/../plugins/php.php';
        
        $template = '[php:"echo \'Hello World\';"end php]';
        $result = $this->engine->processSyntax($template);
        
        $this->assertStringContains('<?php echo \'Hello World\';; ?>', $result);
    }
    
    public function testCommentPlugin(): void
    {
        // Load Comment plugin
        require_once __DIR__ . '/../plugins/comment.php';
        
        $template = 'Before[comment:"This is a comment"end comment]After';
        $result = $this->engine->processSyntax($template);
        
        $this->assertEquals('BeforeAfter', $result);
    }
    
    public function testRawPlugin(): void
    {
        // Load Raw plugin
        require_once __DIR__ . '/../plugins/raw.php';
        
        $template = '[raw:"<script>alert(\'test\');</script>"end raw]';
        $result = $this->engine->processSyntax($template);
        
        $this->assertEquals('<script>alert(\'test\');</script>', $result);
    }
    
    public function testTemplateFileProcessing(): void
    {
        $templateContent = '<html><body>[var:"title"end var]</body></html>';
        $templateFile = $this->createTemplateFile('test.inc', $templateContent);
        
        $result = $this->engine->openFile($templateFile);
        
        $this->assertStringEndsWith('.php', $result);
        $this->assertFileExists($result);
        
        $compiledContent = file_get_contents($result);
        $this->assertStringContains('<?php echo $title; ?>', $compiledContent);
    }
    
    public function testCacheGeneration(): void
    {
        $templateContent = '[var:"cached_var"end var]';
        $templateFile = $this->createTemplateFile('cache_test.inc', $templateContent);
        
        // First compilation
        $result1 = $this->engine->openFile($templateFile);
        $this->assertFileExists($result1);
        
        // Second compilation (should return same cached file)
        $result2 = $this->engine->openFile($templateFile);
        $this->assertEquals($result1, $result2);
    }
    
    public function testNonExistentTemplate(): void
    {
        $result = $this->engine->openFile('/non/existent/template.inc');
        
        $this->assertStringContains('not found', $result);
    }
    
    public function testNestedSyntax(): void
    {
        $template = '[if:"[var:"user_id-var"end var]>0","Logged in"end if]';
        $result = $this->engine->processSyntax($template);
        
        $this->assertStringContains('if ($user_id>0)', $result);
        $this->assertStringContains('Logged in', $result);
    }
    
    public function testSecurityEscaping(): void
    {
        $templateContent = '<?php echo "This should be escaped"; ?>';
        $templateFile = $this->createTemplateFile('security_test.inc', $templateContent);
        
        $engine = new ModernSyntaxEngine($this->cacheDir);
        $result = $engine->openFile($templateFile);
        
        $compiledContent = file_get_contents($result);
        $this->assertStringContains('&lt;?php', $compiledContent);
        $this->assertStringContains('?&gt;', $compiledContent);
    }
    
    /**
     * @dataProvider syntaxExamples
     */
    public function testVariousSyntaxExamples(string $template, string $expectedContains): void
    {
        $result = $this->engine->processSyntax($template);
        $this->assertStringContains($expectedContains, $result);
    }
    
    public function syntaxExamples(): array
    {
        return [
            'simple_var' => ['[var:"name"end var]', '<?php echo $name; ?>'],
            'array_var' => ['[var:"user[name]"end var]', '<?php echo $user[name]; ?>'],
            'session_var' => ['[var:"username-sess"end var]', '$_SESSION[\'username\']'],
            'constant' => ['[var:"VERSION-cons"end var]', 'VERSION'],
            'for_loop' => ['[for:"x","0","3","%x%"end for]', 'for ($x = 0; $x < 3; $x++)'],
            'if_condition' => ['[if:"true","yes"end if]', 'if (true)'],
        ];
    }
}

// Additional Test Classes

class PluginTest extends TestCase
{
    protected function setUp(): void
    {
        // Load all plugins
        $pluginDir = __DIR__ . '/../plugins/';
        $plugins = glob($pluginDir . '*.php');
        
        foreach ($plugins as $plugin) {
            require_once $plugin;
        }
    }
    
    public function testAllPluginsFunctionsExist(): void
    {
        $expectedPlugins = [
            'var_SYNTAX',
            'if_SYNTAX', 
            'for_SYNTAX',
            'each_SYNTAX',
            'include_SYNTAX',
            'php_SYNTAX',
            'comment_SYNTAX',
            'raw_SYNTAX'
        ];
        
        foreach ($expectedPlugins as $function) {
            $this->assertTrue(
                function_exists($function),
                "Plugin function {$function} does not exist"
            );
        }
    }
    
    public function testVarPluginWithDifferentOptions(): void
    {
        $testCases = [
            ['variable'] => '$variable',
            ['variable-var'] => '$variable',
            ['variable-sess'] => '$_SESSION[\'variable\']',
            ['variable-cons'] => 'variable'
        ];
        
        foreach ($testCases as $input => $expected) {
            $result = var_SYNTAX($input);
            $this->assertStringContains($expected, $result);
        }
    }
}

class PerformanceTest extends TestCase
{
    private ModernSyntaxEngine $engine;
    
    protected function setUp(): void
    {
        $this->engine = new ModernSyntaxEngine();
    }
    
    public function testLargeTemplatePerformance(): void
    {
        // Create a large template for performance testing
        $largeTemplate = str_repeat('[var:"test_var_' . random_int(1, 1000) . '"end var]', 1000);
        
        $start = microtime(true);
        $result = $this->engine->processSyntax($largeTemplate);
        $duration = microtime(true) - $start;
        
        // Should process 1000 variables in less than 1 second
        $this->assertLessThan(1.0, $duration, 'Large template processing took too long');
        $this->assertNotEmpty($result);
    }
    
    public function testMemoryUsage(): void
    {
        $memoryBefore = memory_get_usage();
        
        // Process multiple templates
        for ($i = 0; $i < 100; $i++) {
            $template = '[var:"test_' . $i . '"end var][if:"1==1","true"end if]';
            $this->engine->processSyntax($template);
        }
        
        $memoryAfter = memory_get_usage();
        $memoryUsed = $memoryAfter - $memoryBefore;
        
        // Should not use more than 10MB for processing 100 small templates
        $this->assertLessThan(10 * 1024 * 1024, $memoryUsed, 'Memory usage is too high');
    }
}
