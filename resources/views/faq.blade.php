{{-- resources/views/faq/index.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('lang.FAQ') }} - Sprint Skills</title>
    <link rel="shortcut icon" href="{{ asset('images/Sprint_Skills.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/FAQ.css') }}">
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
                <li><a href="{{ route('contact') }}">{{ __('lang.Contact') }}</a></li>
                <li><a href="{{ route('faq') }}" class="active">{{ __('lang.FAQ') }}</a></li>
            </ul>
        </nav>
        <div class="drawer-footer">
            <form action="{{ route('login') }}" method="GET">
                @csrf
                <button class="drawer-cta" type="submit">{{ __('lang.Get Started') }}</button>
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
                        <li><a href="{{ route('contact') }}">{{ __('lang.Contact') }}</a></li>
                        <li><a href="{{ route('faq') }}" class="active">{{ __('lang.FAQ') }}</a></li>
                    </ul>
                    <div class="header-actions">
                        <div class="language-switcher">
                            <a href="{{ route('locale.set', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                            <a href="{{ route('locale.set', 'ar') }}" class="{{ app()->getLocale() == 'ar' ? 'active' : '' }}">AR</a>
                        </div>
                        <form action="{{ route('login') }}" method="GET">
                            @csrf
                            <button class="cta-button" type="submit">{{ __('lang.Get Started') }}</button>
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
                <div class="faq-search" id="faq-search-container">
                    <input type="text" id="faq-search" placeholder="{{ __('lang.Search FAQs...') }}" autocomplete="off">
                    <i class="fas fa-search"></i>
                </div>

                <!-- FAQ Items -->
                <div class="faq-list" id="faq-list">
                    @forelse($faqs as $index => $faq)
                        <div class="faq-item" 
                             data-question="{{ strtolower($faq->localized_question) }}" 
                             data-answer="{{ strtolower($faq->localized_answer) }}"
                             data-index="{{ $index }}">
                            <button class="faq-question" type="button">
                                <h3>{{ $faq->localized_question }}</h3>
                                <div class="icon-wrapper">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </button>
                            <div class="faq-answer">
                                <div>{!! nl2br(e($faq->localized_answer)) !!}</div>
                            </div>
                        </div>
                    @empty
                        <div class="no-results">
                            <i class="fas fa-question-circle"></i>
                            <h3>{{ __('lang.No FAQs Available') }}</h3>
                            <p>{{ __('lang.We are working on adding more frequently asked questions.') }}</p>
                        </div>
                    @endforelse
                </div>

                <!-- No search results message -->
                <div class="no-results" id="no-search-results" style="display: none;">
                    <i class="fas fa-search"></i>
                    <h3>{{ __('lang.No Results Found') }}</h3>
                    <p>{{ __('lang.Try different keywords or contact us directly.') }}</p>
                    <button class="cta-button" onclick="clearSearch()" style="margin-top: 1rem;">
                        {{ __('lang.Clear Search') }}
                    </button>
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
                    <p>&copy; 2025 Sprint Skills. {{ __('lang.All rights reserved.') }}</p>
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
                        // Close all other FAQs (optional - remove these lines for multiple open FAQs)
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

            // Enhanced FAQ Search functionality
            const searchInput = document.getElementById('faq-search');
            const searchContainer = document.getElementById('faq-search-container');
            const noResultsMessage = document.getElementById('no-search-results');
            const faqList = document.getElementById('faq-list');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    
                    // Add loading state
                    searchContainer.classList.add('loading');
                    
                    // Clear previous timeout
                    clearTimeout(searchTimeout);
                    
                    // Debounce search
                    searchTimeout = setTimeout(() => {
                        performSearch(searchTerm);
                        searchContainer.classList.remove('loading');
                    }, 300);
                });

                // Clear search on escape key
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        clearSearch();
                    }
                });
            }

            function performSearch(searchTerm) {
                let hasResults = false;
                const faqItems = document.querySelectorAll('.faq-item[data-question]');

                faqItems.forEach(function(item) {
                    const question = item.dataset.question || '';
                    const answer = item.dataset.answer || '';
                    
                    if (searchTerm === '' || question.includes(searchTerm) || answer.includes(searchTerm)) {
                        item.style.display = 'block';
                        hasResults = true;
                        
                        // Add highlight for search results
                        if (searchTerm !== '' && (question.includes(searchTerm) || answer.includes(searchTerm))) {
                            item.classList.add('search-highlight');
                        } else {
                            item.classList.remove('search-highlight');
                        }
                    } else {
                        item.style.display = 'none';
                        item.classList.remove('search-highlight');
                    }
                });

                // Show/hide no results message
                if (hasResults || searchTerm === '') {
                    noResultsMessage.style.display = 'none';
                    faqList.style.display = 'block';
                } else {
                    noResultsMessage.style.display = 'block';
                    faqList.style.display = 'none';
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
            }

            // Global function for clear search button
            window.clearSearch = function() {
                searchInput.value = '';
                performSearch('');
                searchInput.focus();
            };

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

            // Close mobile menu on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024) {
                    closeMobileMenu();
                }
            });

            // Enhanced keyboard navigation for FAQs
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    const faqQuestions = Array.from(document.querySelectorAll('.faq-question:not([style*="display: none"])'));
                    const currentFocus = document.activeElement;
                    const currentIndex = faqQuestions.indexOf(currentFocus);

                    if (currentIndex !== -1) {
                        e.preventDefault();
                        let nextIndex;
                        
                        if (e.key === 'ArrowDown') {
                            nextIndex = (currentIndex + 1) % faqQuestions.length;
                        } else {
                            nextIndex = (currentIndex - 1 + faqQuestions.length) % faqQuestions.length;
                        }
                        
                        faqQuestions[nextIndex].focus();
                    }
                }
            });

            // Mobile touch interactions
            if ('ontouchstart' in window) {
                const touchElements = document.querySelectorAll('.faq-item, .cta-button, .back-to-contact');
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

                // Observe FAQ items for animation on mobile
                const faqItems = document.querySelectorAll('.faq-item');
                faqItems.forEach((element, index) => {
                    if (window.innerWidth <= 768) {
                        element.style.opacity = '0';
                        element.style.transform = 'translateY(30px)';
                        element.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
                        observer.observe(element);
                    }
                });
            }

            // Search input focus enhancement for mobile
            if (searchInput) {
                searchInput.addEventListener('focus', function() {
                    if (window.innerWidth <= 768) {
                        // Scroll to search input on mobile
                        setTimeout(() => {
                            this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 300);
                    }
                });
            }

            // Auto-expand FAQ if URL has hash
            const hash = window.location.hash;
            if (hash) {
                const faqIndex = hash.replace('#faq-', '');
                const faqItem = document.querySelector(`[data-index="${faqIndex}"]`);
                if (faqItem) {
                    const question = faqItem.querySelector('.faq-question');
                    const answer = faqItem.querySelector('.faq-answer');
                    
                    setTimeout(() => {
                        answer.style.display = 'block';
                        faqItem.classList.add('active');
                        faqItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 500);
                }
            }

            // Add FAQ sharing functionality (if needed)
            faqQuestions.forEach((question, index) => {
                question.addEventListener('contextmenu', function(e) {
                    if (window.innerWidth <= 768) {
                        e.preventDefault();
                        const faqItem = this.parentElement;
                        const faqTitle = this.querySelector('h3').textContent;
                        
                        if (navigator.share) {
                            navigator.share({
                                title: faqTitle,
                                url: `${window.location.origin}${window.location.pathname}#faq-${index}`
                            }).catch(console.error);
                        }
                    }
                });
            });

            // Performance optimization: Lazy load FAQ content
            const lazyLoadFAQs = function() {
                const faqItems = document.querySelectorAll('.faq-item:not(.loaded)');
                
                faqItems.forEach(item => {
                    const rect = item.getBoundingClientRect();
                    if (rect.top < window.innerHeight + 200) {
                        item.classList.add('loaded');
                    }
                });
            };

            // Throttled scroll event for performance
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                if (!scrollTimeout) {
                    scrollTimeout = setTimeout(function() {
                        lazyLoadFAQs();
                        scrollTimeout = null;
                    }, 100);
                }
            });

            // Initial lazy load
            lazyLoadFAQs();
        });
    </script>
</body>
</html>