// Register GSAP Plugins
gsap.registerPlugin(ScrollTrigger, TextPlugin);

class GSAPPortfolio {
    constructor() {
        this.init();
    }

    init() {
        this.setupCursor();
        this.setupLoader();
        this.createFloatingElements();
        this.createCodeRain();
        this.setupScrollProgress();
        this.setupSectionIndicators();
    }

    setupCursor() {
        const cursor = document.querySelector('.cursor');
        
        document.addEventListener('mousemove', (e) => {
            gsap.to(cursor, {
                x: e.clientX - 10,
                y: e.clientY - 10,
                duration: 0.3,
                ease: "power2.out"
            });
        });

        document.addEventListener('mousedown', () => {
            gsap.to(cursor, {scale: 0.8, duration: 0.1});
        });

        document.addEventListener('mouseup', () => {
            gsap.to(cursor, {scale: 1, duration: 0.1});
        });

        // Hide cursor on mobile
        if (window.matchMedia("(max-width: 768px)").matches) {
            cursor.style.display = 'none';
        }
    }

    setupLoader() {
        const loader = document.getElementById('loader');
        const loaderFill = document.getElementById('loaderFill');
        
        console.log('🔧 Setting up loader...', { loader, loaderFill });
        
        if (!loader || !loaderFill) {
            console.error('❌ Loader elements not found!');
            return;
        }
        
        // Force show loader initially
        loader.style.display = 'flex';
        loader.style.opacity = '1';
        
        console.log('🚀 Starting loading animation...');
        
        // Start loading animation immediately
        let progress = 0;
        const loadingInterval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress >= 100) {
                progress = 100;
                clearInterval(loadingInterval);
                
                console.log('✅ Loading complete!');
                
                // Complete loading
                loaderFill.style.width = '100%';
                
                // Hide loader after completion
                setTimeout(() => {
                    loader.style.transition = 'opacity 1s ease';
                    loader.style.opacity = '0';
                    
                    setTimeout(() => {
                        loader.style.display = 'none';
                        console.log('🎬 Starting animations...');
                        this.startAnimations();
                    }, 1000);
                }, 500);
            } else {
                loaderFill.style.width = progress + '%';
            }
        }, 100);
    }

    startAnimations() {
        this.animateHero();
        this.animateSummary();
        this.animateSkills();
        this.animateExperience();
        this.animateProjects();
        this.animateEducation();
        this.animateContact();
        this.setupMagneticEffect();
        this.setupKeyboardNavigation();
    }

    animateHero() {
        const chars = document.querySelectorAll('.animated-name .char');
        const positionText = document.querySelector('.position-text');
        const heroSubtitle = document.querySelector('.hero-subtitle');
        const scrollIndicator = document.querySelector('.scroll-indicator');

        // Animate name characters
        gsap.to(chars, {
            opacity: 1,
            y: 0,
            rotationX: 0,
            duration: 1.2,
            stagger: 0.1,
            ease: "power3.out",
            delay: 0.5
        });

        // Animate position text with typewriter effect
        setTimeout(() => {
            this.typewriterEffect(positionText, "MEARN Stack Developer");
        }, 2000);

        // Animate hero subtitle
        gsap.to(heroSubtitle, {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: "power2.out",
            delay: 3.5
        });

        // Animate scroll indicator
        gsap.to(scrollIndicator, {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: "power2.out",
            delay: 4
        });

        // Add glitch effect on hover
        chars.forEach(char => {
            char.addEventListener('mouseenter', () => {
                gsap.to(char, {
                    duration: 0.1,
                    repeat: 3,
                    yoyo: true,
                    x: gsap.utils.random(-5, 5),
                    y: gsap.utils.random(-5, 5)
                });
            });
        });
    }

    typewriterEffect(element, text) {
        element.textContent = '';
        element.style.opacity = '1';
        
        let i = 0;
        const typeInterval = setInterval(() => {
            element.textContent += text[i];
            i++;
            if (i >= text.length) {
                clearInterval(typeInterval);
                
                // Add blinking cursor
                const cursor = document.createElement('span');
                cursor.textContent = '|';
                cursor.style.animation = 'blink 1s infinite';
                element.appendChild(cursor);
                
                // Add blink animation
                if (!document.getElementById('blink-style')) {
                    const style = document.createElement('style');
                    style.id = 'blink-style';
                    style.textContent = `
                        @keyframes blink {
                            0%, 50% { opacity: 1; }
                            51%, 100% { opacity: 0; }
                        }
                    `;
                    document.head.appendChild(style);
                }
            }
        }, 100);
    }

    animateSummary() {
        const sectionLabel = document.querySelector('.section-label');
        const summaryText = document.getElementById('summaryText');
        const summaryStats = document.querySelector('.summary-stats');

        // Split text into words
        const words = summaryText.textContent.split(' ');
        summaryText.innerHTML = words.map(word => `<span class="word">${word}</span>`).join(' ');
        const wordElements = document.querySelectorAll('.summary-text .word');

        // Animate section label
        gsap.to(sectionLabel, {
            scrollTrigger: {
                trigger: '.summary-section',
                start: 'top 80%',
                end: 'bottom 20%',
            },
            opacity: 1,
            y: 0,
            duration: 1,
            ease: "power2.out"
        });

        // Animate words progressively
        ScrollTrigger.create({
            trigger: '.summary-section',
            start: 'top 50%',
            end: 'bottom 20%',
            onUpdate: (self) => {
                const progress = self.progress;
                const wordsToShow = Math.floor(progress * wordElements.length);
                
                wordElements.forEach((word, index) => {
                    if (index <= wordsToShow) {
                        word.classList.add('active');
                    } else {
                        word.classList.remove('active');
                    }
                });
            }
        });

        // Animate stats
        gsap.to(summaryStats, {
            scrollTrigger: {
                trigger: summaryStats,
                start: 'top 80%',
                end: 'bottom 20%',
            },
            opacity: 1,
            y: 0,
            duration: 1,
            ease: "power2.out"
        });

        // Animate stat numbers
        this.animateStatNumbers();
    }

    animateStatNumbers() {
        document.querySelectorAll('.stat-number').forEach(stat => {
            const finalText = stat.textContent;
            stat.textContent = '0';
            
            ScrollTrigger.create({
                trigger: stat,
                start: 'top 80%',
                onEnter: () => {
                    if (finalText.includes('+')) {
                        const number = parseInt(finalText);
                        gsap.to(stat, {
                            textContent: number,
                            duration: 2,
                            ease: "power2.out",
                            snap: { textContent: 1 },
                            onUpdate: function() {
                                stat.textContent = Math.floor(this.targets()[0].textContent) + '+';
                            }
                        });
                    } else if (finalText.includes('%')) {
                        const number = parseInt(finalText);
                        gsap.to(stat, {
                            textContent: number,
                            duration: 2,
                            ease: "power2.out",
                            snap: { textContent: 1 },
                            onUpdate: function() {
                                stat.textContent = Math.floor(this.targets()[0].textContent) + '%';
                            }
                        });
                    }
                }
            });
        });
    }

    animateSkills() {
        const skillsTitle = document.querySelector('.skills-title');
        const skillsCategories = document.querySelector('.skills-categories');
        const largeSkills = document.getElementById('largeSkills');

        // Animate skills title
        gsap.to(skillsTitle, {
            scrollTrigger: {
                trigger: '.skills-section',
                start: 'top 70%',
                end: 'bottom 30%',
            },
            opacity: 1,
            scale: 1,
            duration: 1.5,
            ease: "power3.out"
        });

        // Animate large background text
        if (largeSkills) {
            gsap.to(largeSkills, {
                scrollTrigger: {
                    trigger: '.skills-section',
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: 1
                },
                rotation: 360,
                ease: "none"
            });
        }

        // Animate skills categories
        gsap.to(skillsCategories, {
            scrollTrigger: {
                trigger: skillsCategories,
                start: 'top 70%',
                end: 'bottom 30%',
            },
            opacity: 1,
            y: 0,
            duration: 1,
            ease: "power2.out",
            delay: 0.5
        });

        // Animate skill items
        document.querySelectorAll('.skill-item').forEach((item, index) => {
            gsap.to(item, {
                scrollTrigger: {
                    trigger: item,
                    start: 'top 80%',
                    end: 'bottom 20%',
                },
                opacity: 1,
                x: 0,
                duration: 0.6,
                ease: "power2.out",
                delay: index * 0.05
            });
        });
    }

    setupSkillHoverEffects() {
        document.querySelectorAll('.skill-category').forEach(category => {
            category.addEventListener('mouseenter', () => {
                gsap.to(category, {
                    scale: 1.05,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            category.addEventListener('mouseleave', () => {
                gsap.to(category, {
                    scale: 1,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });
    }

    animateExperience() {
        const experienceItems = document.querySelectorAll('.experience-item');
        
        experienceItems.forEach((item, index) => {
            gsap.to(item, {
                scrollTrigger: {
                    trigger: item,
                    start: 'top 80%',
                    end: 'bottom 20%',
                },
                opacity: 1,
                x: 0,
                duration: 0.8,
                ease: "power2.out",
                delay: index * 0.2
            });
        });
    }

    animateProjects() {
        const projectCards = document.querySelectorAll('.project-card');
        
        projectCards.forEach((card, index) => {
            gsap.to(card, {
                scrollTrigger: {
                    trigger: card,
                    start: 'top 80%',
                    end: 'bottom 20%',
                },
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
                delay: index * 0.1
            });
        });
    }

    animateEducation() {
        const educationItems = document.querySelectorAll('.education-item');
        
        educationItems.forEach((item, index) => {
            gsap.to(item, {
                scrollTrigger: {
                    trigger: item,
                    start: 'top 80%',
                    end: 'bottom 20%',
                },
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
                delay: index * 0.2
            });
        });
    }

    animateContact() {
        const contactItems = document.querySelectorAll('.contact-item');
        const availabilityStatus = document.querySelector('.availability-status');
        
        contactItems.forEach((item, index) => {
            gsap.to(item, {
                scrollTrigger: {
                    trigger: item,
                    start: 'top 80%',
                    end: 'bottom 20%',
                },
                opacity: 1,
                y: 0,
                duration: 0.6,
                ease: "power2.out",
                delay: index * 0.1
            });
        });

        if (availabilityStatus) {
            gsap.to(availabilityStatus, {
                scrollTrigger: {
                    trigger: availabilityStatus,
                    start: 'top 80%',
                    end: 'bottom 20%',
                },
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
                delay: 0.5
            });
        }
    }

    setupMagneticEffect() {
        document.querySelectorAll('.magnetic').forEach(element => {
            element.addEventListener('mousemove', (e) => {
                const rect = element.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                gsap.to(element, {
                    x: x * 0.1,
                    y: y * 0.1,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            element.addEventListener('mouseleave', () => {
                gsap.to(element, {
                    x: 0,
                    y: 0,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });
    }

    createFloatingElements() {
        const container = document.getElementById('floatingElements');
        if (!container) return;

        for (let i = 0; i < 50; i++) {
            const element = document.createElement('div');
            element.className = 'floating-element';
            element.style.left = Math.random() * 100 + '%';
            element.style.top = Math.random() * 100 + '%';
            element.style.animationDelay = Math.random() * 5 + 's';
            element.style.animationDuration = (Math.random() * 10 + 10) + 's';
            container.appendChild(element);
        }
    }

    createCodeRain() {
        const container = document.getElementById('codeRain');
        if (!container) return;

        const characters = '01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン';
        
        for (let i = 0; i < 100; i++) {
            const char = document.createElement('div');
            char.className = 'code-char';
            char.textContent = characters[Math.floor(Math.random() * characters.length)];
            char.style.left = Math.random() * 100 + '%';
            char.style.animationDelay = Math.random() * 10 + 's';
            char.style.animationDuration = (Math.random() * 10 + 10) + 's';
            container.appendChild(char);
        }
    }

    setupScrollProgress() {
        const progressBar = document.querySelector('.progress-bar');
        
        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset;
            const docHeight = document.body.offsetHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            
            gsap.to(progressBar, {
                width: scrollPercent + '%',
                duration: 0.1
            });
        });
    }

    setupSectionIndicators() {
        const sections = document.querySelectorAll('section');
        
        sections.forEach((section, index) => {
            ScrollTrigger.create({
                trigger: section,
                start: 'top center',
                end: 'bottom center',
                onEnter: () => {
                    console.log(`Entered section ${index + 1}`);
                },
                onLeave: () => {
                    console.log(`Left section ${index + 1}`);
                }
            });
        });
    }

    setupKeyboardNavigation() {
        const sections = document.querySelectorAll('section');
        
        document.addEventListener('keydown', (e) => {
            switch(e.key) {
                case 'ArrowDown':
                case ' ':
                    e.preventDefault();
                    const currentSection = Math.floor(window.scrollY / window.innerHeight);
                    const nextSection = Math.min(currentSection + 1, sections.length - 1);
                    gsap.to(window, {
                        scrollTo: {
                            y: sections[nextSection].offsetTop,
                            autoKill: true
                        },
                        duration: 1.5,
                        ease: "power3.inOut"
                    });
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    const currentSectionUp = Math.floor(window.scrollY / window.innerHeight);
                    const prevSection = Math.max(currentSectionUp - 1, 0);
                    gsap.to(window, {
                        scrollTo: {
                            y: sections[prevSection].offsetTop,
                            autoKill: true
                        },
                        duration: 1.5,
                        ease: "power3.inOut"
                    });
                    break;
                    
                case 'Home':
                    e.preventDefault();
                    gsap.to(window, {
                        scrollTo: {
                            y: 0,
                            autoKill: true
                        },
                        duration: 2,
                        ease: "power3.inOut"
                    });
                    break;
                    
                case 'End':
                    e.preventDefault();
                    gsap.to(window, {
                        scrollTo: {
                            y: document.body.scrollHeight,
                            autoKill: true
                        },
                        duration: 2,
                        ease: "power3.inOut"
                    });
                    break;
            }
        });
    }

    setupEasterEgg() {
        let konamiCode = [];
        const targetCode = [
            'ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown',
            'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight',
            'KeyB', 'KeyA'
        ];

        document.addEventListener('keydown', (e) => {
            konamiCode.push(e.code);
            if (konamiCode.length > targetCode.length) {
                konamiCode.shift();
            }
            
            if (JSON.stringify(konamiCode) === JSON.stringify(targetCode)) {
                this.activateEasterEgg();
                konamiCode = [];
            }
        });
    }

    activateEasterEgg() {
        // Easter egg activated - color shift effect
        gsap.to('body', {
            filter: 'hue-rotate(180deg)',
            duration: 2,
            yoyo: true,
            repeat: 1
        });
        
        // Create celebration particles
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: fixed;
                width: 10px;
                height: 10px;
                background: ${['#ff0088', '#00ff88', '#0088ff'][Math.floor(Math.random() * 3)]};
                border-radius: 50%;
                pointer-events: none;
                z-index: 9999;
                left: 50%;
                top: 50%;
            `;
            document.body.appendChild(particle);
            
            gsap.to(particle, {
                x: gsap.utils.random(-300, 300),
                y: gsap.utils.random(-300, 300),
                rotation: gsap.utils.random(0, 360),
                scale: 0,
                duration: 2,
                ease: "power2.out",
                onComplete: () => {
                    document.body.removeChild(particle);
                }
            });
        }
    }

    setupPerformanceMonitoring() {
        let fps = 0;
        let lastTime = performance.now();
        
        const monitor = () => {
            const currentTime = performance.now();
            fps = Math.round(1000 / (currentTime - lastTime));
            lastTime = currentTime;
            
            // Reduce effects if performance is poor
            if (fps < 30) {
                document.querySelectorAll('.floating-element').forEach(el => {
                    el.style.display = 'none';
                });
                
                document.querySelectorAll('.code-char').forEach(el => {
                    el.style.display = 'none';
                });
            }
            
            requestAnimationFrame(monitor);
        };
        
        monitor();
    }

    setupSmoothScrolling() {
        // Custom smooth scrolling for better control
        let isScrolling = false;
        
        document.addEventListener('wheel', (e) => {
            if (isScrolling) return;
            
            e.preventDefault();
            isScrolling = true;
            
            const delta = e.deltaY;
            const scrollSpeed = 1.2;
            
            gsap.to(window, {
                scrollTo: {
                    y: window.scrollY + (delta * scrollSpeed),
                    autoKill: true
                },
                duration: 0.8,
                ease: "power2.out",
                onComplete: () => {
                    isScrolling = false;
                }
            });
        }, { passive: false });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const portfolio = new GSAPPortfolio();
    
    // Setup additional features
    portfolio.setupEasterEgg();
    portfolio.setupPerformanceMonitoring();
    portfolio.setupSmoothScrolling();
    
    // Performance optimization
    gsap.set('.floating-element', { force3D: true });
    gsap.set('.skill-category', { force3D: true });
    gsap.set('.animated-name .char', { force3D: true });
    
    console.log('🚀 GSAP Portfolio Initialized - Performance Optimized');
});

// Handle resize for responsive behavior
window.addEventListener('resize', () => {
    ScrollTrigger.refresh();
});

// Optimize after full load
window.addEventListener('load', () => {
    console.log('✅ Portfolio fully loaded and optimized');
}); 