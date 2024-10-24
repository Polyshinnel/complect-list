<?php

namespace App\Console\Commands\SetList;

use App\Http\Controllers\Set\BaseController;
use App\Http\Controllers\Set\SetStoreController;
use App\Service\SetService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetSetList extends Command
{
    public SetService $service;

    public function __construct(SetService $service)
    {
        parent::__construct();
        $this->service = $service;
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
    protected $description = 'Get set list';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $message = 'set list successfully saved';
        $setList = $this->getSetList();
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

    public function getSetList() {
        $ch = curl_init('https://liberty-jones.ru/index.php?module=ListSetFDView&key=aZBcNu0V8mXA');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        if($result){
            return json_decode($result, true);
        } else {
            $error_msg = curl_error($ch);
            Log::error($error_msg);
            return null;
        }
    }
}
