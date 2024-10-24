<?php

namespace App\Http\Controllers\Set;

use App\Http\Controllers\Controller;
use App\Service\SetService;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public SetService $service;

    public function __construct(SetService $service){
        $this->service = $service;
    }
}
