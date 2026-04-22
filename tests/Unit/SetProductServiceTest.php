<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\SetList;
use App\Repository\SetProductRepository;
use App\Service\ProductService;
use App\Service\SetProductService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class SetProductServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_manage_product_list_deduplicates_same_sku_for_new_set(): void
    {
        $setProductRepository = Mockery::mock(SetProductRepository::class);
        $productService = Mockery::mock(ProductService::class);

        $productService
            ->shouldReceive('getOrCreateProduct')
            ->once()
            ->with('FineDesign', [
                'variant_id' => 2227,
                'sku' => 'LJ0000519',
                'count' => 2,
            ])
            ->andReturn(new Product([
                'id' => 848,
                'sku' => 'LJ0000519',
            ]));

        $service = new SetProductService($setProductRepository, $productService);

        $result = $service->manageProductList('FineDesign', [
            [
                'variant_id' => 2227,
                'sku' => 'LJ0000519',
                'count' => 2,
            ],
            [
                'variant_id' => 2772,
                'sku' => 'LJ0000519',
                'count' => 2,
            ],
            [
                'variant_id' => 2184,
                'sku' => 'LJ0000519',
                'count' => 2,
            ],
        ]);

        $this->assertSame([
            [
                'product_id' => 848,
                'variant_id' => 2227,
                'sku' => 'LJ0000519',
                'count' => 2,
            ],
        ], $result);
    }

    public function test_manage_product_list_does_not_rebuild_set_when_only_variant_id_changes(): void
    {
        $setProductRepository = Mockery::mock(SetProductRepository::class);
        $productService = Mockery::mock(ProductService::class);

        $setProductRepository
            ->shouldReceive('getSetProducts')
            ->once()
            ->with(1362)
            ->andReturn(new Collection([
                [
                    'set_id' => 1362,
                    'variant_id' => 2184,
                    'sku' => 'LJ0000519',
                    'set_quantity' => 2,
                ],
            ]));

        $setProductRepository->shouldReceive('deleteSetBySetIdProduct')->never();
        $setProductRepository->shouldReceive('addSetProduct')->never();
        $productService->shouldReceive('getOrCreateProduct')->never();

        $service = new SetProductService($setProductRepository, $productService);
        $setList = new SetList(['id' => 1362]);

        $result = $service->manageProductList('FineDesign', [
            [
                'variant_id' => 2772,
                'sku' => 'LJ0000519',
                'count' => 2,
            ],
        ], $setList);

        $this->assertSame([], $result);
    }

    public function test_manage_product_list_rebuilds_existing_set_when_quantity_changes(): void
    {
        $setProductRepository = Mockery::mock(SetProductRepository::class);
        $productService = Mockery::mock(ProductService::class);

        $setProductRepository
            ->shouldReceive('getSetProducts')
            ->once()
            ->with(1362)
            ->andReturn(new Collection([
                [
                    'set_id' => 1362,
                    'variant_id' => 2184,
                    'sku' => 'LJ0000519',
                    'set_quantity' => 1,
                ],
            ]));

        $productService
            ->shouldReceive('getOrCreateProduct')
            ->once()
            ->with('FineDesign', [
                'variant_id' => 2772,
                'sku' => 'LJ0000519',
                'count' => 2,
            ])
            ->andReturn(new Product([
                'id' => 848,
                'sku' => 'LJ0000519',
            ]));

        $setProductRepository
            ->shouldReceive('deleteSetBySetIdProduct')
            ->once()
            ->with(1362);

        $setProductRepository
            ->shouldReceive('addSetProduct')
            ->once()
            ->with([
                'set_id' => 1362,
                'product_id' => 848,
                'variant_id' => 2772,
                'sku' => 'LJ0000519',
                'set_quantity' => 2,
            ]);

        $service = new SetProductService($setProductRepository, $productService);
        $setList = new SetList(['id' => 1362]);

        $result = $service->manageProductList('FineDesign', [
            [
                'variant_id' => 2772,
                'sku' => 'LJ0000519',
                'count' => 2,
            ],
        ], $setList);

        $this->assertSame([], $result);
    }

    public function test_manage_product_list_rebuilds_existing_set_when_legacy_duplicates_exist_in_db(): void
    {
        $setProductRepository = Mockery::mock(SetProductRepository::class);
        $productService = Mockery::mock(ProductService::class);

        $setProductRepository
            ->shouldReceive('getSetProducts')
            ->once()
            ->with(1362)
            ->andReturn(new Collection([
                [
                    'set_id' => 1362,
                    'variant_id' => 2227,
                    'sku' => 'LJ0000519',
                    'set_quantity' => 2,
                ],
                [
                    'set_id' => 1362,
                    'variant_id' => 2772,
                    'sku' => 'LJ0000519',
                    'set_quantity' => 2,
                ],
            ]));

        $productService
            ->shouldReceive('getOrCreateProduct')
            ->once()
            ->with('FineDesign', [
                'variant_id' => 2184,
                'sku' => 'LJ0000519',
                'count' => 2,
            ])
            ->andReturn(new Product([
                'id' => 848,
                'sku' => 'LJ0000519',
            ]));

        $setProductRepository
            ->shouldReceive('deleteSetBySetIdProduct')
            ->once()
            ->with(1362);

        $setProductRepository
            ->shouldReceive('addSetProduct')
            ->once()
            ->with([
                'set_id' => 1362,
                'product_id' => 848,
                'variant_id' => 2184,
                'sku' => 'LJ0000519',
                'set_quantity' => 2,
            ]);

        $service = new SetProductService($setProductRepository, $productService);
        $setList = new SetList(['id' => 1362]);

        $result = $service->manageProductList('FineDesign', [
            [
                'variant_id' => 2184,
                'sku' => 'LJ0000519',
                'count' => 2,
            ],
        ], $setList);

        $this->assertSame([], $result);
    }
}
