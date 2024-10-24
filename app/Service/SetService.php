<?php

namespace App\Service;

use App\Models\SetList;
use App\Repository\SetListRepository;

class SetService
{
    protected SetListRepository $setListRepository;
    protected SetProductService $setProductService;

    public function __construct(
        SetListRepository $setListRepository,
        SetProductService $setProductService
    )
    {
        $this->setListRepository = $setListRepository;
        $this->setProductService = $setProductService;
    }

    public function processingSetList(array $setList): void
    {
        $setListItemDB = $this->checkSetDb($setList);
        $setProducts = $setList['items'];
        if(!$setListItemDB) {
            $setListItemDB = $this->addSetToDB($setList);
        }
        $this->setProductService->manageProductList($setListItemDB, $setProducts);
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

    public function addSetToDB(array $setListItem): SetList
    {
        $createArr = [
            'variant_id' => $setListItem['variant_id_set'],
            'name' => $setListItem['product_name'],
            'sku' => $setListItem['set_sku']
        ];

        return $this->setListRepository->createSetList($createArr);
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
