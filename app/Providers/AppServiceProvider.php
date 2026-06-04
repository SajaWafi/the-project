<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 1. ضيفي السطر هذا هنا فوق
use Illuminate\Pagination\Paginator; 

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
        // 2. ضيفي السطر هذا داخل دالة البوت
        Paginator::useBootstrapFive(); 
        
        // (ملاحظة: لو ما خدمتش، جربي Paginator::useBootstrap(); بدلها)
    }
}
