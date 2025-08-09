// Enhanced Mobile Navigation with Modern Drawer
document.addEventListener('DOMContentLoaded', function() {
    
    // Create enhanced mobile navigation elements
    createMobileNavigation();
    
    // Initialize mobile menu functionality
    initializeMobileMenu();
    
    // Initialize smooth scrolling
    initializeSmoothScroll();
    
    // Initialize header scroll effects
    initializeHeaderEffects();
    
    // Initialize intersection observer for animations
    initializeScrollAnimations();
    
    // Initialize newsletter form
    initializeNewsletterForm();
    
    // Initialize touch gestures
    initializeTouchGestures();
});

function createMobileNavigation() {
    // Create overlay
    const overlay = document.createElement('div');
    overlay.className = 'nav-overlay';
    overlay.id = 'nav-overlay';
    
    // Create mobile drawer
    const drawer = document.createElement('div');
    drawer.className = 'mobile-nav-drawer';
    drawer.id = 'mobile-nav-drawer';
    
    // Get current language and logo
    const currentLogo = document.querySelector('.logo img');
    const currentLogoSrc = currentLogo ? currentLogo.src : '';
    const currentBrandName = document.querySelector('.logo span')?.textContent || 'Sprint Skills';
    const currentLang = document.documentElement.lang || 'en';
    
    // Get navigation links
    const navLinks = document.querySelectorAll('.nav-links a');
    const languageSwitcher = document.querySelector('.language-switcher');
    
    // Create drawer content
    drawer.innerHTML = `
        <div class="drawer-header">
            <div class="drawer-logo">
                ${currentLogoSrc ? `<img src="${currentLogoSrc}" alt="Logo">` : ''}
                <span>${currentBrandName}</span>
            </div>
        </div>
        
        <nav class="drawer-nav">
            <ul>
                ${Array.from(navLinks).map((link, index) => `
                    <li>
                        <a href="${link.href}">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                                ${getNavIcon(index)}
                            </svg>
                            ${link.textContent}
                        </a>
                    </li>
                `).join('')}
            </ul>
        </nav>
        
        <div class="drawer-footer">
            <form action="${document.querySelector('form[action*="login"]')?.action || '/login'}" method="GET">
                <button class="drawer-cta" type="submit">
                    ${document.querySelector('.cta-button')?.textContent || 'Get Started'}
                </button>
            </form>
            
            ${languageSwitcher ? `
                <div class="drawer-language">
                    <div class="drawer-language-title">Language</div>
                    <div class="drawer-language-options">
                        ${Array.from(languageSwitcher.children).map(link => `
                            <a href="${link.href}" class="${link.classList.contains('active') ? 'active' : ''}">${link.textContent}</a>
                        `).join('')}
                    </div>
                </div>
            ` : ''}
        </div>
    `;
    
    // Update mobile menu button with hamburger animation
    const mobileMenu = document.querySelector('.mobile-menu');
    if (mobileMenu) {
        mobileMenu.innerHTML = `
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;
    }
    
    // Append to body
    document.body.appendChild(overlay);
    document.body.appendChild(drawer);
}

function getNavIcon(index) {
    const icons = [
        // Features icon
        '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>',
        // Study Plan icon  
        '<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>',
        // Practice Exams icon
        '<path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 2 2h16c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>',
        // Progress icon
        '<path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>',
        // Testimonials icon
        '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>'
    ];
    return icons[index] || icons[0];
}

function initializeMobileMenu() {
    const mobileMenu = document.querySelector('.mobile-menu');
    const overlay = document.getElementById('nav-overlay');
    const drawer = document.getElementById('mobile-nav-drawer');
    
    if (!mobileMenu || !overlay || !drawer) return;
    
    // Toggle menu
    function toggleMenu(open = null) {
        const isOpen = open !== null ? open : !mobileMenu.classList.contains('active');
        
        mobileMenu.classList.toggle('active', isOpen);
        overlay.classList.toggle('active', isOpen);
        drawer.classList.toggle('active', isOpen);
        
        // Prevent body scroll when menu is open
        document.body.style.overflow = isOpen ? 'hidden' : '';
        
        // Add animation classes to nav items
        if (isOpen) {
            const navItems = drawer.querySelectorAll('.drawer-nav li');
            navItems.forEach((item, index) => {
                item.style.animationDelay = `${(index + 1) * 0.1}s`;
            });
        }
    }
    
    // Event listeners
    mobileMenu.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        toggleMenu();
    });
    
    overlay.addEventListener('click', () => toggleMenu(false));
    
    // Close menu when clicking on nav links
    drawer.querySelectorAll('.drawer-nav a').forEach(link => {
        link.addEventListener('click', () => {
            setTimeout(() => toggleMenu(false), 300);
        });
    });
    
    // Handle escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
            toggleMenu(false);
        }
    });
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth > 1024 && mobileMenu.classList.contains('active')) {
                toggleMenu(false);
            }
        }, 150);
    });
}

function initializeSmoothScroll() {
    // Enhanced smooth scrolling with easing
    document.querySelectorAll('a[href*="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerHeight = document.querySelector('#header')?.offsetHeight || 80;
                const offsetTop = target.offsetTop - headerHeight - 20;
                
                // Custom smooth scroll with easing
                smoothScrollTo(offsetTop, 800);
            }
        });
    });
}

function smoothScrollTo(targetPosition, duration) {
    const startPosition = window.pageYOffset;
    const distance = targetPosition - startPosition;
    const startTime = performance.now();
    
    function easeInOutCubic(t) {
        return t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
    }
    
    function animation(currentTime) {
        const timeElapsed = currentTime - startTime;
        const progress = Math.min(timeElapsed / duration, 1);
        const ease = easeInOutCubic(progress);
        
        window.scrollTo(0, startPosition + distance * ease);
        
        if (progress < 1) {
            requestAnimationFrame(animation);
        }
    }
    
    requestAnimationFrame(animation);
}

function initializeHeaderEffects() {
    let lastScrollY = window.scrollY;
    let ticking = false;
    
    function updateHeader() {
        const currentScrollY = window.scrollY;
        const header = document.querySelector('#header');
        
        if (!header) return;
        
        // Add scrolled class
        if (currentScrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        // Hide/show header on scroll (only on mobile)
        if (window.innerWidth <= 768) {
            if (currentScrollY > lastScrollY && currentScrollY > 200) {
                header.style.transform = 'translateY(-100%)';
            } else {
                header.style.transform = 'translateY(0)';
            }
        } else {
            header.style.transform = 'translateY(0)';
        }
        
        lastScrollY = currentScrollY;
        ticking = false;
    }
    
    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(updateHeader);
            ticking = true;
        }
    });
}

function initializeScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                
                // Handle staggered animations for cards
                if (entry.target.classList.contains('features-grid') ||
                    entry.target.classList.contains('exams-grid')) {
                    const cards = entry.target.children;
                    Array.from(cards).forEach((card, index) => {
                        setTimeout(() => {
                            card.classList.add('animate');
                        }, index * 200);
                    });
                }
                
                // Handle progress bars
                const progressBars = entry.target.querySelectorAll('.progress-fill');
                progressBars.forEach(bar => {
                    const width = bar.dataset.width || bar.style.width;
                    bar.style.width = '0';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 300);
                });
            }
        });
    }, observerOptions);
    
    // Observe elements
    document.querySelectorAll('.section-title, .features-grid, .exams-grid, .feature-card, .exam-card, .testimonial-card').forEach(el => {
        observer.observe(el);
    });
}

function initializeNewsletterForm() {
    const form = document.querySelector('.newsletter-form');
    if (!form) return;
    
    // Prevent default form submission and add custom handling
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const input = form.querySelector('.newsletter-input');
        const button = form.querySelector('.newsletter-btn');
        const email = input.value.trim();
        
        if (!email || !isValidEmail(email)) {
            showNotification('Please enter a valid email address', 'error');
            return;
        }
        
        // Simulate newsletter subscription
        button.textContent = 'Subscribing...';
        button.disabled = true;
        
        setTimeout(() => {
            showNotification('Thank you for subscribing!', 'success');
            input.value = '';
            button.textContent = 'Subscribe';
            button.disabled = false;
        }, 1500);
    });
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'error' ? '#ff4757' : '#2ed573'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        max-width: 300px;
        word-wrap: break-word;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Hide notification
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 400);
    }, 3000);
}

function initializeTouchGestures() {
    const drawer = document.getElementById('mobile-nav-drawer');
    if (!drawer) return;
    
    let startX = 0;
    let currentX = 0;
    let isDragging = false;
    
    drawer.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        isDragging = true;
        drawer.style.transition = 'none';
    });
    
    drawer.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        
        currentX = e.touches[0].clientX;
        const deltaX = currentX - startX;
        
        // Only allow swiping to close (swipe right)
        if (deltaX > 0) {
            const translateX = Math.min(deltaX, drawer.offsetWidth);
            drawer.style.transform = `translateX(${translateX}px)`;
        }
    });
    
    drawer.addEventListener('touchend', () => {
        if (!isDragging) return;
        
        isDragging = false;
        drawer.style.transition = '';
        drawer.style.transform = '';
        
        const deltaX = currentX - startX;
        
        // Close drawer if swiped more than 50px
        if (deltaX > 50) {
            const mobileMenu = document.querySelector('.mobile-menu');
            if (mobileMenu) {
                mobileMenu.click();
            }
        }
    });
}

// Utility function for debouncing
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Performance optimized scroll handler
const optimizedScrollHandler = debounce(() => {
    // Add any additional scroll-based animations here
}, 16); // ~60fps

window.addEventListener('scroll', optimizedScrollHandler);

// Handle page visibility changes
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        // Pause animations when page is not visible
        document.body.style.animationPlayState = 'paused';
    } else {
        // Resume animations when page becomes visible
        document.body.style.animationPlayState = 'running';
    }
});

// Enhanced loading animation
window.addEventListener('load', () => {
    document.body.classList.add('loaded');
    
    // Stagger loading animations
    const elements = document.querySelectorAll('.hero-text, .hero-image');
    elements.forEach((el, index) => {
        setTimeout(() => {
            el.classList.add('fade-in-up');
        }, index * 300);
    });
});