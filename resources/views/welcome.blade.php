<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sprint Skills') }}</title>

    <link rel="shortcut icon" href="{{asset('images/Sprint_Skills.ico')}}" type="image/x-icon"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/welcome.css')}}">
    {{-- <script src="{{asset('js/welcome.js')}}" defer></script> --}}
    <style>
        /* Fix image aspect ratio in progress tracking */
        .hero-text h1 {
            color: #f0f0f0 !important;
        }
        .hero-text p {
            color: #f0f0f0 !important;
        }
        .section-title h1 {
            color: #f0f0f0 !important;
        }

        .section-title p {
            color: #f0f0f0 !important;
        }
        .progress-image {
            position: relative;
            width: 100%;
            max-width: 500px;
        }
        .progress-img {
            width: 100%;
            height: auto;
            aspect-ratio: 4 / 3;
            object-fit: cover;
        }
        .progress-img-bg {
            position: absolute;
            top: 20px;
            left: -20px;
            opacity: 0.5;
            z-index: -1;
        }
        /* Fix header logo alignment */
        .logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        /* RTL adjustments for Arabic */
        html[lang="ar"] .nav-links,
        html[lang="ar"] .hero-content,
        html[lang="ar"] .features-grid,
        html[lang="ar"] .plan-container,
        html[lang="ar"] .exams-grid,
        html[lang="ar"] .progress-container,
        html[lang="ar"] .testimonials-slider,
        html[lang="ar"] .footer-content {
            direction: rtl;
        }

        /* Language switcher styles */
        .language-switcher {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .language-switcher a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .language-switcher a:hover {
            background-color: #f0f0f0;
        }
        .language-switcher a.active {
            background-color: #007bff;
            color: white;
        }
        .logo-img {
            width: 60px !important;
            height: 60px !important;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header id="header">
        <div class="container">
            <nav>
                <div class="logo">
                    <img class="logo-img" src="{{asset('images/Sprint_Skills_Logo_NoText.png')}}" alt="logo">
                    <span style="color: rgb(26, 89, 123); font-size: 22px">{{ __('Sprint Skills') }}</span>
                </div>
                <ul class="nav-links">
                    <li><a href="#features">{{ __('lang.Features') }}</a></li>
                    <li><a href="#study-plan">{{ __('lang.Study Plan') }}</a></li>
                    <li><a href="#practice-exams">{{ __('lang.Practice Exams') }}</a></li>
                    <li><a href="#progress-tracking">{{ __('lang.Progress') }}</a></li>
                    <li><a href="#testimonials">{{ __('lang.Testimonials') }}</a></li>
                </ul>
                <div class="header-actions">
                    <div class="language-switcher">
                        <a href="{{ route('locale.set', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                        <a href="{{ route('locale.set', 'ar') }}" class="{{ app()->getLocale() == 'ar' ? 'active' : '' }}">AR</a>
                    </div>
                    <form action="{{route('login')}}" method="GET" style="display: inline-block;">
                        @csrf
                        <button class="cta-button" type="submit">{{ __('lang.Get Started') }}</button>
                    </form>
                    <div class="mobile-menu">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="text-primary">{{ __('lang.Master the Art and Science of Management') }}</h1>
                    <p class="text-primary">{{ __('lang.Join a dynamic learning experience designed to help you grow in all areas of modern management from agile leadership to portfolio excellence.') }}</p>
                    <form action="{{route('login')}}" method="GET" style="display: inline-block;">
                        @csrf
                        <button class="cta-button" type="submit">{{ __('lang.Let’s Build Your Future in Management') }}</button>
                    </form>
                </div>
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173" alt="{{ __('lang.PMP Exam Dashboard') }}" class="hero-img hero-img-1">
                    <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b" alt="{{ __('lang.PMP Study Materials') }}" class="hero-img hero-img-2">
                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40" alt="{{ __('lang.PMP Progress Tracking') }}" class="hero-img hero-img-3">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('lang.Everything You Need to Succeed') }}</h2>
                <p>{{ __('lang.Our platform provides all the tools and resources to prepare effectively for the PMP certification exam.') }}</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>{{ __('lang.🧠 Foundational Knowledge') }}</h3>
                    <p>{{ __('lang.Explore essential concepts and proven frameworks across various management disciplines—from strategic planning to agile leadership—to build a solid foundation for growth.') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h3>{{ __('lang.✅ Hands-On Learning') }}</h3>
                    <p>{{ __('lang.Test your knowledge with full-length, timed practice exams that mimic the actual PMP exam format and difficulty level.') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>{{ __('lang.📊 Personalized Learning Journey') }}</h3>
                    <p>{{ __('lang.Track your growth with a smart system that adapts to your learning style, highlights areas for improvement, and helps you stay on track to achieve your goals.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Study Plan Section -->
    <section class="study-plan" id="study-plan">
        <div class="container">
            <div class="section-title">
                <h2  style="color: white !important;">{{ __('lang.Personalized Study Plan') }}</h2>
                <p>{{ __('lang.Tell us your exam date and we\'ll create a customized study schedule that fits your timeline.') }}</p>
            </div>
            <div class="plan-container">
                <div class="plan-image">
                    <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d" alt="{{ __('lang.Study Plan') }}" class="plan-img plan-img-main">
                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4" alt="{{ __('lang.Study Plan Background') }}" class="plan-img plan-img-bg">
                </div>
                <div class="plan-content">
                    <h2>{{ __('lang.Study Smarter, Not Harder') }}</h2>
                    <p>{{ __('lang.Our adaptive learning system creates a personalized study plan based on your available study time, learning pace, and progress. Focus on what matters most for your exam success.') }}</p>
                    <div class="plan-steps">
                        <div class="plan-step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4>{{ __('lang.Set Your Exam Date') }}</h4>
                                <p>{{ __('lang.Tell us when you plan to take the exam and how many hours per week you can study.') }}</?p>
                            </div>
                        </div>
                        <div class="plan-step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4>{{ __('lang.Take Initial Assessment') }}</h4>
                                <p>{{ __('lang.Complete our diagnostic test to identify your strengths and weaknesses.') }}</p>
                            </div>
                        </div>
                        <div class="plan-step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h4>{{ __('lang.Follow Your Plan') }}</h4>
                                <p>{{ __('lang.Receive daily study tasks tailored to your needs and timeline.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Practice Exams Section -->
    <section class="practice-exams" id="practice-exams">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('lang.Realistic Practice Exams') }}</h2>
                {{-- <p>{{ __('lang.Simulate the actual exam experience with our comprehensive question bank.') }}</p> --}}
            </div>
            <div class="exams-grid">
                <div class="exam-card">
                    <div class="exam-image">
                        <img src="https://images.unsplash.com/photo-1551269901-5c5e14c25df7?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="{{ __('lang.Full-Length Exam') }}">
                        {{-- <div class="exam-tag">200 {{ __('lang.Questions') }}</div> --}}
                    </div>
                    <div class="exam-content">
                        <h3>{{ __('lang.Full-Length Mock Exam') }}</h3>
                        <p>{{ __('lang.Complete 4-hour simulation with questions covering all knowledge areas and process groups.') }}</p>
                        <div class="exam-meta">
                            {{-- <div class="exam-questions">
                                <i class="fas fa-question-circle"></i>
                                <span>200 {{ __('lang.Questions') }}</span>
                            </div> --}}
                            <form action="{{route('login')}}" method="GET" style="display: inline-block;">
                                @csrf
                                <button class="cta-button" type="submit">{{ __('lang.Start Exam') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="exam-card">
                    <div class="exam-image">
                        <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="{{ __('lang.Knowledge Area Exam') }}">
                        {{-- <div class="exam-tag">50 {{ __('lang.Questions') }}</div> --}}
                    </div>
                    <div class="exam-content">
                        <h3>{{ __('lang.Knowledge Area Tests') }}</h3>
                        <p>{{ __('lang.Focus on specific PMBOK knowledge areas to strengthen your weak points.') }}</p>
                        <div class="exam-meta">
                            {{-- <div class="exam-questions">
                                <i class="fas fa-question-circle"></i>
                                <span>50 {{ __('lang.Questions Each') }}</span>
                            </div> --}}
                            <form action="{{route('login')}}" method="GET" style="display: inline-block;">
                                @csrf
                                <button class="cta-button" type="submit">{{ __('lang.Start Exam') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="exam-card">
                    <div class="exam-image">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="{{ __('lang.Quick Quiz') }}">
                        {{-- <div class="exam-tag">20 {{ __('lang.Questions') }}</div> --}}
                    </div>
                    <div class="exam-content">
                        <h3>{{ __('lang.Daily Quick Quizzes') }}</h3>
                        <p>{{ __('lang.Short 20-question quizzes to reinforce concepts and keep your knowledge fresh.') }}</p>
                        <div class="exam-meta">
                            {{-- <div class="exam-questions">
                                <i class="fas fa-question-circle"></i>
                                <span>20 {{ __('lang.Questions') }}</span>
                            </div> --}}
                            <form action="{{route('login')}}" method="GET" style="display: inline-block;">
                                @csrf
                                <button class="cta-button" type="submit">{{ __('lang.Start Quiz') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Insightful Progress Tracking Section -->
    <!-- <section class="progress-tracking" id="progress-tracking">
        <div class="container">
            <div class="progress-container">
                <div class="progress-content">
                    <h2>{{ __('lang.dynamic_visual_reports_title') }}</h2>
                    <p>{{ __('lang.dynamic_visual_reports_description') }}</p>
                </div>
                <div class="progress-content">
                    <h2>{{ __('lang.progress_tracking_title') }}</h2>
                    <p>{{ __('lang.progress_tracking_description') }}</p>
                </div>
                {{-- <div class="progress-image">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="{{ __('Progress Dashboard') }}" class="progress-img progress-img-main">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="{{ __('Progress Background') }}" class="progress-img progress-img-bg">
                </div> --}}
            </div>
        </div>
    </section> -->

    <!-- Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('lang.Success Stories') }}</h2>
            </div>
            <div class="testimonials-slider">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        {{ __('lang.PMP Master\'s practice exams were incredibly similar to the actual PMP exam. The detailed explanations for each question helped me understand not just what the right answer was, but why it was correct. I passed on my first attempt!') }}
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

   <!-- CTA Section -->
<section class="cta" id="cta">
    <div class="container">
        <h2>{{ __('lang.Start Your Learning Journey Today') }}</h2>
        <p>{{ __('lang.Practical knowledge. Real impact. All in one platform.') }}</p>
        <form action="{{route('login')}}" method="GET" style="display: inline-block;">
                        @csrf
                        <button class="cta-button" type="submit">{{ __('lang.Get Started') }}</button>
                    </form>

    </div>
</section>

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
                
                        <ul class="footer-links">
                            <li><a href="{{ route('faq') }}">{{ __('lang.FAQ') }}</a></li>
                        </ul>                    </ul>
                </div>
                <div class="footer-column">
                    <h3>{{ __('lang.Company') }}</h3>
                    <ul class="footer-links">
                        <li><a href="{{route('about')}}">{{ __('lang.About Us') }}</a></li>
                        <li><a href="{{route('contact')}}">{{ __('lang.Contact Us') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 {{ __('Sprint Skills') }}. {{ __('lang.All rights reserved.') }} | <a href="{{route('privacy-policy')}}">{{ __('lang.Privacy Policy') }}</a> | <a href="{{route('privacy-policy')}}">{{ __('lang.Terms of Service') }}</a></p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile Menu Toggle with Animation
        document.querySelector('.mobile-menu').addEventListener('click', function() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
            
            // Animate menu items
            if (navLinks.classList.contains('active')) {
                const menuItems = navLinks.querySelectorAll('li');
                menuItems.forEach((item, index) => {
                    item.style.setProperty('--i', index);
                });
            }
        });
        
        // Smooth Scrolling with Offset
        document.querySelectorAll('a[href*="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offsetTop = target.offsetTop - 80;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    document.querySelector('.nav-links').classList.remove('active');
                }
            });
        });
        
        // Enhanced Header Scroll Effect
        let lastScrollY = window.scrollY;
        window.addEventListener('scroll', function() {
            const currentScrollY = window.scrollY;
            const header = document.querySelector('header');
            
            if (currentScrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            // Hide/show header on scroll
            if (currentScrollY > lastScrollY && currentScrollY > 200) {
                header.style.transform = 'translateY(-100%)';
            } else {
                header.style.transform = 'translateY(0)';
            }
            
            lastScrollY = currentScrollY;
        });
        
        // Intersection Observer for Animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Add animate class to trigger animations
                    entry.target.classList.add('animate');
                    
                    // Handle different animation types
                    if (entry.target.classList.contains('section-title')) {
                        entry.target.classList.add('animate');
                    }
                    
                    if (entry.target.classList.contains('feature-card')) {
                        const cards = entry.target.parentElement.querySelectorAll('.feature-card');
                        cards.forEach((card, index) => {
                            card.style.setProperty('--delay', index);
                            setTimeout(() => card.classList.add('animate'), index * 200);
                        });
                    }
                    
                    if (entry.target.classList.contains('exam-card')) {
                        const cards = entry.target.parentElement.querySelectorAll('.exam-card');
                        cards.forEach((card, index) => {
                            card.style.setProperty('--delay', index);
                            setTimeout(() => card.classList.add('animate'), index * 200);
                        });
                    }
                    
                    if (entry.target.classList.contains('plan-step')) {
                        const steps = entry.target.parentElement.querySelectorAll('.plan-step');
                        steps.forEach((step, index) => {
                            step.style.setProperty('--delay', index);
                            setTimeout(() => step.classList.add('animate'), index * 300);
                        });
                    }
                    
                    if (entry.target.classList.contains('stat-item')) {
                        const stats = entry.target.parentElement.querySelectorAll('.stat-item');
                        stats.forEach((stat, index) => {
                            stat.style.setProperty('--delay', index);
                            setTimeout(() => stat.classList.add('animate'), index * 200);
                        });
                    }
                }
            });
        }, observerOptions);
        
        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', function() {
            // Observe section titles
            document.querySelectorAll('.section-title').forEach(el => observer.observe(el));
            
            // Observe feature cards
            document.querySelectorAll('.feature-card').forEach(el => observer.observe(el));
            
            // Observe exam cards
            document.querySelectorAll('.exam-card').forEach(el => observer.observe(el));
            
            // Observe plan steps
            document.querySelectorAll('.plan-step').forEach(el => observer.observe(el));
            
            // Observe stat items
            document.querySelectorAll('.stat-item').forEach(el => observer.observe(el));
        });
        
        // Animate Progress Bars
        const progressObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const progressFill = entry.target.querySelector('.progress-fill');
                    if (progressFill) {
                        const width = progressFill.style.width;
                        progressFill.style.width = '0';
                        setTimeout(() => {
                            progressFill.style.width = width;
                        }, 300);
                    }
                }
            });
        }, { threshold: 0.5 });
        
        document.querySelectorAll('.stat-item').forEach(bar => {
            progressObserver.observe(bar);
        });
        
        // Parallax Effect for Hero
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero');
            const heroImages = document.querySelectorAll('.hero-img');
            
            if (hero) {
                // Parallax background
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
                
                // Parallax images
                heroImages.forEach((img, index) => {
                    const speed = 0.2 + (index * 0.1);
                    img.style.transform = `translateY(${scrolled * speed}px)`;
                });
            }
        });
        
        // Add loading animation to page elements
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
            
            // Animate hero content on load
            const heroText = document.querySelector('.hero-text');
            const heroImage = document.querySelector('.hero-image');
            
            if (heroText) {
                setTimeout(() => heroText.classList.add('fade-in-up'), 300);
            }
            if (heroImage) {
                setTimeout(() => heroImage.classList.add('fade-in-up'), 600);
            }
        });
        
        // Performance optimized scroll handlers
        let ticking = false;
        
        function updateScrollEffects() {
            // Your scroll effects here
            ticking = false;
        }
        
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateScrollEffects);
                ticking = true;
            }
        });
    </script>
</body>
</html>