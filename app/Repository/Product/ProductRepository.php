<?php

namespace App\Repository\Product;

use App\Models\SetProduct;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function getSetProducts(int $setId): ?Collection
    {
        return SetProduct::where(['set_id' => $setId])->get();
    }

    public function updateSetProduct(int $productId, array $updateArr): void
    {
        SetProduct::where(['id' => $productId])->update($updateArr);
    }

    public function addSetProduct(array $createArr): void
    {
        SetProduct::insert($createArr);
    }

    public function deleteSetProduct(int $productId): void
    {
        SetProduct::where(['id' => $productId])->delete();
    }

    public function getProductById(int $productId): ?SetProduct
    {
        return SetProduct::find($productId);
    }
}
