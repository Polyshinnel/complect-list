<?php

namespace App\Repository;

use App\Models\SetProduct;
use Illuminate\Database\Eloquent\Collection;

class SetProductRepository
{
    public function getSetProducts(int $setId): ?Collection
    {
        return SetProduct::where(['set_id' => $setId])->get();
    }

    public function updateSetProduct(int $productId, array $updateArr): void
    {
        SetProduct::where(['id' => $productId])->update($updateArr);
    }

    public function addSetProduct(array $data)
    {
        // Проверяем существование записи
        $exists = SetProduct::where('set_id', $data['set_id'])
            ->where('variant_id', $data['variant_id'])
            ->exists();
        
        if (!$exists) {
            return SetProduct::create($data);
        }
    }

    public function deleteSetBySetIdProduct(int $setId): void
    {
        SetProduct::where(['set_id' => $setId])->delete();
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
