@extends('layouts.app')

@section('title', __('lang.certificate'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/certification.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="certificate-wrapper">
        <div class="certificate-inner">
            @if($progress->chapters_completed == $progress->chapters_total && 
                $progress->domains_completed == $progress->domains_total && 
                $progress->exams_completed == $progress->exams_total)
                
                <div class="certificate-box">
                    <div class="certificate-top">
                        <div class="certificate-heading">
                            <img src="{{ asset('images/vuesax-outline-arrow-right0.svg') }}" alt="Arrow" class="certificate-arrow">
                            <h2>{{ __('lang.my_certificate') }}</h2>
                        </div>
                        
                        <div class="certificate-body">
                            <h3 class="celebrate">🎉 {{ __('lang.congrats') }}</h3>
                            <p class="message">
                                {{ __('lang.certificate_message', ['name' => auth()->user()->name]) }}
                            </p>
                            <div class="certificate-image-container">
                                <img src="{{ asset('images/certificate.svg') }}" 
                                     alt="{{ __('lang.certificate') }}" class="certificate-img">
                            </div>
                        </div>
                    </div>
                    
                    <div class="certificate-buttons">
                        <a href="{{ route('student.certificate.download') }}" class="certificate-btn download">
                            <i class="fas fa-download"></i> {{ __('lang.download_certificate') }}
                        </a>
                        <button class="certificate-btn share" data-bs-toggle="modal" data-bs-target="#shareModal">
                            <i class="fas fa-share-alt"></i> {{ __('lang.share_certificate') }}
                        </button>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-share-square"></i> {{ __('lang.share_certificate') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('lang.close') }}"></button>
                            </div>
                            <div class="modal-body">
                                <div class="share-buttons">
                                    <a href="https://wa.me/?text={{ urlencode(__('lang.check_my_certificate') . ': ' . route('student.certificate.view')) }}" 
                                       class="share-btn whatsapp" target="_blank">
                                        <i class="fab fa-whatsapp"></i> {{ __('lang.whatsapp') }}
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('student.certificate.view')) }}" 
                                       class="share-btn facebook" target="_blank">
                                        <i class="fab fa-facebook-f"></i> {{ __('lang.facebook') }}
                                    </a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('student.certificate.view')) }}" 
                                       class="share-btn linkedin" target="_blank">
                                        <i class="fab fa-linkedin-in"></i> {{ __('lang.linkedin') }}
                                    </a>
                                    <button class="share-btn copy" onclick="copyLink()">
                                        <i class="fas fa-copy"></i> {{ __('lang.copy_link') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <div class="not-completed">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>{{ __('lang.complete_all_to_get_certificate') }}</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function copyLink() {
            navigator.clipboard.writeText('{{ route('student.certificate.view') }}')
                .then(() => alert('{{ __('lang.link_copied') }}'))
                .catch(() => alert('{{ __('lang.copy_error') }}'));
        }
    </script>
@endsection
