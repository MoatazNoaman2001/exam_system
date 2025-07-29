<div>
    <!-- Simplicity is an acquired taste. - Katharine Gerould -->
</div>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('about.About Sprint Skills - Where Purpose Meets Performance in Modern Management') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @if(app()->getLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="{{ asset('css/root-welcome.css') }}">
</head>
<body>
    @guest
            <!-- Header -->
    <header id="header">
        <div class="container">
            <nav>
                <div class="logo">
                    <img class="logo-img" src="{{asset('images/Sprint_Skills_logo.png')}}" alt="logo">
                    <span>Sprint Skills</span>
                </div>
                <ul class="nav-links">
                    <li><a href="{{ url('/') }}">{{ __('lang.home') }}</a></li>
                    <li><a href="{{ url('/about') }}" class="active">{{ __('lang.about') }}</a></li>
                    <li><a href="{{ url('/contact') }}">{{ __('lang.Contact Us') }}</a></li>
                    <li><a href="#features">{{ __('lang.Features') }}</a></li>
                    <li><a href="#testimonials">{{ __('lang.Testimonials') }}</a></li>
                </ul>
                <form action="{{route('login')}}" method="GET">
                    @csrf
                    <button class="cta-button" type="submit">{{ __('lang.Get Started') }}</button>
                </form>
                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>
    @endguest

    <!-- About Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>{{ __('about.About Sprint Skills') }}</h1>
                    <p class="text-white">{{ __('about.Where purpose meets performance in modern management') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Genesis Section -->
    <section class="our-story" id="genesis">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('about.Our Genesis') }}</h2>
                <p>{{ __('about.How Sprint Skills became the leading management education platform') }}</p>
            </div>
            <div class="story-timeline">
                <div class="timeline-item">
                    <div class="timeline-year">{{ __('about.Genesis') }}</div>
                    <div class="timeline-content">
                        <h3>{{ __('about.Conceived by Multidisciplinary Experts') }}</h3>
                        <p>{{ __('about.Sprint Skills was conceived by a multidisciplinary team of engineers-turned-managers, humanitarian program leaders, and tech educators who spent years guiding NGOs, startups, and corporate projects across the Middle East and beyond. They discovered that most learning platforms focus on isolated certificates, while real managers must juggle strategy, stakeholders, quality, finance, and social impact simultaneously.') }}</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">{{ __('about.Evolution') }}</div>
                    <div class="timeline-content">
                        <h3>{{ __('about.From Certification Prep to Holistic Mastery') }}</h3>
                        <p>{{ __('about.Early success with PMP-prep cohorts revealed a broader need for an all-in-one space where professionals could build project, program, portfolio, and business skills—supported by adaptive technology and bilingual resources.') }}</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">{{ __('about.Today') }}</div>
                    <div class="timeline-content">
                        <h3>{{ __('about.Where We Stand Today') }}</h3>
                        <p>{{ __('about.Sprint Skills hosts modular courses, AI-driven progress analytics, peer forums, and practical toolkits covering leadership, strategy execution, quality systems, risk, change, and digital transformation. New content is peer-reviewed by certified experts and field practitioners before release.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Challenges Section -->
    <section class="mission-section" id="challenges">
        <div class="container">
            <div class="mission-content">
                <div class="mission-text">
                    <h2>{{ __('about.Challenges That Shaped Us') }}</h2>
                    <div class="challenges-list">
                        <div class="challenge-item">
                            <i class="fas fa-cogs"></i>
                            <p>{{ __('about.Integrating agile, hybrid, and predictive methods into one coherent learning path') }}</p>
                        </div>
                        <div class="challenge-item">
                            <i class="fas fa-globe"></i>
                            <p>{{ __('about.Delivering a bilingual Arabic–English interface that runs smoothly on low-bandwidth networks common in our region') }}</p>
                        </div>
                        <div class="challenge-item">
                            <i class="fas fa-balance-scale"></i>
                            <p>{{ __('about.Sustaining an open-access model while securing funding for continuous content quality and platform innovation') }}</p>
                        </div>
                    </div>
                    <p class="challenge-conclusion">{{ __('about.Each obstacle refined our architecture, pedagogy, and community culture.') }}</p>
                </div>
                <div class="mission-image">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="{{ __('about.Team working together') }}">
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission Section -->
    <section class="vision-mission-section" id="vision-mission">
        <div class="container">
            <div class="vm-grid">
                <div class="vm-item">
                    <div class="vm-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>{{ __('about.Vision') }}</h3>
                    <p>{{ __('about.To democratize world-class management education for every Arabic-speaking professional and their global peers.') }}</p>
                </div>
                <div class="vm-item">
                    <div class="vm-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3>{{ __('about.Mission') }}</h3>
                    <p>{{ __('about.Equip leaders with actionable knowledge, adaptive tools, and a supportive community so they can deliver initiatives that improve lives and economies.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="values-section" id="values">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('about.Core Values') }}</h2>
                <p>{{ __('about.The principles that guide everything we do') }}</p>
            </div>
            <div class="values-grid">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>{{ __('about.Integrity') }}</h3>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>{{ __('about.Practical Excellence') }}</h3>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <img class="logo-img" src="{{asset('images/Sprint_Skills_logo.png')}}" alt="logo">
                    </div>
                    <h3>{{ __('about.Continuous Learning') }}</h3>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>{{ __('about.Collaboration') }}</h3>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>{{ __('about.Impact-Driven Innovation') }}</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section" id="team">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('about.Meet Our Team') }}</h2>
                <p>{{ __('about.The experts behind Sprint Skills success') }}</p>
            </div>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-photo">
                        <img src="https://avatars.githubusercontent.com/u/99621213?v=4" alt="Moataz Noaman">
                    </div>
                    <h3>{{ __('about.Moataz Noaman') }}</h3>
                    <p class="member-title">{{ __('about.Software Engineer') }}</p>
                    <p class="member-bio">{{ __('about.PMP, Engineer Responsible for Design and Development of the Platform.') }}</p>
                    <div class="member-social">
                        <a href="https://www.linkedin.com/in/moataz-noaman/"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-photo">
                        <img src="https://avatars.githubusercontent.com/u/93217206?v=4" alt="Hager Abd Alaziz">
                    </div>
                    <h3>{{ __('about.Hager Abd Alaziz') }}</h3>
                    <p class="member-title">{{ __('about.Software Engineer') }}</p>
                    <p class="member-bio">{{ __('about.PMP, Engineer Responsible for Design and Development of the Platform.') }}</p>
                    <div class="member-social">
                        <a href="https://www.linkedin.com/in/hager-hussien/"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">10,000+</div>
                    <div class="stat-label">{{ __('about.Professionals Trained') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">{{ __('about.Success Rate') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100+</div>
                    <div class="stat-label">{{ __('about.Modular Courses') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">2</div>
                    <div class="stat-label">{{ __('about.Languages Supported') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="cta">
        <div class="container">
            <h2>{{ __('about.Join Our Community') }}</h2>
            <p>{{ __('about.Whether you aim to earn a certification, scale a nonprofit venture, or steer a multinational portfolio, Sprint Skills is built to sprint with you every step of the way.') }}</p>
            <form action="{{route('login')}}" method="GET">
                @csrf
                <button class="cta-button white" type="submit">{{ __('about.Join Us Today') }}</button>
            </form>
        </div>
    </section>

    @guest
            <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>{{ __('Sprint Skills') }}</h3>
                    <p>{{ __('lang.footer_text') }}</p>
                    <div class="footer-social">
                        <a href="https://www.facebook.com/pmarabchapter/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://t.me/+z_AtT8ZlqehmZDhk" target="_blank"><i class="fab fa-telegram"></i></a>
                        <a href="https://www.linkedin.com/company/pm-arabcommunity/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.instagram.com/pm_arab_chapter/" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>{{ __('lang.Resources') }}</h3>
                    <ul class="footer-links">
                        <li><a href="#">{{ __('lang.PMP Exam Guide') }}</a></li>
                        <li><a href="#">{{ __('lang.Study Tips') }}</a></li>
                        <li><a href="#">{{ __('lang.PMBOK Summary') }}</a></li>
                        <li><a href="#">{{ __('lang.FAQ') }}</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>{{ __('lang.Company') }}</h3>
                    <ul class="footer-links">
                        <li><a href="{{route('about')}}">{{ __('lang.About Us') }}</a></li>
                        <li><a href="#">{{ __('lang.Our Instructors') }}</a></li>
                        <li><a href="{{route('contact')}}">{{ __('lang.Contact Us') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Sprint Skills. {{ __('about.All rights reserved.') }} | <a href="#">{{ __('about.Privacy Policy') }}</a> | <a href="#">{{ __('about.Terms of Service') }}</a></p>
            </div>
        </div>
    </footer>
    @endguest

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        // Mobile Menu Toggle
        $('.mobile-menu').click(function() {
            $('.nav-links').toggleClass('active');
        });
        
        // Smooth Scrolling
        $('a[href*="#"]').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate(
                {
                    scrollTop: $($(this).attr('href')).offset().top - 80,
                },
                500,
                'linear'
            );
        });
        
        // Header Scroll Effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('header').addClass('scrolled');
            } else {
                $('header').removeClass('scrolled');
            }
        });
        
        // Animate Stats on Scroll
        $(window).scroll(function() {
            var position = $('.stats-section').offset().top;
            var scroll = $(window).scrollTop();
            var windowHeight = $(window).height();
            
            if (scroll > position - windowHeight + 100) {
                $('.stat-number').each(function() {
                    var $this = $(this);
                    var target = $this.text();
                    $this.text('0');
                    $({countNum: 0}).animate({countNum: target.replace('+', '')}, {
                        duration: 2000,
                        easing: 'swing',
                        step: function() {
                            $this.text(Math.floor(this.countNum) + (target.includes('+') ? '+' : ''));
                        }
                    });
                });
            }
        });
    </script>

    <style>
        /* Additional styles for new sections */
        .challenges-list {
            margin: 2rem 0;
        }
        
        .challenge-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        
        .challenge-item i {
            font-size: 1.5rem;
            margin-right: 1rem;
            color: var(--primary-color);
            min-width: 2rem;
        }
        
        [dir="rtl"] .challenge-item i {
            margin-right: 0;
            margin-left: 1rem;
        }
        
        .challenge-conclusion {
            font-style: italic;
            text-align: center;
            margin-top: 2rem;
            font-size: 1.1rem;
        }
        
        .vision-mission-section {
            padding: 5rem 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .vm-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 3rem;
            margin-top: 3rem;
        }
        
        .vm-item {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }
        
        .vm-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #fff;
        }
        
        .values-section {
            padding: 5rem 0;
            background: #f8f9fa;
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .value-item {
            text-align: center;
            padding: 2rem 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .value-item:hover {
            transform: translateY(-5px);
        }
        
        .value-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        /* Arabic font support */
        [lang="ar"] {
            font-family: 'Cairo', 'Poppins', sans-serif;
        }
        
        /* RTL adjustments */
        [dir="rtl"] .timeline-item {
            text-align: right;
        }
        
        [dir="rtl"] .mission-content {
            flex-direction: row-reverse;
        }
        
        @media (max-width: 768px) {
            .vm-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .values-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1rem;
            }
            
            .challenge-item {
                flex-direction: column;
                text-align: center;
            }
            
            .challenge-item i {
                margin-right: 0;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</body>
</html>