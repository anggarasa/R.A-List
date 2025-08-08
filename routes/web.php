<?php

use App\Livewire\Job\ProjectDetail;
use App\Livewire\Job\ProjectList;
use App\Livewire\ListJobs\JobListView;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // job list route
    Route::get('job/project_lists', ProjectList::class)->name('job.project_list');
    Route::get('job/project_detail/{id}', ProjectDetail::class)->name('job.project_detail');

    // financial
    Route::get('financial', \App\Livewire\Financial\FinancialPage::class)->name('financial-page');

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
