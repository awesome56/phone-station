<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferSqliteData extends Command
{
    protected $signature = 'db:transfer-sqlite {--source= : Path to the sqlite file}';

    protected $description = 'Copy data from the legacy SQLite database into the MySQL database.';

    public function handle(): int
    {
        $path = $this->option('source') ?: database_path('database.sqlite');

        if (! file_exists($path)) {
            $this->error("SQLite file not found: {$path}");

            return self::FAILURE;
        }

        config()->set('database.connections.sqlite_source', [
            'driver' => 'sqlite',
            'database' => $path,
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        $source = DB::connection('sqlite_source');
        $target = DB::connection('mysql');

        $tables = ['permissions', 'categories', 'users', 'role_permissions', 'products', 'orders', 'order_items'];

        foreach ($tables as $table) {
            $count = $source->table($table)->count();

            if ($count === 0) {
                $this->info("{$table}: empty, skipping.");
                continue;
            }

            $this->withProgressBar($source->table($table)->orderBy('id')->cursor(), function ($row) use ($target, $table) {
                $target->table($table)->insert((array) $row);
            });

            $this->newLine();
            $this->info("{$table}: {$count} rows transferred.");
        }

        $this->info('Done. Verify with: SELECT COUNT(*) FROM orders;');

        return self::SUCCESS;
    }
}
