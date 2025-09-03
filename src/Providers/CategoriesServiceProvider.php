<?php

declare(strict_types=1);

namespace Iutrace\Categories\Providers;

use Iutrace\Categories\Models\Category;
use Illuminate\Support\ServiceProvider;
use Iutrace\Support\Traits\ConsoleTools;
use Illuminate\Database\Eloquent\Relations\Relation;
use Iutrace\Categories\Console\Commands\MigrateCommand;
use Iutrace\Categories\Console\Commands\PublishCommand;
use Iutrace\Categories\Console\Commands\RollbackCommand;

class CategoriesServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class,
        PublishCommand::class,
        RollbackCommand::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'iutrace.categories');

        // Bind eloquent models to IoC container
        $this->registerModels([
            'iutrace.categories.category' => Category::class,
        ]);

        // Register console commands
        $this->commands($this->commands);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Register paths to be published by the publish command.
        $this->publishConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'iutrace/categories');
        $this->publishMigrationsFrom(realpath(__DIR__.'/../../database/migrations'), 'iutrace/categories');

        ! $this->app['config']['iutrace.categories.autoload_migrations'] || $this->loadMigrationsFrom(realpath(__DIR__.'/../../database/migrations'));

        // Map relations
        Relation::morphMap([
            'category' => config('iutrace.categories.models.category'),
        ]);
    }
}
