/**
 * Main Application JavaScript
 * Handles sidebar functionality, language dropdowns, and notifications
 */

class SidebarManager {
    constructor() {
        this.init();
    }

    init() {
        this.elements = this.getElements();
        this.config = this.getConfig();
        this.setupEventListeners();
        this.initializeSidebars();
        this.setupLanguageDropdowns();
        this.setupNotifications();
        this.addPageTransition();
    }

    getElements() {
        return {
            sidebar: document.getElementById('sidebar'),
            studentSidebar: document.getElementById('studentSidebar'),
            sidebarToggle: document.getElementById('sidebarToggle'),
            studentSidebarToggle: document.getElementById('studentSidebarToggle'),
            sidebarToggleBtn: document.getElementById('studentSidebarToggleBtn'),
            overlay: document.getElementById('overlay'),
            studentOverlay: document.getElementById('studentOverlay'),
            mainContent: document.getElementById('mainContent'),
            
            // Language dropdown elements
            languageToggle: document.getElementById('languageToggle'),
            languageDropdown: document.getElementById('languageDropdown'),
            studentLanguageToggle: document.getElementById('studentLanguageToggle'),
            studentLanguageDropdown: document.getElementById('studentLanguageDropdown'),
            mobileLanguageSwitcher: document.getElementById('mobileLanguageSwitcher'),
            mobileLanguageDropdown: document.getElementById('mobileLanguageDropdown'),
            
            // Notification elements
            sidebarNotificationBadge: document.getElementById('sidebarNotificationBadge'),
            mobileNotificationBadge: document.getElementById('mobileNotificationBadge')
        };
    }

    getConfig() {
        return {
            isAdmin: window.appConfig?.isAdmin || false,
            isStudent: window.appConfig?.isStudent || false,
            isRTL: window.appConfig?.isRTL || false,
            routes: window.appConfig?.routes || {}
        };
    }

