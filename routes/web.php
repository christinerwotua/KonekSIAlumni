<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AlumniDirectoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobReferralController;
use App\Http\Controllers\MentoringController;
use App\Http\Controllers\MentoringRequestController;
use App\Http\Controllers\MentorProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\TalentDocumentController;
use App\Http\Controllers\TalentPoolController;
use App\Http\Controllers\TalentProfileController;
use Illuminate\Support\Facades\Route;

// ---------- Publik ----------
Route::view('/', 'landing')->name('landing');
Route::view('/offline', 'offline')->name('offline');

// ---------- Tamu (belum login) ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

// ---------- Sudah login ----------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/foto/{user}', [PhotoController::class, 'show'])->name('photos.show');

    // Direktori Alumni & Koneksi
    Route::get('/direktori', [AlumniDirectoryController::class, 'index'])->name('alumni.index');
    Route::get('/direktori/{user}', [AlumniDirectoryController::class, 'show'])->name('alumni.show');
    Route::get('/koneksi', [ConnectionController::class, 'index'])->name('connections.index');
    Route::post('/koneksi/{alumni}', [ConnectionController::class, 'store'])->middleware('role:mahasiswa')->name('connections.store');
    Route::patch('/koneksi/{connection}', [ConnectionController::class, 'update'])->middleware('role:alumni')->name('connections.update');
    Route::delete('/koneksi/{connection}', [ConnectionController::class, 'destroy'])->name('connections.destroy');

    // Talent Pool
    Route::get('/talent-pool', [TalentPoolController::class, 'index'])->name('talent.index');
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/talent-pool/profil-saya', [TalentProfileController::class, 'edit'])->name('talent.edit');
        Route::put('/talent-pool/profil-saya', [TalentProfileController::class, 'update'])->name('talent.update');
        Route::post('/talent-pool/dokumen', [TalentDocumentController::class, 'store'])->name('talent.documents.store');
        Route::delete('/talent-pool/dokumen/{document}', [TalentDocumentController::class, 'destroy'])->name('talent.documents.destroy');
    });
    Route::get('/talent-pool/dokumen/{document}', [TalentDocumentController::class, 'download'])->name('talent.documents.download');
    Route::get('/talent-pool/{talent}', [TalentPoolController::class, 'show'])->name('talent.show');

    // Job Referral
    Route::get('/job-referral', [JobReferralController::class, 'index'])->name('jobs.index');
    Route::middleware('role:alumni')->group(function () {
        Route::get('/job-referral/tambah', [JobReferralController::class, 'create'])->name('jobs.create');
        Route::post('/job-referral', [JobReferralController::class, 'store'])->name('jobs.store');
        Route::patch('/job-referral/{job}/status', [JobReferralController::class, 'toggleStatus'])->name('jobs.status');
        Route::patch('/lamaran/{application}', [JobApplicationController::class, 'update'])->name('applications.update');
    });
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/job-referral/{job}/apply', [JobApplicationController::class, 'create'])->name('applications.create');
        Route::post('/job-referral/{job}/apply', [JobApplicationController::class, 'store'])->name('applications.store');
    });
    Route::get('/job-referral/{job}', [JobReferralController::class, 'show'])->name('jobs.show');

    // Mentoring
    Route::get('/mentoring', [MentoringController::class, 'index'])->name('mentoring.index');
    Route::middleware('role:alumni')->group(function () {
        Route::get('/mentoring/profil-mentor', [MentorProfileController::class, 'edit'])->name('mentor-profile.edit');
        Route::put('/mentoring/profil-mentor', [MentorProfileController::class, 'update'])->name('mentor-profile.update');
        Route::patch('/mentoring/pengajuan/{mentoringRequest}', [MentoringRequestController::class, 'respond'])->name('mentoring.respond');
    });
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/mentoring/ajukan/{mentor}', [MentoringRequestController::class, 'create'])->name('mentoring.create');
        Route::post('/mentoring/ajukan/{mentor}', [MentoringRequestController::class, 'store'])->name('mentoring.store');
    });
    Route::get('/mentoring/pengajuan/{mentoringRequest}', [MentoringRequestController::class, 'show'])->name('mentoring.show');

    // Notifikasi
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::get('/notifikasi/{notification}', [NotificationController::class, 'show'])->name('notifications.show');

    // Account Settings
    Route::get('/akun', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/akun', [AccountController::class, 'update'])->name('account.update');
    Route::put('/akun/password', [AccountController::class, 'updatePassword'])->name('account.password');
});
