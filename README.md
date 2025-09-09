# Modern Template Engine (Updated)

محرك قوالب PHP محدث للعمل مع أحدث إصدارات PHP (8.0+)

## التحديثات الرئيسية

### 1. توافق مع PHP الحديث
- **إصلاح الدوال المهجورة**: تم استبدال `each()` و `split()` بدوال حديثة
- **تحسين معالجة الأخطاء**: استخدام `Throwable` و `try-catch` بشكل أفضل
- **Type Declarations**: إضافة تحديد أنواع البيانات للدوال والمعاملات
- **Modern PHP Features**: استخدام `str_contains()`, `str_starts_with()`, `str_ends_with()`

### 2. تحسينات الأمان
- **منع Code Injection**: تحسين تعقيم البيانات المدخلة
- **Path Traversal Protection**: حماية أفضل من الوصول لملفات خارج النطاق
- **Session Security**: تحسين التعامل مع متغيرات الجلسة

### 3. تحسينات الأداء
- **Caching المحسن**: نظام أفضل لحفظ الملفات المترجمة
- **Memory Management**: استخدام أمثل للذاكرة
- **File Handling**: تحسين عمليات قراءة وكتابة الملفات

## التثبيت والاستخدام

### التثبيت الأساسي
```php
<?php
require_once 'syntax.php';
$engine = new ModernSyntaxEngine();
```

### التثبيت مع مجلد cache مخصص
```php
<?php
require_once 'syntax.php';
$engine = new ModernSyntaxEngine('/path/to/cache/');
```

### تحميل الإضافات تلقائياً
```php
<?php
require_once 'syntax.php';
$engine = new ModernSyntaxEngine();

// تحميل جميع إضافات من مجلد plugin
$pluginDir = __DIR__ . '/plugin/';
if (is_dir($pluginDir)) {
    foreach (scandir($pluginDir) as $file) {
        if (str_ends_with($file, '.php')) {
            require_once $pluginDir . $file;
        }
    }
}
```

## أمثلة الاستخدام

### 1. إضافة المتغيرات (Variable Plugin)
```html
<!-- طباعة متغير -->
[var:"username"end var]

<!-- متغير بدون طباعة -->
[var:"username-var"end var]

<!-- متغير جلسة -->
[var:"user_id-sess"end var]

<!-- متغير جلسة بدون طباعة -->
[var:"user_id-sess-var"end var]

<!-- متغير ثابت -->
[var:"SITE_NAME-cons"end var]

<!-- عنصر من مصفوفة -->
[var:"user[name]"end var]
```

### 2. الشروط (If Plugin)
```html
[if:"[var:"user_id-var"end var] > 0","
    <p>مرحباً بالمستخدم رقم [var:"user_id"end var]</p>
    [else]
    <p>مرحباً زائر!</p>
"end if]
```

### 3. الحلقات (For Plugin)
```html
[for:"i","1","6","
    <div class=\"item-[var:"i-var"end var]\">
        العنصر رقم %i%
    </div>
"end for]
```

### 4. تكرار المصفوفات (Each Plugin)
```html
[each:"users","
    <div class=\"user\">
        <h3>%users:key%</h3>
        <p>%users:val%</p>
        <small>العنصر رقم: %users:#%</small>
    </div>
"end each]

<!-- مع مصفوفة الجلسة -->
[each:"menu_items-sess","
    <li><a href=\"%menu_items:val[url]%\">%menu_items:val[title]%</a></li>
"end each]
```

### 5. إدراج القوالب (Include Plugin)
```html
<!-- من نفس المجلد -->
[include:"header"end include]

<!-- من مجلد فرعي -->
[include:"components/sidebar"end include]

<!-- باستخدام متغيرات النظام -->
[include:"{template}footer"end include]
[include:"{theme}custom-header"end include]
```

### 6. كود PHP مباشر (PHP Plugin)
```html
[php:"$current_time = date('Y-m-d H:i:s')"end php]
[php:"echo 'الوقت الحالي: ' . $current_time"end php]
```

### 7. التعليقات (Comment Plugin)
```html
[comment:"هذا تعليق لن يظهر في الناتج النهائي"end comment]
```

### 8. المحتوى الخام (Raw Plugin)
```html
[raw:"<script>console.log('JavaScript code');</script>"end raw]
```

