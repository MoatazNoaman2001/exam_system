<div>
    <!-- If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius -->
</div>
<!-- Navigation Component - Add this to each view -->
<div class="student-navigation">
    <div class="nav-container">
        <!-- Back Button -->
        <button class="nav-btn back-btn" onclick="goBack()">
            <i class="fas fa-arrow-left"></i>
            <span>{{ __('lang.Back') }}</span>
        </button>

        <!-- Breadcrumb -->
        <div class="breadcrumb-container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(isset($selectedCertificate))
                        <li class="breadcrumb-item">
                            <a   href="{{ route('student.certificates.index') }}">
                                {{-- <i class="fas fa-certificate"></i>  --}}
                                {{ __('lang.Certificates') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ $selectedCertificate->name ?? __('lang.Selected Certificate') }}
                        </li>
                    @endif
                    
                    @if(Request::routeIs('student.sections.index'))
                        <li class="breadcrumb-item active">{{ __('lang.Sections') }}</li>
                    @elseif(Request::routeIs('student.sections.chapters'))
                        <li class="breadcrumb-item">
                            <a href="{{ route('student.sections.index') }}">{{ __('lang.Sections') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('lang.Chapters') }}</li>
                    @elseif(Request::routeIs('student.sections.domains'))
                        <li class="breadcrumb-item">
                            <a href="{{ route('student.sections.index') }}">{{ __('lang.Sections') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('lang.Domains') }}</li>
                    @elseif(Request::routeIs('student.chapter.slides'))
                        <li class="breadcrumb-item">
                            <a href="{{ route('student.sections.index') }}">{{ __('lang.Sections') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('student.sections.chapters') }}">{{ __('lang.Chapters') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $title ?? __('lang.Chapter') }}</li>
                    @elseif(Request::routeIs('student.domain.slides'))
                        <li class="breadcrumb-item">
                            <a href="{{ route('student.sections.index') }}">{{ __('lang.Sections') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('student.sections.domains') }}">{{ __('lang.Domains') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $title ?? __('lang.Domain') }}</li>
                    @endif
                </ol>
            </nav>
        </div>

        
    </div>
</div>

<!-- Certificate Selector Modal -->
<div class="certificate-modal" id="certificateModal" style="display: none;">
    <div class="modal-overlay" onclick="hideCertificateSelector()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('lang.Switch Certificate') }}</h3>
            <button class="close-btn" onclick="hideCertificateSelector()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>{{ __('lang.Select a different certificate to study') }}</p>
            <a href="{{ route('student.certificates.index') }}" class="switch-btn">
                <i class="fas fa-exchange-alt"></i>
                {{ __('lang.Choose Certificate') }}
            </a>
        </div>
    </div>
</div>

<style>
.student-navigation {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    padding: 1rem 0;
    margin-bottom: 2rem;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    display: flex;
    align-items: center;
    gap: 2rem;
}

.nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    color: #374151;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    cursor: pointer;
    white-space: nowrap;
}

.nav-btn:hover {
    background: #e5e7eb;
    color: #1f2937;
    transform: translateY(-1px);
}

.back-btn {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.back-btn:hover {
    background: #2563eb;
    color: white;
}

.home-btn {
    background: #10b981;
    color: white;
    border-color: #10b981;
}

.home-btn:hover {
    background: #059669;
    color: white;
}

.certificate-btn {
    background: #f59e0b;
    color: white;
    border-color: #f59e0b;
    max-width: 200px;
}

.certificate-btn:hover {
    background: #d97706;
    color: white;
}

.breadcrumb-container {
    flex: 1;
    min-width: 0;
}

.breadcrumb {
    margin: 0;
    padding: 0;
    background: none;
    font-size: 0.875rem;
}

.breadcrumb-item {
    color: #6b7280;
}

.breadcrumb-item.active {
    color: #1f2937;
    font-weight: 600;
}

.breadcrumb-item a {
    color: #3b82f6;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.breadcrumb-item a:hover {
    color: #2563eb;
    text-decoration: underline;
}

/* RTL alignment for breadcrumb links */
[dir="rtl"] .breadcrumb-item a {
    flex-direction: row-reverse;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.certificate-selector {
    position: relative;
}

/* Certificate Modal */
.certificate-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.modal-content {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 90%;
    position: relative;
    z-index: 1001;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
}

.close-btn {
    background: none;
    border: none;
    color: #6b7280;
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 0.25rem;
    transition: all 0.2s ease;
}

.close-btn:hover {
    background: #f3f4f6;
    color: #1f2937;
}

.modal-body {
    padding: 1.5rem;
    text-align: center;
}

.modal-body p {
    color: #6b7280;
    margin-bottom: 1.5rem;
}

.switch-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: #3b82f6;
    color: white;
    border-radius: 0.5rem;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s ease;
}

.switch-btn:hover {
    background: #2563eb;
    color: white;
    transform: translateY(-1px);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .nav-container {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .breadcrumb-container {
        order: -1;
    }

    .nav-actions {
        justify-content: space-between;
    }

    .nav-btn span {
        display: none;
    }

    .nav-btn {
        justify-content: center;
        min-width: 44px;
    }

    .certificate-btn {
        max-width: none;
        flex: 1;
    }

    .certificate-btn span {
        display: inline;
    }

    .breadcrumb {
        font-size: 0.8rem;
    }

    .breadcrumb-item {
        max-width: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}

@media (max-width: 480px) {
    .student-navigation {
        padding: 0.75rem 0;
    }

    .nav-container {
        padding: 0 0.5rem;
    }

    .nav-btn {
        padding: 0.5rem;
        font-size: 0.8rem;
    }
}

/* RTL Support */
[dir="rtl"] .nav-container {
    direction: rtl;
}

[dir="rtl"] .breadcrumb-item + .breadcrumb-item::before {
    content: "\\";
    transform: scaleX(-1);
}

[dir="rtl"] .back-btn .fas {
    transform: scaleX(-1);
}
</style>

<script>
function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = "{{ route('student.sections.index') }}";
    }
}

function showCertificateSelector() {
    document.getElementById('certificateModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function hideCertificateSelector() {
    document.getElementById('certificateModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideCertificateSelector();
    }
});
</script>