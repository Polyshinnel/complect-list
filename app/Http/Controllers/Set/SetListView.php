<?php

namespace App\Http\Controllers\Set;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SetListView extends BaseController
{
    public function __invoke(int $provider_id)
    {
        $setList = $this->service->getSetList($provider_id);
        return response()->json($setList);
    }
}
