<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('contact.Page Title') }}</title>
    <link rel="shortcut icon" href="{{asset('images/Sprint_Skills.ico')}}" type="image/x-icon"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/root-welcome.css') }}">
    <style>
        html[dir="rtl"] body { direction: rtl; text-align: right; }
        html[dir="rtl"] .faq-question h3,
        html[dir="rtl"] .contact-form label { text-align: right; }
        
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
                    <li><a href="{{ url('/') }}">{{ __('lang.Home') }}</a></li>
                    <li><a href="{{ url('/about') }}" class="active">{{ __('lang.About') }}</a></li>
                    <li><a href="{{ url('/contact') }}">{{ __('lang.Contact') }}</a></li>
                    <li><a href="#features">{{ __('lang.Features') }}</a></li>
                    <li><a href="#testimonials">{{ __('lang.Testimonials') }}</a></li>
                </ul>
                <div class="header-actions">
                    <div class="language-switcher">
                        <a href="{{ route('locale.set', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                        <a href="{{ route('locale.set', 'ar') }}" class="{{ app()->getLocale() == 'ar' ? 'active' : '' }}">AR</a>
                    </div>
                    <form action="{{route('login')}}" method="GET">
                        @csrf
                        <button class="cta-button" type="submit">{{ __('contact.Get Started') }}</button>
                    </form>
                </div>
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
                    <h1>{{ __('contact.Contact') }} Sprint Skills</h1>
                    <p>{{ __('contact.We are here to help') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Main Section -->
    <section class="contact-main">
        <div class="container">
            <div class="contact-container">
                <div class="contact-form">
                    <h2>{{ __('contact.Send Us a Message') }}</h2>
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('contact.Your Name') }}</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">{{ __('contact.Email Address') }}</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">{{ __('contact.Subject') }}</label>
                            <select id="subject" name="subject">
                                <option value="General Inquiry">{{ __('contact.General Inquiry') }}</option>
                                <option value="Technical Support">{{ __('contact.Technical Support') }}</option>
                                <option value="Billing Question">{{ __('contact.Billing Question') }}</option>
                                <option value="Feedback">{{ __('contact.Feedback') }}</option>
                                <option value="Other">{{ __('contact.Other') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">{{ __('contact.Your Message') }}</label>
                            <textarea id="message" name="message" rows="6" required></textarea>
                        </div>
                        <button type="submit" class="cta-button">{{ __('contact.Send Message') }}</button>
                    </form>
                </div>
                <div class="contact-info">
                    <h2>{{ __('contact.Contact Information') }}</h2>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="info-content">
                            <h3>{{ __('contact.Phone') }}</h3>
                            <p>+00905302354029<br>{{ __('contact.Work Hours') }}</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="info-content">
                            <h3>{{ __('contact.Phone 2') }}</h3>
                            <p>+0016193544916<br>{{ __('contact.Work Hours') }}</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <h3>{{ __('contact.Email') }}</h3>
                            <p>ihsan.arslan_84@hotmail.com<br>help@pmpmaster.com</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <h3>{{ __('contact.Support Hours') }}</h3>
                            <p>{{ __('contact.Tech 24/7') }}</p>
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
            <h2>{{ __('contact.FAQ Title') }}</h2>
            <p>{{ __('contact.FAQ Subtitle') }}</p>
        </div>
        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <h3>{{ __('contact.FAQ1 Q') }}</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>{{ __('contact.FAQ1 A') }}</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3>{{ __('contact.FAQ2 Q') }}</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>{{ __('contact.FAQ2 A') }}</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3>{{ __('contact.FAQ3 Q') }}</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>{{ __('contact.FAQ3 A') }}</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3>{{ __('contact.FAQ4 Q') }}</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>{{ __('contact.FAQ4 A') }}</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3>{{ __('contact.FAQ5 Q') }}</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>{{ __('contact.FAQ5 A') }}</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3>{{ __('contact.FAQ6 Q') }}</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>{{ __('contact.FAQ6 A') }}</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3>{{ __('contact.FAQ7 Q') }}</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>{{ __('contact.FAQ7 A') }}</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3>{{ __('contact.FAQ8 Q') }}</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>{{ __('contact.FAQ8 A') }}</p>
                    <ul>
                        <li>{{ __('contact.FAQ8 A1') }}</li>
                        <li>{{ __('contact.FAQ8 A2') }}</li>
                        <li>{{ __('contact.FAQ8 A3') }}</li>
                       
                    </ul>
                     <p>{{ __('contact.FAQ8 A4') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- CTA Section -->
    <section class="cta" id="cta">
        <div class="container">
            <h2>{{ __('contact.CTA Title') }}</h2>
            <p>{{ __('contact.CTA Text') }}</p>
            <form action="{{route('login')}}" method="GET">
                @csrf
                <button class="cta-button white" type="submit">{{ __('contact.Get Started Today') }}</button>
            </form>
        </div>
    </section>

    @guest
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Sprint Skills</h3>
                    <p>{{ __('about.Footer Text') }}</p>
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
                        <!-- <li><a href="#">{{ __('lang.PMP Exam Guide') }}</a></li>
                        <li><a href="#">{{ __('lang.Study Tips') }}</a></li>
                        <li><a href="#">{{ __('lang.PMBOK Summary') }}</a></li> -->
                        <li><a href="#">{{ __('lang.FAQ') }}</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>{{ __('lang.Company') }}</h3>
                    <ul class="footer-links">
                        <li><a href="{{route('about')}}">{{ __('lang.About Us') }}</a></li>
                        <!-- <li><a href="#">{{ __('lang.Our Instructors') }}</a></li> -->
                        <li><a href="{{route('contact')}}">{{ __('lang.Contact Us') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Sprint Skills. {{ __('about.All rights reserved.') }}</p>
            </div>
        </div>
    </footer>
    @endguest

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    
    $(document).ready(function() {
        $('.mobile-menu').click(function() {
            $('.nav-links').toggleClass('active');
        });

        // FAQ Accordion
        $('.faq-question').click(function() {
            const answer = $(this).siblings('.faq-answer');
            const parentItem = $(this).parent();

            if (parentItem.hasClass('active')) {
                answer.stop(true, true).slideUp(300);
                parentItem.removeClass('active');
                $(this).find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            } else {
                // يقفل باقي الإجابات ويفتح الحالي
                $('.faq-answer').slideUp(300);
                $('.faq-item').removeClass('active');
                $('.faq-question i').removeClass('fa-chevron-up').addClass('fa-chevron-down');

                answer.stop(true, true).slideDown(300);
                parentItem.addClass('active');
                $(this).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            }
        });
    });
</script>

</body>
</html>
