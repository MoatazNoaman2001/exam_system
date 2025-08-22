<?php
use App\Models\Notification;
use App\Http\Middleware\IsStudent;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
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
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EarnPointsController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\LeaderBoardController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\AttemptController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\certificationController;
use App\Http\Controllers\ChangPasswordController;
use App\Http\Controllers\Admin\AdminExamController;
use App\Http\Controllers\CertificateHomeController;
use App\Http\Controllers\CompletedActionController;
use App\Http\Controllers\AchievementPointController;
use App\Http\Controllers\Admin\ExamImportController;
use App\Http\Controllers\VerificationCodeController;


use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\TermsAndConditionsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminExamQuestionController;
use App\Http\Controllers\Admin\NotificationController as NC;

// App::setLocale('ar'); // Removed - this was preventing language switching
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
// Route::view("/contact", "contact")->name('contact');
// Route::post("/contact", [ContactUsController::class, 'store'])->name('contact.store');
Route::view('/privacy', 'privacy');
Route::view("/home", "home")->middleware('auth')->name('home');

// Route::get('/admin', [DashboardController::class, 'index'])
//     ->middleware(['auth', 'verified', 'admin'])
//     ->name('admin.dashboard');
// Admin Routes Group
Route::prefix('admin')->name('admin.')->middleware(['auth','admin', SetLocale::class])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/stats', [AdminDashboardController::class, 'getStats'])->name('stats');
    Route::get('/chart-data', [AdminDashboardController::class, 'getChartData'])->name('chart-data');
    
    // Users Management - Updated to new controller
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    

    // Domains Management - Updated to new controller
    Route::get('/domains', [DomainController::class, 'index'])->name('domains');
    Route::get('/domains/create', [DomainController::class, 'create'])->name('domains.create');
    Route::post('/domains', [DomainController::class, 'store'])->name('domains.store');
    Route::get('/domains/{domain}', [DomainController::class, 'show'])->name('domains.show');
    Route::get('/domains/{domain}/edit', [DomainController::class, 'edit'])->name('domains.edit');
    Route::put('/domains/{domain}', [DomainController::class, 'update'])->name('domains.update');
    Route::delete('/domains/{domain}', [DomainController::class, 'destroy'])->name('domains.destroy');
    Route::get('/domains/certificate/{certificate}', [DomainController::class, 'getByCertificate'])->name('domains.by-certificate');

    // Chapters Management - Updated to new controller
    Route::get('/chapters', [ChapterController::class, 'index'])->name('chapters');
    Route::get('/chapters/create', [ChapterController::class, 'create'])->name('chapters.create');
    Route::post('/chapters', [ChapterController::class, 'store'])->name('chapters.store');
    Route::get('/chapters/{chapter}', [ChapterController::class, 'show'])->name('chapters.show');
    Route::get('/chapters/{chapter}/edit', [ChapterController::class, 'edit'])->name('chapters.edit');
    Route::put('/chapters/{chapter}', [ChapterController::class, 'update'])->name('chapters.update');
    Route::delete('/chapters/{chapter}', [ChapterController::class, 'destroy'])->name('chapters.destroy');
    Route::get('/chapters/certificate/{certificate}', [ChapterController::class, 'getByCertificate'])->name('chapters.by-certificate');
    
    // Slides Management - Updated to new controller
    Route::get('/slides', [SlideController::class, 'index'])->name('slides');
    Route::get('/slides/create', [SlideController::class, 'create'])->name('slides.create');
    Route::post('/slides', [SlideController::class, 'store'])->name('slides.store');
    Route::get('/slides/{slide}', [SlideController::class, 'show'])->name('slides.show');
    Route::get('/slides/{slide}/edit', [SlideController::class, 'edit'])->name('slides.edit');
    Route::put('/slides/{slide}', [SlideController::class, 'update'])->name('slides.update');
    Route::delete('/slides/{slide}', [SlideController::class, 'destroy'])->name('slides.destroy');
    Route::get('/slides/{slide}/download', [SlideController::class, 'downloadPdf'])->name('slides.download');
    Route::get('/slides/{slide}/questions', [SlideController::class, 'getQuestions'])->name('slides.questions');
    
    // Exams Management - Keep existing exam controllers (unchanged)
    Route::get('/exams', [AdminExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create', [AdminExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [AdminExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}', [AdminExamController::class, 'show'])->name('exams.show');
    Route::get('/exams/{exam}/edit', [AdminExamController::class, 'edit'])->name('exams.edit');
    Route::put('/exams/{exam}', [AdminExamController::class, 'update'])->name('exams.update');
    Route::delete('/exams/{exam}', [AdminExamController::class, 'destroy'])->name('exams.destroy');
    
    // Exam Questions Management - Keep existing (unchanged)
    Route::get('/exams/{exam}/questions', [AdminExamQuestionController::class, 'index'])->name('exams.questions.index');
    Route::get('/exams/{exam}/questions/create', [AdminExamQuestionController::class, 'create'])->name('exams.questions.create');
    Route::post('/exams/{exam}/questions', [AdminExamQuestionController::class, 'store'])->name('exams.questions.store');
    Route::get('/exams/{exam}/questions/{question}/edit', [AdminExamQuestionController::class, 'edit'])->name('exams.questions.edit');
    Route::put('/exams/{exam}/questions/{question}', [AdminExamQuestionController::class, 'update'])->name('exams.questions.update');
    Route::delete('/exams/{exam}/questions/{question}', [AdminExamQuestionController::class, 'destroy'])->name('exams.questions.destroy');
    
    // Excel Import routes - Keep existing (unchanged)
    Route::get('/admin/exams/import', [ExamImportController::class, 'showImportForm'])->name('exams.import.form');
    Route::post('/admin/exams/import', [ExamImportController::class, 'import'])->name('exams.import');
    Route::get('/admin/exams/import/template', [ExamImportController::class, 'downloadTemplate'])->name('exams.import.template');
    Route::get('/admin/exams/generate-template', [ExamImportController::class, 'generateTemplateRoute'])->name('exams.generate-template');
    Route::get('/admin/exams/download-template', [ExamImportController::class, 'downloadTemplate'])->name('exams.download-template');

    // Quiz Attempts - Updated to new controller
    Route::get('/quiz-attempts', [AttemptController::class, 'quizAttempts'])->name('quiz-attempts');
    Route::get('/quiz-attempts/{quizAttempt}', [AttemptController::class, 'showQuizAttempt'])->name('quiz-attempts.show');
    Route::delete('/quiz-attempts/{quizAttempt}', [AttemptController::class, 'destroyQuizAttempt'])->name('quiz-attempts.destroy');
    Route::get('/quiz-attempts/export', [AttemptController::class, 'exportQuizAttempts'])->name('quiz-attempts.export');
    
    // Test Attempts - Updated to new controller
    Route::get('/test-attempts', [AttemptController::class, 'testAttempts'])->name('test-attempts');
    Route::get('/test-attempts/{testAttempt}', [AttemptController::class, 'showTestAttempt'])->name('test-attempts.show');
    Route::delete('/test-attempts/{testAttempt}', [AttemptController::class, 'destroyTestAttempt'])->name('test-attempts.destroy');
    Route::get('/test-attempts/export', [AttemptController::class, 'exportTestAttempts'])->name('test-attempts.export');
    
    // Notifications - Updated to new controller
    Route::get('/notifications', [NC::class, 'index'])->name('notifications');
    Route::get('/notifications/create', [NC::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [NC::class, 'store'])->name('notifications.store');
    Route::get('/notifications/{notification}', [NC::class, 'show'])->name('notifications.show');
    Route::get('/notifications/{notification}/edit', [NC::class, 'edit'])->name('notifications.edit');
    Route::put('/notifications/{notification}', [NC::class, 'update'])->name('notifications.update');
    Route::delete('/notifications/{notification}', [NC::class, 'destroy'])->name('notifications.destroy');
    Route::patch('/notifications/{notification}/mark-read', [NC::class, 'markAsRead'])->name('notifications.mark-read');
    Route::patch('/users/{user}/notifications/mark-all-read', [NC::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/users/{user}/notifications/unread-count', [NC::class, 'getUnreadCount'])->name('notifications.unread-count');
    
    // Analytics & Reports - Updated to new controller
    Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [AdminDashboardController::class, 'exportReports'])->name('reports.export');
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

Route::get('/forget-password', [ForgetController::class, 'showForgetPasswordForm'])->name('forget-password');
Route::post('/forget-password', [ForgetController::class, 'sendResetLinkEmail'])->name('forget-password.submit');

Route::get('/verification-code', [VerificationCodeController::class, 'showVerificationCodeForm'])->name('verification-code')->middleware('auth');
Route::post('/verification-code', [VerificationCodeController::class, 'verifyCode'])->name('verification-code.submit');

Route::get('/new-password', [NewPasswordController::class, 'showNewPasswordForm'])->name('new-password');
Route::post('/new-password', [NewPasswordController::class, 'resetPassword'])->name('new-password.submit');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');

// Admin routes (protected by admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Contact Management
    Route::get('/contacts', [ContactController::class, 'adminIndex'])->name('contact.index');
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contact.show');
    Route::post('/contacts/{contact}/reply', [ContactController::class, 'reply'])->name('contact.reply');
    Route::patch('/contacts/{contact}/mark-read', [ContactController::class, 'markAsRead'])->name('contact.mark-read');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contact.destroy');
    Route::post('/contacts/bulk-action', [ContactController::class, 'bulkAction'])->name('contact.bulk-action');
    Route::get('/contacts/export', [ContactController::class, 'export'])->name('contact.export');

    // FAQ Management
    Route::get('/faq', [FaqController::class, 'adminIndex'])->name('faq.index');
    Route::get('/faq/create', [FaqController::class, 'create'])->name('faq.create');
    Route::post('/faq', [FaqController::class, 'store'])->name('faq.store');
    Route::get('/faq/{faq}', [FaqController::class, 'show'])->name('faq.show');
    Route::get('/faq/{faq}/edit', [FaqController::class, 'edit'])->name('faq.edit');
    Route::put('/faq/{faq}', [FaqController::class, 'update'])->name('faq.update');
    Route::delete('/faq/{faq}', [FaqController::class, 'destroy'])->name('faq.destroy');
    Route::patch('/faq/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])->name('faq.toggle-status');
    Route::post('/faq/reorder', [FaqController::class, 'reorder'])->name('faq.reorder');
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/completed-action/{userId}', [CompletedActionController::class, 'completedAction'])->name('completed-action');
//     Route::post('/tasks', [CompletedActionController::class, 'store']);
//     Route::patch('/tasks/{id}', [CompletedActionController::class, 'update']);
//     Route::delete('/tasks/{id}', [CompletedActionController::class, 'destroy']);
// });




// Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->middleware('auth')->name('notifications.markAsRead');

// Route::get('/Achievement', [AchievementController::class, 'Achievement'])->name('Achievement');

Route::middleware(['auth'])->group(function () {
    Route::get('/Achievement', [AchievementController::class, 'index'])->name('achievement.index');
   Route::post('/achievement', [AchievementController::class, 'index'])->name('achievement.index');

    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notification.unread-count');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notification.markAsRead');
    Route::delete('/notifications/delete/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
});
Route::middleware(['auth', 'locale'])->group(function () {
    Route::get('/student/notifications', [NotificationController::class, 'index'])->name('student.notifications.show');
});
Route::get('/Achievement-Point', [AchievementPointController::class, 'AchievementPoint'])->name('AchievementPoint');
Route::get('/plan', [PlanController::class, 'Plan'])->name('Plan');
Route::post('/plan/update', [PlanController::class, 'update'])->name('plan.update');
Route::get('/setting', [SettingController::class, 'show'])->name('setting');



Route::middleware(['auth'])->prefix('student')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'show'])->name('student.profile.show');
    // Route::put('/profile', [ProfileController::class, 'update'])->name('student.profile.update');
    Route::put('/profile/image', [ProfileController::class, 'updateImage'])->name('student.profile.update-image');
});
Route::delete('/user/delete', [ProfileController::class, 'destroy'])->name('user.delete')->middleware('auth');


