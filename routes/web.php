<?php
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\StudentRegisterController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\Auth\StudentForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SchoolYearSemesterController;
use App\Http\Controllers\Student\ScheduleController as StudentScheduleController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Models\Student;
use Illuminate\Support\Facades\DB;


Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return "✅ Database Connected Successfully!";
    } catch (\Exception $e) {
        return "❌ Database Connection Failed: " . $e->getMessage();
    }
});

// Email verification routes
Route::get('/email/verify/{id}/{hash}', [LoginController::class, 'verifyEmail'])
    // Remove the signed middleware for now
    ->name('verification.verify');

Route::post('/email/verification-notification', [LoginController::class, 'resendVerificationEmail'])
    ->middleware('throttle:6,1')
    ->name('verification.send');

// Landing Page
Route::get('/', function () {
    if (Auth::guard('web')->check()) {
        return redirect()->route('admin.dashboard');
    }

    if (Auth::guard('student')->check()) {
        return redirect()->route('student.dashboard');
    }

    return redirect()->route('login');
});

// Authentication Routes for Guests
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [StudentRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [StudentRegisterController::class, 'register']);

   
});

Route::get('/forgot-password', [StudentForgotPasswordController::class, 'showForgotPasswordForm'])->name('student.password.request');
Route::post('/forgot-password', [StudentForgotPasswordController::class, 'sendResetLink'])->name('student.password.email');

Route::get('/reset-password/{token}', [StudentForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');  

Route::post('/reset-password', [StudentForgotPasswordController::class, 'resetPassword'])->name('student.password.update');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth:web'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Schedule Management
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::get('/schedules/pending', [ScheduleController::class, 'pendingSchedules'])->name('admin.schedules.pending');
    Route::get('/schedules/today', [ScheduleController::class, 'todaySchedules'])->name('admin.schedules.today');
    Route::get('/schedules/weekly', [ScheduleController::class, 'weeklySchedules'])->name('admin.schedules.weekly');
    Route::patch('/schedules/{schedule}/approve', [ScheduleController::class, 'approve'])->name('schedules.approve');
    Route::patch('/schedules/{schedule}/reject', [ScheduleController::class, 'reject'])->name('schedules.reject');
    Route::get('/api/schedule/weekly', [ScheduleController::class, 'getWeeklySchedule'])->name('api.schedule.weekly');
    Route::get('/schedules/date-range', [ScheduleController::class, 'getSchedulesByDateRange'])->name('admin.schedules.date-range');

    
    // Report Management
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPDF'])->name('admin.reports.export');
    Route::get('/reports/student/{id}', [ReportController::class, 'studentReport'])->name('admin.reports.student');


    // Student Management
    Route::resource('students', StudentController::class)->names([
        'index' => 'admin.students.index',
        'create' => 'admin.students.create',
        'store' => 'admin.students.store',
        'edit' => 'admin.students.edit',
        'update' => 'admin.students.update',
        'destroy' => 'admin.students.destroy',
    ]);

    // File Management
    Route::resource('files', FileController::class)->names([
        'index' => 'admin.files.index',
        'create' => 'admin.files.create',
        'store' => 'admin.files.store',
        'edit' => 'admin.files.edit',
        'update' => 'admin.files.update',
        'destroy' => 'admin.files.destroy',
    ]);
    

    // Settings Management
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

   // School Year
    Route::get('/school-years-semesters', [SchoolYearSemesterController::class, 'index'])->name('admin.school-years-semesters.index');
    Route::post('/school-years', [SchoolYearSemesterController::class, 'storeSchoolYear'])->name('admin.school-years.store');
    Route::delete('/admin/school-years/{id}', [SchoolYearSemesterController::class, 'destroySchoolYear'])->name('admin.school-years.destroy');
    // Semester Routes
    Route::post('/semesters', [SchoolYearSemesterController::class, 'storeSemester'])->name('admin.semesters.store');
    Route::delete('/admin/semesters/{id}', [SchoolYearSemesterController::class, 'destroySemester'])->name('admin.semesters.destroy');

    // Admin Registration (Only for other admins)
    Route::get('/register', [AdminRegisterController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('/register', [AdminRegisterController::class, 'register']);
});

    // Student Routes
    Route::middleware(['auth:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/schedule', [StudentScheduleController::class, 'create'])->name('student.schedules.create');
    Route::post('/schedule', [StudentScheduleController::class, 'store'])->name('student.schedules.store');
    Route::get('/profile', [StudentProfileController::class, 'show'])->name('student.profile.show');
    Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('student.profile.edit');
    Route::put('/profile', [StudentProfileController::class, 'update'])->name('student.profile.update');
    Route::get('/student/history', [App\Http\Controllers\Student\DashboardController::class, 'studentHistory'])->name('student.history');
    Route::post('/student/schedules/{id}/cancel', [StudentScheduleController::class, 'cancelRequest'])->name('student.schedules.cancel');});
    
/* Route::get('/debug-log', function() {
    return response()->file(storage_path('logs/laravel.log'));
}); */


/* Route::get('/debug', function() {
    dd([
        'student_guard' => Auth::guard('student')->check(),
        'web_guard' => Auth::guard('web')->check(),
        'student_user' => Auth::guard('student')->user(),
    ]);
}); */        