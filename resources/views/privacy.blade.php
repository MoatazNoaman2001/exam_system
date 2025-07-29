<div>
    <!-- Order your soul. Reduce your wants. - Augustine -->
</div>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('lang.Privacy Policy') }} - {{ __('lang.Sprint Skills') }}</title>
    <meta name="description" content="{{ __('lang.Learn about how Sprint Skills protects your privacy and handles your personal information.') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/privacy.css')}}">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="navbar-brand">
                    <a href="{{ route('welcome') }}" class="logo">
                        <i class="fas fa-graduation-cap"></i>
                        <span>{{ __('lang.Sprint Skills') }}</span>
                    </a>
                </div>
                <div class="nav-actions">
                    <div class="language-switcher">
                        <a href="{{ route('locale.set', 'en') }}" class="lang-btn {{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                        <a href="{{ route('locale.set', 'ar') }}" class="lang-btn {{ app()->getLocale() == 'ar' ? 'active' : '' }}">AR</a>
                    </div>
                    <a href="{{ route('welcome') }}" class="btn btn-outline">
                        <i class="fas fa-home"></i>
                        {{ __('lang.Back to Home') }}
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <div class="page-header-content">
                    <h1 class="page-title">{{ __('lang.Privacy Policy') }}</h1>
                    <p class="page-subtitle">{{ __('lang.Learn how we protect your privacy and handle your personal information with care and transparency.') }}</p>
                </div>
            </div>
        </section>

        <!-- Content Section -->
        <section class="content-section">
            <div class="container">
                <div class="privacy-content">
                    <!-- Content Navigation -->
                    <div class="content-nav">
                        <h3>{{ __('lang.Quick Navigation') }}</h3>
                        <ul class="nav-list">
                            <li><a href="#information-we-collect"><i class="fas fa-database"></i> {{ __('lang.Information We Collect') }}</a></li>
                            <li><a href="#how-we-use"><i class="fas fa-cogs"></i> {{ __('lang.How We Use Information') }}</a></li>
                            <li><a href="#data-sharing"><i class="fas fa-share-alt"></i> {{ __('lang.Data Sharing') }}</a></li>
                            <li><a href="#security"><i class="fas fa-shield-alt"></i> {{ __('lang.Security') }}</a></li>
                            <li><a href="#your-rights"><i class="fas fa-user-shield"></i> {{ __('lang.Your Rights') }}</a></li>
                            <li><a href="#cookies"><i class="fas fa-cookie-bite"></i> {{ __('lang.Cookies') }}</a></li>
                        </ul>
                    </div>

                    <!-- Introduction -->
                    <div class="section-block">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            {{ __('lang.Introduction') }}
                        </div>
                        <p class="section-text">
                            {{ __('lang.Sprint Skills ("we," "us," or "our") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform and services.') }}
                        </p>
                        <div class="highlight-box">
                            <div class="icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <strong>{{ __('lang.Our Commitment:') }}</strong> {{ __('lang.We believe in transparency and giving you control over your personal information. This policy is written in plain language to help you understand your rights and our practices.') }}
                        </div>
                    </div>

                    <!-- Information We Collect -->
                    <div class="section-block" id="information-we-collect">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-database"></i>
                            </div>
                            {{ __('lang.Information We Collect') }}
                        </div>
                        <p class="section-text">{{ __('lang.We collect the following types of information to provide you with the best learning experience:') }}</p>
                        <ul class="list-styled">
                            <li>
                                <span class="bullet"><i class="fas fa-user"></i></span>
                                <div>
                                    <strong>{{ __('lang.Personal Information:') }}</strong> {{ __('lang.Your name and email address (required for account creation).') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-phone"></i></span>
                                <div>
                                    <strong>{{ __('lang.Contact Information:') }}</strong> {{ __('lang.Phone number (optional, for account recovery and important notifications).') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-chart-line"></i></span>
                                <div>
                                    <strong>{{ __('lang.Usage Data:') }}</strong> {{ __('lang.Course progress, quiz scores, time spent on platform, and learning preferences.') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-desktop"></i></span>
                                <div>
                                    <strong>{{ __('lang.Technical Data:') }}</strong> {{ __('lang.Device information, browser type, IP address, and usage logs for platform improvement.') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-cookie-bite"></i></span>
                                <div>
                                    <strong>{{ __('lang.Cookies & Analytics:') }}</strong> {{ __('lang.Essential cookies for login functionality and analytics to improve our services.') }}
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- How We Use Information -->
                    <div class="section-block" id="how-we-use">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            {{ __('lang.How We Use Your Information') }}
                        </div>
                        <p class="section-text">{{ __('lang.We use your information for the following purposes:') }}</p>
                        <ul class="list-styled">
                            <li>
                                <span class="bullet"><i class="fas fa-user-plus"></i></span>
                                <div>{{ __('lang.Create and manage your account') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-graduation-cap"></i></span>
                                <div>{{ __('lang.Deliver courses, track progress, and provide certifications') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-paint-brush"></i></span>
                                <div>{{ __('lang.Personalize content and learning recommendations') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-tools"></i></span>
                                <div>{{ __('lang.Improve our platform, courses, and user experience') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-envelope"></i></span>
                                <div>{{ __('lang.Send service-related notices, updates, and important announcements') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-headset"></i></span>
                                <div>{{ __('lang.Provide customer support and respond to your inquiries') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-shield-alt"></i></span>
                                <div>{{ __('lang.Ensure platform security and prevent fraud') }}</div>
                            </li>
                        </ul>
                    </div>

                    <!-- Data Sharing -->
                    <div class="section-block" id="data-sharing">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-share-alt"></i>
                            </div>
                            {{ __('lang.Data Sharing and Disclosure') }}
                        </div>
                        <div class="highlight-box">
                            <div class="icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <strong>{{ __('lang.We Never Sell Your Data:') }}</strong> {{ __('lang.Your personal information is not for sale. We respect your privacy and will never sell your data to third parties.') }}
                        </div>
                        <p class="section-text">{{ __('lang.We only share your information in the following limited circumstances:') }}</p>
                        <ul class="list-styled">
                            <li>
                                <span class="bullet"><i class="fas fa-handshake"></i></span>
                                <div>
                                    <strong>{{ __('lang.Trusted Service Providers:') }}</strong> {{ __('lang.Payment processors, email services, analytics tools, and hosting providers who must keep your information confidential.') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-balance-scale"></i></span>
                                <div>
                                    <strong>{{ __('lang.Legal Requirements:') }}</strong> {{ __('lang.When required by law, court order, or to protect our rights and safety.') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-building"></i></span>
                                <div>
                                    <strong>{{ __('lang.Business Transfers:') }}</strong> {{ __('lang.In case of merger, acquisition, or sale of assets (with continued privacy protection).') }}
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Security -->
                    <div class="section-block" id="security">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            {{ __('lang.Security Measures') }}
                        </div>
                        <p class="section-text">{{ __('lang.We implement industry-standard security measures to protect your information:') }}</p>
                        <ul class="list-styled">
                            <li>
                                <span class="bullet"><i class="fas fa-lock"></i></span>
                                <div>{{ __('lang.Industry-standard encryption for data transmission and storage') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-key"></i></span>
                                <div>{{ __('lang.Secure access controls and authentication systems') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-eye"></i></span>
                                <div>{{ __('lang.Regular security audits and monitoring') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-users-cog"></i></span>
                                <div>{{ __('lang.Limited employee access on a need-to-know basis') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-server"></i></span>
                                <div>{{ __('lang.Secure servers and infrastructure') }}</div>
                            </li>
                        </ul>
                        <div class="highlight-box">
                            <div class="icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <strong>{{ __('lang.Important:') }}</strong> {{ __('lang.While we use industry-best practices, no system is 100% secure. Please keep your login credentials safe and report any suspicious activity immediately.') }}
                        </div>
                    </div>

                    <!-- Your Rights -->
                    <div class="section-block" id="your-rights">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            {{ __('lang.Your Privacy Rights') }}
                        </div>
                        <p class="section-text">{{ __('lang.You have the following rights regarding your personal information:') }}</p>
                        <ul class="list-styled">
                            <li>
                                <span class="bullet"><i class="fas fa-eye"></i></span>
                                <div>
                                    <strong>{{ __('lang.Access:') }}</strong> {{ __('lang.View all personal data we have about you') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-edit"></i></span>
                                <div>
                                    <strong>{{ __('lang.Update:') }}</strong> {{ __('lang.Correct or update your personal information') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-download"></i></span>
                                <div>
                                    <strong>{{ __('lang.Download:') }}</strong> {{ __('lang.Get a copy of your data in a portable format') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-trash-alt"></i></span>
                                <div>
                                    <strong>{{ __('lang.Delete:') }}</strong> {{ __('lang.Request deletion of your personal data (subject to legal requirements)') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-ban"></i></span>
                                <div>
                                    <strong>{{ __('lang.Restrict:') }}</strong> {{ __('lang.Limit how we process your information') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-paper-plane"></i></span>
                                <div>
                                    <strong>{{ __('lang.Portability:') }}</strong> {{ __('lang.Transfer your data to another service') }}
                                </div>
                            </li>
                        </ul>
                        <div class="highlight-box">
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <strong>{{ __('lang.Response Time:') }}</strong> {{ __('lang.We will respond to your privacy requests within 30 days. For urgent matters, please contact us immediately.') }}
                        </div>
                    </div>

                    <!-- Cookies -->
                    <div class="section-block" id="cookies">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-cookie-bite"></i>
                            </div>
                            {{ __('lang.Cookies and Tracking') }}
                        </div>
                        <p class="section-text">{{ __('lang.We use cookies and similar technologies to enhance your experience:') }}</p>
                        <ul class="list-styled">
                            <li>
                                <span class="bullet"><i class="fas fa-key"></i></span>
                                <div>
                                    <strong>{{ __('lang.Essential Cookies:') }}</strong> {{ __('lang.Required for login functionality and platform security (cannot be disabled)') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-chart-bar"></i></span>
                                <div>
                                    <strong>{{ __('lang.Analytics Cookies:') }}</strong> {{ __('lang.Help us understand how you use our platform to improve services') }}
                                </div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-user-cog"></i></span>
                                <div>
                                    <strong>{{ __('lang.Preference Cookies:') }}</strong> {{ __('lang.Remember your settings and personalize your experience') }}
                                </div>
                            </li>
                        </ul>
                        <div class="highlight-box">
                            <div class="icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <strong>{{ __('lang.Cookie Control:') }}</strong> {{ __('lang.You can manage non-essential cookies through your browser settings. Note that disabling certain cookies may affect platform functionality.') }}
                        </div>
                    </div>

                    <!-- Data Retention -->
                    <div class="section-block">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            {{ __('lang.Data Retention') }}
                        </div>
                        <p class="section-text">{{ __('lang.We retain your information for the following periods:') }}</p>
                        <ul class="list-styled">
                            <li>
                                <span class="bullet"><i class="fas fa-user"></i></span>
                                <div>{{ __('lang.Account information: As long as your account is active') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-graduation-cap"></i></span>
                                <div>{{ __('lang.Course progress and certificates: 7 years after account closure') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-chart-line"></i></span>
                                <div>{{ __('lang.Usage analytics: 2 years in anonymized form') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-headset"></i></span>
                                <div>{{ __('lang.Support communications: 3 years for quality purposes') }}</div>
                            </li>
                        </ul>
                    </div>

                    <!-- International Transfers -->
                    <div class="section-block">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            {{ __('lang.International Data Transfers') }}
                        </div>
                        <p class="section-text">{{ __('lang.Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place:') }}</p>
                        <ul class="list-styled">
                            <li>
                                <span class="bullet"><i class="fas fa-shield-alt"></i></span>
                                <div>{{ __('lang.Adequate protection measures for international transfers') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-file-contract"></i></span>
                                <div>{{ __('lang.Standard contractual clauses with service providers') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-check-circle"></i></span>
                                <div>{{ __('lang.Compliance with applicable data protection laws') }}</div>
                            </li>
                        </ul>
                    </div>

                    <!-- Children's Privacy -->
                    <div class="section-block">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-child"></i>
                            </div>
                            {{ __('lang.Children\'s Privacy') }}
                        </div>
                        <p class="section-text">{{ __('lang.Our platform is designed for users 16 years and older. We do not knowingly collect personal information from children under 16. If you believe we have collected information from a child under 16, please contact us immediately.') }}</p>
                    </div>

                    <!-- Changes to Policy -->
                    <div class="section-block">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            {{ __('lang.Changes to This Policy') }}
                        </div>
                        <p class="section-text">{{ __('lang.We may update this Privacy Policy from time to time. When we make material changes:') }}</p>
                        <ul class="list-styled">
                            <li>
                                <span class="bullet"><i class="fas fa-envelope"></i></span>
                                <div>{{ __('lang.We will notify you via email') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-bullhorn"></i></span>
                                <div>{{ __('lang.We will post a notice on our platform') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-calendar"></i></span>
                                <div>{{ __('lang.We will update the "Last Updated" date') }}</div>
                            </li>
                            <li>
                                <span class="bullet"><i class="fas fa-clock"></i></span>
                                <div>{{ __('lang.Changes become effective 30 days after notification') }}</div>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Section -->
                    <div class="contact-section">
                        <h3>{{ __('lang.Contact Us About Privacy') }}</h3>
                        <p>{{ __('lang.If you have any questions about this Privacy Policy or want to exercise your privacy rights, we\'re here to help.') }}</p>
                        <div class="contact-info">
                            <a href="mailto:support@sprintskills.com" class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>support@sprintskills.com</span>
                            </a>
                            <a href="mailto:privacy@sprintskills.com" class="contact-item">
                                <i class="fas fa-user-shield"></i>
                                <span>privacy@sprintskills.com</span>
                            </a>
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ __('lang.Response within 24-48 hours') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Last Updated -->
                    <div class="last-updated">
                        <p>
                            <i class="fas fa-calendar-alt"></i>
                            {{ __('lang.Last updated: January 2025') }}
                        </p>
                        <p>{{ __('lang.This policy is effective immediately and applies to all users of the Sprint Skills platform.') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('.nav-list a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const headerHeight = 100;
                    const targetPosition = target.offsetTop - headerHeight;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add scroll spy effect
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('.section-block[id]');
            const navLinks = document.querySelectorAll('.nav-list a[href^="#"]');
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (scrollY >= sectionTop - 150) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });

        // Add fade-in animation on scroll
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

        // Observe all section blocks
        document.querySelectorAll('.section-block').forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(el);
        });

        // Add active class to current nav link
        const navLinks = document.querySelectorAll('.nav-list a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>

    <style>
        .nav-list a.active {
            background: rgba(102, 126, 234, 0.15);
            color: var(--primary-color);
            font-weight: 600;
        }
    </style>
</body>
</html>