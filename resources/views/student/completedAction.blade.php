@extends('layouts.app')

@section('title', 'Completed_Action')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/completedAction.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <div class="container">
        <!-- Notification -->
        <div class="notification">
            <div class="user-info">
<img src="{{ $user->image ? asset('storage/avatars/' . $user->image) : asset('images/default-avatar.png') }}" alt="User Avatar" class="user-avatar">                <div class="user-info-text">
                    <div class="greeting">مرحبًا، {{ $user->username  }} 👋</div>
                    <div class="question">جاهز تكمّل رحلتك نحو شهادة PMP اليوم؟</div>
                </div>
            </div>
            <div class="bell-container" onclick="toggleNotifications()">
                <img src="{{ asset('images/Bell Icon.png') }}" alt="Bell" class="bell-icon">
                <div class="bell-dot {{ isset($notifications) && $notifications->where('read_at', null)->count() > 0 ? 'active' : '' }}"></div>
            </div>
            <div class="notification-dropdown" id="notification-dropdown">
                @if(isset($notifications) && $notifications->count() > 0)
                    @foreach($notifications as $notification)
                        <div class="notification-item">
                            <span class="notification-icon">🔔</span>
                            <div>
                                <div class="notification-text">{{ $notification->data['message'] ?? 'إشعار جديد' }}</div>
                                <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="notification-item">
                        <div class="notification-text">لا توجد إشعارات جديدة</div>
                    </div>
                @endif
            </div>
        </div>
<!-- Plan Status -->
        <div class="plan-status">
            <div class="plan-header">
                <div class="hourglass">⏳</div>
                <div class="days-left">
                    تبقّى <span id="daysLeft">0</span> يوم من أصل <span id="totalDays">{{ $progress->plan_duration ?? 60 }}</span> يوم على نهاية خطتك
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-text"><span>{{ $progress->progress ?? 60 }}%</span> تقدّمك:</div>
                <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: {{ $progress->progress ?? 60 }}%"></div>
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
        <div class="step-text {{ $currentStep == 'اختبارات' ? 'active' : '' }}">اختبارات</div>
        <img src="{{ asset('images/Arrow Icon.png') }}" alt="Arrow" class="arrow-icon">
        <div class="step-text {{ $currentStep == 'تدريبات' ? 'active' : '' }}">تدريبات</div>
        <img src="{{ asset('images/Arrow Icon.png') }}" alt="Arrow" class="arrow-icon">
        <div class="step-text {{ $currentStep == 'مجالات' ? 'active' : '' }}">مجالات</div>
        <img src="{{ asset('images/Arrow Icon.png') }}" alt="Arrow" class="arrow-icon">
        <div class="step-text {{ $currentStep == 'فصول' ? 'active' : '' }}">فصول</div>
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
                {{ $progress->points ?? 1350 }} نقطة
                <span class="target-icon">🎯</span>
            </div>
            <div class="points-footer">
                <div class="next-level">
                    محترف – بعد {{ $progress->points_to_next_level ?? 150 }} نقطة
                </div>
                <div class="current-level">
                    {{ $progress->current_level ?? 'مجتهد' }}
                    <span class="medal-icon">🏅</span>
                </div>
            </div>
        </div>
        <div class="top-users">
            ممتاز! أنت من بين <span>{{ $percent ?? 'أعلى 10' }}</span>% من المستخدمين 👏
        </div>
    </div>
</div>


        <!-- Achievements -->
<div class="achievements">
    <div class="achievements-header">
        <div class="chart-icon">📊</div>
        <div class="achievements-title">نظرة سريعة على إنجازاتك</div>
    </div>
    <div class="achievements-details">
        @php
            $lessonsCompleted = $progress->lessons_completed ?? 0;
            $lessonsTotal = $progress->lessons_total ?? 40;

            $streakDays = $progress->streak_days ?? 0;

            $questionsCompleted = $progress->questions_completed ?? 0;
            $questionsTotal = $progress->questions_total ?? 500;

            $achievementsCompleted = $progress->achievements_completed ?? 0;
        @endphp

        <div class="achievement-item">
            <div class="achievement-text">
                أنهيت {{ $lessonsCompleted }} درس من أصل {{ $lessonsTotal }}
            </div>
            <div class="achievement-icon">📘</div>
        </div>

        <div class="achievement-item streak">
            <div class="achievement-text">
                {{ $streakDays }} أيام متتالية من الدراسة
            </div>
            <div class="achievement-icon">🔥</div>
        </div>

        <div class="achievement-item questions">
            <div class="achievement-text">
                أجبت على {{ $questionsCompleted }} سؤال من أصل {{ $questionsTotal }}
            </div>
            <div class="achievement-icon">💯</div>
        </div>

        <div class="achievement-item completed">
            <div class="achievement-text">
                {{ $achievementsCompleted }} إنجازات مكتملة
            </div>
            <div class="achievement-icon">🏅</div>
        </div>
    </div>