    setupEventListeners() {
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                if (this.config.isStudent) {
                    this.toggleStudentSidebar();
                } else if (this.config.isAdmin && window.innerWidth < 992) {
                    this.toggleAdminSidebar();
                }
            }
        });

        // Window resize handler
        window.addEventListener('resize', () => {
            this.handleWindowResize();
        });

        // PopState handler for admin AJAX navigation
        window.addEventListener('popstate', () => {
            if (this.config.isAdmin) {
                window.location.reload();
            }
        });
    }

    // Admin Sidebar Methods
    initializeAdminSidebar() {
        if (!this.config.isAdmin || !this.elements.sidebar || !this.elements.overlay) return;

        if (this.elements.sidebarToggle) {
            this.elements.sidebarToggle.addEventListener('click', () => this.toggleAdminSidebar());
        }
        
        this.elements.overlay.addEventListener('click', () => this.toggleAdminSidebar());

        if (window.innerWidth >= 992) {
            this.elements.sidebar.classList.add('show');
        }

        this.setupAdminNavigation();
    }

    toggleAdminSidebar() {
        if (window.innerWidth < 992) {
            this.elements.sidebar.classList.toggle('show');
            this.elements.overlay.classList.toggle('show');
        }
    }

    setupAdminNavigation() {
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', (e) => {
                if (link.target || (link.href.startsWith('http') && !link.href.includes(window.location.host))) {
                    return;
                }
                
                e.preventDefault();
                this.handleAdminNavigation(link);
            });
        });
    }

    handleAdminNavigation(link) {
        const url = link.href;
        
        if (window.innerWidth < 992) {
            this.elements.sidebar.classList.remove('show');
            this.elements.overlay.classList.remove('show');
        }

        const icon = link.querySelector('i');
        const originalIcon = icon.className;
        icon.className = 'fas fa-spinner fa-spin';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('.content-container')?.innerHTML;
            
            if (newContent) {
                document.querySelector('.content-container').innerHTML = newContent;
                window.history.pushState({}, '', url);
                
                document.querySelectorAll('.sidebar-link').forEach(item => {
                    item.classList.remove('active');
                });
                link.classList.add('active');
                
                const title = doc.querySelector('title');
                if (title) {
                    document.title = title.textContent;
                }
                
                this.addPageTransition();
            } else {
                window.location.href = url;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.location.href = url;
        })
        .finally(() => {
            icon.className = originalIcon;
        });
    }

    // Student Sidebar Methods
    initializeStudentSidebar() {
        if (!this.config.isStudent || !this.elements.studentSidebar || !this.elements.studentOverlay) return;

        this.sidebarExpanded = localStorage.getItem('studentSidebarExpanded') === 'true';

        if (this.elements.studentSidebarToggle) {
            this.elements.studentSidebarToggle.addEventListener('click', () => this.toggleStudentSidebar());
        }

        if (this.elements.sidebarToggleBtn) {
            this.elements.sidebarToggleBtn.addEventListener('click', () => this.toggleStudentSidebar());
        }

        if (this.elements.studentOverlay) {
            this.elements.studentOverlay.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    this.elements.studentSidebar.classList.remove('show');
                    this.elements.studentOverlay.classList.remove('show');
                }
            });
        }

        this.initializeStudentSidebarState();
        this.setupStudentNavigation();
        this.setupTooltips();
    }

    initializeStudentSidebarState() {
        if (window.innerWidth >= 992) {
            this.elements.studentSidebar.classList.add('show');
            if (this.sidebarExpanded) {
                this.elements.studentSidebar.classList.add('expanded');
                this.elements.mainContent.classList.add('sidebar-expanded');
            }
            this.updateToggleIcon(this.sidebarExpanded);
        } else {
            this.elements.studentSidebar.classList.remove('expanded');
            this.elements.mainContent.classList.remove('sidebar-expanded');
        }
    }

    toggleStudentSidebar() {
        if (window.innerWidth < 992) {
            // On mobile, keep bottom navigation visible
            return;
        }

        this.sidebarExpanded = !this.sidebarExpanded;
        this.elements.studentSidebar.classList.toggle('expanded', this.sidebarExpanded);
        this.elements.mainContent.classList.toggle('sidebar-expanded', this.sidebarExpanded);
        this.updateToggleIcon(this.sidebarExpanded);
        localStorage.setItem('studentSidebarExpanded', this.sidebarExpanded);
    }

    updateToggleIcon(expanded) {
        const icon = this.elements.sidebarToggleBtn?.querySelector('i');
        if (icon) {
            icon.className = expanded ? 'fas fa-times' : 'fas fa-bars';
            if (this.config.isRTL) {
                this.elements.sidebarToggleBtn.style.right = expanded ? '260px' : '54px';
            } else {
                this.elements.sidebarToggleBtn.style.left = expanded ? '260px' : '54px';
            }
        }
    }

    setupStudentNavigation() {
        document.querySelectorAll('.student-sidebar .sidebar-link').forEach(link => {
            link.addEventListener('click', (e) => {
                if (link.href.includes('#') || link.target) return;

                e.preventDefault();
                const url = link.href;

                const icon = link.querySelector('i');
                const originalIcon = icon.className;
                icon.className = 'fas fa-spinner fa-spin';
                link.style.opacity = '0.7';

                document.querySelectorAll('.student-sidebar .sidebar-link').forEach(item => {
                    item.classList.remove('active');
                });
                link.classList.add('active');

                setTimeout(() => {
                    window.location.href = url;
                }, 300);
            });
        });
    }

    setupTooltips() {
        if (!this.config.isStudent) return;

        const updateTooltips = () => {
            document.querySelectorAll('.student-sidebar .sidebar-link').forEach(link => {
                const text = link.querySelector('.link-text')?.textContent;
                if (text && !this.elements.studentSidebar.classList.contains('expanded')) {
                    link.setAttribute('title', text);
                } else {
                    link.removeAttribute('title');
                }
            });
        };

        const sidebarObserver = new MutationObserver(updateTooltips);
        if (this.elements.studentSidebar) {
            sidebarObserver.observe(this.elements.studentSidebar, {
                attributes: true,
                attributeFilter: ['class']
            });
            updateTooltips();
        }
    }

    // Language Dropdown Methods
    setupLanguageDropdowns() {
        if (this.config.isAdmin) {
            this.setupLanguageDropdown(this.elements.languageToggle, this.elements.languageDropdown);
        }
        
        if (this.config.isStudent) {
            this.setupLanguageDropdown(this.elements.studentLanguageToggle, this.elements.studentLanguageDropdown);
            this.setupMobileLanguageDropdown();
        }
    }

    setupLanguageDropdown(toggle, dropdown) {
        if (!toggle || !dropdown) return;
        
        let isDropdownOpen = false;
        let hoverTimeout;
        
        // Click handler
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            isDropdownOpen = !isDropdownOpen;
            this.setDropdownState(toggle, dropdown, isDropdownOpen);
        });
        
        // Hover handlers for better UX
        toggle.addEventListener('mouseenter', () => {
            clearTimeout(hoverTimeout);
            if (!isDropdownOpen) {
                isDropdownOpen = true;
                this.setDropdownState(toggle, dropdown, true);
            }
        });
        
        toggle.addEventListener('mouseleave', () => {
            hoverTimeout = setTimeout(() => {
                if (isDropdownOpen && !dropdown.matches(':hover')) {
                    isDropdownOpen = false;
                    this.setDropdownState(toggle, dropdown, false);
                }
            }, 300);
        });
        
        // Keep dropdown open when hovering over it
        dropdown.addEventListener('mouseenter', () => {
            clearTimeout(hoverTimeout);
        });
        
        dropdown.addEventListener('mouseleave', () => {
            hoverTimeout = setTimeout(() => {
                isDropdownOpen = false;
                this.setDropdownState(toggle, dropdown, false);
            }, 300);
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                isDropdownOpen = false;
                this.setDropdownState(toggle, dropdown, false);
            }
        });
        
        // Handle dropdown item clicks
        dropdown.addEventListener('click', (e) => {
            if (e.target.classList.contains('dropdown-item') || e.target.classList.contains('mobile-dropdown-item')) {
                isDropdownOpen = false;
                this.setDropdownState(toggle, dropdown, false);
            }
        });
    }

    setDropdownState(toggle, dropdown, isVisible) {
        toggle.classList.toggle('active', isVisible);
        dropdown.classList.toggle('show', isVisible);
        
        // Force styles for immediate feedback
        dropdown.style.opacity = isVisible ? '1' : '0';
        dropdown.style.visibility = isVisible ? 'visible' : 'hidden';
        dropdown.style.pointerEvents = isVisible ? 'auto' : 'none';
        
        const translateX = this.config.isRTL ? 
            (isVisible ? 'translateX(0)' : 'translateX(10px)') :
            (isVisible ? 'translateX(0)' : 'translateX(-10px)');
        
        dropdown.style.transform = translateX;
    }

    setupMobileLanguageDropdown() {
        if (!this.elements.mobileLanguageSwitcher || !this.elements.mobileLanguageDropdown) return;
        
        let isMobileDropdownOpen = false;
        
        this.elements.mobileLanguageSwitcher.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            isMobileDropdownOpen = !isMobileDropdownOpen;
            this.elements.mobileLanguageDropdown.classList.toggle('show', isMobileDropdownOpen);
            
            // Force styles for mobile
            this.elements.mobileLanguageDropdown.style.opacity = isMobileDropdownOpen ? '1' : '0';
            this.elements.mobileLanguageDropdown.style.visibility = isMobileDropdownOpen ? 'visible' : 'hidden';
            this.elements.mobileLanguageDropdown.style.transform = isMobileDropdownOpen ? 
                'translateX(-50%) translateY(0)' : 'translateX(-50%) translateY(10px)';
        });
        
        // Close mobile dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.elements.mobileLanguageSwitcher.contains(e.target) && 
                !this.elements.mobileLanguageDropdown.contains(e.target)) {
                isMobileDropdownOpen = false;
                this.elements.mobileLanguageDropdown.classList.remove('show');
                this.elements.mobileLanguageDropdown.style.opacity = '0';
                this.elements.mobileLanguageDropdown.style.visibility = 'hidden';
                this.elements.mobileLanguageDropdown.style.transform = 'translateX(-50%) translateY(10px)';
            }
        });
        
        // Handle mobile dropdown item clicks
        this.elements.mobileLanguageDropdown.addEventListener('click', (e) => {
            if (e.target.classList.contains('mobile-dropdown-item')) {
                isMobileDropdownOpen = false;
                this.elements.mobileLanguageDropdown.classList.remove('show');
                this.elements.mobileLanguageDropdown.style.opacity = '0';
                this.elements.mobileLanguageDropdown.style.visibility = 'hidden';
                this.elements.mobileLanguageDropdown.style.transform = 'translateX(-50%) translateY(10px)';
            }
        });
    }

    // Remove the old setDropdownVisibility method and replace with setDropdownState

    // Notification Methods
    setupNotifications() {
        if (!this.config.isStudent) return;

        this.updateNotificationBadge();
        setInterval(() => this.updateNotificationBadge(), 5000);
        this.setupNotificationClickHandlers();
    }

    updateNotificationBadge() {
        if (!this.config.routes.notificationUnreadCount) return;

        fetch(this.config.routes.notificationUnreadCount, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            const count = data.unread_count || 0;
            
            if (this.elements.sidebarNotificationBadge && this.elements.mobileNotificationBadge) {
                this.elements.sidebarNotificationBadge.textContent = count;
                this.elements.mobileNotificationBadge.textContent = count;
                
                if (count > 0) {
                    this.elements.sidebarNotificationBadge.classList.add('show');
                    this.elements.mobileNotificationBadge.classList.add('show');
                } else {
                    this.elements.sidebarNotificationBadge.classList.remove('show');
                    this.elements.mobileNotificationBadge.classList.remove('show');
                }
            }
        })
        .catch(error => console.error('Error fetching notification count:', error));
    }

    setupNotificationClickHandlers() {
        if (!this.config.routes.notificationMarkAsRead || !this.config.routes.studentNotifications) return;

        const notificationLinks = document.querySelectorAll(`
            .student-sidebar .sidebar-link[href="${this.config.routes.studentNotifications}"],
            .mobile-bottom-nav .mobile-nav-icon[href="${this.config.routes.studentNotifications}"]
        `);
        
        notificationLinks.forEach(link => {
            link.addEventListener('click', () => {
                fetch(this.config.routes.notificationMarkAsRead, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        this.updateNotificationBadge();
                    }
                })
                .catch(error => console.error('Error marking notifications as read:', error));
            });
        });
    }

    // Utility Methods
    initializeSidebars() {
        if (this.config.isAdmin) {
            this.initializeAdminSidebar();
        }
        
        if (this.config.isStudent) {
            this.initializeStudentSidebar();
        }
    }

    handleWindowResize() {
        if (this.config.isAdmin && this.elements.sidebar && this.elements.overlay) {
            if (window.innerWidth >= 992) {
                this.elements.sidebar.classList.add('show');
                this.elements.overlay.classList.remove('show');
            } else {
                this.elements.sidebar.classList.remove('show');
                this.elements.overlay.classList.remove('show');
            }
        }

        if (this.config.isStudent && this.elements.studentSidebar && this.elements.studentOverlay) {
            if (window.innerWidth >= 992) {
                this.elements.studentSidebar.classList.add('show');
                if (this.sidebarExpanded) {
                    this.elements.studentSidebar.classList.add('expanded');
                    this.elements.mainContent.classList.add('sidebar-expanded');
                } else {
                    this.elements.studentSidebar.classList.remove('expanded');
                    this.elements.mainContent.classList.remove('sidebar-expanded');
                }
                this.updateToggleIcon(this.sidebarExpanded);
                this.elements.studentOverlay.classList.remove('show');
            } else {
                this.elements.studentSidebar.classList.remove('expanded');
                this.elements.mainContent.classList.remove('sidebar-expanded');
                this.elements.studentOverlay.classList.remove('show');
            }
        }
    }

    addPageTransition() {
        if (this.elements.mainContent) {
            this.elements.mainContent.style.opacity = '0';
            this.elements.mainContent.style.transform = 'translateY(10px)';
            setTimeout(() => {
                this.elements.mainContent.style.transition = 'all 0.3s ease';
                this.elements.mainContent.style.opacity = '1';
                this.elements.mainContent.style.transform = 'translateY(0)';
            }, 100);
        }
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new SidebarManager();
}); 