<?php

namespace App\Repository;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository
{
    public function getProductBySku($sku): ?Product
    {
        return Product::where(['sku' => $sku])->first();
    }

    public function createProduct($createArr): Product
    {
        return Product::create($createArr);
    }

    public function updateProduct($sku, $updateArr): void
    {
        Product::where(['sku' => $sku])->update($updateArr);
    }

    public function getAllProducts(): ?Collection
    {
        return Product::all();
    }
}
