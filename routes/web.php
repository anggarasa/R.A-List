<?php

use App\Livewire\Dashboard;
use App\Livewire\Financial\FianancialReportPage;
use App\Livewire\Financial\FinancialAccountPage;
use App\Livewire\Financial\FinancialBudgetPage;
use App\Livewire\Financial\FinancialCategoryPage;
use App\Livewire\Financial\FinancialGoalsPage;
use App\Livewire\Job\ProjectList;
use App\Livewire\Settings\Profile;
use App\Livewire\Job\ProjectDetail;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\ListJobs\JobListView;
use App\Livewire\Financial\FinancialPage;
use App\Livewire\Financial\FinancialTransactionPage;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    // job list route
    Route::get('job/project_lists', ProjectList::class)->name('job.project_list');
    Route::get('job/project_detail/{id}', ProjectDetail::class)->name('job.project_detail');

    // financial
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('dashboard', FinancialPage::class)->name('dashboard');
        Route::get('categories', FinancialCategoryPage::class)->name('category');
        Route::get('accounts', FinancialAccountPage::class)->name('account');
        Route::get('transactions', FinancialTransactionPage::class)->name('transaction');
        Route::get('budgets', FinancialBudgetPage::class)->name('budget');
        Route::get('goals', FinancialGoalsPage::class)->name('goal');
        Route::get('report', FianancialReportPage::class)->name('report');
    });

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
