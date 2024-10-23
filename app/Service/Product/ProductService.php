<?php

namespace App\Service\Product;

use App\Models\SetList;
use App\Repository\Product\ProductRepository;

class ProductService
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function compareAndSetProductList(SetList $setListItem, array $setListItemsArray)
    {
        $setListItemId = $setListItem->id;
        $setProducts = $this->productRepository->getSetProducts($setListItemId);
        if(!$setProducts->isEmpty()) {

        }
    }
}
