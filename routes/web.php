<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ColorController as AdminColorController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SizeController as AdminSizeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\InquiryController;
use App\Http\Controllers\Public\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', HomeController::class)->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product:slug}/reserve', [InquiryController::class, 'create'])->name('inquiries.create');
Route::post('/products/{product:slug}/reserve', [InquiryController::class, 'store'])->name('inquiries.store');
Route::get('/about', fn () => Inertia::render('Public/About'))->name('about');
Route::get('/admin/login', fn () => redirect()->route('login'))->middleware('guest')->name('admin.login');

Route::redirect('/dashboard', '/admin')->middleware(['auth', 'verified', 'can:access-admin'])->name('dashboard');

Route::middleware(['auth', 'verified', 'can:access-admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::resource('products', AdminProductController::class);
        Route::resource('categories', AdminCategoryController::class)->except('show');
        Route::resource('sizes', AdminSizeController::class)->except('show');
        Route::resource('colors', AdminColorController::class)->except('show');
        Route::get('inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
        Route::get('inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
        Route::patch('inquiries/{inquiry}/status', [AdminInquiryController::class, 'updateStatus'])->name('inquiries.status');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
