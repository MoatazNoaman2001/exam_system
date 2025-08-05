@extends('layouts.app')

@section('title', __('faq.title'))

@section('content')
    <style>
        :root {
            --primary-color: #4a6bff;
            --secondary-color: #ff7e5f;
            --accent-color: #2ecc71;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --baby-blue: #597ab6ff;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background-color: #f5f7ff;
            color: var(--dark-color);
            line-height: 1.6;
        }
        
        .rewards-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            position: relative;
        }
        
        .rewards-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .rewards-header h1 {
            font-size: 2.5rem;
            color: var(--dark-color);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }
        
        .rewards-header h1::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            bottom: -10px;
            left: 25%;
            border-radius: 2px;
        }
        
        .rewards-header p {
            font-size: 1.2rem;
            color: #666;
        }
        
        .rewards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .reward-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            border-top: 5px solid var(--primary-color);
        }
        
        .reward-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }
        
        .reward-points {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            width: fit-content;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .reward-icon {
            margin-left: 0.5rem;
            font-size: 1.2rem;
        }
        
        .reward-title {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: var(--dark-color);
            font-weight: 700;
        }
        
        .reward-desc {
            color: #666;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }
        
        .progress-container {
            background-color: #e0e0e0;
            border-radius: 10px;
            height: 10px;
            margin-top: auto;
        }
        
        .reward-card:nth-child(2) {
            border-top-color: var(--accent-color);
        }
        
        .reward-card:nth-child(2) .reward-points {
            background: linear-gradient(135deg, var(--accent-color), #27ae60);
        }
        
        .reward-card:nth-child(3) {
            border-top-color: #f39c12;
        }
        
        .reward-card:nth-child(3) .reward-points {
            background: linear-gradient(135deg, #f39c12, #e67e22);
        }
        
        .reward-card:nth-child(4) {
            border-top-color: #9b59b6;
        }
        
        .reward-card:nth-child(4) .reward-points {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
        }
                .reward-card:nth-child(5) {
            border-top-color: #ee52a0ff
        }
       
           .reward-card:nth-child(5) .reward-points {
            background: linear-gradient(135deg, #ca4286ff, #ca4286ff);
        }
       
        .reward-card:nth-child(6) {
            border-top-color: var(--baby-blue)
        }
         
        .reward-card:nth-child(6) .reward-points {
            background: linear-gradient(135deg, #597ab6ff, #449dadff);
        }
       
        /* زر الرجوع الجديد */
        .back-btn-container {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.8rem 2rem;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .back-btn:hover {
              background-color: var(--primary-color);

            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        .back-btn i {
            margin-right: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .rewards-header h1 {
                font-size: 2rem;
            }
            
            .rewards-grid {
                grid-template-columns: 1fr;
            }
            
            .back-btn {
                padding: 0.7rem 1.5rem;
                font-size: 0.9rem;
            }
        }
        .reward-card-link {
            text-decoration: none;
          
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .reward-card {
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }
        
        .reward-card:nth-child(1) { animation-delay: 0.1s; }
        .reward-card:nth-child(2) { animation-delay: 0.2s; }
        .reward-card:nth-child(3) { animation-delay: 0.3s; }
        .reward-card:nth-child(4) { animation-delay: 0.4s; }
        .reward-card:nth-child(5) { animation-delay: 0.5s; }
                .reward-card:nth-child(6) { animation-delay: 0.6s; }

    </style>

    <div class="rewards-container">
        <div class="rewards-header">
            <h1>{{ __('rewards.title') }}</h1>
            <p>{{ __('rewards.subtitle') }}</p>
        </div>

        <div class="rewards-grid">

            <div class="reward-card">
                                              <a href="{{ route('student.sections.chapters') }}" class="reward-card-link">

                <div class="reward-points">
                    <span>50+</span>
                    <i class="fas fa-book-open reward-icon"></i>
                </div>
                <h3 class="reward-title">{{ __('rewards.complete_lesson') }} </h3>
                <p class="reward-desc"> {{ __('rewards.complete_lesson_desc') }}  </p>
               </a>
              </div>
   
                     

            <div class="reward-card">
                                              <a href="{{ route('student.sections.domains') }}" class="reward-card-link">

                <div class="reward-points">
                    <span>80+</span>
                    <i class="fas fa-book-open reward-icon"></i>
                </div>
                <h3 class="reward-title">{{ __('rewards.complete_domain') }}</h3>
                <p class="reward-desc">{{ __('rewards.complete_domain_desc') }} </p>
               </a>
              </div>


            <div class="reward-card">
                                          <a href="{{ route('student.exams.index') }}" class="reward-card-link">

                <div class="reward-points">
                    <span>100+</span>
                    <i class="fas fa-clipboard-check reward-icon"></i>
                </div>
                <h3 class="reward-title">{{ __('rewards.complete_exam') }}</h3>
                <p class="reward-desc">{{ __('rewards.complete_exam_desc') }}</p>
           </a>
              </div>
                            
            

            <div class="reward-card">
                                                                      <a href="{{ route('student.sections') }}" class="reward-card-link">

                <div class="reward-points">
                    <span>75+</span>
                    <i class="fas fa-question-circle reward-icon"></i>
                </div>
                <h3 class="reward-title"> {{ __('rewards.solve_questions') }}</h3>
                <p class="reward-desc">{{ __('rewards.solve_questions_desc') }}</p>
              </a>
              </div>
                                                    
            
            <div class="reward-card">
                <div class="reward-points">
                    <span>30+</span>
                    <i class="fas fa-trophy reward-icon"></i>
                </div>
                <h3 class="reward-title"> {{ __('rewards.achievement') }}</h3>
                <p class="reward-desc">{{ __('rewards.achievement_desc') }}</p>
            </div>
            
            <div class="reward-card">
                <div class="reward-points">
                    <span>20+</span>
                    <i class="fas fa-calendar-check reward-icon"></i>
                </div>
                <h3 class="reward-title">{{ __('rewards.daily_study') }}</h3>
                <p class="reward-desc">{{ __('rewards.daily_study_desc') }}</p>
            </div>
        </div>
        
        <div class="back-btn-container">
    <a href="{{ route('student.achievements') }}" class="back-btn">
        @if(app()->getLocale() == 'ar')
            {{ __('rewards.back_to_achievements') }} <i class="fas fa-arrow-right"></i>
        @else
            <i class="fas fa-arrow-left"></i> {{ __('rewards.back_to_achievements') }}
        @endif
    </a>
</div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rewardCards = document.querySelectorAll('.reward-card');
            
            rewardCards.forEach(card => {
                card.addEventListener('click', function() {
                    this.classList.toggle('active');
                });
            });
        });
    </script>
@endsection