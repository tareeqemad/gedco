<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\JobsController;
use App\Http\Controllers\Site\TendersController;
use App\Http\Controllers\Site\NewsController;
use App\Http\Controllers\Site\AdvertisementController;
use App\Http\Controllers\Site\SearchController;
use App\Http\Controllers\Staff\ProfileDependents;

Route::prefix('/')->name('site.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/search', [SearchController::class, 'resolve'])->name('search');
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

    Route::view('/services', 'site.services.index')->name('services');
    Route::view('/blog', 'site.blog.index')->name('blog');
    Route::view('/contact', 'site.contact.index')->name('contact');
    Route::view('/track', 'site.track.index')->name('track');
    Route::view('/booking', 'site.booking.index')->name('booking');
    Route::view('/about', 'site.about.index')->name('about');
    Route::view('/team', 'site.team.index')->name('team');
    Route::view('/careers', 'site.careers.index')->name('careers');

    Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
    Route::get('/tenders', [TendersController::class, 'index'])->name('tenders');
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

});

Route::prefix('advertisements')->name('site.advertisements.')->group(function () {
    Route::get('/', [AdvertisementController::class, 'index'])->name('index');
    Route::get('/{id}', [AdvertisementController::class, 'show'])->name('show');
});



Route::prefix('staff/profile')->group(function () {
    Route::get('/dependents',  [ProfileDependents::class, 'create'])
        ->name('staff.profile.dependents.create');   // صفحة الفورم العامة

    Route::post('/dependents', [ProfileDependents::class, 'store'])
        ->name('staff.profile.dependents.store');    // حفظ البيانات
});

require __DIR__.'/admin.php';
