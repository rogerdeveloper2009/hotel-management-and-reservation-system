<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportMysqlSchema extends Command
{
    protected $signature = 'db:export-mysql {--output=database/mysql-schema.sql}';
    protected $description = 'Generate a MySQL-compatible schema SQL file with fresh password hashes';

    public function handle(): int
    {
        $outputPath = $this->option('output');
        $password = 'ChangeMe123!';
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $schema = file_get_contents(database_path('mysql-schema.sql'));

        $schema = str_replace('__PASSWORD_HASH__', $hash, $schema);

        file_put_contents($outputPath, $schema);

        $this->info("MySQL schema exported to: {$outputPath}");
        $this->warn("Password hash regenerated for: {$password}");

        return self::SUCCESS;
    }
}
