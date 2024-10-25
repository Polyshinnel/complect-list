<?php

namespace App\Console\Commands\SetList;

use App\Http\Controllers\Set\BaseController;
use App\Http\Controllers\Set\SetStoreController;
use App\Http\Requests\SetListRequest;
use App\Service\SetService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetSetList extends Command
{
    public SetService $service;
    public SetListRequest $listRequest;

    public function __construct(
        SetService $service,
        SetListRequest $listRequest
    )
    {
        parent::__construct();
        $this->service = $service;
        $this->listRequest = $listRequest;
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
        $setList = $this->listRequest->getSetList();
        if($setList){
            foreach($setList as $set){
                $this->service->processingSetList($set);
            }
        } else {
            $message = 'set list empty';
        }

        echo $message."\r\n";
        return 0;
    }
}
