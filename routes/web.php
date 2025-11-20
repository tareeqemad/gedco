<?php


use App\Http\Controllers\Staff\ProfileDependentsController;
use App\Http\Controllers\Staff\ProfileEditAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\JobsController;
use App\Http\Controllers\Site\TendersController;
use App\Http\Controllers\Site\NewsController;
use App\Http\Controllers\Site\AdvertisementController;
use App\Http\Controllers\Site\SearchController;

Route::prefix('/')->name('site.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/search', [SearchController::class, 'resolve'])->name('search');
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');



    Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
    Route::get('/tenders', [TendersController::class, 'index'])->name('tenders');
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

});

Route::view('/general-manager-message', 'site.general-manager-message')->name('company.general-manager-message');

Route::prefix('advertisements')->name('site.advertisements.')->group(function () {
    Route::get('/', [AdvertisementController::class, 'index'])->name('index');
    Route::get('/{id}', [AdvertisementController::class, 'show'])->name('show');
});


Route::prefix('staff/profile')->name('staff.profile.')->group(function () {

    // إنشاء
    Route::get('create', [ProfileDependentsController::class, 'create'])->name('create');
    Route::post('store', [ProfileDependentsController::class, 'store'])
        ->middleware('throttle:10,1')->name('store');

    Route::get('lookup', [ProfileDependentsController::class, 'lookup'])
        ->middleware('throttle:20,1')->name('lookup');

    // التحقق برقم الهوية
    Route::get('verify', [ProfileEditAuthController::class, 'showVerifyForm'])
        ->name('verify.form');

    Route::post('verify', [ProfileEditAuthController::class, 'verify'])
        ->middleware('throttle:10,1')->name('verify');

    // عرض
    Route::get('{profile}', [ProfileDependentsController::class, 'show'])
        ->whereNumber('profile')->name('show');

    // تعديل (محمي بجلسة التحقق)
    Route::middleware('staff.edit.session')->group(function () {
        Route::get('{profile}/edit',   [ProfileDependentsController::class, 'edit'])
            ->whereNumber('profile')->name('edit');

        Route::put('{profile}/update', [ProfileDependentsController::class, 'update'])
            ->whereNumber('profile')->name('update');
    });
});


require __DIR__.'/admin.php';
