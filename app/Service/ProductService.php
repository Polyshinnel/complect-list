<?php

namespace App\Service;

use App\Http\Controllers\Product\GetProductController;
use App\Models\Product;
use App\Repository\ProductRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public ProductRepository $productRepository;
    public GetProductController $getProductController;

    public function __construct(
        ProductRepository $productRepository,
        GetProductController $getProductController
    ){
        $this->productRepository = $productRepository;
        $this->getProductController = $getProductController;
    }

    public function getOrCreateProduct($product): ?Product
    {
        $productInfo = $this->productRepository->getProductBySku($product['sku']);
        if($productInfo){
            return $productInfo;
        } else {
            $productRaw = $this->getProductController->getProductsBySku([$product['sku']]);
            if($productRaw){
                $createArr = [
                    'name' => $productRaw[0]['name'],
                    'sku' => $productRaw[0]['vendor_code'],
                    'price' => $productRaw[0]['price'],
                    'quantity' => $productRaw[0]['quantity']
                ];
                return Product::create($createArr);
            } else {
                Log::error($product['sku'] . ' not found in ABC');
                return null;
            }

        }
    }

    public function getDatabaseProducts(): ?Collection
    {

    }
}
