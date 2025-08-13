<?php

use App\Livewire\Financial\FinancialCategoryPage;
use App\Livewire\Job\ProjectList;
use App\Livewire\Settings\Profile;
use App\Livewire\Job\ProjectDetail;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\ListJobs\JobListView;
use App\Livewire\Financial\FinancialPage;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // job list route
    Route::get('job/project_lists', ProjectList::class)->name('job.project_list');
    Route::get('job/project_detail/{id}', ProjectDetail::class)->name('job.project_detail');

    // financial
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('dashboard', FinancialPage::class)->name('dashboard');
        Route::get('category', FinancialCategoryPage::class)->name('category');
    });

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
