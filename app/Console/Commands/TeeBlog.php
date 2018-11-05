<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TeeBlog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TeeBlog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建数据库及生成测试数据';

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
        //
    }
}
