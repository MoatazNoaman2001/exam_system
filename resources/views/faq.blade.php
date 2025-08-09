{{-- <div>
    <!-- Order your soul. Reduce your wants. - Augustine -->
</div> --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('lang.FAQ') }} - Sprint Skills</title>
    <link rel="shortcut icon" href="{{asset('images/Sprint_Skills.ico')}}" type="image/x-icon"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/root-welcome.css') }}">
    <style>
        html[dir="rtl"] body { direction: rtl; text-align: right; }
        html[dir="rtl"] .faq-question h3 { text-align: right; }
        
        .faq-hero {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 50%, #2980b9 100%);
            color: white;
            padding: 140px 0 80px;
            text-align: center;
        }
        
        .faq-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .faq-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .faq-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .faq-search {
            margin-bottom: 3rem;
            position: relative;
        }
        
        .faq-search input {
            width: 100%;
            padding: 1rem 1.5rem 1rem 3rem;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 1rem;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .faq-search input:focus {
            outline: none;
            border-color: #2F80ED;
            box-shadow: 0 4px 20px rgba(47, 128, 237, 0.2);
        }
        
        .faq-search i {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 1.1rem;
        }
        
        html[dir="rtl"] .faq-search i {
            left: auto;
            right: 1.2rem;
        }
        
        html[dir="rtl"] .faq-search input {
            padding: 1rem 3rem 1rem 1.5rem;
        }
        
        .faq-item {
            background: white;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .faq-item:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }
        
        .faq-question {
            padding: 1.5rem 2rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border: none;
            width: 100%;
            text-align: left;
            transition: all 0.3s ease;
        }
        
        html[dir="rtl"] .faq-question {
            text-align: right;
        }
        
        .faq-question:hover {
            background: #f8f9fa;
        }
        
        .faq-question h3 {
            font-size: 1.2rem;
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
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }
        
        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }
        
        .faq-answer {
            display: none;
            padding: 0 2rem 2rem;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }
        
        .faq-answer p {
            color: #555;
            line-height: 1.7;
            margin: 0;
            font-size: 1rem;
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
        
        .no-results {
            text-align: center;
            padding: 3rem 2rem;
            color: #666;
            font-size: 1.1rem;
        }
        
        .back-to-contact {
            text-align: center;
            margin-top: 3rem;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .back-to-contact h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .back-to-contact p {
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .faq-hero {
                padding: 120px 0 60px;
            }
            
            .faq-hero h1 {
                font-size: 2.2rem;
            }
            
            .faq-section {
                padding: 60px 0;
            }
            
            .faq-question {
                padding: 1.2rem 1.5rem;
            }
            
            .faq-question h3 {
                font-size: 1.1rem;
            }
            
            .faq-answer {
                padding: 0 1.5rem 1.5rem;
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
                    <li><a href="{{ route('faq') }}" class="active">{{ __('lang.FAQ') }}</a></li>
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

    <!-- FAQ Hero Section -->
    <section class="faq-hero">
        <div class="container">
            <h1>{{ __('lang.Frequently Asked Questions') }}</h1>
            <p>{{ __('lang.Find answers to common questions about our platform and services') }}</p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="faq-container">
                <!-- Search Box -->
                <div class="faq-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="faq-search" placeholder="{{ __('lang.Search FAQs...') }}">
                </div>

                <!-- FAQ Items -->
                <div class="faq-list">
                    @forelse($faqs as $faq)
                        <div class="faq-item" data-question="{{ strtolower($faq->localized_question) }}" data-answer="{{ strtolower($faq->localized_answer) }}">
                            <button class="faq-question" type="button">
                                <h3>{{ $faq->localized_question }}</h3>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="faq-answer">
                                <div>{!! nl2br(e($faq->localized_answer)) !!}</div>
                            </div>
                        </div>
                    @empty
                        <div class="no-results">
                            <i class="fas fa-question-circle" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                            <h3>{{ __('lang.No FAQs Available') }}</h3>
                            <p>{{ __('lang.We are working on adding more frequently asked questions.') }}</p>
                        </div>
                    @endforelse
                </div>

                <!-- No search results message -->
                <div class="no-results" id="no-search-results" style="display: none;">
                    <i class="fas fa-search" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <h3>{{ __('lang.No Results Found') }}</h3>
                    <p>{{ __('lang.Try different keywords or contact us directly.') }}</p>
                </div>

                <!-- Contact CTA -->
                <div class="back-to-contact">
                    <h3>{{ __('lang.Still Have Questions?') }}</h3>
                    <p>{{ __('lang.Can\'t find what you\'re looking for? Get in touch with our support team.') }}</p>
                    <a href="{{ route('contact') }}" class="cta-button">{{ __('lang.Contact Us') }}</a>
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
                        <a href="https://www.facebook.com/pmarabchapter/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://t.me/+z_AtT8ZlqehmZDhk" target="_blank"><i class="fab fa-telegram"></i></a>
                        <a href="https://www.linkedin.com/company/pm-arabcommunity/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.instagram.com/pm_arab_chapter/" target="_blank"><i class="fab fa-instagram"></i></a>
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

            // FAQ Search functionality
            const searchInput = document.getElementById('faq-search');
            const noResultsMessage = document.getElementById('no-search-results');
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    let hasResults = false;

                    document.querySelectorAll('.faq-item').forEach(function(item) {
                        const question = item.dataset.question || '';
                        const answer = item.dataset.answer || '';
                        
                        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                            item.style.display = 'block';
                            hasResults = true;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Show/hide no results message
                    if (hasResults || searchTerm === '') {
                        noResultsMessage.style.display = 'none';
                    } else {
                        noResultsMessage.style.display = 'block';
                    }

                    // Close all open FAQs when searching
                    if (searchTerm !== '') {
                        document.querySelectorAll('.faq-answer').forEach(function(answer) {
                            answer.style.display = 'none';
                        });
                        document.querySelectorAll('.faq-item').forEach(function(item) {
                            item.classList.remove('active');
                        });
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
        });
    </script>
</body>
</html>