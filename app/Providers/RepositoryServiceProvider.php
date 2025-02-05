<?php

namespace App\Providers;

use App\Repositories\CurrencyRepository;
use App\Repositories\Interfaces\CurrencyRepositoryInterface;
use App\Repositories\Interfaces\QuotationRepositoryInterface;
use App\Repositories\QuotationRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(QuotationRepositoryInterface::class, QuotationRepository::class);
        $this->app->bind(CurrencyRepositoryInterface::class, CurrencyRepository::class);
    }
} 