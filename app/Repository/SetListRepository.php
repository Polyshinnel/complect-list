<?php

namespace App\Repository;

use App\Models\SetList;
use Illuminate\Support\Collection;

class SetListRepository
{
    public function getSetListItem(string $sku): ?SetList
    {
        $filter = [
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

    public function deleteSetList(int $id): void
    {
        SetList::where(['id' => $id])->delete();
    }

    public function getSetListAll(): ?Collection
    {
        return SetList::all();
    }
}
