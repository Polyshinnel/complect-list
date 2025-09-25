<?php

namespace App\Console\Commands\Product;

use App\Http\Requests\ProductRequest;
use App\Service\ProductService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateProduct extends Command
{
    public ProductService $productService;
    public ProductRequest $productRequest;
    public function __construct(
        ProductService $productService,
        ProductRequest $productRequest
    )
    {
        parent::__construct();
        $this->productService = $productService;
        $this->productRequest = $productRequest;
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
    protected $description = 'Обновление товаров';

    /**
     * Execute the console command.
     */
    public function handle():int
    {
        $skuList = $this->productService->getDatabaseProductsSkuList();
        foreach ($skuList as $provider => $sku) {
            $products = $this->productRequest->getProductsBySku($provider, $sku);
            $this->productService->updateProducts($provider, $products);
        }
        $this->info('Обновление товаров завершено');
        return 0;
    }
}
