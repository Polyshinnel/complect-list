<?php

namespace App\Service;

use App\Models\SetList;
use App\Models\SetProduct;
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
            $processedProductList = $this->compareAndUpdateProductList($setProductsDB, $setProductList, $provider);
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

    public function compareAndUpdateProductList(Collection $setProductsDB, array $setProductList, $provider): array
    {
        $diffProducts = [];

        if(!$setProductsDB->isEmpty()) {
            $setProductsDBArr = $setProductsDB->toArray();
            $setProductsDbIndexed = [];
            $incomingProductsIndexed = [];

            foreach ($setProductsDBArr as $dbProduct) {
                $key = $this->getProductKey($dbProduct['variant_id'], $dbProduct['sku']);
                $setProductsDbIndexed[$key] = $dbProduct;
            }

            foreach ($setProductList as $product) {
                $key = $this->getProductKey($product['variant_id'], $product['sku']);
                $incomingProductsIndexed[$key] = $product;
            }

            foreach ($incomingProductsIndexed as $key => $product) {
                if(!isset($setProductsDbIndexed[$key])) {
                    $diffProducts[] = $product;
                    continue;
                }

                $dbProduct = $setProductsDbIndexed[$key];
                $dbCount = (int)($dbProduct['set_quantity'] ?? 0);
                $incomingCount = (int)($product['count'] ?? 0);

                if($dbCount !== $incomingCount) {
                    $diffProducts[] = $product;
                }
            }

            foreach ($setProductsDbIndexed as $key => $dbProduct) {
                if(!isset($incomingProductsIndexed[$key])) {
                    $diffProducts[] = [
                        'variant_id' => (string)$dbProduct['variant_id'],
                        'sku' => $dbProduct['sku'],
                        'count' => (int)($dbProduct['set_quantity'] ?? 0)
                    ];
                }
            }

            if(!empty($diffProducts)) {
                $setId = $setProductsDB->first()->set_id;
                SetProduct::where('set_id', $setId)->delete();
                $setListItemDB = SetList::find($setId);
                $productListReturn = [];
                foreach ($setProductList as $product) {
                    $productDb = $this->productService->getOrCreateProduct($provider, $product);
                    if($productDb) {
                        $productListReturn[] = [
                            'product_id' => $productDb->id,
                            'variant_id' => $product['variant_id'],
                            'sku' => $productDb->sku,
                            'count' => $product['count']
                        ];
                    } else {
                        return ['error' => 'Product not found in ABC Service'];
                    }
                }

                $this->addProductsToSet($setListItemDB, $productListReturn);

            }
        }

        return $diffProducts;
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

    private function getProductKey($variantId, string $sku): string
    {
        return $sku . '_' . (string)$variantId;
    }
}
