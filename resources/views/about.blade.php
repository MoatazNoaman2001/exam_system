<div>
    <!-- Simplicity is an acquired taste. - Katharine Gerould -->
</div>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About PMP Master - Your Complete PMP Exam Preparation System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/root-welcome.css') }}">
</head>
<body>
    @guest
            <!-- Header -->
    <header id="header">
        <div class="container">
            <nav>
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                    <span>PMP Master</span>
                </div>
                <ul class="nav-links">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/about') }}" class="active">About</a></li>
                    <li><a href="{{ url('/contact') }}">Contact</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#testimonials">Testimonials</a></li>
                </ul>
                <form action="{{route('login')}}" method="GET">
                    @csrf
                    <button class="cta-button" type="submit">Get Started</button>
                </form>
                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>
    @endguest


    <!-- About Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>About PMP Master</h1>
                    <p class="text-white">Empowering project managers worldwide to achieve PMP certification with confidence and ease.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="our-story">
        <div class="container">
            <div class="section-title">
                <h2>Our Story</h2>
                <p>How PMP Master became the leading PMP exam preparation platform</p>
            </div>
            <div class="story-timeline">
                <div class="timeline-item">
                    <div class="timeline-year">Mar 25</div>
                    <div class="timeline-content">
                        <h3>Founded by PMP Professionals</h3>
                        <p>PMP Master was created by a team of certified project managers who recognized the challenges candidates face when preparing for the PMP exam. We set out to build a comprehensive solution that addresses all aspects of exam preparation.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">July 25</div>
                    <div class="timeline-content">
                        <h3>First Version Launched</h3>
                        <p>Our initial platform featured 1000+ practice questions and 30 hours of video lectures. Within months, we helped our first 500 users achieve PMP certification.</p>
                    </div>
                </div>
                {{-- <div class="timeline-item">
                    <div class="timeline-year">2021</div>
                    <div class="timeline-content">
                        <h3>Adaptive Learning Introduced</h3>
                        <p>We revolutionized PMP prep with our smart adaptive learning system that customizes study plans based on each user's strengths and weaknesses.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2023</div>
                    <div class="timeline-content">
                        <h3>10,000+ Certified Users</h3>
                        <p>PMP Master has now helped over 10,000 professionals from 85+ countries achieve their PMP certification, with a first-time pass rate of 92%.</p>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="section-title">
                <h2>Meet Our Team</h2>
                <p>The experts behind PMP Master's success</p>
            </div>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-photo">
                        <img src="https://avatars.githubusercontent.com/u/99621213?v=4" alt="David Chen">
                    </div>
                    <h3>Moataz Noaman</h3>
                    <p class="member-title">Software Engineer</p>
                    <p class="member-bio">PMP, Engineer Resposible for Design and Development of the Platform.</p>
                    <div class="member-social">
                        <a href="https://www.linkedin.com/in/moataz-noaman/"><i class="fab fa-linkedin-in"></i></a>
                        
                    </div>
                </div>
                <div class="team-member">
                    <div class="member-photo">
                        <img src="https://avatars.githubusercontent.com/u/93217206?v=4" alt="Sarah Johnson">
                    </div>
                    <h3>Hager Abd Alaziz</h3>
                    <p class="member-title">Software Engineer </p>
                    <p class="member-bio">PMP, Engineer Resposible for Design and Development of the Platform.</p>
                    <div class="member-social">
                        <a href="https://www.linkedin.com/in/hager-hussien/"><i class="fab fa-linkedin-in"></i></a>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="container">
            <div class="mission-content">
                <div class="mission-text">
                    <h2>Our Mission</h2>
                    <p>To democratize access to high-quality PMP exam preparation by providing an affordable, comprehensive, and effective learning platform that adapts to each individual's needs.</p>
                    <p>We believe that certification should be accessible to all project management professionals, regardless of their background or budget.</p>
                </div>
                <div class="mission-image">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Team working together">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">10,000+</div>
                    <div class="stat-label">PMPs Certified</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">92%</div>
                    <div class="stat-label">First-Time Pass Rate</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">2,500+</div>
                    <div class="stat-label">Practice Questions</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Hours of Video</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="cta">
        <div class="container">
            <h2>Join Our Community of Successful PMPs</h2>
            <p>Start your journey to PMP certification with the most trusted preparation platform.</p>
            <form action="{{route('login')}}" method="GET">
                @csrf
                <button class="cta-button white" type="submit">Get Started Today</button>
            </form>
        </div>
    </section>

    @guest
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
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="#">Our Instructors</a></li>
                        <li><a href="#">Success Stories</a></li>
                        <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 PMP Master. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
            </div>
        </div>
    </footer>
    @endguest

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
        
        // Animate Stats on Scroll
        $(window).scroll(function() {
            var position = $('.stats-section').offset().top;
            var scroll = $(window).scrollTop();
            var windowHeight = $(window).height();
            
            if (scroll > position - windowHeight + 100) {
                $('.stat-number').each(function() {
                    var $this = $(this);
                    var target = $this.text();
                    $this.text('0');
                    $({countNum: 0}).animate({countNum: target.replace('+', '')}, {
                        duration: 2000,
                        easing: 'swing',
                        step: function() {
                            $this.text(Math.floor(this.countNum) + (target.includes('+') ? '+' : ''));
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>