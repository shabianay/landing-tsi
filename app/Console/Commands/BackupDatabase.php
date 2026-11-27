<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Throwable;

class BackupDatabase extends Command
{
    protected $signature = 'database:backup';

    protected $description = 'Create a private SQL backup of the application database';

    public function handle(): int
    {
        if (! function_exists('proc_open')) {
            $this->error('proc_open is disabled on this PHP installation. Use scripts/backup-database.sh instead.');

            return self::FAILURE;
        }

        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");

        if (! in_array($connection, ['mysql', 'mariadb'], true) || ! $database) {
            $this->error('Database backups require a configured MySQL or MariaDB connection.');

            return self::FAILURE;
        }

        $directory = storage_path('app/private/backups');

        if (! is_dir($directory) && ! mkdir($directory, 0700, true) && ! is_dir($directory)) {
            $this->error("Could not create backup directory: {$directory}");

            return self::FAILURE;
        }

        $filename = 'database-'.now()->format('Y-m-d-His-u').'.sql';
        $backupPath = $directory.DIRECTORY_SEPARATOR.$filename;
        $temporaryPath = $backupPath.'.partial';
        $errorOutput = '';
        $process = new Process([
            config("database.connections.{$connection}.dump_binary", 'mysqldump'),
            '--single-transaction',
            '--skip-lock-tables',
            '--host='.config("database.connections.{$connection}.host"),
            '--port='.config("database.connections.{$connection}.port"),
            '--user='.config("database.connections.{$connection}.username"),
            '--default-character-set=utf8mb4',
            $database,
        ], base_path(), ['MYSQL_PWD' => config("database.connections.{$connection}.password")]);

        try {
            $file = fopen($temporaryPath, 'xb');

            if ($file === false) {
                throw new \RuntimeException("Could not create backup file: {$temporaryPath}");
            }

            try {
                $process->setTimeout(600);
                $process->run(function (string $type, string $buffer) use ($file, &$errorOutput): void {
                    if ($type === Process::ERR) {
                        $errorOutput .= $buffer;
                    } elseif (fwrite($file, $buffer) !== strlen($buffer)) {
                        throw new \RuntimeException('Could not write the complete database backup.');
                    }
                });
            } finally {
                fclose($file);
            }

            if (! $process->isSuccessful()) {
                throw new \RuntimeException(trim($errorOutput) ?: 'The database dump process failed.');
            }

            $size = filesize($temporaryPath);

            if ($size === false || $size === 0 || ! rename($temporaryPath, $backupPath)) {
                throw new \RuntimeException('The database dump was empty or could not be finalized.');
            }

            if (PHP_OS_FAMILY !== 'Windows' && ! chmod($backupPath, 0600)) {
                throw new \RuntimeException('Could not restrict backup file permissions.');
            }

            $this->info("Database backup created: {$backupPath}");

            return self::SUCCESS;
        } catch (Throwable $exception) {
            if (is_file($temporaryPath) && ! unlink($temporaryPath)) {
                $this->error("Could not remove incomplete backup: {$temporaryPath}");
            }

            $this->error('Database backup failed: '.$exception->getMessage());

            return self::FAILURE;
        }
    }
}
