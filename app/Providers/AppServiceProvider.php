<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\LoanDocument;
use App\Models\GoldLoanDocument;
use App\Models\DtrfDocument;
use App\Models\AccountOpeningDocument;
use App\Observers\DocumentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LoanDocument::observe(DocumentObserver::class);
        GoldLoanDocument::observe(DocumentObserver::class);
        DtrfDocument::observe(DocumentObserver::class);
        AccountOpeningDocument::observe(DocumentObserver::class);
    }
}
