<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\JobsController;
use App\Http\Controllers\Site\TendersController;

Route::prefix('/')->name('site.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::view('/services', 'site.services.index')->name('services');
    Route::view('/blog', 'site.blog.index')->name('blog');
    Route::view('/contact', 'site.contact.index')->name('contact');
    Route::view('/track', 'site.track.index')->name('track');
    Route::view('/booking', 'site.booking.index')->name('booking');
    Route::view('/about', 'site.about.index')->name('about');
    Route::view('/team', 'site.team.index')->name('team');
    Route::view('/careers', 'site.careers.index')->name('careers');

    // هنا التعديل: الاسم يكون 'certifications' فقط
    Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
    Route::get('/tenders', [TendersController::class, 'index'])->name('tenders');

});

require __DIR__.'/admin.php';
