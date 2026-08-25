<?php

use App\Http\Controllers\LanguageController;
// =============================================
//  Language Switcher
// =============================================
use Illuminate\Support\Facades\Route;

Route::get('/lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');

// =============================================
//  NEW STORE FRONTEND (الواجهة الرئيسية)
// =============================================
use App\Http\Controllers\NewStore\BlogController as NewBlogController;
use App\Http\Controllers\NewStore\BookingController as NewBookingController;
use App\Http\Controllers\NewStore\CalculatorController as NewCalculatorController;
use App\Http\Controllers\NewStore\CarController as NewCarController;
use App\Http\Controllers\NewStore\CompareController as NewCompareController;
use App\Http\Controllers\NewStore\ContactController as NewContactController;
use App\Http\Controllers\NewStore\HomeController as NewHomeController;
use App\Http\Controllers\NewStore\OfferController as NewOfferController;

Route::name('new.')->group(function () {
    // الصفحة الرئيسية
    Route::get('/', [NewHomeController::class,      'index'])->name('home');
    // السيارات
    Route::get('/cars/api/filter', [NewCarController::class, 'apiFilter'])->name('cars.api.filter');
    Route::get('/cars', [NewCarController::class,       'index'])->name('cars.index');
    Route::get('/cars/{slug}', [NewCarController::class, 'show'])->name('cars.show')->middleware('redirect-old-car');

    // العروض
    Route::get('/offers', [NewOfferController::class,     'index'])->name('offers.index')->middleware('page-cache:600');
    // المدونة
    Route::get('/blog', [NewBlogController::class,      'index'])->name('blog.index');
    Route::get('/blog/{slug}', [NewBlogController::class,      'show'])->name('blog.show');
    // حاسبة التقسيط
    Route::get('/calculator', [NewCalculatorController::class, 'index'])->name('calculator');
    Route::post('/calculator/lead', [NewCalculatorController::class, 'saveLead'])->name('calculator.lead');
    Route::get('/calculator/result', [NewCalculatorController::class, 'result'])->name('calculator.result');
    // مقارنة
    Route::get('/compare', [NewCompareController::class,   'index'])->name('compare');
    Route::get('/compare/search', [NewCompareController::class,   'search'])->name('compare.search');
    // صفحات ثابتة
    Route::get('/page/{page}', [NewHomeController::class, 'page'])->name('page');
    // من نحن
    Route::get('/about', [NewHomeController::class,      'about'])->name('about')->middleware('page-cache:600');
    // تواصل معنا
    Route::get('/contact', [NewContactController::class,   'index'])->name('contact');
    Route::post('/contact', [NewContactController::class,   'store'])->name('contact.store');
    // حجز سيارة
    Route::get('/booking', [NewBookingController::class, 'index'])->name('booking');
    Route::post('/booking', [NewBookingController::class, 'store'])->name('booking.store');
});

// =============================================
//  CRM (Admin Dashboard)
// =============================================
use App\Http\Controllers\CRM\AuthController;
use App\Http\Controllers\CRM\BlogController;
use App\Http\Controllers\CRM\BookingController;
use App\Http\Controllers\CRM\BrandController;
use App\Http\Controllers\CRM\CalculatorSettingsController;
use App\Http\Controllers\CRM\CarCategoryController;
use App\Http\Controllers\CRM\CarController;
use App\Http\Controllers\CRM\ContactSourceController;
use App\Http\Controllers\CRM\DashboardController;
use App\Http\Controllers\CRM\EmployeeController;
use App\Http\Controllers\CRM\FeatureController;
use App\Http\Controllers\CRM\GeneralSettingController;
use App\Http\Controllers\CRM\LeadController;
use App\Http\Controllers\CRM\NotificationController;
use App\Http\Controllers\CRM\OfferController;
use App\Http\Controllers\CRM\PartnerController;
use App\Http\Controllers\CRM\ProfileController;
use App\Http\Controllers\CRM\ProjectDesignController;
use App\Http\Controllers\CRM\ReportController;
use App\Http\Controllers\CRM\RoleController;
use App\Http\Controllers\CRM\SearchController;
use App\Http\Controllers\CRM\SpecificationController;
use App\Http\Controllers\CRM\TaskController;
use App\Http\Controllers\CRM\TestimonialController;
use App\Http\Controllers\CRM\TrackingController;

// Unique Secure Login
Route::get('/Souq-admin/login', [AuthController::class, 'showLoginForm'])->name('crm.login');
Route::post('/Souq-admin/login', [AuthController::class, 'login'])->name('crm.login.post');

