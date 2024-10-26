<?php

namespace App\Console\Commands\SetList;

use App\Service\SetService;
use Illuminate\Console\Command;

class CalculatePriceQuantity extends Command
{
    public SetService $setService;

    public function __construct(SetService $setService)
    {
        parent::__construct();
        $this->setService = $setService;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set-list:calculate-price-quantity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Расчет цен и количества комплектов';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setService->calculateSetPriceAndQuantity();
    }
}
