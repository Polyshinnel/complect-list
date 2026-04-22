<?php

namespace App\Service;

use App\Models\SetList;
use App\Repository\SetProductRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SetProductService
{
    protected SetProductRepository $setProductRepository;
    protected ProductService $productService;

    public function __construct(
        SetProductRepository $setProductRepository,
        ProductService $productService
    )
    {
        $this->setProductRepository = $setProductRepository;
        $this->productService = $productService;
    }

    public function manageProductList(string $provider, array $setProductList, SetList $setListItemDB = null): array
    {
        $normalizedSetProductList = $this->normalizeIncomingProductList($setProductList);

        if($setListItemDB) {
            $setListItemId = $setListItemDB->id;
            $setProductsDB = $this->setProductRepository->getSetProducts($setListItemId);
            $this->compareAndUpdateProductList($setListItemDB, $setProductsDB, $normalizedSetProductList, $provider);

            return [];
        }

        $productListReturn = $this->buildPreparedProductList($provider, $normalizedSetProductList);
        if($productListReturn === null) {
            return [];
        }

        return $productListReturn;
    }

    public function compareAndUpdateProductList(
        SetList $setListItemDB,
        Collection $setProductsDB,
        array $setProductList,
        string $provider
    ): bool
    {
        if($setProductsDB->isEmpty()) {
            $productListReturn = $this->buildPreparedProductList($provider, $setProductList);
            if($productListReturn === null) {
                return false;
            }

            $this->addProductsToSet($setListItemDB, $productListReturn);

            return true;
        }

        [$setProductsDbIndexed, $dbHasDuplicates] = $this->normalizePersistedProductList($setProductsDB);
        $incomingProductsIndexed = $this->indexIncomingProductList($setProductList);

        if(!$dbHasDuplicates && !$this->hasProductDifferences($setProductsDbIndexed, $incomingProductsIndexed)) {
            return false;
        }

        $productListReturn = $this->buildPreparedProductList($provider, $setProductList);
        if($productListReturn === null) {
            return false;
        }

        $this->setProductRepository->deleteSetBySetIdProduct($setListItemDB->id);
        $this->addProductsToSet($setListItemDB, $productListReturn);

        return true;
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

    private function buildPreparedProductList(string $provider, array $setProductList): ?array
    {
        $productListReturn = [];

        foreach ($setProductList as $product) {
            $productDb = $this->productService->getOrCreateProduct($provider, $product);
            if(!$productDb) {
                return null;
            }

            $productListReturn[] = [
                'product_id' => $productDb->id,
                'variant_id' => $product['variant_id'],
                'sku' => $productDb->sku,
                'count' => $product['count']
            ];
        }

        return $productListReturn;
    }

    private function normalizeIncomingProductList(array $setProductList): array
    {
        $normalizedProducts = [];

        foreach ($setProductList as $product) {
            if(empty($product['sku'])) {
                continue;
            }

            $key = $this->getProductKey($product['sku']);
            $preparedProduct = [
                'variant_id' => (int)($product['variant_id'] ?? 0),
                'sku' => $product['sku'],
                'count' => (int)($product['count'] ?? 0),
            ];

            if(isset($normalizedProducts[$key])) {
                $existingProduct = $normalizedProducts[$key];
                if($existingProduct['count'] !== $preparedProduct['count']) {
                    Log::warning('Set item duplicated with different quantity. Keeping the first occurrence.', [
                        'sku' => $preparedProduct['sku'],
                        'kept_variant_id' => $existingProduct['variant_id'],
                        'discarded_variant_id' => $preparedProduct['variant_id'],
                        'kept_count' => $existingProduct['count'],
                        'discarded_count' => $preparedProduct['count'],
                    ]);
                }

                continue;
            }

            $normalizedProducts[$key] = $preparedProduct;
        }

        return array_values($normalizedProducts);
    }

    private function normalizePersistedProductList(Collection $setProductsDB): array
    {
        $normalizedProducts = [];
        $hasDuplicates = false;

        foreach ($setProductsDB as $dbProduct) {
            $sku = $dbProduct['sku'];
            $key = $this->getProductKey($sku);
            $preparedProduct = [
                'sku' => $sku,
                'count' => (int)($dbProduct['set_quantity'] ?? 0),
            ];

            if(isset($normalizedProducts[$key])) {
                $hasDuplicates = true;

                if($normalizedProducts[$key]['count'] !== $preparedProduct['count']) {
                    Log::warning('Persisted set contains duplicated SKU rows with different quantities.', [
                        'set_id' => $dbProduct['set_id'] ?? null,
                        'sku' => $sku,
                        'kept_count' => $normalizedProducts[$key]['count'],
                        'duplicate_count' => $preparedProduct['count'],
                    ]);
                }

                continue;
            }

            $normalizedProducts[$key] = $preparedProduct;
        }

        return [$normalizedProducts, $hasDuplicates];
    }

    private function indexIncomingProductList(array $setProductList): array
    {
        $indexedProducts = [];

        foreach ($setProductList as $product) {
            $indexedProducts[$this->getProductKey($product['sku'])] = [
                'sku' => $product['sku'],
                'count' => (int)($product['count'] ?? 0),
            ];
        }

        return $indexedProducts;
    }

    private function hasProductDifferences(array $setProductsDbIndexed, array $incomingProductsIndexed): bool
    {
        foreach ($incomingProductsIndexed as $key => $product) {
            if(!isset($setProductsDbIndexed[$key])) {
                return true;
            }

            $dbCount = (int)($setProductsDbIndexed[$key]['count'] ?? 0);
            $incomingCount = (int)($product['count'] ?? 0);

            if($dbCount !== $incomingCount) {
                return true;
            }
        }

        foreach ($setProductsDbIndexed as $key => $dbProduct) {
            if(!isset($incomingProductsIndexed[$key])) {
                return true;
            }
        }

        return false;
    }

    private function getProductKey(string $sku): string
    {
        return trim($sku);
    }
}
