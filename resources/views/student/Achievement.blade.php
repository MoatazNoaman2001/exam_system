@extends('layouts.app')

@section('title', 'Achievement')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Achievement.css') }}">

<div class="achievement-dashboard">
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="dashboard-title">إنجازي - <span class="username">{{ $user->username }}</span></h1>
        </div>
        <div class="header-ornament"></div>
    </div>

    <div class="dashboard-grid">
        <!-- كارت النقاط -->
        @php
            $levels = ['مبتدئ', 'متوسط', 'محترف', 'خبير', 'أسطورة'];
            $currentIndex = array_search($progress->current_level, $levels);
            $nextLevel = isset($levels[$currentIndex + 1]) ? $levels[$currentIndex + 1] : null;

            $pointsMap = [
                'مبتدئ' => 150,
                'متوسط' => 300,
                'محترف' => 500,
                'خبير' => 800,
                'أسطورة' => null,
            ];

            $pointsToNext = isset($pointsMap[$progress->current_level]) ? max(0, $pointsMap[$progress->current_level] - $totalPoints) : 0;
            $fillPercent = ($pointsToNext > 0) ? ($totalPoints / ($totalPoints + $pointsToNext)) * 100 : 100;

            $levelColors = [
                'مبتدئ' => '#28a745',
                'متوسط' => '#ffc107',
                'محترف' => '#fd7e14',
                'خبير' => '#dc3545',
                'أسطورة' => '#6f42c1',
            ];

            $color = $levelColors[$progress->current_level] ?? '#6c757d';
        @endphp

        <div class="dashboard-card points-card">
            <div class="card-decoration"></div>
            <div class="card-header">
                <span class="card-icon">🎯</span>
                <h2 class="card-title">نقاطك</h2>
            </div>
            <div class="stat-item">
                <span class="stat-label">النقاط:</span>
                <span class="stat-value">{{ $totalPoints }} نقطة</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">المرحلة الحالية:</span>
                <span class="stat-value">{{ $progress->current_level }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">المرحلة التالية:</span>
                <span class="stat-value">
                    @if ($progress->current_level === 'أسطورة')
                        🎉 تهانينا! لقد وصلت إلى أقصى مستوى! استمر في التألق!
                    @else
                        {{ $nextLevel ?? 'لا يوجد مستوى أعلى' }} – بعد {{ $pointsToNext }} نقطة
                    @endif
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-track">
                    <div class="progress-fill" style="width: {{ $fillPercent }}%; background-color: {{ $color }}"></div>
                </div>
                <div class="level-indicator">
                    <span class="current-level">{{ $progress->current_level }}</span>
                    <span class="next-level">
                        @if ($progress->current_level !== 'أسطورة')
                            المستوى التالي: {{ $nextLevel ?? 'لا يوجد' }}
                        @else
                            أنت الأسطورة 💪
                        @endif
                    </span>
                </div>
            </div>
            <button class="action-btn">طرق الحصول على النقاط</button>
        </div>

        <!-- كارت الخطة الزمنية -->
        @if ($progress && isset($progress->plan_id))
            <div class="dashboard-card plan-card">
                <div class="card-header">
                    <span class="card-icon">⏳</span>
                    <h2 class="card-title">الخطة الزمنية</h2>
                </div>
                <div class="stat-item">
                    <span class="stat-label">الأيام المتبقية:</span>
                    <span class="stat-value" id="daysLeft">{{ $progress->days_left ?? 0 }}</span> من {{ $progress->plan_duration ?? 30 }} يوم
                </div>
                <div class="stat-item">
                    <span class="stat-label">تاريخ الانتهاء:</span>
                    <span class="stat-value">{{ $progress->plan_end_date ?? 'غير محدد' }}</span>
                </div>
                <div class="progress-container">
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $progress->progress ?? 0 }}%"></div>
                    </div>
                    <div class="level-indicator">
                        <span class="current-level">{{ $progress->progress ?? 0 }}%</span>
                        <span class="next-level">نسبة الإنجاز</span>
                    </div>
                </div>
                <button class="action-btn">تعديل الخطة</button>
            </div>
        @else
            <div class="dashboard-card plan-card">
                <div class="card-header">
                    <span class="card-icon">⏳</span>
                    <h2 class="card-title">الخطة الزمنية</h2>
                </div>
                <p>لم تبدأ بعد في أي اختبارات. ابدأ الآن لتفعيل خطتك!</p>
            </div>
        @endif

        <!-- كارت الإحصائيات -->
        <div class="dashboard-card stats-card">
            <div class="card-header">
                <span class="card-icon">📊</span>
                <h2 class="card-title">إحصائيات التقدم</h2>
            </div>
            <div class="stats-grid">
                <div class="stat-circle">
                    <div class="circle-progress">
                        <div class="stat-number">{{ $progress->domains_completed ?? 0 }}</div>
                    </div>
                    <div class="stat-name">المجالات</div>
                </div>
                <div class="stat-circle">
                    <div class="circle-progress">
                        <div class="stat-number">{{ $progress->lessons_completed ?? 0 }}</div>
                    </div>
                    <div class="stat-name">الدروس</div>
                </div>
                <div class="stat-circle">
                    <div class="circle-progress">
                        <div class="stat-number">{{ $progress->exams_completed ?? 0 }}</div>
                    </div>
                    <div class="stat-name">الاختبارات</div>
                </div>
                <div class="stat-circle">
                    <div class="circle-progress">
                        <div class="stat-number">{{ $progress->questions_completed ?? 0 }}</div>
                    </div>
                    <div class="stat-name">الأسئلة</div>
                </div>
            </div>
        </div>

        <!-- كارت الإنجازات -->
        <div class="dashboard-card achievements-card">
            <div class="card-header">
                <span class="card-icon">🏆</span>
                <h2 class="card-title">إنجازاتي</h2>
            </div>
            <div class="achievements-container">
                <div class="achievement-badge gold">
                    <span class="badge-icon">🥇</span>
                    <div class="badge-content">
                        <h3>المجالات</h3>
                        <p>أكملت {{ $completed_domains->count() }} مجال</p>
                    </div>
                </div>
                <div class="achievement-badge silver">
                    <span class="badge-icon">📘</span>
                    <div class="badge-content">
                        <h3>الدروس</h3>
                        <p>أنهيت {{ $progress->lessons_completed ?? 0 }} درس</p>
                    </div>
                </div>
                <div class="achievement-badge bronze">
                    <span class="badge-icon">💡</span>
                    <div class="badge-content">
                        <h3>الأسئلة</h3>
                        <p>أجبت على {{ $progress->questions_completed ?? 0 }} سؤال</p>
                    </div>
                </div>
                <div class="achievement-badge streak">
                    <span class="badge-icon">🔥</span>
                    <div class="badge-content">
                        <h3>الاستمرارية</h3>
                        <p>{{ $streakDays }} يوم دراسة متتالي</p>
                    </div>
                </div>
            </div>
        </div>

       
    

<script>
    function updateDaysLeft() {
        const endDate = "{{ $progress->plan_end_date ?? '' }}";
        if (endDate) {
            const end = new Date(endDate);
            const today = new Date();
            const diffTime = end - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const daysLeft = Math.max(0, diffDays);
            document.getElementById('daysLeft').textContent = daysLeft;
        } else {
            document.getElementById('daysLeft').textContent = '0';
        }
    }

    @if ($progress && isset($progress->plan_id))
        updateDaysLeft();
    @endif
</script>
@endsection