## الدوال المساعدة

### معالجة قالب من نص
```php
function processTemplateString(string $template): string {
    global $engine;
    return $engine->processSyntax($template);
}

$result = processTemplateString('[var:"name"end var] - [if:"1==1","تم!"end if]');
```

### معالجة ملف قالب
```php
function renderTemplate(string $templateFile): string {
    global $engine;
    
    try {
        if (!str_ends_with($templateFile, '.inc')) {
            $templateFile .= '.inc';
        }
        
        $compiledFile = $engine->openFile($templateFile);
        
        if (!str_ends_with($compiledFile, '.php')) {
            return "خطأ في ترجمة القالب: {$compiledFile}";
        }
        
        ob_start();
        include $compiledFile;
        return ob_get_clean();
        
    } catch (Throwable $e) {
        return "خطأ: " . $e->getMessage();
    }
}
```

## المتغيرات العامة المدعومة

يمكن تعريف مسارات النظام للاستخدام في قوالب الإدراج:

```php
define('TEMPLATE_PATH', __DIR__ . '/templates/');
define('PLUGIN_PATH', __DIR__ . '/plugins/');
define('CACHE_PATH', __DIR__ . '/cache/');
define('TMP_PATH', __DIR__ . '/tmp/');
define('THEME_PATH', __DIR__ . '/themes/');
define('UPLOADED_PATH', __DIR__ . '/uploads/');
```

## إنشاء إضافات مخصصة

### بنية الإضافة الأساسية
```php
<?php
/**
 * Custom Plugin Example
 * Usage: [mycommand:"param1","param2"end mycommand]
 */
function mycommand_SYNTAX(array $vars): string {
    global $syntaxcode;
    
    // معالجة المعاملات
    $param1 = $vars[0] ?? '';
    $param2 = $vars[1] ?? '';
    
    // معالجة الأوامر المتداخلة
    if (isset($syntaxcode) && method_exists($syntaxcode, 'processSyntax')) {
        $param1 = $syntaxcode->processSyntax($param1);
        $param2 = $syntaxcode->processSyntax($param2);
    }
    
    // منطق الإضافة
    return "<div class=\"custom\">{$param1} - {$param2}</div>";
}
```

### مثال إضافة متقدمة - Date Plugin
```php
<?php
/**
 * Date formatting plugin
 * Usage: [date:"Y-m-d H:i:s","now"end date]
 * Usage: [date:"d/m/Y","2023-12-25"end date]
 */
function date_SYNTAX(array $vars): string {
    global $syntaxcode;
    
    $format = $vars[0] ?? 'Y-m-d';
    $timestamp = $vars[1] ?? 'now';
    
    // معالجة الأوامر المتداخلة
    if (isset($syntaxcode) && method_exists($syntaxcode, 'processSyntax')) {
        $format = $syntaxcode->processSyntax($format);
        $timestamp = $syntaxcode->processSyntax($timestamp);
    }
    
    try {
        if ($timestamp === 'now') {
            $date = new DateTime();
        } else {
            $date = new DateTime($timestamp);
        }
        
        return $date->format($format);
    } catch (Exception $e) {
        return "خطأ في التاريخ: " . $e->getMessage();
    }
}
```

### مثال إضافة - URL Plugin
```php
<?php
/**
 * URL generation plugin
 * Usage: [url:"page","param=value&param2=value2"end url]
 */
function url_SYNTAX(array $vars): string {
    global $syntaxcode;
    
    $page = $vars[0] ?? '';
    $params = $vars[1] ?? '';
    
    if (isset($syntaxcode) && method_exists($syntaxcode, 'processSyntax')) {
        $page = $syntaxcode->processSyntax($page);
        $params = $syntaxcode->processSyntax($params);
    }
    
    $baseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
    $url = $baseUrl . '/' . ltrim($page, '/');
    
    if (!empty($params)) {
        $url .= '?' . $params;
    }
    
    return htmlspecialchars($url);
}
```

## استكشاف الأخطاء وإصلاحها

### الأخطاء الشائعة وحلولها

1. **خطأ: Template file not found**
   - تأكد من صحة مسار الملف
   - تأكد من وجود الملف بامتداد `.inc`

