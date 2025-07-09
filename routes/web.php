<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
// use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IntroController;
use App\Http\Controllers\ForgetController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\SplashController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\LeaderBoardController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\certificationController;

use App\Http\Controllers\ChangPasswordController;
use App\Http\Controllers\CompletedActionController;
use App\Http\Controllers\AchievementPointController;
use App\Http\Controllers\VerificationCodeController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\TermsAndConditionsController;
use App\Http\Controllers\Auth\ForgotPasswordController;


App::setLocale('en');
Route::get('/locale/{locale}', [LocaleController::class, 'setLocale'])->name('locale.set');



Route::get('/set-locale/{locale}', function ($locale) {
    session(['locale' => $locale]);
    return "Locale set to $locale";
});

Route::get('/test-session', function () {
    return [
        'locale_in_session' => session('locale'),
        'locale_from_app' => App::getLocale(),
    ];
});


Route::get('/test-locale', function() {
    return [
        'current_locale' => app()->getLocale(),
        'session_locale' => session('locale'),
        'translation_test' => __('setting.account'),
        'all_session_data' => session()->all(),
        'config_locale' => config('app.locale')
    ];
});

Route::get('/locale-test', function() {
    return [
        'App Locale' => app()->getLocale(),
        'Session Locale' => session('locale'),
        'Config Locale' => config('app.locale'),
        'Translation Test' => __('setting.account'),
        'Carbon Locale' => \Carbon\Carbon::now()->locale,
        'Translator Locale' => app('translator')->getLocale()
    ];
});

Route::get("/", [WelcomeController::class , "root"])->name('welcome');


Route::get('/check-session', function () {
    return Session::get('locale'); // لازم تطبع "ar"
});

Route::view("/home", "home")->middleware('auth')
    
    ->name('home');
// Route::get('/admin', [DashboardController::class, 'index'])
//     ->middleware(['auth', 'verified', 'admin'])
//     ->name('admin.dashboard');
// Admin Routes Group
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // Domains Management
    Route::get('/domains', [AdminController::class, 'domains'])->name('domains');
    Route::get('/domains/create', [AdminController::class, 'createDomain'])->name('domains.create');
    Route::post('/domains', [AdminController::class, 'storeDomain'])->name('domains.store');
    Route::get('/domains/{domain}/edit', [AdminController::class, 'editDomain'])->name('domains.edit');
    Route::put('/domains/{domain}', [AdminController::class, 'updateDomain'])->name('domains.update');
    Route::delete('/domains/{domain}', [AdminController::class, 'destroyDomain'])->name('domains.destroy');

    // Chapters Management
    Route::get('/chapters', [AdminController::class, 'chapters'])->name('chapters');
    Route::get('/chapters/create', [AdminController::class, 'createChapter'])->name('chapters.create');
    Route::post('/chapters', [AdminController::class, 'storeChapter'])->name('chapters.store');
    Route::get('/chapters/{chapter}/edit', [AdminController::class, 'editChapter'])->name('chapters.edit');
    Route::put('/chapters/{chapter}', [AdminController::class, 'updateChapter'])->name('chapters.update');
    Route::delete('/chapters/{chapter}', [AdminController::class, 'destroyChapter'])->name('chapters.destroy');
    
    // Slides Management
    Route::get('/slides', [AdminController::class, 'slides'])->name('slides');
    Route::get('/slides/create', [AdminController::class, 'createSlide'])->name('slides.create');
    Route::post('/slides', [AdminController::class, 'storeSlide'])->name('slides.store');
    Route::get('/slides/{slide}/edit', [AdminController::class, 'editSlide'])->name('slides.edit');
    Route::put('/slides/{slide}', [AdminController::class, 'updateSlide'])->name('slides.update');
    Route::delete('/slides/{slide}', [AdminController::class, 'destroySlide'])->name('slides.destroy');
    
    // Additional routes for the new functionality
    Route::get('/slides/{slide}', [AdminController::class, 'showSlide'])->name('slides.show');
    Route::get('/slides/{slide}/download', [AdminController::class, 'downloadSlidePdf'])->name('slides.download');
    Route::get('/slides/{slide}/questions', [AdminController::class, 'getSlideQuestions'])->name('slides.questions');
    
    
    // Exams Management
    Route::get('/exams', [AdminController::class, 'exams'])->name('exams');
    Route::get('/exams/create', [AdminController::class, 'createExam'])->name('exams.create');
    Route::post('/admin/exams/import', [AdminController::class, 'import'])->name('exams.import');
    Route::post('/exams', [AdminController::class, 'storeExam'])->name('exams.store');
    Route::get('/exams/{exam}/edit', [AdminController::class, 'editExam'])->name('exams.edit');
    Route::put('/exams/{exam}', [AdminController::class, 'updateExam'])->name('exams.update');
    Route::delete('/exams/{exam}', [AdminController::class, 'destroyExam'])->name('exams.destroy');
    
    // Quiz Attempts
    Route::get('/quiz-attempts', [AdminController::class, 'quizAttempts'])->name('quiz-attempts');
    Route::get('/quiz-attempts/{quizAttempt}', [AdminController::class, 'showQuizAttempt'])->name('quiz-attempts.show');
    
    // Test Attempts
    Route::get('/test-attempts', [AdminController::class, 'testAttempts'])->name('test-attempts');
    Route::get('/test-attempts/{testAttempt}', [AdminController::class, 'showTestAttempt'])->name('test-attempts.show');
    
    // // Job Applications
    // Route::get('/applications', [AdminController::class, 'applications'])->name('applications');
    // Route::get('/applications/{application}', [AdminController::class, 'showApplication'])->name('applications.show');
    // Route::put('/applications/{application}/status', [AdminController::class, 'updateApplicationStatus'])->name('applications.status');
    
    // Notifications
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::get('/notifications/create', [AdminController::class, 'createNotification'])->name('notifications.create');
    Route::post('/notifications', [AdminController::class, 'storeNotification'])->name('notifications.store');
    Route::delete('/notifications/{notification}', [AdminController::class, 'destroyNotification'])->name('notifications.destroy');
    
    // Analytics & Reports
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [AdminController::class, 'exportReports'])->name('reports.export');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::view("/terms" , "aggrement_terms")->name('terms');

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::get('/logo', [LogoController::class, 'index'])->name('index');
Route::get('/feature', [FeaturesController::class, 'features'])->name('feature');
Route::get('/welcome', [WelcomeController::class, 'welcome'])->name('welcome');
Route::get('/splash', [SplashController::class, 'splash'])->name('splash');
// سير عمل إعادة تعيين كلمة المرور
Route::get('/forget-password', [ForgetController::class, 'showForgetPasswordForm'])->name('forget-password');
Route::post('/forget-password', [ForgetController::class, 'sendResetLinkEmail'])->name('forget-password.submit');

