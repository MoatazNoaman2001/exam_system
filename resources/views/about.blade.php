{{-- resources/views/about.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('lang.About Us') }} - Sprint Skills</title>
    <link rel="shortcut icon" href="{{asset('images/Sprint_Skills.ico')}}" type="image/x-icon"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/root-welcome.css') }}">
    <style>
        html[dir="rtl"] body { 
            direction: rtl; 
            text-align: right; 
            font-family: 'Cairo', 'Tajawal', sans-serif;
        }
        
        .about-hero {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 50%, #2980b9 100%);
            color: white;
            padding: 140px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .about-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.8) 0%, rgba(52, 152, 219, 0.6) 50%, rgba(41, 128, 185, 0.8) 100%);
            z-index: 1;
        }
        
        .about-hero .container {
            position: relative;
            z-index: 2;
        }
        
        .about-hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .about-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .about-content {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .about-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .about-section {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            margin-bottom: 3rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            border-left: 5px solid #2F80ED;
            position: relative;
            overflow: hidden;
        }
        
        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(47, 128, 237, 0.1), rgba(21, 101, 192, 0.05));
            border-radius: 50%;
            transform: translate(30px, -30px);
        }
        
        html[dir="rtl"] .about-section {
            border-left: none;
            border-right: 5px solid #2F80ED;
        }
        
        html[dir="rtl"] .about-section::before {
            right: auto;
            left: 0;
            transform: translate(-30px, -30px);
        }
        
        .section-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            z-index: 2;
        }
        
        .section-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #2F80ED, #1565C0);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(47, 128, 237, 0.3);
        }
        
        .section-content {
            color: #555;
            line-height: 1.8;
            font-size: 1.1rem;
            position: relative;
            z-index: 2;
        }
        
        .section-content p {
            margin-bottom: 1.5rem;
        }
        
        .section-content ul {
            margin: 1.5rem 0;
            padding-left: 0;
            list-style: none;
        }
        
        .section-content li {
            margin-bottom: 1rem;
            position: relative;
            padding-left: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        
        html[dir="rtl"] .section-content li {
            padding-left: 0;
            padding-right: 2rem;
        }
        
        .section-content li::before {
            content: '';
            width: 8px;
            height: 8px;
            background: linear-gradient(135deg, #2F80ED, #1565C0);
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 0.5rem;
            position: absolute;
            left: 0;
        }
        
        html[dir="rtl"] .section-content li::before {
            left: auto;
            right: 0;
        }
        
        .highlight-text {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            padding: 1.5rem;
            border-radius: 15px;
            border-left: 4px solid #2196f3;
            margin: 2rem 0;
            font-weight: 600;
            color: #1976d2;
        }
        
        html[dir="rtl"] .highlight-text {
            border-left: none;
            border-right: 4px solid #2196f3;
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .value-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .value-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #2F80ED, #1565C0);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .value-item:hover {
            border-color: #2F80ED;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(47, 128, 237, 0.2);
        }
        
        .value-item:hover::before {
            opacity: 0.05;
        }
        
        .value-title {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
            position: relative;
            z-index: 2;
        }
        
        .features-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .feature-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 1.5rem;
            border-radius: 15px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .feature-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-color: #2F80ED;
        }
        
        .feature-title {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .feature-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 50%, #2980b9 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
            margin-top: 3rem;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.8) 0%, rgba(52, 152, 219, 0.6) 50%, rgba(41, 128, 185, 0.8) 100%);
            z-index: 1;
        }
        
        .cta-section .container {
            position: relative;
            z-index: 2;
        }
        
        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        @media (max-width: 768px) {
            .about-hero {
                padding: 120px 0 60px;
            }
            
            .about-hero h1 {
                font-size: 2.5rem;
            }
            
            .about-content {
                padding: 60px 0;
            }
            
            .about-section {
                padding: 2rem;
                margin: 0 1rem 2rem;
            }
            
            .section-title {
                font-size: 1.8rem;
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .section-content {
                font-size: 1rem;
            }
            
            .values-grid {
                grid-template-columns: 1fr;
            }
            
            .features-list {
                grid-template-columns: 1fr;
            }
            
            .cta-section h2 {
                font-size: 2rem;
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
                    <li><a href="{{ route('about') }}" class="active">{{ __('lang.About') }}</a></li>
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


    @auth
        @if (auth()->user()->role === 'student')
            <button class="back_to_setting" onclick="history.back()">
            </button>
        @endif
    @endauth
    <!-- About Hero Section -->
    <section class="about-hero">
        <div class="container">
            <h1>{{ __('lang.About Us') }}</h1>
            <p>
                @if(app()->getLocale() == 'en')
                    Learn more about Sprint Skills and our mission to transform project management education
                @else
                    تعرّف أكثر على Sprint Skills ورسالتنا في تطوير تعليم إدارة المشاريع
                @endif
            </p>
        </div>
    </section>

    <!-- About Content -->
    <section class="about-content">
        <div class="container">
            <div class="about-container">
                
                @if(app()->getLocale() == 'en')
                    <!-- English Content -->
                    <div class="about-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            Who We Are
                        </div>
                        <div class="section-content">
                            <p>Sprint Skills is a digital learning platform built by certified trainers with over a decade of experience delivering project-, program-, and portfolio-management workshops across the MENA region and beyond. We've distilled that classroom expertise into bite-sized e-lessons, ready-to-use tools, and an active peer community that goes straight to what managers need—no fluff.</p>
                        </div>
                    </div>

                    <div class="about-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-gift"></i>
                            </div>
                            What We Offer
                        </div>
                        <div class="section-content">
                            <div class="features-list">
                                <div class="feature-item">
                                    <div class="feature-title">Focused courses</div>
                                    <div class="feature-description">7–12-minute lessons, each ending with a real-world task.</div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-title">Arabic–English parity</div>
                                    <div class="feature-description">Identical rigor and depth in both languages.</div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-title">Downloadable templates</div>
                                    <div class="feature-description">Excel, PowerPoint, and Miro files for instant application.</div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-title">Peer support hub</div>
                                    <div class="feature-description">Fast Q&A forum plus peer review on project plans.</div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-title">Clear progress tracker</div>
                                    <div class="feature-description">Shows completed units and suggests next steps.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="about-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            Our Vision
                        </div>
                        <div class="section-content">
                            <div class="highlight-text">
                                To make modern management skills practical and accessible for professionals in the Arab world and beyond.
                            </div>
                        </div>
                    </div>

                    <div class="about-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            Our Mission
                        </div>
                        <div class="section-content">
                            <p>Deliver continuously updated, PMI-aligned content—rooted in regional case studies—and pair it with tools that shorten the gap between learning and doing.</p>
                           
                        </div>
                    </div>

                    <div class="about-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            Core Values
                        </div>
                        <div class="section-content">
                            <div class="values-grid">
                                <div class="value-item">
                                    <div class="value-title">Clarity</div>
                                </div>
                                <div class="value-item">
                                    <div class="value-title">Practicality</div>
                                </div>
                                <div class="value-item">
                                    <div class="value-title">Lifelong Learning</div>
                                </div>
                                <div class="value-item">
                                    <div class="value-title">Collaboration</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="about-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            What Sets Us Apart
                        </div>
                        <div class="section-content">
                            <ul>
                                <li>Every lesson ends with a "do-today" action item.</li>
                                <li>Content is refreshed after each PMI standards update or shift in best practice.</li>
                                <li>Flexible pricing: monthly subscription or one-off track purchase—no long-term lock-ins.</li>
                            </ul>
                        </div>
                    </div>

                @else
                    <!-- Arabic Content -->
                    <div class="about-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            من نحن؟
                        </div>
                        <div class="section-content">
                            <p>Sprint Skills منصّة تعليمية رقمية أسّسها مدرّبون معتمدون في إدارة المشاريع، البرامج، والمحافظ ويتمتّعون بخبرة تتجاوز عشر سنوات في قاعات التدريب المباشر والافتراضي على امتداد الشرق الأوسط وخارجه. حولنا هذه الخبرة إلى محتوى إلكتروني قصير، أدوات عمل جاهزة، ومجتمع مساعدة يركّز على ما يحتاجه المدير اليوم، بلا حشو أو تعقيد.</p>
                        </div>
                    </div>

                    <div class="about-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-gift"></i>
                            </div>
                            ماذا نقدّم؟
                        </div>
                        <div class="section-content">
                            <div class="features-list">
                                <div class="feature-item">
                                    <div class="feature-title">دورات مركّزة</div>
                                    <div class="feature-description">مقاطع من 7–12 دقيقة، ينتهي كل مقطع بمثال عملي وتمرين قصير.</div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-title">ثنائية اللغة</div>
                                    <div class="feature-description">كل درس متاح بالعربية والإنجليزية بنفس العمق والجودة.</div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-title">قوالب جاهزة للتنزيل</div>
                                    <div class="feature-description">ملفات Excel و PowerPoint و Miro تطبّق بها ما تعلّمته فوراً.</div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-title">مجتمع تفاعلي</div>
                                    <div class="feature-description">منتدى أسئلة وأجوبة سريع مع مراجعات أقران لخطط المشاريع.</div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-title">لوحة تقدّم مبسّطة</div>
                                    <div class="feature-description">تعرض إنجازك وتقترح الخطوة التالية.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="about-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            رؤيتنا
                        </div>
                        <div class="section-content">
                            <div class="highlight-text">
                                نشر المهارات الإدارية الحديثة بأسلوب عملي يسهُل تطبيقه في بيئات العمل العربية والعالمية.
                            </div>
                        </div>
                    </div>

                    <div class="about-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            رسالتنا
                        </div>
                        <div class="section-content">
                            <p>توفير محتوى موثوق يُحدَّث دوريًّا وفق معايير PMI وشهاداته المعروفة مثل PMP®، مع ربط المحتوى بأمثلة من القطاعات الربحية وغير الربحية في منطقتنا.</p>
                           
                        </div>
                    </div>

                    <div class="about-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            قيمنا
                        </div>
                        <div class="section-content">
                            <div class="values-grid">
                                <div class="value-item">
                                    <div class="value-title">الوضوح</div>
                                </div>
                                <div class="value-item">
                                    <div class="value-title">التطبيق العملي</div>
                                </div>
                                <div class="value-item">
                                    <div class="value-title">التعلّم مدى الحياة</div>
                                </div>
                                <div class="value-item">
                                    <div class="value-title">التعاون</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="about-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            ما الذي يميّزنا؟
                        </div>
                        <div class="section-content">
                            <ul>
                                <li>كل درس يُختَم بإجراء يمكنك تنفيذه اليوم.</li>
                                <li>نحدِّث المحتوى بعد كل إصدار جديد من معايير PMI أو تغيّر في أفضل الممارسات.</li>
                                <li>خطط اشتراك مرنة: شهرية أو شراء مسار واحد، بلا التزام طويل الأمد.</li>
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- CTA Section -->
                <div class="cta-section">
                    <div class="container">
                        <h2>
                            @if(app()->getLocale() == 'en')
                                Join Us
                            @else
                                انضمّ إلينا
                            @endif
                        </h2>
                        <p>
                            @if(app()->getLocale() == 'en')
                                Start with a free track and see how Sprint Skills streamlines your path to stronger project outcomes.
                            @else
                                جرّب أول مسار مجاناً وابداً رحلتك نحو إدارة مشاريع أكثر كفاءة.
                            @endif
                        </p>
                        <form action="{{route('login')}}" method="GET">
                            @csrf
                            <button class="cta-button white" type="submit">{{ __('lang.Get Started Today') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

            // Add scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all sections for animation
            document.querySelectorAll('.about-section').forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(30px)';
                section.style.transition = `all 0.6s ease ${index * 0.1}s`;
                observer.observe(section);
            });

            // Add hover effects to feature items
            document.querySelectorAll('.feature-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });

                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(-3px) scale(1)';
                });
            });

            // Add click effects to value items
            document.querySelectorAll('.value-item').forEach(item => {
                item.addEventListener('click', function() {
                    // Add ripple effect
                    const ripple = document.createElement('span');
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(47, 128, 237, 0.3);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        pointer-events: none;
                    `;
                    
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = (rect.width / 2 - size / 2) + 'px';
                    ripple.style.top = (rect.height / 2 - size / 2) + 'px';
                    
                    this.style.position = 'relative';
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                });
            });

            // Parallax effect for hero section
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const hero = document.querySelector('.about-hero');
                if (hero) {
                    hero.style.transform = `translateY(${scrolled * 0.3}px)`;
                }
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to { transform: scale(4); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>