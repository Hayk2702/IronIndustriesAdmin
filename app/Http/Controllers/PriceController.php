<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceDestroyRequest;
use App\Http\Requests\PriceShowRequest;
use App\Http\Requests\PriceStoreRequest;
use App\Services\PriceService;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function __construct(private PriceService $priceService){}

    public function index(PriceShowRequest $request)
    {
        return $this->priceService->show($request);
    }

    public function store(PriceStoreRequest $request)
    {
        return $this->priceService->store($request);
    }

    public function destroy(PriceDestroyRequest $request, $id)
    {
        return $this->priceService->destroy($id);
    }
}
