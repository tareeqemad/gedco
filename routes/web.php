<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\ProfileController;


Route::prefix('/')->name('site.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // لو هتفعل الصفحات دي لاحقًا:
    Route::view('/services', 'site.services.index')->name('services');
    Route::view('/blog', 'site.blog.index')->name('blog');
    Route::view('/contact', 'site.contact.index')->name('contact');
    Route::view('/track', 'site.track.index')->name('track');
    Route::view('/booking', 'site.booking.index')->name('booking');
    Route::view('/about', 'site.about.index')->name('about');
    Route::view('/team', 'site.team.index')->name('team');
    Route::view('/careers', 'site.careers.index')->name('careers');
    Route::view('/certifications', 'site.certifications.index')->name('certifications');
});

// تضمين ملفات المسارات الإضافية
require __DIR__.'/admin.php';  // مسارات لوحة التحكم
