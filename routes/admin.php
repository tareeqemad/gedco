<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;


use App\Http\Controllers\Admin\Site\SiteSettingController;
use App\Http\Controllers\Admin\Site\FooterLinkController;
use App\Http\Controllers\Admin\Site\SocialLinkController;

use App\Http\Controllers\Admin\Site\SliderController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'create'])->name('login');
        Route::post('login', [AuthController::class, 'store'])
            ->middleware('throttle:login')
            ->name('login.post');
    });

    // وصول لوحة التحكم للأدوار الإدارية
    Route::middleware(['auth','role:super-admin|admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware('permission:users.view')->get('/users', [UserController::class, 'index'])->name('users.index');
        Route::middleware('permission:users.create')->get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::middleware('permission:users.create')->post('/users', [UserController::class, 'store'])->name('users.store');
        Route::middleware('permission:users.edit')->get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::middleware('permission:users.edit')->put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::middleware('permission:users.delete')->delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');


        Route::get('/permissions',            [PermissionController::class,'index'])->name('permissions.index');
        Route::get('/permissions/create',     [PermissionController::class,'create'])->name('permissions.create');
        Route::post('/permissions',           [PermissionController::class,'store'])->name('permissions.store');
        Route::get('/permissions/{permission}/edit', [PermissionController::class,'edit'])->name('permissions.edit');
        Route::put('/permissions/{permission}',      [PermissionController::class,'update'])->name('permissions.update');
        Route::delete('/permissions/{permission}',   [PermissionController::class,'destroy'])->name('permissions.destroy');


        Route::middleware('permission:site-settings.edit')->group(function () {
            Route::get('/site-settings/{id}/edit', [SiteSettingController::class, 'edit'])
                ->whereNumber('id')
                ->name('site-settings.edit');

            Route::put('/site-settings/{id}', [SiteSettingController::class, 'update'])
                ->whereNumber('id')
                ->name('site-settings.update');
        });


        Route::resource('sliders', SliderController::class)
            ->names('sliders')
            ->middleware('permission:sliders.view|sliders.create|sliders.edit|sliders.delete');


        //(services/company)
        Route::middleware('permission:footer-links.view')->get('/footer-links', [FooterLinkController::class, 'index'])->name('footer-links.index');
        Route::middleware('permission:footer-links.create')->get('/footer-links/create', [FooterLinkController::class, 'create'])->name('footer-links.create');
        Route::middleware('permission:footer-links.create')->post('/footer-links', [FooterLinkController::class, 'store'])->name('footer-links.store');
        Route::middleware('permission:footer-links.edit')->get('/footer-links/{footer_link}/edit', [FooterLinkController::class, 'edit'])->name('footer-links.edit');
        Route::middleware('permission:footer-links.edit')->put('/footer-links/{footer_link}', [FooterLinkController::class, 'update'])->name('footer-links.update');
        Route::middleware('permission:footer-links.delete')->delete('/footer-links/{footer_link}', [FooterLinkController::class, 'destroy'])->name('footer-links.destroy');
        //
        Route::middleware('permission:social-links.view')->get('/social-links', [SocialLinkController::class, 'index'])->name('social-links.index');
        Route::middleware('permission:social-links.create')->get('/social-links/create', [SocialLinkController::class, 'create'])->name('social-links.create');
        Route::middleware('permission:social-links.create')->post('/social-links', [SocialLinkController::class, 'store'])->name('social-links.store');
        Route::middleware('permission:social-links.edit')->get('/social-links/{social_link}/edit', [SocialLinkController::class, 'edit'])->name('social-links.edit');
        Route::middleware('permission:social-links.edit')->put('/social-links/{social_link}', [SocialLinkController::class, 'update'])->name('social-links.update');
        Route::middleware('permission:social-links.delete')->delete('/social-links/{social_link}', [SocialLinkController::class, 'destroy'])->name('social-links.destroy');

        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');


    });

});
