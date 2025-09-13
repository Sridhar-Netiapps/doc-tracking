<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\LoanDocument;
use App\Models\GoldLoanDocument;
use App\Models\DtrfDocument;
use Illuminate\Support\Facades\Event;
use App\Models\AccountOpeningDocument;
use App\Observers\DocumentObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Listeners\LogUserLogin;
use App\Listeners\LogUserLogout;

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
        Event::listen(Login::class, [LogUserLogin::class, 'handle']);
        Event::listen(Logout::class, [LogUserLogout::class, 'handle']);
        LoanDocument::observe(DocumentObserver::class);
        GoldLoanDocument::observe(DocumentObserver::class);
        DtrfDocument::observe(DocumentObserver::class);
        AccountOpeningDocument::observe(DocumentObserver::class);
    }
}
