<div>
    <!-- Order your soul. Reduce your wants. - Augustine -->
</div>
@extends('layouts.app')

@section('title', __('Slide') . ': ' . __($slide['title']))

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    :root {
        --primary-blue: #3b82f6;
        --primary-blue-light: #60a5fa;
        --primary-blue-dark: #2563eb;
        --primary-blue-bg: #eff6ff;
        --success-green: #10b981;
        --success-green-bg: #ecfdf5;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
        --space-xs: 0.5rem;
        --space-sm: 1rem;
        --space-md: 1.5rem;
        --space-lg: 2rem;
        --space-xl: 3rem;
        --radius-sm: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
        --radius-2xl: 1.5rem;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    body {
        font-family: 'Tajawal', 'Cairo', sans-serif;
        background-color: var(--gray-50);
        margin: 0;
        overflow: hidden;
    }
    body .main-content.sidebar-open {
        margin: 0 0 !important;
    } 
    .sidebar{
        display: none;
    }
    .slide-container {
        padding: 0;
        width: 100%;
        height: 100vh;
    }

    .slide-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: white;
        z-index: 1000;
        padding: var(--space-sm) var(--space-md);
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--shadow-sm);
    }

    .slide-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
    }

    .slide-actions {
        display: flex;
        gap: var(--space-sm);
    }

    .action-btn {
        padding: var(--space-xs) var(--space-sm);
        border: none;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .action-btn.back {
        background: var(--gray-100);
        color: var(--gray-700);
    }

    .action-btn.back:hover {
        background: var(--gray-200);
    }

    .action-btn.download {
        background: var(--primary-blue);
        color: white;
    }

    .action-btn.download:hover {
        background: var(--primary-blue-dark);
    }

    #pdf-viewer-container {
        width: 100vw;
        height: calc(100vh - 60px);
        margin-top: 60px;
        position: relative;
        background: var(--gray-50);
    }

    #pdf-canvas {
        width: 100%;
        height: 100%;
    }

    #end-of-pdf-notification {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: var(--success-green);
        color: white;
        padding: var(--space-sm);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-md);
        z-index: 1001;
    }

    .error-message {
        text-align: center;
        color: var(--gray-600);
        font-size: 1.1rem;
        margin: var(--space-xl) 0;
    }

    @media (max-width: 768px) {
        .slide-header {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-sm);
            padding: var(--space-xs) var(--space-sm);
        }

        .slide-title {
            font-size: 1.25rem;
        }

        .slide-actions {
            width: 100%;
            justify-content: space-between;
        }

        .action-btn {
            flex: 1;
            justify-content: center;
            font-size: 0.75rem;
        }

        #pdf-viewer-container {
            height: calc(100vh - 100px);
            margin-top: 100px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .action-btn {
            transition: none;
        }
    }
</style>

<div class="container-fluid" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="slide-container">
        <div class="slide-header">
            <h1 class="slide-title">{{ __('Slide') }}: {{ __($slide['title']) }}</h1>
            <div class="slide-actions">
                <a href="{{ route('student.chapter.slides', $slide['chapter_id']) }}" class="action-btn back">
                    <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
                    {{ __('Back to Slides') }}
                </a>
                <a href="{{ $pdf_url }}" download class="action-btn download">
                    <i class="fas fa-download"></i>
                    {{ __('Download PDF') }}
                </a>
            </div>
        </div>

        @if ($pdf_url)
            <div id="pdf-viewer-container">
                <canvas id="pdf-canvas"></canvas>
                <div id="end-of-pdf-notification">
                    {{ __('You have reached the end of the slide.') }}
                </div>
            </div>
        @else
            <div class="error-message">
                {{ __('Error Loading Slide') }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';

    @if ($pdf_url)
        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');
        const endNotification = document.getElementById('end-of-pdf-notification');

        pdfjsLib.getDocument('{{ $pdf_url }}').promise.then(pdf => {
            pdfDoc = pdf;
            renderPage(pageNum);
        }).catch(error => {
            console.error('Error loading PDF:', error);
            document.getElementById('pdf-viewer-container').innerHTML = '<div class="error-message">{{ __('Error Loading Slide') }}</div>';
        });

        function renderPage(num) {
            if (!pdfDoc) return;
            pageRendering = true;
            pdfDoc.getPage(num).then(page => {
                const viewport = page.getViewport({ scale: 1 });
                const scale = Math.min(window.innerWidth / viewport.width, (window.innerHeight - 60) / viewport.height);
                const scaledViewport = page.getViewport({ scale: scale });

                canvas.height = scaledViewport.height;
                canvas.width = scaledViewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: scaledViewport
                };
                page.render(renderContext).promise.then(() => {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                    if (num === pdfDoc.numPages) {
                        endNotification.style.display = 'block';
                        setTimeout(() => {
                            endNotification.style.display = 'none';
                        }, 3000);
                    }
                }).catch(error => {
                    console.error('Error rendering page:', error);
                });
            }).catch(error => {
                console.error('Error getting page:', error);
            });
        }

        window.addEventListener('wheel', event => {
            if (!pdfDoc || pageRendering) return;
            if (event.deltaY > 0 && pageNum < pdfDoc.numPages) {
                pageNum++;
                renderPage(pageNum);
            } else if (event.deltaY < 0 && pageNum > 1) {
                pageNum--;
                renderPage(pageNum);
            }
        });

        window.addEventListener('resize', () => {
            if (pdfDoc) {
                renderPage(pageNum);
            }
        });
    @endif

    $(document).ready(function() {
        $('.action-btn').on('click', function() {
            const $btn = $(this);
            const originalContent = $btn.html();
            const originalWidth = $btn.width();
            $btn.html('<i class="fas fa-spinner fa-spin"></i>').width(originalWidth);
            setTimeout(() => {
                $btn.html(originalContent).css('width', 'auto');
            }, 1000);
        });
    });
</script>
@endsection