Route::get('/Souq-admin', function () {
    return redirect()->route('crm.dashboard');
});

Route::prefix('Souq-admin')->name('crm.')->middleware(['auth:employee', 'guard.employee'])->group(function () {

    // === Open routes (accessible to all authenticated employees) ===
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/global-search', [SearchController::class, 'globalSearch'])->name('global-search');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // === المهام ===
    Route::middleware('permission:tasks.view')->group(function () {
        Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
        Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });

    // === تتبع الحالات (Kanban) ===
    Route::middleware('permission:tracking.view')->group(function () {
        Route::get('tracking', [TrackingController::class, 'index'])->name('tracking.index');
    });

    // === السيارات ===
    Route::middleware('permission:cars.view')->group(function () {
        Route::resource('cars', CarController::class);
        Route::get('cars/images/{image}/delete', [CarController::class, 'deleteImage'])->name('cars.delete-image');
    });

    // === المواصفات والمميزات ===
    Route::middleware('permission:specifications.view')->group(function () {
        Route::resource('specifications', SpecificationController::class);
    });
    Route::middleware('permission:features.view')->group(function () {
        Route::resource('features', FeatureController::class);
    });

    // === الماركات ===
    Route::middleware('permission:brands.view')->group(function () {
        Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
        Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
        Route::put('brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
    });

    // === تصنيفات السيارات ===
    Route::middleware('permission:categories.view')->group(function () {
        Route::get('car-categories', [CarCategoryController::class, 'index'])->name('car-categories.index');
        Route::post('car-categories', [CarCategoryController::class, 'store'])->name('car-categories.store');
        Route::put('car-categories/{carCategory}', [CarCategoryController::class, 'update'])->name('car-categories.update');
        Route::delete('car-categories/{carCategory}', [CarCategoryController::class, 'destroy'])->name('car-categories.destroy');
    });

    // === العملاء المحتملون + مصادر التواصل ===
    Route::middleware('permission:contacts.view')->group(function () {
        Route::resource('leads', LeadController::class);
        Route::get('contact-messages', [\App\Http\Controllers\CRM\ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::delete('contact-messages/{lead}', [\App\Http\Controllers\CRM\ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
    });
    Route::middleware('permission:contact-sources.view')->group(function () {
        Route::get('contact-sources', [ContactSourceController::class, 'index'])->name('contact-sources.index');
        Route::post('contact-sources', [ContactSourceController::class, 'store'])->name('contact-sources.store');
        Route::put('contact-sources/{contactSource}', [ContactSourceController::class, 'update'])->name('contact-sources.update');
        Route::delete('contact-sources/{contactSource}', [ContactSourceController::class, 'destroy'])->name('contact-sources.destroy');
    });

    // === تقارير منصة الحجز ===
    Route::middleware('permission:reports.view')->group(function () {
        Route::get('reports/bookings', [ReportController::class, 'bookings'])->name('reports.bookings');
        Route::get('reports/sources', [ReportController::class, 'sources'])->name('reports.sources');
        Route::get('reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');

        Route::post('reports/export-bookings', [ReportController::class, 'exportBookings'])->name('reports.export-bookings');
        Route::post('reports/export-monthly', [ReportController::class, 'exportMonthly'])->name('reports.export-monthly');
        Route::post('reports/export-sources', [ReportController::class, 'exportSources'])->name('reports.export-sources');
    });

    // === حاسبة التقسيط (إعدادات) ===
    Route::middleware('permission:calculator.view')->group(function () {
        Route::get('calculator', [CalculatorSettingsController::class, 'index'])->name('calculator.index');
        Route::post('calculator/banks', [CalculatorSettingsController::class, 'storeBank'])->name('calculator.banks.store');
        Route::put('calculator/banks/{calculatorBank}', [CalculatorSettingsController::class, 'updateBank'])->name('calculator.banks.update');
        Route::delete('calculator/banks/{calculatorBank}', [CalculatorSettingsController::class, 'destroyBank'])->name('calculator.banks.destroy');
        Route::post('calculator/factors', [CalculatorSettingsController::class, 'storeFactor'])->name('calculator.factors.store');
        Route::put('calculator/factors/{calculatorFactor}', [CalculatorSettingsController::class, 'updateFactor'])->name('calculator.factors.update');
        Route::delete('calculator/factors/{calculatorFactor}', [CalculatorSettingsController::class, 'destroyFactor'])->name('calculator.factors.destroy');
    });

    // === عملاء الحاسبة ===
    Route::middleware('permission:calculator-leads.view')->group(function () {
        Route::get('calculator-leads', [\App\Http\Controllers\CRM\CalculatorLeadController::class, 'index'])->name('calculator-leads.index');
        Route::delete('calculator-leads/{calculatorLead}', [\App\Http\Controllers\CRM\CalculatorLeadController::class, 'destroy'])->name('calculator-leads.destroy');
    });

    // === الطلبات (Leads) ===
    Route::middleware('permission:bookings.view')->group(function () {
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/new', [BookingController::class, 'index'])->name('bookings.new')->defaults('status', 'new');
        Route::get('bookings/inprogress', [BookingController::class, 'index'])->name('bookings.inprogress');
        Route::get('bookings/completed', [BookingController::class, 'index'])->name('bookings.completed');
        Route::post('bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
        Route::patch('bookings/{booking}/assign', [BookingController::class, 'assign'])->name('bookings.assign');
        Route::post('bookings/{booking}/note', [BookingController::class, 'addNote'])->name('bookings.note');
        Route::post('bookings/{booking}/documents', [BookingController::class, 'uploadDocument'])->name('bookings.documents.store');
        Route::delete('booking-documents/{document}', [BookingController::class, 'deleteDocument'])->name('bookings.documents.destroy');
        Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    });

    // === الموظفين ===
    Route::middleware('permission:users.view')->group(function () {
        Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

    // === الصلاحيات والأدوار ===
    Route::middleware('permission:roles.manage')->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // === العروض ===
    Route::middleware('permission:offers.view')->group(function () {
        Route::get('offers', [OfferController::class, 'index'])->name('offers.index');
        Route::post('offers', [OfferController::class, 'store'])->name('offers.store');
        Route::put('offers/{offer}', [OfferController::class, 'update'])->name('offers.update');
        Route::delete('offers/{offer}', [OfferController::class, 'destroy'])->name('offers.destroy');
    });

    // === المدونة ===
    Route::middleware('permission:blog.view')->group(function () {
        Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
        Route::get('blog/create', [BlogController::class, 'create'])->name('blog.create');
        Route::post('blog', [BlogController::class, 'store'])->name('blog.store');
        Route::get('blog/{blog}/edit', [BlogController::class, 'edit'])->name('blog.edit');
        Route::put('blog/{blog}', [BlogController::class, 'update'])->name('blog.update');
        Route::delete('blog/{blog}', [BlogController::class, 'destroy'])->name('blog.destroy');
    });

    // === إدارة الترجمة ===
    Route::middleware('permission:translations.view')->group(function () {
        Route::get('translations', [\App\Http\Controllers\CRM\TranslationController::class, 'index'])->name('translations.index');
        Route::post('translations', [\App\Http\Controllers\CRM\TranslationController::class, 'update'])->name('translations.update');
    });

    // === الإعدادات ===
    Route::prefix('settings')->name('settings.')->middleware('permission:settings.manage')->group(function () {
        Route::get('general', [GeneralSettingController::class, 'index'])->name('general');
        Route::get('seo', [GeneralSettingController::class, 'seo'])->name('seo');
        Route::post('update', [GeneralSettingController::class, 'update'])->name('update');
        Route::post('twilio-test', [GeneralSettingController::class, 'testTwilio'])->name('twilio.test');

        // التوصيات
        Route::middleware('permission:testimonials.view')->group(function () {
            Route::resource('testimonials', TestimonialController::class);
        });

        // الشركاء
        Route::middleware('permission:partners.view')->group(function () {
            Route::resource('partners', PartnerController::class);
        });

        // التصميمات
        Route::middleware('permission:designs.view')->group(function () {
            Route::resource('designs', ProjectDesignController::class);
            Route::post('designs/{projectDesign}/toggle-featured', [ProjectDesignController::class, 'toggleFeatured'])->name('designs.toggle-featured');
        });
    });
});

// =============================================
//  Redirects & Fallback
// =============================================
// إعادة توجيه /erp أو /crm إلى /Souq-admin/login لأي روابط قديمة
Route::get('/erp/{any?}', function () {
    return redirect('/Souq-admin/login', 301);
})->where('any', '.*');

Route::get('/crm/{any?}', function () {
    return redirect('/Souq-admin/login', 301);
})->where('any', '.*');

// Fallback 404
Route::fallback(function () {
    abort(404);
});
