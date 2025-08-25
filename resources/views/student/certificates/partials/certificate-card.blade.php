<div>
    <!-- It is not the man who has too little, but the man who craves more, that is poor. - Seneca -->
</div>
@php
    // Define card variants and icons
    $cardClasses = [
        'certificate-card--pmp',
        'certificate-card--agile', 
        'certificate-card--capm',
        'certificate-card--other'
    ];
    
    $cardIcons = [
        'fas fa-project-diagram',
        'fas fa-sync-alt',
        'fas fa-certificate',
        'fas fa-graduation-cap'
    ];
    
    // Assign variant based on index
    $cardClass = $cardClasses[$index % count($cardClasses)];
    $cardIcon = $cardIcons[$index % count($cardIcons)];
    
    // Get localized content
    $certificateName = app()->getLocale() === 'ar' && $certificate->name_ar 
        ? $certificate->name_ar 
        : $certificate->name;
        
    $certificateDescription = app()->getLocale() === 'ar' && $certificate->description_ar 
        ? $certificate->description_ar 
        : $certificate->description;
    
    // Define certificate-specific data
    $certificateData = [
        'pmp' => [
            'duration' => __('lang.4-6 months'),
            'students' => '15,000+',
            'rating' => '4.8',
            'level' => __('lang.Professional'),
            'highlights' => [
                __('lang.Global recognition in 195+ countries'),
                __('lang.Average salary increase of 25%'),
                __('lang.Interactive simulations and practice exams'),
                __('lang.Expert mentorship and career guidance')
            ],
            'badges' => [
                ['icon' => 'fas fa-globe', 'text' => __('lang.Global Standard')],
                ['icon' => 'fas fa-trophy', 'text' => __('lang.Industry Leader')],
                ['icon' => 'fas fa-chart-line', 'text' => __('lang.Career Growth')]
            ]
        ],
        'agile' => [
            'duration' => __('lang.2-3 months'),
            'students' => '8,500+',
            'rating' => '4.9',
            'level' => __('lang.Intermediate'),
            'highlights' => [
                __('lang.Master Scrum and Kanban methodologies'),
                __('lang.Real-world project experience'),
                __('lang.Certification preparation included'),
                __('lang.Access to exclusive Agile community')
            ],
            'badges' => [
                ['icon' => 'fas fa-rocket', 'text' => __('lang.Fast Track')],
                ['icon' => 'fas fa-users', 'text' => __('lang.Team Focused')],
                ['icon' => 'fas fa-sync', 'text' => __('lang.Adaptive')]
            ]
        ],
        'capm' => [
            'duration' => __('lang.3-4 months'),
            'students' => '12,000+',
            'rating' => '4.7',
            'level' => __('lang.Entry Level'),
            'highlights' => [
                __('lang.Perfect entry point to project management'),
                __('lang.PMI-approved curriculum and training'),
                __('lang.35 contact hours certification'),
                __('lang.Step towards PMP certification')
            ],
            'badges' => [
                ['icon' => 'fas fa-play', 'text' => __('lang.Entry Level')],
                ['icon' => 'fas fa-shield-alt', 'text' => __('lang.PMI Approved')],
                ['icon' => 'fas fa-arrow-up', 'text' => __('lang.Career Starter')]
            ]
        ],
        'other' => [
            'duration' => __('lang.2-5 months'),
            'students' => '5,000+',
            'rating' => '4.6',
            'level' => __('lang.Specialized'),
            'highlights' => [
                __('lang.Industry-specific expertise'),
                __('lang.Cutting-edge curriculum'),
                __('lang.Expert instructor-led training'),
                __('lang.Practical hands-on projects')
            ],
            'badges' => [
                ['icon' => 'fas fa-star', 'text' => __('lang.Specialized')],
                ['icon' => 'fas fa-cogs', 'text' => __('lang.Hands-on')],
                ['icon' => 'fas fa-lightbulb', 'text' => __('lang.Innovative')]
            ]
        ]
    ];
    
    // Determine certificate type based on code or name
    $type = 'other';
    $code = strtolower($certificate->code ?? '');
    if (str_contains($code, 'pmp') || str_contains(strtolower($certificateName), 'pmp')) {
        $type = 'pmp';
    } elseif (str_contains($code, 'agile') || str_contains(strtolower($certificateName), 'agile') || str_contains(strtolower($certificateName), 'scrum')) {
        $type = 'agile';
    } elseif (str_contains($code, 'capm') || str_contains(strtolower($certificateName), 'capm')) {
        $type = 'capm';
    }
    
    $data = $certificateData[$type];
@endphp

<form action="{{ route('student.certificates.select') }}" method="POST" class="certificate-form">
    @csrf
    <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
    
    <div class="certificate-card {{ $cardClass }}" onclick="selectCertificate(this)" tabindex="0">
        
        {{-- Card Header --}}
        <div class="certificate-header">
            <div class="certificate-icon">
                <i class="{{ $cardIcon }}"></i>
            </div>
            <div class="certificate-info">
                <h3 class="certificate-name">{{ $certificateName }}</h3>
                {{-- <div class="certificate-code">{{ strtoupper($certificate->code) }}</div> --}}
            </div>
        </div>
        
        {{-- Card Description --}}
        <div class="certificate-description">
            {{ Str::limit($certificateDescription, 120) }}
        </div>
        
        
        {{-- Card Footer --}}
        <div class="certificate-footer">
            
            <button type="submit" class="select-btn" data-original-text="{{ __('lang.Start Learning') }}">
                <span>{{ __('lang.Start Learning') }}</span>
                {{-- <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"></i> --}}
            </button>
        </div>
        
    </div>
</form>
        
