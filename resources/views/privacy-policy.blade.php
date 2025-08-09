<div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
</div>

{{-- resources/views/privacy-policy.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('lang.Privacy Policy') }} - Sprint Skills</title>
    <link rel="shortcut icon" href="{{asset('images/Sprint_Skills.ico')}}" type="image/x-icon"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/root-welcome.css') }}">
    <style>
        * {
            /* Prevent text selection */
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

            /* Prevent drag */
            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
            user-drag: none;

            /* Prevent right-click context menu */
            -webkit-touch-callout: none;

            /* Disable highlighting */
            -webkit-tap-highlight-color: transparent;
        }
        html[dir="rtl"] body { 
            direction: rtl; 
            text-align: right; 
            font-family: 'Cairo', 'Tajawal', sans-serif;
        }
        
        .privacy-hero {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 50%, #2980b9 100%);
            color: white;
            padding: 140px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .privacy-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.8) 0%, rgba(52, 152, 219, 0.6) 50%, rgba(41, 128, 185, 0.8) 100%);
            z-index: 1;
        }
        
        .privacy-hero .container {
            position: relative;
            z-index: 2;
        }
        
        .privacy-hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .privacy-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .privacy-content {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .privacy-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .privacy-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border-left: 5px solid #2F80ED;
        }
        
        .privacy-section {
            margin-bottom: 3rem;
        }
        
        .privacy-section:last-child {
            margin-bottom: 0;
        }
        
        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .section-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #2F80ED, #1565C0);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .section-content {
            color: #555;
            line-height: 1.8;
            font-size: 1.1rem;
        }
        
        .section-content p {
            margin-bottom: 1.5rem;
        }
        
        .section-content ul {
            margin: 1.5rem 0;
            padding-left: 2rem;
        }
        
        html[dir="rtl"] .section-content ul {
            padding-left: 0;
            padding-right: 2rem;
        }
        
        .section-content li {
            margin-bottom: 0.8rem;
            position: relative;
        }
        
        .section-content li::before {
            content: '•';
            color: #2F80ED;
            font-weight: bold;
            position: absolute;
            left: -1.5rem;
        }
        
        html[dir="rtl"] .section-content li::before {
            left: auto;
            right: -1.5rem;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 1px solid #2196f3;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            position: relative;
        }
        
        .highlight-box::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(135deg, #2F80ED, #1565C0);
            border-radius: 20px;
            z-index: -1;
            opacity: 0.1;
        }
        
        .contact-info {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-top: 3rem;
            text-align: center;
        }
        
        .contact-info h3 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .contact-info p {
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }
        
        .contact-email {
            color: #74b9ff;
            text-decoration: none;
            font-weight: 600;
            padding: 0.8rem 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .contact-email:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
        }
        
        .last-updated {
            text-align: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f1f3f4 0%, #e8eaf6 100%);
            border-radius: 15px;
            margin-bottom: 2rem;
            color: #666;
            font-weight: 500;
        }
        
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #2F80ED, #1565C0);
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(47, 128, 237, 0.3);
        }
        
        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(47, 128, 237, 0.4);
        }
        
        html[dir="rtl"] .back-to-top {
            right: auto;
            left: 2rem;
        }
        
        @media (max-width: 768px) {
            .privacy-hero {
                padding: 120px 0 60px;
            }
            
            .privacy-hero h1 {
                font-size: 2.5rem;
            }
            
            .privacy-content {
                padding: 60px 0;
            }
            
            .privacy-card {
                padding: 2rem;
                margin: 0 1rem 2rem;
            }
            
            .section-title {
                font-size: 1.5rem;
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }
            
            .section-content {
                font-size: 1rem;
            }
            
            .section-content ul {
                padding-left: 1.5rem;
            }
            
            html[dir="rtl"] .section-content ul {
                padding-left: 0;
                padding-right: 1.5rem;
            }
        }
    </style>
