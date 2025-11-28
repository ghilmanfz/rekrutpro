<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicJobController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\AuditController;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\SuperAdmin\MasterDataController;
use App\Http\Controllers\SuperAdmin\ConfigurationController;
use App\Http\Controllers\HR\DashboardController as HRDashboardController;
use App\Http\Controllers\HR\JobPostingController;
use App\Http\Controllers\HR\ApplicationController;
use App\Http\Controllers\HR\InterviewController;
use App\Http\Controllers\HR\OfferController;
use App\Http\Controllers\Candidate\DashboardController as CandidateDashboardController;
use App\Http\Controllers\Candidate\ApplicationController as CandidateApplicationController;
use App\Http\Controllers\Candidate\ProfileController as CandidateProfileController;
use App\Http\Controllers\Candidate\NotificationController as CandidateNotificationController;
use App\Http\Controllers\Interviewer\DashboardController as InterviewerDashboardController;
use App\Http\Controllers\Interviewer\AssessmentController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [PublicJobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}', [PublicJobController::class, 'show'])->name('jobs.show');

// Main Dashboard - Redirect based on role
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Super Admin Routes
Route::middleware(['auth', 'super.admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', UserManagementController::class);
    Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');
    
    // Master Data
    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data.index');
    Route::post('/divisions', [MasterDataController::class, 'storeDivision'])->name('divisions.store');
    Route::put('/divisions/{division}', [MasterDataController::class, 'updateDivision'])->name('divisions.update');
    Route::delete('/divisions/{division}', [MasterDataController::class, 'destroyDivision'])->name('divisions.destroy');
    Route::post('/positions', [MasterDataController::class, 'storePosition'])->name('positions.store');
    Route::put('/positions/{position}', [MasterDataController::class, 'updatePosition'])->name('positions.update');
    Route::delete('/positions/{position}', [MasterDataController::class, 'destroyPosition'])->name('positions.destroy');
    Route::post('/locations', [MasterDataController::class, 'storeLocation'])->name('locations.store');
    Route::put('/locations/{location}', [MasterDataController::class, 'updateLocation'])->name('locations.update');
    Route::delete('/locations/{location}', [MasterDataController::class, 'destroyLocation'])->name('locations.destroy');
    
    // Configuration
    Route::get('/config', [ConfigurationController::class, 'index'])->name('config.index');
    Route::post('/templates', [ConfigurationController::class, 'storeTemplate'])->name('templates.store');
    Route::put('/templates/{template}', [ConfigurationController::class, 'updateTemplate'])->name('templates.update');
    Route::delete('/templates/{template}', [ConfigurationController::class, 'destroyTemplate'])->name('templates.destroy');
    
    // Audit & Reports
    Route::get('/audit', [AuditController::class, 'index'])->name('audit');
    Route::get('/audit/export', [AuditController::class, 'export'])->name('audit.export');
});

// HR Routes
Route::middleware(['auth', 'hr'])->prefix('hr')->name('hr.')->group(function () {
    Route::get('/dashboard', [HRDashboardController::class, 'index'])->name('dashboard');
    
    // Job Postings
    Route::resource('job-postings', JobPostingController::class);
    
    // Applications
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::put('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.update-status');
    Route::get('/applications/export', [ApplicationController::class, 'export'])->name('applications.export');
    
    // Interviews
    Route::get('/interviews', [\App\Http\Controllers\HR\InterviewController::class, 'index'])->name('interviews.index');
    Route::get('/interviews/{interview}', [\App\Http\Controllers\HR\InterviewController::class, 'show'])->name('interviews.show');
    Route::post('/interviews', [\App\Http\Controllers\HR\InterviewController::class, 'store'])->name('interviews.store');
    Route::put('/interviews/{interview}', [\App\Http\Controllers\HR\InterviewController::class, 'update'])->name('interviews.update');
    Route::delete('/interviews/{interview}', [\App\Http\Controllers\HR\InterviewController::class, 'destroy'])->name('interviews.destroy');
    
    // Offers
    Route::get('/offers', [\App\Http\Controllers\HR\OfferController::class, 'index'])->name('offers.index');
    Route::get('/offers/{offer}', [\App\Http\Controllers\HR\OfferController::class, 'show'])->name('offers.show');
    Route::post('/offers', [\App\Http\Controllers\HR\OfferController::class, 'store'])->name('offers.store');
    Route::put('/offers/{offer}', [\App\Http\Controllers\HR\OfferController::class, 'update'])->name('offers.update');
});

// Candidate Routes
Route::middleware(['auth', 'candidate'])->prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/dashboard', [CandidateDashboardController::class, 'index'])->name('dashboard');
    
    // Applications
    Route::get('/applications', [CandidateApplicationController::class, 'index'])->name('applications.index');
    Route::get('/jobs/{job}/apply', [CandidateApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications', [CandidateApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/{application}', [CandidateApplicationController::class, 'show'])->name('applications.show');
    
    // Profile
    Route::get('/profile', [CandidateProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [CandidateProfileController::class, 'update'])->name('profile.update');
    
    // Notifications
    Route::get('/notifications', [CandidateNotificationController::class, 'index'])->name('notifications');
});

// Interviewer Routes
Route::middleware(['auth', 'interviewer'])->prefix('interviewer')->name('interviewer.')->group(function () {
    Route::get('/dashboard', [InterviewerDashboardController::class, 'index'])->name('dashboard');
    
    // Interviews & Assessments
    Route::get('/interviews', [\App\Http\Controllers\Interviewer\InterviewController::class, 'index'])->name('interviews.index');
    Route::get('/interviews/{interview}', [AssessmentController::class, 'show'])->name('interviews.show');
    Route::post('/interviews/{interview}/assessment', [AssessmentController::class, 'store'])->name('assessments.store');
    
    // Assessments
    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
    Route::get('/assessments/{assessment}', [AssessmentController::class, 'showAssessment'])->name('assessments.show');
});

require __DIR__.'/auth.php';