Route::delete('/account/delete', [SettingController::class, 'deleteAccount'])->name('delete-account');

Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [ChangPasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/update-password', [ChangPasswordController::class, 'updatePassword'])->name('password.update');
});



// Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::view("/about", "about")->name('about');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');



// Route::post('/contact', [ContactUsController::class, 'index'])->name('contact.submit');
// Route::post('/contact-us', [ContactUsController::class, 'store'])->name('contactus.store');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/contactus', [ContactUsController::class, 'index'])->name('contactus.index');
});

// Route::get('/contact-us', [ContactUsController::class, 'index'])->name('contact.us')->middleware('auth');

Route::get('/test-verification', function() {
    $user = App\Models\User::first();
    return $user->getKey();
});

Route::prefix('student')->name('student.')->middleware(['auth', 'verified', 'student', 'locale'])->group(function () {

    Route::get('certificates', [SectionsController::class, 'showCertificates'])->name('certificates.index');
    Route::post('certificates/select', [SectionsController::class, 'selectCertificate'])->name('certificates.select');
    

    Route::get('/logo', [LogoController::class, 'index'])->name('index');
    Route::get('/feature', [FeaturesController::class, 'features'])->name('feature');
    Route::get('/welcome', [WelcomeController::class, 'welcome'])->name('welcome');
    Route::get('/splash', [SplashController::class, 'splash'])->name('splash');
    
    Route::get('/intro', [IntroController::class, 'index'])->name('intro.index');
    Route::get('/intro/step/{step}', [IntroController::class, 'step'])->name('intro.step');
    Route::post('/intro/step/{step}', [IntroController::class, 'store'])->name('intro.store');
    Route::get('/intro/complete', [IntroController::class, 'complete'])->name('intro.complete');
    Route::get('/home',[CertificateHomeController::class , 'index'])->name('home');
    // Route::get('/sections', [SectionsController::class, 'index'])->name('sections');
    Route::get('/sections/chapters', [SectionsController::class, 'chapters'])->name('sections.chapters');
    Route::get('/sections/domains', [SectionsController::class, 'domains'])->name('sections.domains');
    Route::get('/sections/chapters/{chapterId}/slides', [SectionsController::class , 'chapterShow'])->name('chapter.slides');
    // Route::get('/sections/doamins/{domainId}/slides', [SectionsController::class , 'domainShow'])->name('domain.slides');
    Route::get('/sections/slides/{slideId}', [SectionsController::class, 'slideShow'])->name('sections.slides');
    Route::post('/slide/attempt', [SectionsController::class, 'recordAttempt'])->name('slide.attempt');

    Route::get('/sections', [SectionsController::class, 'index'])->name('sections.index');
    Route::get('/sections/domains/{domainId}/slides', [SectionsController::class , 'domainShow'])->name('domain.slides');
  
    Route::get('/setting',[SettingController::class, 'show'])->name('setting');
    Route::get('/contact', [ContactUsController::class, 'index'])->name('contact.us');
    Route::post('/contact', [ContactUsController::class, 'store'])->name('contact.store');  
    Route::post('setting/profile/edit', [SettingController::class, 'edit'])->name('profile.update-avatar');
    Route::post('setting/notifications', [SettingController::class, 'update'])->name('settings.notifications');
    Route::get('setting/security' , [SectionsController::class , 'show'])->name('security.show');
    // Route::view('/notifications', 'student.notifications.show')->name('notifications.show');
    // Plan Selection Routes
    Route::get('/plan/selection', [SectionsController::class, 'showPlanSelection'])->name('plan.selection');
    Route::post('/plan/store', [SectionsController::class, 'storePlan'])->name('plan.store');
    Route::get('/achievments', [AchievementController::class, 'index'])->name('achievements');
            Route::Get('/endpoint', [EarnPointsController::class, 'index'])->name('user.point');

    // Settings routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');

    // Profile management
    Route::get('/profile', [SettingController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [SettingController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/avatar', [SettingController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::delete('/profile/avatar', [SettingController::class, 'removeAvatar'])->name('profile.remove-avatar');
        Route::put('/profile/update-image', [SettingController::class, 'updateAvatar'])->name('profile.update-image');

    #certificate 
    Route::get('/certification', [certificationController::class, 'certification'])->name('certification');
    Route::get('/leaderboard/{userId}', [LeaderBoardController::class, 'showLeaderBoard'])->name('leaderboard');
    Route::get('/certificate/download', [CertificationController::class, 'download'])->name('certificate.download');
    Route::get('/certificate/view', [CertificationController::class, 'view'])->name('certificate.view');
    
    // Certificate routes
    Route::get('/certificates/all', [CertificateHomeController::class, 'index'])->name('certificate.index');
    Route::get('/certificates/{certificateId}', [CertificateHomeController::class, 'show'])->name('certificate.show');

    Route::get('/terms-and-conditions', [TermsAndConditionsController::class, 'showTermsAndConditions'])->name('terms.conditions');

    // Security settings
    Route::get('/security', [SettingController::class, 'showSecurity'])->name('security.show');
    Route::put('/security/password', [SettingController::class, 'updatePassword'])->name('security.update-password');
    
    // App settings
    Route::post('/settings/notifications', [SettingController::class, 'updateNotifications'])->name('settings.notifications');

    // User statistics
    Route::get('/stats', [SettingController::class, 'getUserStats'])->name('stats');
    
    // Account deletion
    Route::delete('/account', [SettingController::class, 'deleteAccount'])->name('account.delete');
    
    // Add route alias for delete-account (to match your existing blade template)
    Route::delete('/delete-account', [SettingController::class, 'deleteAccount'])->name('delete-account');

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
