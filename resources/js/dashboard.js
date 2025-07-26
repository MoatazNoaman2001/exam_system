// Dashboard JavaScript - Clean Architecture
'use strict';

// Dashboard Main Initialization
function initializeDashboard() {
    console.log('Dashboard initializing...');
    
    // Initialize all dashboard components
    initializeCharts();
    initializeAnimations();
    initializeInteractions();
    
    console.log('Dashboard initialized successfully');
}

// Charts initialization
function initializeCharts() {
    if (!window.dashboardData) {
        console.warn('Dashboard data not found');
        return;
    }

    const { chartData, userActivityLevels, locale } = window.dashboardData;

    // Initialize Registration Chart
    initializeRegistrationChart(chartData, locale);
    
    // Initialize Activity Chart
    initializeActivityChart(userActivityLevels, locale);
}

function initializeRegistrationChart(data, locale) {
    const ctx = document.getElementById('registrationChart');
    if (!ctx) {
        console.warn('Registration chart canvas not found');
        return;
    }

    const months = locale === 'ar' 
        ? ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر']
        : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    try {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: locale === 'ar' ? 'التسجيلات الشهرية' : 'Monthly Registrations',
                    data: data,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return months[context[0].dataIndex];
                            },
                            label: function(context) {
                                const label = locale === 'ar' ? 'المستخدمين الجدد' : 'New Users';
                                return `${label}: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        display: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12
                            },
                            beginAtZero: true
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    } catch (error) {
        console.error('Error initializing registration chart:', error);
    }
}

function initializeActivityChart(data, locale) {
    const ctx = document.getElementById('activityChart');
    if (!ctx) {
        console.warn('Activity chart canvas not found');
        return;
    }

    const labels = locale === 'ar' 
        ? ['نشاط عالي', 'نشاط متوسط', 'نشاط منخفض']
        : ['High Activity', 'Medium Activity', 'Low Activity'];

    try {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: [data.high, data.medium, data.low],
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 0,
                    hoverBorderWidth: 2,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : '0';
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    } catch (error) {
        console.error('Error initializing activity chart:', error);
    }
}

// Animation functions
function initializeAnimations() {
    // Animate statistics cards
    animateCounters();
    
    // Animate progress bars
    animateProgressBars();
    
    // Add fade-in animations to cards
    addCardAnimations();
}

function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    
    counters.forEach(counter => {
        const text = counter.textContent;
        const target = parseFloat(text.replace(/[^0-9.]/g, ''));
        
        if (isNaN(target)) return;
        
        const increment = target / 100;
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                if (text.includes('%')) {
                    counter.textContent = Math.ceil(current).toLocaleString() + '%';
                } else {
                    counter.textContent = Math.ceil(current).toLocaleString();
                }
                requestAnimationFrame(updateCounter);
            } else {
                // Set final value
                if (text.includes('%')) {
                    counter.textContent = target.toLocaleString() + '%';
                } else {
                    counter.textContent = target.toLocaleString();
                }
            }
        };
        
        // Start animation when element is in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCounter();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(counter);
    });
}

function animateProgressBars() {
    const progressBars = document.querySelectorAll('.progress-fill');
    
    progressBars.forEach(bar => {
        const finalWidth = bar.style.width || '0%';
        bar.style.width = '0%';
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        bar.style.width = finalWidth;
                    }, 500);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(bar);
    });
}

function addCardAnimations() {
    const cards = document.querySelectorAll('.stat-card, .chart-card, .activity-card, .summary-card');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Interactive features
function initializeInteractions() {
    // Add hover effects to stat cards
    addStatCardInteractions();
    
    // Add click handlers for activity items
    addActivityInteractions();
    
    // Initialize tooltips
    initializeTooltips();
}

function addStatCardInteractions() {
    const statCards = document.querySelectorAll('.stat-card');
    
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

function addActivityInteractions() {
    const activityItems = document.querySelectorAll('.activity-item');
    
    activityItems.forEach(item => {
        item.addEventListener('click', function() {
            // Add click feedback
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
}

function initializeTooltips() {
    const elements = document.querySelectorAll('[data-tooltip]');
    
    elements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

function showTooltip(event) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = event.target.getAttribute('data-tooltip');
    
    document.body.appendChild(tooltip);
    
    const rect = event.target.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
    
    setTimeout(() => tooltip.classList.add('show'), 10);
}

function hideTooltip() {
    const tooltip = document.querySelector('.tooltip');
    if (tooltip) {
        tooltip.remove();
    }
}

// Utility functions
function downloadChart(chartId) {
    try {
        const canvas = document.getElementById(chartId);
        if (!canvas) {
            console.error('Chart canvas not found:', chartId);
            return;
        }
        
        const url = canvas.toDataURL('image/png', 1.0);
        const link = document.createElement('a');
        link.download = `${chartId}_chart_${new Date().toISOString().split('T')[0]}.png`;
        link.href = url;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Show success message
        showNotification('Chart downloaded successfully!', 'success');
    } catch (error) {
        console.error('Error downloading chart:', error);
        showNotification('Failed to download chart', 'error');
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Add notification styles
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        padding: '12px 24px',
        borderRadius: '8px',
        color: 'white',
        fontWeight: '500',
        zIndex: '9999',
        opacity: '0',
        transform: 'translateY(-20px)',
        transition: 'all 0.3s ease'
    });
    
    // Set background color based on type
    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };
    notification.style.backgroundColor = colors[type] || colors.info;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 10);
    
    // Animate out and remove
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

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

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Handle responsive behavior
window.addEventListener('resize', debounce(function() {
    // Reinitialize charts on resize
    const charts = ['registrationChart', 'activityChart'];
    
    charts.forEach(chartId => {
        const chart = Chart.getChart(chartId);
        if (chart) {
            chart.resize();
        }
    });
}, 250));

// Loading states
function showLoading(target = document.body) {
    const existing = target.querySelector('.loading-overlay');
    if (existing) return;
    
    const loadingOverlay = document.createElement('div');
    loadingOverlay.className = 'loading-overlay';
    loadingOverlay.innerHTML = `
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Loading...</p>
        </div>
    `;
    
    Object.assign(loadingOverlay.style, {
        position: 'absolute',
        top: '0',
        left: '0',
        width: '100%',
        height: '100%',
        background: 'rgba(255, 255, 255, 0.9)',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        zIndex: '999'
    });
    
    target.style.position = 'relative';
    target.appendChild(loadingOverlay);
}

function hideLoading(target = document.body) {
    const loadingOverlay = target.querySelector('.loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
}

// Error handling
window.addEventListener('error', function(e) {
    console.error('Dashboard Error:', e.error);
    showNotification('An error occurred. Please refresh the page.', 'error');
});

// Performance monitoring
if ('performance' in window) {
    window.addEventListener('load', function() {
        setTimeout(function() {
            try {
                const perfData = performance.getEntriesByType('navigation')[0];
                const loadTime = perfData.loadEventEnd - perfData.loadEventStart;
                console.log('Dashboard Load Time:', loadTime + 'ms');
                
                // Report slow loading
                if (loadTime > 3000) {
                    console.warn('Dashboard loaded slowly:', loadTime + 'ms');
                }
            } catch (error) {
                console.warn('Performance monitoring failed:', error);
            }
        }, 0);
    });
}

// Export functions for global access
window.dashboardUtils = {
    downloadChart,
    showNotification,
    showLoading,
    hideLoading,
    debounce,
    throttle
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeDashboard);
} else {
    initializeDashboard();
}