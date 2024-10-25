<?php

namespace App\Console\Commands\Product;

use Illuminate\Console\Command;

class UpdateProduct extends Command
{
    public function __construct()
    {
        parent::__construct();

    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set-list:update-product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