Route::get('/verification-code', [VerificationCodeController::class, 'showVerificationCodeForm'])->name('verification-code')->middleware('auth');
Route::post('/verification-code', [VerificationCodeController::class, 'verifyCode'])->name('verification-code.submit');

Route::get('/new-password', [NewPasswordController::class, 'showNewPasswordForm'])->name('new-password');
Route::post('/new-password', [NewPasswordController::class, 'resetPassword'])->name('new-password.submit');


// Route::middleware(['auth'])->group(function () {
//     Route::get('/completed-action/{userId}', [CompletedActionController::class, 'completedAction'])->name('completed-action');
//     Route::post('/tasks', [CompletedActionController::class, 'store']);
//     Route::patch('/tasks/{id}', [CompletedActionController::class, 'update']);
//     Route::delete('/tasks/{id}', [CompletedActionController::class, 'destroy']);
// });

Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->middleware('auth')->name('notifications.markAsRead');

// Route::get('/Achievement', [AchievementController::class, 'Achievement'])->name('Achievement');

Route::middleware(['auth'])->group(function () {
    Route::get('/Achievement', [AchievementController::class, 'index'])->name('achievement.index');
   Route::post('/achievement', [AchievementController::class, 'index'])->name('achievement.index');

    
});

Route::get('/Achievement-Point', [AchievementPointController::class, 'AchievementPoint'])->name('AchievementPoint');
Route::get('/plan', [PlanController::class, 'Plan'])->name('Plan');
Route::post('/plan/update', [PlanController::class, 'update'])->name('plan.update');
Route::get('/setting', [SettingController::class, 'show'])->name('setting');
Route::get('/certification', [certificationController::class, 'certification'])->name('certification');

Route::get('/leaderboard/{userId}', [LeaderBoardController::class, 'showLeaderBoard'])->name('leaderboard');

Route::get('/certificate/download', [CertificationController::class, 'download'])->name('certificate.download');
Route::get('/certificate/view', [CertificationController::class, 'view'])->name('certificate.view');



Route::middleware(['auth'])->prefix('student')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('student.profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('student.profile.update');
    Route::put('/profile/image', [ProfileController::class, 'updateImage'])->name('student.profile.update-image');
});
Route::delete('/user/delete', [ProfileController::class, 'destroy'])->name('user.delete')->middleware('auth');


