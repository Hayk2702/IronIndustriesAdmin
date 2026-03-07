<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryReorderRequest;
use App\Http\Requests\CategoryShowRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function index(CategoryShowRequest $request)
    {
        return $this->categoryService->show($request);
    }

    public function store(CategoryStoreRequest $request)
    {
        return $this->categoryService->store($request);
    }

    public function destroy($id)
    {
        return $this->categoryService->destroy($id);
    }

    public function deleteImage($serviceId, $imageId)
    {
        return $this->categoryService->deleteImage($serviceId, $imageId);
    }

    public function updatePositions(CategoryReorderRequest $request)
    {
        return $this->categoryService->reorder($request->validated()['items']);
    }
}
