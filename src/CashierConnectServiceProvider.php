<?php

namespace MeeeetDev\CashierConnect;

use Laravel\Cashier\Cashier;
use Illuminate\Support\ServiceProvider;
use MeeeetDev\CashierConnect\Console\ConnectWebhook;

/**
 * Service provider for the package.
 *
 * @package MeeeetDev\CashierConnect\Providers
 */
class CashierConnectServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->initializeMigrations();
        $this->initializePublishing();
        $this->initializeCommands();
        $this->setupRoutes();
        $this->setupConfig();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/cashierconnect.php',
            'cashierconnect'
        );
    }

    /**
     * Register the package migrations.
     *
     * @return void
     */
    protected function initializeMigrations()
    {
        if (property_exists(Cashier::class, 'runsMigrations') && Cashier::$runsMigrations && $this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function initializePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'cashier-connect-migrations');
            $this->publishes([
                __DIR__ . '/../database/migrations' => $this->app->databasePath('migrations/tenant'),
            ], 'cashier-connect-tenancy-migrations');
        }
    }

    /**
     * Register the package's console commands.
     *
     * @return void
     */
    protected function initializeCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ConnectWebhook::class
            ]);
        }
    }

    /**
     * Register the package's console commands.
     *
     * @return void
     */
    protected function setupRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/webhook.php');
    }

    /**
     * Register the package's config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/cashierconnect.php' => config_path('cashierconnect.php'),
        ]);
    }
}