Route::delete('/delete-account', [AccountController::class, 'delete'])->name('delete-account');

Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [ChangPasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/update-password', [ChangPasswordController::class, 'updatePassword'])->name('password.update');
});


Route::get('/terms-and-conditions', [TermsAndConditionsController::class, 'showTermsAndConditions'])->name('terms.conditions')->middleware('auth');

Route::get('/about', [AboutController::class, 'index'])->name('about')->middleware('auth');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');



Route::get('/contact-us', [ContactUsController::class, 'index'])->name('contact.us')->middleware('auth');

Route::get('/test-verification', function() {
    $user = App\Models\User::first();
    return $user->getKey();
});



Route::prefix('student')->name('student.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/intro', [IntroController::class, 'index'])->name('intro.index');
    Route::get('/intro/step/{step}', [IntroController::class, 'step'])->name('intro.step');
    Route::post('/intro/step/{step}', [IntroController::class, 'store'])->name('intro.store');
    Route::get('/intro/complete', [IntroController::class, 'complete'])->name('intro.complete');
    Route::get('/home',[HomeController::class , 'index'])->name('home');
    Route::get('/sections', [SectionsController::class, 'index'])->name('sections');
    Route::get('/sections/chapters', [SectionsController::class, 'chapters'])->name('sections.chapters');
    Route::get('/sections/domains', [SectionsController::class, 'domains'])->name('sections.domains');
    Route::get('/sections/chapters/{chapterId}/slides', [SectionsController::class , 'chapterShow'])->name('chapter.slides');
    Route::get('/sections/doamins/{domainId}/slides', [SectionsController::class , 'domainShow'])->name('domain.slides');
    Route::get('/sections/slides/{slideId}', [SectionsController::class, 'slideShow'])->name('sections.slides');
    Route::post('/slide/attempt', [SectionsController::class, 'recordAttempt'])->name('slide.attempt');
  
    Route::get('/setting',[SettingController::class, 'show'])->name('setting');
    
    // Plan Selection Routes
    Route::get('/plan/selection', [SectionsController::class, 'showPlanSelection'])->name('plan.selection');
    Route::post('/plan/store', [SectionsController::class, 'storePlan'])->name('plan.store');
    Route::get('/achievments', [AchievementController::class, 'index'])->name('achievements');

    // Route::get('/profile', [ProfileController::class, 'show'])->name('account');

    Route::prefix('exams')->name('exams.')->group(function () {
        
        // Main exam routes
        // Route::get('/', [ExamController::class, 'index'])->name('index');
        // Route::get('/{id}', [ExamController::class, 'show'])->name('show');
        Route::post('/{id}/start', [ExamController::class, 'startExam'])->name('start');
        Route::get('/check-plan', [SectionsController::class, 'checkPlanAndRedirect'])->name('check-plan');
        Route::get('/', [SectionsController::class, 'examsIndex'])->name('index');
        Route::get('/{exam}/details', [SectionsController::class, 'takeExam'])->name('details');
        
        // Session management routes
        Route::get('/{sessionId}/take', [ExamController::class, 'take'])->name('take');
        Route::post('/{sessionId}/answer', [ExamController::class, 'submitAnswer'])->name('submit-answer');
        Route::post('/{sessionId}/navigate', [ExamController::class, 'navigate'])->name('navigate');
        Route::post('/{sessionId}/pause', [ExamController::class, 'pause'])->name('pause');
        Route::post('/{sessionId}/resume', [ExamController::class, 'resume'])->name('resume');
        Route::post('/{sessionId}/complete', [ExamController::class, 'complete'])->name('complete');
        
        // Results and reporting routes
        Route::get('/{sessionId}/result', [ExamController::class, 'result'])->name('result');
        Route::get('/{sessionId}/detailed-report', [ExamController::class, 'detailedReport'])->name('detailed-report');
        Route::get('/{sessionId}/review', [ExamController::class, 'reviewSession'])->name('review');
        
        // AJAX/API routes for real-time updates
        Route::get('/{sessionId}/progress', [ExamController::class, 'getProgress'])->name('progress');
        Route::post('/{sessionId}/update-activity', [ExamController::class, 'updateActivityTime'])->name('update-activity');
        Route::get('/{sessionId}/question', [ExamController::class, 'apiGetQuestion'])->name('api-get-question');
        
        // Session history and management
        Route::get('/history/all', [ExamController::class, 'sessionHistory'])->name('history');
        Route::delete('/{sessionId}/delete', [ExamController::class, 'deleteSession'])->name('delete-session');
    });
});