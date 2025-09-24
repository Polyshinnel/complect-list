<?php

namespace App\Service;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Provider;
use App\Repository\ProductRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public ProductRepository $productRepository;
    public ProductRequest $productRequest;

    public function __construct(
        ProductRepository $productRepository,
        ProductRequest $productRequest
    ){
        $this->productRepository = $productRepository;
        $this->productRequest = $productRequest;
    }

    public function getOrCreateProduct($provider, $product): ?Product
    {
        $providerData = Provider::where('name', $provider)->first();
        $productInfo = $this->productRepository->getProductBySku($providerData->id, $product['sku']);
        if($productInfo){
            return $productInfo;
        } else {
            $productRaw = $this->productRequest->getProductsBySku($provider, [$product['sku']]);
            if($productRaw){
                $createArr = [
                    'name' => $productRaw[0]['name'],
                    'sku' => $productRaw[0]['vendor_code'],
                    'price' => $productRaw[0]['price'],
                    'quantity' => $productRaw[0]['quantity'],
                    'provider_id' => $providerData->id,
                ];
                return Product::create($createArr);
            } else {
                Log::error($product['sku'] . ' not found in ABC');
                return null;
            }

        }
    }

    public function getDatabaseProductsSkuList(): array
    {
        $products = $this->productRepository->getAllProducts();
        $sku = [];
        $providers = Provider::all();
        $providersMap = [];
        foreach($providers as $provider){
            $providersMap[$provider->id] = $provider->name;
        }
        if(!$products->isEmpty()){
            foreach($products as $product){
                $provider = $providersMap[$product->provider_id];
                $sku[$provider][] = $product->sku;
            }
        }

        return $sku;
    }

    public function updateProducts(string $provider, array $products): void
    {
        $provider = Provider::where('name', $provider)->first();
        if($products) {
            foreach($products as $product){
                $quantity = $product['quantity'];
                $price = $product['price'];
                $sku = $product['vendor_code'];
                $optPrice = $product['opt_price'];

                $productInfo = $this->productRepository->getProductBySku($provider->id, $sku);
                if($productInfo->quantity != $quantity || $productInfo->price != $price || $productInfo->opt_price != $optPrice){
                    $updateArr = [
                        'quantity' => $quantity,
                        'price' => $price,
                        'opt_price' => $optPrice
                    ];
                    $productInfo->update($updateArr);
                }
            }
        }
    }

    public function checkProductUpdates(array $products, int $percent): bool
    {
        $countProducts = count($products);
        $countProductsZero = 0;
        foreach ($products as $product){
            $quantity = $product['quantity'];
            if($quantity == 0){
                $countProductsZero++;
            }
        }

        $percentZero = ($countProductsZero / $countProducts) * 100;

        if($percentZero > $percent){
            return false;
        }
        return true;
    }
}
