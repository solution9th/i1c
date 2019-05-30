<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init application and init Databases';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('key:generate');

        $db = config('database.connections.mysql.database');

        if (DB::connection()->statement("create database if not exists {$db}")) {
            // 设置数据库连接
            config(["database.connections.{$db}" => array_merge(config('database.connections.mysql'), ['database' => $db])]);
            $this->call('migrate', ['--database' => $db, '--force' => true]);
        }

        $this->call('passport:install');
        
        return $this->info('Init application success');
    }
}
