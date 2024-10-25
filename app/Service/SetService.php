<?php

namespace App\Service;

use App\Models\SetList;
use App\Repository\SetListRepository;
use App\Repository\SetProductRepository;

class SetService
{
    protected SetListRepository $setListRepository;
    protected SetProductService $setProductService;
    protected SetProductRepository $setProductRepository;

    public function __construct(
        SetListRepository $setListRepository,
        SetProductService $setProductService,
        SetProductRepository $setProductRepository
    )
    {
        $this->setListRepository = $setListRepository;
        $this->setProductService = $setProductService;
        $this->setProductRepository = $setProductRepository;
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

    public function calculateSetPriceAndQuantity(): void
    {
        $setList = $this->setListRepository->getSetListAll();
        if(!$setList->isEmpty()) {
            foreach ($setList as $setListItem) {
                $setListProducts = $this->setProductRepository->getSetProducts($setListItem->id);
                $quantity = 0;
                $price = 0;
                $productList = [];

                if(!$setListProducts->isEmpty()) {
                    foreach ($setListProducts as $setListProduct) {
                        $productInfo = $setListProduct->getProductInfo;
                        $setCurrentQuantity = floor($productInfo-> quantity/ $setListProduct->set_quantity);
                        $setPrice = ceil($productInfo->price * $setListProduct->set_quantity);
                        $productList[] = [
                            'quantity' => $productInfo->quantity,
                            'price' => $productInfo->price,
                            'set_required_quantity' => $setListProduct->set_quantity,
                            'set_price' => $setPrice,
                            'set_current_quantity' => (int)$setCurrentQuantity,
                        ];
                        $price += $setPrice;
                    }
                }

                if($productList) {
                    usort($productList, function ($a, $b) {
                        if ($a['set_current_quantity'] == $b['set_current_quantity']) return 0;
                        return ($a['set_current_quantity'] > $b['set_current_quantity']) ? 1 : -1;
                    });
                    $quantity = $productList[0]['set_current_quantity'];
                }

                $updateArr = [
                    'price' => $price,
                    'quantity' => $quantity,
                ];
                $setListItem->update($updateArr);
            }
        }
    }
}
