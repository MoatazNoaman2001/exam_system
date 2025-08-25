<div>
    <!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->
</div>
<div class="empty-state fade-in">
    <i class="fas fa-certificate empty-icon"></i>
    <h3 class="empty-title">{{ __('lang.No Certificates Available') }}</h3>
    <p class="empty-description">
        {{ __('lang.No certification programs are currently available. Please contact our support team or check back later for new programs.') }}
    </p>
    
    @if(config('app.support_email') || config('app.support_url'))
        <div style="margin-top: 2rem;">
            <a href="{{ config('app.support_url', 'mailto:' . config('app.support_email', 'support@example.com')) }}" 
               class="select-btn" 
               style="display: inline-flex; text-decoration: none; --card-accent: var(--primary);">
                <span>{{ __('lang.Contact Support') }}</span>
                <i class="fas fa-external-link-alt"></i>
            </a>
        </div>
    @endif
</div>