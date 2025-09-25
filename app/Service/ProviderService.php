<?php

namespace App\Service;

use App\Models\Provider;
use App\Models\ProviderBrands;

class ProviderService
{
    private function getPreparedBrands(): array
    {
        $preparedBrands = [];
        $preparedProviders = [];
        $providers = Provider::all();
        $providerBrands = ProviderBrands::all();

        if(!$providers->isEmpty()) {
            foreach($providers as $provider) {
                $preparedProviders[$provider->id] = $provider->name;
            }
        }

        if(!$providerBrands->isEmpty())
        {
            foreach($providerBrands as $providerBrand)
            {
                $preparedBrands[$providerBrand->normalized_name] = [
                    'name' => $providerBrand->name,
                    'provider_id' => $providerBrand->provider_id,
                    'provider_name' => $preparedProviders[$providerBrand->provider_id]
                ];
            }
        }

        return $preparedBrands;
    }

    public function addProductsToProvidersArray($products): array
    {
        $preparedBrands = $this->getPreparedBrands();
        $providersArray = [];

        foreach($products as $product) {
            $brand = $product['brand'];
            $brand = strtolower($brand);
            if(isset($preparedBrands[$brand])) {
                $providerName = $preparedBrands[$brand]['provider_name'];
                $providersArray[$providerName][] = $product;
            }
        }

        return $providersArray;
    }
}
