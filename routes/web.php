<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\StudentRegisterController;
use App\Http\Controllers\Auth\AdminRegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Student\ScheduleController as StudentScheduleController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Admin\ReportController;

// Landing Page
Route::get('/', [LoginController::class, 'index'])->name('landing.page');

// Authentication Routes for Guests
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [StudentRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [StudentRegisterController::class, 'register']);
});

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

    // Semester Management
    /* Route::get('/semesters', [SemesterController::class, 'index'])->name('admin.semesters.index');
    Route::post('/semesters', [SemesterController::class, 'store'])->name('admin.semesters.store');
    Route::put('/semesters/{semester}', [SemesterController::class, 'update'])->name('admin.semesters.update');
    Route::delete('/semesters/{semester}', [SemesterController::class, 'destroy'])->name('admin.semesters.destroy');
 */
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
});


    Route::get('/debug-student-auth', function() {
        return response()->json([
            'session_id' => session()->getId(),
            'is_authenticated' => Auth::guard('student')->check(),
            'user' => Auth::guard('student')->user(),
            'session_data' => session()->all()
        ]);
    });