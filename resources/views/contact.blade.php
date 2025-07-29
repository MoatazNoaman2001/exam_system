<div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
</div>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact PMP Master - Your Complete PMP Exam Preparation System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/root-welcome.css') }}">
</head>
<body>

    @guest
        
    <header id="header">
        <div class="container">
            <nav>
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Sprint Skills</span>
                </div>
                <ul class="nav-links">
                    <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ url('/about') }}" class="active">{{ __('About') }}</a></li>
                    <li><a href="{{ url('/contact') }}">{{ __('Contact') }}</a></li>
                    <li><a href="#features">{{ __('Features') }}</a></li>
                    <li><a href="#testimonials">{{ __('Testimonials') }}</a></li>
                </ul>
                <form action="{{route('login')}}" method="GET">
                    @csrf
                    <button class="cta-button" type="submit">{{ __('Get Started') }}</button>
                </form>
                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>
    @endguest
    <!-- Contact Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Contact PMP Master</h1>
                    <p>We're here to help you with any questions about our platform or your PMP exam preparation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Main Section -->
    <section class="contact-main">
        <div class="container">
            <div class="contact-container">
                <div class="contact-form">
                    <h2>Send Us a Message</h2>
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <select id="subject" name="subject">
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Technical Support">Technical Support</option>
                                <option value="Billing Question">Billing Question</option>
                                <option value="Feedback">Feedback</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" rows="6" required></textarea>
                        </div>
                        <button type="submit" class="cta-button">Send Message</button>
                    </form>
                </div>
                <div class="contact-info">
                    <h2>Contact Information</h2>
                
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="info-content">
                            <h3>Phone</h3>
                            <p>+00905302354029<br>Monday-Friday, 9am-5pm PST</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="info-content">
                            <h3>Phone2</h3>
                            <p>+0016193544916<br>Monday-Friday, 9am-5pm PST</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <h3>Email</h3>
                            <p>ihsan.arslan_84@hotmail.com<br>help@pmpmaster.com</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <h3>Support Hours</h3>
                            <p>24/7 for technical issues<br>Live chat available 6am-8pm PST</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-title">
                <h2>Frequently Asked Questions</h2>
                <p>Quick answers to common questions about PMP Master</p>
            </div>
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How does PMP Master compare to other PMP prep courses?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>PMP Master offers a more comprehensive and adaptive learning experience than most other platforms. Our unique features include personalized study plans that adjust based on your progress, realistic exam simulations with detailed answer explanations, and progress tracking that helps you focus on areas needing improvement.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>What's included in the subscription?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Your subscription gives you full access to all PMP Master resources: 50+ hours of video lectures, 2,500+ practice questions with explanations, 5 full-length mock exams, knowledge area quizzes, study planners, progress tracking tools, and mobile access. We also regularly update our content to reflect the latest PMP exam changes.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Can I access PMP Master on mobile devices?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes! PMP Master is fully responsive and works on all devices. We also have dedicated iOS and Android apps that allow you to study offline and sync your progress when you're back online. Many users find our mobile app perfect for quick study sessions during commutes or breaks.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How long does it typically take to prepare using PMP Master?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Most users spend 6-8 weeks preparing with PMP Master when studying 10-15 hours per week. However, our adaptive system creates a personalized timeline based on your schedule and initial assessment. We've had users pass in as little as 3 weeks and others take 3 months - it all depends on your existing knowledge and available study time.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Do you offer any money-back guarantee?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we offer a 30-day money-back guarantee if you're not satisfied with our platform. Additionally, if you don't pass the PMP exam after completing at least 80% of our recommended study plan, we'll extend your subscription for free until you pass.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="cta">
        <div class="container">
            <h2>Ready to Start Your PMP Journey?</h2>
            <p>Join thousands of successful PMP candidates and take the first step toward your certification today.</p>
            <form action="{{route('login')}}" method="GET">
                @csrf
                <button class="cta-button white" type="submit">Get Started Today</button>
            </form>
        </div>
    </section>

    @guest
            <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Sprint Skills</h3>
                    <p>{{ __('about.The most comprehensive management education platform with personalized study plans, AI-driven analytics, and bilingual support for Arabic-speaking professionals.') }}</p>
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
        
        // FAQ Accordion
        $('.faq-question').click(function() {
            $(this).parent().toggleClass('active');
            $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
            $(this).siblings('.faq-answer').slideToggle();
        });
    </script>
</body>
</html>