</div>
    <script>
       document.addEventListener('DOMContentLoaded', () => {
            // Use a fallback userId
            const userId = '{{ auth()->id() ?? "default" }}';
            
            // Update days left dynamically
   function updateDaysLeft() {
    const endDate = new Date("{{ $progress->plan_end_date }}");
    const startDate = new Date("{{ $progress->start_date ?? \Carbon\Carbon::now() }}");
    const today = new Date();

    const totalDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
    const daysLeft = Math.max(0, Math.ceil((endDate - today) / (1000 * 60 * 60 * 24)));

    const daysLeftElement = document.getElementById('daysLeft');
    const totalDaysElement = document.getElementById('totalDays');

    if (daysLeftElement) daysLeftElement.textContent = daysLeft;
    if (totalDaysElement) totalDaysElement.textContent = totalDays;
}

            // Run on page load
            updateDaysLeft();
            
            // Toggle notifications dropdown
            window.toggleNotifications = () => {
                const dropdown = document.getElementById('notification-dropdown');
                const bellDot = document.querySelector('.bell-dot');
                
                if (dropdown && bellDot) {
                    dropdown.classList.toggle('active');
                    if (dropdown.classList.contains('active')) {
                        bellDot.classList.remove('active');
                        // Mark notifications as read via AJAX
                        fetch('/notifications/mark-as-read', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                        }).catch(error => console.error('Error marking notifications as read:', error));
                    }
                }
            };
            
            // Load tasks from localStorage for the specific user
            const loadTasks = () => {
                const today = new Date().toISOString().split('T')[0];
                const storedData = JSON.parse(localStorage.getItem(`tasks_${userId}`)) || { date: today, tasks: [] };
                
                if (storedData.date !== today) {
                    storedData.date = today;
                    storedData.tasks = [];
                    localStorage.setItem(`tasks_${userId}`, JSON.stringify(storedData));
                }
                
                const taskList = document.getElementById('task-list');
                if (!taskList) {
                    console.error('Task list element not found');
                    return;
                }
                
                taskList.innerHTML = '';
                
                storedData.tasks.forEach((task, index) => {
                    const taskItem = document.createElement('div');
                    taskItem.className = `task-item ${task.completed ? 'completed' : ''}`;
                    taskItem.innerHTML = `
                        <div class="task-content">
                            <input type="checkbox" class="task-checkbox" ${task.completed ? 'checked' : ''} onchange="toggleTask(${index})">
                            <span class="task-text ${task.completed ? 'completed' : ''}">${task.text}</span>
                        </div>
                        <button class="task-delete" onclick="deleteTask(${index})">🗑️</button>
                    `;
                    taskList.appendChild(taskItem);
                });
            };
            
            // Add a new task
            window.addTask = () => {
                const taskInput = document.getElementById('task-input');
                if (!taskInput) {
                    console.error('Task input element not found');
                    return;
                }
                
                const taskText = taskInput.value.trim();
                if (taskText === '') {
                    return;
                }
                
                const today = new Date().toISOString().split('T')[0];
                const storedData = JSON.parse(localStorage.getItem(`tasks_${userId}`)) || { date: today, tasks: [] };
                
                storedData.tasks.push({ text: taskText, completed: false });
                localStorage.setItem(`tasks_${userId}`, JSON.stringify(storedData));
                
                taskInput.value = '';
                loadTasks();
            };
            
            // Toggle task completion
            window.toggleTask = (index) => {
                const storedData = JSON.parse(localStorage.getItem(`tasks_${userId}`));
                if (storedData && storedData.tasks[index]) {
                    storedData.tasks[index].completed = !storedData.tasks[index].completed;
                    localStorage.setItem(`tasks_${userId}`, JSON.stringify(storedData));
                    loadTasks();
                }
            };
            
            // Delete a task
            window.deleteTask = (index) => {
                const storedData = JSON.parse(localStorage.getItem(`tasks_${userId}`));
                if (storedData && storedData.tasks[index]) {
                    storedData.tasks.splice(index, 1);
                    localStorage.setItem(`tasks_${userId}`, JSON.stringify(storedData));
                    loadTasks();
                }
            };
            
            // Load tasks on page load
            try {
                loadTasks();
            } catch (error) {
                console.error('Error loading tasks:', error);
            }
            
            // Allow adding task with Enter key
            const taskInput = document.getElementById('task-input');
            if (taskInput) {
                taskInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        addTask();
                    }
                });
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                const dropdown = document.getElementById('notification-dropdown');
                const bellContainer = document.querySelector('.bell-container');
                if (dropdown && bellContainer && !bellContainer.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('active');
                }
            });
        });
    
    </script>
@endsection