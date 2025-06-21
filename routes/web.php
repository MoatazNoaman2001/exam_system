<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\AdminController;
// use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\FeaturesController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SplashController;
use App\Http\Controllers\ForgetController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\VerificationCodeController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\CompletedActionController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AchievementPointController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SettingController;

App::setLocale('en');
Route::get('/lang/{lang}' , function ($lang) {
    if (in_array($lang, ['en', 'ar'])) {
        App::setLocale($lang);
        session(['applocale' => $lang]);
    }
    return redirect()->back();
})->name("lang.switch");

Route::get("/" , function (Request $request){ 
   if (Auth::user() != null){
    if (Auth::user()->role == "admin"){
        return redirect()->intended('/admin/dashboard');
    }else{
        return view("home");
    }
   }
   return view('welcome'); 
});

Route::view("/home", "home")->middleware('auth')
    ->middleware(['auth', 'verified'])
    ->name('home');
// Route::get('/admin', [DashboardController::class, 'index'])
//     ->middleware(['auth', 'verified', 'admin'])
//     ->name('admin.dashboard');
// Admin Routes Group
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    
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
Route::get('/forget-password', [ForgetController::class, 'forgetPassword'])->name('forget-password');
Route::get('/verificationCode', [VerificationCodeController::class, 'verificationCode'])->name('verificationCode');
Route::get('/newPassword', [NewPasswordController::class, 'NewPassword'])->name('NewPassword');
Route::get('/completedAction', [CompletedActionController::class, 'completedAction'])->name('completedAction');
Route::get('/Achievement', [AchievementController::class, 'Achievement'])->name('Achievement');
Route::get('/Achievement-Point', [AchievementPointController::class, 'AchievementPoint'])->name('AchievementPoint');
Route::get('/Plan', [PlanController::class, 'Plan'])->name('Plan');
Route::post('/plan/update', [PlanController::class, 'update'])->name('plan.update');
Route::get('/setting', [SettingController::class, 'Setting'])->name('setting');


Route::get('/test-verification', function() {
    $user = App\Models\User::first();
    return $user->getKey(); // Should return the user's ID
});


