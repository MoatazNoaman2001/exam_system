{{-- Shared Navigation Component --}}
<nav class="shared-navbar" id="shared-navbar">  
    <div class="navbar-container">
        <!-- Logo -->
        <div class="navbar-logo">
            <a href="{{ route('welcome') }}" class="logo-link">
                <img src="{{ asset('images/Sprint_Skills_Logo_NoText.png') }}" alt="Sprint Skills Logo" class="logo-image">
                <span class="logo-text">{{ __('Sprint Skills') }}</span>
            </a>
        </div>

        <!-- Desktop Navigation Links -->
        <div class="navbar-menu" id="navbar-menu">
            <ul class="navbar-nav">
                @if(request()->routeIs('welcome'))
                    {{-- Home page - navigate to sections --}}
                    <li class="nav-item">
                        <a href="#features" class="nav-link">
                            <i class="fas fa-star nav-icon"></i>
                            <span>{{ __('lang.Features') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#study-plan" class="nav-link">
                            <i class="fas fa-calendar-alt nav-icon"></i>
                            <span>{{ __('lang.Study Plan') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#practice-exams" class="nav-link">
                            <i class="fas fa-clipboard-list nav-icon"></i>
                            <span>{{ __('lang.Practice Exams') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#testimonials" class="nav-link">
                            <i class="fas fa-comments nav-icon"></i>
                            <span>{{ __('lang.Testimonials') }}</span>
                        </a>
                    </li>
                @else
                    {{-- Other pages - navigate to different pages --}}
                    <li class="nav-item">
                        <a href="{{ route('welcome') }}" class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}">
                            <i class="fas fa-home nav-icon"></i>
                            <span>{{ __('lang.Home') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                            <i class="fas fa-info-circle nav-icon"></i>
                            <span>{{ __('lang.About') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                            <i class="fas fa-envelope nav-icon"></i>
                            <span>{{ __('lang.Contact') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('faq') }}" class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}">
                            <i class="fas fa-question-circle nav-icon"></i>
                            <span>{{ __('lang.FAQ') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Right Side Actions -->
        <div class="navbar-actions">
            <!-- Language Switcher -->
            <div class="language-switcher">
                @if(app()->getLocale() == 'en')
                    <a href="{{ route('locale.set', 'ar') }}" class="language-btn">
                        <span class="flag">🇸🇦</span>
                        <span>العربية</span>
                    </a>
                @else
                    <a href="{{ route('locale.set', 'en') }}" class="language-btn">
                        <span class="flag">🇺🇸</span>
                        <span>English</span>
                    </a>
                @endif
            </div>

            <!-- Get Started Button -->
            <div class="cta-wrapper">
                <form action="{{ route('login') }}" method="GET" class="cta-form">
                    @csrf
                    <button type="submit" class="cta-button">
                        <i class="fas fa-rocket"></i>
                        <span>{{ __('lang.Get Started') }}</span>
                    </button>
                </form>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-toggle" id="mobile-toggle" aria-label="Toggle navigation">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-overlay" id="mobile-overlay"></div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobile-menu">
        <div class="mobile-menu-header">
            <div class="mobile-logo">
                <img src="{{ asset('images/Sprint_Skills_Logo_NoText.png') }}" alt="Sprint Skills">
                <span>{{ __('Sprint Skills') }}</span>
            </div>
            <button class="mobile-close" id="mobile-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mobile-menu-content">
            <ul class="mobile-nav">
                @if(request()->routeIs('welcome'))
                    {{-- Home page - navigate to sections --}}
                    <li class="mobile-nav-item">
                        <a href="#features" class="mobile-nav-link">
                            <i class="fas fa-star"></i>
                            <span>{{ __('lang.Features') }}</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="#study-plan" class="mobile-nav-link">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ __('lang.Study Plan') }}</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="#practice-exams" class="mobile-nav-link">
                            <i class="fas fa-clipboard-list"></i>
                            <span>{{ __('lang.Practice Exams') }}</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="#testimonials" class="mobile-nav-link">
                            <i class="fas fa-comments"></i>
                            <span>{{ __('lang.Testimonials') }}</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    {{-- Other pages - navigate to different pages --}}
                    <li class="mobile-nav-item">
                        <a href="{{ route('welcome') }}" class="mobile-nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>{{ __('lang.Home') }}</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="{{ route('about') }}" class="mobile-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                            <i class="fas fa-info-circle"></i>
                            <span>{{ __('lang.About') }}</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="{{ route('contact') }}" class="mobile-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                            <i class="fas fa-envelope"></i>
                            <span>{{ __('lang.Contact') }}</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="{{ route('faq') }}" class="mobile-nav-link {{ request()->routeIs('faq') ? 'active' : '' }}">
                            <i class="fas fa-question-circle"></i>
                            <span>{{ __('lang.FAQ') }}</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @endif
            </ul>

            <div class="mobile-actions">
                <!-- Mobile Language Switcher -->
                <div class="mobile-language">
                    @if(app()->getLocale() == 'en')
                        <a href="{{ route('locale.set', 'ar') }}" class="mobile-language-btn">
                            <span class="flag">🇸🇦</span>
                            <span>تبديل إلى العربية</span>
                        </a>
                    @else
                        <a href="{{ route('locale.set', 'en') }}" class="mobile-language-btn">
                            <span class="flag">🇺🇸</span>
                            <span>Switch to English</span>
                        </a>
                    @endif
                </div>

                <!-- Mobile CTA Button -->
                <div class="mobile-cta">
                    <form action="{{ route('login') }}" method="GET">
                        @csrf
                        <button type="submit" class="mobile-cta-button">
                            <i class="fas fa-rocket"></i>
                            <span>{{ __('lang.Get Started') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
/* Navigation Reset - Override any conflicting styles */
.shared-navbar * {
    box-sizing: border-box;
}

.shared-navbar ul {
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
}

.shared-navbar a {
    text-decoration: none !important;
}

/* Shared Navigation Styles - High Specificity */
.shared-navbar {
    position: fixed !important;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000 !important;
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
}

.shared-navbar.scrolled {
    background: rgba(255, 255, 255, 0.98);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.12);
}

.shared-navbar .navbar-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex !important;
    align-items: center;
    justify-content: space-between;
    height: 80px;
}

/* Logo Styles */
.navbar-logo {
    flex: 1;
    max-width: 400px;
    min-width: 300px;
}

.logo-link {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.logo-link:hover {
    transform: scale(1.02);
}

.logo-image {
    width: 60px;
    height: 60px;
    object-fit: contain;
    border-radius: 8px;
}

.logo-text {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2F80ED;
    font-family: 'Poppins', sans-serif;
    white-space: nowrap;
}

/* Desktop Navigation - High Specificity */
.shared-navbar .navbar-menu {
    display: flex !important;
    align-items: center;
    flex: 0 0 auto;
}

/* Ensure navbar menu is visible on desktop and tablets */
@media (min-width: 769px) {
    .shared-navbar .navbar-menu {
        display: flex !important;
    }
}

.shared-navbar .navbar-nav {
    display: flex !important;
    flex-direction: row !important;
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
    gap: 2rem;
    align-items: center !important;
}

.shared-navbar .nav-item {
    position: relative;
}

.shared-navbar .nav-link {
    display: flex !important;
    align-items: center;
    gap: 8px;
    padding: 12px 16px !important;
    text-decoration: none !important;
    color: #4a5568 !important;
    font-weight: 500 !important;
    font-size: 0.95rem !important;
    border-radius: 8px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.shared-navbar .nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #2F80ED, #1565C0);
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 8px;
}

.shared-navbar .nav-link:hover::before,
.shared-navbar .nav-link.active::before {
    opacity: 0.1;
}

.shared-navbar .nav-link:hover {
    color: #2F80ED !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(47, 128, 237, 0.2);
}

.shared-navbar .nav-link.active {
    color: #2F80ED !important;
    background: rgba(47, 128, 237, 0.08) !important;
}

.shared-navbar .nav-icon {
    font-size: 0.9rem !important;
    position: relative;
    z-index: 1;
}

.shared-navbar .nav-link span {
    position: relative;
    z-index: 1;
}

/* Right Side Actions */
.shared-navbar .navbar-actions {
    display: flex !important;
    align-items: center;
    gap: 1.5rem;
    flex: 0 0 auto;
}

/* Language Switcher */
.language-switcher {
    position: relative;
}

.language-dropdown {
    position: relative;
}

.language-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    color: #4a5568;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    font-weight: 500;
}

.language-btn:hover {
    background: #edf2f7;
    border-color: #cbd5e0;
    transform: translateY(-1px);
    color: #2F80ED;
    text-decoration: none;
}


.flag {
    font-size: 1.2rem;
}

/* CTA Button */
.cta-form {
    margin: 0;
}

.cta-button {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #2F80ED, #1565C0);
    color: white;
    border: none;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(47, 128, 237, 0.3);
}

.cta-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(47, 128, 237, 0.4);
    background: linear-gradient(135deg, #1565C0, #0d47a1);
}

.cta-button:active {
    transform: translateY(0);
}

/* Mobile Toggle */
.mobile-toggle {
    display: none !important;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    gap: 4px;
}

/* Show mobile toggle only on mobile */
@media (max-width: 768px) {
    .shared-navbar .mobile-toggle {
        display: flex !important;
    }
}

/* iPhone and mobile specific */
@media (max-width: 414px) {
    .shared-navbar .navbar-container {
        display: flex !important;
        justify-content: space-between !important;
    }
    
    .shared-navbar .navbar-menu {
        display: none !important;
    }
    
    .shared-navbar .language-switcher {
        display: none !important;
    }
    
    .shared-navbar .cta-wrapper {
        display: none !important;
    }
    
    .shared-navbar .mobile-toggle {
        display: flex !important;
    }
}

.hamburger-line {
    width: 24px;
    height: 2px;
    background: #4a5568;
    border-radius: 2px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.mobile-toggle.active .hamburger-line:nth-child(1) {
    transform: rotate(45deg) translate(6px, 6px);
}

.mobile-toggle.active .hamburger-line:nth-child(2) {
    opacity: 0;
}

.mobile-toggle.active .hamburger-line:nth-child(3) {
    transform: rotate(-45deg) translate(6px, -6px);
}

/* Mobile Menu */
.mobile-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 999;
}

.mobile-overlay.active {
    opacity: 1;
    visibility: visible;
}

.mobile-menu {
    position: fixed;
    top: 0;
    right: -100%;
    width: 280px;
    max-width: 80vw;
    height: 100vh;
    background: white;
    z-index: 1001;
    transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
    box-shadow: -10px 0 30px rgba(0, 0, 0, 0.2);
}

.mobile-menu.active {
    right: 0;
}

.mobile-menu-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    border-bottom: 1px solid #e2e8f0;
}

.mobile-logo {
    display: flex;
    align-items: center;
    gap: 10px;
}

.mobile-logo img {
    width: 32px;
    height: 32px;
    border-radius: 6px;
}

.mobile-logo span {
    font-size: 1rem;
    font-weight: 600;
    color: #2F80ED;
}

.mobile-close {
    width: 36px;
    height: 36px;
    border: none;
    background: #f7fafc;
    border-radius: 8px;
    color: #4a5568;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.mobile-close:hover {
    background: #edf2f7;
    color: #2d3748;
}

.mobile-menu-content {
    padding: 16px;
}

.mobile-nav {
    list-style: none;
    margin: 0 0 24px 0;
    padding: 0;
}

.mobile-nav-item {
    margin-bottom: 6px;
}

.mobile-nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    text-decoration: none;
    color: #4a5568;
    font-weight: 500;
    font-size: 0.9rem;
    border-radius: 10px;
    transition: all 0.3s ease;
    position: relative;
}

.mobile-nav-link:hover {
    background: #f7fafc;
    color: #2F80ED;
    transform: translateX(4px);
}

.mobile-nav-link.active {
    background: rgba(47, 128, 237, 0.08);
    color: #2F80ED;
}

.mobile-nav-link i:first-child {
    width: 20px;
    text-align: center;
}

.mobile-nav-link i:last-child {
    margin-left: auto;
    font-size: 0.8rem;
    opacity: 0.5;
}

.mobile-actions {
    border-top: 1px solid #e2e8f0;
    padding-top: 16px;
}

.mobile-language {
    margin-bottom: 20px;
}

.mobile-language h4 {
    margin: 0 0 12px 0;
    font-size: 0.9rem;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.mobile-language-options {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.mobile-language-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    text-decoration: none;
    color: #4a5568;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.mobile-language-option:hover {
    background: #f7fafc;
}

.mobile-language-option.active {
    background: rgba(47, 128, 237, 0.08);
    color: #2F80ED;
}

.mobile-language-option i {
    margin-left: auto;
    color: #48bb78;
}

.mobile-language-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    text-decoration: none;
    color: #4a5568;
    background: #f7fafc;
    border-radius: 10px;
    transition: all 0.2s ease;
    font-weight: 500;
    font-size: 0.9rem;
    width: 100%;
    justify-content: center;
}

.mobile-language-btn:hover {
    background: #edf2f7;
    color: #2F80ED;
    text-decoration: none;
}

.mobile-cta-button {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px;
    background: linear-gradient(135deg, #2F80ED, #1565C0);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.mobile-cta-button:hover {
    background: linear-gradient(135deg, #1565C0, #0d47a1);
    transform: translateY(-2px);
}

/* RTL Support */
html[dir="rtl"] .mobile-menu {
    right: auto;
    left: -100%;
    box-shadow: 10px 0 30px rgba(0, 0, 0, 0.2);
}

html[dir="rtl"] .mobile-menu.active {
    left: 0;
}

html[dir="rtl"] .language-menu {
    right: auto;
    left: 0;
}

html[dir="rtl"] .mobile-nav-link {
    flex-direction: row-reverse;
}

html[dir="rtl"] .mobile-nav-link:hover {
    transform: translateX(-4px);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .navbar-nav {
        gap: 1.5rem;
    }
    
    .nav-link {
        padding: 10px 12px;
        font-size: 0.9rem;
    }
}

@media (max-width: 768px) {
    .shared-navbar .navbar-container {
        display: flex !important;
        justify-content: space-between !important;
        padding: 0 16px;
        height: 70px;
    }
    
    .shared-navbar .navbar-logo {
        flex: 1;
        max-width: none;
        min-width: auto;
    }
    
    .logo-image {
        width: 45px;
        height: 45px;
    }
    
    .logo-text {
        font-size: 1.4rem;
    }
    
    .shared-navbar .navbar-menu {
        display: none !important;
    }
    
    .shared-navbar .language-switcher {
        display: none !important;
    }
    
    .shared-navbar .cta-wrapper {
        display: none !important;
    }
    
    .shared-navbar .mobile-toggle {
        display: flex !important;
    }
}

@media (max-width: 480px) {
    .navbar-container {
        padding: 0 12px;
        height: 65px;
    }
    
    .logo-image {
        width: 40px;
        height: 40px;
    }
    
    .logo-text {
        font-size: 1.3rem;
    }
    
    .mobile-menu {
        width: 100%;
        max-width: 100%;
    }
}

/* Animation Classes */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.mobile-menu.active {
    animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Focus States */
.nav-link:focus,
.language-btn:focus,
.cta-button:focus,
.mobile-toggle:focus,
.mobile-nav-link:focus {
    outline: 2px solid #2F80ED;
    outline-offset: 2px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Navigation elements
    const navbar = document.getElementById('shared-navbar');
    const mobileToggle = document.getElementById('mobile-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileOverlay = document.getElementById('mobile-overlay');
    const mobileClose = document.getElementById('mobile-close');

    // Scroll effect for navbar
    let lastScrollY = window.scrollY;
    
    function handleScroll() {
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        // Hide/show navbar on scroll (optional)
        if (currentScrollY > lastScrollY && currentScrollY > 200) {
            navbar.style.transform = 'translateY(-100%)';
        } else {
            navbar.style.transform = 'translateY(0)';
        }
        
        lastScrollY = currentScrollY;
    }

    // Throttled scroll handler
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (!scrollTimeout) {
            scrollTimeout = setTimeout(function() {
                handleScroll();
                scrollTimeout = null;
            }, 10);
        }
    });

    // Mobile menu functionality
    function openMobileMenu() {
        mobileToggle.classList.add('active');
        mobileMenu.classList.add('active');
        mobileOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        mobileToggle.classList.remove('active');
        mobileMenu.classList.remove('active');
        mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Mobile menu event listeners
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            if (mobileMenu.classList.contains('active')) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });
    }

    if (mobileClose) {
        mobileClose.addEventListener('click', closeMobileMenu);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMobileMenu);
    }

    // Close mobile menu when clicking on nav links
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });


    // Close mobile menu on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeMobileMenu();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMobileMenu();
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const offsetTop = target.offsetTop - 80; // Account for fixed navbar
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Add active class to current page nav link
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && (currentPath === href || (href !== '/' && currentPath.startsWith(href)))) {
            link.classList.add('active');
        }
    });

    // Intersection Observer for animations (optional)
    if ('IntersectionObserver' in window) {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
    }
});
</script>
