<?php

namespace App\Http\Controllers\Set;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SetListView extends BaseController
{
    public function __invoke()
    {
        $setList = $this->service->getSetList();
        return response()->json($setList);
    }
}