</head>
<body>

    @guest
    <header id="header">
        <div class="container">
            <nav>
                <div class="logo">
                    <img class="logo-img" src="{{asset('images/Sprint_Skills_Logo_NoText.png')}}" alt="logo">
                    <span style="color: rgb(26, 89, 123); font-size: 22px">{{ __('Sprint Skills') }}</span>
                </div>
                <ul class="nav-links">
                    <li><a href="{{ route('welcome') }}">{{ __('lang.Home') }}</a></li>
                    <li><a href="{{ route('about') }}">{{ __('lang.About') }}</a></li>
                    <li><a href="{{ route('contact') }}">{{ __('lang.Contact') }}</a></li>
                    <li><a href="{{ route('faq') }}">{{ __('lang.FAQ') }}</a></li>
                </ul>
                <div class="header-actions">
                    <div class="language-switcher">
                        <a href="{{ route('locale.set', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                        <a href="{{ route('locale.set', 'ar') }}" class="{{ app()->getLocale() == 'ar' ? 'active' : '' }}">AR</a>
                    </div>
                    <form action="{{route('login')}}" method="GET">
                        @csrf
                        <button class="cta-button" type="submit">{{ __('lang.Get Started') }}</button>
                    </form>
                </div>
                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>
    @endguest

    <!-- Privacy Policy Hero Section -->
    <section class="privacy-hero">
        <div class="container">
            <h1>{{ __('lang.Privacy Policy') }}</h1>
            <p>{{ __('lang.Your privacy and data protection are our top priorities') }}</p>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="privacy-content">
        <div class="container">
            <div class="privacy-container">
                
                <!-- Last Updated -->
                <div class="last-updated">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <strong>{{ __('lang.Last Updated') }}:</strong> {{ __('lang.January 2025') }}
                </div>

                <div class="privacy-card">
                    @if(app()->getLocale() == 'en')
                        <!-- English Content -->
                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                Our Commitment
                            </div>
                            <div class="section-content">
                                <p>Sprint Skills ("we," "us," or "our") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.</p>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-database"></i>
                                </div>
                                Information We Collect
                            </div>
                            <div class="section-content">
                                <p>We collect the following types of information to provide and improve our services:</p>
                                <ul>
                                    <li><strong>Personal Information:</strong> Name, email address, and optional phone number</li>
                                    <li><strong>Usage Data:</strong> Logs of your activity on our platform, including courses accessed and progress</li>
                                    <li><strong>Technical Data:</strong> Cookies, IP address, browser type, and device information for analytics</li>
                                    <li><strong>Communication Data:</strong> Messages you send through our contact forms or support channels</li>
                                </ul>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                How We Use Your Information
                            </div>
                            <div class="section-content">
                                <p>We use your information for the following purposes:</p>
                                <ul>
                                    <li>Create and manage your account</li>
                                    <li>Deliver courses and educational content</li>
                                    <li>Personalize your learning experience</li>
                                    <li>Improve our platform and services</li>
                                    <li>Send service-related notices and updates</li>
                                    <li>Provide customer support and respond to inquiries</li>
                                    <li>Ensure security and prevent fraud</li>
                                </ul>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-share-alt"></i>
                                </div>
                                Information Sharing
                            </div>
                            <div class="section-content">
                                <div class="highlight-box">
                                    <p><strong>We never sell your personal data.</strong> Your trust is paramount to us.</p>
                                </div>
                                <p>We may share your information only in the following circumstances:</p>
                                <ul>
                                    <li><strong>Service Providers:</strong> Trusted third-party partners (payment processors, analytics tools) who must maintain confidentiality</li>
                                    <li><strong>Legal Requirements:</strong> When required by law or to protect our rights and safety</li>
                                    <li><strong>Business Transfers:</strong> In connection with mergers or acquisitions (with prior notice)</li>
                                    <li><strong>With Your Consent:</strong> Any other sharing will require your explicit permission</li>
                                </ul>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                Security Measures
                            </div>
                            <div class="section-content">
                                <p>We implement industry-standard security measures to protect your information:</p>
                                <ul>
                                    <li>End-to-end encryption for data transmission</li>
                                    <li>Secure data storage with access controls</li>
                                    <li>Regular security audits and updates</li>
                                    <li>Employee training on data protection</li>
                                    <li>Monitoring for unauthorized access attempts</li>
                                </ul>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                Your Rights
                            </div>
                            <div class="section-content">
                                <p>You have the following rights regarding your personal data:</p>
                                <ul>
                                    <li><strong>Access:</strong> View all personal data we have about you</li>
                                    <li><strong>Update:</strong> Correct or modify your information</li>
                                    <li><strong>Download:</strong> Export your data in a portable format</li>
                                    <li><strong>Delete:</strong> Request complete removal of your data</li>
                                    <li><strong>Restrict:</strong> Limit how we process your information</li>
                                    <li><strong>Object:</strong> Opt out of certain data processing activities</li>
                                </ul>
                                <p>To exercise any of these rights, please contact us at the email address provided below.</p>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-cookie-bite"></i>
                                </div>
                                Cookies and Tracking
                            </div>
                            <div class="section-content">
                                <p>We use cookies and similar technologies for:</p>
                                <ul>
                                    <li><strong>Essential Cookies:</strong> Required for login and core functionality</li>
                                    <li><strong>Analytics Cookies:</strong> Help us understand usage patterns and improve our service</li>
                                    <li><strong>Preference Cookies:</strong> Remember your settings and preferences</li>
                                </ul>
                                <p>You can manage cookie preferences through your browser settings. Note that disabling essential cookies may affect platform functionality.</p>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                                Policy Updates
                            </div>
                            <div class="section-content">
                                <p>We may update this Privacy Policy from time to time. When we make material changes:</p>
                                <ul>
                                    <li>We will notify you via email</li>
                                    <li>Post updates on this page with the new effective date</li>
                                    <li>Provide a clear summary of changes made</li>
                                    <li>Request your consent if required by law</li>
                                </ul>
                                <p>We encourage you to review this policy periodically to stay informed about how we protect your information.</p>
                            </div>
                        </div>

                    @else
                        <!-- Arabic Content -->
                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                التزامنا
                            </div>
                            <div class="section-content">
                                <p>تلتزم منصة Sprint Skills ("نحن") بحماية خصوصيتك. توضح سياسة الخصوصية هذه كيفية جمع واستخدام والكشف عن وحماية معلوماتك عند استخدام منصتنا.</p>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-database"></i>
                                </div>
                                المعلومات التي نجمعها
                            </div>
                            <div class="section-content">
                                <p>نجمع الأنواع التالية من المعلومات لتقديم وتحسين خدماتنا:</p>
                                <ul>
                                    <li><strong>المعلومات الشخصية:</strong> الاسم وعنوان البريد الإلكتروني ورقم الهاتف (اختياري)</li>
                                    <li><strong>بيانات الاستخدام:</strong> سجلات نشاطك على منصتنا، بما في ذلك الدورات التي تم الوصول إليها والتقدم المحرز</li>
                                    <li><strong>البيانات التقنية:</strong> ملفات تعريف الارتباط وعنوان IP ونوع المتصفح ومعلومات الجهاز للتحليلات</li>
                                    <li><strong>بيانات التواصل:</strong> الرسائل التي ترسلها من خلال نماذج الاتصال أو قنوات الدعم</li>
                                </ul>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                كيف نستخدم معلوماتك
                            </div>
                            <div class="section-content">
                                <p>نستخدم معلوماتك للأغراض التالية:</p>
                                <ul>
                                    <li>إنشاء وإدارة حسابك</li>
                                    <li>تقديم الدورات والمحتوى التعليمي</li>
                                    <li>تخصيص تجربة التعلم الخاصة بك</li>
                                    <li>تحسين منصتنا وخدماتنا</li>
                                    <li>إرسال إشعارات وتحديثات متعلقة بالخدمة</li>
                                    <li>تقديم دعم العملاء والرد على الاستفسارات</li>
                                    <li>ضمان الأمان ومنع الاحتيال</li>
                                </ul>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-share-alt"></i>
                                </div>
                                مشاركة المعلومات
                            </div>
                            <div class="section-content">
                                <div class="highlight-box">
                                    <p><strong>لا نبيع بياناتك الشخصية أبداً.</strong> ثقتك أمر بالغ الأهمية بالنسبة لنا.</p>
                                </div>
                                <p>قد نشارك معلوماتك فقط في الظروف التالية:</p>
                                <ul>
                                    <li><strong>مقدمو الخدمة:</strong> شركاء موثوقون من طرف ثالث (معالجات الدفع، أدوات التحليلات) يجب عليهم الحفاظ على السرية</li>
                                    <li><strong>المتطلبات القانونية:</strong> عند الطلب بموجب القانون أو لحماية حقوقنا وسلامتنا</li>
                                    <li><strong>التحويلات التجارية:</strong> فيما يتعلق بعمليات الاندماج أو الاستحواذ (مع إشعار مسبق)</li>
                                    <li><strong>بموافقتك:</strong> أي مشاركة أخرى ستتطلب إذنك الصريح</li>
                                </ul>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                التدابير الأمنية
                            </div>
                            <div class="section-content">
                                <p>نطبق تدابير أمنية معيارية في الصناعة لحماية معلوماتك:</p>
                                <ul>
                                    <li>التشفير من طرف إلى طرف لنقل البيانات</li>
                                    <li>تخزين آمن للبيانات مع ضوابط الوصول</li>
                                    <li>مراجعات أمنية منتظمة وتحديثات</li>
                                    <li>تدريب الموظفين على حماية البيانات</li>
                                    <li>مراقبة محاولات الوصول غير المصرح بها</li>
                                </ul>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                حقوقك
                            </div>
                            <div class="section-content">
                                <p>لديك الحقوق التالية فيما يتعلق ببياناتك الشخصية:</p>
                                <ul>
                                    <li><strong>الوصول:</strong> عرض جميع البيانات الشخصية التي لدينا عنك</li>
                                    <li><strong>التحديث:</strong> تصحيح أو تعديل معلوماتك</li>
                                    <li><strong>التنزيل:</strong> تصدير بياناتك بتنسيق قابل للنقل</li>
                                    <li><strong>الحذف:</strong> طلب الإزالة الكاملة لبياناتك</li>
                                    <li><strong>التقييد:</strong> تحديد كيفية معالجة معلوماتك</li>
                                    <li><strong>الاعتراض:</strong> إلغاء الاشتراك في أنشطة معالجة بيانات معينة</li>
                                </ul>
                                <p>لممارسة أي من هذه الحقوق، يرجى الاتصال بنا على عنوان البريد الإلكتروني المقدم أدناه.</p>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-cookie-bite"></i>
                                </div>
                                ملفات تعريف الارتباط والتتبع
                            </div>
                            <div class="section-content">
                                <p>نستخدم ملفات تعريف الارتباط والتقنيات المماثلة لـ:</p>
                                <ul>
                                    <li><strong>ملفات تعريف الارتباط الأساسية:</strong> مطلوبة لتسجيل الدخول والوظائف الأساسية</li>
                                    <li><strong>ملفات تعريف ارتباط التحليلات:</strong> تساعدنا في فهم أنماط الاستخدام وتحسين خدمتنا</li>
                                    <li><strong>ملفات تعريف ارتباط التفضيلات:</strong> تذكر إعداداتك وتفضيلاتك</li>
                                </ul>
                                <p>يمكنك إدارة تفضيلات ملفات تعريف الارتباط من خلال إعدادات المتصفح. لاحظ أن تعطيل ملفات تعريف الارتباط الأساسية قد يؤثر على وظائف المنصة.</p>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="section-title">
                                <div class="section-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                                تحديثات السياسة
                            </div>
                            <div class="section-content">
                                <p>قد نقوم بتحديث سياسة الخصوصية هذه من وقت لآخر. عند إجراء تغييرات جوهرية:</p>
                                <ul>
                                    <li>سنخطرك عبر البريد الإلكتروني</li>
                                    <li>ننشر التحديثات على هذه الصفحة مع تاريخ السريان الجديد</li>
                                    <li>نقدم ملخصاً واضحاً للتغييرات المُجراة</li>
                                    <li>نطلب موافقتك إذا كان مطلوباً بموجب القانون</li>
                                </ul>
                                <p>نشجعك على مراجعة هذه السياسة بشكل دوري للبقاء على اطلاع حول كيفية حماية معلوماتك.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Contact Information -->
                    <div class="contact-info">
                        <h3>
                            <i class="fas fa-envelope me-2"></i>
                            {{ __('lang.Questions About Privacy?') }}
                        </h3>
                        <p>
                            @if(app()->getLocale() == 'en')
                                If you have any questions about this Privacy Policy or how we handle your data, please don't hesitate to contact us.
                            @else
                                إذا كان لديك أي أسئلة حول سياسة الخصوصية هذه أو كيفية التعامل مع بياناتك، يرجى عدم التردد في الاتصال بنا.
                            @endif
                        </p>
                        <a href="mailto:support@sprintskills.com" class="contact-email">
                            <i class="fas fa-paper-plane me-2"></i>
                            support@sprintskills.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Back to Top Button -->
    <button class="back-to-top" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    @guest
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Sprint Skills</h3>
                    <p>{{ __('lang.footer_text') }}</p>
                    <div class="footer-social">
                        <a href="https://www.facebook.com/pmarabchapter/" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://t.me/+z_AtT8ZlqehmZDhk" target="_blank" rel="noopener noreferrer"><i class="fab fa-telegram"></i></a>
                        <a href="https://www.linkedin.com/company/pm-arabcommunity/" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.instagram.com/pm_arab_chapter/" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>{{ __('lang.Resources') }}</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('faq') }}">{{ __('lang.FAQ') }}</a></li>
                        <li><a href="{{ route('privacy-policy') }}">{{ __('lang.Privacy Policy') }}</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>{{ __('lang.Company') }}</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('about') }}">{{ __('lang.About Us') }}</a></li>
                        <li><a href="{{ route('contact') }}">{{ __('lang.Contact Us') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Sprint Skills. {{ __('lang.All rights reserved.') }}</p>
            </div>
        </div>
    </footer>
    @endguest

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenu = document.querySelector('.mobile-menu');
            const navLinks = document.querySelector('.nav-links');
            
            if (mobileMenu) {
                mobileMenu.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }

            // Back to top button functionality
            const backToTopButton = document.querySelector('.back-to-top');
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('visible');
                } else {
                    backToTopButton.classList.remove('visible');
                }
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add reading progress indicator
            createReadingProgress();

            // Add section navigation
            createSectionNavigation();
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function createReadingProgress() {
            const progressBar = document.createElement('div');
            progressBar.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 0%;
                height: 4px;
                background: linear-gradient(135deg, #2F80ED, #1565C0);
                z-index: 9999;
                transition: width 0.3s ease;
            `;
            document.body.appendChild(progressBar);

            window.addEventListener('scroll', function() {
                const windowHeight = document.documentElement.scrollHeight - window.innerHeight;
                const scrolled = (window.pageYOffset / windowHeight) * 100;
                progressBar.style.width = scrolled + '%';
            });
        }

        function createSectionNavigation() {
            const sections = document.querySelectorAll('.privacy-section');
            if (sections.length === 0) return;

            const nav = document.createElement('div');
            nav.style.cssText = `
                position: fixed;
                left: 2rem;
                top: 50%;
                transform: translateY(-50%);
                z-index: 1000;
                background: white;
                border-radius: 15px;
                padding: 1rem;
                box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                max-height: 400px;
                overflow-y: auto;
            `;

            // Hide on mobile and RTL
            if (window.innerWidth <= 768 || document.documentElement.dir === 'rtl') {
                nav.style.display = 'none';
            }

            const navTitle = document.createElement('div');
            navTitle.textContent = 'Navigation';
            navTitle.style.cssText = `
                font-weight: 700;
                color: #2c3e50;
                margin-bottom: 1rem;
                font-size: 0.9rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            `;
            nav.appendChild(navTitle);

            sections.forEach((section, index) => {
                const title = section.querySelector('.section-title');
                if (!title) return;

                const navItem = document.createElement('a');
                navItem.href = '#section-' + index;
                navItem.textContent = title.textContent.trim();
                navItem.style.cssText = `
                    display: block;
                    padding: 0.5rem 0;
                    color: #666;
                    text-decoration: none;
                    font-size: 0.85rem;
                    transition: all 0.3s ease;
                    border-left: 3px solid transparent;
                    padding-left: 1rem;
                `;

                navItem.addEventListener('mouseenter', function() {
                    this.style.color = '#2F80ED';
                    this.style.borderLeftColor = '#2F80ED';
                });

                navItem.addEventListener('mouseleave', function() {
                    this.style.color = '#666';
                    this.style.borderLeftColor = 'transparent';
                });

                navItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    section.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });

                nav.appendChild(navItem);

                // Add ID to section for navigation
                section.id = 'section-' + index;
            });

            document.body.appendChild(nav);

            // Hide/show navigation based on scroll position
            let isNavVisible = true;
            window.addEventListener('scroll', function() {
                const shouldShow = window.pageYOffset > 500;
                if (shouldShow !== isNavVisible) {
                    nav.style.opacity = shouldShow ? '1' : '0';
                    nav.style.visibility = shouldShow ? 'visible' : 'hidden';
                    isNavVisible = shouldShow;
                }
            });

            // Initially hide
            nav.style.opacity = '0';
            nav.style.visibility = 'hidden';
            nav.style.transition = 'opacity 0.3s ease, visibility 0.3s ease';
        }

        // Enhanced accessibility
        document.addEventListener('keydown', function(e) {
            // Escape key to close mobile menu
            if (e.key === 'Escape') {
                const navLinks = document.querySelector('.nav-links');
                if (navLinks && navLinks.classList.contains('active')) {
                    navLinks.classList.remove('active');
                }
            }

            // Keyboard navigation for back to top
            if ((e.ctrlKey || e.metaKey) && e.key === 'Home') {
                e.preventDefault();
                scrollToTop();
            }
        });
    </script>
</body>
</html>
