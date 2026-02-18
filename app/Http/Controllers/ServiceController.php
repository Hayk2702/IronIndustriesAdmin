<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceShowRequest;
use App\Http\Requests\ServiceStoreRequest;
use App\Services\ServiceService;

class ServiceController extends Controller
{
    public function __construct(private ServiceService $serviceService) {}

    public function index(ServiceShowRequest $request)
    {
        return $this->serviceService->show($request);
    }

    public function store(ServiceStoreRequest $request)
    {
        return $this->serviceService->store($request);
    }

    public function destroy($id)
    {
        return $this->serviceService->destroy($id);
    }

    public function deleteImage($serviceId, $imageId)
    {
        return $this->serviceService->deleteImage($serviceId, $imageId);
    }
}
