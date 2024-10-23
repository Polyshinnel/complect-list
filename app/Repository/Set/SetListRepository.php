<?php

namespace App\Repository\Set;

use App\Models\SetList;

class SetListRepository
{
    public function getSetListItem(int $variantId, string $sku): ?SetList
    {
        $filter = [
            'variant_id' => $variantId,
            'sku' => $sku
        ];
        return SetList::where($filter)->first();
    }

    public function createSetList(array $createArr): SetList
    {
        return SetList::create($createArr);
    }

    public function updateSetList(string $sku, array $updateArr): void
    {
        SetList::where('sku', $sku)->update($updateArr);
    }
}
