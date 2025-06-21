@extends('layouts.app')

@section('title', 'Completed_Action')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/completedAction.css') }}">

    <div class="nav">
        <div class="nav-item">
            <img src="{{ asset('images/home.png') }}" alt="Home" class="nav-icon">
            <div>الرئيسية</div>
        </div>
        <div class="nav-item">
            <img src="{{ asset('images/element-3.png') }}" alt="Element" class="nav-icon">
            <div>عنصر</div>
        </div>
        <div class="nav-item">
            <img src="{{ asset('images/ranking.png') }}" alt="Ranking" class="nav-icon">
            <div>الترتيب</div>
        </div>
        <div class="nav-item">
            <img src="{{ asset('images/user.png') }}" alt="User" class="nav-icon">
            <div>حسابي</div>
        </div>
    </div>

    <div class="container">
        <!-- Notification -->
        <div class="notification">
            <div class="user-info">
                <img src="{{ auth()->user()->avatar ? asset('storage/avatars/' . auth()->user()->avatar) : asset('images/default-avatar.png') }}" alt="User Avatar" class="user-avatar">
                <div class="user-info-text">
                    <div class="greeting">مرحبًا، {{ auth()->user()->name ?? 'مستخدم' }} 👋</div>
                    <div class="question">جاهز تكمّل رحلتك نحو شهادة PMP اليوم؟</div>
                </div>
            </div>
            <div class="bell-container">
                <img src="{{ asset('images/Bell Icon.png') }}" alt="Bell" class="bell-icon">
                <div class="bell-dot"></div>
            </div>
        </div>

        <!-- Plan Status -->
        <div class="plan-status">
            <div class="plan-header">
                <div class="hourglass">⏳</div>
                <div class="days-left">تبقّى {{ auth()->user()->progress->days_left ?? 45 }} يوم على نهاية خطتك</div>
            </div>
            <div class="progress-container">
                <div class="progress-text"><span>{{ auth()->user()->progress->progress ?? 60 }}%</span> تقدّمك:</div>
                <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: {{ auth()->user()->progress->progress ?? 60 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Tasks -->
        <div class="tasks">
            <div class="tasks-header">
                <div class="calendar-icon">📅</div>
                <div class="tasks-title">مهام اليوم</div>
            </div>
            <div class="task-list" id="task-list"></div>
            <div class="task-input-container">
                <input type="text" id="task-input" class="task-input" placeholder="اكتب مهمتك الجديدة هنا">
                <div class="add-task-btn" onclick="addTask()">
                    <div class="add-task-text">إضافة</div>
                    <img src="{{ asset('images/add.png') }}" alt="Add" class="add-icon">
                </div>
            </div>
        </div>

        <!-- Journey -->
        <div class="journey">
            <div class="journey-header">
                <div class="compass-icon">🧭</div>
                <div class="journey-title">مسار رحلتك</div>
            </div>
            <div class="journey-steps">
                <div class="step-text">اختبارات</div>
                <img src="{{ asset('images/Arrow Icon.png') }}" alt="Arrow" class="arrow-icon">
                <div class="step-text">تدريبات</div>
                <img src="{{ asset('images/Arrow Icon.png') }}" alt="Arrow" class="arrow-icon">
                <div class="step-text active">مجالات</div>
                <img src="{{ asset('images/Arrow Icon.png') }}" alt="Arrow" class="arrow-icon">
                <div class="step-text active">فصول</div>
            </div>
        </div>

        <!-- Points -->
        <div class="points">
            <div class="points-header">
                <div class="diamond-icon">💎</div>
                <div class="points-title">نقاطك</div>
            </div>
            <div class="points-details">
                <div class="points-breakdown">
                    <div class="points-text">
                        {{ auth()->user()->progress->points ?? 1350 }} نقطة
                        <span class="target-icon">🎯</span>
                    </div>
                    <div class="points-footer">
                        <div class="next-level">محترف – بعد {{ auth()->user()->progress->points_to_next_level ?? 150 }} نقطة</div>
                        <div class="current-level">
                            {{ auth()->user()->progress->current_level ?? 'مجتهد' }}
                            <span class="medal-icon">🏅</span>
                        </div>
                    </div>
                </div>
                <div class="top-users">ممتاز! أنت من بين <span>{{ auth()->user()->progress->top_users_percent ?? 'أعلى 10%' }}</span> من المستخدمين 👏</div>
            </div>
        </div>

        <!-- Achievements -->
        <div class="achievements">
            <div class="achievements-header">
                <div class="chart-icon">📊</div>
                <div class="achievements-title">نظرة سريعة على إنجازاتك</div>
            </div>
            <div class="achievements-details">
                <div class="achievement-item">
                    <div class="achievement-text">أنهيت {{ auth()->user()->progress->lessons_completed ?? 8 }} دروس من أصل 40</div>
                    <div class="achievement-icon">📘</div>
                </div>
                <div class="achievement-item streak">
                    <div class="achievement-text">{{ auth()->user()->progress->streak_days ?? 7 }} أيام متتالية من الدراسة</div>
                    <div class="achievement-icon">🔥</div>
                </div>
                <div class="achievement-item questions">
                    <div class="achievement-text">أجبت على {{ auth()->user()->progress->questions_answered ?? 200 }} سؤال</div>
                    <div class="achievement-icon">💯</div>
                </div>
                <div class="achievement-item completed">
                    <div class="achievement-text">{{ auth()->user()->progress->achievements_completed ?? 4 }} إنجازات مكتملة</div>
                    <div class="achievement-icon">🏅</div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection