<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[var:"page_title"end var] - محرك القوالب الحديث</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .code-example { background: #f8f9fa; border-left: 4px solid #007bff; }
        .result-box { background: #d4edda; border: 1px solid #c3e6cb; }
        .error-box { background: #f8d7da; border: 1px solid #f5c6cb; }
        .sidebar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover:hover { transform: translateY(-2px); transition: transform 0.2s; }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar text-white p-4">
                <h4><i class="bi bi-gear-fill"></i> محرك القوالب</h4>
                <p class="small">إصدار محدث للعمل مع PHP 8+</p>
                
                <nav class="nav flex-column mt-4">
                    [each:"navigation_items","
                        <a class=\"nav-link text-white-50 
                        [if:\"%navigation_items:val[active]-var%==true\",\"active text-white\"end if]\" 
                        href=\"%navigation_items:val[url]%\">
                            <i class=\"%navigation_items:val[icon]%\"></i>
                            %navigation_items:val[title]%
                        </a>
                    "end each]
                </nav>
                
                <div class="mt-5">
                    <h6>معلومات النظام</h6>
                    <small class="text-white-50">
                        PHP: [var:"PHP_VERSION-cons"end var]<br>
                        الوقت: [date:"Y-m-d H:i:s","now"end date]<br>
                        المستخدم: [var:"current_user-sess"end var]
                    </small>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>[var:"page_title"end var]</h1>
                    <div>
                        [if:"[var:"user_logged_in-sess-var"end var]==true","
                            <div class=\"dropdown\">
                                <button class=\"btn btn-outline-primary dropdown-toggle\" type=\"button\" data-bs-toggle=\"dropdown\">
                                    <i class=\"bi bi-person-circle\"></i>
                                    [var:"username-sess"end var]
                                </button>
                                <ul class=\"dropdown-menu\">
                                    <li><a class=\"dropdown-item\" href=\"#\">الملف الشخصي</a></li>
                                    <li><a class=\"dropdown-item\" href=\"#\">الإعدادات</a></li>
                                    <li><hr class=\"dropdown-divider\"></li>
                                    <li><a class=\"dropdown-item\" href=\"#\">تسجيل الخروج</a></li>
                                </ul>
                            </div>
                        [else]
                            <a href=\"login.php\" class=\"btn btn-primary\">
                                <i class=\"bi bi-box-arrow-in-right\"></i>
                                تسجيل الدخول
                            </a>
                        "end if]
                    </div>
                </div>
                
                <!-- Alert Messages -->
                [if:"[var:"success_message-sess-var"end var]","
                    <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
                        <i class=\"bi bi-check-circle-fill\"></i>
                        [var:"success_message-sess"end var]
                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
                    </div>
                "end if]
                
                [if:"[var:"error_message-sess-var"end var]","
                    <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
                        <i class=\"bi bi-exclamation-triangle-fill\"></i>
                        [var:"error_message-sess"end var]
                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
                    </div>
                "end if]
                
                <!-- Features Section -->
                <div class="row mb-5">
                    <div class="col-md-12">
                        <h2>الميزات الجديدة</h2>
                        <div class="row">
                            [each:"features","
                                <div class=\"col-md-6 col-lg-4 mb-4\">
                                    <div class=\"card h-100 card-hover\">
                                        <div class=\"card-body\">
                                            <div class=\"d-flex align-items-center mb-3\">
                                                <i class=\"%features:val[icon]% fs-2 text-primary me-3\"></i>
                                                <h5 class=\"card-title mb-0\">%features:val[title]%</h5>
                                            </div>
                                            <p class=\"card-text\">%features:val[description]%</p>
                                            [if:\"%features:val[new]-var%==true\",\"<span class='badge bg-success'>جديد</span>\"end if]
                                        </div>
                                    </div>
                                </div>
                            "end each]
                        </div>
                    </div>
                </div>
                
                <!-- Code Examples -->
                <div class="row">
                    <div class="col-md-12">
                        <h2>أمثلة الاستخدام</h2>
                        
                        <!-- Variable Example -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="bi bi-code-slash"></i> مثال: المتغيرات</h5>
                            </div>
                            <div class="card-body">
                                <div class="code-example p-3 rounded mb-3">
                                    <code>[var:"username"end var]</code>
                                </div>
                                <div class="result-box p-3 rounded">
                                    <strong>النتيجة:</strong> [var:"demo_username"end var]
                                </div>
                            </div>
                        </div>
                        
                        <!-- Loop Example -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="bi bi-arrow-repeat"></i> مثال: الحلقات</h5>
                            </div>
                            <div class="card-body">
                                <div class="code-example p-3 rounded mb-3">
                                    <code>[for:"i","1","4","&lt;span class=\"badge bg-secondary me-1\"&gt;%i%&lt;/span&gt;"end for]</code>
                                </div>
                                <div class="result-box p-3 rounded">
                                    <strong>النتيجة:</strong> 
                                    [for:"i","1","4","<span class=\"badge bg-secondary me-1\">%i%</span>"end for]
                                </div>
                            </div>
                        </div>
                        
                        <!-- Array Example -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="bi bi-list-ul"></i> مثال: المصفوفات</h5>
                            </div>
                            <div class="card-body">
                                <div class="code-example p-3 rounded mb-3">
                                    <code>[each:"demo_items","&lt;li&gt;%demo_items:key% - %demo_items:val%&lt;/li&gt;"end each]</code>
                                </div>
                                <div class="result-box p-3 rounded">
                                    <strong>النتيجة:</strong>
                                    <ul class="mb-0">
                                        [each:"demo_items","<li>%demo_items:key% - %demo_items:val%</li>"end each]
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Conditional Example -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="bi bi-question-circle"></i> مثال: الشروط</h5>
                            </div>
                            <div class="card-body">
                                <div class="code-example p-3 rounded mb-3">
                                    <code>[if:"[var:"is_admin-var"end var]==true","&lt;span class=\"text-success\"&gt;مدير&lt;/span&gt;[else]&lt;span class=\"text-muted\"&gt;مستخدم عادي&lt;/span&gt;"end if]</code>
                                </div>
                                <div class="result-box p-3 rounded">
                                    <strong>النتيجة:</strong> 
                                    [if:"[var:"is_admin-var"end var]==true","<span class=\"text-success\">مدير</span>[else]<span class=\"text-muted\">مستخدم عادي</span>"end if]
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="row mt-5">
                    <div class="col-md-12">
                        <h2>إحصائيات</h2>
                        <div class="row">
                            [each:"statistics","
                                <div class=\"col-md-3 col-sm-6 mb-3\">
                                    <div class=\"card text-center\">
                                        <div class=\"card-body\">
                                            <i class=\"%statistics:val[icon]% fs-1 text-primary mb-2\"></i>
                                            <h3 class=\"card-title\">%statistics:val[value]%</h3>
                                            <p class=\"card-text text-muted\">%statistics:val[label]%</p>
                                        </div>
                                    </div>
                                </div>
                            "end each]
                        </div>
                    </div>
                </div>
                
                <!-- Include Example -->
                <div class="mt-5">
                    <h2>مثال: إدراج القوالب</h2>
                    <div class="card">
                        <div class="card-body">
                            [comment:"This will include footer template"end comment]
                            [include:"template_include"end include]
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p>&copy; [date:"Y","now"end date] محرك القوالب الحديث. جميع الحقوق محفوظة.</p>
            <p class="small">
                تم التطوير باستخدام PHP [var:"PHP_VERSION-cons"end var] 
                | آخر تحديث: [date:"d/m/Y","now"end date]
            </p>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    [raw:"
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Add smooth scrolling
        document.querySelectorAll('a[href^=\"#\"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
    "end raw]
</body>
</html>
