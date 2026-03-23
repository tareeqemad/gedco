<?php

use App\Http\Controllers\Admin\NewsController;
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
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\TenderController;
use App\Http\Controllers\Admin\Site\SiteSettingController;
use App\Http\Controllers\Admin\Site\FooterLinkController;
use App\Http\Controllers\Admin\Site\SocialLinkController;
use App\Http\Controllers\Admin\Site\SliderController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\HomeVideoController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\Staff\StaffProfileController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ContactMessageController;


Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'create'])->name('login');
        Route::post('login', [AuthController::class, 'store'])
            ->middleware('throttle:login')
            ->name('login.post');
    });

    // وصول لوحة التحكم للأدوار الإدارية
    Route::middleware(['auth','role:super-admin|admin|editor|viewer'])->group(function () {
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

        // Routes for password management (super-admin only)
        Route::middleware('role:super-admin')->group(function () {
            Route::post('/users/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update-password');
            Route::get('/users/{user}/show-temporary-password', [UserController::class, 'showTemporaryPassword'])->name('users.show-temporary-password');
            Route::post('/users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
            Route::post('/users/stop-impersonating', [UserController::class, 'stopImpersonating'])->name('users.stop-impersonating');
        });

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
        Route::middleware('permission:sliders.view')->get('/sliders', [SliderController::class, 'index'])->name('sliders.index');
        Route::middleware('permission:sliders.create')->get('/sliders/create', [SliderController::class, 'create'])->name('sliders.create');
        Route::middleware('permission:sliders.create')->post('/sliders', [SliderController::class, 'store'])->name('sliders.store');
        Route::middleware('permission:sliders.view')->get('/sliders/{slider}', [SliderController::class, 'show'])->name('sliders.show');
        Route::middleware('permission:sliders.edit')->get('/sliders/{slider}/edit', [SliderController::class, 'edit'])->name('sliders.edit');
        Route::middleware('permission:sliders.edit')->put('/sliders/{slider}', [SliderController::class, 'update'])->name('sliders.update');
        Route::middleware('permission:sliders.delete')->delete('/sliders/{slider}', [SliderController::class, 'destroy'])->name('sliders.destroy');
        Route::middleware('permission:sliders.delete')->delete('/sliders/{slider}/remove-image', [SliderController::class, 'deactivateImage'])->name('sliders.remove-image');

        // === الوظائف ===
        Route::middleware('permission:jobs.view')->get('/jobs', [JobController::class, 'index'])->name('jobs.index');
        Route::middleware('permission:jobs.create')->get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
        Route::middleware('permission:jobs.create')->post('/jobs', [JobController::class, 'store'])->name('jobs.store');
        Route::middleware('permission:jobs.view')->get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
        Route::middleware('permission:jobs.edit')->get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit');
        Route::middleware('permission:jobs.edit')->put('/jobs/{job}', [JobController::class, 'update'])->name('jobs.update');
        Route::middleware('permission:jobs.delete')->delete('/jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');

        // === من نحن ===
        Route::middleware('permission:about.view')->get('/about', [AboutUsController::class, 'index'])->name('about.index');
        Route::middleware('permission:about.create')->get('/about/create', [AboutUsController::class, 'create'])->name('about.create');
        Route::middleware('permission:about.create')->post('/about', [AboutUsController::class, 'store'])->name('about.store');
        Route::middleware('permission:about.edit')->get('/about/{about}/edit', [AboutUsController::class, 'edit'])->name('about.edit');
        Route::middleware('permission:about.edit')->put('/about/{about}', [AboutUsController::class, 'update'])->name('about.update');
        Route::middleware('permission:about.delete')->delete('/about/{about}', [AboutUsController::class, 'destroy'])->name('about.destroy');
        Route::middleware('permission:about.edit')->delete('/about/{about}/remove-image', [AboutUsController::class, 'removeImage'])->name('about.remove-image');

        // === لماذا تختارنا ===
        Route::middleware('permission:why.view')->get('/why', [WhyChooseUsController::class, 'index'])->name('why.index');
        Route::middleware('permission:why.create')->get('/why/create', [WhyChooseUsController::class, 'create'])->name('why.create');
        Route::middleware('permission:why.create')->post('/why', [WhyChooseUsController::class, 'store'])->name('why.store');
        Route::middleware('permission:why.edit')->get('/why/{why}/edit', [WhyChooseUsController::class, 'edit'])->name('why.edit');
        Route::middleware('permission:why.edit')->put('/why/{why}', [WhyChooseUsController::class, 'update'])->name('why.update');
        Route::middleware('permission:why.delete')->delete('/why/{why}', [WhyChooseUsController::class, 'destroy'])->name('why.destroy');

        // === احصائيات ===
        Route::middleware('permission:impact-stats.view')->get('/impact-stats', [ImpactStatController::class, 'index'])->name('impact-stats.index');
        Route::middleware('permission:impact-stats.create')->get('/impact-stats/create', [ImpactStatController::class, 'create'])->name('impact-stats.create');
        Route::middleware('permission:impact-stats.create')->post('/impact-stats', [ImpactStatController::class, 'store'])->name('impact-stats.store');
        Route::middleware('permission:impact-stats.edit')->get('/impact-stats/{impact_stat}/edit', [ImpactStatController::class, 'edit'])->name('impact-stats.edit');
        Route::middleware('permission:impact-stats.edit')->put('/impact-stats/{impact_stat}', [ImpactStatController::class, 'update'])->name('impact-stats.update');
        Route::middleware('permission:impact-stats.delete')->delete('/impact-stats/{impact_stat}', [ImpactStatController::class, 'destroy'])->name('impact-stats.destroy');
        Route::middleware('permission:impact-stats.edit')->patch('/impact-stats/{impactStat}/toggle', [ImpactStatController::class, 'toggle'])->name('impact-stats.toggle');
        Route::middleware('permission:impact-stats.edit')->post('/impact-stats/reorder', [ImpactStatController::class, 'reorder'])->name('impact-stats.reorder');

        // === الإعلانات ===
        Route::middleware('permission:advertisements.view')->get('/advertisements', [AdvertisementController::class, 'index'])->name('advertisements.index');
        Route::middleware('permission:advertisements.create')->get('/advertisements/create', [AdvertisementController::class, 'create'])->name('advertisements.create');
        Route::middleware('permission:advertisements.create')->post('/advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store');
        Route::middleware('permission:advertisements.view')->get('/advertisements/{ID_ADVER}', [AdvertisementController::class, 'show'])->name('advertisements.show');
        Route::middleware('permission:advertisements.edit')->get('/advertisements/{ID_ADVER}/edit', [AdvertisementController::class, 'edit'])->name('advertisements.edit');
        Route::middleware('permission:advertisements.edit')->put('/advertisements/{ID_ADVER}', [AdvertisementController::class, 'update'])->name('advertisements.update');
        Route::middleware('permission:advertisements.delete')->delete('/advertisements/{ID_ADVER}', [AdvertisementController::class, 'destroy'])->name('advertisements.destroy');
        Route::middleware('permission:advertisements.view')->get('/advertisements/{ad}/pdf', [AdvertisementController::class, 'pdf'])->name('advertisements.pdf');

        // === المناقصات ===
        Route::middleware('permission:tenders.view')->get('/tenders', [TenderController::class, 'index'])->name('tenders.index');
        Route::middleware('permission:tenders.create')->get('/tenders/create', [TenderController::class, 'create'])->name('tenders.create');
        Route::middleware('permission:tenders.create')->post('/tenders', [TenderController::class, 'store'])->name('tenders.store');
        Route::middleware('permission:tenders.view')->get('/tenders/{id}', [TenderController::class, 'show'])->name('tenders.show');
        Route::middleware('permission:tenders.edit')->get('/tenders/{id}/edit', [TenderController::class, 'edit'])->name('tenders.edit');
        Route::middleware('permission:tenders.edit')->put('/tenders/{id}', [TenderController::class, 'update'])->name('tenders.update');
        Route::middleware('permission:tenders.delete')->delete('/tenders/{id}', [TenderController::class, 'destroy'])->name('tenders.destroy');

        // === رفع الصور (Quill Editor) ===
        Route::middleware('permission:news.create|news.edit')->post('/uploads/quill-image', [UploadController::class, 'quillImage'])->name('uploads.quill-image');
        Route::middleware('permission:advertisements.create|advertisements.edit')->post('/uploads/quill-image/ads', [UploadController::class, 'quillImageAds'])->name('admin.uploads.quill-image.ads');

        // === الأخبار ===
        Route::middleware('permission:news.view')->get('/news', [NewsController::class, 'index'])->name('news.index');
        Route::middleware('permission:news.create')->get('/news/create', [NewsController::class, 'create'])->name('news.create');
        Route::middleware('permission:news.create')->post('/news', [NewsController::class, 'store'])->name('news.store');
        Route::middleware('permission:news.view')->get('/news/{news}', [NewsController::class, 'show'])->name('news.show');
        Route::middleware('permission:news.edit')->get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::middleware('permission:news.edit')->put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
        Route::middleware('permission:news.delete')->delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');

        // === رسائل الاتصال ===
        Route::middleware('permission:contact-messages.view')->get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::middleware('permission:contact-messages.view')->get('/contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
        Route::middleware('permission:contact-messages.edit')->patch('/contact-messages/{contactMessage}/mark-read', [ContactMessageController::class, 'markAsRead'])->name('contact-messages.mark-read');
        Route::middleware('permission:contact-messages.edit')->patch('/contact-messages/{contactMessage}/mark-unread', [ContactMessageController::class, 'markAsUnread'])->name('contact-messages.mark-unread');
        Route::middleware('permission:contact-messages.delete')->delete('/contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

        // === فيديو الصفحة الرئيسية ===
        Route::middleware('permission:home-video.edit')->get('/home-video', [HomeVideoController::class, 'edit'])->name('homeVideo.edit');
        Route::middleware('permission:home-video.edit')->put('/home-video', [HomeVideoController::class, 'update'])->name('homeVideo.update');

        // === ملفات الموظفين ===
        Route::middleware('permission:staff-profiles.view')->get('/staff-profiles', [StaffProfileController::class, 'index'])->name('staff-profiles.index');
        Route::middleware('permission:staff-profiles.view')->get('/staff-profiles/export', [StaffProfileController::class, 'export'])->name('staff-profiles.export');
        Route::middleware('permission:staff-profiles.view')->get('/staff-profiles/{profile}', [StaffProfileController::class, 'show'])->name('staff-profiles.show');
        Route::middleware('permission:staff-profiles.edit')->get('/staff-profiles/{profile}/edit', [StaffProfileController::class, 'edit'])->name('staff-profiles.edit');
        Route::middleware('permission:staff-profiles.edit')->put('/staff-profiles/{profile}', [StaffProfileController::class, 'update'])->name('staff-profiles.update');

        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

        // === الأدوار (super-admin permissions) ===
        Route::middleware('permission:roles.view')->get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::middleware('permission:roles.create')->get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::middleware('permission:roles.create')->post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::middleware('permission:roles.edit')->get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::middleware('permission:roles.edit')->put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::middleware('permission:roles.delete')->delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        // === الصلاحيات ===
        Route::middleware('permission:permissions.view')->get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::middleware('permission:permissions.create')->get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::middleware('permission:permissions.create')->post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::middleware('permission:permissions.edit')->get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::middleware('permission:permissions.edit')->put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::middleware('permission:permissions.delete')->delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

        // === روابط الفوتر ===
        Route::middleware('permission:footer-links.view')->get('/footer-links', [FooterLinkController::class, 'index'])->name('footer-links.index');
        Route::middleware('permission:footer-links.create')->get('/footer-links/create', [FooterLinkController::class, 'create'])->name('footer-links.create');
        Route::middleware('permission:footer-links.create')->post('/footer-links', [FooterLinkController::class, 'store'])->name('footer-links.store');
        Route::middleware('permission:footer-links.edit')->get('/footer-links/{footer_link}/edit', [FooterLinkController::class, 'edit'])->name('footer-links.edit');
        Route::middleware('permission:footer-links.edit')->put('/footer-links/{footer_link}', [FooterLinkController::class, 'update'])->name('footer-links.update');
        Route::middleware('permission:footer-links.delete')->delete('/footer-links/{footer_link}', [FooterLinkController::class, 'destroy'])->name('footer-links.destroy');

        // === روابط التواصل الاجتماعي ===
        Route::middleware('permission:social-links.view')->get('/social-links', [SocialLinkController::class, 'index'])->name('social-links.index');
        Route::middleware('permission:social-links.create')->get('/social-links/create', [SocialLinkController::class, 'create'])->name('social-links.create');
        Route::middleware('permission:social-links.create')->post('/social-links', [SocialLinkController::class, 'store'])->name('social-links.store');
        Route::middleware('permission:social-links.edit')->get('/social-links/{social_link}/edit', [SocialLinkController::class, 'edit'])->name('social-links.edit');
        Route::middleware('permission:social-links.edit')->put('/social-links/{social_link}', [SocialLinkController::class, 'update'])->name('social-links.update');
        Route::middleware('permission:social-links.delete')->delete('/social-links/{social_link}', [SocialLinkController::class, 'destroy'])->name('social-links.destroy');

        // === سجل الأنشطة ===
        Route::middleware('permission:activity-logs.view')->get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::middleware('permission:activity-logs.view')->get('/activity-logs/active-users', [ActivityLogController::class, 'activeUsers'])->name('activity-logs.active-users');
        Route::middleware('permission:activity-logs.view')->get('/activity-logs/user/{user}', [ActivityLogController::class, 'userActivity'])->name('activity-logs.user');
    });
});
