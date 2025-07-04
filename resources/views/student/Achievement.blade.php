
@php
use Carbon\Carbon;
@endphp

@extends('layouts.app')

@section('title', 'إنجازاتي')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Achievement.css') }}">

<div class="achievement-dashboard">
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="dashboard-title">{{ __('إنجازي') }} - <span class="username">{{ $user->username }}</span></h1>
        </div>
        <div class="header-ornament"></div>
    </div>

    <div class="dashboard-grid">
        <!-- كارت النقاط -->
        @php
            $fillPercent = ($pointsToNext > 0 && $nextLevel) ? (($totalPoints / ($totalPoints + $pointsToNext)) * 100) : ($currentLevel === 'أسطورة' ? 100 : 0);
            $levelColors = [
                'مبتدئ' => '#28a745',
                'متوسط' => '#ffc107',
                'محترف' => '#fd7e14',
                'خبير | محترف' => '#dc3545',
                'أسطورة' => '#6f42c1',
            ];
            $color = $levelColors[$currentLevel] ?? '#6c757d';
        @endphp

        <div class="dashboard-card points-card">
            <div class="card-decoration"></div>
            <div class="card-header">
                <span class="card-icon">🎯</span>
                <h2 class="card-title">{{ __('نقاطك') }}</h2>
            </div>
            <div class="stat-item">
                <span class="stat-label">{{ __('النقاط') }}:</span>
                <span class="stat-value">{{ $totalPoints }} {{ __('نقطة') }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">{{ __('المرحلة الحالية') }}:</span>
                <span class="stat-value">{{ $currentLevel }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">{{ __('المرحلة التالية') }}:</span>
                <span class="stat-value">
                    @if ($currentLevel === 'أسطورة')
                        🎉 {{ __('تهانينا! لقد وصلت إلى أقصى مستوى! استمر في التألق!') }}
                    @else
                        {{ $nextLevel ?? __('لا يوجد مستوى أعلى') }} – {{ __('بعد') }} {{ $pointsToNext }} {{ __('نقطة') }}
                    @endif
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-track">
                    <div class="progress-fill" style="width: {{ $fillPercent }}%; background-color: {{ $color }}"></div>
                </div>
                <div class="level-indicator">
                    <span class="current-level">{{ $currentLevel }}</span>
                    <span class="next-level">
                        @if ($currentLevel !== 'أسطورة')
                            {{ __('المستوى التالي') }}: {{ $nextLevel ?? __('لا يوجد') }}
                        @else
                            {{ __('أنت الأسطورة 💪') }}
                        @endif
                    </span>
                </div>
            </div>
            <button class="action-btn">{{ __('طرق الحصول على النقاط') }}</button>
        </div>

        <!-- كارت الخطة الزمنية -->
        @if ($planDuration > 0 && $planEndDate)
            @php
                $circleColor = ($progressPercent <= 33.33) ? '#28a745' : ($progressPercent <= 66.67 ? '#ffc107' : '#dc3545');
                $circumference = 2 * pi() * 45;
                $offset = $circumference * (1 - ($daysLeft / $planDuration));
            @endphp
            <div class="dashboard-card plan-card">
                <div class="card-header">
                    <span class="card-icon">⏳</span>
                    <h2 class="card-title">{{ __('الخطة الزمنية') }}</h2>
                </div>
                <div class="stat-item">
                    <span class="stat-label">{{ __('الأيام المتبقية') }}:</span>
                    <span class="stat-value" id="daysLeft">{{ $daysLeft }}</span> {{ __('من') }} {{ $planDuration }} {{ __('يوم') }}
                </div>
                <div class="stat-item">
                    <span class="stat-label">{{ __('تاريخ الانتهاء') }}:</span>
                    <span class="stat-value">{{ $planEndDate ? Carbon::parse($planEndDate)->format('Y-m-d') : __('غير محدد') }}</span>
                </div>
                <div class="circle-progress-container">
                    <svg width="100" height="100" class="circle-progress">
                        <circle class="circle-progress-bg" cx="50" cy="50" r="45"></circle>
                        <circle class="circle-progress-fill" cx="50" cy="50" r="45" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}" style="stroke: {{ $circleColor }}"></circle>
                    </svg>
                    <div class="circle-progress-text">{{ $daysLeft }}</div>
                </div>
                <button class="action-btn" onclick="showPlanForm()">{{ __('تعديل الخطة') }}</button>
                <div class="plan-form" id="planForm" style="display: none;">
                    <form method="POST" action="{{ route('achievement.index') }}">
                        @csrf
                        <input type="number" name="plan_duration" min="1" placeholder="{{ __('عدد الأيام') }}" required>
                        <button type="submit">{{ __('تأكيد الخطة') }}</button>
                    </form>
                </div>
            </div>
        @elseif ($allContentCompleted)
            <div class="dashboard-card plan-card">
                <div class="card-header">
                    <span class="card-icon">⏳</span>
                    <h2 class="card-title">{{ __('الخطة الزمنية') }}</h2>
                </div>
                <p>{{ __('تهانينا! لقد أكملت جميع الفصول والمجالات. يمكنك الآن اختيار الخطة الزمنية للامتحانات.') }}</p>
                <button class="action-btn" onclick="showPlanForm()">{{ __('اختيار الخطة الزمنية') }}</button>
                <div class="plan-form" id="planForm" style="display: none;">
                    <form method="POST" action="{{ route('achievement.index') }}">
                        @csrf
                        <input type="number" name="plan_duration" min="1" placeholder="{{ __('عدد الأيام') }}" required>
                        <button type="submit">{{ __('تأكيد الخطة') }}</button>
                    </form>
                </div>
            </div>
        @else
            <div class="dashboard-card plan-card">
                <div class="card-header">
                    <span class="card-icon">⏳</span>
                    <h2 class="card-title">{{ __('الخطة الزمنية') }}</h2>
                </div>
                <p>{{ __('لم تبدأ بعد في أي اختبارات. أكمل جميع الفصول والمجالات لتفعيل خطتك!') }}</p>
            </div>
        @endif

        <!-- كارت الإحصائيات -->
        <div class="dashboard-card stats-card">
            <div class="card-header">
                <span class="card-icon">📊</span>
                <h2 class="card-title">{{ __('إحصائيات التقدم') }}</h2>
            </div>
            <div class="stats-grid">
                <div class="stat-circle">
                    <div class="circle-progress">
                        <div class="stat-number">{{ $completedDomains }}</div>
                    </div>
                    <div class="stat-name">{{ __('المجالات') }}</div>
                </div>
                <div class="stat-circle">
                    <div class="circle-progress">
                        <div class="stat-number">{{ $completedChapters }}</div>
                    </div>
                    <div class="stat-name">{{ __('الفصول') }}</div>
                </div>
                <div class="stat-circle">
                    <div class="circle-progress">
                        <div class="stat-number">{{ $completedExams }}</div>
                    </div>
                    <div class="stat-name">{{ __('الامتحانات') }}</div>
                </div>
                <div class="stat-circle">
                    <div class="circle-progress">
                        <div class="stat-number">{{ $completedQuestions }}</div>
                    </div>
                    <div class="stat-name">{{ __('الأسئلة') }}</div>
                </div>
            </div>
        </div>

        <!-- كارت الإنجازات -->
        <div class="dashboard-card achievements-card">
            <div class="card-header">
                <span class="card-icon">🏆</span>
                <h2 class="card-title">{{ __('إنجازاتي') }}</h2>
            </div>
            <div class="achievements-container">
                <div class="achievement-badge gold">
                    <span class="badge-icon">🥇</span>
                    <div class="badge-content">
                        <h3>{{ __('المجالات') }}</h3>
                        <p>{{ __('أكملت') }} {{ $completedDomains }} {{ __('مجال') }}</p>
                    </div>
                </div>
                <div class="achievement-badge silver">
                    <span class="badge-icon">📘</span>
                    <div class="badge-content">
                        <h3>{{ __('الفصول') }}</h3>
                        <p>{{ __('أنهيت') }} {{ $completedChapters }} {{ __('فصل') }}</p>
                    </div>
                </div>
                <div class="achievement-badge bronze">
                    <span class="badge-icon">💡</span>
                    <div class="badge-content">
                        <h3>{{ __('الأسئلة') }}</h3>
                        <p>{{ __('أجبت على') }} {{ $completedQuestions }} {{ __('سؤال') }}</p>
                    </div>
                </div>
                <div class="achievement-badge streak">
                    <span class="badge-icon">🔥</span>
                    <div class="badge-content">
                        <h3>{{ __('الاستمرارية') }}</h3>
                        <p>{{ $streakDays }} {{ __('يوم دراسة متتالي') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateDaysLeft() {
            const endDate = "{{ $planEndDate ?? '' }}";
            const planDuration = {{ $planDuration ?? 1 }};
            if (endDate) {
                const end = new Date(endDate);
                const today = new Date();
                const diffTime = end - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const daysLeft = Math.max(0, diffDays);
                document.getElementById('daysLeft').textContent = daysLeft;

                // تحديث الدائرة
                const circumference = 2 * Math.PI * 45;
                const offset = circumference * (1 - (daysLeft / planDuration));
                const circle = document.querySelector('.circle-progress-fill');
                if (circle) {
                    circle.setAttribute('stroke-dashoffset', offset);
                    const progressPercent = (planDuration - daysLeft) / planDuration * 100;
                    if (progressPercent <= 33.33) {
                        circle.style.stroke = '#28a745'; // أخضر
                    } else if (progressPercent <= 66.67) {
                        circle.style.stroke = '#ffc107'; // أصفر
                    } else {
                        circle.style.stroke = '#dc3545'; // أحمر
                    }
                }
            } else {
                document.getElementById('daysLeft').textContent = '0';
            }
        }

        function showPlanForm() {
            const planForm = document.getElementById('planForm');
            if (planForm) {
                planForm.style.display = planForm.style.display === 'none' ? 'block' : 'none';
            }
        }

        @if ($planDuration > 0 && $planEndDate)
            updateDaysLeft();
            setInterval(updateDaysLeft, 24 * 60 * 60 * 1000); // تحديث يومي
        @endif
    </script>
</div>
@endsection