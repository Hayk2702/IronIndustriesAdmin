<?php

namespace App\Http\Controllers;

use App\Http\Requests\AboutUsShowRequest;
use App\Http\Requests\AboutUsStoreRequest;
use App\Services\AboutUsService;

class AboutUsController extends Controller
{
    public function __construct(private AboutUsService $service){}

    public function index(AboutUsShowRequest $request)
    {
        return $this->service->show($request);
    }

    public function store(AboutUsStoreRequest $request)
    {
        return $this->service->store($request);
    }
}
