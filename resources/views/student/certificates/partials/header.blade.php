<div>
    <!-- We must ship. - Taylor Otwell -->
</div>

<div class="header-section">
    <div class="header-content">
        
        {{-- Header Badge --}}
        <div class="header-badge fade-in">
            <i class="fas fa-star"></i>
            <span>{{ __('lang.Professional Certifications') }}</span>
        </div>

        {{-- Main Title --}}
        <h1 class="header-title slide-up">
            {{ __('lang.Select Certificate') }}
        </h1>

        {{-- Subtitle --}}
        <p class="header-subtitle slide-up">
            {{ __('lang.Choose the certification program that matches your career goals and start your journey to professional excellence') }}
        </p>

        {{-- Statistics --}}
        <div class="header-stats slide-up">
            <div class="stat-item">
                <span class="stat-number">{{ $certificates->count() }}+</span>
                <span class="stat-label">{{ __('lang.Available Programs') }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">10K+</span>
                <span class="stat-label">{{ __('lang.Students Enrolled') }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">95%</span>
                <span class="stat-label">{{ __('lang.Success Rate') }}</span>
            </div>
        </div>

    </div>
</div>