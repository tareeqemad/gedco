<?php

use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Site\WhyChooseUsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\Site\AboutUsController;
use App\Http\Controllers\Admin\Site\ImpactStatController;
use App\Http\Controllers\Admin\Site\SiteSettingController;
use App\Http\Controllers\Admin\Site\FooterLinkController;
use App\Http\Controllers\Admin\Site\SocialLinkController;
use App\Http\Controllers\Admin\Site\SliderController;
use App\Http\Controllers\Admin\RoleController; // تم إضافته

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

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // === المستخدمين ===
        Route::middleware('permission:users.view')->get('/users', [UserController::class, 'index'])->name('users.index');
        Route::middleware('permission:users.create')->get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::middleware('permission:users.create')->post('/users', [UserController::class, 'store'])->name('users.store');
        Route::middleware('permission:users.edit')->get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::middleware('permission:users.edit')->put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::middleware('permission:users.delete')->delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // === إعدادات الموقع ===
        Route::middleware('permission:site-settings.edit')->group(function () {
            Route::get('/site-settings/{id}/edit', [SiteSettingController::class, 'edit'])
                ->whereNumber('id')
                ->name('site-settings.edit');
            Route::put('/site-settings/{id}', [SiteSettingController::class, 'update'])
                ->whereNumber('id')
                ->name('site-settings.update');
        });

        // === السلايدر ===
        Route::resource('sliders', SliderController::class)
            ->names('sliders')
            ->middleware('permission:sliders.view|sliders.create|sliders.edit|sliders.delete');

        Route::delete('sliders/{slider}/remove-image', [SliderController::class, 'deactivateImage'])
            ->name('sliders.remove-image');
        // === الوظائف ===
        Route::resource('jobs', JobController::class)
            ->names('jobs')
            ->middleware('permission:jobs.view|jobs.create|jobs.edit|jobs.delete');

        // === من نحن ===
        Route::resource('about', AboutUsController::class)
            ->names('about')
            ->except(['show'])
            ->middleware('permission:about.view|about.create|about.edit|about.delete');
        Route::delete('about/{about}/remove-image', [AboutUsController::class, 'removeImage'])
            ->name('about.remove-image');


        // === لماذا تختارنا ===
        Route::resource('why', WhyChooseUsController::class)
            ->except(['show'])
            ->names('why')
            ->middleware('permission:why.view|why.create|why.edit|why.delete');

        // === احصائيات ===
         Route::resource('impact-stats', ImpactStatController::class)
            ->except(['show'])
            ->middleware([
                'permission:impact-stats.view',
                'permission:impact-stats.create',
                'permission:impact-stats.edit',
                'permission:impact-stats.delete'
            ])
            ->names([
                'index'   => 'impact-stats.index',
                'create'  => 'impact-stats.create',
                'store'   => 'impact-stats.store',
                'edit'    => 'impact-stats.edit',
                'update'  => 'impact-stats.update',
                'destroy' => 'impact-stats.destroy',
            ]);

        Route::patch('impact-stats/{impactStat}/toggle', [ImpactStatController::class, 'toggle'])
            ->name('impact-stats.toggle')
            ->middleware('permission:impact-stats.edit');

        Route::post('impact-stats/reorder', [ImpactStatController::class, 'reorder'])
            ->name('impact-stats.reorder')
            ->middleware('permission:impact-stats.edit');



        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

        // === الأقسام الحصرية للـ super-admin فقط ===
        Route::middleware('role:super-admin')->group(function () {

            // Roles
            Route::resource('roles', RoleController::class)->except(['show']);

            // Permissions
            Route::get('/permissions', [PermissionController::class,'index'])->name('permissions.index');
            Route::get('/permissions/create', [PermissionController::class,'create'])->name('permissions.create');
            Route::post('/permissions', [PermissionController::class,'store'])->name('permissions.store');
            Route::get('/permissions/{permission}/edit', [PermissionController::class,'edit'])->name('permissions.edit');
            Route::put('/permissions/{permission}', [PermissionController::class,'update'])->name('permissions.update');
            Route::delete('/permissions/{permission}', [PermissionController::class,'destroy'])->name('permissions.destroy');

            // Footer Links
            Route::get('/footer-links', [FooterLinkController::class, 'index'])->name('footer-links.index');
            Route::get('/footer-links/create', [FooterLinkController::class, 'create'])->name('footer-links.create');
            Route::post('/footer-links', [FooterLinkController::class, 'store'])->name('footer-links.store');
            Route::get('/footer-links/{footer_link}/edit', [FooterLinkController::class, 'edit'])->name('footer-links.edit');
            Route::put('/footer-links/{footer_link}', [FooterLinkController::class, 'update'])->name('footer-links.update');
            Route::delete('/footer-links/{footer_link}', [FooterLinkController::class, 'destroy'])->name('footer-links.destroy');

            // Social Links
            Route::get('/social-links', [SocialLinkController::class, 'index'])->name('social-links.index');
            Route::get('/social-links/create', [SocialLinkController::class, 'create'])->name('social-links.create');
            Route::post('/social-links', [SocialLinkController::class, 'store'])->name('social-links.store');
            Route::get('/social-links/{social_link}/edit', [SocialLinkController::class, 'edit'])->name('social-links.edit');
            Route::put('/social-links/{social_link}', [SocialLinkController::class, 'update'])->name('social-links.update');
            Route::delete('/social-links/{social_link}', [SocialLinkController::class, 'destroy'])->name('social-links.destroy');
        });
    });
});
