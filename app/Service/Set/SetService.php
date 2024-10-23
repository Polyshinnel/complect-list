<?php

namespace App\Service\Set;

use App\Models\SetList;
use App\Repository\Set\SetListRepository;

class SetService
{
    protected SetListRepository $setListRepository;

    public function __construct(SetListRepository $setListRepository)
    {
        $this->setListRepository = $setListRepository;
    }

    public function checkSetDb(array $setListItem): ?SetList
    {
        $checkSetItem = $this
            ->setListRepository
            ->getSetListItem(
                $setListItem['variant_id_set'],
                $setListItem['set_sku'],
            );
        if($checkSetItem) {
            return $checkSetItem;
        }
        return null;
    }

    public function addSetToDB(array $setListItem): int
    {
        $createArr = [
            'variant_id' => $setListItem['variant_id_set'],
            'name' => $setListItem['product_name'],
            'sku' => $setListItem['set_sku']
        ];

        $setItem = $this->setListRepository->createSetList($createArr);
        return $setItem->id;
    }

    public function updateSetPriceQuantity(float $price, int $quantity, string $setItemSku): void
    {
        $updateArr = [
            'quantity' => $quantity,
            'price' => $price,
        ];

        $this->setListRepository->updateSetList($setItemSku, $updateArr);
    }
}
