<?php

declare(strict_types=1);

namespace Iutrace\Categories\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'iutrace:rollback:categories')]
class RollbackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iutrace:rollback:categories {--f|force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback iutrace Categories Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $path = config('iutrace.categories.autoload_migrations') ?
            'vendor/iutrace/laravel-categories/database/migrations' :
            'database/migrations/iutrace/laravel-categories';

        if (file_exists($path)) {
            $this->call('migrate:reset', [
                '--path' => $path,
                '--force' => $this->option('force'),
            ]);
        } else {
            $this->warn('No migrations found! Consider publish them first: <fg=green>php artisan iutrace:publish:categories</>');
        }

        $this->line('');
    }
}
