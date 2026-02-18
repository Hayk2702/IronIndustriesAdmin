<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductDestroyRequest;
use App\Http\Requests\ProductShowRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService){}

    public function index(ProductShowRequest $request)
    {
        return $this->productService->show($request);
    }

    public function store(ProductStoreRequest $request)
    {
        return $this->productService->store($request);
    }

    public function destroy(ProductDestroyRequest $request, $id)
    {
        return $this->productService->destroy($id);
    }
}
