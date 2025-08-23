<div>
    <!-- It is not the man who has too little, but the man who craves more, that is poor. - Seneca -->
</div>
@php
    $cardClasses = [
        'certificate-card--pmp',
        'certificate-card--agile', 
        'certificate-card--capm',
        'certificate-card--other'
    ];
    $cardClass = $cardClasses[$index % count($cardClasses)];
    
    $icons = [
        'fas fa-project-diagram',
        'fas fa-sync-alt',
        'fas fa-certificate',
        'fas fa-graduation-cap'
    ];
    $icon = $icons[$index % count($icons)];
@endphp

<form action="{{ route('student.certificates.select') }}" method="POST" class="certificate-form">
    @csrf
    <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
    
    <div class="certificate-card {{ $cardClass }}" onclick="selectCertificate(this)">
        <div class="certificate-header">
            <div class="certificate-icon">
                <i class="{{ $icon }}"></i>
            </div>
            <div class="certificate-info">
                <h3 class="certificate-name">
                    {{ app()->getLocale() === 'ar' && $certificate->name_ar ? $certificate->name_ar : $certificate->name }}
                </h3>
                <div class="certificate-code">
                    {{ strtoupper($certificate->code) }}
                </div>
            </div>
        </div>
        
        <div class="certificate-description">
            {{ app()->getLocale() === 'ar' && $certificate->description_ar ? $certificate->description_ar : $certificate->description }}
        </div>
        
        <div class="certificate-meta">
            <div class="meta-item">
                <i class="fas fa-book meta-icon"></i>
                <span>{{ __('lang.Professional Certification') }}</span>
            </div>
            <button type="submit" class="select-btn" data-original-text="{{ __('lang.Start Learning') }}">
                {{ __('lang.Start Learning') }}
            </button>
        </div>
    </div>
</form>