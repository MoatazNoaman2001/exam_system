<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('contact.Page Title') }}</title>
    <link rel="shortcut icon" href="{{ asset('images/Sprint_Skills.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
</head>

<body>
    <!-- Navigation Overlay for Mobile -->
    <div class="nav-overlay" id="nav-overlay"></div>

    <!-- Mobile Navigation Drawer -->
    <div class="mobile-nav-drawer" id="mobile-nav-drawer">
        <div class="drawer-header">
            <div class="drawer-logo">
                <img src="{{ asset('images/Sprint_Skills_Logo_NoText.png') }}" alt="Sprint Skills">
                <span>Sprint Skills</span>
            </div>
        </div>
        <nav class="drawer-nav">
            <ul>
                <li><a href="{{ route('welcome') }}">{{ __('lang.Home') }}</a></li>
                <li><a href="{{ route('about') }}">{{ __('lang.About') }}</a></li>
                <li><a href="{{ route('contact') }}" class="active">{{ __('lang.Contact') }}</a></li>
                <li><a href="{{ route('faq') }}">{{ __('lang.FAQ') }}</a></li>
            </ul>
        </nav>
        <div class="drawer-footer">
            <form action="{{ route('login') }}" method="GET">
                @csrf
                <button class="drawer-cta" type="submit">{{ __('contact.Get Started') }}</button>
            </form>
            <div class="drawer-language">
                <div class="drawer-language-title">{{ __('lang.Language') }}</div>
                <div class="drawer-language-options">
                    <a href="{{ route('locale.set', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('locale.set', 'ar') }}" class="{{ app()->getLocale() == 'ar' ? 'active' : '' }}">AR</a>
                </div>
            </div>
        </div>
    </div>

    @guest
        <header class="header" id="header">
            <div class="container">
                <nav>
                    <div class="logo">
                        <img class="logo-img" src="{{ asset('images/Sprint_Skills_Logo_NoText.png') }}" alt="logo">
                        <span>{{ __('Sprint Skills') }}</span>
                    </div>
                    <ul class="nav-links">
                        <li><a href="{{ route('welcome') }}">{{ __('lang.Home') }}</a></li>
                        <li><a href="{{ route('about') }}">{{ __('lang.About') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="active">{{ __('lang.Contact') }}</a></li>
                        <li><a href="{{ route('faq') }}">{{ __('lang.FAQ') }}</a></li>
                    </ul>
                    <div class="header-actions">
                        <div class="language-switcher">
                            <a href="{{ route('locale.set', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                            <a href="{{ route('locale.set', 'ar') }}" class="{{ app()->getLocale() == 'ar' ? 'active' : '' }}">AR</a>
                        </div>
                        <form action="{{ route('login') }}" method="GET">
                            @csrf
                            <button class="cta-button" type="submit">{{ __('contact.Get Started') }}</button>
                        </form>
                    </div>
                    <div class="mobile-menu" id="mobile-menu">
                        <div class="hamburger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
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
                <!-- Contact Form -->
                <div class="contact-form">
                    <h2>{{ __('contact.Send Us a Message') }}</h2>

                    @if (session('success'))
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <strong>{{ __('validation.whoops') }}</strong>
                                <ul class="error-list">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" id="contact-form">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('contact.Your Name') }} <span style="color: red;">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                                   placeholder="{{ __('contact.Enter your full name') }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">{{ __('contact.Email Address') }} <span style="color: red;">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   placeholder="{{ __('contact.Enter your email address') }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">{{ __('contact.Subject') }} <span style="color: red;">*</span></label>
                            <select id="subject" name="subject" required>
                                <option value="">{{ __('contact.Select Subject') }}</option>
                                <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>
                                    {{ __('contact.General Inquiry') }}
                                </option>
                                <option value="Technical Support" {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>
                                    {{ __('contact.Technical Support') }}
                                </option>
                                <option value="Billing Question" {{ old('subject') == 'Billing Question' ? 'selected' : '' }}>
                                    {{ __('contact.Billing Question') }}
                                </option>
                                <option value="Feedback" {{ old('subject') == 'Feedback' ? 'selected' : '' }}>
                                    {{ __('contact.Feedback') }}
                                </option>
                                <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>
                                    {{ __('contact.Other') }}
                                </option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">{{ __('contact.Your Message') }} <span style="color: red;">*</span></label>
                            <textarea id="message" name="message" rows="6" required
                                      placeholder="{{ __('contact.Please describe your inquiry in detail...') }}">{{ old('message') }}</textarea>
                        </div>
                        
                        <button type="submit" class="cta-button" id="submit-btn">
                            <span class="btn-text">{{ __('contact.Send Message') }}</span>
                            <span class="btn-loading">
                                <i class="fas fa-spinner fa-spin"></i> {{ __('contact.Sending...') }}
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
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
    @if ($faqs->count() > 0)
        <section class="faq-section">
            <div class="container">
                <div class="section-title">
                    <h2>{{ __('contact.FAQ Title') }}</h2>
                    <p>{{ __('contact.FAQ Subtitle') }}</p>
                </div>
                <div class="faq-container">
                    @foreach ($faqs as $faq)
                        <div class="faq-item">
                            <button class="faq-question" type="button">
                                <h3>{{ $faq->localized_question }}</h3>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="faq-answer">
                                <div>{!! nl2br(e($faq->localized_answer)) !!}</div>
                            </div>
                        </div>
                    @endforeach

                    <div style="text-align: center; margin-top: 2rem;">
                        <a href="{{ route('faq') }}" class="cta-button">{{ __('contact.View All FAQs') }}</a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- CTA Section -->
    <section class="cta" id="cta">
        <div class="container">
            <h2>{{ __('contact.CTA Title') }}</h2>
            <p>{{ __('contact.CTA Text') }}</p>
            <form action="{{ route('login') }}" method="GET">
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
                        <p>{{ __('about.The most comprehensive management education platform with personalized study plans, AI-driven analytics, and bilingual support for Arabic-speaking professionals.') }}</p>
                        <div class="footer-social">
                            <a href="https://www.facebook.com/pmarabchapter/" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://t.me/+z_AtT8ZlqehmZDhk" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-telegram"></i>
                            </a>
                            <a href="https://www.linkedin.com/company/pm-arabcommunity/" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://www.instagram.com/pm_arab_chapter/" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-instagram"></i>
                            </a>
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
                    <p>&copy; 2025 Sprint Skills. {{ __('about.All rights reserved.') }}</p>
                </div>
            </div>
        </footer>
    @endguest

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu functionality
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileNavDrawer = document.getElementById('mobile-nav-drawer');
            const navOverlay = document.getElementById('nav-overlay');

            function toggleMobileMenu() {
                mobileMenu.classList.toggle('active');
                mobileNavDrawer.classList.toggle('active');
                navOverlay.classList.toggle('active');
                document.body.style.overflow = mobileNavDrawer.classList.contains('active') ? 'hidden' : '';
            }

            function closeMobileMenu() {
                mobileMenu.classList.remove('active');
                mobileNavDrawer.classList.remove('active');
                navOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (mobileMenu) {
                mobileMenu.addEventListener('click', toggleMobileMenu);
            }

            if (navOverlay) {
                navOverlay.addEventListener('click', closeMobileMenu);
            }

            // Close mobile menu when clicking on nav links
            const drawerNavLinks = document.querySelectorAll('.drawer-nav a');
            drawerNavLinks.forEach(link => {
                link.addEventListener('click', closeMobileMenu);
            });

            // FAQ Accordion functionality
            const faqQuestions = document.querySelectorAll('.faq-question');

            faqQuestions.forEach(function(question) {
                question.addEventListener('click', function() {
                    const answer = this.nextElementSibling;
                    const parentItem = this.parentElement;

                    if (parentItem.classList.contains('active')) {
                        // Close current FAQ
                        answer.style.display = 'none';
                        parentItem.classList.remove('active');
                    } else {
                        // Close all other FAQs
                        document.querySelectorAll('.faq-answer').forEach(function(ans) {
                            ans.style.display = 'none';
                        });
                        document.querySelectorAll('.faq-item').forEach(function(item) {
                            item.classList.remove('active');
                        });

                        // Open current FAQ
                        answer.style.display = 'block';
                        parentItem.classList.add('active');
                    }
                });
            });

            // Contact form handling
            const contactForm = document.getElementById('contact-form');
            const submitBtn = document.getElementById('submit-btn');

            if (contactForm && submitBtn) {
                contactForm.addEventListener('submit', function(e) {
                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.querySelector('.btn-text').style.display = 'none';
                    submitBtn.querySelector('.btn-loading').style.display = 'inline-block';
                });
            }

            // Form validation
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const subjectSelect = document.getElementById('subject');
            const messageInput = document.getElementById('message');

            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            function showFieldError(field, message) {
                // Remove existing error
                const existingError = field.parentNode.querySelector('.field-error');
                if (existingError) {
                    existingError.remove();
                }

                // Add new error
                const errorDiv = document.createElement('div');
                errorDiv.className = 'field-error';
                errorDiv.textContent = message;
                field.parentNode.appendChild(errorDiv);
                field.style.borderColor = '#dc3545';
            }

            function clearFieldError(field) {
                const existingError = field.parentNode.querySelector('.field-error');
                if (existingError) {
                    existingError.remove();
                }
                field.style.borderColor = '#e0e0e0';
            }

            // Real-time validation
            if (nameInput) {
                nameInput.addEventListener('blur', function() {
                    if (this.value.trim().length < 2) {
                        showFieldError(this, '{{ __('validation.min.string', ['attribute' => 'name', 'min' => 2]) }}');
                    } else {
                        clearFieldError(this);
                    }
                });

                nameInput.addEventListener('input', function() {
                    if (this.value.trim().length >= 2) {
                        clearFieldError(this);
                    }
                });
            }

            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    if (!validateEmail(this.value)) {
                        showFieldError(this, '{{ __('validation.email') }}');
                    } else {
                        clearFieldError(this);
                    }
                });

                emailInput.addEventListener('input', function() {
                    if (validateEmail(this.value)) {
                        clearFieldError(this);
                    }
                });
            }

            if (subjectSelect) {
                subjectSelect.addEventListener('change', function() {
                    if (this.value === '') {
                        showFieldError(this, '{{ __('validation.required', ['attribute' => 'subject']) }}');
                    } else {
                        clearFieldError(this);
                    }
                });
            }

            if (messageInput) {
                messageInput.addEventListener('blur', function() {
                    const messageLength = this.value.trim().length;
                    if (messageLength < 10) {
                        showFieldError(this, '{{ __('validation.min.string', ['attribute' => 'message', 'min' => 10]) }}');
                    } else if (messageLength > 2000) {
                        showFieldError(this, '{{ __('validation.max.string', ['attribute' => 'message', 'max' => 2000]) }}');
                    } else {
                        clearFieldError(this);
                    }
                });

                messageInput.addEventListener('input', function() {
                    const messageLength = this.value.trim().length;
                    if (messageLength >= 10 && messageLength <= 2000) {
                        clearFieldError(this);
                    }

                    // Show character count
                    let charCount = this.parentNode.querySelector('.char-count');
                    if (!charCount) {
                        charCount = document.createElement('div');
                        charCount.className = 'char-count';
                        this.parentNode.appendChild(charCount);
                    }
                    charCount.textContent = messageLength + '/2000';

                    if (messageLength > 2000) {
                        charCount.style.color = '#dc3545';
                    } else {
                        charCount.style.color = '#666';
                    }
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

            // Auto-hide success/error messages after 5 seconds
            const messages = document.querySelectorAll('.success-message, .error-message');
            messages.forEach(function(message) {
                setTimeout(function() {
                    message.style.transition = 'opacity 0.5s ease';
                    message.style.opacity = '0';
                    setTimeout(function() {
                        if (message.parentNode) {
                            message.parentNode.removeChild(message);
                        }
                    }, 500);
                }, 5000);
            });

            // Phone number formatting
            const phoneInputs = document.querySelectorAll('input[type="tel"]');
            phoneInputs.forEach(function(input) {
                input.addEventListener('input', function() {
                    let value = this.value.replace(/[^\d+]/g, '');
                    if (value.indexOf('+') > 0) {
                        value = value.replace(/\+/g, '');
                    }
                    this.value = value;
                });
            });

            // Enhanced accessibility for mobile
            const formInputs = document.querySelectorAll('input, select, textarea');
            formInputs.forEach(function(input) {
                input.addEventListener('focus', function() {
                    this.style.boxShadow = '0 0 0 3px rgba(47, 128, 237, 0.1)';
                });

                input.addEventListener('blur', function() {
                    if (!this.matches(':focus')) {
                        this.style.boxShadow = '';
                    }
                });
            });

            // Handle browser back button
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        const btnText = submitBtn.querySelector('.btn-text');
                        const btnLoading = submitBtn.querySelector('.btn-loading');
                        if (btnText) btnText.style.display = 'inline-block';
                        if (btnLoading) btnLoading.style.display = 'none';
                    }
                }
            });

            // Close mobile menu on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024) {
                    closeMobileMenu();
                }
            });

            // Mobile touch interactions
            if ('ontouchstart' in window) {
                const touchElements = document.querySelectorAll('.info-item, .cta-button');
                touchElements.forEach(element => {
                    element.addEventListener('touchstart', function() {
                        this.style.transform = 'scale(0.98)';
                    });
                    
                    element.addEventListener('touchend', function() {
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);
                    });
                });
            }

            // Intersection Observer for mobile animations
            if ('IntersectionObserver' in window) {
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, observerOptions);

                // Observe elements for animation
                const animateElements = document.querySelectorAll('.contact-form, .contact-info, .info-item, .faq-item');
                animateElements.forEach(element => {
                    if (window.innerWidth <= 768) {
                        element.style.opacity = '0';
                        element.style.transform = 'translateY(30px)';
                        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                        observer.observe(element);
                    }
                });
            }
        });
    </script>
</body>
</html>