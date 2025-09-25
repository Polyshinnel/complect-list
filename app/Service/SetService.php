<?php

namespace App\Service;

use App\Models\Provider;
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

    public function processingSetList(string $provider, array $setList): void
    {
        $setListItemDB = $this->checkSetDb($provider, $setList);
        $setProducts = $setList['items'];
        if(!$setListItemDB) {
            $productToSetArr = $this->setProductService->manageProductList($provider, $setProducts, $setListItemDB);
            if($productToSetArr) {
                if($setList['brand']) {
                    $setListItemDB = $this->addSetToDB($provider, $setList);
                    $this->setProductService->addProductsToSet($setListItemDB, $productToSetArr);
                }
            }
        } else {
            $this->setProductService->manageProductList($provider, $setProducts, $setListItemDB);
        }
    }

    public function checkSetDb(string $provider, array $setListItem): ?SetList
    {
        $provider = Provider::where('name', $provider)->first();
        $checkSetItem = $this
            ->setListRepository
            ->getSetListItem(
                $provider->id,
                $setListItem['set_sku']
            );
        if($checkSetItem) {
            return $checkSetItem;
        }
        return null;
    }

    public function addSetToDB(string $provider, array $setListItem): SetList
    {
        $provider = Provider::where('name', $provider)->first();
        $createArr = [
            'variant_id' => $setListItem['variant_id_set'],
            'name' => $setListItem['product_name'],
            'sku' => $setListItem['set_sku'],
            'brand' => $setListItem['brand'],
            'provider_id' => $provider->id
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
                $optPrice = 0;

                if(!$setListProducts->isEmpty()) {
                    foreach ($setListProducts as $setListProduct) {
                        $productInfo = $setListProduct->getProductInfo;
                        $setCurrentQuantity = floor($productInfo-> quantity/ $setListProduct->set_quantity);
                        $setPrice = ceil($productInfo->price * $setListProduct->set_quantity);
                        $setOptPrice = ceil($productInfo->opt_price * $setListProduct->set_quantity);

                        $productList[] = [
                            'quantity' => $productInfo->quantity,
                            'price' => $productInfo->price,
                            'set_required_quantity' => $setListProduct->set_quantity,
                            'set_price' => $setPrice,
                            'set_current_quantity' => (int)$setCurrentQuantity,
                            'set_opt_price' => $setOptPrice
                        ];
                        $price += $setPrice;
                        $optPrice += $setOptPrice;
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
                    'opt_price' => $optPrice
                ];
                $setListItem->update($updateArr);
            }
        }
    }

    public function getSetList(int $providerId): array
    {
        $setList = [];
        $setListCollection = $this->setListRepository->getSetListByProvider($providerId);
        if(!$setListCollection->isEmpty()) {
            foreach ($setListCollection as $setListItem) {
                $setList[] = [
                    'name' => $setListItem->name,
                    'price' => $setListItem->price,
                    'vendor_code' => $setListItem->sku,
                    'quantity' => $setListItem->quantity,
                    'vendor' => $setListItem->brand,
                    'opt_price' => $setListItem->opt_price,
                    'picture' => NULL,
                ];
            }
        }

        return $setList;
    }
}
