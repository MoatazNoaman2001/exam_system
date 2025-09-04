@extends('layouts.app')

@section('title', __('lang.welcome'))

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .main-content.student-layout {
    margin: 0 !important;
}
    </style>
    <div class="welcome-container">
       
        
        <!-- Main Content -->
        <div class="welcome-content text-center">
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
        <a href="{{ route('student.sections.index') }}" class="next-btn btn btn-primary" data-aos="fade-up" data-aos-delay="400">
            {{ __('lang.next') }}
        </a>
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
            height: 100vh;
            overflow: hidden;
            font-family: 'Tajawal', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: url('/images/welcome_in_board.png') no-repeat center center;
            background-size: cover;
            margin: 0;
            padding: 0;
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
            margin: 0;
        }

        .app-logo {
            width: 120px;
            height: auto;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.1));
        }

        /* Welcome Content */
        .welcome-content {
            width: 100%;
            z-index: 1;
            margin: 0;
        }

        .welcome-title {
            font-weight: 700;
            font-size: 2.8rem;
            line-height: 1.2;
            color: #1a1a1a;
            margin: 0 0 1.5rem;
            text-align: center;
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
            margin: 0 0 2rem;
            text-align: center;
        }

        /* Next Button */
        .next-btn {
            width: 220px;
            height: 60px;
            font-family: 'Tajawal', sans-serif;
            font-weight: 700;
            font-size: 1.2rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(47, 128, 237, 0.3);
            transition: all 0.3s ease;
            z-index: 1;
            margin: 0 auto;
        }

        .next-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(47, 128, 237, 0.4);
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
        }
    </style>
@endsection