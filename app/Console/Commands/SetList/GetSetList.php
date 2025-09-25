<?php

namespace App\Console\Commands\SetList;

use App\Http\Controllers\Set\BaseController;
use App\Http\Controllers\Set\SetStoreController;
use App\Http\Requests\SetListRequest;
use App\Service\ProviderService;
use App\Service\SetService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetSetList extends Command
{
    public SetService $service;
    public SetListRequest $listRequest;
    public ProviderService  $providerService;

    public function __construct(
        SetService $service,
        SetListRequest $listRequest,
        ProviderService $providerService
    )
    {
        parent::__construct();
        $this->service = $service;
        $this->listRequest = $listRequest;
        $this->providerService = $providerService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set-list:get-set-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получение и обновление списка комплектов';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $message = 'set list successfully saved';
        $setLists = [];
        $rawSetList = $this->listRequest->getFdSetListRequest();

        $preparedSetList = $this->providerService->addProductsToProvidersArray($rawSetList);

        if(!$preparedSetList) {
            $this->error('Ошибка при получении списка комплектов');
            return 1;
        }
        foreach($preparedSetList as $provider => $sets){
            foreach($sets as $set){
                $this->service->processingSetList($provider, $set);
            }
        }

        $this->info($message);
        return 0;
    }
}
