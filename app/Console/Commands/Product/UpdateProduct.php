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
    public function handle()
    {
        $skuList = $this->productService->getDatabaseProductsSkuList();
        $products = $this->productRequest->getProductsBySku($skuList);
        if($this->productService->checkProductUpdates($products, 90)) {
            $this->productService->updateProducts($products);
            echo "products updated successfully\n";
        } else {
            Log::error('percent of products zero too high!');
            echo "products update failed\n";
        }

        return 0;
    }
}
