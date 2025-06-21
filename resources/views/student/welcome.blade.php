@extends('layouts.app')

@section('title', __('lang.welcome'))

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <div class="welcome-container">
        <!-- Background Gradient -->
        <div class="background-gradient"></div>
        
        <!-- Skip Button -->
        <a href="{{ route('splash') }}" class="skip-btn">
            <img src="/images/arrow-left.png" alt="{{ __('lang.skip') }}" class="arrow-icon" />
            <span>{{ __('lang.skip') }}</span>
        </a>
        
        <!-- Main Content -->
        <div class="welcome-content">
            <div class="logo-container">
                <img src="/images/logo.png" alt="{{ config('app.name', 'PMP App') }}" class="app-logo" />
            </div>
            <h1 class="welcome-title" data-aos="fade-up">
                {{ __('lang.welcome_to') }}<br>
                <span class="app-name">{{ config('app.name', 'PMP App') }}</span>
            </h1>
            <p class="welcome-subtitle" data-aos="fade-up" data-aos-delay="200">
                {{ __('lang.your_smart_companion') }}<br>
                {{ __('lang.professional_journey') }}
            </p>
        </div>
        
        <!-- Next Button -->
        <a href="{{ route('student.intro.index') }}" class="next-btn" data-aos="fade-up" data-aos-delay="400">
            {{ __('lang.next') }}
        </a>
        
        <!-- Decorative Graphic -->
        <div class="decorative-graphic" data-aos="fade-left">
            <img src="/images/Frame 9.png" alt="{{ __('lang.decorative_element') }}" />
        </div>
    </div>

    <!-- AOS Animation Library -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-out-cubic'
        });
    </script>

    <style>
        .welcome-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            font-family: 'Tajawal', sans-serif;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #f5f7fa;
        }

        .background-gradient {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(47, 128, 237, 0.1) 0%, rgba(255, 255, 255, 0.8) 100%);
            z-index: -1;
        }

        /* Skip Button */
        .skip-btn {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            font-size: 1.1rem;
            color: #333;
            text-decoration: none;
            z-index: 10;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .skip-btn:hover {
            color: #2F80ED;
            background: rgba(47, 128, 237, 0.1);
        }

        .arrow-icon {
            width: 1.1rem;
            height: auto;
            transform: rotate(180deg);
        }

        [dir="rtl"] .arrow-icon {
            transform: rotate(0deg);
        }

        /* Logo */
        .logo-container {
            margin-bottom: 2rem;
        }

        .app-logo {
            width: 120px;
            height: auto;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.1));
        }

        /* Welcome Content */
        .welcome-content {
            width: 90%;
            max-width: 600px;
            z-index: 1;
        }

        .welcome-title {
            font-weight: 700;
            font-size: 2.8rem;
            line-height: 1.2;
            color: #1a1a1a;
            margin-bottom: 1.5rem;
        }

        .app-name {
            color: #2F80ED;
            font-size: 3.2rem;
            font-weight: 800;
        }

        .welcome-subtitle {
            font-weight: 400;
            font-size: 1.3rem;
            line-height: 1.6;
            color: #4a4a4a;
            margin-bottom: 2rem;
        }

        /* Next Button */
        .next-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 220px;
            height: 60px;
            background: #2F80ED;
            color: white;
            border: none;
            border-radius: 12px;
            font-family: 'Tajawal', sans-serif;
            font-weight: 700;
            font-size: 1.2rem;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(47, 128, 237, 0.3);
            transition: all 0.3s ease;
            z-index: 1;
        }

        .next-btn:hover {
            background: #256fcf;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(47, 128, 237, 0.4);
        }

        /* Decorative Graphic */
        .decorative-graphic {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 30%;
            max-width: 400px;
            z-index: 0;
            opacity: 0.8;
        }

        [dir="rtl"] .decorative-graphic {
            right: auto;
            left: 0;
            transform: scaleX(-1);
        }

        .decorative-graphic img {
            width: 100%;
            height: auto;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2.2rem;
            }

            .app-name {
                font-size: 2.6rem;
            }

            .welcome-subtitle {
                font-size: 1.1rem;
            }

            .next-btn {
                width: 180px;
                height: 50px;
                font-size: 1.1rem;
            }

            .skip-btn {
                font-size: 1rem;
                padding: 0.4rem 0.8rem;
            }

            .app-logo {
                width: 100px;
            }

            .decorative-graphic {
                width: 40%;
            }
        }

        @media (max-width: 480px) {
            .welcome-title {
                font-size: 1.8rem;
            }

            .app-name {
                font-size: 2.2rem;
            }

            .welcome-subtitle {
                font-size: 1rem;
            }

            .next-btn {
                width: 160px;
                height: 45px;
                font-size: 1rem;
            }

            .skip-btn {
                top: 1rem;
                left: 1rem;
                font-size: 0.9rem;
            }

            .app-logo {
                width: 80px;
            }

            .decorative-graphic {
                width: 50%;
            }
        }
    </style>
@endsection