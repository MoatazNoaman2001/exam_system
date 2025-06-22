@extends('layouts.app')

@section('title', 'Acheivement')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/Achievement.css') }}">

    <div class="web-achievement-dashboard" dir="rtl">
        <div class="web-achievement-dashboard__header">
            <h1 class="web-achievement-dashboard__title">إنجازي - {{ $user->username }}</h1>
        </div>
        <div class="web-achievement-dashboard__content">
            <div class="web-achievement-dashboard__card points-card">
                <div class="web-achievement-dashboard__card-header">
                    <span class="web-achievement-dashboard__icon">🎯</span>
                    <h2 class="web-achievement-dashboard__subtitle">نقاطك</h2>
                </div>
                <div class="web-achievement-dashboard__card-body">
                    <div class="web-achievement-dashboard__stat">
                        <span class="web-achievement-dashboard__stat-label">النقاط:</span>
                        <span class="web-achievement-dashboard__stat-value">{{ $progress->points ?? 0 }} نقطة</span>
                    </div>
                    <div class="web-achievement-dashboard__stat">
                        <span class="web-achievement-dashboard__stat-label">المرحلة الحالية:</span>
                        <span class="web-achievement-dashboard__stat-value">{{ $progress->current_level ?? 'مبتدئ' }} <span class="web-achievement-dashboard__icon">🏅</span></span>
                    </div>
                    <div class="web-achievement-dashboard__stat">
                        <span class="web-achievement-dashboard__stat-label">المرحلة التالية:</span>
                        <span class="web-achievement-dashboard__stat-value">محترف – بعد {{ $progress->points_to_next_level ?? 150 }} نقطة</span>
                    </div>
                    <a href="#" class="web-achievement-dashboard__link">طرق الحصول على النقاط</a>
                </div>
            </div>
            <div class="web-achievement-dashboard__card plan-card">
                <div class="web-achievement-dashboard__card-header">
                    <span class="web-achievement-dashboard__icon">⏳</span>
                    <h2 class="web-achievement-dashboard__subtitle">الخطة الزمنية</h2>
                </div>
                <div class="web-achievement-dashboard__card-body">
                    <div class="web-achievement-dashboard__stat" id="daysLeftContainer">
                        <span class="web-achievement-dashboard__stat-label">الأيام المتبقية:</span>
                        <span class="web-achievement-dashboard__stat-value" id="daysLeft">0</span> من {{ $progress->plan_duration ?? 30 }} يومًا
                    </div>
                    <div class="web-achievement-dashboard__stat">
                        <span class="web-achievement-dashboard__stat-label">تاريخ الانتهاء:</span>
                        <span class="web-achievement-dashboard__stat-value">{{ $progress->plan_end_date ?? 'غير محدد' }}</span>
                    </div>
                    <div class="web-achievement-dashboard__progress">
                        <span class="web-achievement-dashboard__progress-label">{{ $progress->progress ?? 0 }}% مكتمل</span>
                        <div class="web-achievement-dashboard__progress-bar">
                            <div class="web-achievement-dashboard__progress-fill" style="width: {{ $progress->progress ?? 0 }}%"></div>
                        </div>
                    </div>
                    <a href="#" class="web-achievement-dashboard__link">تعديل الخطة</a>
                </div>
            </div>
            <div class="web-achievement-dashboard__card stats-card">
                <div class="web-achievement-dashboard__card-header">
                    <span class="web-achievement-dashboard__icon">🔥</span>
                    <h2 class="web-achievement-dashboard__subtitle">إحصائيات التقدم</h2>
                </div>
                <div class="web-achievement-dashboard__card-body">
                    <div class="web-achievement-dashboard__stats-grid">
                        <div class="web-achievement-dashboard__stat-item">
                            <span class="web-achievement-dashboard__icon">🧭</span>
                            <span class="web-achievement-dashboard__stat-label">المجالات</span>
                            <span class="web-achievement-dashboard__stat-value">{{ $progress->domains_completed ?? 0 }} / {{ $progress->domains_total ?? 0 }}</span>
                        </div>
                        <div class="web-achievement-dashboard__stat-item">
                            <span class="web-achievement-dashboard__icon">📘</span>
                            <span class="web-achievement-dashboard__stat-label">الدروس</span>
                            <span class="web-achievement-dashboard__stat-value">{{ $progress->lessons_completed ?? 0 }} / {{ $progress->lessons_total ?? 0 }}</span>
                        </div>
                        <div class="web-achievement-dashboard__stat-item">
                            <span class="web-achievement-dashboard__icon">📝</span>
                            <span class="web-achievement-dashboard__stat-label">الاختبارات</span>
                            <span class="web-achievement-dashboard__stat-value">{{ $progress->exams_completed ?? 0 }} / {{ $progress->exams_total ?? 0 }}</span>
                        </div>
                        <div class="web-achievement-dashboard__stat-item">
                            <span class="web-achievement-dashboard__icon">💡</span>
                            <span class="web-achievement-dashboard__stat-label">الأسئلة</span>
                            <span class="web-achievement-dashboard__stat-value">{{ $progress->questions_completed ?? 0 }} / {{ $progress->questions_total ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="web-achievement-dashboard__card achievements-card">
                <div class="web-achievement-dashboard__card-header">
                    <span class="web-achievement-dashboard__icon">🏆</span>
                    <h2 class="web-achievement-dashboard__subtitle">إنجازاتي</h2>
                </div>
                <div class="web-achievement-dashboard__card-body">
                    <div class="web-achievement-dashboard__achievements-grid">
                        <div class="web-achievement-dashboard__achievement-item" style="border-color: #ffbe00; background: rgba(255, 190, 0, 0.1)">
                            <span class="web-achievement-dashboard__achievement-text">أكملت أول مجال</span>
                            <span class="web-achievement-dashboard__icon">🏅</span>
                        </div>
                        <div class="web-achievement-dashboard__achievement-item" style="border-color: #35b369; background: rgba(53, 179, 105, 0.1)">
                            <span class="web-achievement-dashboard__achievement-text">أنهيت {{ $progress->lessons_milestone ?? 0 }} دروس</span>
                            <span class="web-achievement-dashboard__icon">📘</span>
                        </div>
                        <div class="web-achievement-dashboard__achievement-item" style="border-color: #2f80ed; background: rgba(47, 128, 237, 0.1)">
                            <span class="web-achievement-dashboard__achievement-text">أجبت على {{ $progress->questions_milestone ?? 0 }} سؤال</span>
                            <span class="web-achievement-dashboard__icon">💯</span>
                        </div>
                        <div class="web-achievement-dashboard__achievement-item" style="border-color: #ed3a3a; background: rgba(237, 58, 58, 0.1)">
                            <span class="web-achievement-dashboard__achievement-text">{{ $progress->streak_days ?? 0 }} أيام دراسة</span>
                            <span class="web-achievement-dashboard__icon">🔥</span>
                        </div>
                    </div>
                    <a href="#" class="web-achievement-dashboard__link">عرض المزيد</a>
                </div>
            </div>
        </div>
     

    <script>
        function updateDaysLeft() {
            const endDate = new Date("{{ $progress->plan_end_date }}");
            const today = new Date();
            const diffTime = endDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // الفرق بالأيام
            const daysLeft = Math.max(0, diffDays); // لا تسمح بقيمة سلبية
            document.getElementById('daysLeft').textContent = daysLeft;
        }

        // تحديث عند تحميل الصفحة
        updateDaysLeft();

        // تحديث كل يوم (يمكن تفعيل هذا لتحديث ديناميكي)
        // setInterval(updateDaysLeft, 24 * 60 * 60 * 1000); // كل 24 ساعة
    </script>
@endsection