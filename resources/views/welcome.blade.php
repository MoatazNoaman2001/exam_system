<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMP Master - Your Complete PMP Exam Preparation System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/root-welcome.css') }}">
</head>
<body>
    <!-- Header -->
    <header id="header">
        <div class="container">
            <nav>
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                    <span>PMP Master</span>
                </div>
                <ul class="nav-links">
                    <li><a href="#features">Features</a></li>
                    <li><a href="#study-plan">Study Plan</a></li>
                    <li><a href="#practice-exams">Practice Exams</a></li>
                    <li><a href="#progress-tracking">Progress</a></li>
                    <li><a href="#testimonials">Testimonials</a></li>
                </ul>
                <form action="{{route('login')}}" method="GET">
                    @csrf
                    <button class="cta-button" type="submit" >Get Started</button>
                </form>
                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Master the PMP Exam with Confidence</h1>
                    <p>Our comprehensive platform combines expert lectures, realistic practice exams, and personalized progress tracking to help you pass the PMP exam on your first attempt.</p>
                
                    <form action="{{route('login')}}" method="GET">
                        @csrf
                        <button class="cta-button" type="submit" >click to begin our joureny</button>
                    </form>
                </div>
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="PMP Exam Dashboard" class="hero-img hero-img-1">
                    <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="PMP Study Materials" class="hero-img hero-img-2">
                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="PMP Progress Tracking" class="hero-img hero-img-3">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Everything You Need to Succeed</h2>
                <p>Our platform provides all the tools and resources to prepare effectively for the PMP certification exam.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>Comprehensive Lectures</h3>
                    <p>Access 50+ hours of video lectures covering all PMBOK knowledge areas and process groups, taught by PMP-certified instructors.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h3>Realistic Practice Exams</h3>
                    <p>Test your knowledge with full-length, timed practice exams that mimic the actual PMP exam format and difficulty level.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Smart Progress Tracking</h3>
                    <p>Our system analyzes your performance and adapts your study plan to focus on areas that need improvement.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Study Plan Section -->
    <section class="study-plan" id="study-plan">
        <div class="container">
            <div class="section-title">
                <h2>Personalized Study Plan</h2>
                <p>Tell us your exam date and we'll create a customized study schedule that fits your timeline.</p>
            </div>
            <div class="plan-container">
                <div class="plan-image">
                    <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Study Plan" class="plan-img plan-img-main">
                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Study Plan Background" class="plan-img plan-img-bg">
                </div>
                <div class="plan-content">
                    <h2>Study Smarter, Not Harder</h2>
                    <p>Our adaptive learning system creates a personalized study plan based on your available study time, learning pace, and progress. Focus on what matters most for your exam success.</p>
                    <div class="plan-steps">
                        <div class="plan-step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4>Set Your Exam Date</h4>
                                <p>Tell us when you plan to take the exam and how many hours per week you can study.</p>
                            </div>
                        </div>
                        <div class="plan-step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4>Take Initial Assessment</h4>
                                <p>Complete our diagnostic test to identify your strengths and weaknesses.</p>
                            </div>
                        </div>
                        <div class="plan-step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h4>Follow Your Plan</h4>
                                <p>Receive daily study tasks tailored to your needs and timeline.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Practice Exams Section -->
    <section class="practice-exams" id="practice-exams">
        <div class="container">
            <div class="section-title">
                <h2>Realistic Practice Exams</h2>
                <p>Simulate the actual exam experience with our comprehensive question bank.</p>
            </div>
            <div class="exams-grid">
                <div class="exam-card">
                    <div class="exam-image">
                        <img src="https://images.unsplash.com/photo-1551269901-5c5e14c25df7?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Full-Length Exam">
                        <div class="exam-tag">200 Questions</div>
                    </div>
                    <div class="exam-content">
                        <h3>Full-Length Mock Exam</h3>
                        <p>Complete 4-hour simulation with questions covering all knowledge areas and process groups.</p>
                        <div class="exam-meta">
                            <div class="exam-questions">
                                <i class="fas fa-question-circle"></i>
                                <span>200 Questions</span>
                            </div>
                            <button class="cta-button">Start Exam</button>
                        </div>
                    </div>
                </div>
                <div class="exam-card">
                    <div class="exam-image">
                        <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Knowledge Area Exam">
                        <div class="exam-tag">50 Questions</div>
                    </div>
                    <div class="exam-content">
                        <h3>Knowledge Area Tests</h3>
                        <p>Focus on specific PMBOK knowledge areas to strengthen your weak points.</p>
                        <div class="exam-meta">
                            <div class="exam-questions">
                                <i class="fas fa-question-circle"></i>
                                <span>50 Questions Each</span>
                            </div>
                            <button class="cta-button">Start Exam</button>
                        </div>
                    </div>
                </div>
                <div class="exam-card">
                    <div class="exam-image">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Quick Quiz">
                        <div class="exam-tag">20 Questions</div>
                    </div>
                    <div class="exam-content">
                        <h3>Daily Quick Quizzes</h3>
                        <p>Short 20-question quizzes to reinforce concepts and keep your knowledge fresh.</p>
                        <div class="exam-meta">
                            <div class="exam-questions">
                                <i class="fas fa-question-circle"></i>
                                <span>20 Questions</span>
                            </div>
                            <button class="cta-button">Start Quiz</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Progress Tracking Section -->
    <section class="progress-tracking" id="progress-tracking">
        <div class="container">
            <div class="section-title">
                <h2>Track Your Progress</h2>
                <p>Visualize your improvement and stay motivated with our comprehensive analytics.</p>
            </div>
            <div class="progress-container">
                <div class="progress-content">
                    <h2>Data-Driven Preparation</h2>
                    <p>Our advanced analytics track your performance across all knowledge areas, question types, and time management to give you a clear picture of your readiness for the actual exam.</p>
                    <div class="progress-stats">
                        <div class="stat-item">
                            <div class="stat-header">
                                <span class="stat-title">Overall Readiness</span>
                                <span class="stat-percent">78%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 78%"></div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-header">
                                <span class="stat-title">Knowledge Areas</span>
                                <span class="stat-percent">85%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-header">
                                <span class="stat-title">Process Groups</span>
                                <span class="stat-percent">72%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 72%"></div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-header">
                                <span class="stat-title">Time Management</span>
                                <span class="stat-percent">91%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 91%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="progress-image">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Progress Dashboard" class="progress-img progress-img-main">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Progress Background" class="progress-img progress-img-bg">
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Success Stories</h2>
                <p>Hear from PMP candidates who achieved certification with our platform.</p>
            </div>
            <div class="testimonials-slider">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        "PMP Master's practice exams were incredibly similar to the actual PMP exam. The detailed explanations for each question helped me understand not just what the right answer was, but why it was correct. I passed on my first attempt!"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah Johnson">
                        </div>
                        <div class="author-info">
                            <h4>Sarah Johnson</h4>
                            <p>Newly Certified PMP</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="cta">
        <div class="container">
            <h2>Ready to Start Your PMP Journey?</h2>
            <p>Join thousands of successful PMP candidates and take the first step toward your certification today.</p>
          
            <form action="{{route('login')}}" method="GET">
                @csrf
                <button class="cta-button white" type="submit" >Lets Get Started</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>PMP Master</h3>
                    <p>The most comprehensive PMP exam preparation platform with personalized study plans, realistic practice exams, and progress tracking.</p>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Resources</h3>
                    <ul class="footer-links">
                        <li><a href="#">PMP Exam Guide</a></li>
                        <li><a href="#">Study Tips</a></li>
                        <li><a href="#">PMBOK Summary</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Features</h3>
                    <ul class="footer-links">
                        <li><a href="#">Video Lectures</a></li>
                        <li><a href="#">Practice Exams</a></li>
                        <li><a href="#">Study Planner</a></li>
                        <li><a href="#">Progress Tracking</a></li>
                        <li><a href="#">Mobile App</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Company</h3>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Instructors</a></li>
                        <li><a href="#">Success Stories</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 PMP Master. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        // Mobile Menu Toggle
        $('.mobile-menu').click(function() {
            $('.nav-links').toggleClass('active');
        });
        
        // Smooth Scrolling
        $('a[href*="#"]').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate(
                {
                    scrollTop: $($(this).attr('href')).offset().top - 80,
                },
                500,
                'linear'
            );
        });
        
        // Header Scroll Effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('header').addClass('scrolled');
            } else {
                $('header').removeClass('scrolled');
            }
        });
        
        // Testimonials Slider
        $('.testimonials-slider').slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true,
            autoplay: true,
            autoplaySpeed: 5000,
            arrows: false
        });
        
        // Animate Progress Bars on Scroll
        $(window).scroll(function() {
            $('.progress-fill').each(function() {
                var position = $(this).parent().offset().top;
                var scroll = $(window).scrollTop();
                var windowHeight = $(window).height();
                
                if (scroll > position - windowHeight + 100) {
                    $(this).css('width', $(this).attr('style'));
                }
            });
        });
        
        // Initialize all progress bars to 0
        $(document).ready(function() {
            $('.progress-fill').css('width', '0');
        });
    </script>
</body>
</html>