2. **خطأ: Function not found**
   - تأكد من تحميل جميع ملفات الإضافات
   - تأكد من صحة اسم الأمر في القالب

3. **خطأ في الذاكرة**
   - تحقق من وجود حلقات لا نهائية في القوالب
   - قم بزيادة `memory_limit` في PHP

4. **خطأ في الصلاحيات**
   - تأكد من صلاحيات الكتابة على مجلد `cache`
   - تأكد من صلاحيات القراءة على ملفات القوالب

### تفعيل وضع التتبع
```php
<?php
// تفعيل عرض الأخطاء
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
```

## الأداء والتحسينات

### نصائح للأداء الأمثل

1. **استخدام Cache بفعالية**
   ```php
   // تنظيف Cache القديم دورياً
   $engine->clearOldCache(7 * 24 * 3600); // أسبوع
   ```

2. **تجميع القوالب مسبقاً**
   ```php
   // ترجمة جميع القوالب مسبقاً
   $templates = ['header', 'footer', 'sidebar', 'main'];
   foreach ($templates as $template) {
       $engine->openFile("templates/{$template}.inc");
   }
   ```

3. **تحسين الاستعلامات**
   ```php
   // تجنب الاستعلامات داخل الحلقات
   // بدلاً من:
   // [each:"users","[var:"user_data[%users:val-var%]"end var]"end each]
   
   // استخدم:
   // معالجة البيانات مسبقاً في PHP ثم تمريرها للقالب
   ```

## الترقية من الإصدار القديم

### خطوات الترقية

1. **نسخ احتياطي**
   ```bash
   cp -r old_engine/ backup_engine/
   ```

2. **استبدال الملفات الأساسية**
   - استبدل `syntax.php` بالإصدار الجديد
   - احتفظ بملفات القوالب الموجودة

3. **تحديث ملفات الإضافات**
   - استبدل ملفات `/plugin/` بالإصدارات الجديدة
   - أو قم بدمج التحديثات يدوياً

4. **تحديث الكود**
   ```php
   // القديم
   $syntaxcode = new __SYNTAX();
   
   // الجديد (مع دعم للقديم)
   $syntaxcode = new ModernSyntaxEngine();
   // أو للتوافق العكسي:
   $syntaxcode = new __SYNTAX();
   ```

5. **اختبار الوظائف**
   - اختبر جميع القوالب الموجودة
   - تحقق من عمل جميع الإضافات
   - اختبر الأداء والذاكرة

## الترخيص والدعم

- **الترخيص**: LGPL 2.1
- **المطور الأصلي**: Bassam al-essawi
- **التحديثات**: محدث للعمل مع PHP 8+
- **الدعم**: يمكن الحصول على الدعم من خلال GitHub Issues

## مساهمات المجتمع

نرحب بالمساهمات! يمكنك:

1. **الإبلاغ عن الأخطاء**: عبر GitHub Issues
2. **اقتراح تحسينات**: عبر Pull Requests
3. **إنشاء إضافات جديدة**: شارك إضافاتك المخصصة
4. **تحسين الوثائق**: ساعد في تحسين الدليل

## أمثلة عملية

### مثال: نظام التعليقات
```html
<!-- template/comments.inc -->
<div class="comments">
    <h3>التعليقات ([var:"comment_count"end var])</h3>
    
    [each:"comments","
        <div class=\"comment\">
            <div class=\"comment-header\">
                <strong>%comments:val[author]%</strong>
                <span class=\"date\">[date:"d/m/Y H:i","%comments:val[date]-var%"end date]</span>
            </div>
            <div class=\"comment-body\">
                %comments:val[content]%
            </div>
        </div>
    "end each]
</div>
```

### مثال: نظام القوائم الديناميكي
```html
<!-- template/menu.inc -->
<nav class="menu">
    [each:"menu_items","
        <a href=\"%menu_items:val[url]%\" 
           [if:"%menu_items:val[active]-var%==1","class=\"active\""end if]>
            %menu_items:val[title]%
        </a>
    "end each]
</nav>
```

---

**ملاحظة**: هذا الإصدار المحدث يحافظ على التوافق العكسي مع الكود القديم مع إضافة ميزات وتحسينات للعمل مع أحدث إصدارات PHP.
