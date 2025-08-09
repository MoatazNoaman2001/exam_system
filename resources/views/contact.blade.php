</html>{{-- resources/views/contact/index.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('contact.Page Title') }}</title>
    <link rel="shortcut icon" href="{{ asset('images/Sprint_Skills.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/root-welcome.css') }}">
    <style>
        html[dir="rtl"] body {
            direction: rtl;
            text-align: right;
        }

        html[dir="rtl"] .faq-question h3,
        html[dir="rtl"] .contact-form label {
            text-align: right;
        }

        .contact-hero {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 50%, #2980b9 100%);
            color: white;
            padding: 140px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .contact-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.8) 0%, rgba(52, 152, 219, 0.6) 50%, rgba(41, 128, 185, 0.8) 100%);
            z-index: 1;
        }

        .contact-hero .container {
            position: relative;
            z-index: 2;
        }

        .contact-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .contact-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .contact-main {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .contact-form {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .contact-form h2 {
            color: #2c3e50;
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
            font-size: 1rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2F80ED;
            box-shadow: 0 0 0 3px rgba(47, 128, 237, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .contact-info {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .contact-info h2 {
            color: #2c3e50;
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 600;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: #e3f2fd;
            transform: translateY(-2px);
        }

        .info-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #2F80ED, #1565C0);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            flex-shrink: 0;
            font-size: 1.2rem;
        }

        html[dir="rtl"] .info-icon {
            margin-right: 0;
            margin-left: 1.5rem;
        }

        .info-content h3 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .info-content p {
            color: #666;
            margin: 0;
            line-height: 1.6;
        }

        .faq-section {
            padding: 80px 0;
            background: white;
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            background: #f8f9fa;
            border-radius: 15px;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .faq-question {
            padding: 1.5rem 2rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            transition: all 0.3s ease;
        }

        html[dir="rtl"] .faq-question {
            text-align: right;
        }

        .faq-question:hover {
            background: #e9ecef;
        }

        .faq-question h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            flex: 1;
            padding-right: 1rem;
        }

        html[dir="rtl"] .faq-question h3 {
            padding-right: 0;
            padding-left: 1rem;
        }

        .faq-question i {
            color: #2F80ED;
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        .faq-answer {
            display: none;
            padding: 0 2rem 1.5rem;
            background: white;
        }

        .faq-answer p {
            color: #555;
            line-height: 1.6;
            margin: 0;
        }

        .faq-answer ul {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        html[dir="rtl"] .faq-answer ul {
            padding-left: 0;
            padding-right: 1.5rem;
        }

        .faq-answer li {
            margin-bottom: 0.5rem;
            color: #555;
        }

        .cta {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 50%, #2980b9 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.8) 0%, rgba(52, 152, 219, 0.6) 50%, rgba(41, 128, 185, 0.8) 100%);
            z-index: 1;
        }

        .cta .container {
            position: relative;
            z-index: 2;
        }

        .cta h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .cta p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .success-message,
        .error-message {
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .error-list {
            margin: 0;
            padding-left: 1.5rem;
        }

        html[dir="rtl"] .error-list {
            padding-left: 0;
            padding-right: 1.5rem;
        }

        @media (max-width: 1024px) {
            .contact-container {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
        }

        @media (max-width: 768px) {
            .contact-hero {
                padding: 120px 0 60px;
            }

            .contact-hero h1 {
                font-size: 2.2rem;
            }

            .contact-main {
                padding: 60px 0;
            }

            .contact-form,
            .contact-info {
                padding: 2rem;
            }

            .faq-section {
                padding: 60px 0;
            }

            .faq-question {
                padding: 1.2rem 1.5rem;
            }

            .faq-answer {
                padding: 0 1.5rem 1.2rem;
            }

            .cta {
                padding: 60px 0;
            }

            .cta h2 {
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
                        <img class="logo-img" src="{{ asset('images/Sprint_Skills_Logo_NoText.png') }}" alt="logo">
                        <span style="color: rgb(26, 89, 123); font-size: 22px">{{ __('Sprint Skills') }}</span>
                    </div>
                    <ul class="nav-links">
                        <li><a href="{{ route('welcome') }}">{{ __('lang.Home') }}</a></li>
                        <li><a href="{{ route('about') }}">{{ __('lang.About') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="active">{{ __('lang.Contact') }}</a></li>
                        <li><a href="{{ route('faq') }}">{{ __('lang.FAQ') }}</a></li>
                    </ul>
                    <div class="header-actions">
                        <div class="language-switcher">
                            <a href="{{ route('locale.set', 'en') }}"
                                class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                            <a href="{{ route('locale.set', 'ar') }}"
                                class="{{ app()->getLocale() == 'ar' ? 'active' : '' }}">AR</a>
                        </div>
                        <form action="{{ route('login') }}" method="GET">
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

                    @if (session('success'))
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="error-message">
                            <strong>{{ __('validation.whoops') }}</strong>
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('contact.Your Name') }} <span
                                    style="color: red;">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">{{ __('contact.Email Address') }} <span
                                    style="color: red;">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">{{ __('contact.Subject') }} <span
                                    style="color: red;">*</span></label>
                            <select id="subject" name="subject" required>
                                <option value="">{{ __('contact.Select Subject') }}</option>
                                <option value="General Inquiry"
                                    {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>
                                    {{ __('contact.General Inquiry') }}</option>
                                <option value="Technical Support"
                                    {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>
                                    {{ __('contact.Technical Support') }}</option>
                                <option value="Billing Question"
                                    {{ old('subject') == 'Billing Question' ? 'selected' : '' }}>
                                    {{ __('contact.Billing Question') }}</option>
                                <option value="Feedback" {{ old('subject') == 'Feedback' ? 'selected' : '' }}>
                                    {{ __('contact.Feedback') }}</option>
                                <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>
                                    {{ __('contact.Other') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">{{ __('contact.Your Message') }} <span
                                    style="color: red;">*</span></label>
                            <textarea id="message" name="message" rows="6" required
                                placeholder="{{ __('contact.Please describe your inquiry in detail...') }}">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="cta-button">
                            {{ __('contact.Send Message') }}
                        </button>
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
                        <p>{{ __('about.Footer Text') }}</p>
                        <div class="footer-social">
                            <a href="https://www.facebook.com/pmarabchapter/" target="_blank"
                                rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://t.me/+z_AtT8ZlqehmZDhk" target="_blank" rel="noopener noreferrer"><i
                                    class="fab fa-telegram"></i></a>
                            <a href="https://www.linkedin.com/company/pm-arabcommunity/" target="_blank"
                                rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>
                            <a href="https://www.instagram.com/pm_arab_chapter/" target="_blank"
                                rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
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
            // Mobile menu toggle
            const mobileMenu = document.querySelector('.mobile-menu');
            const navLinks = document.querySelector('.nav-links');

            if (mobileMenu) {
                mobileMenu.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }

            // FAQ Accordion
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
                            ans.style.display = 'document.querySelectorAll('.faq -
                                answer ').forEach(function(ans) {
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
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    // Show loading state
                    submitBtn.disabled = true;
                    btnText.style.display = 'none';
                    btnLoading.style.display = 'inline-block';
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
                errorDiv.style.cssText = 'color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;';
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
                        showFieldError(this,
                            '{{ __('validation.min.string', ['attribute' => 'name', 'min' => 2]) }}');
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
                        showFieldError(this,
                        '{{ __('validation.required', ['attribute' => 'subject']) }}');
                    } else {
                        clearFieldError(this);
                    }
                });
            }

            if (messageInput) {
                messageInput.addEventListener('blur', function() {
                    const messageLength = this.value.trim().length;
                    if (messageLength < 10) {
                        showFieldError(this,
                            '{{ __('validation.min.string', ['attribute' => 'message', 'min' => 10]) }}'
                            );
                    } else if (messageLength > 2000) {
                        showFieldError(this,
                            '{{ __('validation.max.string', ['attribute' => 'message', 'max' => 2000]) }}'
                            );
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
                        charCount.style.cssText =
                            'font-size: 0.875rem; color: #666; text-align: right; margin-top: 0.25rem;';
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

            // Phone number formatting (optional enhancement)
            const phoneInputs = document.querySelectorAll('input[type="tel"]');
            phoneInputs.forEach(function(input) {
                input.addEventListener('input', function() {
                    // Remove all non-digit characters except + at the beginning
                    let value = this.value.replace(/[^\d+]/g, '');

                    // Ensure + is only at the beginning
                    if (value.indexOf('+') > 0) {
                        value = value.replace(/\+/g, '');
                    }

                    this.value = value;
                });
            });

            // Accessibility improvements
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

            // Handle browser back button to reset form state
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    // Reset form loading state if page was cached
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        if (btnText) btnText.style.display = 'inline-block';
                        if (btnLoading) btnLoading.style.display = 'none';
                    }
                }
            });
        });
    </script>
</body>
