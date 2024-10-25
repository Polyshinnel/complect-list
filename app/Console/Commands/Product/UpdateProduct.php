<?php

namespace App\Console\Commands\Product;

use App\Http\Requests\ProductRequest;
use App\Service\ProductService;
use Illuminate\Console\Command;

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
        $this->productService->updateProducts($products);
        echo "products updated successfully\n";
        return 0;
    }
}
