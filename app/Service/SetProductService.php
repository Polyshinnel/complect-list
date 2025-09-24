<?php

namespace App\Service;

use App\Models\SetList;
use App\Repository\ProductRepository;
use App\Repository\SetListRepository;
use App\Repository\SetProductRepository;
use Illuminate\Support\Collection;

class SetProductService
{
    protected SetProductRepository $setProductRepository;
    protected ProductService $productService;
    protected SetListRepository $setListRepository;

    public function __construct(
        SetProductRepository $setProductRepository,
        ProductService $productService,
        SetListRepository $setListRepository
    )
    {
        $this->setProductRepository = $setProductRepository;
        $this->productService = $productService;
        $this->setListRepository = $setListRepository;
    }

    public function manageProductList(string $provider, array $setProductList, SetList $setListItemDB = null): array
    {
        $productListReturn = [];
        $processedProductList = [];
        if($setListItemDB) {
            $setListItemId = $setListItemDB->id;
            $setProductsDB = $this->setProductRepository->getSetProducts($setListItemId);
            $processedProductList = $this->compareAndUpdateProductList($setProductsDB, $setProductList);
        } else {
            $processedProductList = $setProductList;
        }

        if(!empty($processedProductList)) {
            foreach ($processedProductList as $product) {
                $productDb = $this->productService->getOrCreateProduct($provider, $product);
                if($productDb) {
                    $productListReturn[] = [
                        'product_id' => $productDb->id,
                        'variant_id' => $product['variant_id'],
                        'sku' => $productDb->sku,
                        'count' => $product['count']
                    ];
                } else {
                    return [];
                }
            }
        }

        if($productListReturn && $setListItemDB) {
            $this->addProductsToSet($setListItemDB, $productListReturn);
            return [];
        }

        return $productListReturn;
    }

    public function compareAndUpdateProductList(Collection $setProductsDB, array $setProductList): array
    {
        if(!$setProductsDB->isEmpty()) {
            foreach ($setProductsDB as $setProductDB) {
                foreach ($setProductList as $key => $value) {
                    if($setProductDB->variant_id == $value['variant_id']) {
                        if($setProductDB->set_quantity != $value['count']) {
                            $setProductId = $setProductDB->id;
                            $this->setProductRepository->updateSetProduct($setProductId, ['set_quantity' => $value['count']]);
                        }
                        unset($setProductList[$key]);
                    }
                }
            }

        }
        return $setProductList;
    }

    public function addProductsToSet(SetList $setListItemDB, array $products): void
    {
        foreach ($products as $item) {
            $createArr = [
                'set_id' => $setListItemDB->id,
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'],
                'sku' => $item['sku'],
                'set_quantity' => $item['count'],
            ];
            $this->setProductRepository->addSetProduct($createArr);
        }
    }
}
