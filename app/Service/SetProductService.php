<?php

namespace App\Service;

use App\Http\Controllers\Product\GetProductController;
use App\Models\SetList;
use App\Repository\ProductRepository;
use App\Repository\SetListRepository;
use App\Repository\SetProductRepository;

class SetProductService
{
    protected SetProductRepository $setProductRepository;
    protected GetProductController $getProductController;
    protected ProductService $productService;
    protected SetListRepository $setListRepository;

    public function __construct(
        SetProductRepository $setProductRepository,
        GetProductController $getProductController,
        ProductService $productService,
        SetListRepository $setListRepository
    )
    {
        $this->setProductRepository = $setProductRepository;
        $this->getProductController = $getProductController;
        $this->productService = $productService;
        $this->setListRepository = $setListRepository;
    }

    public function manageProductList(SetList $setListItemDB, array $setProductList): void
    {
        $setListItemId = $setListItemDB->id;
        $setProductsDB = $this->setProductRepository->getSetProducts($setListItemId);
        if(!$setProductsDB->isEmpty()) {
            foreach ($setProductsDB as $setProductDB) {
                foreach ($setProductList as $key => $value) {
                    if($setProductDB->variant_id == $value['variant_id']) {
                        if($setProductDB->set_quantity != $value['count']) {
                            $setProductId = $setProductDB->id;
                            $this->setProductRepository->updateSetProduct($setProductId, ['quantity' => $value['quantity']]);
                            unset($setProductList[$key]);
                        }
                    }
                }
            }
        } else {

            foreach ($setProductList as $product) {
                $productDb = $this->productService->getOrCreateProduct($product);
                if($productDb) {
                    $createArr = [
                        'set_id' => $setListItemDB->id,
                        'product_id' => $productDb->id,
                        'variant_id' => $product['variant_id'],
                        'sku' => $productDb->sku,
                        'set_quantity' => $product['count'],
                    ];
                    $this->setProductRepository->addSetProduct($createArr);
                } else {
                    $this->setProductRepository->deleteSetBySetIdProduct($setListItemId);
                    $this->setListRepository->deleteSetList($setListItemId);
                    break;
                }

            }
        }
    }


}
